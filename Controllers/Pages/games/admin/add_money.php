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

	if ($user['authlevel'] >= 2) {
		includeLang('admin');

		$mode      = $_POST['mode'];

		$PageTpl   = gettemplate("admin/add_money");
		$parse     = $lang;
		
			$parse['adm_am_gala']=$lang['adm_am_gala'];
			$parse['adm_am_syst']=$lang['adm_am_syst'];
			$parse['adm_am_plant']=$lang['adm_am_plant'];
			$parse['adm_am_type']=$lang['adm_am_type'];
			$parse['adm_am_coor']=$lang['adm_am_coor'];
			$parse['adm_am_ress']=$lang['adm_am_ress'];
			
		if ($mode == 'addit') {
			$metal       = $_POST['metal'];
			$cristal     = $_POST['cristal'];
			$deut        = $_POST['deut'];
			
			$gala        = intval($_POST['gala']);
			$syst        = intval($_POST['syst']);
			$plant        = intval($_POST['plant']);
			$typeplant     = intval($_POST['typeplant']);

			$planetexist = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '" . $gala . "' AND `system` = '" . $syst . "' AND `planet` = '" . $plant . "';", 'planets', true);

			if($planetexist != false)
			{
				$Qry = "
					UPDATE
							{{table}}
					SET 
							`metal` =`metal` + '{$metal}',
							`crystal` = `crystal` + '{$cristal}',
							`deuterium` =`deuterium` + '{$deut}'
					WHERE 
								`galaxy`      = '{$galaxie}' and
								`system`      = '{$systeme}' and
								`planet`      = '{$planete}' and
								`planet_type`      = '{$typeplant}';";

				doquery($Qry, 'planets');
				
				AdminMessage ( $lang['adm_am_done'], $lang['adm_am_ttle'] );

			}
			else
			{
				AdminMessage ($lang['adm_am_error1']." ".$gala.":".$syst.":".$plant." ".$lang['adm_am_error2'], $lang['adm_am_ttle'] );
			}
		}
		$Page = parsetemplate($PageTpl, $parse);

					//si le mode frame est activé alors
			if($game_config['frame_disable'] == 1)
			{
				frame ( $Page, $lang['sys_overview'], false, '', true);
			}
			elseif($game_config['frame_disable'] == 0)
			{
				display ($Page, $title = 'generale', $topnav = false, $metatags = '', $AdminPage = true, $leftMenu = false,$leftMenuAdmin = true);
			}
	} else {
		AdminMessage ( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>