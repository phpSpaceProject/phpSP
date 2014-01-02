<?php
/**
 * This file is part of Nacatiks
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Copyright (c) 2012-Present, Nacatiks Support Team <http://www.nacatikx.dafun.com/index.php?page=Accueil>
 * All rights reserved.
 *=========================================================
  _   _          _____       _______ _____ _  __ _____ 
 | \ | |   /\   / ____|   /\|__   __|_   _| |/ // ____|
 |  \| |  /  \ | |       /  \  | |    | | | ' /| (___  
 | . ` | / /\ \| |      / /\ \ | |    | | |  <  \___ \ 
 | |\  |/ ____ \ |____ / ____ \| |   _| |_| . \ ____) |
 |_| \_/_/    \_\_____/_/    \_\_|  |_____|_|\_\_____/      


 *=========================================================
 *
 */

includeLang('fleet');

$fleet_group_mr = 0;
if ($_POST['fleet_group'] > 0)
{
	if ($_POST['mission'] == 2)
	{
		$fleet_group_mr = $_POST['fleet_group'];
	}
}

if(($_POST['fleet_group'] == 0) && ($_POST['mission'] == 2))
{
	$_POST['mission'] = 1;
}

$CurrentPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '" . $user['current_planet'] . "'", 'planets', true);
$TargetPlanet = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '" . $_POST['galaxy'] . "' AND `system` = '" . $_POST['system'] . "' AND `planet` = '" . $_POST['planet'] . "' AND `planet_type` = '" . $_POST['planettype'] . "';", 'planets', true);
$MyDBRec = doquery("SELECT * FROM {{table}} WHERE `id` = '" . $user['id'] . "';", 'users', true);

$lemetal = preg_match("/[^0-9]/", $_POST['resource1']);
$lecristal = preg_match("/[^0-9]/", $_POST['resource2']);
$ledeut = preg_match("/[^0-9]/", $_POST['resource3']);
// si il y a un petit malin qui rentre des caracteres autre que numerique				
if ($lemetal)
    message("" . $lang['error'] . "", "" . $lang['error_g'] . "");
if ($lecristal)
    message("" . $lang['error'] . "", "" . $lang['error_g'] . "");
if ($ledeut)
    message("" . $lang['error'] . "", "" . $lang['error_g'] . "");

$protection = $game_config['noobprotection'];
$protectiontime = $game_config['noobprotectiontime'];
$protectionmulti = $game_config['noobprotectionmulti'];
if ($protectiontime < 1) {
    $protectiontime = 9999999999999999;
}
$fleetarray = unserialize(base64_decode(str_rot13($_POST["usedfleet"])));

// if ($_POST['mission'] == 1) 
// {
    // message("<font color=\"red\"><b>Les attaques sont stoppés pendant 48H<br>Désoler pour la gene.</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
// }

// On verifie s'il y a assez de vaisseaux sur la planete !
foreach ($fleetarray as $Ship => $Count) {
    if ($Count > $CurrentPlanet[$resource[$Ship]]) {
        message("<font color=\"red\"><b>" . $lang['fl_fleet_err'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
}

$error			= 0;
$galaxy			= intval($_POST['galaxy']);
$system			= intval($_POST['system']);
$planet			= intval($_POST['planet']);
$planettype		= intval($_POST['planettype']);
$fleetmission 	= intval($_POST['mission']);
$fleet_acs		= intval($_POST['fleet_group']);


/************************************************/
/*		LIMITATION D'ATTAQUE BY MANDA			*/
/*==============================================*/

$select = doquery("SELECT * FROM {{table}} WHERE `attaquant`='" . $CurrentPlanet['id_owner'] . "' AND `defenseur`='" . $TargetPlanet['id_owner'] . "' LIMIT 1", 'attack', true);
$ActiviteDefenseur = doquery("SELECT `onlinetime` FROM {{table}} WHERE `id`='" . $FleetRow['fleet_target_owner'] . "' LIMIT 1", "users", true);
// on vérifie le temp d'abor !
$numberattaque  = doquery("SELECT COUNT(fleet_target_owner) AS `mnumb` FROM {{table}} WHERE `fleet_owner` = '" . $user['id'] . "' and `fleet_target_owner` = '" . $TargetPlanet['id_owner'] . "';", 'fleets', true);

$nombredattaque = $numberattaque['mnumb'];
$time = time();
// 24 heure !
$temp = $select['temp'] + TIME_LONG_ATTACK_BLOCKED;
if (($select['compteur']+$nombredattaque) >= MAX_ATTACK and $temp > time()) {
    message("Vous avez atteint la limite du nombre maximum d'attaque envers 1 m&ecirc;me joueur ! C'est-&agrave;-dire " . MAX_ATTACK . " attaques/joueur.", "fleet." . PHPEXT, 2);
}

if ($select['compteur'] >= MAX_ATTACK and $temp > time()) {
    message("Vous avez atteint la limite du nombre maximum d'attaque envers 1 m&ecirc;me joueur ! C'est-&agrave;-dire " . MAX_ATTACK . " attaques/joueur.", "fleet." . PHPEXT, 2);
}

if ($select['compteur'] >= MAX_ATTACK and $temp > time()) {
    message("Vous avez atteint la limite du nombre maximum d'attaque envers 1 m&ecirc;me joueur ! C'est-&agrave;-dire " . MAX_ATTACK . " attaques/joueur.", "fleet." . PHPEXT, 2);
}

if ($planettype != 1 && $planettype != 2 && $planettype != 3) {
    message("<font color=\"red\"><b>" . $lang['fl_fleet_err_pl'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}
/************************************************/
/*		FIN  LIMITATION D'ATTAQUE BY MANDA		*/
/*==============================================*/

if ($fleetmission == 8) {
    $YourPlanet = false;
    $UsedPlanet = false;
    $select = doquery("SELECT * FROM {{table}} WHERE galaxy = '" . $galaxy . "' AND system = '" . $system . "' AND planet = '" . $planet . "'", "planets");
} else {
    $YourPlanet = false;
    $UsedPlanet = false;
    $select = doquery("SELECT * FROM {{table}} WHERE galaxy = '" . $galaxy . "' AND system = '" . $system . "' AND planet = '" . $planet . "' AND planet_type = '" . $planettype . "'", "planets");
}

if ($CurrentPlanet['galaxy'] == $galaxy &&
        $CurrentPlanet['system'] == $system &&
        $CurrentPlanet['planet'] == $planet &&
        $CurrentPlanet['planet_type'] == $planettype) {
    message("<font color=\"red\"><b>" . $lang['fl_ownpl_err'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

/************************************************/
/*			JOUEUR DE LA MEME ALLIANCE			*/
/*==============================================*/

$allymembreowner = doquery("SELECT * FROM {{table}} WHERE `id`='" . $CurrentPlanet['id_owner'] . "';", 'users', true);
$allytargetowner = doquery("SELECT * FROM {{table}} WHERE `id`='" . $TargetPlanet['id_owner'] . "';", 'users', true);
if ($allytargetowner['ally_id'] != 0 and $allymembreowner['ally_id'] == $allytargetowner['ally_id'] and $fleetmission == 1) {
    message("<font color=\"red\"><b>vous ne pouvez pas attaquer un membre de votre alliance.", $lang['fl_error'], "fleet." . PHPEXT, 2);
} elseif ($allytargetowner['ally_id'] != 0 and $allymembreowner['ally_id'] == $allytargetowner['ally_id'] and $fleetmission == 6) {
    message("<font color=\"red\"><b>vous ne pouvez pas Espionner un membre de votre alliance.", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

/************************************************/
/*				JOUEURS & AMI(E)S				*/
/*==============================================*/
// si c'est un ami  ^^'
$buddyowner = doquery("SELECT * FROM {{table}} where `owner`='" . $CurrentPlanet['id_owner'] . "';", 'buddy', true);
$buddysender = doquery("SELECT * FROM {{table}} where `sender`='" . $CurrentPlanet['id_owner'] . "';", 'buddy', true);
if($fleetmission == 1 or $fleetmission == 6)
{
	if ($buddyowner['sender'] !=0 and $buddyowner['sender'] == $TargetPlanet['id_owner'] and $buddyowner['active']==1) 
	{
		message("<font color=\"red\"><b>vous ne pouvez pas attaquer/espionner un(e) Ami(e).", $lang['fl_error'], "fleet." . PHPEXT, 2);
	}
	elseif($buddysender['owner'] !=0 and $buddysender['owner'] == $TargetPlanet['id_owner'] and $buddysender['active']==1)
	{
		message("<font color=\"red\"><b>vous ne pouvez pas attaquer/espionner un(e) Ami(e).", $lang['fl_error'], "fleet." . PHPEXT, 2);
	}
}

// Test d'existance de l'enregistrement dans la gaalxie !
if (mysql_num_rows($select) < 1 && $fleetmission != 7) {
	message("<font color=\"red\"><b>" . $lang['fl_unknow_target'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
} elseif ($fleetmission == 9 && mysql_num_rows($select) < 1) {
	message("<font color=\"red\"><b>" . $lang['fl_used_target'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

$select = mysql_fetch_array($select);

if ($select['id_owner'] == $user['id']) {
    $YourPlanet = true;
    $UsedPlanet = true;
} elseif (!empty($select['id_owner'])) {
    $YourPlanet = false;
    $UsedPlanet = true;
} else {
    $YourPlanet = false;
    $UsedPlanet = false;
}

// Determinons les type de missions possibles par rapport a la planete cible
 		if ($_POST['planettype'] == "2") {
			if ($_POST['ship209'] >= 1) {
				$missiontype = array(8 => $lang['type_mission'][8]);#mission recycler
			} else {
				$missiontype = array();
			}
		} elseif ($_POST['planettype'] == "1") {
			if ($_POST['ship208'] >= 1 && !$UsedPlanet) {
				$missiontype = array(7 => $lang['type_mission'][7]);#mission coloniser
			} elseif ($_POST['ship210'] >= 1 && !$YourPlanet) {
				$missiontype = array(6 => $lang['type_mission'][6]);#mission espionner
			}

			if ($_POST['ship202'] >= 1 ||
				$_POST['ship203'] >= 1 ||
				$_POST['ship204'] >= 1 ||
				$_POST['ship205'] >= 1 ||
				$_POST['ship206'] >= 1 ||
				$_POST['ship207'] >= 1 ||
				$_POST['ship210'] >= 1 ||
				$_POST['ship211'] >= 1 ||
				$_POST['ship213'] >= 1 ||
				$_POST['ship214'] >= 1 ||
				$_POST['ship215'] >= 1) {
				if (!$YourPlanet) {
					$missiontype[1] = $lang['type_mission'][1];#mission attaquer
					$missiontype[5] = $lang['type_mission'][5];#stationner chez un allié
				}
				if($YourPlanet && $CurrentPlanet['planet']!=$planet)
				{
					$missiontype[3] = $lang['type_mission'][3];#mission transporter
				}
			}


		} elseif ($_POST['ship209'] >= 1 || $_POST['ship208']) {
			$missiontype[3] = $lang['type_mission'][3];#mission transporter
		}
		if ($YourPlanet)
			$missiontype[4] = $lang['type_mission'][4];#mission stationner
		
	//AG
	if ($_POST['planettype'] == 1 && ($fleet_acs > 0) && $UsedPlanet){
			$acs = doquery ( "SELECT * FROM `{{table}}` WHERE `id`= ".$fleet_acs."" , "aks" , TRUE );
			if ( 	$acs['galaxy'] == $galaxy &&
					$acs['planet'] == $planet &&
					$acs['system'] == $system &&
					$acs['planet_type'] == $planettype ){
			$missiontype[2] 	= $lang['type_mission'][2];#mission attaque groupé
			}
		}

if (empty($missiontype[$fleetmission])) {
    message("<font color=\"red\"><b>" . $lang['fl_bad_mission'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

CheckPlanetUsedFields($CurrentPlanet);

/************************************************/
/*	 GESTION DE LA PROTECTION DEBUTANT/FORT		*/
/*==============================================*/

if ($TargetPlanet['id_owner'] == '') {
    $MyDBRec = doquery("SELECT * FROM {{table}} WHERE `id` = '" . $CurrentPlanet['id_owner'] . "';", 'users', true);
    ;
} elseif ($TargetPlanet['id_owner'] != '') {
    $HeDBRec = doquery("SELECT * FROM {{table}} WHERE `id` = '" . $TargetPlanet['id_owner'] . "';", 'users', true);
    $inactif = $HeDBRec['onlinetime'] + (60 * 60 * 24 * 5);
}

$UserPoints = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . $MyDBRec['id'] . "';", 'statpoints', true);
$User2Points = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . $HeDBRec['id'] . "';", 'statpoints', true);

$MyGameLevel = $UserPoints['total_points'];
$HeGameLevel = $User2Points['total_points'];
$VacationMode = $HeDBRec['urlaubs_modus'];

if(($_POST['mission'] == 1) or ($_POST['mission'] == 6))
{
	// si le joueur est plus petit que l'inactivité de 7 jours :)
	if($HeDBRec['onlinetime'] >= (time() - (60 * 60 * 24 * 7)))
	{
		// si le joueur  est trop faible
		if (($MyGameLevel > ($HeGameLevel * $protectionmulti))) {
			if (    ($TargetPlanet['id_owner'] != '') or ($TargetPlanet['id_owner'] != $CurrentPlanet['id_owner']) and
					($protection == 1) AND
					($HeGameLevel < ($protectiontime * 1000))) {
						message("<font color=\"lime\"><b>" . $lang['fl_noob_mess_n'] . "</b></font>", $lang['fl_noob_title'], "fleet." . PHPEXT, 2);
			}
		}

		// si le joueurs est trop fort lool
		if (($HeGameLevel > ($MyGameLevel * $protectionmulti))) {
			if (    ($TargetPlanet['id_owner'] != '') or ($TargetPlanet['id_owner'] != $CurrentPlanet['id_owner']) and
					($protection == 1) AND
					($MyGameLevel < ($protectiontime * 1000))) {
						message("<font color=\"red\"><b>" . $lang['fl_strong_mess_n'] . "</b></font>", $lang['fl_strong_title'], "fleet." . PHPEXT, 2);
			}
		}
	}
}
/************************************************/
/*	FIN GESTION DE LA PROTECTION DEBUTANT/FORT	*/
/*==============================================*/

	if ($fleet_group_mr != 0)
	{
			$AksStartTime = doquery("SELECT MAX(`fleet_start_time`) AS Start FROM {{table}} WHERE `fleet_group` = '". $fleet_group_mr . "';", "fleets", TRUE);
			$AksEndTime = doquery("SELECT MAX(`fleet_end_time`) AS End FROM {{table}} WHERE `fleet_group` = '". $fleet_group_mr . "';", "fleets", TRUE);

			/*************** AG****************
			 * on regarde les flottes du meme groupe envoyé
			 * si la flotte avec un temps le plus grand donc la flotte la plus rescente
			 * on met les autres flottes a la meme vitesse ;)
			 */
			
			if ($AksStartTime['Start'] >= $fleet['start_time'])
			{
				$QryUpdateFleets = "UPDATE {{table}} SET ";
				$QryUpdateFleets .= "`fleet_start_time` = '". $AksStartTime['Start'] ."' + 1, ";
				$QryUpdateFleets .= "`fleet_end_time` = '". $AksEndTime['End'] ."' + 1 ";
				$QryUpdateFleets .= "WHERE ";
				$QryUpdateFleets .= "`fleet_group` = '". $fleet_group_mr ."';";
				doquery($QryUpdateFleets, 'fleets');
				
				// $fleet['fleet_end_time']        += $AksStartTime['Start'] -  $fleet['start_time'];
				// $fleet['start_time']     = $AksStartTime['Start'];
			}
			else
			{
				$QryUpdateFleets = "UPDATE {{table}} SET ";
				$QryUpdateFleets .= "`fleet_start_time` = '". $fleet['start_time'] ."', ";
				$QryUpdateFleets .= "`fleet_end_time` = fleet_end_time + '".($fleet['start_time'] - $AksStartTime['Start'])."' ";
				$QryUpdateFleets .= "WHERE ";
				$QryUpdateFleets .= "`fleet_group` = '". $fleet_group_mr ."';";
				doquery($QryUpdateFleets, 'fleets');
				$fleet['end_time']         += $fleet['start_time'] -  $AksStartTime['Start'];
			}
	}

if ($VacationMode AND $_POST['mission'] != 8) {
    message("<font color=\"lime\"><b>" . $lang['fl_vacation_pla'] . "</b></font>", $lang['fl_vacation_ttl'], "fleet." . PHPEXT, 2);
}

$FlyingFleets = mysql_fetch_assoc(doquery("SELECT COUNT(fleet_id) as Number FROM {{table}} WHERE `fleet_owner`='{$user['id']}'", 'fleets'));
$ActualFleets = $FlyingFleets["Number"];
if (($user[$resource[102]] + 1) <= $ActualFleets) {
    message("Pas de slot disponible", "Erreur", "fleet." . PHPEXT, 1);
}

if ($_POST['resource1'] + $_POST['resource2'] + $_POST['resource3'] < 1 AND $_POST['mission'] == 3) {
    message("<font color=\"lime\"><b>" . $lang['fl_noenoughtgoods'] . "</b></font>", $lang['type_mission'][3], "fleet." . PHPEXT, 1);
}

    if ($TargetPlanet['id_owner'] == '' AND $_POST['mission'] < 7) {
        message("<font color=\"red\"><b>" . $lang['fl_bad_planet01'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($TargetPlanet['id_owner'] != '' AND $_POST['mission'] == 7) {
        message("<font color=\"red\"><b>" . $lang['fl_bad_planet02'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($HeDBRec['ally_id'] != $MyDBRec['ally_id'] AND $_POST['mission'] == 4) {
        message("<font color=\"red\"><b>" . $lang['fl_dont_stay_here'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($TargetPlanet['ally_deposit'] < 1 AND $HeDBRec != $MyDBRec AND $_POST['mission'] == 5) {
        message("<font color=\"red\"><b>" . $lang['fl_no_allydeposit'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if (($TargetPlanet["id_owner"] == $CurrentPlanet["id_owner"]) AND ($_POST["mission"] == 1)) {
        message("<font color=\"red\"><b>" . $lang['fl_no_self_attack'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if (($TargetPlanet["id_owner"] == $CurrentPlanet["id_owner"]) AND ($_POST["mission"] == 6)) {
        message("<font color=\"red\"><b>" . $lang['fl_no_self_spy'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if (($TargetPlanet["id_owner"] != $CurrentPlanet["id_owner"]) AND ($_POST["mission"] == 4)) {
        message("<font color=\"red\"><b>" . $lang['fl_only_stay_at_home'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }


    $missiontype = array(
		1 => $lang['type_mission'][1],#mission attaqué
		2 => $lang['type_mission'][2],#mission attaqué groupé
		3 => $lang['type_mission'][3],#transporter
		4 => $lang['type_mission'][4],#stationner
		5 => $lang['type_mission'][5],#stationner chez un allié
		6 => $lang['type_mission'][6],#espionner
		7 => $lang['type_mission'][7],#coloniser
		8 => $lang['type_mission'][8]#recycler
    );

$speed_possible = array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1);

$AllFleetSpeed = GetFleetMaxSpeed($fleetarray, 0, $user);
$GenFleetSpeed = $_POST['speed'];
$SpeedFactor = $_POST['speedfactor'];
$MaxFleetSpeed = min($AllFleetSpeed);

if (!in_array($GenFleetSpeed, $speed_possible)) {
    message("<font color=\"red\"><b>" . $lang['fl_cheat_speed'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

$CurrentPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '" . $user['current_planet'] . "';", 'planets', true);

if (!$_POST['planettype']) {
    message("<font color=\"red\"><b>" . $lang['fl_no_planet_type'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}
// Test de coherance de la destination (voir si elle se trouve dans les limites de l'univers connu
$error = 0;
$errorlist = "";
if (!$_POST['galaxy'] || !is_numeric($_POST['galaxy']) || $_POST['galaxy'] > MAX_GALAXY_IN_WORLD || $_POST['galaxy'] < 1) {
    $error++;
    $errorlist .= $lang['fl_limit_galaxy'];
}
if (!$_POST['system'] || !is_numeric($_POST['system']) || $_POST['system'] > MAX_SYSTEM_IN_GALAXY || $_POST['system'] < 1) {
    $error++;
    $errorlist .= $lang['fl_limit_system'];
}
if (!$_POST['planet'] || !is_numeric($_POST['planet']) || $_POST['planet'] > MAX_PLANET_IN_SYSTEM + 1 || $_POST['planet'] < 1) {
    $error++;
    $errorlist .= $lang['fl_limit_planet'];
}
if ($error > 0) {
    message("<font color=\"red\"><ul>" . $errorlist . "</ul></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

// La flotte part bien de la planete courrante ??
if ($_POST['thisgalaxy'] != $CurrentPlanet['galaxy'] |
        $_POST['thissystem'] != $CurrentPlanet['system'] |
        $_POST['thisplanet'] != $CurrentPlanet['planet'] |
        $_POST['thisplanettype'] != $CurrentPlanet['planet_type']) {
    message("<font color=\"red\"><b>" . $lang['fl_cheat_origine'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if (!isset($fleetarray)) {
    message("<font color=\"red\"><b>" . $lang['fl_no_fleetarray'] . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

$distance = GetTargetDistance($_POST['thisgalaxy'], $_POST['galaxy'], $_POST['thissystem'], $_POST['system'], $_POST['thisplanet'], $_POST['planet']);
if ($verification_techno['hyperspace_tech'] >= 5) {
    if ($_POST['propuls'] == 1) {
        $duration = GetMissionDuration($GenFleetSpeed, $MaxFleetSpeed, $distance, $SpeedFactor);
    } elseif ($_POST['propuls'] == 4) {
        $duration = GetMissionDurationhyper($GenFleetSpeed, $MaxFleetSpeed, $distance, $SpeedFactor);
    }
} else {
    $duration = GetMissionDuration($GenFleetSpeed, $MaxFleetSpeed, $distance, $SpeedFactor);
}
$consumption = GetFleetConsumption($fleetarray, $SpeedFactor, $duration, $distance, $MaxFleetSpeed, $user);

$fleet['start_time'] = $duration + time();
if ($_POST['mission'] == 5) {
    $StayDuration = $_POST['holdingtime'] * 3600;
    $StayTime = $fleet['start_time'] + $_POST['holdingtime'] * 3600;
} else {
    $StayDuration = 0;
    $StayTime = 0;
}
$fleet['end_time'] = $StayDuration + (2 * $duration) + time();
$FleetStorage = 0;
$FleetShipCount = 0;
$fleet_array = "";
$FleetSubQRY = "";

foreach ($fleetarray as $Ship => $Count) {
    $FleetStorage += $pricelist[$Ship]["capacity"] * $Count;
    $FleetShipCount += $Count;
    $fleet_array .= $Ship . "," . $Count . ";";
    $FleetSubQRY .= "`" . $resource[$Ship] . "` = `" . $resource[$Ship] . "` - " . $Count . " , ";
}

$FleetStorage -= $consumption;
$StorageNeeded = 0;
if ($_POST['resource1'] < 1) {
    $TransMetal = 0;
} else {
    $TransMetal = $_POST['resource1'];
    $StorageNeeded += $TransMetal;
}
if ($_POST['resource2'] < 1) {
    $TransCrystal = 0;
} else {
    $TransCrystal = $_POST['resource2'];
    $StorageNeeded += $TransCrystal;
}
if ($_POST['resource3'] < 1) {
    $TransDeuterium = 0;
} else {
    $TransDeuterium = $_POST['resource3'];
    $StorageNeeded += $TransDeuterium;
}

$StockMetal = $CurrentPlanet['metal'];
$StockCrystal = $CurrentPlanet['crystal'];
$StockDeuterium = $CurrentPlanet['deuterium'];
$StockDeuterium -= $consumption;

$StockOk = false;
if ($StockMetal >= $TransMetal) {
    if ($StockCrystal >= $TransCrystal) {
        if ($StockDeuterium >= $TransDeuterium) {
            $StockOk = true;
        }
    }
}
if (!$StockOk) {
    message("<font color=\"red\"><b>" . $lang['fl_noressources'] . pretty_number($consumption) . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if ($StorageNeeded > $FleetStorage) {
    message("<font color=\"red\"><b>" . $lang['fl_nostoragespa'] . pretty_number($StorageNeeded - $FleetStorage) . "</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if ($TargetPlanet['id_level'] > $user['authlevel']) {
    $Allowed = true;
        switch ($_POST['mission']) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
                break;
            default:
        }
    if ($Allowed == false) {
        message("<font color=\"red\"><b>" . $lang['fl_adm_attak'] . "</b></font>", $lang['fl_warning'], "fleet." . PHPEXT, 2);
    }
}

// ecriture de l'enregistrement de flotte (a partir de l�, y a quelque chose qui vole et c'est toujours sur la planete d'origine)
$QryInsertFleet = "INSERT INTO {{table}} SET ";
$QryInsertFleet .= "`fleet_owner` = '" . $user['id'] . "', ";
$QryInsertFleet .= "`fleet_mission` = '" . $_POST['mission'] . "', ";
$QryInsertFleet .= "`fleet_amount` = '" . $FleetShipCount . "', ";
$QryInsertFleet .= "`fleet_array` = '" . $fleet_array . "', ";
$QryInsertFleet .= "`fleet_start_time` = '" . $fleet['start_time'] . "', ";
$QryInsertFleet .= "`fleet_start_galaxy` = '" . intval($_POST['thisgalaxy']) . "', ";
$QryInsertFleet .= "`fleet_start_system` = '" . intval($_POST['thissystem']) . "', ";
$QryInsertFleet .= "`fleet_start_planet` = '" . intval($_POST['thisplanet']) . "', ";
$QryInsertFleet .= "`fleet_start_type` = '" . intval($_POST['thisplanettype']) . "', ";
$QryInsertFleet .= "`fleet_end_time` = '" . $fleet['end_time'] . "', ";
$QryInsertFleet .= "`fleet_end_stay` = '" . $StayTime . "', ";
$QryInsertFleet .= "`fleet_end_galaxy` = '" . intval($_POST['galaxy']) . "', ";
$QryInsertFleet .= "`fleet_end_system` = '" . intval($_POST['system']) . "', ";
$QryInsertFleet .= "`fleet_end_planet` = '" . intval($_POST['planet']) . "', ";
$QryInsertFleet .= "`fleet_end_type` = '" . intval($_POST['planettype']) . "', ";
$QryInsertFleet .= "`fleet_resource_metal` = '" . $TransMetal . "', ";
$QryInsertFleet .= "`fleet_resource_crystal` = '" . $TransCrystal . "', ";
$QryInsertFleet .= "`fleet_resource_deuterium` = '" . $TransDeuterium . "', ";
$QryInsertFleet .= "`fleet_target_owner` = '" . $TargetPlanet['id_owner'] . "', ";
$QryInsertFleet .= "`fleet_group` = '" . $fleet_group_mr . "', ";
$QryInsertFleet .= "`start_time` = '" . time() . "';";
doquery($QryInsertFleet, 'fleets');


$CurrentPlanet["metal"] = $CurrentPlanet["metal"] - $TransMetal;
$CurrentPlanet["crystal"] = $CurrentPlanet["crystal"] - $TransCrystal;
$CurrentPlanet["deuterium"] = $CurrentPlanet["deuterium"] - $TransDeuterium;
$CurrentPlanet["deuterium"] = $CurrentPlanet["deuterium"] - $consumption;

$QryUpdatePlanet = "UPDATE {{table}} SET ";
$QryUpdatePlanet .= $FleetSubQRY;
$QryUpdatePlanet .= "`metal` = '" . $CurrentPlanet["metal"] . "', ";
$QryUpdatePlanet .= "`crystal` = '" . $CurrentPlanet["crystal"] . "', ";
$QryUpdatePlanet .= "`deuterium` = '" . $CurrentPlanet["deuterium"] . "' ";
$QryUpdatePlanet .= "WHERE ";
$QryUpdatePlanet .= "`id` = '" . $CurrentPlanet['id'] . "'";

// Mise a jours de l'enregistrement de la planete de depart (a partir de là, y a quelque chose qui vole et ce n'est plus sur la planete de depart)
doquery("LOCK TABLE {{table}} WRITE", 'planets');
doquery($QryUpdatePlanet, "planets");
doquery("UNLOCK TABLES", '');
//	doquery("FLUSH TABLES", '');
// Un peu de blabla pour l'utilisateur, affichage d'un joli tableau de la flotte expedi�e
$page = "<br><div><center>";
$page .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">";
$page .= "<tr height=\"20\">";
$page .= "<td class=\"c\" colspan=\"2\"><span class=\"success\">" . $lang['fl_fleet_send'] . "</span></td>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_mission'] . "</th>";
$page .= "<th>" . $missiontype[$_POST['mission']] . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_dist'] . "</th>";
$page .= "<th>" . pretty_number($distance) . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_speed'] . "</th>";
$page .= "<th>" . pretty_number($_POST['speedallsmin']) . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_deute_need'] . "</th>";
$page .= "<th>" . pretty_number($consumption) . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_from'] . "</th>";
$page .= "<th>" . $_POST['thisgalaxy'] . ":" . $_POST['thissystem'] . ":" . $_POST['thisplanet'] . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_dest'] . "</th>";
$page .= "<th>" . $_POST['galaxy'] . ":" . $_POST['system'] . ":" . $_POST['planet'] . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_time_go'] . "</th>";
$page .= "<th>" . date("M D d H:i:s", $fleet['start_time']) . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>" . $lang['fl_time_back'] . "</th>";
$page .= "<th>" . date("M D d H:i:s", $fleet['end_time']) . "</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<td class=\"c\" colspan=\"2\">" . $lang['fl_title'] . "</td>";

foreach ($fleetarray as $Ship => $Count) {
    $page .= "</tr><tr height=\"20\">";
    $page .= "<th>" . $lang['tech'][$Ship] . "</th>";
    $page .= "<th>" . pretty_number($Count) . "</th>";
}
$page .= "</tr></table></div></center>";

// Provisoire
sleep(1);

$planetrow = doquery("SELECT * FROM {{table}} WHERE `id` = '" . $CurrentPlanet['id'] . "';", 'planets', true);

display($page, $title = 'Flotte', $topnav = true, $metatags = '', $AdminPage = false, $leftMenu = true);

// Updated by Chlorel. 16 Jan 2008 (String extraction, bug corrections, code uniformisation
// Updated by -= MoF =- for Deutsches Ugamela Forum
// 06.12.2007 - 08:39
// Open Source
// (c) by MoF
?>