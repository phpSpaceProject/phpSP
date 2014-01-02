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

	if ($user['authlevel'] >= 3) {
		includeLang('admin');

		$parse          = $lang;
		$parse['dpath'] = $dpath;
		$parse['mf']    = $mf;

		/*
		mysql_connect('serveur_mysql', 'utilisateur_mysql', 'mot_de_passe_mysql');
		$base = 'test';
		$table = mysql_list_tables($base);
		//on prépare la requête
		$sql = "OPTIMIZE TABLE ";
		//on recherche toutes les données des tables
		$req = mysql_query('SHOW TABLE STATUS');
		while($data = mysql_fetch_assoc($req))
		{
			//on regarde seulement les tables qui affichent des pertes
			if($data['Data_free'] > 0)
			{
				//et on l'inclut si elle comporte des pertes
				$sql .= '`'.$data['Name'].'`, ';
			}
		}
		//on enlève le ', ' de trop
		$sql = substr($sql, 0, (strlen($sql)-2));
		//et on optimise
		mysql_query($sql);
		mysql_close();

		*/
		$page = parsetemplate( $PageTPL	, $parse );
					//si le mode frame est activé alors
			if($game_config['frame_disable'] == 1)
			{
			frame ( $page, $lang['sys_overview'], false, '', true);
			}
			elseif($game_config['frame_disable'] == 0)
			{
			display ($page, $title = 'generale', $topnav = false, $metatags = '', $AdminPage = true, $leftMenu = false,$leftMenuAdmin = true);
			}
	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>