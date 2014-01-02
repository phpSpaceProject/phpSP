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

includeLang('messages');
includeLang('system');


$Mode = $_GET['mode'];


if ($Mode != 'add') {

    $parse['Declaration']     = $lang['Declaration'];
    $parse['DeclarationText'] = $lang['DeclarationText'];

    $page = parsetemplate(gettemplate('multi'), $parse);
    display($page, $lang['messages']);

}
if ($mode == 'add') {
    $Texte = $_POST['texte'];
    $Joueur = $user['username'];

    $SQLAjoutDeclaration = "INSERT INTO {{table}} SET ";
    $SQLAjoutDeclaration .= "`player` = '". $Joueur ."', ";
	$SQLAjoutDeclaration .= "`text` = '". $Texte ."';";
    doquery($SQLAjoutDeclaration, 'multi');


    message($lang['sys_request_ok'],$lang['sys_ok']);

}
// D�claration des multi compte
// Par Tom pour XNova
?>

