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
    includeLang('options');

    $lang['PHP_SELF'] = 'options.' . PHPEXT;

    $mode = $_GET['mode'];
	$parse = $lang;
	$parse['link'] = INDEX_BASE;	

    if ($_POST && $mode == "exit") { // Array ( [db_character]
       if (isset($_POST["exit_modus"]) && $_POST["exit_modus"] == 'on' and $user['urlaubs_until'] <= time()){
          $urlaubs_modus = "0";
          doquery("UPDATE {{table}} SET
             `urlaubs_modus` = '0',
             `urlaubs_until` = '0'
             WHERE `id` = '".$user['id']."' LIMIT 1", "users");

//Remise des mines au retour du mod vacance

          $query = doquery("SELECT * FROM {{table}} WHERE id_owner = '{$user['id']}'", 'planets');
          while($id = mysql_fetch_array($query)){
             doquery("UPDATE {{table}} SET
                   energy_used = '10',
                   energy_max = '10',
                   metal_mine_porcent = '10',
                   crystal_mine_porcent = '10',
                   deuterium_sintetizer_porcent = '10',
                   solar_plant_porcent = '10',
                   fusion_plant_porcent = '10',
                   solar_satelit_porcent = '10'
                 WHERE id = '{$id['id']}' AND `planet_type` = 1 ", 'planets');}

          // $dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];
          message($lang['succeful_save'], $lang['Options'],"options.php",1);
       }else{
       $urlaubs_modus = "1";
       // $dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];
       message($lang['You_cant_exit_vmode'], $lan['Error'] ,"options.php",1);
       }
    }
    if ($_POST && $mode == "change") { // Array ( [db_character]
       $iduser = $user["id"];
       $avatar = mysql_real_escape_string($_POST["avatar"]);

	   // if ($_POST["dpath"] != "")
			// $dpath =  mysql_real_escape_string($_POST["dpath"]);
		// else
			// $dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];

       // Gestion des options speciales pour les admins
       if ($user['authlevel'] > 0) {
          if ($_POST['adm_pl_prot'] == 'on') {
             doquery ("UPDATE {{table}} SET `id_level` = '".$user['authlevel']."' WHERE `id_owner` = '".$user['id']."';", 'planets');
          } else {
             doquery ("UPDATE {{table}} SET `id_level` = '0' WHERE `id_owner` = '".$user['id']."';", 'planets');
          }
       }

       // Mostrar skin
       if (isset($_POST["design"]) && $_POST["design"] == 'on') {
          $design = "1";
       } else {
          $design = "0";
       }
       // Desactivar comprobaci? de IP
       if (isset($_POST["noipcheck"]) && $_POST["noipcheck"] == 'on') {
          $noipcheck = "1";
       } else {
          $noipcheck = "0";
       }
	   
       // Desactivar l'affichage chat
       if (isset($_POST["add_chat"]) && $_POST["add_chat"] == 'on') {
          $add_chat = "1";
       } else {
          $add_chat = "0";
       }
       // Nombre de usuario
       if (isset($_POST["db_character"]) && $_POST["db_character"] != '') {
          $username = mysql_real_escape_string($_POST['db_character']);
       } else {
          $username = $user['username'];
       }
       // Adresse e-Mail
       if (isset($_POST["db_email"]) && $_POST["db_email"] != '') {
          $db_email = mysql_real_escape_string($_POST['db_email']);
       } else {
          $db_email = $user['email'];
       }
       // Affichage record
       if (isset($_POST["settings_record"]) && $_POST["settings_record"] == 'on') {
          $settings_record = "1";
       } else {
          $settings_record = "0";
       }
       // Cantidad de sondas de espionaje
       if (isset($_POST["spio_anz"]) && is_numeric($_POST["spio_anz"])) {
          $spio_anz = $_POST["spio_anz"];
       } else {
          $spio_anz = "1";
       }
       // Mostrar tooltip durante
       if (isset($_POST["settings_tooltiptime"]) && is_numeric($_POST["settings_tooltiptime"])) {
          $settings_tooltiptime = $_POST["settings_tooltiptime"];
       } else {
          $settings_tooltiptime = "1";
       }
       // Maximo mensajes de flotas
       if (isset($_POST["settings_fleetactions"]) && is_numeric($_POST["settings_fleetactions"])) {
          $settings_fleetactions = $_POST["settings_fleetactions"];
       } else {
          $settings_fleetactions = "1";
       } //
       // Mostrar logos de los aliados
       if (isset($_POST["settings_allylogo"]) && $_POST["settings_allylogo"] == 'on') {
          $settings_allylogo = "1";
       } else {
          $settings_allylogo = "0";
       }
       // Espionaje
       if (isset($_POST["settings_esp"]) && $_POST["settings_esp"] == 'on') {
          $settings_esp = "1";
       } else {
          $settings_esp = "0";
       }
       // Escribir mensaje
       if (isset($_POST["settings_wri"]) && $_POST["settings_wri"] == 'on') {
          $settings_wri = "1";
       } else {
          $settings_wri = "0";
       }
       // A?dir a lista de amigos
       if (isset($_POST["settings_bud"]) && $_POST["settings_bud"] == 'on') {
          $settings_bud = "1";
       } else {
          $settings_bud = "0";
       }
       // Ataque con misiles
       if (isset($_POST["settings_mis"]) && $_POST["settings_mis"] == 'on') {
          $settings_mis = "1";
       } else {
          $settings_mis = "0";
       }
       // Ver reporte
       if (isset($_POST["settings_rep"]) && $_POST["settings_rep"] == 'on') {
          $settings_rep = "1";
       } else {
          $settings_rep = "0";
       }
       // Modo vacaciones
       if (isset($_POST["urlaubs_modus"]) && $_POST["urlaubs_modus"] == 'on') {
       //Selectionne si le joueur a des flottes en vol
       	$fleet  = doquery("SELECT COUNT(fleet_owner) AS `actcnt` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."';", 'fleets', true);
       //Selectionne si le joueur a des batiments en construction
        $build  = doquery("SELECT COUNT(id_owner) AS `building` FROM {{table}} WHERE `id_owner` = '".$user['id']."' and `b_building`!=0;", 'planets', true);
       //Selectionne si le joueur a des techno en cours
        $tech  = doquery("SELECT COUNT(id_owner) AS `tech` FROM {{table}} WHERE `id_owner` = '".$user['id']."' and `b_tech`!=0;", 'planets', true);
		//Selectionne si le joueur a des vaisseau en cours
        $hangar  = doquery("SELECT COUNT(id_owner) AS `vaisseau` FROM {{table}} WHERE `id_owner` = '".$user['id']."' and `b_hangar`!=0;", 'planets', true);
       //Selectionne si le joueur a des defenses en cours
        $defense  = doquery("SELECT COUNT(id_owner) AS `defense` FROM {{table}} WHERE `id_owner` = '".$user['id']."' and `b_defense`!=0;", 'planets', true);
       //Selectionne si le joueur est en train de se faire attaquer
        $attack  = doquery("SELECT COUNT(fleet_target_owner) AS `attack` FROM {{table}} WHERE `fleet_target_owner` = '".$user['id']."';", 'fleets', true);
		
       	if($fleet['actcnt']=='0' && $build['building']=='0' && $tech['tech']=='0' && $attack['attack']=='0' && $hangar['vaisseau']=='0' && $defense['defense']=='0') 
		{
          $urlaubs_modus = "1";
          $time = time() + 172800;
          doquery("UPDATE {{table}} SET
             `urlaubs_modus` = '$urlaubs_modus',
             `urlaubs_until` = '$time'
             WHERE `id` = '$iduser' LIMIT 1", "users");
		} 
		else
		{
			message ( 'Verifiez vos flottes, technologies et batiments','<center><font color=\"red\">Vous avez des actions en cours</font></center>'  );
		}

          $query = doquery("SELECT * FROM {{table}} WHERE id_owner = '{$user['id']}'", 'planets');
          while($id = mysql_fetch_array($query)){
             doquery("UPDATE {{table}} SET
                   metal_perhour = '".$game_config['metal_basic_income']."',
                   crystal_perhour = '".$game_config['metal_basic_income']."',
                   deuterium_perhour = '".$game_config['metal_basic_income']."',
                   energy_used = '0',
                   energy_max = '0',
                   metal_mine_porcent = '0',
                   crystal_mine_porcent = '0',
                   deuterium_sintetizer_porcent = '0',
                   solar_plant_porcent = '0',
                   fusion_plant_porcent = '0',
                   solar_satelit_porcent = '0'
                 WHERE id = '{$id['id']}' AND `planet_type` = 1 ", 'planets');
          }
       } else {
          $urlaubs_modus = "0";
       }

       // Borrar cuenta
       if (isset($_POST["db_deaktjava"]) && $_POST["db_deaktjava"] == 'on') {
          $db_deaktjava = "1";
       } else {
          $db_deaktjava = "0";
       }
       $SetSort  = $_POST['settings_sort'];
       $SetOrder = $_POST['settings_order'];

       doquery("UPDATE {{table}} SET
       `email` = '$db_email',
       `avatar` = '$avatar',
       `dpath` = '$dpath',
       `design` = '$design',
       `noipcheck` = '$noipcheck',
	   `add_chat` = '$add_chat',
       `planet_sort` = '$SetSort',
       `planet_sort_order` = '$SetOrder',
       `spio_anz` = '$spio_anz',
       `settings_tooltiptime` = '$settings_tooltiptime',
       `settings_fleetactions` = '$settings_fleetactions',
       `settings_allylogo` = '$settings_allylogo',
       `settings_esp` = '$settings_esp',
	   `record` = '$settings_record',
       `settings_wri` = '$settings_wri',
       `settings_bud` = '$settings_bud',
       `settings_mis` = '$settings_mis',
       `settings_rep` = '$settings_rep',
       `urlaubs_modus` = '$urlaubs_modus',
       `db_deaktjava` = '$db_deaktjava',
       `kolorminus` = '$kolorminus',
       `kolorplus` = '$kolorplus',
       `kolorpoziom` = '$kolorpoziom'
       WHERE `id` = '$iduser' LIMIT 1", "users");

       if (isset($_POST["db_password"]) && md5($_POST["db_password"]) == $user["password"]) {
          if (!empty($_POST['newpass1']) && !empty($_POST['newpass2']) && $_POST["newpass1"] == $_POST["newpass2"]) {
             $newpass = md5($_POST["newpass1"]);
             doquery("UPDATE {{table}} SET `password` = '{$newpass}' WHERE `id` = '{$user['id']}' LIMIT 1", "users");
             setcookie(COOKIE_NAME, "", time()-100000, "/", "", 0); //le da el expire
             message($lang['succeful_changepass'], $lang['changue_pass'],"Tenexia",1);
          }
       }
       if ($user['username'] != $_POST["db_character"]) {
          $query = doquery("SELECT id FROM {{table}} WHERE username='{$_POST["db_character"]}'", 'users', true);
          if (!$query) {
             doquery("UPDATE {{table}} SET username='{$username}' WHERE id='{$user['id']}' LIMIT 1", "users");
             setcookie(COOKIE_NAME, "", time()-100000, "/", "", 0); //le da el expire
             message($lang['succeful_changename'], $lang['changue_name'],"Tenexia",1);
          }
       }
       message($lang['succeful_save'], $lang['Options'],"Tenexia",1);
    } else {

       $parse['dpath'] = $dpath;
       $parse['opt_lst_skin_data']  = "<option value =\"skins/xnova/\">skins/xnova/</option>";
       $parse['opt_lst_ord_data']   = "<option value =\"0\"". (($user['planet_sort'] == 0) ? " selected": "") .">". $lang['opt_lst_ord0'] ."</option>";
       $parse['opt_lst_ord_data']  .= "<option value =\"1\"". (($user['planet_sort'] == 1) ? " selected": "") .">". $lang['opt_lst_ord1'] ."</option>";
       $parse['opt_lst_ord_data']  .= "<option value =\"2\"". (($user['planet_sort'] == 2) ? " selected": "") .">". $lang['opt_lst_ord2'] ."</option>";

       $parse['opt_lst_cla_data']   = "<option value =\"0\"". (($user['planet_sort_order'] == 0) ? " selected": "") .">". $lang['opt_lst_cla0'] ."</option>";
       $parse['opt_lst_cla_data']  .= "<option value =\"1\"". (($user['planet_sort_order'] == 1) ? " selected": "") .">". $lang['opt_lst_cla1'] ."</option>";

       if ($user['authlevel'] > 0) {
          $FrameTPL = gettemplate('options_admadd');
          $IsProtOn = doquery ("SELECT `id_level` FROM {{table}} WHERE `id_owner` = '".$user['id']."' LIMIT 1;", 'planets', true);
          $bloc['opt_adm_title']       = $lang['opt_adm_title'];
          $bloc['opt_adm_planet_prot'] = $lang['opt_adm_planet_prot'];
          $bloc['adm_pl_prot_data']    = ($IsProtOn['id_level'] > 0) ? " checked='checked'/":'';
          $parse['opt_adm_frame']      = parsetemplate($FrameTPL, $bloc);
       }

       $parse['opt_usern_data'] = $user['username'];
       $parse['opt_mail1_data'] = $user['email'];
       $parse['opt_mail2_data'] = $user['email_2'];
       $parse['opt_dpath_data'] = $user['dpath'];
       $parse['opt_avata_data'] = $user['avatar'];
       $parse['opt_probe_data'] = $user['spio_anz'];
       $parse['opt_toolt_data'] = $user['settings_tooltiptime'];
       $parse['opt_fleet_data'] = $user['settings_fleetactions'];
       $parse['opt_sskin_data'] = ($user['design'] == 1) ? " checked='checked'":'';
       $parse['opt_noipc_data'] = ($user['noipcheck'] == 1) ? " checked='checked'":'';
	   $parse['opt_chat_data'] = ($user['add_chat'] == 1) ? " checked='checked'":'';
       $parse['opt_allyl_data'] = ($user['settings_allylogo'] == 1) ? " checked='checked'/":'';
       $parse['opt_delac_data'] = ($user['db_deaktjava'] == 1) ? " checked='checked'/":'';
       $parse['opt_modev_data'] = ($user['urlaubs_modus'] == 1)?" checked='checked'/":'';
       $parse['opt_modev_exit'] = ($user['urlaubs_modus'] == 0)?" checked='1'/":'';
       $parse['Vaccation_mode'] = $lang['Vaccation_mode'];
       $parse['vacation_until'] = date("d.m.Y G:i:s",$user['urlaubs_until']);
       $parse['user_settings_rep'] = ($user['settings_rep'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_esp'] = ($user['settings_esp'] == 1) ? " checked='checked'/":'';
	   $parse['user_settings_record'] = ($user['record'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_wri'] = ($user['settings_wri'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_mis'] = ($user['settings_mis'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_bud'] = ($user['settings_bud'] == 1) ? " checked='checked'/":'';
       $parse['kolorminus']  = $user['kolorminus'];
       $parse['kolorplus']   = $user['kolorplus'];
       $parse['kolorpoziom'] = $user['kolorpoziom'];

       if($user['urlaubs_modus']){
			//si le mode frame est activ� alors
			if($game_config['frame_disable'] == 1)
			{
            frame(parsetemplate(gettemplate('options_body_vmode'), $parse), $lang['officier']);
			}
			elseif($game_config['frame_disable'] == 0)
			{
			display(parsetemplate(gettemplate('options_body_vmode'), $parse), $title = 'Option mod vac', $topnav = true, $metatags = '', $AdminPage = false, $leftMenu = true);
			}

        
       }else{
			if($game_config['frame_disable'] == 1)
			{
            frame(parsetemplate(gettemplate('options_body'), $parse), $lang['officier']);
			}
			elseif($game_config['frame_disable'] == 0)
			{
			display(parsetemplate(gettemplate('options_body'), $parse),$title, $topnav = true, $metatags = '', $AdminPage = false, $leftMenu = true);
			}
       }
       die();
    }

    ?>
