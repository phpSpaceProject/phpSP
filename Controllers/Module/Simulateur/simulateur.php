<?php
/**
 * This file is part of phpSpaceProject
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://phpsp.fr/
 *
 * Copyright (c) 2012-Present, phpSpaceProject Support Team <http://phpsp.fr/board/>
 * All rights reserved.
 *===================================
  _____  _    _ _____   _____ _____  
 |  __ \| |  | |  __ \ / ____|  __ \ 
 | |__) | |__| | |__) | (___ | |__) |
 |  ___/|  __  |  ___/ \___ \|  ___/ 
 | |    | |  | | |     ____) | |     
 |_|    |_|  |_|_|    |_____/|_|     
 
 *		-- phpSpaceProject --		
 *===================================
 *
 */
 
$parse = $lang;
$parse['link'] = INDEX_BASE;
if ($_POST) 
{

	$CurrentId['id'] = $user['id'];
	$TargetId['id'] = intval(substr($_POST['target'], 40, 41));
	$CurrentSet[$i]['count'] = array();
	$TargetSet[$i]['count'] = array();
	$PresenceFlotteCurrent = false;
	$PresenceFlotteTarget = false;
	
	for ($i = 200; $i < 500; $i++) {
		$fleet_us_mr   = $_POST['fleet_us'];
		$fleet_them_mr = $_POST['fleet_them'];
		if($fleet_us_mr[$i] > 0){
			$CurrentSet[$i]['count'] = $fleet_us_mr[$i];
			$PresenceFlotteCurrent = true;
		}
		if($fleet_them_mr[$i] > 0){
			$TargetSet[$i]['count']  = $fleet_them_mr[$i];
			$PresenceFlotteTarget = true;
		}
	}
	
	// if($user['vote'] >= 2)
	// {
		if (isset($_POST['submit']) and $PresenceFlotteTarget AND $PresenceFlotteCurrent) {
			
			/*//update du vote
				$Qry2 = "
						UPDATE
								{{table}}
						SET 
								`vote` = `vote` - '2'
						WHERE 
								`id`      = '{$IdUser}';";

			doquery($Qry2, 'users');*/
			$CurrentTechno = array();
			$TargetTechno  = array();
			
			$PowerAtt = intval(100 / 100);
			$PowerDef = intval(100 / 100);
			
			$CurrentTechno['defence_tech'] 		= intval($_POST['defence_tech_us'] 		* $PowerAtt);
			$CurrentTechno['shield_tech']  		= intval($_POST['shield_tech_us']		* $PowerAtt);
			$CurrentTechno['military_tech']  	= intval($_POST['military_tech_us']		* $PowerAtt);
				
			$TargetTechno['defence_tech'] 		= intval($_POST['defence_tech_them']	* $PowerDef);
			$TargetTechno['shield_tech'] 		= intval($_POST['shield_tech_them']		* $PowerDef);
			$TargetTechno['military_tech'] 		= intval($_POST['military_tech_them']	* $PowerDef);

			$page = SimuleCombat($CurrentSet, $TargetSet, $CurrentTechno, $TargetTechno,$CurrentId,$TargetId);
			
			$menu = false;
		}else{
			message("Combat impossible! Il semblerait que le nombre de vaisseaux dans l'un des deux camps soit nul.","Erreur simulation");
		}
	// }else{
		// message("Combat impossible! Il semblerait que vous n'avaient pas assez votes.","Erreur simulation");
	// }	
	
}else{
	$parse['military'] 	= ($user['military_tech'] != '') ? $user['military_tech'] : 0; 
	$parse['defence'] 	= ($user['defence_tech']  != '') ? $user['defence_tech']  : 0;
	$parse['shield'] 	= ($user['shield_tech']   != '') ? $user['shield_tech']   : 0;
	
	for ($SetItem = 200; $SetItem < 500; $SetItem++) {
		if($lang["tech"][$SetItem] != "") {
			if(floor($SetItem/100)*100 == $SetItem) $parse['lst_vaisseaux'] .= "<tr><td class=\"c\" colspan=\"3\">".$lang["tech"][$SetItem]."</td></tr>";
			else {
				$parse['lst_vaisseaux'] .= "<tr><th>".$lang["tech"][$SetItem]."</th>";
				if($SetItem == 212)
					$parse['lst_vaisseaux'] .= "<th> - </th><th><input type='text' id='fleet_them[".$SetItem."]' name='fleet_them[".$SetItem."]' value='0'></th></tr>";
				elseif($SetItem < 400)
					$parse['lst_vaisseaux'] .= "<th><input type='text' id='fleet_us[".$SetItem."]' name='fleet_us[".$SetItem."]' value='0'></th><th><input type='text' id='fleet_them[".$SetItem."]' name='fleet_them[".$SetItem."]' value='0'></th></tr>";
				else
					$parse['lst_vaisseaux'] .= "<th>&nbsp;</th><th><input type='text' id='fleet_them[".$SetItem."]' name='fleet_them[".$SetItem."]' value='0'></th></tr>";
			}
		}
	}

	$list ="<select name='target'>";
	$alluser = doquery("SELECT * FROM {{table}} WHERE `authlevel`='0' AND `id`!='".$user['id']."';", 'users');
	while($ua = mysql_fetch_assoc($alluser))
	{
		$list .="<option value='". sha1(uniqid(null, true)) ."" .$ua['id'] ."'>".$ua['username']."</option>";
	}
	$list .="</select>";
	$parse['listuser'] = $list;
	$parse['ususer'] = $user['username'];
	$page = parsetemplate(gettemplate('simulateur_body'), $parse);
	
	$menu = true;
}
	display($page,$title,true);

?>