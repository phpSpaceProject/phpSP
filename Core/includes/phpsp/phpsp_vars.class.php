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
 
class Phpsp_Vars
{
	/* rappel :
	 * Une constante est une sorte d'attribut comme define appartenant à la classe dont la valeur ne change jamais
	 */
	const TYPE_BUILDING   = 'build';
    const TYPE_RESEARCH   = 'tech';
    const TYPE_SHIP       = 'fleet';
    const TYPE_DEFENSE    = 'defense';
    const TYPE_OFFICER    = 'officier';
    const TYPE_PRODUCTION = 'prod';

    const RESOURCE_METAL      = 'metal';
    const RESOURCE_CRISTAL    = 'crystal';
    const RESOURCE_DEUTERIUM  = 'deuterium';
    const RESOURCE_ENERGY     = 'energy';
	
    const RESOURCE_MULTIPLIER = 'factor';
    const RESOURCE_FORMULA    = 'formule';

    const SHIPS_CONSUMPTION_PRIMARY   = 'consumption';
    const SHIPS_CELERITY_PRIMARY      = 'speed';
    const SHIPS_CONSUMPTION_SECONDARY = 'consumption2';
    const SHIPS_CELERITY_SECONDARY    = 'speed2';
    const SHIPS_CAPACITY              = 'capacity';
	
    const ID_BUILDING_METAL_MINE            = 1;
    const ID_BUILDING_CRISTAL_MINE          = 2;
    const ID_BUILDING_DEUTERIUM_SYNTHETISER = 3;
    const ID_BUILDING_SOLAR_PLANT			= 4;
    const ID_BUILDING_FUSION_REACTOR        = 5;
    const ID_BUILDING_ROBOTIC_FACTORY       = 6;
    const ID_BUILDING_NANITE_FACTORY        = 7;
    const ID_BUILDING_SHIPYARD              = 8;
    const ID_BUILDING_METAL_STORAGE         = 9;
    const ID_BUILDING_CRISTAL_STORAGE       = 10;
    const ID_BUILDING_DEUTERIUM_TANK        = 11;
    const ID_BUILDING_RESEARCH_LAB          = 12;
    const ID_BUILDING_TERRAFORMER           = 13;
	const ID_BUILDING_DEFENSE				= 14;
	
    const ID_RESEARCH_ESPIONAGE_TECHNOLOGY           = 1;
    const ID_RESEARCH_COMPUTER_TECHNOLOGY            = 2;
    const ID_RESEARCH_WEAPON_TECHNOLOGY              = 3;
    const ID_RESEARCH_SHIELDING_TECHNOLOGY           = 4;
    const ID_RESEARCH_ARMOUR_TECHNOLOGY              = 5;
    const ID_RESEARCH_ENERGY_TECHNOLOGY              = 6;
    const ID_RESEARCH_HYPERSPACE_TECHNOLOGY          = 7;
    const ID_RESEARCH_COMBUSTION_DRIVE               = 8;
    const ID_RESEARCH_IMPULSE_DRIVE                  = 9;
    const ID_RESEARCH_HYPERSPACE_DRIVE               = 10;
    const ID_RESEARCH_LASER_TECHNOLOGY               = 11;
    const ID_RESEARCH_ION_TECHNOLOGY                 = 12;
    const ID_RESEARCH_PLASMA_TECHNOLOGY              = 13;
    const ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK = 14;
    const ID_RESEARCH_STORAGE_TECHNOLOGY			 = 15;
    const ID_RESEARCH_EXPANSSION					 = 16;
	const ID_RESEARCH_TELEPORTATION					 = 17;
    const ID_RESEARCH_GRAVITON_TECHNOLOGY            = 18;
	
    const ID_SHIP_LIGHT_TRANSPORT = 1;
    const ID_SHIP_LARGE_TRANSPORT = 2;
    const ID_SHIP_LIGHT_FIGHTER   = 3;
    const ID_SHIP_HEAVY_FIGHTER   = 4;
    const ID_SHIP_CRUISER         = 5;
    const ID_SHIP_BATTLESHIP      = 6;
    const ID_SHIP_COLONY_SHIP     = 7;
    const ID_SHIP_RECYCLER        = 8;
    const ID_SHIP_SPY_DRONE       = 9;
    const ID_SHIP_BOMBER          = 10;
    const ID_SHIP_SOLAR_SATELLITE = 11;
    const ID_SHIP_DESTRUCTOR      = 12;
    const ID_SHIP_DEATH_STAR      = 13;
    const ID_SHIP_BATTLECRUISER   = 14;

    const ID_DEFENSE_ROCKET_LAUNCHER   = 1;
    const ID_DEFENSE_LIGHT_LASER       = 2;
    const ID_DEFENSE_HEAVY_LASER       = 3;
    const ID_DEFENSE_ION_CANNON        = 4;
    const ID_DEFENSE_GAUSS_CANNON      = 5;
    const ID_DEFENSE_PLASMA_TURRET     = 6;
    const ID_DEFENSE_SMALL_SHIELD_DOME = 7;
    const ID_DEFENSE_LARGE_SHIELD_DOME = 8;
}

$Phpsp_vars = new Phpsp_Vars();