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

	includeLang('limited');

	$parse   = $lang;

	if(is_mobile())
	{
		$parse['mobile'] ="width='400px'";
	}
	else
	{
		$parse['mobile'] ="";
	}
	// var_dump(pretty_time(floor($select['temp']+ 432000 - time())));
	$QrySelectUsers = doquery("SELECT * FROM {{table}} WHERE `attaquant` ='".$user['id']."' ORDER BY `temp` ASC",'attack');

	while( $recup = mysql_fetch_array($QrySelectUsers) ) {
	$theattaquant  = doquery("SELECT *  FROM {{table}} WHERE `id` = '".$recup['attaquant']."';", 'users', true);
	$thedefenseur  = doquery("SELECT *  FROM {{table}} WHERE `id` = '".$recup['defenseur']."';", 'users', true);
	if(($recup['temp'] + TIME_LONG_ATTACK_BLOCKED) <= time() or $recup['compteur'] < 1)
	{
	$parse['limitattaq']    .="";
	}
	else
	{
		if($recup['compteur'] < MAX_ATTACK)
		{
		$parse['limitattaq']    .="<tr><th>".$theattaquant['username']."</th>
										<th>".$thedefenseur['username']."</th>
										<th></th>
										<th>".$recup['compteur']."</th></tr>";
		}
		else
		{
		$parse['limitattaq']    .="<tr><th>".$theattaquant['username']."</th>
										<th>".$thedefenseur['username']."</th>
										<th>".pretty_time(floor($recup['temp'] + TIME_LONG_ATTACK_BLOCKED - time()))."</th>
										<th>".$recup['compteur']."</th></tr>";
		}
	}
	}


	$page = parsetemplate(gettemplate('limit_body'), $parse);

	display($page,$title,true);

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 - Mise au propre (Virer tout ce qui ne sert pas a une prise de contact en fait)
?>

