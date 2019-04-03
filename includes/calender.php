<?php
function generate_calendar($cat_id, $year, $month, $evday, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array()){
    global $db, $cm, $bdir;
    $calendar = '';
        
    $first_of_month = mktime(0,0,0,$month,1,$year); //This is actual code which is correct. But the spain server may have spme error and pick the last date of the previous month and not the 1st date of the current month.
    $pd = $year . "-" . $month . "-15";
    $ptm = strtotime($pd);
    $prev_d = $ptm - (30*24*60*60);
    $next_d = $ptm + (30*24*60*60);
    //$first_of_month = gmmktime(0,0,0,$month,1,$year)+(24*60*60);

    $day_names = array(); #generate all the day names according to the current locale

    for($n=0,$t=(4+$first_day)*86400; $n<7; $n++,$t+=86400){ #January 4, 1970 was a Sunday
       $day_names[$n] = ucfirst(strftime('%A',$t)); #%A means full textual day name
    }

    list($month, $year, $month_name, $weekday) = explode(',',strftime('%m,%Y,%B,%w',$first_of_month));

    $weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
    $title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

    $calendar .= '
    <div class="calendar-month"><a class="c_nextprev" gmn="'. date("m", $prev_d) .'" gyr="'. date("Y", $prev_d) .'" calop="2" href="javascript:void(0);"><img src="'. $bdir .'images/month-arrow-prev.png" alt="" class="month-arrow-prev" /></a>'. date("F", $first_of_month) .' - '. date("Y",$first_of_month) .' <a class="c_nextprev" gmn="'. date("m", $next_d) .'" gyr="'. date("Y", $next_d) .'" calop="2" href="javascript:void(0);"><img src="'. $bdir .'images/month-arrow-next.png" alt="" class="month-arrow-next" /></a></div>
    <br clear="all" />
    ';

    $calendar .= '<table class="calendar" border="0" cellspacing="2" cellpadding="0" width="100%">';
    $calendar .= '<tr class="day">';
    if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
        #if day_name_length is >3, the full name of the day will be printed
        $pd = 0;
        foreach($day_names as $d){
            $calendar .= '<td align="center">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</td>';
            $calendar .= "</td>\n";
            $pd = 1;
        }
    }
    $calendar .= '</tr>';

    $calendar .= '<tr>';
    if($weekday > 0){
        for($kk = 0; $kk < $weekday; $kk++){
            $calendar .= '<td align="center"><img src="'.$bdir.'images/spacer.gif" border="0"></td>'; #initial 'empty' days
        }
    }

    $high_slide = 0;
    for($day=1,$days_in_month=date('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){

        if($weekday == 7){
            $weekday   = 0; #start a new week
            $calendar .= "</tr><tr>";
        }

        $present_dt = date("Y",$first_of_month)."-".date("m",$first_of_month)."-".$day;
        $wclick = $day;

        $tdcls = "";
        //if (date("d") == $day AND date("m") == $month AND date("Y") == $year) { $tdcls = ' class="currend-date"'; }

        $query_sql = "select distinct a.id";
        $query_form = " from tbl_class as a,";
        $query_where = " where";
        $query_where .= " a.sdate <= '". $present_dt."' and a.edate >= '". $present_dt."' and";

        $query_where .= " a.status_id = 1";
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");
        $sql = $query_sql . $query_form . $query_where;
        $result = $db->fetch_all_array($sql);
        $found = count($result);


        if ($found > 0){
            $wclick = '
                <a href="javascript:void(0);" class="eventactive"  fordate="'. $present_dt .'">' . $day. '</a>
            ';
            $tdcls = ' class="currend-date"';
        }else{
            $wclick = $day;
        }

        $calendar .= '<td'. $tdcls .' align="center">';
        $calendar .= $wclick;
        $calendar .= '</td>';
    }



    if($weekday != 7){
        $rem_td = 7-$weekday;
        for($kk = 0; $kk < $rem_td; $kk++){
            $calendar .= '<td ><img src="'.$bdir.'images/spacer.gif" border="0"></td>'; #initial 'empty' days
        }
    }
    $calendar .= '</tr></table>';
    return $calendar;
}
?>
