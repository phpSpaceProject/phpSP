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


$Id                  = $_GET['techid'];
$PageTPL             = gettemplate('techtree_details');
$RowsTPL             = gettemplate('techtree_details_rows');

$parse               = $lang;
$parse['te_dt_id']   = $Id;
$parse['te_dt_name'] = $lang['tech'][$Id];
$Liste = "";

if ($Id == 5) {
    $Liste .= "<tr><th>".$lang['tech']['12']." (".$lang['level']." 1)</th></tr>";
    $Liste .= "<tr><td class=\"c\">2</td><tr>";
    $Liste .= "<tr><th>".$lang['tech']['3']." (".$lang['level']." 5)</th></tr>";
    $Liste .= "<tr><th>".$lang['tech']['106']." (".$lang['level']." 3) <a href=\"". INDEX_BASE ."detail&tech=106\">[i]</a></th></tr>";
}

$parse['Liste'] = $Liste;
$page = parsetemplate($PageTPL, $parse);

display($page,$title, $topnav = true, $metatags = '', $AdminPage = false, $leftMenu = true);

?>