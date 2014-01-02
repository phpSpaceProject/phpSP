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
    includeLang('chat');
    $BodyTPL = gettemplate('chat_body');

	$link =INDEX_BASE;
    $nick = $user['username'];
    $parse = $lang;

    $NbreDaysDelMsg = 3;
    $QryDeleteOldMsg = <<<SQL
                                DELETE FROM {{table}} 
                                WHERE         `timestamp` <= UNIX_TIMESTAMP(NOW()) - (3600 * 24 * {$NbreDaysDelMsg});
SQL;
    
    // Les messages datant de plus de 3 jours
    // sont effacés
    doquery($QryDeleteOldMsg, 'chat');
    
    $QrySelectUsers = <<<SQL
                                SELECT         `id`, `username`, `authlevel` 
                                FROM         {{table}} 
                                WHERE         (`current_page` LIKE '%{$link}chat_msg%' OR `current_page` LIKE '%{$link}discussion%') AND 
    `onlinetime` >= UNIX_TIMESTAMP(NOW()) - 300 
                                ORDER BY     `username` ASC;
SQL;
    
    // On récupère les joueurs qui sont sur le chat
    // il y a moins de 5 minutes
    $Result = doquery($QrySelectUsers, 'users');
    
    $parse['UsersConnected'] = "";
    
    while($TheUser = mysql_fetch_array($Result))
    {
        // Couleur personnalisée selon
        // le rang du joueur
        
        if ($TheUser['authlevel'] == 3)
            $color = "#FF0000";
        elseif ($TheUser['authlevel'] == 2)
            $color = "#660066";
        elseif ($TheUser['authlevel'] == 1)
            $color = "#006600";
        else
            $color = "#FFFFFF";
            
        $parse['UsersConnected'] .= <<<TXT
    <tr align="left">
        <td class="c" style="background-image: none; border: 0px;"><a href="messages.php?mode=write&id={$TheUser['id']}" style="color: {$color};">{$TheUser['username']}</a></td>
    </tr>
TXT;
    }
    

    $page = parsetemplate($BodyTPL, $parse);
    display($page, $lang['Chat'], true);

// Shoutbox by e-Zobar - Copyright XNova Team 2008
// Modifié par Winjet
?>