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