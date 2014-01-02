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

define('LOGIN'   , true);

    includeLang('logout');
    
    $parse = array();
    $second = 0; // Nombre de secondes qui doivent s'écouler avant la redirection
    
    $parse['session_close'] = $lang['see_you'];
    $parse['mes_session_close'] = $lang['session_closed'];
    $parse['tps_seconds'] = $second; // On indique au script le nombre de secondes pour le compte à rebours

    setcookie('nova-cookie', "", time()-100000, "/", "", 0);
	session_destroy();
	unset($_SESSION);	

    $page = parsetemplate(gettemplate('logout'), $parse);
 	header("Location:". SITEURL ."");
    // header("Refresh: ".$second."; Url = ". REDIRECT ."");
    
    display($page, $title, $topnav = false, $metatags = '', $AdminPage = false, $leftMenu = false);

// -----------------------------------------------------------------------------------------------------------
// History version
//
// 1.0   : Version Originale de ?????? pour Xnova
// 1.1   : Redirection et affichage d'un compte à rebours de Winjet
// 1.11 : Ajout d'un lien pour effectuer la redirection tout de suite 
//          et éviter d'attendre la fin du compte à rebours
?>