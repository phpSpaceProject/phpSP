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

	if ( $CurrentUser['authlevel'] == 3) {
		$PageTpl = gettemplate( "admin/deletuser" );

		if ( $mode != "delet" ) {
			$parse['adm_bt_delet'] = $lang['adm_bt_delet'];
		}

		$Page = parsetemplate( $PageTpl, $parse );
		//si le mode frame est activé alors
		display($Page, $title,true);
		

	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>