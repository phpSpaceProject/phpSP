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

//futur systeme de connexion
/* 
if(isset($_POST['connect']))
{
	$login = htmlentities($_POST['login'],ENT_QUOTES,"UTF-8");
	$password = md5(mysql_real_escape_string($_POST['password']));

	$condition ="WHERE (`username`='{$login}' or `email`='{$login}') AND password='{$password}'";
	//on va chercher la table users .
	$Users = Model::load("users");//attention c'est le model config est nom le fichier de connexion
	$SelectUser = $Users->find(array('conditions'=>"{$condition}",'fields' =>"`id`,`username`,`email`,`password`"));
	//il a trouvé !!!!
	if(!empty($SelectUser))
	{
		foreach ($SelectUser as $us => $value)
		{
			//bon on créer la session.
			$_SESSION['id'] = intval($value['id']);
			
			//et par la meme occase rediriger
			header("Location:". GAME_BASE ."overview");
		}
	}
}
*/
includeLang('login');
if (!empty($_POST)) {
    $userData = array(
        'username' => mysql_real_escape_string($_POST['username']),
        'password' => mysql_real_escape_string($_POST['password'])
    );
    $sql =<<<EOF
SELECT
    users.id,
    users.username,
    users.banaday,
    (CASE WHEN MD5("{$userData['password']}")=users.password THEN 1 ELSE 0 END) AS login_success,
    CONCAT((@salt:=MID(MD5(RAND()), 0, 4)), SHA1(CONCAT(users.username, users.password, @salt))) AS login_rememberme
    FROM {{table}}users AS users
        WHERE users.username="{$userData['username']}"
        LIMIT 1
EOF;

    $login = doquery($sql, '', true);

    if($login['banaday'] <= time() & $login['banaday'] !='0'){
        doquery("UPDATE {{table}} SET `banaday` = '0', `bana` = '0', `urlaubs_modus` ='0'  WHERE `username` = '".$login['username']."' LIMIT 1;", 'users');
        doquery("DELETE FROM {{table}} WHERE `who` = '".$login['username']."'",'banned');
    }

    if ($login) {
        if (intval($login['login_success'])) {
            if (isset($_POST["rememberme"])) {
                setcookie('nova-cookie', array('id' => $login['id'], 'key' => $login['login_rememberme']), time() + 2592000);
            }

            $sql =<<<EOF
UPDATE {{table}} AS users
  SET users.onlinetime=UNIX_TIMESTAMP()
  WHERE users.id={$login['id']}
EOF;
            doquery($sql, 'users');

            $_SESSION['user_id'] = $login['id'];
            header("Location:index.php?page=overview");
            exit(0);
        } else {
            message_accueil($lang['Login_FailPassword'], $lang['Login_Error']);
        }
    } else {
        message_accueil($lang['Login_FailUser'], $lang['Login_Error']);
    }
} else {
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

    // Test pour prendre le nombre total de joueur et le nombre de joueurs connect�s
    if (isset($_GET['ucount']) && $_GET['ucount'] == 1) {
        $page = $PlayersOnline['onlinenow']."/".$Count['players'];
        die ( $page );
    } else {
        display($page, $title);
    }
}
