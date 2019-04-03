<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New User";
$ms = round($_GET["id"], 0);
$datastring = $cm->session_field_user();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
 ${$key} = $val;
}

$old_status_id = 0;
$old_d_username = $old_d_email = "";
$type_id = $parent_id = $country_id = 0;
$front_display = 0;
$admin_access = 0;
$yw_agent = 0;
$display_title = 0;
$yw_broker_id = "";
$display_listings = 0;
$support_crew = 0;
$marketing_staff = 0;

$b_admin = $b_company = $b_location = $b_agent = ' com_none';
$in_asso = $in_cert = '';
$social_media_links = '';
$aboutme_section = '';
$brokeronlyfield = '';
if ($ms > 0){	
  $sql = "select * from tbl_user where id = '". $cm->filtertext($ms) ."'";
  $result = $db->fetch_all_array($sql);
  $found = count($result);   
  if ($found > 0){  
    $row = $result[0];
	$d_username = $old_d_username = htmlspecialchars($row['uid']);
    $d_email = $old_d_email = htmlspecialchars($row['email']);
	$d_password = htmlspecialchars($edclass->txt_decode($row['pwd']));    

	$title = htmlspecialchars($row['title']);	
	$fname = htmlspecialchars($row['fname']);
    $lname = htmlspecialchars($row['lname']); 
	$phone = htmlspecialchars($row['phone']);
	$office_phone_ext = htmlspecialchars($row['office_phone_ext']);
    $about_me = $row['about_me'];    
    $user_imgpath = htmlspecialchars($row['user_imgpath']);
    $type_id = $row['type_id'];
	$company_id = $row['company_id'];
	$location_id = $row['location_id'];
    $parent_id = $row['parent_id'];
	$front_display = $row['front_display'];
	$admin_access = $row['admin_access'];
    $status_id = $old_status_id = $row['status_id'];
	$yw_agent = $row['yw_agent'];
	$display_title = $row['display_title'];
	$display_listings = $row['display_listings'];
	$support_crew = $row['support_crew'];
	$marketing_staff = $row['marketing_staff'];
	$sub_group_id = $row['sub_group_id'];  
	
	//social media
	$facebook_url = htmlspecialchars($row['facebook_url']);
	$twitter_url = htmlspecialchars($row['twitter_url']);
	$linkedin_url = htmlspecialchars($row['linkedin_url']);
	$youtube_url = htmlspecialchars($row['youtube_url']);
	$googleplus_url = htmlspecialchars($row['googleplus_url']);
	$instagram_url = htmlspecialchars($row['instagram_url']);
	$pinterest_url = htmlspecialchars($row['pinterest_url']);
	$blog_url = htmlspecialchars($row['blog_url']);
	
	//YW Feed
	$yw_broker_id = $row['yw_broker_id'];
	if ($yw_broker_id == 0){ $yw_broker_id = ""; }
	
	$company_ar = $cm->get_table_fields('tbl_company', 'cname, website_url', $company_id);
	$cname = $company_ar[0]['cname'];
	$website_url = $company_ar[0]['website_url'];
		
	$link_name = "Modify Existing User";  
  }else{
    $ms = 0;
  }
}

if ($type_id == 2){
	$b_admin = '';
	$b_company = $b_location = $b_agent = ' com_none';
}

if ($type_id == 3){
	//$b_agent = '';
	$b_company = $b_location = '';
	$b_admin = ' com_none';
}

if ($type_id == 4){
	$b_admin = ' com_none';
	$b_company = '';
	$b_location = '';
	$b_agent = ' com_none';
}

if ($type_id == 5){
	$b_admin = ' com_none';
	$b_company = '';
	$b_location = '';
	$b_agent = ' com_none';
}

if ($type_id == 6){
	$b_admin = ' com_none';
	$b_company = ' com_none';
	$b_location = ' com_none';
	$b_agent = ' com_none';
	$in_asso = $in_cert = ' com_none';
	$social_media_links = ' com_none';
	$aboutme_section = ' com_none';
	$brokeronlyfield = ' com_none';
}

$icclass = "leftusericon";
include("head.php");
?>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#type_id").change(function(){
      var selected_type_id = $(this).val();
      var targetcombo = "status_id";

      $('.b_admin').addClass('com_none');
	  $('.b_company').addClass('com_none');
	  $('.b_location').addClass('com_none');
      $('.b_agent').addClass('com_none');
	  
	  $('.in_asso').removeClass('com_none');
	  $('.in_cert').removeClass('com_none');
	  $('.social_media_links').removeClass('com_none');
	  $('.aboutme_section').removeClass('com_none');
	  $('.brokeronlyfield').removeClass('com_none');

      if (selected_type_id == 2){
          $('.b_admin').removeClass('com_none');
      }

      if (selected_type_id == 3){
          //$('.b_agent').removeClass('com_none');
		  $('.b_company').removeClass('com_none');
		  $('.b_location').removeClass('com_none');
      }
	  
	  if (selected_type_id == 4){
          $('.b_company').removeClass('com_none');
		  $('.b_location').removeClass('com_none');
      }
	  
	  if (selected_type_id == 5){
          $('.b_company').removeClass('com_none');
		  $('.b_location').removeClass('com_none');
      }
	  
	  if (selected_type_id == 6){
		  $('.in_asso').addClass('com_none');
		  $('.in_cert').addClass('com_none');
		  $('.social_media_links').addClass('com_none');
		  $('.aboutme_section').addClass('com_none');
		  $('.brokeronlyfield').addClass('com_none');
	  }

      /*
      b_sURL = "stmod.php";
      $.post(b_sURL,
      {
          q:selected_type_id,
          t:1
      },
      function(data){
          if (data != ""){
              $('#' + targetcombo).empty();

              var str_sr = data.split("#");
              var str_ln = str_sr.length;
              for (var k=0; k <str_ln-1; k++){
                  var second_sr = str_sr[k].split("/!");
                  document.getElementById(targetcombo).options[k] = new Option(second_sr[0], second_sr[1]);
              }
          }
      });
      */

  });

  $("#user_ff").submit(function(){

      if(!validate_text(document.ff.type_id,1,"Please select User Type")){
          return false;
      }

      var selected_type_id = $("#type_id").val();

      //Company selection
      if ((selected_type_id == 3) || (selected_type_id == 4) || (selected_type_id == 5)){
          if(!validate_text(document.ff.company_id,1,"Please select Company")){
              return false;
          }
      }
      //end
	  
	  //Location selection
      if ((selected_type_id == 3) || (selected_type_id == 4) || (selected_type_id == 5)){
          if(!validate_text(document.ff.location_id,1,"Please select Location")){
              return false;
          }
      }
      //end
	  
	  /*if(!validate_text(document.ff.sub_group_id,1,"Please select User Sub-group")){
          return false;
      }*/

      //Login details
      if (!username_validation()){ return false; }
      if (!password_validation()){ return false; } 
      //end

      //Broker Admin
      if (selected_type_id == 2){		  
		  if(!validate_text(document.ff.cname,1,"Please enter Company Name")){
			  return false;
		  }	      
      }
      //end

      if(!validate_text(document.ff.fname,1,"Please enter First Name")){
        return false;
      }
     
      if(!validate_text(document.ff.lname,1,"Please enter Last Name")){
        return false;
      }
	  
	  if($("#support_crew").is(':checked')){      

		  if(!validate_email(document.ff.d_email,0,"Please enter Email Address")){
			  return false;
		  }
	  }else{
		  if(!validate_email(document.ff.d_email,1,"Please enter Email Address")){
			  return false;
		  }
	  }

	  if(!validate_text(document.ff.phone,0,"Please enter Mobile Phone")){
          return false;
      }

      if ($("#user_imgpath").length > 0){
          if (!image_validation(document.ff.user_imgpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
      }

      return true;
   });
   
   $("#company_id").change(function(){
	   var selected_type_id = $("#type_id").val();	 
	   if ((selected_type_id == 3) || (selected_type_id == 4) || (selected_type_id == 5)){
		   opencompanylocatiob();
	   }
   });
   

    $(".checkvaliddata").blur(function(){
        var fieldopt = $(this).attr('fieldopt');
        var selvalue = $(this).val();
        var oselvalue = $(this).attr('currentval');
        var targerholder  = "checkvaliddatares" + fieldopt;
        var b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
        {
            fieldopt:fieldopt,
            selvalue:selvalue,
            oselvalue:oselvalue,
            az:16,
            dataType: 'json'
        },
        function(data){
            data = $.parseJSON(data);
            ajclass = data[0].ajclass;
            doc = data[0].doc;

            $("#" + targerholder).removeClass('correctIcon');
            $("#" + targerholder).removeClass('incorrectIcon');
            $("#" + targerholder).addClass(ajclass);
            $("#" + targerholder).attr('title', doc);
        });
    });
	
	//add new record - industry association
 	$(".addrowasso").click(function(){
		var total_associations = $('#total_associations').val();
		total_associations = eval(total_associations);
		total_associations = total_associations + 1;
		
		b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{ 	
			az:41,
			dataType: 'json'
		},
		function(data){			
			data = $.parseJSON(data);
			industryassociation_data = data[0].industryassociation_data;
						
			var added_text = '<tr class="assorowind'+ total_associations +'">';
				added_text += '<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Industry Association:</td>';
				added_text += '<td width="" align="left" valign="top" class="tdpadding1"><select name="industry_associations_id'+ total_associations +'" id="industry_associations_id'+ total_associations +'" class="combobox_size4 htext"><option value="">Select</option>'+ industryassociation_data +'</select></td>';
				added_text += '<td width="25" align="left" valign="top" class="tdpadding1"><a class="asso_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_associations +'" asso_id=""><img src="images/del.png" title="Delete Record" alt="Delete Record"></a></td>';
			added_text += '</tr>';
			
			$("#assoholder").append(added_text);
		    $('#total_associations').val(total_associations);			
		});		
	});
	
	//delete - industry association
	$(".singleblock_box").on("click", ".asso_del", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var del_pointer = $(this).attr('yval');				
			var isdb = $(this).attr('isdb');			
			$("tr, li").remove('.assorowind' + del_pointer);
			
			isdb = eval(isdb);			
			if (isdb == 1){
				//record delete from db also using ajax	
				var asso_id = $(this).attr('asso_id');
				var connect_id = $(this).attr('yid');
				var section = $(this).attr('section');					
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
				{
					asso_id:asso_id,
					connect_id:connect_id,
					section:section,
					az:42
				});
			}
		}		
	});
	
	//sortable - industry association
	$( "#assosortable" ).sortable({
		update: function (event, ui) {
			var connect_id = $("#assosortable").attr('yid');
			var section = $("#assosortable").attr('section');
			var sortdata = $(this).sortable('serialize');
			
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				connect_id:connect_id,
				section:section,
				data:sortdata,
				az:43
			});			
		}
	});
	
	//add new record - Certification
 	$(".addrowcert").click(function(){
		var total_certification = $('#total_certification').val();		
		total_certification = eval(total_certification);
		total_certification = total_certification + 1;
		
		var valimg = $(this).attr('valimg');
		var alltype = $(this).attr('alltype');
		
		b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{ 	
			az:44,
			dataType: 'json'
		},
		function(data){			
			data = $.parseJSON(data);
			certification_data = data[0].certification_data;
						
			var added_text = '<tr class="certrowind'+ total_certification +'">';
				added_text += '<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Certificate:</td>';
				added_text += '<td width="" align="left" valign="top" class="tdpadding1"><select name="certification_id'+ total_certification +'" id="certification_id'+ total_certification +'" class="combobox_size4 htext"><option value="">Select</option>'+ certification_data +'</select></td>';
				added_text += '</tr>';
				
				added_text += '<tr class="certrowind'+ total_certification +'">';
				added_text += '<td width="" align="left" valign="top" class="tdpadding1" colspan="2"><span class="fontcolor3">&nbsp;&nbsp;</span><strong>OR</strong></td>';
				added_text += '</tr>';
				
				added_text += '<tr class="certrowind'+ total_certification +'">';
				added_text += '<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Certificate Name:</td>';
				added_text += '<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="certification_name'+ total_certification +'" name="certification_name'+ total_certification +'" class="inputbox inputbox_size4" /></td>';
				added_text += '</tr>';
				
				added_text += '<tr class="certrowind'+ total_certification +'">';
				added_text += '<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Link URL:</td>';
				added_text += '<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="certification_link_url'+ total_certification +'" name="certification_link_url'+ total_certification +'" class="inputbox inputbox_size4" /></td>';
				added_text += '</tr>';
				
				added_text += '<tr class="certrowind'+ total_certification +'">';
				added_text += '<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Certificate Logo ['+ valimg +']:</td>';
				added_text += '<td width="" align="left" valign="top" class="tdpadding1"><input type="file" id="logo_image'+ total_certification +'" name="logo_image'+ total_certification +'" class="inputbox filebox_size4" /><br />['+ alltype +']</td>';
				added_text += '</tr>';
				
				added_text += '<tr class="certrowind'+ total_certification +'">';
				added_text += '<td align="left" valign="top" class="tdpadding1" colspan="2"><a class="cert_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_certification +'" cert_id=""><img src="images/del.png" title="Delete Record" alt="Delete Record"></a></td>';
				added_text += '</tr>';
			
			$("#certholder").append(added_text);
		    $('#total_certification').val(total_certification);			
		});		
	});
	
	//delete - certification
	$(".singleblock_box").on("click", ".cert_del", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var del_pointer = $(this).attr('yval');				
			var isdb = $(this).attr('isdb');			
			$("tr, li").remove('.certrowind' + del_pointer);
			
			isdb = eval(isdb);
			if (isdb == 1){
				//record delete from db also using ajax	
				var cert_id = $(this).attr('cert_id');
				var connect_id = $(this).attr('yid');
				var section = $(this).attr('section');		
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
				{
					cert_id:cert_id,
					connect_id:connect_id,
					section:section,
					az:45
				});
			}
		}		
	});
	
	//sortable - certification
	$( "#certsortable" ).sortable({
		update: function (event, ui) {
			var connect_id = $("#certsortable").attr('yid');
			var section = $("#certsortable").attr('section');
			var sortdata = $(this).sortable('serialize');
			
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				connect_id:connect_id,
				section:section,
				data:sortdata,
				az:46
			});			
		}
	});
});
</script>


<?php if ($_SESSION["postmessage"] != ""){ ?>
<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
 <tr>
   <td width="100%" align="center"><span class="fontcolor3"><?php echo $_SESSION["postmessage"]; ?></span></td>
 </tr>
</table>    
<?php $_SESSION["postmessage"] = ""; } ?>
		
<form method="post" action="user_sub.php" id="user_ff" name="ff" enctype="multipart/form-data">         
        <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
	    <input type="hidden" value="<?php echo $old_d_username; ?>" name="old_d_username" />
        <input type="hidden" value="<?php echo $old_d_email; ?>" name="old_d_email" />
	    <input type="hidden" value="<?php echo $old_status_id; ?>" name="old_status_id" />
		<input type="hidden" value="<?php echo $yw_broker_id; ?>" name="old_yw_broker_id" />
        <input type="hidden" value="<?php echo $sub_group_id; ?>" name="sub_group_id" id="sub_group_id" />
        <p>All fields marked with <span class="mandt_color">*</span> are mandatory.</p>
        <div class="singleblock">        
        	<div class="singleblock_box">
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>User Type:</td>
                        <td width="^5%" align="left" valign="top" class="tdpadding1">
                        <?php
                            if ($type_id == 1 OR $type_id == 2){
                                $type_name = $cm->get_common_field_name('tbl_user_type', 'name', $type_id);
                        ?>
                        <strong><?php echo $type_name; ?></strong>
                        <input type="hidden" value="<?php echo $type_id; ?>" id="type_id" name="type_id" />
                        <?php		
                            }else{
                        ?>
                            <select id="type_id" name="type_id" class="htext combobox_size4">
                                <option value="">Select Type</option>
                                <?php $yachtclass->get_user_type_combo($type_id, 1); ?>
                            </select>
                        <?php } ?>    
                        </td>
                    </tr>
                    
                    <tr class="b_company<?php echo $b_company; ?>">
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Company:</td>
                        <td width="" align="left" valign="top" class="tdpadding1">
                            <select id="company_id" name="company_id" class="htext combobox_size4">
                                <option value="">Select Company</option>
                                <?php echo $yachtclass->get_company_combo($company_id); ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr class="b_location<?php echo $b_location; ?>">
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Location:</td>
                        <td width="" align="left" valign="top" class="tdpadding1">
                            <select id="location_id" name="location_id" class="htext combobox_size4">
                                <option value="">Select Location</option>
                                <?php echo $yachtclass->get_company_location_combo($location_id, $company_id); ?>
                            </select>
                        </td>
                    </tr>                    
                </table>
            </div>
        </div>    
        
        <div class="singleblock">
        	<div class="singleblock_heading"><span>Login Information</span></div>
        	<div class="singleblock_box">
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Username [min 6 chars]:</td>
                        <td width="65%" align="left" valign="top" class="tdpadding1"><input type="text" id="d_username" name="d_username" value="<?php echo $d_username; ?>" currentval="<?php echo $old_d_username; ?>" fieldopt="1" class="checkvaliddata inputbox inputbox_size4" /> <span title="" id="checkvaliddatares1" class="butta-space">&nbsp;</span></td>
                    </tr>
        
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Password [min 6 chars]:</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input type="password" id="d_password" name="d_password" value="<?php echo $d_password; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
        
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Confirm Password:</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input type="password" id="cd_password" name="cd_password" value="<?php echo $d_password; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                </table>
            </div>
        </div>    
        
        <div class="b_admin<?php echo $b_admin; ?> singleblock">
        	<div class="singleblock_heading"><span>Company Information</span></div>
        	<div class="singleblock_box">
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                        <td width="35%" align="left"><span class="mandt_color">* </span>Company Name:</td>
                        <td width="65%" align="left"><input type="text" id="cname" name="cname" value="<?php echo $cname; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <?php
					  if ($ms > 0){
					?>
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Location:</td>
                        <td width="" align="left" valign="top" class="tdpadding1">
                            <select id="location_id_m" name="location_id_m" class="htext">
                                <option value="">Select Location</option>
                                <?php echo $yachtclass->get_company_location_combo($location_id, $company_id); ?>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <tr>
                        <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Website URL:</td>
                        <td width="" align="left"><input type="text" id="website_url" name="website_url" value="<?php echo $website_url; ?>" class="inputbox inputbox_size4" /></td>                
                    </tr> 
                </table>
            </div>
        </div>  
        
        <div class="singleblock">
        	<div class="singleblock_heading"><span>General Information</span></div>
        	<div class="singleblock_box"> 
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                       <td width="35%" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Title:</td>
                       <td width="65%" align="left"><input type="text" id="title" name="title" value="<?php echo $title; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
                       <td width="" align="left"><span class="mandt_color">* </span>First Name:</td>
                       <td width="" align="left"><input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                   
                    <tr>
                      <td width="" align="left"><span class="fontcolor3">* </span>Last Name:</td>
                      <td width="" align="left"><input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
        
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Email Address:</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="d_email" name="d_email" value="<?php echo $d_email; ?>" currentval="<?php echo $old_d_email; ?>" fieldopt="2" class="checkvaliddata inputbox inputbox_size4" /> <span title="" id="checkvaliddatares2" class="butta-space">&nbsp;</span></td>
                    </tr>
                    
                    <tr>
                      <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Mobile Phone:</td>
                      <td width="" align="left"><input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
						<td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Office Phone Ext:</td>
						<td width="" align="left"><input type="text" id="office_phone_ext" name="office_phone_ext" value="<?php echo $office_phone_ext; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
        
                    <?php if ($user_imgpath != ""){ ?>
                        <tr>
                            <td align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Profile Image:</td>
                            <td align="left" valign="top" class="tdpadding1">
                                <img src="../userphoto/<?php echo $user_imgpath; ?>" border="0" width="100" /><br />
                                <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','user_imgpath','tbl_user','id','userphoto')">Delete Image</a>
                            </td>
                        </tr>
                    <?php }else{ ?>
                        <tr>
                            <td align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Profile Image [w: <?php echo $cm->user_im_width; ?>px, h: <?php echo $cm->user_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
                            <td align="left" class="tdpadding1"><input type="file" id="user_imgpath" name="user_imgpath" class="inputbox" size="65" /></td>
                        </tr>
                    <?php } ?>                    
                </table>
            </div>
       </div>   
       
       <div class="aboutme_section<?php echo $aboutme_section;?> singleblock">
        <div class="singleblock_heading"><span>About Me</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="center" class="tdpadding1">
                        <?php
                        $editorstylepath = "";
                        $editorextrastyle = "adminbodyclass text_area";
                        $cm->display_editor("about_me", $sBasePath, "100%", 300, $about_me, $editorstylepath, $editorextrastyle);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
       </div> 
       
       <div class="social_media_links<?php echo $social_media_links; ?> singleblock">
        	<div class="singleblock_heading"><span>Social Media Links</span></div>
        	<div class="singleblock_box"> 
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                       <td width="35%" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Facebok URL:</td>
                       <td width="65%" align="left"><input type="text" id="facebook_url" name="facebook_url" value="<?php echo $facebook_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
                       <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Twitter URL:</td>
                       <td width="" align="left"><input type="text" id="twitter_url" name="twitter_url" value="<?php echo $twitter_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
                       <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>LinkedIn URL:</td>
                       <td width="" align="left"><input type="text" id="linkedin_url" name="linkedin_url" value="<?php echo $linkedin_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
                       <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>YouTube URL:</td>
                       <td width="" align="left"><input type="text" id="youtube_url" name="youtube_url" value="<?php echo $youtube_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
                       <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Google Plus URL:</td>
                       <td width="" align="left"><input type="text" id="googleplus_url" name="googleplus_url" value="<?php echo $googleplus_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
                       <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Instagram URL:</td>
                       <td width="" align="left"><input type="text" id="instagram_url" name="instagram_url" value="<?php echo $instagram_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr>
                       <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Pinterest URL:</td>
                       <td width="" align="left"><input type="text" id="pinterest_url" name="pinterest_url" value="<?php echo $pinterest_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                   
                     <tr>
                       <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Blog URL:</td>
                       <td width="" align="left"><input type="text" id="blog_url" name="blog_url" value="<?php echo $blog_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>                 
                </table>
            </div>
       </div>
       
       <div class="in_asso<?php echo $in_asso; ?> singleblock">
           <div class="singleblock_heading"><span>Industry Association</span></div>
           <div class="singleblock_box">
                <?php
                    echo $yachtclass->industryassociation_display_list($ms, 2);			
                ?>
           </div>
        </div> 
    
        <div class="in_cert<?php echo $in_cert; ?> singleblock">
            <div class="singleblock_heading"><span>Certification</span></div>
            <div class="singleblock_box">
                <?php
                    echo $yachtclass->certification_display_list($ms, 2);			
                ?>
            </div>

        </div>  
        
        <div class="singleblock">
        	<div class="singleblock_heading"><span>Admin Only</span></div>
        	<div class="singleblock_box">
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                      <td width="35%" align="left"><span class="mandt_color">* </span>Account Status:</td>
                      <td width="65%" align="left">
                      <?php
                        if ($type_id == 1 OR $type_id == 2){
                            $st_name = $cm->get_common_field_name('tbl_user_account_status', 'name', $status_id);
                      ?>
                        <strong><?php echo $st_name; ?></strong>
                        <input type="hidden" value="<?php echo $status_id; ?>" id="status_id" name="status_id" />
                      <?php			
                        }else{
                      ?>
                      <select id="status_id" name="status_id" class="htext combobox_size4">
                          <?php echo $yachtclass->get_user_account_combo($status_id); ?>
                      </select>
                      <?php } ?>
                      </td>
                    </tr>
                 </table>
                 
                 <table class="brokeronlyfield<?php echo $brokeronlyfield; ?>" border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">                    
                    <tr>
                       <td width="35%" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>YW Broker ID:</td>
                       <td width="65%" align="left"><input type="text" id="yw_broker_id" name="yw_broker_id" value="<?php echo $yw_broker_id; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                                       
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Allow Back-end Access?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="admin_access" name="admin_access" value="1" <?php if ($admin_access == 1){?> checked="checked"<?php } ?> <?php if ($ms == 1){?> onclick="this.checked=true"<?php } ?> /> Yes</td>
                    </tr>
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Show up on the website?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="front_display" name="front_display" value="1" <?php if ($front_display == 1){?> checked="checked"<?php } ?> /> Yes</td>
                    </tr>
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Show Title on the Profile Page?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="display_title" name="display_title" value="1" <?php if ($display_title == 1){?> checked="checked"<?php } ?> /> Yes</td>
                    </tr>
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Assign As Yachtworld Agent (For not owned boat)?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="yw_agent" name="yw_agent" value="1" <?php if ($yw_agent == 1){?> checked="checked"<?php } ?> /> Yes</td>
                    </tr>
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Display listings on profile page?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="display_listings" name="display_listings" value="1" <?php if ($display_listings == 1){?> checked="checked"<?php } ?> /> Yes</td>
                    </tr>
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Want to send login invitation?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="sendemailinfo" name="sendemailinfo" value="1" /> Yes</td>
                    </tr>
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Assign as SUPPORT CREW?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="support_crew" name="support_crew" value="1" <?php if ($support_crew == 1){?> checked="checked"<?php } ?> /> Yes</td>
                    </tr>
                    
                    <!--<tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Assign as MARKETING STAFF?</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="marketing_staff" name="marketing_staff" value="1" <?php if ($marketing_staff == 1){?> checked="checked"<?php } ?> /> Yes</td>
                    </tr>-->
                </table>
            </div>
        </div>
        
        <div class="singleblock">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="" align="right">
                        <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                        <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
                    </td>
                </tr>
            </table>
        </div>
</form>
<?php
include("foot.php");
?>