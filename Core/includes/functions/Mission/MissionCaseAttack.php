<?php
/**
 * This file is part of Noxgame
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Copyright (c) 2012-Present, mandalorien
 * All rights reserved.
 *=========================================================
  _   _                                     
 | \ | |                                    
 |  \| | _____  ____ _  __ _ _ __ ___   ___ 
 | . ` |/ _ \ \/ / _` |/ _` | '_ ` _ \ / _ \
 | |\  | (_) >  < (_| | (_| | | | | | |  __/
 |_| \_|\___/_/\_\__, |\__,_|_| |_| |_|\___|
                  __/ |                     
                 |___/                                                                             
 *=========================================================
 *
 */

function MissionCaseAttack ($FleetRow)
{
    global $user, $xnova_root_path, $pricelist, $lang, $resource, $CombatCaps;

    if ($FleetRow['fleet_start_time'] <= time()) {
				if($FleetRow['fleet_check'] == 0) {
					$select = doquery("SELECT * FROM {{table}} WHERE `attaquant`='".$FleetRow['fleet_owner']."' AND `defenseur`='".$FleetRow['fleet_target_owner']."' LIMIT 1",'attack',true);
					$ActiviteDefenseur = doquery("SELECT `onlinetime` FROM {{table}} WHERE `id`='".$FleetRow['fleet_target_owner']."' LIMIT 1","users",true);
					// on vérifie le temps d'abord!
					$time = time();
					if ($ActiviteDefenseur['onlinetime'] > $time - 3600 * 24 * 7) {
						if(!$select) {
							doquery("INSERT INTO {{table}} SET `attaquant`='".$FleetRow['fleet_owner']."' , `defenseur`='".$FleetRow['fleet_target_owner']."' , `temp`='".time()."' , `compteur`='1'",'attack');
							doquery("UPDATE {{table}} SET  `fleet_check`='1'  WHERE `fleet_id`='".$FleetRow['fleet_id']."'",'fleets');
						} else {
							
							// 24 heure !
							$temp = $select['temp'] + 3600 * 24;
							
					  
							// si il a atteint la limit et que le les 24 heure ne se sont pas encore ecoulé
							if($select['compteur'] >= MAX_ATTACK and $temp > $time) {
								SendSimpleMessage ( $FleetRow['fleet_owner'], '0', $FleetRow['fleet_end_time'], 1, 'Contract de paix Uniguerre', $lang['sys_mess_fleetback'], 'Vous avez atteint la limite du nombre maximum d\'attaque envers un m&ecirc;me joueur ! C\'est-&agrave;-dire '.MAX_ATTACK.' attaques/joueur.');
								doquery("UPDATE {{table}} SET `fleet_mess`='1' , `fleet_check`='1'  WHERE `fleet_id`='".$FleetRow['fleet_id']."'",'fleets');
							// si il a atteint la limit mais que le les 24 heure sont plus inferieur ou egale au temps actuel	
							} elseif($select['compteur'] >= MAX_ATTACK and $temp <= $time) {
								doquery("UPDATE {{table}} SET `compteur`='1'  AND `temp`='".time()."' WHERE `attaquant`='".$FleetRow['fleet_owner']."' AND `defenseur`='".$FleetRow['fleet_target_owner']."'",'attack');
								doquery("UPDATE {{table}} SET  `fleet_check`='1'  WHERE `fleet_id`='".$FleetRow['fleet_id']."'",'fleets');
								// si il a pas atteint la limit mais que le les 24 heure sont plus inferieur ou egale au temps actuel
							} elseif($select['compteur'] < MAX_ATTACK AND $temp < $time) {
								doquery("UPDATE {{table}} SET `compteur`=1 , `temp`='".time()."' WHERE `attaquant`='".$FleetRow['fleet_owner']."' AND `defenseur`='".$FleetRow['fleet_target_owner']."'",'attack');
								doquery("UPDATE {{table}} SET  `fleet_check`='1'  WHERE `fleet_id`='".$FleetRow['fleet_id']."'",'fleets');   
							} elseif($select['compteur'] < MAX_ATTACK AND $temp >= $time) {
								doquery("UPDATE {{table}} SET `compteur`=compteur+1 , `temp`='".time()."' WHERE `attaquant`='".$FleetRow['fleet_owner']."' AND `defenseur`='".$FleetRow['fleet_target_owner']."'",'attack');
								doquery("UPDATE {{table}} SET  `fleet_check`='1'  WHERE `fleet_id`='".$FleetRow['fleet_id']."'",'fleets');
							}
						}
					}else{
						doquery("UPDATE {{table}} SET  `fleet_check`='1'  WHERE `fleet_id`='".$FleetRow['fleet_id']."'",'fleets');
					}
				}
		
        if ($FleetRow['fleet_mess'] == 0) {
            $QryTargetPlanet = "SELECT * FROM {{table}} ";
            $QryTargetPlanet .= "WHERE ";
            $QryTargetPlanet .= "`galaxy` = '" . $FleetRow['fleet_end_galaxy'] . "' AND ";
            $QryTargetPlanet .= "`system` = '" . $FleetRow['fleet_end_system'] . "' AND ";
            $QryTargetPlanet .= "`planet` = '" . $FleetRow['fleet_end_planet'] . "' AND ";
            $QryTargetPlanet .= "`planet_type` = '" . $FleetRow['fleet_end_type'] . "';";
            $TargetPlanet = doquery($QryTargetPlanet, 'planets', true);
            $TargetUserID = $TargetPlanet['id_owner'];

            $QryCurrentUser = "SELECT * FROM {{table}} ";
            $QryCurrentUser .= "WHERE ";
            $QryCurrentUser .= "`id` = '" . $FleetRow['fleet_owner'] . "';";
            $CurrentUser = doquery($QryCurrentUser , 'users', true);
            $CurrentUserID = $CurrentUser['id'];

            $QryTargetUser = "SELECT * FROM {{table}} ";
            $QryTargetUser .= "WHERE ";
            $QryTargetUser .= "`id` = '" . $TargetUserID . "';";
            $TargetUser = doquery($QryTargetUser, 'users', true);

            // Actualisation des ressources de la planete.
      		PlanetResourceUpdate($TargetUser, $TargetPlanet, time());

            $QryTargetTech = "SELECT ";
            $QryTargetTech .= "`military_tech`, `defence_tech`, `shield_tech` ";
            $QryTargetTech .= "FROM {{table}} ";
            $QryTargetTech .= "WHERE ";
            $QryTargetTech .= "`id` = '" . $TargetUserID . "';";

            $TargetTechno = doquery($QryTargetTech, 'users', true);

            $QryCurrentTech = "SELECT ";
            $QryCurrentTech .= "`military_tech`, `defence_tech`, `shield_tech` ";
            $QryCurrentTech .= "FROM {{table}} ";
            $QryCurrentTech .= "WHERE ";
            $QryCurrentTech .= "`id` = '" . $CurrentUserID . "';";
            $CurrentTechno = doquery($QryCurrentTech, 'users', true);

            for ($SetItem = 200; $SetItem < 500; $SetItem++) {
                if ($TargetPlanet[$resource[$SetItem]] > 0) {
                    $TargetSet[$SetItem]['count'] = $TargetPlanet[$resource[$SetItem]];
                }
            }

            $TheFleet = explode(";", $FleetRow['fleet_array']);
            foreach($TheFleet as $a => $b) {
                if ($b != '') {
                    $a = explode(",", $b);
                    $CurrentSet[$a[0]]['count'] = $a[1];
                }
            }

            include_once(INCLUDES . 'ataki.' . PHPEXT);
            // Calcul de la duree de traitement (initialisation)
            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $starttime = $mtime;

            $walka = walka($CurrentSet, $TargetSet, $CurrentTechno, $TargetTechno);
            // Calcul de la duree de traitement (calcul)
            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;
            $totaltime = ($endtime - $starttime);
            // Ce qu'il reste de l'attaquant
            $CurrentSet = $walka["atakujacy"];
            // Ce qu'il reste de l'attaqué
            $TargetSet = $walka["wrog"];
            // Le resultat de la bataille
            $FleetResult = $walka["wygrana"];
            // Rapport long (rapport de bataille detaillé)
            $dane_do_rw = $walka["dane_do_rw"];
            // Rapport court (cdr + unitées perdues)
            $zlom = $walka["zlom"];

            $FleetArray = "";
            $FleetAmount = 0;
            $FleetStorage = 0;
            foreach ($CurrentSet as $Ship => $Count) {
                $FleetStorage += $pricelist[$Ship]["capacity"] * $Count['count'];
                $FleetArray .= $Ship . "," . $Count['count'] . ";";
                $FleetAmount += $Count['count'];
            }
            // Au cas ou le p'tit rigolo qu'a envoyé la flotte y avait mis des ressources ...
            $FleetStorage -= $FleetRow["fleet_resource_metal"];
            $FleetStorage -= $FleetRow["fleet_resource_crystal"];
            $FleetStorage -= $FleetRow["fleet_resource_deuterium"];

            $TargetPlanetUpd = "";
            if (!is_null($TargetSet)) {
                foreach($TargetSet as $Ship => $Count) {
                    $TargetPlanetUpd .= "`" . $resource[$Ship] . "` = '" . $Count['count'] . "', ";
                }
            }
            // Determination des ressources pillées
            $Mining['metal'] = 0;
            $Mining['crystal'] = 0;
            $Mining['deuter'] = 0;
            if ($FleetResult == "a") {
                if ($FleetStorage > 0) {
                    $metal = $TargetPlanet['metal'] / 2;
                    $crystal = $TargetPlanet['crystal'] / 2;
                    $deuter = $TargetPlanet["deuterium"] / 2;
                    if (($metal) > $FleetStorage / 3) {
                        $Mining['metal'] = $FleetStorage / 3;
                        $FleetStorage = $FleetStorage - $Mining['metal'];
                    } else {
                        $Mining['metal'] = $metal;
                        $FleetStorage = $FleetStorage - $Mining['metal'];
                    }

                    if (($crystal) > $FleetStorage / 2) {
                        $Mining['crystal'] = $FleetStorage / 2;
                        $FleetStorage = $FleetStorage - $Mining['crystal'];
                    } else {
                        $Mining['crystal'] = $crystal;
                        $FleetStorage = $FleetStorage - $Mining['crystal'];
                    }

                    if (($deuter) > $FleetStorage) {
                        $Mining['deuter'] = $FleetStorage;
                        $FleetStorage = $FleetStorage - $Mining['deuter'];
                    } else {
                        $Mining['deuter'] = $deuter;
                        $FleetStorage = $FleetStorage - $Mining['deuter'];
                    }
                }
            }
            $Mining['metal'] = round($Mining['metal']);
            $Mining['crystal'] = round($Mining['crystal']);
            $Mining['deuter'] = round($Mining['deuter']);
            // Mise a jour de l'enregistrement de la planete attaquée
            $QryUpdateTarget = "UPDATE {{table}} SET ";
            $QryUpdateTarget .= $TargetPlanetUpd;
            $QryUpdateTarget .= "`metal` = `metal` - '" . $Mining['metal'] . "', ";
            $QryUpdateTarget .= "`crystal` = `crystal` - '" . $Mining['crystal'] . "', ";
            $QryUpdateTarget .= "`deuterium` = `deuterium` - '" . $Mining['deuter'] . "' ";
            $QryUpdateTarget .= "WHERE ";
            $QryUpdateTarget .= "`galaxy` = '" . $FleetRow['fleet_end_galaxy'] . "' AND ";
            $QryUpdateTarget .= "`system` = '" . $FleetRow['fleet_end_system'] . "' AND ";
            $QryUpdateTarget .= "`planet` = '" . $FleetRow['fleet_end_planet'] . "' AND ";
            $QryUpdateTarget .= "`planet_type` = '" . $FleetRow['fleet_end_type'] . "' ";
            $QryUpdateTarget .= "LIMIT 1;";
            doquery($QryUpdateTarget , 'planets');
            // Mise a jour du champ de ruine devant la planete attaquée
            $QryUpdateGalaxy = "UPDATE {{table}} SET ";
            $QryUpdateGalaxy .= "`metal` = `metal` + '" . $zlom['metal'] . "', ";
            $QryUpdateGalaxy .= "`crystal` = `crystal` + '" . $zlom['crystal'] . "' ";
            $QryUpdateGalaxy .= "WHERE ";
            $QryUpdateGalaxy .= "`galaxy` = '" . $FleetRow['fleet_end_galaxy'] . "' AND ";
            $QryUpdateGalaxy .= "`system` = '" . $FleetRow['fleet_end_system'] . "' AND ";
            $QryUpdateGalaxy .= "`planet` = '" . $FleetRow['fleet_end_planet'] . "' ";
            $QryUpdateGalaxy .= "LIMIT 1;";
            doquery($QryUpdateGalaxy , 'galaxy');
            // Là on va discuter le bout de gras pour voir s'il y a moyen d'avoir une Lune !
            $FleetDebris = $zlom['metal'] + $zlom['crystal'];
            $StrAttackerUnits = sprintf ($lang['sys_attacker_lostunits'], pretty_number ($zlom["atakujacy"]));
            $StrDefenderUnits = sprintf ($lang['sys_defender_lostunits'], pretty_number ($zlom["wrog"]));
            $StrRuins = sprintf ($lang['sys_gcdrunits'], pretty_number ($zlom["metal"]), $lang['Metal'], pretty_number ($zlom['crystal']), $lang['Crystal']);
            $DebrisField = $StrAttackerUnits . "<br />" . $StrDefenderUnits . "<br />" . $StrRuins;

            $AttackDate = date("r", $FleetRow["fleet_start_time"]);
            $title = sprintf ($lang['sys_attack_title'], $AttackDate);
            $raport = "<center><table><tr><td>" . $title . "<br />";
            $zniszczony = false;
            $a_zestrzelona = 0;
            $AttackTechon['A'] = $CurrentTechno["military_tech"] * 10;
            $AttackTechon['B'] = $CurrentTechno["defence_tech"] * 10;
            $AttackTechon['C'] = $CurrentTechno["shield_tech"] * 10;
            $AttackerData = sprintf ($lang['sys_attack_attacker_pos'], $CurrentUser["username"], $FleetRow['fleet_start_galaxy'], $FleetRow['fleet_start_system'], $FleetRow['fleet_start_planet']);
            $AttackerTech = sprintf ($lang['sys_attack_techologies'], $AttackTechon['A'], $AttackTechon['B'], $AttackTechon['C']);

            $DefendTechon['A'] = $TargetTechno["military_tech"] * 10;
            $DefendTechon['B'] = $TargetTechno["defence_tech"] * 10;
            $DefendTechon['C'] = $TargetTechno["shield_tech"] * 10;
            $DefenderData = sprintf ($lang['sys_attack_defender_pos'], $TargetUser["username"], $FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
            $DefenderTech = sprintf ($lang['sys_attack_techologies'], $DefendTechon['A'], $DefendTechon['B'], $DefendTechon['C']);

            foreach ($dane_do_rw as $a => $b) {
                $raport .= "<table class='rapport'><tr><th><br /><center>" . $AttackerData . "<br />" . $AttackerTech . "<table>";
                if ($b["atakujacy"]['count'] > 0) {
                    $raport1 = "<tr><th>" . $lang['sys_ship_type'] . "</th>";
                    $raport2 = "<tr><th>" . $lang['sys_ship_count'] . "</th>";
                    $raport3 = "<tr><th>" . $lang['sys_ship_weapon'] . "</th>";
                    $raport4 = "<tr><th>" . $lang['sys_ship_shield'] . "</th>";
                    $raport5 = "<tr><th>" . $lang['sys_ship_armour'] . "</th>";
                    foreach ($b["atakujacy"] as $Ship => $Data) {
                        if (is_numeric($Ship)) {
                            if ($Data['count'] > 0) {
                                $raport1 .= "<th>" . $lang["tech_rc"][$Ship] . "</th>";
                                $raport2 .= "<th>" . pretty_number ($Data['count']) . "</th>";
                                $raport3 .= "<th>" . pretty_number (round($Data["atak"] / $Data['count'])) . "</th>";
                                $raport4 .= "<th>" . pretty_number (round($Data["tarcza"] / $Data['count'])) . "</th>";
                                $raport5 .= "<th>" . pretty_number (round($Data["obrona"] / $Data['count'])) . "</th>";
                            }
                        }
                    }
                    $raport1 .= "</tr>";
                    $raport2 .= "</tr>";
                    $raport3 .= "</tr>";
                    $raport4 .= "</tr>";
                    $raport5 .= "</tr>";
                    $raport .= $raport1 . $raport2 . $raport3 . $raport4 . $raport5;
                } else {
                    if ($a == 2) {
                        $a_zestrzelona = 1;
                    }
                    $zniszczony = true;
                    $raport .= "<br />" . $lang['sys_destroyed'];
                }

                $raport .= "</table></center></th></tr></table>";
                $raport .= "<table class='rapport'><tr><th><br /><center>" . $DefenderData . "<br />" . $DefenderTech . "<table>";
                if ($b["wrog"]['count'] > 0) {
                    $raport1 = "<tr><th>" . $lang['sys_ship_type'] . "</th>";
                    $raport2 = "<tr><th>" . $lang['sys_ship_count'] . "</th>";
                    $raport3 = "<tr><th>" . $lang['sys_ship_weapon'] . "</th>";
                    $raport4 = "<tr><th>" . $lang['sys_ship_shield'] . "</th>";
                    $raport5 = "<tr><th>" . $lang['sys_ship_armour'] . "</th>";
                    foreach ($b["wrog"] as $Ship => $Data) {
                        if (is_numeric($Ship)) {
                            if ($Data['count'] > 0) {
                                $raport1 .= "<th>" . $lang["tech_rc"][$Ship] . "</th>";
                                $raport2 .= "<th>" . pretty_number ($Data['count']) . "</th>";
                                $raport3 .= "<th>" . pretty_number (round($Data["atak"] / $Data['count'])) . "</th>";
                                $raport4 .= "<th>" . pretty_number (round($Data["tarcza"] / $Data['count'])) . "</th>";
                                $raport5 .= "<th>" . pretty_number (round($Data["obrona"] / $Data['count'])) . "</th>";
                            }
                        }
                    }
                    $raport1 .= "</tr>";
                    $raport2 .= "</tr>";
                    $raport3 .= "</tr>";
                    $raport4 .= "</tr>";
                    $raport5 .= "</tr>";
                    $raport .= $raport1 . $raport2 . $raport3 . $raport4 . $raport5;
                } else {
                    $zniszczony = true;
                    $raport .= "<br />" . $lang['sys_destroyed'];
                }
                $raport .= "</table></center></th></tr></table>";

                if (($zniszczony == false) and !($a == 8)) {
                    $AttackWaveStat = sprintf ($lang['sys_attack_attack_wave'], pretty_number (floor($b["atakujacy"]["atak"])), pretty_number (floor($b["wrog"]["tarcza"])));
                    $DefendWavaStat = sprintf ($lang['sys_attack_defend_wave'], pretty_number (floor($b["wrog"]["atak"])), pretty_number (floor($b["atakujacy"]["tarcza"])));
                    $raport .= "<br /><center>" . $AttackWaveStat . "<br />" . $DefendWavaStat . "</center>";
                }
            }
            switch ($FleetResult) {
                case "a":#gagner
                    $Pillage = sprintf ($lang['sys_stealed_ressources'], pretty_number ($Mining['metal']), $lang['metal'], pretty_number ($Mining['crystal']), $lang['crystal'], pretty_number ($Mining['deuter']), $lang['Deuterium']);
                    $raport .= $lang['sys_attacker_won'] . "<br />" . $Pillage . "<br />";
                    $raport .= $DebrisField . "<br />";
                    $raport .= $ChanceMoon . "<br />";
                    $raport .= $GottenMoon . "<br />";
                    break;
                case "r":#match nul
                    $raport .= $lang['sys_both_won'] . "<br />";
                    $raport .= $DebrisField . "<br />";
                    $raport .= $ChanceMoon . "<br />";
                    $raport .= $GottenMoon . "<br />";
                    break;
                case "w":#perdu
                    $raport .= $lang['sys_defender_won'] . "<br />";
                    $raport .= $DebrisField . "<br />";
                    $raport .= $ChanceMoon . "<br />";
                    $raport .= $GottenMoon . "<br />";
                    doquery("DELETE FROM {{table}} WHERE `fleet_id` = '" . $FleetRow["fleet_id"] . "';", 'fleets');
                    break;
                default:
                    break;
            }
            $SimMessage = sprintf ($lang['sys_rapport_build_time'], $totaltime);
            $raport .= $SimMessage . "</table>";

            $dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];
            $rid = md5($raport);
            $QryInsertRapport = "INSERT INTO {{table}} SET ";
            $QryInsertRapport .= "`time` = UNIX_TIMESTAMP(), ";
            $QryInsertRapport .= "`id_owner1` = '" . $FleetRow['fleet_owner'] . "', ";
            $QryInsertRapport .= "`id_owner2` = '" . $TargetUserID . "', ";
            $QryInsertRapport .= "`rid` = '" . $rid . "', ";
            $QryInsertRapport .= "`a_zestrzelona` = '" . $a_zestrzelona . "', ";
            $QryInsertRapport .= "`raport` = '" . addslashes ($raport) . "';";
            doquery($QryInsertRapport , 'rw');
            // Colorisation du résumé de rapport pour l'attaquant
            $raport = "<a href # OnClick=\"f( '". INDEX_BASE ."combat&raport=" . $rid . "', '');\" >";
            $raport .= "<center>";
            if ($FleetResult == "a") {
                $raport .= "<font color=\"green\">";
            } elseif ($FleetResult == "r") {
                $raport .= "<font color=\"orange\">";
            } elseif ($FleetResult == "w") {
                $raport .= "<font color=\"red\">";
            }
            $raport .= $lang['sys_mess_attack_report'] . " [" . $FleetRow['fleet_end_galaxy'] . ":" . $FleetRow['fleet_end_system'] . ":" . $FleetRow['fleet_end_planet'] . "] </font></a><br /><br />";
            $raport .= "<font color=\"red\">" . $lang['sys_perte_attaquant'] . ": " . pretty_number ($zlom["atakujacy"]) . "</font>";
            $raport .= "<font color=\"green\">   " . $lang['sys_perte_defenseur'] . ":" . pretty_number ($zlom["wrog"]) . "</font><br />" ;
            $raport .= $lang['sys_gain'] . " " . $lang['Metal'] . ":<font color=\"#adaead\">" . pretty_number ($Mining['metal']) . "</font>   " . $lang['Crystal'] . ":<font color=\"#ef51ef\">" . pretty_number ($Mining['crystal']) . "</font>   " . $lang['Deuterium'] . ":<font color=\"#f77542\">" . pretty_number ($Mining['deuter']) . "</font><br />";
            $raport .= $lang['sys_debris'] . " " . $lang['Metal'] . ":<font color=\"#adaead\">" . pretty_number ($zlom['metal']) . "</font>   " . $lang['Crystal'] . ":<font color=\"#ef51ef\">" . pretty_number ($zlom['crystal']) . "</font><br /></center>";

            $Mining['metal'] = $Mining['metal'] + $FleetRow["fleet_resource_metal"];
            $Mining['crystal'] = $Mining['crystal'] + $FleetRow["fleet_resource_crystal"];
            $Mining['deuter'] = $Mining['deuter'] + $FleetRow["fleet_resource_deuterium"];

            $QryUpdateFleet = "UPDATE {{table}} SET ";
            $QryUpdateFleet .= "`fleet_amount` = '" . $FleetAmount . "', ";
            $QryUpdateFleet .= "`fleet_array` = '" . $FleetArray . "', ";
            $QryUpdateFleet .= "`fleet_mess` = '1', ";
            $QryUpdateFleet .= "`fleet_resource_metal` = '" . $Mining['metal'] . "', ";
            $QryUpdateFleet .= "`fleet_resource_crystal` = '" . $Mining['crystal'] . "', ";
            $QryUpdateFleet .= "`fleet_resource_deuterium` = '" . $Mining['deuter'] . "' ";
            $QryUpdateFleet .= "WHERE fleet_id = '" . $FleetRow['fleet_id'] . "' ";
            $QryUpdateFleet .= "LIMIT 1 ;";
            doquery($QryUpdateFleet , 'fleets');

            SendSimpleMessage ($CurrentUserID, '0', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_mess_attack_report'], $raport);
            // Ajout du petit point raideur
            $AddPoint = $CurrentUser['xpraid'] + 1;

            $QryUpdateOfficier = "UPDATE {{table}} SET ";
            $QryUpdateOfficier .= "`xpraid` = '" . $AddPoint . "' ";
            $QryUpdateOfficier .= "WHERE id = '" . $CurrentUserID . "' ";
            $QryUpdateOfficier .= "LIMIT 1 ;";
            doquery($QryUpdateOfficier, 'users');
            // Ajout d'un point au compteur de raids
            $RaidsTotal = $CurrentUser['raids'] + 1;
            if ($FleetResult == "a") //gagner
			{
                $RaidsWin = $CurrentUser['raidswin'] + 1;
                $QryUpdateRaidsCompteur = "UPDATE {{table}} SET ";
                $QryUpdateRaidsCompteur .= "`raidswin` ='" . $RaidsWin . "', ";
                $QryUpdateRaidsCompteur .= "`raids` ='" . $RaidsTotal . "' ";
                $QryUpdateRaidsCompteur .= "WHERE id = '" . $CurrentUserID . "' ";
                $QryUpdateRaidsCompteur .= "LIMIT 1 ;";
                doquery($QryUpdateRaidsCompteur, 'users');
            } 
			elseif ($FleetResult == "r") //match nul
			{
                $RaidsLoose = $CurrentUser['raidsloose'] + 1;
                $QryUpdateRaidsCompteur = "UPDATE {{table}} SET ";
                $QryUpdateRaidsCompteur .= "`raidsloose` ='" . $RaidsLoose . "', ";
                $QryUpdateRaidsCompteur .= "`raids` ='" . $RaidsTotal . "' ";
                $QryUpdateRaidsCompteur .= "WHERE id = '" . $CurrentUserID . "' ";
                $QryUpdateRaidsCompteur .= "LIMIT 1 ;";
                doquery($QryUpdateRaidsCompteur, 'users');
            }
			elseif ($FleetResult == "w") //perdu
			{
                $RaidsLoose = $CurrentUser['raidsloose'] + 1;
                $QryUpdateRaidsCompteur = "UPDATE {{table}} SET ";
                $QryUpdateRaidsCompteur .= "`raidsloose` ='" . $RaidsLoose . "', ";
                $QryUpdateRaidsCompteur .= "`raids` ='" . $RaidsTotal . "' ";
                $QryUpdateRaidsCompteur .= "WHERE id = '" . $CurrentUserID . "' ";
                $QryUpdateRaidsCompteur .= "LIMIT 1 ;";
                doquery($QryUpdateRaidsCompteur, 'users');
            }
            // Colorisation du résumé de rapport pour le defenseur
            $raport2 = "<a href # OnClick=\"f( '". INDEX_BASE ."combat&raport=" . $rid . "', '');\" >";
            $raport2 .= "<center>";
            if ($FleetResult == "a") {
                $raport2 .= "<font color=\"red\">";
            } elseif ($FleetResult == "r") {
                $raport2 .= "<font color=\"orange\">";
            } elseif ($FleetResult == "w") {
                $raport2 .= "<font color=\"green\">";
            }
            $raport2 .= $lang['sys_mess_attack_report'] . " [" . $FleetRow['fleet_end_galaxy'] . ":" . $FleetRow['fleet_end_system'] . ":" . $FleetRow['fleet_end_planet'] . "] </font></a><br /><br />";

            SendSimpleMessage ($TargetUserID, '0', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_mess_attack_report'], $raport2);
        }
        // Retour de flotte (s'il en reste)
        $fquery = "";
        if ($FleetRow['fleet_end_time'] <= time()) {
            if (!is_null($CurrentSet)) {
                foreach($CurrentSet as $Ship => $Count) {
                    $fquery .= "`" . $resource[$Ship] . "` = `" . $resource[$Ship] . "` + '" . $Count['count'] . "', ";
                }
            } else {
                $fleet = explode(";", $FleetRow['fleet_array']);
                foreach($fleet as $a => $b) {
                    if ($b != '') {
                        $a = explode(",", $b);
                        $fquery .= "{$resource[$a[0]]}={$resource[$a[0]]} + {$a[1]}, \n";
                    }
                }
            }

            doquery ("DELETE FROM {{table}} WHERE `fleet_id` = " . $FleetRow["fleet_id"], 'fleets');
            if (!($FleetResult == "w")) {
                $QryUpdatePlanet = "UPDATE {{table}} SET ";
                $QryUpdatePlanet .= $fquery;
                $QryUpdatePlanet .= "`metal` = `metal` + " . $FleetRow['fleet_resource_metal'] . ", ";
                $QryUpdatePlanet .= "`crystal` = `crystal` + " . $FleetRow['fleet_resource_crystal'] . ", ";
                $QryUpdatePlanet .= "`deuterium` = `deuterium` + " . $FleetRow['fleet_resource_deuterium'] . " ";
                $QryUpdatePlanet .= "WHERE ";
                $QryUpdatePlanet .= "`galaxy` = " . $FleetRow['fleet_start_galaxy'] . " AND ";
                $QryUpdatePlanet .= "`system` = " . $FleetRow['fleet_start_system'] . " AND ";
                $QryUpdatePlanet .= "`planet` = " . $FleetRow['fleet_start_planet'] . " AND ";
                $QryUpdatePlanet .= "`planet_type` = " . $FleetRow['fleet_start_type'] . " LIMIT 1 ;";
                doquery($QryUpdatePlanet, 'planets');
            }
        }
    }
}

?>