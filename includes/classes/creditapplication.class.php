<?php
class Creditapplicationclass {
	
	//module combo list
	public function get_engine_type_combo($engine_type_id, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_engine_type where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($engine_type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }
	
	public function get_drive_type_combo($drive_type_id, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_drive_type where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($drive_type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }
	
	public function get_credit_application_type_combo($application_type_id){
		global $db;
		$returntxt = '';
		$vsql = "select id, name from tbl_credit_application_type where status_id = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$cname = $vrow['name'];	
			$bck = '';
			if ($application_type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
		}
		return $returntxt;
    }
	//end
	
	//Personal Financial Statement - Field set
	public function asset_form_common_fields(){	
		$fieldsset1 = array();		
		$fieldsset1[] = array(
            'name' => 'Cash in Checking',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Cash in Savings',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Money Markets',
            'sep' => 1
        );
		
		$fieldsset1[] = array(
            'name' => 'Stocks and Bonds',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Mutual Funds',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Other Liquidity',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Cash in Business',
            'sep' => 1
        );
		
		$fieldsset1[] = array(
            'name' => 'IRA/ 401K/ Roth',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Non Marketable',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Non Marketable',
            'sep' => 1
        );
		
		$fieldsset2 = array();		
		$fieldsset2[] = array(
            'name' => 'Present Home',
            'sep' => 0
        );
		$fieldsset2[] = array(
            'name' => 'Other Homes/ RE',
            'sep' => 0
        );
		$fieldsset2[] = array(
            'name' => 'Other Homes/ RE',
            'sep' => 0
        );
		$fieldsset2[] = array(
            'name' => 'Other Homes/ RE',
            'sep' => 1
        );
		
		$fieldsset3 = array();		
		$fieldsset3[] = array(
            'name' => 'Trust Value',
            'sep' => 0
        );
		$fieldsset3[] = array(
            'name' => 'Business Value',
            'sep' => 0
        );
		$fieldsset3[] = array(
            'name' => 'Vehicle(s) - Combined',
            'sep' => 0
        );
		$fieldsset3[] = array(
            'name' => 'Current Boat',
            'sep' => 1
        );
		
		$fieldsset4 = array();		
		$fieldsset4[] = array(
            'name' => 'Household Goods',
            'sep' => 0
        );
		
		$fieldsset5 = array();		
		$fieldsset5[] = array(
            'name' => '',
            'sep' => 0
        );
		$fieldsset5[] = array(
            'name' => '',
            'sep' => 0
        );
		$fieldsset5[] = array(
            'name' => '',
            'sep' => 0
        );
		
		$fieldset = array(
            'set1' => $fieldsset1,
            'set2' => $fieldsset2,
			'set3' => $fieldsset3,
			'set4' => $fieldsset4,
			'set5' => $fieldsset5
        );
						
		return $fieldset;
	}
	
	public function liabilities_form_common_fields(){
		$fieldsset1 = array();
		$fieldsset1[] = array(
            'name' => 'Notes Payable Sec',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Notes Payable Sec',
            'sep' => 0
        );
		$fieldsset1[] = array(
            'name' => 'Boat Loan',
            'sep' => 1
        );
		
		$fieldsset2 = array();
		$fieldsset2[] = array(
            'name' => 'Notes Unsecured',
            'sep' => 0
        );
		$fieldsset2[] = array(
            'name' => 'Notes Unsecured',
            'sep' => 0
        );
		$fieldsset2[] = array(
            'name' => 'Notes Unsecured',
            'sep' => 0
        );
		$fieldsset2[] = array(
            'name' => 'Notes Unsecured',
            'sep' => 0
        );
		$fieldsset2[] = array(
            'name' => 'Notes Unsecured',
            'sep' => 1
        );
		
		$fieldsset3 = array();
		$fieldsset3[] = array(
            'name' => 'Other Notes Due',
            'sep' => 0
        );
		$fieldsset3[] = array(
            'name' => 'Other Notes Due',
            'sep' => 0
        );
		$fieldsset3[] = array(
            'name' => 'Other Notes Due',
            'sep' => 0
        );
		$fieldsset3[] = array(
            'name' => 'Other Notes Due',
            'sep' => 0
        );
		$fieldsset3[] = array(
            'name' => 'Other Notes Due',
            'sep' => 1
        );
		
		$fieldsset4 = array();
		$fieldsset4[] = array(
            'name' => 'Real Estate Mtgs.',
            'sep' => 0
        );
		$fieldsset4[] = array(
            'name' => 'Real Estate Mtgs.',
            'sep' => 0
        );
		$fieldsset4[] = array(
            'name' => 'Real Estate Mtgs.',
            'sep' => 0
        );
		$fieldsset4[] = array(
            'name' => 'Real Estate Mtgs.',
            'sep' => 0
        );
		$fieldsset4[] = array(
            'name' => 'Real Estate Mtgs.',
            'sep' => 1
        );
		
		$fieldsset5 = array();
		$fieldsset5[] = array(
            'name' => 'Other Debts ( Rev.)',
            'sep' => 0
        );
		$fieldsset5[] = array(
            'name' => 'Other Debts ( Rev.)',
            'sep' => 0
        );
		$fieldsset5[] = array(
            'name' => 'Other Debts ( Rev.)',
            'sep' => 0
        );
		$fieldsset5[] = array(
            'name' => 'Other Debts ( Rev.)',
            'sep' => 0
        );
		$fieldsset5[] = array(
            'name' => 'Other Debts ( Rev.)',
            'sep' => 0
        );
		
		$fieldset = array(
            'set1' => $fieldsset1,
            'set2' => $fieldsset2,
			'set3' => $fieldsset3,
			'set4' => $fieldsset4,
			'set5' => $fieldsset5
        );
						
		return $fieldset;
	}
	
	//Personal Financial Statement	
	public function display_personal_financial_statement_form($fullform = 0){
		global $db, $cm;
		$finance_company_name = $cm->sitename;
			
		$counter = 0;
		$fieldsets = $this->asset_form_common_fields();
		$fieldsets = (object)$fieldsets;
		
		$fieldset1 = $fieldsets->set1;		
		$assets_form = '
		<h3>Assets</h4>
		<ul class="form2">
			<li class="first noborder formfieldheading">Asset Name</li>
			<li class="second formfieldheading">Financial Institution</li>
			<li class="third formfieldheading">$</li>
		';			
		
		foreach($fieldset1 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$assets_form .= '
				<li class="first noborder"><p>'. $fieldname .'</p></li>
				<li class="second"><label class="com_none" for="financial_inst'. $counter .'">Financial Institution</label><input type="text" id="financial_inst'. $counter .'" name="financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="third"><label class="com_none" for="assetamt'. $counter .'">Asset Amount</label><input placeholder="$" type="text" id="assetamt'. $counter .'" name="assetamt'. $counter .'" value="" class="input assetamt" /></li>
			';
			
			if ($fieldsep == 1){
				$assets_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
			
		$assets_form .= '
		</ul>
		';
		
		$fieldset2 = $fieldsets->set2;		
		$assets_form .= '
		<ul class="form3">
			<li class="first noborder">Real Estate</li>
			<li class="second">Property Location</li>
			<li class="third">Income</li>
			<li class="fourth">Present Value</li>
		';
		
		foreach($fieldset2 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$assets_form .= '
				<li class="first noborder"><p>'. $fieldname .'</p></li>
				<li class="second"><label class="com_none" for="financial_inst'. $counter .'">Property Location</label><input type="text" id="financial_inst'. $counter .'" name="financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="third"><label class="com_none" for="assetincome'. $counter .'">Income</label><input placeholder="$" type="text" id="assetincome'. $counter .'" name="assetincome'. $counter .'" value="" class="input assetamt" /></li>
				<li class="fourth"><label class="com_none" for="assetamt'. $counter .'">Present Value</label><input placeholder="$" type="text" id="assetamt'. $counter .'" name="assetamt'. $counter .'" value="" class="input assetamt" /></li>
			';
			
			if ($fieldsep == 1){
				$assets_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		
		$assets_form .= '
		</ul>
		';
		
		$fieldset3 = $fieldsets->set3;		
		$assets_form .= '
		<ul class="form4">			
		';
		
		foreach($fieldset3 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$assets_form .= '
				<li class="first noborder"><p><label for="assetamt'. $counter .'">'. $fieldname .'</label></p></li>
				<li class="second"><input placeholder="$" type="text" id="assetamt'. $counter .'" name="assetamt'. $counter .'" value="" class="input assetamt" /></li>
			';
			
			if ($fieldsep == 1){
				$assets_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		$assets_form .= '
		</ul>
		';
		
		$fieldset4 = $fieldsets->set4;		
		$assets_form .= '
		<ul class="form4">			
		';
		
		foreach($fieldset4 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$assets_form .= '
				<li class="first noborder"><p><label for="assetamt'. $counter .'">'. $fieldname .'</label></p></li>
				<li class="second"><input placeholder="$" type="text" id="assetamt'. $counter .'" name="assetamt'. $counter .'" value="" class="input assetamt" /></li>
			';
			
			if ($fieldsep == 1){
				$assets_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		$assets_form .= '
		</ul>
		';
		
		$fieldset5 = $fieldsets->set5;		
		$assets_form .= '
		<ul class="form4">	
			<li class="full noborder">Other Non-Titled Assets (describe)</li>
		';
		
		foreach($fieldset5 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$assets_form .= '
				<li class="first noborder"><label class="com_none" for="financial_inst'. $counter .'">A</label><input type="text" id="financial_inst'. $counter .'" name="financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="second"><label class="com_none" for="assetamt'. $counter .'">B</label><input placeholder="$" type="text" id="assetamt'. $counter .'" name="assetamt'. $counter .'" value="" class="input assetamt" /></li>
			';
			
			if ($fieldsep == 1){
				$assets_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		$assets_form .= '
		</ul>
		';
		
		
		$counter = 0;
		$fieldsets = $this->liabilities_form_common_fields();
		$fieldsets = (object)$fieldsets;
		
		$fieldset1 = $fieldsets->set1;
		
		$liabilities_form = '
		<h3>Liabilities</h4>
		<ul class="form5">
			<li class="first noborder formfieldheading">Name Of Liability</li>
			<li class="second formfieldheading">Financial Institution</li>
			<li class="third formfieldheading">$</li>
			<li class="fourth formfieldheading">PMT</li>
		';
		
		foreach($fieldset1 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$liabilities_form .= '
				<li class="first noborder"><p>'. $fieldname .'</p></li>
				<li class="second"><label class="com_none" for="l_financial_inst'. $counter .'">Financial Institution</label><input type="text" id="l_financial_inst'. $counter .'" name="l_financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="third"><label class="com_none" for="liabilitiesamt'. $counter .'">Amt</label><input placeholder="$" type="text" id="liabilitiesamt'. $counter .'" name="liabilitiesamt'. $counter .'" value="" class="input liabilitiesamt" /></li>
				<li class="fourth"><label class="com_none" for="pmt'. $counter .'">PMT</label><input placeholder="$" type="text" id="pmt'. $counter .'" name="pmt'. $counter .'" value="" class="input pmtamt" /></li>
			';
			
			if ($fieldsep == 1){
				$liabilities_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		
		$liabilities_form .= '
		</ul>
		';
		
		$fieldset2 = $fieldsets->set2;		
		$liabilities_form .= '
		<ul class="form5">			
		';
		
		foreach($fieldset2 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$liabilities_form .= '
				<li class="first noborder"><p>'. $fieldname .'</p></li>
				<li class="second"><label class="com_none" for="l_financial_inst'. $counter .'">Financial Institution</label><input type="text" id="l_financial_inst'. $counter .'" name="l_financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="third"><label class="com_none" for="liabilitiesamt'. $counter .'">Amt</label><input placeholder="$" type="text" id="liabilitiesamt'. $counter .'" name="liabilitiesamt'. $counter .'" value="" class="input liabilitiesamt" /></li>
				<li class="fourth"><label class="com_none" for="pmt'. $counter .'">PMT</label><input placeholder="$" type="text" id="pmt'. $counter .'" name="pmt'. $counter .'" value="" class="input pmtamt" /></li>
			';
			
			if ($fieldsep == 1){
				$liabilities_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		
		$liabilities_form .= '
		</ul>
		';
		
		$fieldset3 = $fieldsets->set3;		
		$liabilities_form .= '
		<ul class="form5">			
		';
		
		foreach($fieldset3 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$liabilities_form .= '
				<li class="first noborder"><p>'. $fieldname .'</p></li>
				<li class="second"><label class="com_none" for="l_financial_inst'. $counter .'">Financial Institution</label><input type="text" id="l_financial_inst'. $counter .'" name="l_financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="third"><label class="com_none" for="liabilitiesamt'. $counter .'">Amt</label><input placeholder="$" type="text" id="liabilitiesamt'. $counter .'" name="liabilitiesamt'. $counter .'" value="" class="input liabilitiesamt" /></li>
				<li class="fourth"><label class="com_none" for="pmt'. $counter .'">PMT</label><input placeholder="$" type="text" id="pmt'. $counter .'" name="pmt'. $counter .'" value="" class="input pmtamt" /></li>
			';
			
			if ($fieldsep == 1){
				$liabilities_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		
		$liabilities_form .= '
		</ul>
		';
		
		$fieldset4 = $fieldsets->set4;		
		$liabilities_form .= '
		<ul class="form5">			
		';
		
		foreach($fieldset4 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$liabilities_form .= '
				<li class="first noborder"><p>'. $fieldname .'</p></li>
				<li class="second"><label class="com_none" for="l_financial_inst'. $counter .'">Financial Institution</label><input type="text" id="l_financial_inst'. $counter .'" name="l_financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="third"><label class="com_none" for="liabilitiesamt'. $counter .'">Amt</label><input placeholder="$" type="text" id="liabilitiesamt'. $counter .'" name="liabilitiesamt'. $counter .'" value="" class="input liabilitiesamt" /></li>
				<li class="fourth"><label class="com_none" for="pmt'. $counter .'">PMT</label><input placeholder="$" type="text" id="pmt'. $counter .'" name="pmt'. $counter .'" value="" class="input pmtamt" /></li>
			';
			
			if ($fieldsep == 1){
				$liabilities_form .= '<li class="fieldsep noborder"></li>';
			}
		

			$counter++;
		}
		
		$liabilities_form .= '
		</ul>
		';
		
		$fieldset5 = $fieldsets->set5;		
		$liabilities_form .= '
		<ul class="form5">			
		';
		
		foreach($fieldset5 as $innerar){			
			$innerar = (object)$innerar;
			$fieldname = $innerar->name;
			$fieldsep = $innerar->sep;
			
			$liabilities_form .= '
				<li class="first noborder"><p>'. $fieldname .'</p></li>
				<li class="second"><label class="com_none" for="l_financial_inst'. $counter .'">Financial Institution</label><input type="text" id="l_financial_inst'. $counter .'" name="l_financial_inst'. $counter .'" value="" class="input" /></li>
				<li class="third"><label class="com_none" for="liabilitiesamt'. $counter .'">Amt</label><input placeholder="$" type="text" id="liabilitiesamt'. $counter .'" name="liabilitiesamt'. $counter .'" value="" class="input liabilitiesamt" /></li>
				<li class="fourth"><label class="com_none" for="pmt'. $counter .'">PMT</label><input placeholder="$" type="text" id="pmt'. $counter .'" name="pmt'. $counter .'" value="" class="input pmtamt" /></li>
			';
			
			if ($fieldsep == 1){
				$liabilities_form .= '<li class="fieldsep noborder"></li>';
			}
		
			$counter++;
		}
		
		$liabilities_form .= '
		</ul>
		';
		
		if ($fullform == 1){
			$returntext .= '
				<div class="left-cell-half">'. $assets_form .'</div>
				<div class="right-cell-half">'. $liabilities_form .'</div>
				<div class="clear"></div>
				
				<div class="left-cell-half">
					<ul class="form4">
						<li class="first noborder"><p><strong>Total Assets</strong></p></li>
						<li class="second"><p><span class="totalasset">$0</span></p></li>
					</ul>
				</div>
				<div class="right-cell-half">
					<ul class="form6">
						<li class="first noborder"><p><strong>Total Liabilities</strong></p></li>
						<li class="third"><p><span class="totalliabilities">$0</span></p></li>
						<li class="fourth blank">&nbsp;</li>
					</ul>
					
					<ul class="form6">
						<li class="first noborder"><p><strong>Net Worth</strong></p></li>
						<li class="third"><p><span class="totalnetworth">$0</span></p></li>
						<li class="fourth blank">&nbsp;</li>
					</ul>
				</div>
				
				<div class="clear"></div>
			';
		}else{
		
			$returntext .= '
			<form method="post" action="'. $cm->folder_for_seo .'" id="assetapplication-ff" name="assetapplication-ff">
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="personalfinancialformsubmit" />	   	   
			';
			
			$returntext .= '
			<div class="left-cell-half">'. $assets_form .'</div>
			<div class="right-cell-half">'. $liabilities_form .'</div>
			<div class="clear"></div>
			
			<div class="left-cell-half">
				<ul class="form4">
					<li class="first noborder"><p><strong>Total Assets</strong></p></li>
					<li class="second"><p><span class="totalasset">$0</span></p></li>
				</ul>
			</div>
			<div class="right-cell-half">
				<ul class="form6">
					<li class="first noborder"><p><strong>Total Liabilities</strong></p></li>
					<li class="third"><p><span class="totalliabilities">$0</span></p></li>
					<li class="fourth blank">&nbsp;</li>
				</ul>
				
				<ul class="form6">
					<li class="first noborder"><p><strong>Net Worth</strong></p></li>
					<li class="third"><p><span class="totalnetworth">$0</span></p></li>
					<li class="fourth blank">&nbsp;</li>
				</ul>
			</div>
			
			<div class="clear"></div>
			';
			
			$returntext .= '
			<div class="singleblock spacer1"> 
				<div class="singleblock_box">	 
					<ul class="form">
						<li class="left">
						<p><label for="fullname">Name</label> <span class="requiredfieldindicate">*</span></p>
						<input type="text" id="fullname" name="fullname" value="'. $fullname .'" class="input" />
						</li>
						<li class="right">
						<p><label for="email">Email Address</label> <span class="requiredfieldindicate">*</span></p>
						<input type="text" id="email" name="email" value="'. $email .'" class="input" />
						</li>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			
			<div class="singleblock spacer1"> 
				<div class="singleblock_box">	 
					<p>
					I (We) confirm that the information provided is complete and accurate to the best of my/our knowledge. 
					I (We) authorize <strong>'. $finance_company_name .'</strong> and its affiliated lending institutions to utilize the information within this personal financial statement in conjunction with my/our loan application. 
					I (We) authorize <strong>'. $finance_company_name .'</strong> and its affiliated lending institutions to obtain and verify information in connection with my/our application including credit investigation, employment history and any other information necessary to evaluate this loan application.
					</p>  
					
					<div class="credit_authorization">		  	    
					<ul class="form">	   		
					<li class="t-center">					
					<span class="creditauthcheckbox"><input type="checkbox" id="form_auth" name="form_auth" value="1" class="checkbox" /><label for="form_auth">I Agree</label></span>
					</li>
					</ul>
					<div class="clear"></div>
					</div>
					
					<p>
					PATRIOT ACT NOTICE: To help the government fight the funding of terrorism and money laundering activities, 
					Federal Law requires all financial institutions to obtain, verify and record information that identifies each person who opens an account. 
					For the purposes of this section, account shall be understood to include loan accounts.
					</p>
					<div class="clear"></div>
				</div>
			</div>
			';
			
			$returntext .= '
			<div class="multistepformbutton spacer1">
				<button type="button" class="button assetsubmit"><span>Submit</span></button>
			</div>
			';
			
			$returntext .= '
			<input type="hidden" id="total_asset" name="total_asset" value="0" />
			<input type="hidden" id="total_liabilities" name="total_liabilities" value="0" />
			</form>
			';					
		}
		
		$returntext .= '
		<script type="text/javascript">
			$(document).ready(function(){
				$.fn.assetcalculation = function(){
					var totalasset = 0;
					$(".assetamt").each(function(){
						var idd = $(this).attr("id");
						if (!field_validation_border(idd, 4, 0)){
							all_ok = "n";
							setfocus = set_field_focus(setfocus, idd);
						}
										
						var rowassetamt = parseFloat($(this).val());
						if(isNaN(rowassetamt)) { rowassetamt = 0; }
						totalasset = totalasset + rowassetamt;
					});
					
					//totalasset = totalasset * 1000;
					totalasset = number_round(totalasset);
					$(".totalasset").html(digits("$" + totalasset));
					
					var totalliabilities = 0;
					$(".liabilitiesamt").each(function(){
						var idd = $(this).attr("id");
						if (!field_validation_border(idd, 4, 0)){
							all_ok = "n";
							setfocus = set_field_focus(setfocus, idd);
						}
										
						var rowassetamt = parseFloat($(this).val());
						if(isNaN(rowassetamt)) { rowassetamt = 0; }
						totalliabilities = totalliabilities + rowassetamt;
					});
					
					//totalliabilities = totalliabilities * 1000;
					totalliabilities = number_round(totalliabilities);
					$(".totalliabilities").html(digits("$" + totalliabilities));
					
					var totalnetworth = totalasset - totalliabilities;					
					totalnetworth = number_round(totalnetworth);
					$(".totalnetworth").html(digits("$" + totalnetworth));
				}
				
				$(".assetamt, .liabilitiesamt").keyup(function(){
					$(this).assetcalculation();
				});
				
				$(".pmtamt").keyup(function(){
					var idd = $(this).attr("id");
					if (!field_validation_border(idd, 4, 0)){						
						setfocus = set_field_focus(setfocus, idd);
					}
				});
				
				//submit the form
				$(".assetsubmit").click(function(){
					var all_ok = "y";
					var setfocus = "n";
					
					//asset
					var assetcounter = 0;
					$(".assetamt").each(function(){
						var rowassetamt = parseFloat($(this).val());
						if($.isNumeric(rowassetamt)) {
							assetcounter = 1;
						}
					});
					
					if (assetcounter == 0){
						all_ok = "n";
					}
					//end
					
					//liabilities
					var liabilitiescounter = 0;
					$(".liabilitiesamt").each(function(){
						var rowassetamt = parseFloat($(this).val());
						if($.isNumeric(rowassetamt)) {
							liabilitiescounter = 1;
						}
					});
					
					if (liabilitiescounter == 0){
						all_ok = "n";
					}
					//end
					
					//pmt
					$(".pmtamt").each(function(){
						var idd = $(this).attr("id");
						if (!field_validation_border(idd, 4, 0)){						
							setfocus = set_field_focus(setfocus, idd);
							all_ok = "n";
						}
					});			
					//end
					
					if (!field_validation_border("fullname", 1, 1)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "fullname");
					}
					
					if (!field_validation_border("email", 2, 1)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "email");
					}
					
					//credit authorization 	
					if(!($("#form_auth").is(":checked"))){
						all_ok = "n";
						$(".credit_authorization").addClass("indicateborder");
					}
					//end
					
					if (all_ok == "y"){ 
						$("#assetapplication-ff" ).submit();
					}else{
						errormessagepop("Please fill the form properly");
						return false;
					}					
				});
			});
		</script>
		';
		
		return $returntext;
	}
	
	/*--------------------BOAT CREDIT--------------------*/
	//session fields
	public function session_field_credit_application(){     
		$datastring = "fname,lname,middle_name,dob,social_security,drivers_license,drivers_license_state,mobile,home_phone,phone,us_citizen,citizen_country,crop_llc_trust_name,llc_ein";
		$datastring .= ",address,city,state,zip,country,address_year,address_month,own_rent,monthly_payment";
		$datastring .= ",prev_address,prev_city,prev_state,prev_zip,prev_country,prev_address_year,prev_address_month,prev_own_rent,prev_monthly_payment";
		$datastring .= ",employer,emp_address,emp_city,emp_state,emp_zip,emp_country,emp_phone,emp_year,emp_month,emp_position,emp_supervisor";
		$datastring .= ",prev_employer,prev_emp_address,prev_emp_city,prev_emp_state,prev_emp_zip,prev_emp_country,prev_emp_phone,prev_emp_year,prev_emp_month";
		$datastring .= ",wages,paid,oth_income,oth_income_paid,oth_income_description,prior_bankruptcy,bankruptcy_year";
		$datastring .= ",nearest_relative,nearest_relative_relationship,nearest_relative_phone";
		
		$datastring .= ",co_app_fname,co_app_lname,co_app_middle_name,co_app_dob,co_app_social_security,co_app_drivers_license,co_app_drivers_license_state,co_app_mobile,co_app_home_phone,co_app_phone";
		$datastring .= ",co_app_address,co_app_city,co_app_state,co_app_zip,co_app_country,co_app_address_year,co_app_address_month,co_app_own_rent,co_app_monthly_payment";
		$datastring .= ",co_app_prev_address,co_app_prev_city,co_app_prev_state,co_app_prev_zip,co_app_prev_country,co_app_prev_address_year,co_app_prev_address_month,co_app_prev_own_rent,co_app_prev_monthly_payment";
		$datastring .= ",co_app_employer,co_app_emp_address,co_app_emp_city,co_app_emp_state,co_app_emp_zip,co_app_emp_country,co_app_emp_phone,co_app_emp_year,co_app_emp_month,co_app_emp_position,co_app_emp_supervisor";
		$datastring .= ",co_app_prev_employer,co_app_prev_emp_address,co_app_prev_emp_city,co_app_prev_emp_state,co_app_prev_emp_zip,co_app_prev_emp_country,co_app_prev_emp_phone,co_app_prev_emp_year,co_app_prev_emp_month";
		$datastring .= ",co_app_wages,co_app_paid,co_app_oth_income,co_app_oth_income_paid,co_app_oth_income_description";
		$datastring .= ",co_app_nearest_relative,co_app_nearest_relative_relationship,co_app_nearest_relative_phone";
		
		$datastring .= ",boat_manufacturer,boat_model,boat_year,boat_length,boat_price,sales_agent_name,engine_make,engine_type,drive_type,engine_no,fuel_type,horsepower_individual";
		$datastring .= ",purchase_price,tradein,tradein_year,tradein_make,tradein_model,tradein_length,tradein_engine_make,tradein_hp,tradein_engine_no,tradein_fuel_type,tradein_horsepower_individual";
		$datastring .= ",estimated_tax_rate,cash_down,cash_down_per,trade_amount,trade_payoff";
		return $datastring;
	}
	
	public function online_credit_application_form($s = 0){
	  global $db, $cm, $yachtclass;
	  $loggedin_member_id = $yachtclass->loggedin_member_id();
	  $finance_company_name = $cm->get_common_field_name('tbl_brokerage_services_email', 'company_name', 3);
  
	  $datastring = $this->session_field_credit_application();
	  $return_ar = $cm->collect_session_for_form($datastring);
		
	  foreach($return_ar AS $key => $val){
		   ${$key} = $val;
	  }
	  
	  if ($fname == "" AND $email == ""){
		    //form not submitted
		    $user_det = $cm->get_table_fields('tbl_user', 'fname, lname, email, phone', $loggedin_member_id);
			$email = $user_det[0]['email'];
			$fname = $user_det[0]['fname'];
			$lname = $user_det[0]['lname'];
			$phone = $user_det[0]['phone'];
			
			if (isset($_SESSION["visited_boat"]) AND $_SESSION["visited_boat"] > 0){
				$result = $yachtclass->check_yacht_with_return($_SESSION["visited_boat"]);
				$row = $res_row = $result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				//Dimensions & Weight
				$ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($_SESSION["visited_boat"]) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				//Engine
				$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($_SESSION["visited_boat"]) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$boat_manufacturer = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
				$boat_model = $model;
				$boat_year = $year;
				$boat_price = $price;
				$boat_length = $length;
			}
	  }
	  
	  if ($application_type_id == 0){ $application_type_id = 1; };
	  
	  $own_rent1 = ' checked="checked"';
	  $own_rent2 = '';
	  if ($own_rent == "Rent") {$own_rent1 = ''; $own_rent2 = ' checked="checked"';}
	  
	  $prev_own_rent1 = ' checked="checked"';
	  $prev_own_rent2 = '';
	  if ($prev_own_rent == "Rent") {$prev_own_rent1 = ''; $prev_own_rent2 = ' checked="checked"';}
	  
	  $paid1 = ' checked="checked"';
	  $paid2 = '';
	  if ($paid == "Annually") {$paid1 = ''; $paid2 = ' checked="checked"';}
	  
	  $oth_income_paid1 = ' checked="checked"';
	  $oth_income_paid2 = '';
	  if ($oth_income_paid == "Annually") {$oth_income_paid1 = ''; $oth_income_paid2 = ' checked="checked"';}
	  
	  $fuel_type1 = '';
	  $fuel_type2 = '';
	  if ($fuel_type == "Gas") {$fuel_type1 = ' checked="checked"'; $fuel_type2 = '';}
	  if ($fuel_type == "Diesel") {$fuel_type1 = ''; $fuel_type2 = ' checked="checked"';}
	  
	  $tradein_checked = '';
	  if ($tradein == 1){ $tradein_checked = ' checked="checked"'; }
	  
	  $tradein_fuel_type1 = '';
	  $tradein_fuel_type2 = '';
	  if ($tradein_fuel_type == "Gas") {$tradein_fuel_type1 = ' checked="checked"'; $tradein_fuel_type2 = '';}
	  if ($tradein_fuel_type == "Diesel") {$tradein_fuel_type1 = ''; $tradein_fuel_type2 = ' checked="checked"';}
	  
	  /*-------------*/
	  $co_app_own_rent1 = ' checked="checked"';
	  $co_app_own_rent2 = '';
	  if ($co_app_own_rent == "Rent") {$co_app_own_rent1 = ''; $co_app_own_rent2 = ' checked="checked"';}
	  
	  $co_app_prev_own_rent1 = ' checked="checked"';
	  $co_app_prev_own_rent2 = '';
	  if ($co_app_prev_own_rent == "Rent") {$co_app_prev_own_rent1 = ''; $co_app_prev_own_rent2 = ' checked="checked"';}
	  
	  $co_app_paid1 = ' checked="checked"';
	  $co_app_paid2 = '';
	  if ($co_app_paid == "Annually") {$co_app_paid1 = ''; $co_app_paid2 = ' checked="checked"';}
	  
	  $co_app_oth_income_paid1 = ' checked="checked"';
	  $co_app_oth_income_paid2 = '';
	  if ($co_app_oth_income_paid == "Annually") {$co_app_oth_income_paid1 = ''; $co_app_oth_income_paid2 = ' checked="checked"';}
	  
	  $us_citizen_checked = ' checked="checked"';
	  if ($us_citizen == 1){ $us_citizen_checked = ''; }
	  
	  $prior_bankruptcy_checked_yes = '';
	  $prior_bankruptcy_checked_no = ' checked="checked"';
	  if ($prior_bankruptcy == 1){ 
	  	$prior_bankruptcy_checked_yes = ' checked="checked"'; 
		$prior_bankruptcy_checked_no = '';
	  }
	  
	  $horsepower_combined = $engine_no * $horsepower_individual;
	  $tradein_horsepower_combined = $tradein_engine_no * $tradein_horsepower_individual;
	  
	  $returntext = '
	  <div id="creditapplicationform" class="multistepform-main divborder">
	  	   <div class="form-steps">    
				<ul class="stephead">
					<li class="s1 active">Applicant</li>
					<li class="s2 com_none">Co-Applicant</li>
					<li class="s3">Boat</li>
					<li class="s4">Personal Financial Statement</li>
					<li class="s5">Submit Application</li>
				</ul>        
				<div class="clearfix"></div>
			</div>
			<div class="multistepform-holder">
	  ';
	  
	  $returntext .= '
	  <form method="post" action="'. $cm->folder_for_seo .'" id="creditapplication-ff" name="creditapplication-ff">
	  <label class="com_none" for="email2">email2</label>
	  <input type="hidden" id="s" name="s" type="text" value="'. $s .'" />
	  <input class="finfo" id="email2" name="email2" type="text" />
	  <input type="hidden" id="fcapi" name="fcapi" value="creditapplication" />	   	   
	  ';
	  
	  $returntext .= '
	  <!--Step 1-->
	  <div id="form1" class="formstep">
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Applicant</span><span class="requiredinfo">* Required</span></div> 
	  <div class="singleblock_box singleblock_box_h">
	  <ul class="form">
	  		<li class="left" id="application_type_id_heading">
				<p><label for="application_type_id">Application Type</label> <span class="requiredfieldindicate">*</span></p>
				<select id="application_type_id" name="application_type_id" class="select">		
					'. $this->get_credit_application_type_combo($application_type_id) .'
				</select>
			</li>
	  </ul>
	    
	  <ul class="form">	   		
			<li class="left">
				<p><label for="fname">First Name</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
			</li>
			<li class="right">
				<p><label for="lname">Last Name</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="middle_name">Middle Name</label></p>
				<input type="text" id="middle_name" name="middle_name" value="'. $middle_name .'" class="input" />
			</li>			
			<li class="right">
				<p><label for="dob">Date of Birth (mm/dd/yyyy)</label></p>
				<input defaultdateset="01/01/1980" rangeyear="1900:'. (date("Y") - 18) .'" type="text" id="dob" name="dob" value="'. $dob .'" class="date-field-b input2" />
			</li>
			
			<li class="left corp_llc_trust com_none">
				<p><label for="crop_llc_trust_name">Corp/LLC/Trust Name</label></p>
				<input type="text" id="crop_llc_trust_name" name="crop_llc_trust_name" value="'. $crop_llc_trust_name .'" class="input" />
			</li>
			<li class="right corp_llc_trust com_none">
				<p><label for="llc_ein">EIN</label></p>
				<input type="text" id="llc_ein" name="llc_ein" value="'. $llc_ein .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="social_security">Social Security #</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="social_security" name="social_security" value="'. $social_security .'" class="input" />
			</li>
			<li class="right">
				<p><label for="drivers_license">Drivers License #</label></p>
				<input type="text" id="drivers_license" name="drivers_license" value="'. $drivers_license .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="drivers_license_state">Drivers License State</label></p>
				<select id="drivers_license_state" name="drivers_license_state" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_state_combo($drivers_license_state, 1) .'
				</select>
			</li>
			<li class="right">
				<p><label for="email">Email Address</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
						
			<li class="left">
				<p><label for="mobile">Mobile Phone</label></p>
				<input type="text" id="mobile" name="mobile" value="'. $mobile .'" class="input" />
			</li>
			<li class="right">
				<p><label for="phone">Work Phone</label></p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="home_phone">Home Phone</label></p>
				<input type="text" id="home_phone" name="home_phone" value="'. $home_phone .'" class="input" />
			</li>
			<li class="right">
				<p><label for="us_citizen">U.S. Citizen</label></p>
				<input type="checkbox" id="us_citizen" name="us_citizen" value="1" class="checkbox"'. $us_citizen_checked .'> Yes
			</li>
	  </ul>
	  
	  <ul class="form citizencountry com_none">
	  		<li class="left" id="citizen_country_heading">
				<p><label for="citizen_country">Country of Citizenship</label> <span class="requiredfieldindicate">*</span></p>
				<select id="citizen_country" name="citizen_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($citizen_country, 1) .'
				</select>
			</li>
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Current Address</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="address">Address</label></p>
				<input type="text" id="address" name="address" value="'. $address .'" class="input" />
			</li>
			<li class="right">
				<p><label for="city">City</label></p>
				<input type="text" id="city" name="city" value="'. $city .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="state">State</label></p>
				<input type="text" id="state" name="state" value="'. $state .'" class="input" />
			</li>
			<li class="right">
				<p><label for="zip">Postal Code</label></p>
				<input type="text" id="zip" name="zip" value="'. $zip .'" class="input" />
			</li>
			
			<li class="left" id="country_heading">
				<p><label for="country">Country</label></p>
				<select id="country" name="country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($country, 1) .'
				</select>
			</li>
			<li class="right">
				<p>Years at Address</p>
				<label class="com_none" for="address_year">Year</label>
				<label class="com_none" for="address_month">Month</label>
				<div class="leftfield"><input type="text" id="address_year" name="address_year" value="'. $address_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="address_month" name="address_month" value="'. $address_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p>Own or Rent</p>
				<label class="com_none" for="own_rent1">Own</label>
				<label class="com_none" for="own_rent2">Month</label>
				<input type="radio" id="own_rent1" name="own_rent" value="Own" class="radiobutton"'. $own_rent1 .' />&nbsp;Own&nbsp;&nbsp;&nbsp;
				<input type="radio" id="own_rent2" name="own_rent" value="Rent" class="radiobutton"'. $own_rent2 .' />&nbsp;Rent
			</li>
			<li class="right">
				<p><label for="monthly_payment">Monthly Payment [$]</label></p>
				<input type="text" id="monthly_payment" name="monthly_payment" value="'. $monthly_payment .'" class="input" />
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div id="pevious_address" class="singleblock com_none"> 
	  <div class="singleblock_heading"><span>Previous Address</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="prev_address">Address</label></p>
				<input type="text" id="prev_address" name="prev_address" value="'. $prev_address .'" class="input" />
			</li>
			<li class="right">
				<p><label for="prev_city">City</label></p>
				<input type="text" id="prev_city" name="prev_city" value="'. $prev_city .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="prev_state">State</label></p>
				<input type="text" id="prev_state" name="prev_state" value="'. $prev_state .'" class="input" />
			</li>
			<li class="right">
				<p><label for="prev_zip">Postal Code</label></p>
				<input type="text" id="prev_zip" name="prev_zip" value="'. $prev_zip .'" class="input" />
			</li>
			
			<li class="left" id="prev_country_heading">
				<p><label for="prev_country">Country</label></p>
				<select id="prev_country" name="prev_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($prev_country, 1) .'
				</select>				
			</li>
			<li class="right">
				<p>Years at Address</p>
				<label class="com_none" for="prev_address_year">Year</label>
				<label class="com_none" for="prev_address_month">Month</label>
				<div class="leftfield"><input type="text" id="prev_address_year" name="prev_address_year" value="'. $prev_address_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="prev_address_month" name="prev_address_month" value="'. $prev_address_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p>Own or Rent</p>
				<label class="com_none" for="prev_own_rent1">Own</label>
				<label class="com_none" for="prev_own_rent2">Rent</label>
				<input type="radio" id="prev_own_rent1" name="prev_own_rent" value="Own" class="radiobutton"'. $prev_own_rent1 .' />&nbsp;Own&nbsp;&nbsp;&nbsp;
				<input type="radio" id="prev_own_rent2" name="prev_own_rent" value="Rent" class="radiobutton"'. $prev_own_rent2 .' />&nbsp;Rent
			</li>
			<li class="right">
				<p><label for="prev_monthly_payment">Monthly Payment [$]</label></p>
				<input type="text" id="prev_monthly_payment" name="prev_monthly_payment" value="'. $prev_monthly_payment .'" class="input" />
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Current Employer</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="employer">Employer</label></p>
				<input type="text" id="employer" name="employer" value="'. $employer .'" class="input" />
			</li>
			<li class="right">
				<p><label for="emp_address">Address</label></p>
				<input type="text" id="emp_address" name="emp_address" value="'. $emp_address .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="emp_city">City</label></p>
				<input type="text" id="emp_city" name="emp_city" value="'. $emp_city .'" class="input" />
			</li>			
			<li class="right">
				<p><label for="emp_state">State</label></p>
				<input type="text" id="emp_state" name="emp_state" value="'. $emp_state .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="emp_zip">Postal Code</label></p>
				<input type="text" id="emp_zip" name="emp_zip" value="'. $emp_zip .'" class="input" />
			</li>
			<li class="right" id="emp_country_heading">
				<p><label for="emp_country">Country</label></p>
				<select id="emp_country" name="emp_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($emp_country, 1) .'
				</select>
			</li>
			
			<li class="left">
				<p><label for="emp_phone">Phone</label></p>
				<input type="text" id="emp_phone" name="emp_phone" value="'. $emp_phone .'" class="input" />
			</li>
			<li class="right">
				<p>Length of Employment</p>
				<label class="com_none" for="emp_year">Year</label>
				<label class="com_none" for="emp_month">Month</label>
				<div class="leftfield"><input type="text" id="emp_year" name="emp_year" value="'. $emp_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="emp_month" name="emp_month" value="'. $emp_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p><label for="emp_position">Position / Title</label></p>
				<input type="text" id="emp_position" name="emp_position" value="'. $emp_position .'" class="input" />
			</li>
			<li class="right">
				<p><label for="emp_supervisor">Supervisor</label></p>
				<input type="text" id="emp_supervisor" name="emp_supervisor" value="'. $emp_supervisor .'" class="input" />
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div id="pevious_employer" class="singleblock com_none">
	  <div class="singleblock_heading"><span>Previous Employer</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="prev_employer">Employer</label></p>
				<input type="text" id="prev_employer" name="prev_employer" value="'. $prev_employer .'" class="input" />
			</li>
			<li class="right">
				<p><label for="prev_emp_address">Address</label></p>
				<input type="text" id="prev_emp_address" name="prev_emp_address" value="'. $prev_emp_address .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="prev_emp_city">City</label></p>
				<input type="text" id="prev_emp_city" name="prev_emp_city" value="'. $prev_emp_city .'" class="input" />
			</li>			
			<li class="right">
				<p><label for="prev_emp_state">State</label></p>
				<input type="text" id="prev_emp_state" name="prev_emp_state" value="'. $prev_emp_state .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="prev_emp_zip">Postal Code</label></p>
				<input type="text" id="prev_emp_zip" name="prev_emp_zip" value="'. $prev_emp_zip .'" class="input" />
			</li>
			<li class="right" id="prev_emp_country_heading">
				<p><label for="prev_emp_country">Country</label></p>
				<select id="prev_emp_country" name="prev_emp_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($prev_emp_country, 1) .'
				</select>
			</li>
			
			<li class="left">
				<p><label for="prev_emp_phone">Phone</label></p>
				<input type="text" id="prev_emp_phone" name="prev_emp_phone" value="'. $prev_emp_phone .'" class="input" />
			</li>
			<li class="right">
				<p>Length of Employment</p>
				<label class="com_none" for="prev_emp_year">Year</label>
				<label class="com_none" for="prev_emp_month">Month</label>
				<div class="leftfield"><input type="text" id="prev_emp_year" name="prev_emp_year" value="'. $prev_emp_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="prev_emp_month" name="prev_emp_month" value="'. $prev_emp_month .'" class="input" placeholder="Months" /></div>
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock">
	  <div class="singleblock_heading"><span>Income</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="wages">Wages [$]</label></p>
				<input type="text" id="wages" name="wages" value="'. $wages .'" class="input" />
			</li>
			<li class="right">
				<p>Paid</p>
				<label class="com_none" for="paid1">Monthly</label>
				<label class="com_none" for="paid2">Annually</label>
				<input type="radio" id="paid1" name="paid" value="Monthly" class="radiobutton"'. $paid1.' />&nbsp;Monthly&nbsp;&nbsp;&nbsp;
				<input type="radio" id="paid2" name="paid" value="Annually" class="radiobutton"'. $paid2.' />&nbsp;Annually
			</li>
	  </ul>
	  <ul class="form">		
			<li class="left">
				<p><label for="oth_income">Other Income [$]</label></p>
				<input type="text" id="oth_income" name="oth_income" value="'. $oth_income .'" class="input" />
			</li>			
			<li class="right">
				<p>Paid</p>
				<label class="com_none" for="oth_income_paid1">Monthly</label>
				<label class="com_none" for="oth_income_paid2">Annually</label>
				<input type="radio" id="oth_income_paid1" name="oth_income_paid" value="Monthly" class="radiobutton"'. $oth_income_paid1.' />&nbsp;Monthly&nbsp;&nbsp;&nbsp;
				<input type="radio" id="oth_income_paid2" name="oth_income_paid" value="Annually" class="radiobutton"'. $oth_income_paid2.' />&nbsp;Annually
			</li>
			
			<li>
				<p><label for="oth_income_description">Other Income Description</label></p>
				<textarea name="oth_income_description" id="oth_income_description" rows="1" cols="1" class="comments">'. $oth_income_description .'</textarea>
				<p class="smalltext">Applicant does not need to disclose income from alimony, child support, or separate maintenance, unless applicant desires this income included in determining creditworthiness.</p>
			</li>
	  </ul>	  
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock">
	  <div class="singleblock_heading"><span>Other Information</span></div> 
	  <div class="singleblock_box singleblock_box_h">	
	  <ul class="form">	
      		<li class="left">
				<p>Prior Bankruptcy</p>
				<label class="com_none" for="prior_bankruptcy1">Yes</label>
				<label class="com_none" for="prior_bankruptcy2">No</label>
				<input type="radio" id="prior_bankruptcy1" name="prior_bankruptcy" value="1" class="prior_bankruptcy radiobutton"'. $prior_bankruptcy_checked_yes .'> Yes &nbsp;&nbsp;
				<input type="radio" id="prior_bankruptcy2" name="prior_bankruptcy" value="0" class="prior_bankruptcy radiobutton"'. $prior_bankruptcy_checked_no .'> No
			</li>
            <li class="right bankruptcyyear com_none">
				<p><label for="bankruptcy_year">Bankruptcy Year</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="bankruptcy_year" name="bankruptcy_year" value="" class="input">
			</li>
      </ul>   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="nearest_relative">Nearest Relative - not living with you</label></p>
				<input type="text" id="nearest_relative" name="nearest_relative" value="'. $nearest_relative .'" class="input" />
			</li>
			<li class="right">
				<p><label for="nearest_relative_relationship">Relationship</label></p>
				<input type="text" id="nearest_relative_relationship" name="nearest_relative_relationship" value="'. $nearest_relative_relationship .'" class="input" />
			</li>
	  </ul>
	  <ul class="form">		
			<li class="left">
				<p><label for="nearest_relative_phone">Phone</label></p>
				<input type="text" id="nearest_relative_phone" name="nearest_relative_phone" value="'. $nearest_relative_phone .'" class="input" />
			</li>	
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="clearfix"></div>
	  <div class="multistepformbutton">
		<button type="button" class="button nextbutton" nextstep="2"><span>Next</span></button>
	  </div>
	  
	  </div>
	  <!--Step 1 End-->
	  
	  <!--Step 2-->
	  <div id="form2" class="formstep com_none">
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Co-Applicant</span><span class="requiredinfo">* Required</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="co_app_fname">First Name</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="co_app_fname" name="co_app_fname" value="'. $co_app_fname .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_lname">Last Name</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="co_app_lname" name="co_app_lname" value="'. $co_app_lname .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_middle_name">Middle Name</label></p>
				<input type="text" id="co_app_middle_name" name="co_app_middle_name" value="'. $co_app_middle_name .'" class="input" />
			</li>			
			<li class="right">
				<p><label for="co_app_dob">Date of Birth (mm/dd/yyyy)</label></p>
				<input defaultdateset="01/01/1980" rangeyear="1900:'. (date("Y") - 18) .'" type="text" id="co_app_dob" name="co_app_dob" value="'. $co_app_dob .'" class="date-field-b input2" />
			</li>
			
			<li class="left">
				<p><label for="co_app_social_security">Social Security #</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="co_app_social_security" name="co_app_social_security" value="'. $co_app_social_security .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_drivers_license">Drivers License #</label></p>
				<input type="text" id="co_app_drivers_license" name="co_app_drivers_license" value="'. $co_app_drivers_license .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_drivers_license_state">Drivers License State</label></p>
				<select id="co_app_drivers_license_state" name="co_app_drivers_license_state" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_state_combo($co_app_drivers_license_state, 1) .'
				</select>
			</li>
			<li class="right">
				<p><label for="co_app_email">Email Address</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="co_app_email" name="co_app_email" value="'. $co_app_email .'" class="input" />
			</li>
						
			<li class="left">
				<p><label for="co_app_mobile">Mobile Phone</label></p>
				<input type="text" id="co_app_mobile" name="co_app_mobile" value="'. $co_app_mobile .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_app_phone">Work Phone</label></p>
				<input type="text" id="co_app_app_phone" name="co_app_app_phone" value="'. $co_app_app_phone .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_home_phone">Home Phone</label></p>
				<input type="text" id="co_app_home_phone" name="co_app_home_phone" value="'. $co_app_home_phone .'" class="input" />
			</li>
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Current Address</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="co_app_address">Address</label></p>
				<input type="text" id="co_app_address" name="co_app_address" value="'. $co_app_address .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_city">City</label></p>
				<input type="text" id="co_app_city" name="co_app_city" value="'. $co_app_city .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_state">State</label></p>
				<input type="text" id="co_app_state" name="co_app_state" value="'. $co_app_state .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_zip">Postal Code</label></p>
				<input type="text" id="co_app_zip" name="co_app_zip" value="'. $co_app_zip .'" class="input" />
			</li>
			
			<li class="left" id="co_app_country_heading">
				<p><label for="co_app_country">Country</label></p>
				<select id="co_app_country" name="co_app_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($co_app_country, 1) .'
				</select>
			</li>
			<li class="right">
				<p>Years at Address</p>
				<label class="com_none" for="co_app_address_year">Year</label>
				<label class="com_none" for="co_app_address_month">Month</label>
				<div class="leftfield"><input type="text" id="co_app_address_year" name="co_app_address_year" value="'. $co_app_address_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="co_app_address_month" name="co_app_address_month" value="'. $co_app_address_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p>Own or Rent</p>
				<label class="com_none" for="co_app_own_rent1">Own</label>
				<label class="com_none" for="co_app_own_rent2">Rent</label>
				<input type="radio" id="co_app_own_rent1" name="co_app_own_rent" value="Own" class="radiobutton"'. $co_app_own_rent1 .' />&nbsp;Own&nbsp;&nbsp;&nbsp;
				<input type="radio" id="co_app_own_rent2" name="co_app_own_rent" value="Rent" class="radiobutton"'. $co_app_own_rent2 .' />&nbsp;Rent
			</li>
			<li class="right">
				<p><label for="co_app_monthly_payment">Monthly Payment [$]</label></p>
				<input type="text" id="co_app_monthly_payment" name="co_app_monthly_payment" value="'. $co_app_monthly_payment .'" class="input" />
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div id="co_app_pevious_address" class="singleblock com_none"> 
	  <div class="singleblock_heading"><span>Previous Address</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="co_app_prev_address">Address</label></p>
				<input type="text" id="co_app_prev_address" name="co_app_prev_address" value="'. $co_app_prev_address .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_prev_city">City</label></p>
				<input type="text" id="co_app_prev_city" name="co_app_prev_city" value="'. $co_app_prev_city .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_prev_state">State</label></p>
				<input type="text" id="co_app_prev_state" name="co_app_prev_state" value="'. $co_app_prev_state .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_prev_zip">Postal Code</label></p>
				<input type="text" id="co_app_prev_zip" name="co_app_prev_zip" value="'. $co_app_prev_zip .'" class="input" />
			</li>
			
			<li class="left" id="co_app_prev_country_heading">
				<p><label for="co_app_prev_country">Country</label></p>
				<select id="co_app_prev_country" name="co_app_prev_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($co_app_prev_country, 1) .'
				</select>
			</li>
			<li class="right">
				<p>Years at Address</p>
				<label class="com_none" for="co_app_prev_address_year">Year</label>
				<label class="com_none" for="co_app_prev_address_month">Month</label>
				<div class="leftfield"><input type="text" id="co_app_prev_address_year" name="co_app_prev_address_year" value="'. $co_app_prev_address_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="co_app_prev_address_month" name="co_app_prev_address_month" value="'. $co_app_prev_address_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p>Own or Rent</p>
				<label class="com_none" for="co_app_prev_own_rent1">Own</label>
				<label class="com_none" for="co_app_prev_own_rent2">Rent</label>
				<input type="radio" id="co_app_prev_own_rent1" name="co_app_prev_own_rent" value="Own" class="radiobutton"'. $co_app_prev_own_rent1 .' />&nbsp;Own&nbsp;&nbsp;&nbsp;
				<input type="radio" id="co_app_prev_own_rent2" name="co_app_prev_own_rent" value="Rent" class="radiobutton"'. $co_app_prev_own_rent2 .' />&nbsp;Rent
			</li>
			<li class="right">
				<p><label for="co_app_prev_monthly_payment">Monthly Payment [$]</label></p>
				<input type="text" id="co_app_prev_monthly_payment" name="co_app_prev_monthly_payment" value="'. $co_app_prev_monthly_payment .'" class="input" />
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Current Employer</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="co_app_employer">Employer</label></p>
				<input type="text" id="co_app_employer" name="co_app_employer" value="'. $co_app_employer .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_emp_address">Address</label></p>
				<input type="text" id="co_app_emp_address" name="co_app_emp_address" value="'. $co_app_emp_address .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_emp_city">City</label></p>
				<input type="text" id="co_app_emp_city" name="co_app_emp_city" value="'. $co_app_emp_city .'" class="input" />
			</li>			
			<li class="right">
				<p><label for="co_app_emp_state">State</label></p>
				<input type="text" id="co_app_emp_state" name="co_app_emp_state" value="'. $co_app_emp_state .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_emp_zip">Postal Code</label></p>
				<input type="text" id="co_app_emp_zip" name="co_app_emp_zip" value="'. $co_app_emp_zip .'" class="input" />
			</li>
			<li class="right" id="co_app_emp_country_heading">
				<p><label for="co_app_emp_country">Country</label></p>
				<select id="co_app_emp_country" name="co_app_emp_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($co_app_emp_country, 1) .'
				</select>
			</li>
			
			<li class="left">
				<p><label for="co_app_emp_phone">Phone</label></p>
				<input type="text" id="co_app_emp_phone" name="co_app_emp_phone" value="'. $co_app_emp_phone .'" class="input" />
			</li>
			<li class="right">
				<p>Length of Employment</p>
				<label class="com_none" for="co_app_emp_year">Year</label>
				<label class="com_none" for="co_app_emp_month">Month</label>
				<div class="leftfield"><input type="text" id="co_app_emp_year" name="co_app_emp_year" value="'. $co_app_emp_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="co_app_emp_month" name="co_app_emp_month" value="'. $co_app_emp_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p><label for="co_app_emp_position">Position / Title</label></p>
				<input type="text" id="co_app_emp_position" name="co_app_emp_position" value="'. $co_app_emp_position .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_emp_supervisor">Supervisor</label></p>
				<input type="text" id="co_app_emp_supervisor" name="co_app_emp_supervisor" value="'. $co_app_emp_supervisor .'" class="input" />
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div id="co_app_pevious_employer" class="singleblock com_none">
	  <div class="singleblock_heading"><span>Previous Employer</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="co_app_prev_employer">Employer</label></p>
				<input type="text" id="co_app_prev_employer" name="co_app_prev_employer" value="'. $co_app_prev_employer .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_prev_emp_address">Address</label></p>
				<input type="text" id="co_app_prev_emp_address" name="co_app_prev_emp_address" value="'. $co_app_prev_emp_address .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_prev_emp_city">City</label></p>
				<input type="text" id="co_app_prev_emp_city" name="co_app_prev_emp_city" value="'. $co_app_prev_emp_city .'" class="input" />
			</li>			
			<li class="right">
				<p><label for="co_app_prev_emp_state">State</label></p>
				<input type="text" id="co_app_prev_emp_state" name="co_app_prev_emp_state" value="'. $co_app_prev_emp_state .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="co_app_prev_emp_zip">Postal Code</label></p>
				<input type="text" id="co_app_prev_emp_zip" name="co_app_prev_emp_zip" value="'. $co_app_prev_emp_zip .'" class="input" />
			</li>
			<li class="right" id="co_app_prev_emp_country_heading">
				<p><label for="co_app_prev_emp_country">Country</label></p>
				<select id="co_app_prev_emp_country" name="co_app_prev_emp_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($co_app_prev_emp_country, 1) .'
				</select>
			</li>
			
			<li class="left">
				<p><label for="co_app_prev_emp_phone">Phone</label></p>
				<input type="text" id="co_app_prev_emp_phone" name="co_app_prev_emp_phone" value="'. $co_app_prev_emp_phone .'" class="input" />
			</li>
			<li class="right">
				<p>Length of Employment</p>
				<label class="com_none" for="co_app_prev_emp_year">Year</label>
				<label class="com_none" for="co_app_prev_emp_month">Month</label>
				<div class="leftfield"><input type="text" id="co_app_prev_emp_year" name="co_app_prev_emp_year" value="'. $co_app_prev_emp_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="co_app_prev_emp_month" name="co_app_prev_emp_month" value="'. $co_app_prev_emp_month .'" class="input" placeholder="Months" /></div>
			</li>		
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock">
	  <div class="singleblock_heading"><span>Income</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="co_app_wages">Wages [$]</label></p>
				<input type="text" id="co_app_wages" name="co_app_wages" value="'. $co_app_wages .'" class="input" />
			</li>
			<li class="right">
				<p>Paid</p>
				<label class="com_none" for="co_app_paid1">Monthly</label>
				<label class="com_none" for="co_app_paid2">Annually</label>
				<input type="radio" id="co_app_paid1" name="co_app_paid" value="Monthly" class="radiobutton"'. $co_app_paid1.' />&nbsp;Monthly&nbsp;&nbsp;&nbsp;
				<input type="radio" id="co_app_paid2" name="co_app_paid" value="Annually" class="radiobutton"'. $co_app_paid2.' />&nbsp;Annually
			</li>
	  </ul>
	  <ul class="form">		
			<li class="left">
				<p><label for="co_app_oth_income">Other Income [$]</label></p>
				<input type="text" id="co_app_oth_income" name="co_app_oth_income" value="'. $co_app_oth_income .'" class="input" />
			</li>			
			<li class="right">
				<p>Paid</p>
				<label class="com_none" for="co_app_oth_income_paid1">Monthly</label>
				<label class="com_none" for="co_app_oth_income_paid2">Annually</label>
				<input type="radio" id="co_app_oth_income_paid1" name="co_app_oth_income_paid" value="Monthly" class="radiobutton"'. $co_app_oth_income_paid1.' />&nbsp;Monthly&nbsp;&nbsp;&nbsp;
				<input type="radio" id="co_app_oth_income_paid2" name="co_app_oth_income_paid" value="Annually" class="radiobutton"'. $co_app_oth_income_paid2.' />&nbsp;Annually
			</li>
			
			<li>
				<p><label for="co_app_oth_income_description">Other Income Description</label></p>
				<textarea name="co_app_oth_income_description" id="co_app_oth_income_description" rows="1" cols="1" class="comments">'. $co_app_oth_income_description .'</textarea>
				<p class="smalltext">Applicant does not need to disclose income from alimony, child support, or separate maintenance, unless applicant desires this income included in determining creditworthiness.</p>
			</li>
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock">
	  <div class="singleblock_heading"><span>Other Information</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="co_app_nearest_relative">Nearest Relative - not living with you</label></p>
				<input type="text" id="co_app_nearest_relative" name="co_app_nearest_relative" value="'. $co_app_nearest_relative .'" class="input" />
			</li>
			<li class="right">
				<p><label for="co_app_nearest_relative_relationship">Relationship</label></p>
				<input type="text" id="co_app_nearest_relative_relationship" name="co_app_nearest_relative_relationship" value="'. $co_app_nearest_relative_relationship .'" class="input" />
			</li>
	  </ul>
	  <ul class="form">			
			<li class="left">
				<p><label for="co_app_nearest_relative_phone">Phone</label></p>
				<input type="text" id="co_app_nearest_relative_phone" name="co_app_nearest_relative_phone" value="'. $co_app_nearest_relative_phone .'" class="input" />
			</li>	
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  
	  <div class="clearfix"></div>
	  <div class="multistepformbutton">
		<button type="button" class="button prevbutton" prevstep="1"><span>Back</span></button>
		<button type="button" class="button nextbutton" nextstep="3"><span>Next</span></button>
	  </div>
	  
	  </div>
	  <!--Step 2 End-->
	  
	  <!--Step 3-->
	  <div id="form3" class="formstep com_none">
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Boat Information</span><span class="requiredinfo">* Required</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="boat_manufacturer">Manufacturer</label></p>
				<input type="text" id="boat_manufacturer" name="boat_manufacturer" value="'. $boat_manufacturer .'" class="input" />
			</li>
			<li class="right">
				<p><label for="boat_model">Model</label></p>
				<input type="text" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="boat_year">Year</label></p>
				<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
			</li>
			<li class="right">
				<p><label for="boat_length">Length [ft]</label></p>
				<input type="text" id="boat_length" name="boat_length" value="'. $boat_length .'" class="input" />
			</li>
			
			<li class="left">
				<p><label for="boat_price">Price [$]</label></p>
				<input type="text" id="boat_price" name="boat_price" value="'. $boat_price .'" class="input" />
			</li>
			<li class="right">
				<p><label for="sales_agent_name">Sales Agent Name</label></p>
				<input type="text" id="sales_agent_name" name="sales_agent_name" value="'. $sales_agent_name .'" class="input" />
			</li>		
	  </ul>
	  <ul class="form">
      		<li class="left">
				<p><label for="engine_make">Engine Make</label></p>
				<input type="text" id="engine_make" name="engine_make" value="'. $engine_make .'" class="input">
			</li>
			<li class="right">
				<p><label for="engine_type">Engine Type</label></p>
				<select name="engine_type" id="engine_type" class="select">
				<option value="">Select</option>
                '. $this->get_engine_type_combo($engine_type, 1, 1).'                      
                </select>
			</li>
            
            <li class="left">
				<p><label for="drive_type">Drive Type</label></p>
				<select name="drive_type" id="drive_type" class="select">
				<option value="">Select</option>
                '. $this->get_drive_type_combo($drive_type, 1, 1).'                      
                </select>
			</li>  
            <li class="right">
				<p><label for="engine_no">Number of Engines</label></p>
				<select fieldvalue="" name="engine_no" id="engine_no" class="select">
				<option value="">Select</option>
                '. $yachtclass->get_common_number_combo($engine_no, 4, 1).'                      
                </select>
			</li>
            
            <li class="left">
				<p>Fuel Type</p>
				<label class="com_none" for="fuel_type1">Gas</label>
				<label class="com_none" for="fuel_type2">Diesel</label>
				<input type="radio" id="fuel_type1" name="fuel_type" value="Gas" class="radiobutton"'. $fuel_type1.'>&nbsp;Gas&nbsp;&nbsp;&nbsp;
				<input type="radio" id="fuel_type2" name="fuel_type" value="Diesel" class="radiobutton"'. $fuel_type2.'>&nbsp;Diesel
			</li>
            <li class="right">
				<p><label for="horsepower_individual">Horsepower Individual</label></p>
				<input fieldvalue="" type="text" id="horsepower_individual" name="horsepower_individual" value="'. $horsepower_individual .'" class="input">                
                <p>Horsepower Combined: <span class="horsepower_combined_v fontbold">'. $horsepower_combined .'</span></p>
			</li>
      </ul>
	  
	  <ul class="form">
      		<li>
				<p><label for="tradein">Is there a Trade-In</label>&nbsp;&nbsp;
				<input type="checkbox" id="tradein" name="tradein" value="1" class="checkbox"'. $tradein_checked .'>&nbsp;Yes
                </p>
			</li>
            
            <li class="left tradeinclass com_none">
				<p><label for="tradein_year">Trade-In Year</label></p>
				<input type="text" id="tradein_year" name="tradein_year" value="'. $tradein_year .'" class="input">
			</li>
			<li class="right tradeinclass com_none">
				<p><label for="tradein_make">Trade-In Make</label></p>
				<input type="text" id="tradein_make" name="tradein_make" value="'. $tradein_make .'" class="input">
			</li>
            
            <li class="left tradeinclass com_none">
				<p><label for="tradein_model">Trade-In Model</label></p>
				<input type="text" id="tradein_model" name="tradein_model" value="'. $tradein_model .'" class="input">
			</li>
			<li class="right tradeinclass com_none">
				<p><label for="tradein_length">Trade-In Length</label></p>
				<input type="text" id="tradein_length" name="tradein_length" value="'. $tradein_length .'" class="input">
			</li>
            
            <li class="left tradeinclass com_none">
				<p><label for="tradein_engine_make">Trade-In Engine Make</label></p>
				<input type="text" id="tradein_engine_make" name="tradein_engine_make" value="'. $tradein_engine_make .'" class="input">
			</li>
			<li class="right tradeinclass com_none">
				<p><label for="tradein_hp">Trade-In HP</label></p>
				<input type="text" id="tradein_hp" name="tradein_hp" value="'. $tradein_hp .'" class="input">
			</li>
            
            <li class="left tradeinclass com_none">
				<p><label for="tradein_engine_no">Trade-In # Engines</label></p>
				<select fieldvalue="tradein_" name="tradein_engine_no" id="tradein_engine_no" class="select">
				<option value="">Select</option>
                '. $yachtclass->get_common_number_combo($tradein_engine_no, 4, 1).'                     
                 </select>
			</li>
			
			<li class="right tradeinclass com_none">
				<p><label for="tradein_horsepower_individual">Trade-In Horsepower Individual</label></p>
				<input fieldvalue="tradein_" type="text" id="tradein_horsepower_individual" name="tradein_horsepower_individual" value="'. $tradein_horsepower_individual .'" class="input">                
                <p>Trade-In Horsepower Combined: <span class="tradein_horsepower_combined_v fontbold">'. $tradein_horsepower_combined .'</span></p>
			</li>
			
			<li class="left tradeinclass com_none">
				<p>Trade-In Fuel Type</p>
				<label class="com_none" for="tradein_fuel_type1">Gas</label>
				<label class="com_none" for="tradein_fuel_type2">Diesel</label>
				<input type="radio" id="tradein_fuel_type1" name="tradein_fuel_type" value="Gas" class="radiobutton"'. $tradein_fuel_type1 .'>&nbsp;Gas&nbsp;&nbsp;&nbsp;
				<input type="radio" id="tradein_fuel_type2" name="tradein_fuel_type" value="Diesel" class="radiobutton"'. $tradein_fuel_type2 .'>&nbsp;Diesel
			</li>
      </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Loan Information</span></div> 
	  <div class="singleblock_box singleblock_box_h">	   
	  <ul class="form">	   		
			<li class="left">
				<p><label for="purchase_price">Purchase Price [$]</label></p>
				<input type="text" id="purchase_price" name="purchase_price" value="'. $purchase_price .'" class="input" />
			</li>
			<li class="right">
				<p><label for="estimated_tax_rate">Estimated Tax Rate %</label></p>
				<input type="text" id="estimated_tax_rate" name="estimated_tax_rate" value="'. $estimated_tax_rate .'" class="input" />
			</li>
			
			<li>
				<p>Estimated Taxes Payable: <span class="estimated_tax_pay fontbold"></span></p>
			</li>
	  </ul>
	  <ul class="form">			
			<li class="left">				
				<p><label for="cash_down">Cash Down [$]</label></p>
				<div class="column1">
				<input type="text" id="cash_down" name="cash_down" value="'. $cash_down .'" class="input" />
				</div>
				
				<div class="column2 responsivespace">
				<label class="com_none" for="cash_down_per">Percent</label>
				<select id="cash_down_per" name="cash_down_per" class="select">	
				<option value="0">Select %</option>	
				'. $yachtclass->get_common_percent_combo($cash_down_per, 50, 5, 5, 1) .'			
				</select>
				</div>
			</li>
			<li class="right">
				<p>Net Purchase Amount: <span class="net_purchase_amount fontbold"></span></p>
			</li>
	  </ul>
	  <ul class="form">
			<li class="left tradeinclass com_none">
				<p><label for="trade_amount">Trade Amount [$]</label></p>
				<input type="text" id="trade_amount" name="trade_amount" value="'. $trade_amount .'" class="input" />
			</li>	
			<li class="right tradeinclass com_none">
				<p><label for="trade_payoff">Trade Payoff [$]</label></p>
				<input type="text" id="trade_payoff" name="trade_payoff" value="'. $trade_payoff .'" class="input" />
			</li>	
			
			<li>
				<p>Desired Loan Amount: <span class="desired_loan_amount fontbold"></span></p>
			</li>
	  </ul>
	  <div class="clear"></div>
	  </div>
	  </div>
	   
	  <div class="clearfix"></div>
	  <div class="multistepformbutton">
		<button type="button" class="button prevbutton" prevstep="2"><span>Back</span></button>
		<button type="button" class="button nextbutton" nextstep="4"><span>Next</span></button>
	  </div>
	   
	  </div>
	  <!--Step 3 End-->
	  
	  <!--Step 4-->
	  <div id="form4" class="formstep com_none">
	  	
	  <div class="singleblock"> 
	  <div class="singleblock_heading"><span>Personal Financial Statement</span><span class="requiredinfo">* Required</span></div> 
	  <div class="singleblock_box singleblock_box_h">
	  		'. $this->display_personal_financial_statement_form(1) .'
	  		<div class="clear"></div>
	  </div>
	  </div>
	  
	  <div class="clearfix"></div>
	  <div class="multistepformbutton">
		<button type="button" class="button prevbutton" prevstep="3"><span>Back</span></button>
		<button type="button" class="button nextbutton" nextstep="5"><span>Next</span></button>
	  </div>
	  	
	  </div>
	  <!--Step 4 End-->
	  
	  <!--Step 5-->
	  <div id="form5" class="formstep com_none">
	  
	  <div class="singleblock"> 
	  <div class="singleblock_box">	 
	  <p>
	  I (We) confirm that the information provided is complete and accurate to the best of my/our knowledge. 
	  I (We) authorize <strong>'. $finance_company_name .'</strong> and it\'s affiliated lending institutions to obtain information in 
	  connection with this application including credit investigation, employment history and any other information necessary to evaluate credit. 
	  Furthermore, this application shall remain the property of <strong>'. $finance_company_name .'</strong>.
	  </p>  
	  
	  <div class="credit_authorization">		  	    
		  <ul class="form">	   		
				<li class="t-center">
					<p>Applicant</p>
					<span class="creditauthcheckbox"><input type="checkbox" id="applicant_auth" name="applicant_auth" value="1" class="checkbox" /><label for="applicant_auth"> Credit Authorization</label></span>
				</li>
				<li class="t-center jointapplicant com_none">
					<p>Co-Applicant</p>
					<span class="cocreditauthcheckbox"><input type="checkbox" id="co_applicant_auth" name="co_applicant_auth" value="1" class="checkbox" /><label for="co_applicant_auth"> Credit Authorization</label></span>
				</li>	
		  </ul>
		  <div class="clear"></div>
	  </div>
	  
	  <p>
	  PATRIOT ACT NOTICE: To help the government fight the funding of terrorism and money laundering activities, 
	  Federal Law requires all financial institutions to obtain, verify and record information that identifies each person who opens an account. 
	  For the purposes of this section, account shall be understood to include loan accounts.
	  </p>
	  <div class="clear"></div>
	  </div>
	  </div>
	  
	  
	  <div class="clearfix"></div>
	  <div class="multistepformbutton">
			<button type="button" class="button prevbutton" prevstep="4"><span>Back</span></button>
			<button type="button" class="button submitbutton"><span>Submit</span></button>
	  </div>
	  
	  </div>				
	  <!--Step 5 End-->
	  ';
	  
	  $returntext .= '
	   </form>';
	  
	  $returntext .= '
	  		<div class="clear"></div>
		</div>
	  </div>
	  ';
	  
	  return $returntext;
    }
	
	public function submit_online_credit_application_form(){
	  if(($_POST['fcapi'] == "creditapplication")){
		  global $db, $cm, $edclass, $sdeml;
		  $p_ar = $_POST;
		  foreach($p_ar AS $key => $val){
			  ${$key} = $val;
			  if ($key == "boat_price" OR $key == "co_app_wages" OR $key == "oth_income" OR $key == "co_app_oth_income" OR $key == "purchase_price" OR $key == "estimated_tax_rate" OR $key == "cash_down" OR $key == "trade_amount" OR $key == "trade_payoff"){
				  ${$key} = round(${$key}, 2);
			  }elseif ($key == "application_type_id" 
			  		OR $key == "company_id" 
			  		OR $key == "boat_year" 
					OR $key == "address_year" 
					OR $key == "address_month" 
					OR $key == "prev_address_year" 
					OR $key == "prev_address_month" 
					OR $key == "emp_year" 
					OR $key == "emp_month" 
					OR $key == "prev_emp_year" 
					OR $key == "prev_emp_month" 
					OR $key == "co_app_address_year" 
					OR $key == "co_app_address_month" 
					OR $key == "co_app_prev_address_year" 
					OR $key == "co_app_prev_address_month" 
					OR $key == "co_app_emp_year" 
					OR $key == "co_app_emp_month" 
					OR $key == "co_app_prev_emp_year" 
					OR $key == "co_app_prev_emp_month" 
					OR $key == "applicant_auth" 
					OR $key == "co_applicant_auth" 
					OR $key == "country" 
					OR $key == "prev_country" 
					OR $key == "emp_country" 
					OR $key == "prev_emp_country" 					
					OR $key == "co_app_country" 
					OR $key == "co_app_prev_country" 
					OR $key == "co_app_emp_country" 
					OR $key == "co_app_prev_emp_country" 
					OR $key == "us_citizen" 
					OR $key == "citizen_country" 
					OR $key == "prior_bankruptcy" 
					OR $key == "engine_no" 
					OR $key == "tradein" 
					OR $key == "tradein_engine_no" 
					OR $key == "engine_type" 
					OR $key == "drive_type" 
					OR $key == "applicant_auth" 
					OR $key == "co_applicant_auth" 
					OR $key == "drivers_license_state" 
					OR $key == "co_app_drivers_license_state" OR $key == "s"){
				  ${$key} = round(${$key}, 0);
			  }
		  }
	  
		  
		  //create the session
		  $datastring = $this->session_field_credit_application();
		  $cm->create_session_for_form($datastring, $_POST);
		  //end
		  
		  $dob_a = $cm->set_date_format($dob);
		  $co_app_dob_a = $cm->set_date_format($co_app_dob);
		  
		  /*CHECKING*/
		  $red_pg = $_SESSION["s_backpage"];
		  $cm->field_validation($application_type_id, '', 'Application Type', $red_pg, '', '', 2, 'fr_');
		  $cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
		  
		  //$cm->field_validation($manufacturer , '', 'Manufacturer ', $red_pg, '', '', 1, 'fr_');
		  //$cm->field_validation($boat_price , '', 'Price ', $red_pg, '', '', 1, 'fr_');
		  
		  //$cm->field_validation($middle_name , '', 'Middle Name ', $red_pg, '', '', 1, 'fr_');
		  //$cm->field_validation($dob, '', 'Date of birth', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($social_security , '', 'Social Security #', $red_pg, '', '', 1, 'fr_');
		  //$cm->field_validation($drivers_license , '', 'Drivers License #', $red_pg, '', '', 1, 'fr_');
		  //$cm->field_validation($drivers_license_state , '', 'Drivers License State', $red_pg, '', '', 1, 'fr_');
		  
		  //Address
		  /*$cm->field_validation($address , '', 'Address', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($city , '', 'City', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($state , '', 'State', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($country , '', 'Country', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($zip , '', 'Postal Code', $red_pg, '', '', 1, 'fr_');*/
		  
		  //Previous Address
		  if ($address_year < 3){
			  /*$cm->field_validation($prev_address , '', 'Previous Address', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_city , '', 'Previous City', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_state , '', 'Previous State', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_country , '', 'Previous Country', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_zip , '', 'Previous Postal Code', $red_pg, '', '', 1, 'fr_');*/
		  }
		  
		  //Current Employer
		  /*$cm->field_validation($employer , '', 'Current Employer', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($emp_address , '', 'Current Employer Address', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($emp_city , '', 'Current Employer City', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($emp_state , '', 'Current Employer State', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($emp_country , '', 'Current Employer Country', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($emp_zip , '', 'Current Employer Postal Code', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($emp_phone , '', 'Current Employer Postal Code', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($emp_position , '', 'Current Employer Position / Title', $red_pg, '', '', 1, 'fr_');*/
		  
		  //Previous Employer
		  if ($emp_year < 3){
			  /*$cm->field_validation($prev_employer , '', 'Previous Employer', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_emp_address , '', 'Previous Employer Address', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_emp_city , '', 'Previous Employer City', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_emp_state , '', 'Previous Employer State', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_emp_country , '', 'Previous Employer Country', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_emp_zip , '', 'Previous Employer Postal Code', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($prev_emp_phone , '', 'Previous Employer Postal Code', $red_pg, '', '', 1, 'fr_');*/
		  }
		  
		  //Income
		  //$cm->field_validation($wages , '', 'Wages', $red_pg, '', '', 1, 'fr_');
		  
		  if ($applicant_auth != 1){
				$_SESSION["fr_postmessage"] = 'Authorization Error.';
				header('Location: '.$red_pg);
				exit;
		  }
		  
		  //Co-Applicant
		  if ($application_type_id == 2){
			  $cm->field_validation($co_app_fname, '', 'Co-Applicant First Name', $red_pg, '', '', 1, 'fr_');
		  	  $cm->field_validation($co_app_lname, '', 'Co-Applicant Last Name', $red_pg, '', '', 1, 'fr_');
			  //$cm->field_validation($co_app_dob, '', 'Co-Applicant Date of birth', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_social_security , '', 'Co-Applicant Social Security #', $red_pg, '', '', 1, 'fr_');
			  //$cm->field_validation($co_app_drivers_license , '', 'Co-Applicant Drivers License #', $red_pg, '', '', 1, 'fr_');
			  //$cm->field_validation($co_app_drivers_license_state , '', 'Co-Applicant Drivers License State', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_email, '', 'Co-Applicant Email Address', $red_pg, '', '', 1, 'fr_');
			  
			  //Current Address
			  /*$cm->field_validation($co_app_address , '', 'Co-Applicant Address', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_city , '', 'Co-Applicant City', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_state , '', 'Co-Applicant State', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_country , '', 'Co-Applicant Country', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_zip , '', 'Co-Applicant Postal Code', $red_pg, '', '', 1, 'fr_');*/
			  
			  //Previous Address
			  if ($co_app_address_year < 3){
				  /*$cm->field_validation($co_app_prev_address , '', 'Co-Applicant Previous Address', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_city , '', 'Co-Applicant Previous City', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_state , '', 'Co-Applicant Previous State', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_country , '', 'Co-Applicant Previous Country', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_zip , '', 'Co-Applicant Previous Postal Code', $red_pg, '', '', 1, 'fr_');*/
			  }
			  
			  //Current Employer
			  /*$cm->field_validation($co_app_employer , '', 'Co-Applicant Current Employer', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_emp_address , '', 'Co-Applicant Current Employer Address', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_emp_city , '', 'Co-Applicant Current Employer City', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_emp_state , '', 'Co-Applicant Current Employer State', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_emp_country , '', 'Co-Applicant Current Employer Country', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_emp_zip , '', 'Co-Applicant Current Employer Postal Code', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_emp_phone , '', 'Co-Applicant Current Employer Postal Code', $red_pg, '', '', 1, 'fr_');
			  $cm->field_validation($co_app_emp_position , '', 'Co-Applicant Current Employer Position / Title', $red_pg, '', '', 1, 'fr_');*/
			  
			  //Previous Employer
			  if ($co_app_emp_year < 3){
				  /*$cm->field_validation($co_app_prev_employer , '', 'Co-Applicant Previous Employer', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_emp_address , '', 'Co-Applicant Previous Employer Address', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_emp_city , '', 'Co-Applicant Previous Employer City', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_emp_state , '', 'Co-Applicant Previous Employer State', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_emp_country , '', 'Co-Applicant Previous Employer Country', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_emp_zip , '', 'Co-Applicant Previous Employer Postal Code', $red_pg, '', '', 1, 'fr_');
				  $cm->field_validation($co_app_prev_emp_phone , '', 'Co-Applicant Previous Employer Postal Code', $red_pg, '', '', 1, 'fr_');*/
			  }
			  
			  //Income
		  	  //$cm->field_validation($co_app_wages , '', 'Co-Applicant Wages', $red_pg, '', '', 1, 'fr_');
			  
			  if ($co_applicant_auth != 1){
				  $_SESSION["fr_postmessage"] = 'Authorization Error.';
				  header('Location: '.$red_pg);
				  exit;
			  }
		  }
		  
		  //Loan Information
		  /*$cm->field_validation($purchase_price , '', 'Purchase Price', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($estimated_tax_rate , '', 'Estimated Tax Rate %', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($cash_down , '', 'Cash Down', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($trade_amount , '', 'Trade Amount', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($trade_payoff , '', 'Trade Payoff', $red_pg, '', '', 1, 'fr_');*/
		  
		  if ($email2 != ""){
			header('Location: '. $cm->site_url .'');
			exit;
		  }
		  //end
		  
		  //insert to db
		  //$formfieldsar = json_encode($p_ar);
		  $reg_date = date("Y-m-d H:i:s");
		  $sql = "insert into tbl_credit_application (application_type_id
		  											, first_name
													, last_name
													, email
													, phone
													, ssn																								
													, reg_date) values ('". $application_type_id ."'
													, '". $cm->filtertext($fname) ."'
													, '". $cm->filtertext($lname) ."'
													, '". $cm->filtertext($email) ."'
													, '". $cm->filtertext($phone) ."'
													, '". $cm->filtertext($edclass->text_encode($social_security)) ."'													
													, '". $reg_date ."')";
		  
		  //$db->mysqlquery($sql); 
		  $application_id = $db->mysqlquery_ret($sql); 
		  
		  foreach($p_ar AS $key => $val){
			  
			  if ($key == "email2" OR $key == "fcapi" OR $key == "company_id"){
				  continue;
			  }
			  		  
			  if ($key == "social_security" OR $key == "drivers_license" OR $key == "dob" OR $key == "co_app_social_security" OR $key == "co_app_drivers_license" OR $key == "co_app_dob"){
				  $val = $edclass->text_encode($val);
			  }
			  
			  $sql = "insert into tbl_credit_application_value (application_id, keyname, keyvalue) values ('". $application_id ."', '". $cm->filtertext($key) ."', '". $cm->filtertext($val) ."')";
			  $db->mysqlquery($sql);
		  }		  
		  //end
		  
		  $cm->delete_session_for_form($datastring);
		  $application_type_name = $cm->get_common_field_name('tbl_credit_application_type', 'name', $application_type_id);
		  $oth_income_description = nl2br($oth_income_description);
		  $co_app_oth_income_description = nl2br($co_app_oth_income_description);
		  
		  //state name
		  $drivers_license_state_name = $cm->get_common_field_name('tbl_state', 'code', $drivers_license_state);
		  $co_app_drivers_license_state_name = $cm->get_common_field_name('tbl_state', 'code', $co_app_drivers_license_state);
		  
		  //country name
		  $country_name = $cm->get_common_field_name('tbl_country', 'name', $country);
		  $prev_country_name = $cm->get_common_field_name('tbl_country', 'name', $prev_country);
		  
		  $emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $emp_country);
		  $prev_emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $prev_emp_country);
		  
		  $co_app_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_country);
		  $co_app_prev_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_prev_country);
		  
		  $co_app_emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_emp_country);
		  $co_app_prev_emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_prev_emp_country);
		  
		  $us_citizen_text = $cm->set_yesyno_field($us_citizen);
		  $prior_bankruptcy_text = $cm->set_yesyno_field($prior_bankruptcy);
		  $tradein_text = $cm->set_yesyno_field($tradein);
		  
		  $engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type);
		  $drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type);
		  
		  $horsepower_combined = $engine_no * $horsepower_individual;
		  $tradein_horsepower_combined = $tradein_engine_no * $tradein_horsepower_individual;
		  
		  $corp_llc_trust_text = '';
		  if ($application_type_id == 3){
			  $corp_llc_trust_text = '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Corp/LLC/Trust Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($crop_llc_trust_name, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">EIN:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($llc_ein, 1) .'</td>
			  </tr>
			  ';
		  }
		  
		  /*-------------CREATE EMAIL MESSAGE---------------*/		  
		  $emailmessage = '';
		  
		  /*-----------asset----------------*/	
			$total_asset = 0;		
			$counter = 0;
			$fieldsets = $this->asset_form_common_fields();
			$fieldsets = (object)$fieldsets;
			
			$assets_text = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="3"><strong>Assets</strong></td>
				</tr>
				
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Asset Name</td>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Financial Institution</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$ amount</td>
				</tr>
			';
			
			//set 1
			$fieldset1 = $fieldsets->set1;
			foreach($fieldset1 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$financial_inst = $_POST["financial_inst" . $counter];
				$assetamt = round($_POST["assetamt" . $counter], 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $cm->filtertextdisplay($financial_inst, 1) .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="3">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 2
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'"><strong>Real Estate</strong></td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'"><strong>Property Location</strong></td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right"><strong>Income</strong></td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right"><strong>Present Value</strong></td>
				</tr>
			';
			
			$fieldset2 = $fieldsets->set2;
			foreach($fieldset2 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$financial_inst = $_POST["financial_inst" . $counter];
				$assetincome = round($_POST["assetincome" . $counter], 2);
				$assetamt = round($_POST["assetamt" . $counter], 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $cm->filtertextdisplay($financial_inst, 1) .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetincome, 2) .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 3
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset3 = $fieldsets->set3;
			foreach($fieldset3 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				//$financial_inst = $_POST["financial_inst" . $counter];
				//$assetincome = round($_POST["assetincome" . $counter], 2);
				$assetamt = round($_POST["assetamt" . $counter], 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="2">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 4
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset4 = $fieldsets->set4;
			foreach($fieldset4 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				//$financial_inst = $_POST["financial_inst" . $counter];
				//$assetincome = round($_POST["assetincome" . $counter], 2);
				$assetamt = round($_POST["assetamt" . $counter], 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="2">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 5
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">	
				<tr>
					<td width="100%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Other Non-Titled Assets (describe)</strong></td>
				</tr>			
			';
			
			$fieldset5 = $fieldsets->set5;
			foreach($fieldset5 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$financial_inst = $_POST["financial_inst" . $counter];
				$assetamt = round($_POST["assetamt" . $counter], 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="2">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">	
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Total Assets</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$'. $cm->format_price($total_asset, 2) .'</td>
				</tr>	
			</table>			
			';
			
			/*-----------asset end----------------*/
			
			/*-----------liabilities----------------*/
			$total_liabilities = 0;
			$counter = 0;
			$fieldsets = $this->liabilities_form_common_fields();
			$fieldsets = (object)$fieldsets;			
			
			//set 1
			$liabilities_text = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="3"><strong>Liabilities</strong></td>
				</tr>
				
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Name Of Liability</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Financial Institution</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$ amount</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">PMT</td>
				</tr>
			';
			
			$fieldset1 = $fieldsets->set1;
			foreach($fieldset1 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = $_POST["l_financial_inst" . $counter];
				$liabilitiesamt = round($_POST["liabilitiesamt" . $counter], 2);
				$pmt = round($_POST["pmt" . $counter], 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 2
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset2 = $fieldsets->set2;
			foreach($fieldset2 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = $_POST["l_financial_inst" . $counter];
				$liabilitiesamt = round($_POST["liabilitiesamt" . $counter], 2);
				$pmt = round($_POST["pmt" . $counter], 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 3
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset3 = $fieldsets->set3;
			foreach($fieldset3 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = $_POST["l_financial_inst" . $counter];
				$liabilitiesamt = round($_POST["liabilitiesamt" . $counter], 2);
				$pmt = round($_POST["pmt" . $counter], 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 4
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset4 = $fieldsets->set4;
			foreach($fieldset4 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = $_POST["l_financial_inst" . $counter];
				$liabilitiesamt = round($_POST["liabilitiesamt" . $counter], 2);
				$pmt = round($_POST["pmt" . $counter], 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 5
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset5 = $fieldsets->set5;
			foreach($fieldset5 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = $_POST["l_financial_inst" . $counter];
				$liabilitiesamt = round($_POST["liabilitiesamt" . $counter], 2);
				$pmt = round($_POST["pmt" . $counter], 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">	
				<tr>
					<td width="70%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Total Liabilities</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$'. $cm->format_price($total_liabilities, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">&nbsp;</td>
				</tr>
				
				<tr>
					<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="3">&nbsp;</td>
				</tr>	
			</table>			
			';
			
			$net_worth = $total_asset - $total_liabilities;
			
			$asset_liabilities_final_text = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="80%">Total Assets:</td>
				   <td align="right" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="20%">$'. $cm->format_price($total_asset, 2) .'</td>
			  	</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="80%">Total Liabilities:</td>
				   <td align="right" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="20%">$'. $cm->format_price($total_liabilities, 2) .'</td>
			  	</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="80%">Net Worth:</td>
				   <td align="right" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="20%">$'. $cm->format_price($net_worth, 2) .'</td>
			  	</tr>
			</table>
			';
			
			/*-----------liabilities end----------------*/
		  		  
		  //Applicant		  
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
					<td align="center" valign="top" style="padding: 15px 5px 5px 0px; font-size: 18px" colspan="2">'. $company_name .'</td>
			  </tr>
			  	
		  	  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Applicant</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Application Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $application_type_name .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">First Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Middle Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($middle_name, 1) .'</td>
			  </tr>
			  
			  '. $corp_llc_trust_text .'
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Dob:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($dob, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Social Security:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($social_security, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License #:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drivers_license, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drivers_license_state_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Mobile Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($mobile, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Work Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Home Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($home_phone, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">U.S. Citizen:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $us_citizen_text .'</td>
			  </tr>
		  ';
		  
		  if ($us_citizen == 0){
			  $citizen_country_name = $cm->get_common_field_name('tbl_country', 'name', $citizen_country);
			  $emailmessage .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country of Citizenship:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $citizen_country_name .'</td>
			  </tr>
			  ';
		  }
		  
		  $emailmessage .= '
		  </table>
		  ';
		  
		  //Current Address
		  $years_address = '';
		  if ($address_year >= 0){
			  $years_address .= $address_year . ' Years - ';
		  }
		  if ($address_month > 0){
			  $years_address .= $address_month . ' Months';
		  }
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
			   		<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Address:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($address, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($city, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($state, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($zip, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($country_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($years_address, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($own_rent, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($monthly_payment, 2) .'</td>
			  </tr>		  
		  </table>	  
		  ';
		  
		  //Previous Address
		  if ($address_year < 3){
			  $prev_years_address = '';
			  if ($prev_address_year >= 0){
				  $prev_years_address .= $prev_address_year . ' Years - ';
			  }
			  if ($prev_address_month > 0){
				  $prev_years_address .= $prev_address_month . ' Months';
			  }
			  $emailmessage .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Address:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_years_address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_own_rent, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($prev_monthly_payment, 2) .'</td>
				  </tr>		  
			  </table>	  
			  ';
		  }
		  
		  //Current Employer
		  $emp_length = '';
		  if ($emp_year >= 0){
			  $emp_length .= $emp_year . ' Years - ';
		  }
		  if ($emp_month > 0){
			  $emp_length .= $emp_month . ' Months';
		  }
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
			   		<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Employer:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($employer, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_address, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_city, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_state, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_zip, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_country_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_phone, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_length, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Position / Title:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_position, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Supervisor:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_supervisor, 1) .'</td>
			  </tr>		  
		  </table>	  
		  ';
		  
		  //Previous Employer
		  if ($emp_year < 3){
			  $prev_emp_length = '';
			  if ($prev_emp_year >= 0){
				  $prev_emp_length .= $prev_emp_year . ' Years - ';
			  }
			  if ($prev_emp_month > 0){
				  $prev_emp_length .= $prev_emp_month . ' Months';
			  }
			  $emailmessage .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Employer:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_employer, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_length, 1) .'</td>
				  </tr>	  
			  </table>	  
			  ';
		  }
		  
		  //Income
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
			   		<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Income:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Wages:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($wages, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($paid, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($oth_income, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($oth_income_paid, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income Description:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($oth_income_description, 1) .'</td>
			  </tr>
		  </table>
		  ';	  
			  
			 
		  
		  //Other Information
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
			   		<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Other Information:</strong></td>
			  </tr>
			  
			   <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Prior Bankruptcy:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $prior_bankruptcy_text .'</td>
			  </tr>	  
		  ';
		  
		  if ($prior_bankruptcy == 1){			  
			  $emailmessage .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Bankruptcy Year:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $bankruptcy_year .'</td>
			  </tr>
			  ';
		  }
		  
		  $emailmessage .= '
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Nearest Relative:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($nearest_relative, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Relationship:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($nearest_relative_relationship, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($nearest_relative_phone, 1) .'</td>
			  </tr>  
		  </table>	  
		  ';
		  
		  //Co-Applicant
		  if ($application_type_id == 2){
			  $emailmessage .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Co-Applicant</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_fname, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_lname, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Middle Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_middle_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Dob:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_dob, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Social Security:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_social_security, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License #:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_drivers_license, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_drivers_license_state_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_email, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Mobile Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_mobile, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Work Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Home Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_home_phone, 1) .'</td>
				  </tr>			  
			  </table>	  
			  ';
			  
			  //Current Address
			  $co_app_years_address = '';
			  if ($co_app_address_year >= 0){
				  $co_app_years_address .= $co_app_address_year . ' Years - ';
			  }
			  if ($co_app_address_month > 0){
				  $co_app_years_address .= $co_app_address_month . ' Months';
			  }
			  $emailmessage .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Address:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_years_address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_own_rent, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_monthly_payment, 2) .'</td>
				  </tr>		  
			  </table>	  
			  ';
			  
			  //Previous Address
			  if ($co_app_address_year < 3){
				  $co_app_prev_years_address = '';
				  if ($co_app_prev_address_year >= 0){
					  $co_app_prev_years_address .= $co_app_prev_address_year . ' Years - ';
				  }
				  if ($co_app_prev_address_month > 0){
					  $co_app_prev_years_address .= $co_app_prev_address_month . ' Months';
				  }
				  $emailmessage .= '
				  <table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr>
							<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Address:</strong></td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_address, 1) .'</td>
					  </tr>	
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_city, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_state, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_zip, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_country_name, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_years_address, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_own_rent, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_prev_monthly_payment, 2) .'</td>
					  </tr>		  
				  </table>	  
				  ';
			  }
			  
			  //Current Employer
			  $co_app_emp_length = '';
			  if ($co_app_emp_year >= 0){
				  $co_app_emp_length .= $co_app_emp_year . ' Years&nbsp;&nbsp;';
			  }
			  if ($co_app_emp_month > 0){
				  $co_app_emp_length .= $co_app_emp_month . ' Months';
			  }
			  $emailmessage .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Employer:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_employer, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_length, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Position / Title:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_position, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Supervisor:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_supervisor, 1) .'</td>
				  </tr>		  
			  </table>	  
			  ';
			  
			  //Previous Employer
			  if ($co_app_emp_year < 3){
				  $co_app_prev_emp_length = '';
				  if ($co_app_prev_emp_year >= 0){
					  $co_app_prev_emp_length .= $co_app_prev_emp_year . ' Years&nbsp;&nbsp;';
				  }
				  if ($co_app_prev_emp_month > 0){
					  $co_app_prev_emp_length .= $co_app_prev_emp_month . ' Months';
				  }
				  $emailmessage .= '
				  <table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr>
							<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Employer:</strong></td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_employer, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_address, 1) .'</td>
					  </tr>	
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_city, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_state, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_zip, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_country_name, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_phone, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_length, 1) .'</td>
					  </tr>	  
				  </table>	  
				  ';
			  }
			  
			  //Income
			  $emailmessage .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Income:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Wages:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_wages, 2) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_paid, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_oth_income, 2) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_oth_income_paid, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income Description:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_oth_income_description, 1) .'</td>
				  </tr>	  
			  </table>	  
			  ';
			  
			  //Other Information
			  $emailmessage .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Other Information:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Nearest Relative:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_nearest_relative, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Relationship:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_nearest_relative_relationship, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_nearest_relative_phone, 1) .'</td>
				  </tr>  
			  </table>	  
			  ';
		  }
		  
		  //Boat Information
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Boat Information</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Manufacturer:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_manufacturer, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_length, 1) .' ft</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Price:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($boat_price, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Sales Agent Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($sales_agent_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Make:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_make, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_type_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drive Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drive_type_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Fuel Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fuel_type, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Number of Engines:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_no, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Horsepower Individual:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($horsepower_individual, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Horsepower Combined:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($horsepower_combined, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Is there a Trade-In?</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_text .'</td>
			  </tr>
		  ';
		  
		  if ($tradein == 1){			  
			  $emailmessage .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Year:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_year .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Make:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_make .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Model:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_model .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Length:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_length .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Engine Make:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_engine_make .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In HP:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_hp .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In # Engines:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_engine_no .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Fuel Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_fuel_type .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Horsepower Individual:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($tradein_horsepower_individual, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Horsepower Combined:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($tradein_horsepower_combined, 1) .'</td>
			  </tr>
			  ';
		  }
		  
		  $emailmessage .= '
		  </table>
		  ';	  
		  
		  //Loan Information
		  $estimated_tax_pay = ($purchase_price * $estimated_tax_rate)/100;
		  $estimated_tax_pay = round($estimated_tax_pay, 2);
		  
		  $net_purchase_amount = $purchase_price + $estimated_tax_pay - $cash_down;
		  $net_purchase_amount = round($net_purchase_amount, 2);
		  
		  $desired_loan_amount = $net_purchase_amount - $trade_amount + $trade_payoff;
		  $desired_loan_amount = round($desired_loan_amount, 2);
		  
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
			   		<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Loan Information:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Purchase Price:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($purchase_price, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Estimated Tax Rate %:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->format_price($estimated_tax_rate, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Estimated Taxes Payable:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($estimated_tax_pay, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Cash Down:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($cash_down, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Net Purchase Amount:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($net_purchase_amount, 2) .'</td>
			  </tr>
			  ';
			  
			  if ($tradein == 1){
				  $emailmessage .= '
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade Amount:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($trade_amount, 2) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade Payoff:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($trade_payoff, 2) .'</td>
				  </tr>
				  ';
			  }
			  
			  $emailmessage .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Desired Loan Amount:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($desired_loan_amount, 2) .'</td>
			  </tr>
		  </table>
		  ';
		  
		  //Personal Financial Statement
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Personal Financial Statement</strong></td>
			  </tr>
		  </table>	  
		  ' . $assets_text . $liabilities_text . $asset_liabilities_final_text;
		  
		  //submit form
		  if ($applicant_auth == 1){
			  $applicant_auth_d = 'Yes';
		  }else{
			  $applicant_auth_d = 'No';
		  }
		  
		  if ($co_applicant_auth == 1){
			  $co_applicant_auth_d = 'Yes';
		  }else{
			  $co_applicant_auth_d = 'No';
		  }
		  $emailmessage .= '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		  	  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Authorization</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Applicant:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $applicant_auth_d .'</td>
			  </tr>
		  ';			  
		  
		  if ($application_type_id == 2){	  
		  $emailmessage .= '
		  	  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Co-Applicant:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $co_applicant_auth_d .'</td>
			  </tr>
		  ';
		  }
		  
		  $emailmessage .= '
		  </table>	  
		  ';
		  
		    //pdf attachment	
			$folderpath = '';
			$pdftext = $this->create_credit_application_content($application_id);
			$filename = "credit-application-" . $application_id . ".pdf";
			$pdfcontent = $cm->generate_pdf($folderpath, $pdftext, '', '', 'S');
			$attachfile = array();
			$attachfile[0]["attype"] = 1;
			$attachfile[0]["contenttype"] = "application/pdf";
			$attachfile[0]["name"] = $filename;
			$attachfile[0]["pdfdata"] = $pdfcontent;
			//end

		  //Send Email		  
		  $send_ml_id = 3;
		  $email_ar = $cm->get_table_fields('tbl_brokerage_services_email', 'agent_name, agent_email, agent_phone, agent_fax, company_name, company_email, cc_email, siteadmin, othersend, email_subject, pdes', $send_ml_id);
		  $agent_name = $email_ar[0]["agent_name"];
		  $agent_email = $email_ar[0]["agent_email"];
		  $agent_phone = $email_ar[0]["agent_phone"];
		  $agent_fax = $email_ar[0]["agent_fax"];
		  
		  $finance_company_name = $email_ar[0]["company_name"];
		  $finance_company_email = $email_ar[0]["company_email"];
		  
		  $oth_email = $email_ar[0]["cc_email"];
		  $siteadmin_send = $email_ar[0]["siteadmin"];
		  $other_send = $email_ar[0]["othersend"];
		  
		  $mail_subject = $email_ar[0]["email_subject"];
		  $msg = $email_ar[0]["pdes"];
		  $companyname = $cm->sitename;
			
		  $msg = str_replace("#name#", $cm->filtertextdisplay($finance_company_name), $msg);
		  $msg = str_replace("#messagedetails#", $emailmessage, $msg);
		  $msg = str_replace("#companyname#", $companyname, $msg);
		  $mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
		  $mail_fm = $cm->admin_email();
		  $mail_to = $cm->filtertextdisplay($finance_company_email);
		  $mail_cc = '';
		  $mail_bcc = '';
		  $mail_reply = '';		 		  
		  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u, $attachfile);
		  
		  //send email to Applicant
		  $send_ml_id = 13;
		  $app_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
		  $app_msg = $app_email_ar[0]["pdes"];
		  $app_mail_subject = $email_ar[0]["email_subject"];
		  
		  $applicantname = $fname . ' '. $lname;
		  $boatinfo = $boat_year . ' ' . $boat_manufacturer . ' ' . $boat_model;
		  
		  $agentinformation = '';
		  if ($agent_name != ""){
			  $agentinformation .= $agent_name . '<br />';
		  }
		  
		  if ($agent_phone != ""){
			  $agentinformation .= 'Phone: ' . $agent_phone . '<br />';
		  }
		  
		  if ($agent_fax != ""){
			  $agentinformation .= 'Fax: ' . $agent_fax . '<br />';
		  }
		  
		  if ($agent_email != ""){
			  $agentinformation .= $agent_email . '<br />';
		  }
		  
		  $agentinformation = rtrim($agentinformation, '<br />');
		  
		  $app_msg = str_replace("#applicantname#", $cm->filtertextdisplay($applicantname), $app_msg);
		  $app_msg = str_replace("#boatinfo#", $cm->filtertextdisplay($boatinfo), $app_msg);
		  $app_msg = str_replace("#applicationcompany#", $cm->filtertextdisplay($finance_company_name), $app_msg);
		  
		  $app_msg = str_replace("#agentinformation#", $agentinformation, $app_msg);
		  $app_msg = str_replace("#companyname#", $companyname, $app_msg);
		  $app_mail_subject = str_replace("#companyname#", $companyname, $app_mail_subject);
		  
		  $mail_fm = $cm->admin_email();
		  $mail_to = $cm->filtertextdisplay($email);
		  $mail_cc = '';
		  $mail_bcc = '';
		  $mail_reply = '';
		  if ($cc_email != ""){ $mail_cc = $mail_cc . ', ' . $cc_email; }
		  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $app_mail_subject, $app_msg, $cm->site_url, $news_footer_u);
		  
		  //send notification email
		  if ($siteadmin_send == 1 OR $other_send == 1){
			  $mail_fm = $cm->admin_email();
			  $mail_to = '';
			  $mail_cc = '';
		  	  $mail_bcc = '';
			  $mail_reply = $cm->filtertextdisplay($email);
			  
			  if ($siteadmin_send == 1){
			  	  $mail_to = $cm->admin_email_to() . ', ';
			  }
			  
			  if ($oth_email != ""){ 
				  $mail_to = $mail_to . ', ' . $oth_email; 
			  }
			  $mail_to = rtrim($mail_to, ', ');
			  
			  if ($mail_to != ""){
				  $send_nf_id = 14;
				  $nf_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_nf_id);
				  $nf_msg = $nf_email_ar[0]["pdes"];
				  $nf_mail_subject = $nf_email_ar[0]["email_subject"];
				  
				  $applicantinformation = $applicantname . '<br />';
				  if ($mobile != ""){
					  $applicantinformation .= 'Mobile: ' . $mobile . '<br />';
				  }
				  
				  if ($phone != ""){
					  $applicantinformation .= 'Work: ' . $phone . '<br />';
				  }
				  $applicantinformation .= $email . '<br />';
				  
				  $vesselinformation = $boatinfo;
				  
				  $nf_msg = str_replace("#applicantinformation#", $cm->filtertextdisplay($applicantinformation), $nf_msg);
				  $nf_msg = str_replace("#vesselinformation#", $cm->filtertextdisplay($vesselinformation), $nf_msg);
				  $nf_msg = str_replace("#agentinformation#", $agentinformation, $nf_msg);
				  $nf_msg = str_replace("#applicationcompany#", $cm->filtertextdisplay($finance_company_name), $nf_msg);
				  $nf_msg = str_replace("#companyname#", $companyname, $nf_msg);			  
				  $nf_mail_subject = str_replace("#companyname#", $companyname, $nf_mail_subject);
				  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $nf_mail_subject, $nf_msg, $cm->site_url, $news_footer_u);
			  }
		  }	
		  
		  $_SESSION["thnk"] = $app_msg;
		  if ($s == 2){
			  header('Location: ' . $cm->get_page_url('', 'popthankyou'));
		  }else{
		  	header('Location: '. $cm->site_url .'/thankyou/');
		  }
		  exit;	  	  
	  }
  }
  
	//credit application => get html format
	public function create_credit_application_content($id){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_credit_application where id = '". $cm->filtertext($id) ."'";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			$row = $result[0];
			foreach($row AS $key => $val){
				if ($key == "social_security"){
					${$key} = $edclass->text_decode($val);
				}else{
					${$key} = $val;
				}
			}
						
			//get values	
			$sqlval = "select keyname, keyvalue from tbl_credit_application_value where application_id = '". $cm->filtertext($id) ."'";
			$resultval = $db->fetch_all_array($sqlval);
				
			foreach($resultval AS $resultrow){
				$key = $resultrow["keyname"];
				$val = $resultrow["keyvalue"];
				
				if ($key == "social_security" OR $key == "drivers_license" OR $key == "dob" OR $key == "co_app_social_security" OR $key == "co_app_drivers_license" OR $key == "co_app_dob"){
					//${$key} = $edclass->text_decode($val);
					${$key} = "********";
				}else{
					${$key} = $val;
				}
			}
			
			$application_type_name = $cm->get_common_field_name('tbl_credit_application_type', 'name', $application_type_id);
			$oth_income_description = nl2br($oth_income_description);
			$co_app_oth_income_description = nl2br($co_app_oth_income_description);
			
			//state name
			$drivers_license_state_name = $cm->get_common_field_name('tbl_state', 'code', $drivers_license_state);
			$co_app_drivers_license_state_name = $cm->get_common_field_name('tbl_state', 'code', $co_app_drivers_license_state);
			
			$country_name = $cm->get_common_field_name('tbl_country', 'name', $country);
			$prev_country_name = $cm->get_common_field_name('tbl_country', 'name', $prev_country);
			
			$emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $emp_country);
			$prev_emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $prev_emp_country);
			
			$co_app_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_country);
			$co_app_prev_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_prev_country);
			
			$co_app_emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_emp_country);
			$co_app_prev_emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $co_app_prev_emp_country);
			
			$us_citizen_text = $cm->set_yesyno_field($us_citizen);
			$prior_bankruptcy_text = $cm->set_yesyno_field($prior_bankruptcy);
			$tradein_text = $cm->set_yesyno_field($tradein);  
			$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type);
			$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type);
			
			$horsepower_combined = $engine_no * $horsepower_individual;
			$tradein_horsepower_combined = $tradein_engine_no * $tradein_horsepower_individual;
			
			$corp_llc_trust_text = '';
			if ($application_type_id == 3){
				$corp_llc_trust_text = '
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Corp/LLC/Trust Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($crop_llc_trust_name, 1) .'</td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">EIN:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($llc_ein, 1) .'</td>
				</tr>
				';
			}
			
			/*-----------asset----------------*/	
			$total_asset = 0;		
			$counter = 0;
			$fieldsets = $this->asset_form_common_fields();
			$fieldsets = (object)$fieldsets;
			
			$assets_text = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="3"><strong>Assets</strong></td>
				</tr>
				
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Asset Name</td>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Financial Institution</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$ amount</td>
				</tr>
			';
			
			//set 1
			$fieldset1 = $fieldsets->set1;
			foreach($fieldset1 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$financial_inst = ${"financial_inst" . $counter};
				$assetamt = round(${"assetamt" . $counter}, 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $cm->filtertextdisplay($financial_inst, 1) .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="3">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 2
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'"><strong>Real Estate</strong></td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'"><strong>Property Location</strong></td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right"><strong>Income</strong></td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right"><strong>Present Value</strong></td>
				</tr>
			';
			
			$fieldset2 = $fieldsets->set2;
			foreach($fieldset2 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$financial_inst = ${"financial_inst" . $counter};
				$assetincome = round(${"assetincome" . $counter}, 2);
				$assetamt = round(${"assetamt" . $counter}, 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $cm->filtertextdisplay($financial_inst, 1) .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetincome, 2) .'</td>
					<td style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 3
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset3 = $fieldsets->set3;
			foreach($fieldset3 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				//$financial_inst = ${"financial_inst" . $counter};
				//$assetincome = round(${"assetincome" . $counter]}, 2);
				$assetamt = round(${"assetamt" . $counter}, 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="2">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 4
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset4 = $fieldsets->set4;
			foreach($fieldset4 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				//$financial_inst = ${"financial_inst" . $counter};
				//$assetincome = round(${"assetincome" . $counter}, 2);
				$assetamt = round(${"assetamt" . $counter}, 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="2">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			//set 5
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">	
				<tr>
					<td width="100%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Other Non-Titled Assets (describe)</strong></td>
				</tr>			
			';
			
			$fieldset5 = $fieldsets->set5;
			foreach($fieldset5 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$financial_inst = ${"financial_inst" . $counter};
				$assetamt = round(${"assetamt" . $counter}, 2);
				//$assetamt = $assetamt * 1000;
				
				$assets_text .= '
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($assetamt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$assets_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="2">&nbsp;</td>
					</tr>
					';
				}
				
				$total_asset = $total_asset + $assetamt;
				$counter++;
			}
			
			$assets_text .= '
			</table>
			';
			//end
			
			$assets_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">	
				<tr>
					<td width="80%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Total Assets</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$'. $cm->format_price($total_asset, 2) .'</td>
				</tr>	
			</table>			
			';
			
			/*-----------asset end----------------*/
			
			/*-----------liabilities----------------*/
			$total_liabilities = 0;
			$counter = 0;
			$fieldsets = $this->liabilities_form_common_fields();
			$fieldsets = (object)$fieldsets;			
			
			//set 1
			$liabilities_text = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="3"><strong>Liabilities</strong></td>
				</tr>
				
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Name Of Liability</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Financial Institution</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$ amount</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">PMT</td>
				</tr>
			';
			
			$fieldset1 = $fieldsets->set1;
			foreach($fieldset1 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = ${"l_financial_inst" . $counter};
				$liabilitiesamt = round(${"liabilitiesamt" . $counter}, 2);
				$pmt = round(${"pmt" . $counter}, 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 2
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset2 = $fieldsets->set2;
			foreach($fieldset2 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = ${"l_financial_inst" . $counter};
				$liabilitiesamt = round(${"liabilitiesamt" . $counter}, 2);
				$pmt = round(${"pmt" . $counter}, 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 3
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset3 = $fieldsets->set3;
			foreach($fieldset3 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = ${"l_financial_inst" . $counter};
				$liabilitiesamt = round(${"liabilitiesamt" . $counter}, 2);
				$pmt = round(${"pmt" . $counter}, 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 4
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset4 = $fieldsets->set4;
			foreach($fieldset4 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = ${"l_financial_inst" . $counter};
				$liabilitiesamt = round(${"liabilitiesamt" . $counter}, 2);
				$pmt = round(${"pmt" . $counter}, 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			//set 5
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
			';
			
			$fieldset5 = $fieldsets->set5;
			foreach($fieldset5 as $innerar){
				$innerar = (object)$innerar;
				$fieldname = $innerar->name;
				$fieldsep = $innerar->sep;
				
				$l_financial_inst = ${"l_financial_inst" . $counter};
				$liabilitiesamt = round(${"liabilitiesamt" . $counter}, 2);
				$pmt = round(${"pmt" . $counter}, 2);
				//$liabilitiesamt = $liabilitiesamt * 1000;
				
				$liabilities_text .= '
				<tr>
					<td width="40%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $fieldname .'</td>
					<td width="30%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">'. $l_financial_inst .'</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($liabilitiesamt, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" align="right">$'. $cm->format_price($pmt, 2) .'</td>
				</tr>
				';
				
				if ($fieldsep == 1){
					$liabilities_text .= '
					<tr>
						<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="4">&nbsp;</td>
					</tr>
					';
				}
				
				$total_liabilities = $total_liabilities + $liabilitiesamt;
				$counter++;
			}
			
			$liabilities_text .= '
			</table>
			';			
			//end
			
			$liabilities_text .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">	
				<tr>
					<td width="70%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'">Total Liabilities</td>
					<td width="20%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">$'. $cm->format_price($total_liabilities, 2) .'</td>
					<td width="10%" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" align="right">&nbsp;</td>
				</tr>
				
				<tr>
					<td valign="top" style="padding: 0px; border-top: 1px solid #000;" colspan="3">&nbsp;</td>
				</tr>	
			</table>			
			';
			
			$net_worth = $total_asset - $total_liabilities;
			
			$asset_liabilities_final_text = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="80%">Total Assets:</td>
				   <td align="right" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="20%">$'. $cm->format_price($total_asset, 2) .'</td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="80%">Total Liabilities:</td>
				   <td align="right" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="20%">$'. $cm->format_price($total_liabilities, 2) .'</td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="80%">Net Worth:</td>
				   <td align="right" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultsubheading .'" width="20%">$'. $cm->format_price($net_worth, 2) .'</td>
				</tr>
			</table>
			';	
			/*-----------liabilities end----------------*/
			
			//Applicant		  
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">			  
			  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Applicant</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Application Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $application_type_name .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Middle Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($middle_name, 1) .'</td>
			  </tr>
			  
			  '. $corp_llc_trust_text .'
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Dob:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($dob, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Social Security:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($social_security, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License #:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drivers_license, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drivers_license_state_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Mobile Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($mobile, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Work Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Home Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($home_phone, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">U.S. Citizen:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $us_citizen_text .'</td>
			  </tr>
			';
			
			if ($us_citizen == 0){
			  $citizen_country_name = $cm->get_common_field_name('tbl_country', 'name', $citizen_country);
			  $returntext .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country of Citizenship:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $citizen_country_name .'</td>
			  </tr>
			  ';
			}
			
			$returntext .= '
			</table>
			';
			
			//Current Address
			$years_address = '';
			if ($address_year >= 0){
			  $years_address .= $address_year . ' Years - ';
			}
			if ($address_month > 0){
			  $years_address .= $address_month . ' Months';
			}
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Address:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($address, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($city, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($state, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($zip, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($country_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($years_address, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($own_rent, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($monthly_payment, 2) .'</td>
			  </tr>		  
			</table>	  
			';
			
			//Previous Address
			if ($address_year < 3){
			  $prev_years_address = '';
			  if ($prev_address_year >= 0){
				  $prev_years_address .= $prev_address_year . ' Years - ';
			  }
			  if ($prev_address_month > 0){
				  $prev_years_address .= $prev_address_month . ' Months';
			  }
			  $returntext .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Address:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_years_address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_own_rent, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($prev_monthly_payment, 2) .'</td>
				  </tr>		  
			  </table>	  
			  ';
			}
			
			//Current Employer
			$emp_length = '';
			if ($emp_year >= 0){
			  $emp_length .= $emp_year . ' Years - ';
			}
			if ($emp_month > 0){
			  $emp_length .= $emp_month . ' Months';
			}
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Employer:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($employer, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_address, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_city, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_state, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_zip, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_country_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_phone, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_length, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Position / Title:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_position, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Supervisor:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_supervisor, 1) .'</td>
			  </tr>		  
			</table>	  
			';
			
			//Previous Employer
			if ($emp_year < 3){
			  $prev_emp_length = '';
			  if ($prev_emp_year >= 0){
				  $prev_emp_length .= $prev_emp_year . ' Years - ';
			  }
			  if ($prev_emp_month > 0){
				  $prev_emp_length .= $prev_emp_month . ' Months';
			  }
			  $returntext .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Employer:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_employer, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_length, 1) .'</td>
				  </tr>	  
			  </table>	  
			  ';
			}
			
			//Income
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Income:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Wages:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($wages, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($paid, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($oth_income, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($oth_income_paid, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income Description:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($oth_income_description) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Prior Bankruptcy:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $prior_bankruptcy_text .'</td>
			  </tr>	  
			</table>	  
			';
			
			//Other Information
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Other Information:</strong></td>
			  </tr>
			';
			
			if ($prior_bankruptcy == 1){			  
			  $returntext .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Bankruptcy Year:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $bankruptcy_year .'</td>
			  </tr>
			  ';
			}	  
			  
			$returntext .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Nearest Relative:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($nearest_relative, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Relationship:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($nearest_relative_relationship, 1) .'</td>
			  </tr>	
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($nearest_relative_phone, 1) .'</td>
			  </tr>  
			</table>	  
			';
			
			//Co-Applicant
			if ($application_type_id == 2){
			  $returntext .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Co-Applicant</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_fname, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_lname, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Middle Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_middle_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Dob:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_dob, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Social Security:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_social_security, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License #:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_drivers_license, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_drivers_license_state_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_email, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Mobile Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_mobile, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Work Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Home Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_home_phone, 1) .'</td>
				  </tr>			  
			  </table>	  
			  ';
			  
			  //Current Address
			  $co_app_years_address = '';
			  if ($co_app_address_year >= 0){
				  $co_app_years_address .= $co_app_address_year . ' Years - ';
			  }
			  if ($co_app_address_month > 0){
				  $co_app_years_address .= $co_app_address_month . ' Months';
			  }
			  $returntext .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Address:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_years_address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_own_rent, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_monthly_payment, 2) .'</td>
				  </tr>		  
			  </table>	  
			  ';
			  
			  //Previous Address
			  if ($co_app_address_year < 3){
				  $co_app_prev_years_address = '';
				  if ($co_app_prev_address_year >= 0){
					  $co_app_prev_years_address .= $co_app_prev_address_year . ' Years - ';
				  }
				  if ($co_app_prev_address_month > 0){
					  $co_app_prev_years_address .= $co_app_prev_address_month . ' Months';
				  }
				  $returntext .= '
				  <table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr>
							<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Address:</strong></td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_address, 1) .'</td>
					  </tr>	
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_city, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_state, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_zip, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_country_name, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_years_address, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_own_rent, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Monthly Payment:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_prev_monthly_payment, 2) .'</td>
					  </tr>		  
				  </table>	  
				  ';
			  }
			  
			  //Current Employer
			  $co_app_emp_length = '';
			  if ($co_app_emp_year >= 0){
				  $co_app_emp_length .= $co_app_emp_year . ' Years - ';
			  }
			  if ($co_app_emp_month > 0){
				  $co_app_emp_length .= $co_app_emp_month . ' Months';
			  }
			  $returntext .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Employer:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_employer, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_address, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_country_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_length, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Position / Title:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_position, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Supervisor:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_emp_supervisor, 1) .'</td>
				  </tr>		  
			  </table>	  
			  ';
			  
			  //Previous Employer
			  if ($co_app_emp_year < 3){
				  $co_app_prev_emp_length = '';
				  if ($co_app_prev_emp_year >= 0){
					  $co_app_prev_emp_length .= $co_app_prev_emp_year . ' Years - ';
				  }
				  if ($co_app_prev_emp_month > 0){
					  $co_app_prev_emp_length .= $co_app_prev_emp_month . ' Months';
				  }
				  $returntext .= '
				  <table border="0" width="100%" cellspacing="0" cellpadding="0">
					  <tr>
							<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Employer:</strong></td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_employer, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_address, 1) .'</td>
					  </tr>	
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_city, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_state, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_zip, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_country_name, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_phone, 1) .'</td>
					  </tr>
					  
					  <tr>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
						   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_prev_emp_length, 1) .'</td>
					  </tr>	  
				  </table>	  
				  ';
			  }
			  
			  //Income
			  $returntext .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Income:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Wages:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_wages, 2) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_paid, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($co_app_oth_income, 2) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Paid:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_oth_income_paid, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Other Income Description:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_oth_income_description) .'</td>
				  </tr>	  
			  </table>	  
			  ';
			  
			  //Other Information
			  $returntext .= '
			  <table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
						<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Other Information:</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Nearest Relative:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_nearest_relative, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Relationship:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_nearest_relative_relationship, 1) .'</td>
				  </tr>	
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($co_app_nearest_relative_phone, 1) .'</td>
				  </tr>  
			  </table>	  
			  ';
		  }
		  
			//Boat Information
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Boat Information</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Manufacturer:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_manufacturer, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_length, 1) .' ft</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Price:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($boat_price, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Sales Agent Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($sales_agent_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Make:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_make, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_type_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drive Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drive_type_name, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Fuel Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fuel_type, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Number of Engines:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_no, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Horsepower Individual:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($horsepower_individual, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Horsepower Combined:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($horsepower_combined, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Is there a Trade-In?</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_text .'</td>
			  </tr>
			';
			
			if ($tradein == 1){			  
			  $returntext .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Year:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_year .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Make:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_make .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Model:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_model .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Length:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_length .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Engine Make:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_engine_make .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In HP:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_hp .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In # Engines:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_engine_no .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Fuel Type:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $tradein_fuel_type .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Horsepower Individual:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($tradein_horsepower_individual, 1) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade-In Horsepower Combined:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($tradein_horsepower_combined, 1) .'</td>
			  </tr>
			  ';
			}
			
			$returntext .= '
			</table>
			';
			
			//Loan Information
			$estimated_tax_pay = ($purchase_price * $estimated_tax_rate)/100;
			$estimated_tax_pay = round($estimated_tax_pay, 2);
			
			$net_purchase_amount = $purchase_price + $estimated_tax_pay - $cash_down;
			$net_purchase_amount = round($net_purchase_amount, 2);
			
			$desired_loan_amount = $net_purchase_amount - $trade_amount + $trade_payoff;
			$desired_loan_amount = round($desired_loan_amount, 2);
			
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Loan Information:</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Purchase Price:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($purchase_price, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Estimated Tax Rate %:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->format_price($estimated_tax_rate, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Estimated Taxes Payable:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($estimated_tax_pay, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Cash Down:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($cash_down, 2) .'</td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Net Purchase Amount:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($net_purchase_amount, 2) .'</td>
			  </tr>
			  ';
			  
			  if ($tradein == 1){
				  $returntext .= '
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade Amount:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($trade_amount, 2) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Trade Payoff:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($trade_payoff, 2) .'</td>
				  </tr>
				  ';
			  }
			  
			  $returntext .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Desired Loan Amount:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">$'. $cm->format_price($desired_loan_amount, 2) .'</td>
			  </tr>
			</table>
			';
			
			//Personal Financial Statement
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Personal Financial Statement</strong></td>
			  </tr>
			</table>	  
			' . $assets_text . $liabilities_text . $asset_liabilities_final_text;
			
			//submit form
			if ($applicant_auth == 1){
			  $applicant_auth_d = 'Yes';
			}else{
			  $applicant_auth_d = 'No';
			}
			
			if ($co_applicant_auth == 1){
			  $co_applicant_auth_d = 'Yes';
			}else{
			  $co_applicant_auth_d = 'No';
			}
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Authorization</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Applicant:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $applicant_auth_d .'</td>
			  </tr>
			';			  
			
			if ($application_type_id == 2){	  
			$returntext .= '
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Co-Applicant:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $co_applicant_auth_d .'</td>
			  </tr>
			';
			}
			
			$returntext .= '
			</table>	  
			';
	
		}else{
			$returntext = '<h2>Invalid selection</h2>';
		}
		
		return $returntext;
	}
	
	public function create_credit_application_pdf($checkval, $folderpath, $checkoption = 0){
		global $db, $cm;		
		$pdftext = $this->create_credit_application_content($checkval);
		
		$filename = "credit-application-" . $checkval . ".pdf";
		$cm->generate_pdf($folderpath, $pdftext, '', $filename);
	}
  
  	/*--------------------/BOAT CREDIT--------------------*/
}
?>