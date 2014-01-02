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

    // On récupère les informations du message et de l'envoyeur
    if (isset($_POST["msg"]) && isset($user['username']))
    {
       $nick = trim (str_replace ("+","plus",$user['username']));
       $msg  = trim (str_replace ("+","plus",$_POST["msg"]));
       $msg  = addslashes ($_POST["msg"]);
       $nick = addslashes ($user['username']);
    }
    else {
       $msg="";
       $nick="";
    }

    // Ajout du message dans la database
    if ( !empty($msg) && !empty($nick) ) {
       $query = doquery("INSERT INTO {{table}}(user, message, timestamp) VALUES ('".$nick."', '".$msg."', '".time()."')", "chat");
    }

// Shoutbox by e-Zobar - Copyright XNova Team 2008
// Modifié par Winjet
?>