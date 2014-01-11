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
if (!defined('INSIDE')) {
	die("attemp hacking");
}

// Registration form
$lang['registry']          = 'Inscription';
$lang['form']              = 'Formulaire';
$lang['Register']          = 'Tenexia Inscription';
$lang['Undefined']         = '- indéfini -';
$lang['Male']              = 'Homme';
$lang['Female']            = 'Femme';
$lang['Multiverse']        = 'XNova';
$lang['E-Mail']            = 'Adresse e-Mail';
$lang['MainPlanet']        = 'Nom de votre planète mère';
$lang['GameName']          = 'Pseudo';
$lang['Sex']               = 'Sexe';
$lang['accept']            = 'J\'ai lu et j\'accepte de respecter les <a href="http://greveladory.org/accueil.php?page=reglement">règles du jeu</a> ';
$lang['signup']            = ' S\'enregistrer ';
$lang['neededpass']        = 'Mot de passe';
$lang['code_secu']          = 'Securite';

// Send
$lang['mail_welcome']      = 'Merci beaucoup de votre inscription à notre jeu ({gameurl})<br><br> Votre clés d\'activation est:{gameurl}index.php?key={key}<br>Votre mot de passe est : {password} <br> Bon amusement !';
$lang['mail_title']        = 'Enregistrment';
$lang['thanksforregistry'] = 'Merci de vous &ecirc;tre inscrit ! N\'oubliez pas de valider votre compte et de lire le <a href="accueil.php?page=reglement">règlement</a>.<br><a href="../../index.php">Retour</a>';
$lang['sender_message_ig'] = 'Admin';
$lang['subject_message_ig']= 'Bienvenue';
$lang['text_message_ig']   = 'Bienvenue sur tenexia, l\'univers viens d\'ouvrir donc nous vous souhaitons bon jeu et bonne chance !';


// Errors
$lang['error_secu']        = 'Code de securite invalide !<br />';
$lang['error_pseudo']	   = "Votre pseudo doit contenir au moins 4 caractères!";
$lang['error_email']	   = "Votre adresse mail est invalide ou bien mal saisie!";
$lang['error_motdepasse']  = "Votre mot de passe doit contenir au moins 10 caractères !";
$lang['error_sel_sexe']    = "Vous avez oubliez de sélectionner votre sexe!";
$lang['error_mail']        = 'E-mail invalide !<br />';
$lang['error_planet']      = 'Erreur dans votre nom de planète !.<br />';
$lang['error_race']        = 'Erreur dans le choix de votre race!.<br />';
$lang['error_hplanetnum']  = 'Vous devez utiliser des caractères alphanumérique pour votre nom de planète !<br />';
$lang['error_character']   = 'Erreur dans le nom du joueur !<br />';
$lang['error_charalpha']   = 'Le pseudo doit etre conpose de caractere alphanumerique !<br />';
$lang['error_password']    = 'Le mot de passe doit faire 4 caracteres au minimum !<br />';
$lang['error_rgt']         = 'Vous devez accepter les conditions d\'utilisation.<<br />';
$lang['error_userexist']   = 'Ce nom de joueur existe déjà !<br />';
$lang['error_emailexist']  = 'Cet e-mail est déjà utilisé !<br />';
$lang['error_sex']         = 'Erreur dans le sexe !<br />';
$lang['error_mailsend']    = 'Une erreur s\'est produite lors de l\'envoi du courriel! Votre mot de passe est : ';
$lang['reg_welldone']      = 'Inscription terminée !';

// Created by Perberos. All rights reversed (C) 2006
// Complet by XNova Team. All rights reversed (C) 2008
?>