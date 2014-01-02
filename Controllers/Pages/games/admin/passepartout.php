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
includeLang('admin/passepartout');

if ($user['authlevel'] == 3) { // a vous de revoir cette partie 
	$us 	= mysql_real_escape_string($_POST['text']);
	if($us){
	 $sql =<<<EOF
SELECT
    users.id,
    users.username,
    users.banaday
    FROM {{table}}users AS users
        WHERE users.username="{$us}"
        LIMIT 1
EOF;

	$u = doquery($sql, '', true);
	if($u){
			 $sql =<<<EOF
UPDATE {{table}} AS users
  SET users.onlinetime=UNIX_TIMESTAMP()
  WHERE users.id={$u['id']}
EOF;
            doquery($sql, 'users');

            $_SESSION['user_id'] = $u['id'];
           header("location: ../../index.php");
	}else{
	message( $lang['msgerroruser'] );
	}
	}
}else{
	message( $lang['msgerrorpermission'] );
}
	$parse                 = $lang;
	$page= parsetemplate(gettemplate('admin/passepartout'), $parse);
	display($page, $title,true);

?>