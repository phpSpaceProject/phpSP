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
 *===================================
 *
 */

	includeLang('login');

	if(isset($_POST['submit']))
	{
		/* sécurisation des champs */

		$login = htmlentities($_POST['username'],ENT_QUOTES,"UTF-8");
		$password = md5(mysql_real_escape_string($_POST['password']));

		/*===============
		 * on autorise l'internaute à se connecter avec:
		 *
		 * - adresse mail ou
		 * - pseudo
		 */

		$condition ="WHERE (`username`='{$login}' or `email`='{$login}') AND password='{$password}'";
		$Users = Model::load("users");
		$SelectUser = $Users->find(array('conditions'=>"{$condition}",'fields' =>"`id`,`username`,`email`,`password`"));
		if(!empty($SelectUser))
		{
			foreach ($SelectUser as $us => $value)
			{
				/* création de la session */
				$_SESSION['user_id'] = intval($value['id']);
				
				/* utilisation des cookies n'est plus obligatoire
				if (isset($_POST["rememberme"])) {
					setcookie('nova-cookie', array('id' => $value['id'], 'key' => $login['login_rememberme']), time() + 2592000);
				}
				*/
				
				//et par la meme occase rediriger
				header("Location:". INDEX_BASE ."overview");
			}
		}
	}
	
    $parse                 = $lang;
    $Count                 = doquery('SELECT COUNT(DISTINCT users.id) AS `players` FROM {{table}} AS users WHERE users.authlevel < 3', 'users', true);
    $LastPlayer            = doquery('SELECT users.`username` FROM {{table}} AS users ORDER BY `register_time` DESC LIMIT 1', 'users', true);
    $parse['last_user']    = $LastPlayer['username'];
    $PlayersOnline         = doquery("SELECT COUNT(DISTINCT id) AS `onlinenow` FROM {{table}} AS users WHERE `onlinetime` > (UNIX_TIMESTAMP()-900) AND users.authlevel < 3", 'users', true);
    $parse['online_users'] = $PlayersOnline['onlinenow'];
    $parse['users_amount'] = $Count['players'];
    $parse['servername']   = $game_config['game_name'];
    $parse['forum_url']    = $game_config['forum_url'];
	$parse['link'] = ACCUEIL_BASE;
	
    $page = parsetemplate(gettemplate('accueil/login_body'), $parse);
	display($page, $title);
