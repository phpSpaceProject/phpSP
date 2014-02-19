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

function SpyTarget ( $TargetPlanet, $Mode, $TitleString ) {
	global $lang, $resource,$reslist;

/*********************** BATIMENT ******************************/
$FirstElementBuild = each($reslist['build']);#$FirstElementBuild[1]
$EndElementBuild = end($reslist['build']);#EndElementBuild
/*********************** RECHERCHE ******************************/
$FirstElementTech = each($reslist['tech']);#$FirstElementTech[1]
$EndElementTech = end($reslist['tech']);#EndElementTech
/************************* FLOTTE *******************************/
$FirstElementFleet = each($reslist['fleet']);#$FirstElementFleet[1]
$EndElementFleet = end($reslist['fleet']);#EndElementFleet
/************************* DEFENSE *******************************/
$FirstElementDefense = each($reslist['defense']);#$FirstElementDefense[1]
$EndElementDefense = end($reslist['defense']);#EndElementDefense


	$LookAtLoop = true;
	if       ($Mode == 0) {
		$String  = "<table width=\"440\"><tr><td class=\"c\" colspan=\"5\">";
		$String .= $TitleString ." ". $TargetPlanet['name'];
		$String .= " <a href=\"". INDEX_BASE ."galaxie&mode=3&galaxy=". $TargetPlanet["galaxy"] ."&system=". $TargetPlanet["system"]. "\">";
		$String .= "[". $TargetPlanet["galaxy"] .":". $TargetPlanet["system"] .":". $TargetPlanet["planet"] ."]</a>";
		$String .= " le ". gmdate("d-m-Y H:i:s", time() + 2 * 60 * 60) ."</td>";
		$String .= "</tr><tr>";
		$String .= "<td width=220>". $lang['Metal']     ."</td><td width=220 align=right>". pretty_number($TargetPlanet['metal'])."</td><td>&nbsp;</td>";
		$String .= "<td width=220>". $lang['Crystal']   ."</td></td><td width=220 align=right>". pretty_number($TargetPlanet['crystal'])."</td>";
		$String .= "</tr><tr>";
		$String .= "<td width=220>". $lang['Deuterium'] ."</td><td width=220 align=right>". pretty_number($TargetPlanet['deuterium'])."</td><td>&nbsp;</td>";
		$String .= "<td width=220>". $lang['Energy']    ."</td><td width=220 align=right>". pretty_number($TargetPlanet['energy_max'])."</td>";
		$String .= "</tr>";
		$LookAtLoop = false;
	} elseif ($Mode == 1) {
		$ResFrom[0] = 200;
		$ResTo[0]   = 299;
		$Loops      = 1;
	} elseif ($Mode == 2) {
		$ResFrom[0] = 400;
		$ResTo[0]   = 499;
		$ResFrom[1] = 500;
		$ResTo[1]   = 599;
		$Loops      = 2;
	} elseif ($Mode == 3) {
		$ResFrom[0] = 1;
		$ResTo[0]   = 99;
		$Loops      = 1;
	} elseif ($Mode == 4) {
		$ResFrom[0] = 100;
		$ResTo[0]   = 199;
		$Loops      = 1;
	}

	if ($LookAtLoop == true) {
		$String  = "<table width=\"440\" cellspacing=\"1\"><tr><td class=\"c\" colspan=\"". ((2 * SPY_REPORT_ROW) + (SPY_REPORT_ROW - 1))."\">". $TitleString ."</td></tr>";
		$Count       = 0;
		$CurrentLook = 0;
		while ($CurrentLook < $Loops) {
			$row     = 0;
			for ($Item = $ResFrom[$CurrentLook]; $Item <= $ResTo[$CurrentLook]; $Item++) {
				if ( $TargetPlanet[$resource[$Item]] > 0) {
					if ($row == 0) {
						$String  .= "<tr>";
					}
					$String  .= "<td align=left>".$lang['tech'][$Item]."</td><td align=right>".$TargetPlanet[$resource[$Item]]."</td>";
					if ($row < SPY_REPORT_ROW - 1) {
						$String  .= "<td>&nbsp;</td>";
					}
					$Count   += $TargetPlanet[$resource[$Item]];
					$row++;
					if ($row == SPY_REPORT_ROW) {
						$String  .= "</tr>";
						$row      = 0;
					}
				}
			}

			while ($row != 0) {
				$String  .= "<td>&nbsp;</td><td>&nbsp;</td>";
				$row++;
				if ($row == SPY_REPORT_ROW) {
					$String  .= "</tr>";
					$row      = 0;
				}
			}
			$CurrentLook++;
		} // while
	}
	$String .= "</table>";

	$return['String'] = $String;
	$return['Count']  = $Count;
	return $return;
}

?>