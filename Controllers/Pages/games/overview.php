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

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$deleteid = intval(isset($_POST['deleteid']) ? $_POST['deleteid'] : '');
$pl = intval(isset($_GET['pl']) ? $_GET['pl'] : 0);

includeLang('resources');
includeLang('overview');
includeLang('vote');
switch ($mode)
{
	case 'renameplanet':
		include_once('overview/renameplanet.'. PHPEXT);
	break;
    default:
	if ($user['id'] != '')
	{
			$parse = $lang;
			$parse['link'] = INDEX_BASE;
			// --- Gestion des messages ----------------------------------------------------------------------
			$Have_new_message = "";
			if ($user['new_message'] != 0)
			{
				$Have_new_message .= "<tr>";
				if ($user['new_message'] == 1)
				{
					$Have_new_message .= "<td colspan=4><a href=". INDEX_BASE ."messages>" . $lang['Have_new_message'] . "</a></td>";
				}
				elseif($user['new_message'] > 1)
				{
					$Have_new_message .= "<td colspan=4><a href='". INDEX_BASE ."messages'>";
					$m = pretty_number($user['new_message']);
					$Have_new_message .= str_replace('%m', $m, $lang['Have_new_messages']);
                    $Have_new_message .= "</a></td>";
                }
                $Have_new_message .= "</tr>";
            }

            $parse['planet_name'] = $planetrow['name'];
            $parse['planet_diameter'] = pretty_number($planetrow['diameter']);
            $parse['planet_field_current'] = intval($planetrow['field_current']);
            $parse['planet_field_max'] = CalculateMaxPlanetFields($planetrow,$user);
            $parse['planet_temp_min'] = intval($planetrow['temp_min']);
            $parse['planet_temp_max'] = intval($planetrow['temp_max']);
            $parse['galaxy_galaxy'] = intval($planetrow['galaxy']);
            $parse['galaxy_planet'] = intval($planetrow['planet']);
            $parse['galaxy_system'] = intval($planetrow['system']);
			
			//on veux afficher les admins dans le classement
			if(SHOW_ADMIN_IN_CLASSEMENT==1)
			{
				$StatRecord = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . intval($user['id']) . "';", 'statpoints', true);
			}
			else
			{
				$adminstat  = doquery("SELECT * FROM {{table}} WHERE `authlevel` >= '3';", 'users');
				$retraitAdmin = "";
				$nombreadmin = 0;
				while ($admins = mysql_fetch_array($adminstat))
				{
					$retraitAdmin .= " AND `id_owner` !='".intval($admins['id'])."' ";
					$nombreadmin += count($admins['id']);
				}
				$StatRecord = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . $user['id'] . "' ".$retraitAdmin.";", 'statpoints', true);
			}

			//pour le classement
			$parse['user_rank'] = $StatRecord['total_rank'];
            $parse['user_points'] = pretty_number($StatRecord['build_points']);
            $parse['user_fleet'] = pretty_number($StatRecord['fleet_points']);
			$parse['user_def'] = pretty_number($StatRecord['defs_points']);
            $parse['player_points_tech'] = pretty_number($StatRecord['tech_points']);
			if($StatRecord['pertes_points'] < 0)
			{
				$parse['player_points_pertes'] = 0;
			}
			else
			{
				$parse['player_points_pertes'] = pretty_number($StatRecord['pertes_points']);
			}
			
            $parse['total_points'] = pretty_number($StatRecord['total_points']);

            $ile = $StatRecord['total_old_rank'] - $StatRecord['total_rank'];
            if ($ile >= 1)
			{
				$parse['ile'] = "<font color=lime>+" . $ile . "</font>";
            }
			elseif($ile < 0)
			{
				$parse['ile'] = "<font color=red>-" . $ile . "</font>";
            }
			elseif($ile == 0)
			{
				$parse['ile'] = "<font color=lightblue>" . $ile . "</font>";
            }
            $parse['u_user_rank'] = pretty_number($StatRecord['total_rank']);
            $parse['user_username'] =htmlentities($user['username'],ENT_QUOTES);


            $parse['energy_used'] = $planetrow["energy_max"] - $planetrow["energy_used"];

            $parse['Have_new_message'] = $Have_new_message;
            $parse['time'] = "<div id=\"dateheure\"></div>";
            $parse['dpath'] = $dpath;
            $parse['planet_image'] = typeplanets($user,$planetrow['planet']);
            $parse['max_users'] = $game_config['users_amount']-$nombreadmin;
            $parse['metal_debris'] = pretty_number($galaxyrow['metal']);
            $parse['crystal_debris'] = pretty_number($galaxyrow['crystal']);
            if ($planetrow['b_building'] != 0)
			{
                UpdatePlanetBatimentQueueList ($planetrow, $user);
                if ($planetrow['b_building'] != 0)
				{
                    $BuildQueue = explode (";", $planetrow['b_building_id']);
                    $CurrBuild = explode (",", $BuildQueue[0]);
                    $RestTime = $planetrow['b_building'] - time();
                    $PlanetID = $planetrow['id'];
                    $Build = InsertBuildListScript ("overview");
					$namebuild = htmlentities($lang['tech'][$CurrBuild[0]],ENT_QUOTES);
					
					$Build .='<a href="'. INDEX_BASE .'infos&gid='.$CurrBuild[0].'" title="'.$namebuild.'"><img border="0" src="'.$dpath.'Games/batiment/'.$CurrBuild[0].'.png" align="top" width="'.$width.'" title="'.$namebuild.'" alt="'.$namebuild.'"></a><br>';
                    $Build .= $lang['tech'][$CurrBuild[0]] . ' (' . ($CurrBuild[1]) . ')';
                    $Build .= "<br /><div id=\"blc\" class=\"z\">" . pretty_time($RestTime) . "</div>";
                    $Build .= "\n<script language=\"JavaScript\">";
                    $Build .= "\n	pp = \"" . $RestTime . "\";\n"; // temps necessaire (a compter de maintenant et sans ajouter time() )
                    $Build .= "\n	pk = \"" . 1 . "\";\n"; // id index (dans la liste de construction)
                    $Build .= "\n	pm = \"cancel\";\n"; // mot de controle
                    $Build .= "\n	pl = \"" . $PlanetID . "\";\n"; // id planete
                    $Build .= "\n	t();\n";
                    $Build .= "\n</script>\n";
                    $parse['building'] = $Build;
                } else {
                    $parse['building'] = $lang['Free'];
                }
            } else {
                $parse['building'] = $lang['Free'];
            }
			
            $query = doquery('SELECT username FROM {{table}} ORDER BY register_time DESC', 'users', true);
            $parse['last_user'] = $query['username'];
            $parse['users_amount'] = intval($game_config['users_amount']);
            // Rajout d'une barre pourcentage
            // Calcul du pourcentage de remplissage
            $parse['case_pourcentage'] = floor($planetrow["field_current"] / CalculateMaxPlanetFields($planetrow,$user) * 100) . $lang['o/o'];
            // Barre de remplissage
            $parse['case_barre'] = floor($planetrow["field_current"] / CalculateMaxPlanetFields($planetrow,$user) * 100) * 4.0;
            // Couleur de la barre de remplissage
            if ($parse['case_barre'] > (100 * 4.0)) {
                $parse['case_barre'] = 400;
                $parse['case_barre_barcolor'] = '#C00000';
            } elseif ($parse['case_barre'] > (80 * 4.0)) {
                $parse['case_barre_barcolor'] = '#C0C000';
            } else {
                $parse['case_barre_barcolor'] = '#00C000';
            }
            {
            $parse['Raids'] = $lang['Raids'];
            $parse['NumberOfRaids'] = $lang['NumberOfRaids'];
            $parse['RaidsWin'] = $lang['RaidsWin'];
            $parse['RaidsLoose'] = $lang['RaidsLoose'];
            $parse['raids'] = $user['raids'];
			$parse['raidswin'] = sprintf("%d", (int) $user['raidswin']);
			$parse['raidsloose'] = sprintf("%d", (int) $user['raidsloose']);
			
			if(is_mobile()==false)
			{
				$parse['width'] = 120;
				$parse['casw'] = 400;
				$parse['widthplapla'] = 600;
				
			}
			else
			{
				$parse['casw']=110;
				$parse['width']= $width = 25;
				$parse['widthplapla']= 400;
			}

            // Compteur de Membres en ligne
            $OnlineUsers = doquery("SELECT COUNT(*) FROM {{table}} WHERE onlinetime>='" . (time()-15 * 60) . "'", 'users', 'true');
            $parse['NumberMembersOnline'] = $OnlineUsers[0];

            $page = parsetemplate(gettemplate('overview_body'), $parse);

            display($page, $title,true);
            break;
			}
	}
}
?>