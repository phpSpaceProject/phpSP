<?php
/**
 * Tis file is part of Nacatiks
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
 
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('url_rewriter.tags', '');

// Même chose que error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

try
{
	// On crée la connexion avec la base de données
	$db = new PDO('mysql:host=localhost;dbname=exemple','root','');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec('SET NAMES UTF8');
}
catch(PDOException $e)
{
	// Oups !!! On a rencontré un problème
	die("Error : " . $e->getMessage());
}				
?>