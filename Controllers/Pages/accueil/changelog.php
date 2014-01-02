<?php
/**
 * This file is part of Nacatiks
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.nacatikx.dafun.com/forum/index.php
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

includeLang('changelog');
$parse = $lang;
$template = gettemplate('accueil/changelog_table');

$body ='';
foreach($lang['changelog'] as $a => $b)
{

	$parse['version_number'] = $a;
	$parse['description'] = nl2br($b);
	$body .= parsetemplate($template, $parse);

}
$parse['body'] = $body;

$page = parsetemplate(gettemplate('accueil/changelog_body'), $parse);
display($page,$title, $topnav = true, $metatags = '', $AdminPage = false, $leftMenu = true);

// Created by Perberos. All rights reversed (C) 2006
?>