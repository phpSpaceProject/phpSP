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

	includeLang('galaxy');

	$parse = $lang;
	$parse['link'] = INDEX_BASE;
	$CurrentPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '". $user['current_planet'] ."';", 'planets', true);
	$lunarow       = doquery("SELECT * FROM {{table}} WHERE `id` = '". $user['current_luna'] ."';", 'lunas', true);
	$galaxyrow     = doquery("SELECT * FROM {{table}} WHERE `id_planet` = '". $CurrentPlanet['id'] ."';", 'galaxy', true);

	$fleetmax      = $user['computer_tech'] + 1;
	$CurrentPlID   = $CurrentPlanet['id'];
	$CurrentMIP    = $CurrentPlanet['interplanetary_misil'];
	$CurrentRC     = $CurrentPlanet['recycler'];
	$CurrentSP     = $CurrentPlanet['spy_sonde'];
	$HavePhalanx   = $CurrentPlanet['phalanx'];
	$CurrentSystem = $CurrentPlanet['system'];
	$CurrentGalaxy = $CurrentPlanet['galaxy'];
	$CanDestroy    = $CurrentPlanet[$resource[213]] + $CurrentPlanet[$resource[214]];

	$maxfleet       = doquery("SELECT * FROM {{table}} WHERE `fleet_owner` = '". $user['id'] ."';", 'fleets');
	$maxfleet_count = mysql_num_rows($maxfleet);

	CheckPlanetUsedFields($CurrentPlanet);
	CheckPlanetUsedFields($lunarow);

	// Imperatif, dans quel mode suis-je (pour savoir dans quel etat j'ere)
	if (!isset($mode)) {
		if (isset($_GET['mode'])) {
			$mode          = intval($_GET['mode']);
		} else {
			// ca ca sent l'appel sans parametres a plein nez
			$mode          = 0;
		}
	}

	if ($mode == 0) {
		// On vient du menu
		// Y a pas de parametres de passé
		// On met ce qu'il faut pour commencer là ou l'on se trouve

		$galaxy        = $CurrentPlanet['galaxy'];
		$system        = $CurrentPlanet['system'];
		$planet        = $CurrentPlanet['planet'];
	} elseif ($mode == 1) {
		// On vient du selecteur de galaxie
		// Il nous poste :
		// $_POST['galaxy']      => Galaxie affichée dans la case a saisir
		// $_POST['galaxyLeft']  => <- A ete cliqué
		// $_POST['galaxyRight'] => -> A ete cliqué
		// $_POST['system']      => Systeme affiché dans la case a saisir
		// $_POST['systemLeft']  => <- A ete cliqué
		// $_POST['systemRight'] => -> A ete cliqué

		if ($_POST["galaxyLeft"]) {
			if ($_POST["galaxy"] < 1) {
				$_POST["galaxy"] = 1;
				$galaxy          = 1;
			} elseif ($_POST["galaxy"] == 1) {
				$_POST["galaxy"] = 1;
				$galaxy          = 1;
			} else {
				$galaxy = $_POST["galaxy"] - 1;
			}
		} elseif ($_POST["galaxyRight"]) {
			if ($_POST["galaxy"]      > MAX_GALAXY_IN_WORLD OR
				$_POST["galaxyRight"] > MAX_GALAXY_IN_WORLD) {
				$_POST["galaxy"]      = MAX_GALAXY_IN_WORLD;
				$_POST["galaxyRight"] = MAX_GALAXY_IN_WORLD;
				$galaxy               = MAX_GALAXY_IN_WORLD;
			} elseif ($_POST["galaxy"] == MAX_GALAXY_IN_WORLD) {
				$_POST["galaxy"]      = MAX_GALAXY_IN_WORLD;
				$galaxy               = MAX_GALAXY_IN_WORLD;
			} else {
				$galaxy = $_POST["galaxy"] + 1;
			}
		} else {
			$galaxy = $_POST["galaxy"];
		}

		if ($_POST["systemLeft"]) {
			if ($_POST["system"] < 1) {
				$_POST["system"] = 1;
				$system          = 1;
			} elseif ($_POST["system"] == 1) {
				$_POST["system"] = 1;
				$system          = 1;
			} else {
				$system = $_POST["system"] - 1;
			}
		} elseif ($_POST["systemRight"]) {
			if ($_POST["system"]      > MAX_SYSTEM_IN_GALAXY OR
				$_POST["systemRight"] > MAX_SYSTEM_IN_GALAXY) {
				$_POST["system"]      = MAX_SYSTEM_IN_GALAXY;
				$system               = MAX_SYSTEM_IN_GALAXY;
			} elseif ($_POST["system"] == MAX_SYSTEM_IN_GALAXY) {
				$_POST["system"]      = MAX_SYSTEM_IN_GALAXY;
				$system               = MAX_SYSTEM_IN_GALAXY;
			} else {
				$system = $_POST["system"] + 1;
			}
		} else {
			$system = $_POST["system"];
		}
	} elseif ($mode == 2) {
		// Mais c'est qu'il mordrait !
		// A t'on idée de vouloir lancer des MIP sur ce pauvre bonhomme !!

		$galaxy        = $_GET['galaxy'];
		$system        = $_GET['system'];
		$planet        = $_GET['planet'];
	} elseif ($mode == 3) {
		// Appel depuis un menu avec uniquement galaxy et system de passé !
		$galaxy        = $_GET['galaxy'];
		$system        = $_GET['system'];
	} else {
		// Si j'arrive ici ...
		// C'est qu'il y a vraiment eu un bug
		$galaxy        = 1;
		$system        = 1;
	}

	$planetcount = 0;
	$lunacount   = 0;

	$page  = InsertGalaxyScripts ( $CurrentPlanet );

	$page .= "<body onUnload=\"\"><br><br>";
	$page .= ShowGalaxySelector ( $galaxy, $system );

	if ($mode == 2) {
		$CurrentPlanetID = $_GET['current'];
		$page .= ShowGalaxyMISelector ( $galaxy, $system, $planet, $CurrentPlanetID, $CurrentMIP );
	}

	$page .= "<br><table width=800><tbody>";

	$page .= ShowGalaxyTitles ( $galaxy, $system );
    $page .= ShowGalaxyRows   ( $galaxy, $system );
    $page .= ShowGalaxyFooter ( $galaxy, $system,  $CurrentMIP, $CurrentRC, $CurrentSP);

	$page .= "</tbody></table></div>";

	//si on est en mode vac
	if($user['urlaubs_modus'])
	{
	    includeLang('options');
       $parse['vacation_until'] = date("d.m.Y G:i:s",$user['urlaubs_until']);
		display(parsetemplate(gettemplate('options_body_vmode'), $parse), $title = 'Option mod vac', $topnav = true, $metatags = '', $AdminPage = false, $leftMenu = true);
	}
	else
	{	
		//si le mode frame est activé alors
		if($game_config['frame_disable'] == 1)
		{
			frame($page, 'Galaxy');
		}
		elseif($game_config['frame_disable'] == 0)
		{
			display ($page, $title, $topnav = true, $metatags = '', $AdminPage = false, $leftMenu = true);
		}
	}

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 - Created by Perberos
// 1.1 - Modified by -MoF- (UGamela germany)
// 1.2 - 1er Nettoyage Chlorel ...
// 1.3 - 2eme Nettoyage Chlorel ... Mise en fonction et debuging complet
?>