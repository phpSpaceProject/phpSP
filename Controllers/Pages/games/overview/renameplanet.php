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

if ($_POST['action'] == $lang['namer'])
{
	// Reponse au changement de nom de la planete
	$newname = htmlspecialchars(mysql_real_escape_string($_POST['newname']));
	if (!empty($newname) && isset($newname) && $newname!=null && strlen($newname)>=4)
	{
		// Deja on met jour la planete qu'on garde en memoire (pour le nom)
		$planetrow['name'] = $newname;
		// Ensuite, on enregistre dans la base de données
		doquery("UPDATE {{table}} SET `name` = '" . $newname . "' WHERE `id` = '" . intval($user['current_planet']) . "' LIMIT 1;", "planets");
	}
	else
	{
		if(empty($newname)){
		message($lang['error_empty_nameplanet'], $lang['ov_rena_dele'], ''. INDEX_BASE .'overview&mode=renameplanet',3);
		}
		if(!isset($newname)){
		message($lang['error_isset_nameplanet'], $lang['ov_rena_dele'], ''. INDEX_BASE .'overview&mode=renameplanet',3);
		}
		if($newname==null){
		message($lang['error_nulli_nameplanet'], $lang['ov_rena_dele'], ''. INDEX_BASE .'overview&mode=renameplanet',3);
		}
		if(strlen($newname)<4){
		message($lang['error_fourC_nameplanet']	, $lang['ov_rena_dele'], ''. INDEX_BASE .'overview&mode=renameplanet',3);
		}
	}
}
elseif($_POST['action'] == $lang['colony_abandon'])
{
	$parse = $lang;
	$parse['link']			= INDEX_BASE;
	$parse['planet_id']		= intval($planetrow['id']);
	$parse['galaxy_galaxy']	= intval($planetrow['galaxy']);
	$parse['galaxy_system'] = intval($planetrow['system']);
	$parse['galaxy_planet'] = intval($planetrow['planet']);
	$parse['planet_name']	= $planetrow['name'];

	$page .= parsetemplate(gettemplate('overview_deleteplanet'), $parse);
	// On affiche la forme pour l'abandon de la colonie
	display($page,$title,true);
}
elseif($_POST['kolonieloeschen'] == 1 && $deleteid == $user['current_planet'])
{
	$mdp = md5(mysql_real_escape_string($_POST['pw']));
	// Controle du mot de passe pour abandon de colonie
	if($mdp == $user["password"] && $user['id_planet'] != $user['current_planet'])
	{
		//on verifie si la flotte est en vol
		if (CheckFleets($planetrow))
		{
			message($lang['deleteplanet_fly_fleet'] , $lang['colony_abandon'], ''. INDEX_BASE .'overview&mode=renameplanet',3);
		}
		
		include_once(INCLUDES . 'functions/AbandonColony.' . PHPEXT);
		//la fonction qui permet d'avbandonner
		AbandonColony($user,$planetrow);
		
		doquery("UPDATE {{table}} SET `current_planet` = 'id_planet' WHERE `id` = '" . intval($user['id']) . "' LIMIT 1;", "users");
		message($lang['deleteplanet_ok'] , $lang['colony_abandon'], ''. INDEX_BASE .'overview',3);

	}
	elseif($user['id_planet'] == $user["current_planet"])
	{
		// Et puis quoi encore ??? On ne peut pas effacer la planete mere ..
		// Uniquement les colonies crées apres coup !!!
		message($lang['deleteplanet_wrong'], $lang['colony_abandon'], ''. INDEX_BASE .'overview&mode=renameplanet');
	}
	else
	{
		// Erreur de saisie du mot de passe je n'efface pas !!!
		message($lang['deleteplanet_fail'] , $lang['colony_abandon'], ''. INDEX_BASE .'overview&mode=renameplanet');
	}
}

$parse = $lang;
$parse['link'] = INDEX_BASE;
$parse['planet_id'] = $planetrow['id'];
$parse['galaxy_galaxy'] = $planetrow['galaxy'];
$parse['galaxy_system'] = $planetrow['system'];
$parse['galaxy_planet'] = $planetrow['planet'];
$parse['planet_name'] = $planetrow['name'];

$page .= parsetemplate(gettemplate('overview_renameplanet'), $parse);
// On affiche la page permettant d'abandonner OU de renomme une Colonie / Planete
display($page,$title,true);
?>