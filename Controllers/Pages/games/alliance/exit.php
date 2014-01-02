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
if ($ally['ally_owner'] == $user['id']) {
			message($lang['Owner_cant_go_out'], $lang['Alliance']);
		}
		$lang['link'] = INDEX_BASE;
		// se sale de la alianza
		if ($_GET['yes'] == 1) {
			doquery("UPDATE {{table}} SET `ally_id`=0, `ally_name` = '' WHERE `id`='{$user['id']}'", "users");
			$lang['Go_out_welldone'] = str_replace("%s", $ally_name, $lang['Go_out_welldone']);
			$page = MessageForm($lang['Go_out_welldone'], "<br>", $PHP_SELF, $lang['Ok']);
			// Se quitan los puntos del user en la alianza
		} else {
			// se pregunta si se quiere salir
			$lang['Want_go_out'] = str_replace("%s", $ally_name, $lang['Want_go_out']);
			$page = MessageForm($lang['Want_go_out'], "<br>", "?mode=exit&yes=1", "Oui");
		}
		display($page);