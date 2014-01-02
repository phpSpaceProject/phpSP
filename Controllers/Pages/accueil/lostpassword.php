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
includeLang('lostpassword');
$mailData = array(
    'recipient' => NULL,
    'sender' => $lang['PassForm'],
    'subject' => $lang['ResetPass']
    );
$username = NULL;
if (!empty($_POST)) {
    if(isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
        $username = mysql_real_escape_string($_POST['pseudo']);
        $sql =<<<EOF
SELECT users.email, users.username
  FROM {{table}} AS users
  WHERE users.username="{$username}"
  LIMIT 1
EOF;
        if (!($result = doquery($sql, 'users', true)))
		{
            message_accueil("Cet utilisateur n'existe pas", 'Erreur', 'lostpassword.php');
            die();
        }
		
        list($mailData['recipient'], $username) = $result;
    } else if(isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysql_real_escape_string($_POST['email']);
        $sql =<<<EOF
SELECT users.email, users.username
  FROM {{table}} AS users
  WHERE users.email="{$email}"
  LIMIT 1
EOF;
        if (!($result = doquery($sql, 'users', true))) {
            message_accueil("Cet email n'est utilisé par aucun joueur", 'Erreur', 'lostpassword.php');
            die();
        }
        list($mailData['recipient'], $username) = $result;
    } else {
        message_accueil('Veuillez entrer votre login ou votre email.', 'Erreur', 'lostpassword.php');
        die();
    }

    if (!is_null($mailData['recipient'])) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomPass = '';
        $size = rand(8, 10);
        for ($i = 0; $i < $size; $i++) {
            $randomPass .= $characters[rand(0, strlen($characters) - 1)];
        }

        $message_accueil =<<<EOF
Votre mot de passe a été modifié, veuillez trouver ci-dessous vos informations de connexion :
login : $username
mot de passe : $randomPass

A bientôt sur XNova:Legacies
EOF;

        $version = VERSION;
        $headers =<<<EOF
From: {$mailData['sender']}
X-Sender: Legacies/{$version}

EOF;
mail($mailData['recipient'], $mailData['subject'], $message_accueil, $headers);
$newpasswor = md5($randomPass);
 					$Qry = "
						UPDATE
								{{table}}
						SET 
								`password` ='{$newpasswor}'
						WHERE 
								`username`      = '{$username}';";

					doquery($Qry, 'users');
        message_accueil('Mot de passe envoyé ! Veuillez regarder votre boite e-mail ou dans vos spam.', 'Nouveau mot de passe', 'index.php');
        die();
    }
}

$parse = $lang;
$page = parsetemplate(gettemplate('accueil/lostpassword'), $parse);
display($page, $title);
