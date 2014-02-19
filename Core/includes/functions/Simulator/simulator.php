<?php
/**
 * This file is part of phpSpaceProject
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://phpsp.fr/
 *
 * Copyright (c) 2012-Present, phpSpaceProject Support Team <http://phpsp.fr/board/>
 * All rights reserved.
 *===================================
  _____  _    _ _____   _____ _____  
 |  __ \| |  | |  __ \ / ____|  __ \ 
 | |__) | |__| | |__) | (___ | |__) |
 |  ___/|  __  |  ___/ \___ \|  ___/ 
 | |    | |  | | |     ____) | |     
 |_|    |_|  |_|_|    |_____/|_|                 
 *===================================
 *
 */
 

function simulator($CurrentSet, $TargetSet, $CurrentTechno, $TargetTechno) {
	global $pricelist, $CombatCaps, $game_config;
	$runda       = array();
	$attaquant_n = array();
	$defenseur_n      = array();

	// Calcul des points de Structure de l'attaquant
	if (!is_null($CurrentSet)) {
		$attaquant_structure['metal']   = 0;
		$attaquant_structure['crystal'] = 0;
		for($a = 200; $a < 500; $a++) {
			if ($CurrentSet[$a]['count'] > 0) {
				$attaquant_structure['metal']   = $attaquant_structure['metal']   + $CurrentSet[$a]['count'] * $pricelist[$a]['metal'];
				$attaquant_structure['crystal'] = $attaquant_structure['crystal'] + $CurrentSet[$a]['count'] * $pricelist[$a]['crystal'];
			}
		}
	}

	// Calcul des points de Structure du défenseur
	$defenseur_structure['metal']    = 0;
	$defenseur_structure['crystal'] = 0;
	$defenseur_poczatek = $TargetSet;
	if (!is_null($TargetSet)) {
		for($a = 200; $a < 500; $a++) {
			if ($TargetSet[$a]['count'] > 0) {
				if ($a < 300) {
					$defenseur_structure['metal']   = $defenseur_structure['metal']   + $TargetSet[$a]['count'] * $pricelist[$a]['metal'];
					$defenseur_structure['crystal'] = $defenseur_structure['crystal'] + $TargetSet[$a]['count'] * $pricelist[$a]['crystal'];
				} else {
					$defenseur_structure_coque['metal']   = $defenseur_structure_coque['metal']   + $TargetSet[$a]['count'] * $pricelist[$a]['metal'];
					$defenseur_structure_coque['crystal'] = $defenseur_structure_coque['crystal'] + $TargetSet[$a]['count'] * $pricelist[$a]['crystal'];
				}
			}
		}
	}

	//comme ogame un combat dure 6 tours
	for ($i = 0; $i < 6; $i++) {
		$attaquant_dommage   = 0;
		$defenseur_dommage        = 0;
		$attaquant_coque = 0;
		$defenseur_coque      = 0;
		$attaquant_nombre  = 0;
		$defenseur_nombre       = 0;
		$defenseur_bouclier      = 0;
		$attaquant_bouclier = 0;

		if (!is_null($CurrentSet)) {
			for($a = 200; $a < 500; $a++) {
				if ($CurrentSet[$a]['count'] > 0) {
					$rand = rand(100, 100) / 100;
					$CurrentSet[$a]["obrona"] = $CurrentSet[$a]['count'] * ($pricelist[$a]['metal'] + $pricelist[$a]['crystal']) / 10 * (1 + (0.1 * ($CurrentTechno["defence_tech"])));
					$CurrentSet[$a]["tarcza"] = $CurrentSet[$a]['count'] * $CombatCaps[$a]['shield'] * (1 + (0.1 * $CurrentTechno["shield_tech"])) *$rand;
					$dommage_vaisseau = $CombatCaps[$a]['attack'];
					$technologie = (1 + (0.1 * $CurrentTechno["military_tech"]));
					$nombre = $CurrentSet[$a]['count'];
					$CurrentSet[$a]["atak"] = $nombre * $dommage_vaisseau * $technologie * $rand;
					$attaquant_dommage = $attaquant_dommage + $CurrentSet[$a]["atak"];
					$attaquant_coque = $attaquant_coque + $CurrentSet[$a]["obrona"];
					$attaquant_nombre = $attaquant_nombre + $CurrentSet[$a]['count'];
				}
			}
		} else {
			$attaquant_nombre = 0;
			break;
		}

		if (!is_null($TargetSet)) {
			for($a = 200; $a < 500; $a++) {
				if ($TargetSet[$a]['count'] > 0) {
					$rand = rand(100, 100) / 100;
					$TargetSet[$a]["obrona"] = $TargetSet[$a]['count'] * ($pricelist[$a]['metal'] + $pricelist[$a]['crystal']) / 10 * (1 + (0.1 * ($TargetTechno["defence_tech"])));
					$TargetSet[$a]["tarcza"] = $TargetSet[$a]['count'] * $CombatCaps[$a]['shield'] * (1 + (0.1 * $TargetTechno["shield_tech"])) * $rand;
					$dommage_vaisseau = $CombatCaps[$a]['attack'];
					$technologie = (1 + (0.1 * $TargetTechno["military_tech"]));
					$nombre = $TargetSet[$a]['count'];
					$TargetSet[$a]["atak"] = $nombre * $dommage_vaisseau * $technologie * $rand;
					$defenseur_dommage = $defenseur_dommage + $TargetSet[$a]["atak"];
					$defenseur_coque = $defenseur_coque + $TargetSet[$a]["obrona"];
					$defenseur_nombre = $defenseur_nombre + $TargetSet[$a]['count'];	
				}
			}
		} else {
			$defenseur_nombre = 0;
			$runda[$i]["atakujacy"] = $CurrentSet;
			$runda[$i]["wrog"] = $TargetSet;
			$runda[$i]["atakujacy"]["atak"] = $attaquant_dommage;
			$runda[$i]["wrog"]["atak"] = $defenseur_dommage;
			$runda[$i]["atakujacy"]['count'] = $attaquant_nombre;
			$runda[$i]["wrog"]['count'] = $defenseur_nombre;
			break;
		}

		$runda[$i]["atakujacy"] = $CurrentSet;
		$runda[$i]["wrog"] = $TargetSet;
		$runda[$i]["atakujacy"]["atak"] = $attaquant_dommage;
		$runda[$i]["wrog"]["atak"] = $defenseur_dommage;
		$runda[$i]["atakujacy"]['count'] = $attaquant_nombre;
		$runda[$i]["wrog"]['count'] = $defenseur_nombre;

		if (($attaquant_nombre == 0) or ($defenseur_nombre == 0)) {
			break;
		}

		//on commence par le combat attaquant vs defenseur
		for($a = 200; $a < 500; $a++) 
		{
			if ($TargetSet[$a]['count'] > 0) #le type de vaisseaux
			{
				if ($defenseur_nombre > 0) #si il y a toujours des vaisseaux
				{	
					$attaquant_puissance = $TargetSet[$a]['count'] * $attaquant_dommage / $defenseur_nombre; #la puissance de l'attaquant
					
					if ($TargetSet[$a]["tarcza"] <= $attaquant_puissance) #si le bouclier du defenseur est plus petit ou égale a la puissance de l'attaquant 
					{
						$defenseur_bouclier = $defenseur_bouclier + $TargetSet[$a]["tarcza"];
						$attaquant_puissance -= $defenseur_bouclier;
						
						if($TargetSet[$a]["obrona"] > $attaquant_puissance) #si la coque du defenseur est plus grand que la puissance de l'attaquant
						{
							$coque = $TargetSet[$a]["obrona"] - $attaquant_puissance;
							$calc = $coque/$TargetSet[$a]["obrona"];
							if($calc>=1)
							{
								$calc = 1;
							}
							
							$RShipDef = round(($calc) * $TargetSet[$a]["count"]);
							$defenseur_n[$a]['count'] = $RShipDef;

							if ($defenseur_n[$a]['count'] <= 0)
							{
								$defenseur_n[$a]['count'] = 0;
							}
						}
						else
						{
							$defenseur_n[$a]['count'] = 0;
							$defenseur_bouclier = 0;
						}
					}
					else #si le bouclier du defenseur est plus grand a la puissance de l'attaquant  
					{
						$defenseur_n[$a]['count'] = $TargetSet[$a]['count'];
						$defenseur_bouclier = $defenseur_bouclier + $attaquant_puissance;
					}
				} 
				else #si il n'y a plus de vaisseaux 
				{
					$defenseur_n[$a]['count'] = 0;
					$defenseur_bouclier = 0;
				}
			}

			if ($CurrentSet[$a]['count'] > 0) #le type de vaisseaux
			{	
				if($attaquant_nombre > 0) #si il y a toujours des vaisseaux
				{
					$defenseur_puissance = $CurrentSet[$a]['count'] * $defenseur_dommage / $attaquant_nombre; #la puissance de l'attaquant
					
					if ($CurrentSet[$a]["tarcza"] <= $defenseur_puissance) #si le bouclier de l'attaquant est plus petit ou égale a la puissance du def
					{
						$attaquant_bouclier = ($attaquant_bouclier + $CurrentSet[$a]["tarcza"]);
						$defenseur_puissance -= $attaquant_bouclier;
						
						if($CurrentSet[$a]["obrona"] > $defenseur_puissance)#si la coque de l'attaquant est plus grand que la puissance de l'attaquant
						{
							// on soustrait la valeur de la coque attaquante a la puissance du defenseur
							$coque = ($CurrentSet[$a]["obrona"]) - $defenseur_puissance;
							
							$calc = $coque/$CurrentSet[$a]["obrona"];
							if($calc >= 1)
							{
								$calc = 1;
							}
							$RShipAtt = round(($calc) * $CurrentSet[$a]["count"]);
							$attaquant_n[$a]['count'] = $RShipAtt;
							
							if ($attaquant_n[$a]['count'] <= 0)
							{
								$attaquant_n[$a]['count'] = 0;
							}
						}
						else
						{
							$attaquant_n[$a]['count'] = 0;
							$attaquant_bouclier = 0;
						}
					}
					else
					{
						$attaquant_n[$a]['count'] = $CurrentSet[$a]['count'];
						$attaquant_bouclier = $attaquant_bouclier + $CurrentSet[$a]["tarcza"];
					}
				}
				else
				{
					$attaquant_n[$a]['count'] = 0;
					$attaquant_bouclier = 0;
				}
			}
			
/********************************************************/
/*			CALCULE APROXIMATIF DU RAPID FIRE			*/
/********************************************************/
			if ($CurrentSet[$a]['count'] > 0)#rapidfire de l'attaquant sur le défenseur
			{
				foreach($CombatCaps[$a]['sd'] as $c => $d) # combat le rapidfire est plus petit ou egale 1 il n'y en a pas 
				{
					if($TargetSet[$c]!=Null)
					{
						if($d > 1) {
							$rapidfire = true;
						}
					}
				}
				
					while($rapidfire)
					{
						$randEntier = rand(0,100);
						$randDecimal = rand(0,99);
						$pourcentage = $randEntier + ($randDecimal / 100);
						foreach($CombatCaps[$a]['sd'] as $c => $d)
						{
							if($TargetSet[$c]!=Null)
							{
								if($pourcentage < 100*(1 - ( 1 / $CombatCaps[$a]['sd'][$c])))
								{
									if ($defenseur_nombre > 0)
									{
										$attaquant_puissance = $TargetSet[$c]['count'] * $attaquant_dommage / $defenseur_nombre;
										$newcoque = $TargetSet[$c]["obrona"] / $defenseur_nombre * $defenseur_n[$c]['count'];
										if($newcoque > $attaquant_puissance)
										{
											$coque = $newcoque - $attaquant_puissance;
											$calc = $coque/$newcoque;		
											if($calc >= 1)
											{
												$calc = 1;
											}

											$RFRShipDef = round(($calc) * $TargetSet[$c]["count"]);
											$enleDEF = ($TargetSet[$c]["count"] - $RFRShipDef);
											$defenseur_n[$c]['count'] -= $RFRShipDef - $enleDEF;
											if ($defenseur_n[$c]['count'] <= 0) {
													$defenseur_n[$c]['count'] = 0;
											}
										}
									} else {
										$defenseur_n[$c]['count'] = $TargetSet[$c]['count'];
										$defenseur_bouclier = $defenseur_bouclier + $attaquant_puissance;
									}
								} else{
									$rapidfire = false;
								}
							}
						}
					}
			}

			if ($TargetSet[$a]['count'] > 0)
			{
				foreach($CombatCaps[$a]['sd'] as $c => $d) # combat le rapidfire est plus petit ou egale 1 il n'y en a pas 
				{
					if($CurrentSet[$c]!=Null)
					{
						if($d > 1) {
							$rapidfire = true;
						}
					}
				}

					while($rapidfire)
					{
						$randEntier = rand(0,100);
						$randDecimal = rand(0,99);
						$pourcentage = $randEntier + ($randDecimal / 100);
						foreach($CombatCaps[$a]['sd'] as $c => $d)
						{
							if($CurrentSet[$c]!=Null)
							{
								if($pourcentage < 100*(1 - ( 1 / $CombatCaps[$a]['sd'][$c])))
								{
									if ($attaquant_nombre > 0)
									{
										$defenseur_puissance = $CurrentSet[$c]['count'] * $defenseur_dommage / $attaquant_nombre;
										$newcoque = $CurrentSet[$c]["obrona"] / $attaquant_nombre * $attaquant_n[$c]['count'];
										if($newcoque > $defenseur_puissance)
										{
											$coque = $newcoque - $defenseur_puissance;
											$calc = $coque/$newcoque;
													
											if($calc >= 1)
											{
												$calc = 1;
											}
													
											$RFRShipAtt = round(($calc) * $CurrentSet[$c]["count"]);
											$enleATT = ($CurrentSet[$c]["count"] - $RFRShipAtt);
											$attaquant_n[$c]['count'] -= $RFRShipAtt - $enleATT;
											if ($attaquant_n[$c]['count'] <= 0) {
													$attaquant_n[$c]['count'] = 0;
											}
										}
									} else {
										$attaquant_n[$c]['count'] = $CurrentSet[$c]['count'];
										$attaquant_bouclier = $attaquant_bouclier + $defenseur_puissance;
									}
								} else{
									$rapidfire = false;
								}
							}
						}
					}
			}
		}		

		
		$runda[$i]["atakujacy"]["tarcza"] = $attaquant_bouclier;
		$runda[$i]["wrog"]["tarcza"] = $defenseur_bouclier;
		$TargetSet = $defenseur_n;
		$CurrentSet = $attaquant_n;
	}

	
	if (($attaquant_nombre == 0) or ($defenseur_nombre == 0)) {
		if (($attaquant_nombre == 0) and ($defenseur_nombre == 0)) {
			$wygrana = "r";
		} else {
			if ($attaquant_nombre == 0) {
				$wygrana = "w";
			} else {
				$wygrana = "a";
			}
		}
	} else {
		$i = sizeof($runda);
		$runda[$i]["atakujacy"] = $CurrentSet;
		$runda[$i]["wrog"] = $TargetSet;
		$runda[$i]["atakujacy"]["atak"] = $attaquant_dommage;
		$runda[$i]["wrog"]["atak"] = $defenseur_dommage;
		$runda[$i]["atakujacy"]['count'] = $attaquant_nombre;
		$runda[$i]["wrog"]['count'] = $defenseur_nombre;
		$wygrana = "r";
	}
	$attaquant_perte['metal'] = 0;
	$attaquant_perte['crystal'] = 0;
	if (!is_null($CurrentSet)) {
		for($a = 200; $a < 500; $a++) {
			if ($CurrentSet[$a]['count'] > 0) {
				$attaquant_perte['metal'] = $attaquant_perte['metal'] + $CurrentSet[$a]['count'] * $pricelist[$a]['metal'];
				$attaquant_perte['crystal'] = $attaquant_perte['crystal'] + $CurrentSet[$a]['count'] * $pricelist[$a]['crystal'];
			}
		}
	}
	$defenseur_perte['metal'] = 0;
	$defenseur_perte['crystal'] = 0;
	if (!is_null($TargetSet)) {
		for($a = 200; $a < 500; $a++) {
				if ($a < 300) {
					$defenseur_perte['metal'] = $defenseur_perte['metal'] + $TargetSet[$a]['count'] * $pricelist[$a]['metal'];
					$defenseur_perte['crystal'] = $defenseur_perte['crystal'] + $TargetSet[$a]['count'] * $pricelist[$a]['crystal'];
				} else {
					$defenseur_perte_coque['metal'] = $defenseur_perte_coque['metal'] + $TargetSet[$a]['count'] * $pricelist[$a]['metal'];
					$defenseur_perte_coque['crystal'] = $defenseur_perte_coque['crystal'] + $TargetSet[$a]['count'] * $pricelist[$a]['crystal'];
				}
			}
	}
	$nombre_defenseur = 0;
	$straty_coque_defenseur = 0;
	if (!is_null($TargetSet)) {
		for($a = 200; $a < 500; $a++) {
				if ($a > 300) {
					$straty_coque_defenseur = $straty_coque_defenseur + (($defenseur_poczatek[$a]['count'] - $TargetSet[$a]['count']) * ($pricelist[$a]['metal'] + $pricelist[$a]['crystal']));
					$TargetSet[$a]['count'] = $TargetSet[$a]['count'] + (($defenseur_poczatek[$a]['count'] - $TargetSet[$a]['count']) * 0.8);
					$nombre_defenseur = $nombre_defenseur + $TargetSet[$a]['count'];
				}
		}
	}
	if (($nombre_defenseur > 0) and ($attaquant_nombre == 0)) {
		$wygrana = "w";
	}

	$zlom['metal']    = ((($attaquant_structure['metal']   - $attaquant_perte['metal'])   + ($defenseur_structure['metal']   - $defenseur_perte['metal']))   * ($game_config['Fleet_Cdr'] / 100));
	$zlom['crystal']  = ((($attaquant_structure['crystal'] - $attaquant_perte['crystal']) + ($defenseur_structure['crystal'] - $defenseur_perte['crystal'])) * ($game_config['Fleet_Cdr'] / 100));

	$zlom['metal']   += ((($attaquant_structure['metal']   - $attaquant_perte['metal'])   + ($defenseur_structure['metal']   - $defenseur_perte['metal']))   * ($game_config['Defs_Cdr'] / 100));
	$zlom['crystal'] += ((($attaquant_structure['crystal'] - $attaquant_perte['crystal']) + ($defenseur_structure['crystal'] - $defenseur_perte['crystal'])) * ($game_config['Defs_Cdr'] / 100));

	$zlom["atakujacy"] = (($attaquant_structure['metal'] - $attaquant_perte['metal']) + ($attaquant_structure['crystal'] - $attaquant_perte['crystal']));
	$zlom["wrog"]      = (($defenseur_structure['metal'] - $defenseur_perte['metal']) + ($defenseur_structure['crystal'] - $defenseur_perte['crystal']) + $straty_coque_defenseur);
	return array("atakujacy" => $CurrentSet, "wrog" => $TargetSet, "wygrana" => $wygrana, "dane_do_rw" => $runda, "zlom" => $zlom);
}

?>
