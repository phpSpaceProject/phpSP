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
	includeLang('admin/admin');
	if ($user['authlevel'] == 3) {
	switch ($_GET['page']) {
		case 'general':
			// --------------------------------------------------------------------------------------------------
			GeneralAdminPage ( $planetrow, $user );
			break;

		case 'player':
			// --------------------------------------------------------------------------------------------------
			PlayerAdminPage ( $planetrow, $user);
			break;

		case 'pratique':
			// --------------------------------------------------------------------------------------------------
			PratiqueAdminPage ( $planetrow, $user );
			break;
			
		case 'divers':
			// --------------------------------------------------------------------------------------------------
			DiversAdminPage ( $planetrow, $user );
			break;
			
		default:
			// --------------------------------------------------------------------------------------------------
			GeneralAdminPage ( $planetrow, $user );
			break;
	}
	} else {
		AdminMessage ( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 - Nettoyage modularisation
// 1.1 - Mise au point, mise en fonction pour lin�arisation du fonctionnement
// 1.2 - Liste de construction batiments
?>
