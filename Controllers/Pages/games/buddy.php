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
includeLang('buddy');

$a = $_GET['a'];
$e = $_GET['e'];
$s = $_GET['s'];
$u = intval( $_GET['u'] );

if ( $s == 1 && isset( $_GET['bid'] ) ) {
	// Effacer une entree de la liste d'amis
	$bid = intval( $_GET['bid'] );

	$buddy = doquery( "SELECT * FROM {{table}} WHERE `id` = '".$bid."';", 'buddy', true );
	if ( $buddy['owner'] == $user['id'] ) {
		if ( $buddy['active'] == 0 && $a == 1 ) {
			doquery( "DELETE FROM {{table}} WHERE `id` = '".$bid."';", 'buddy' );
		} elseif ( $buddy['active'] == 1 ) {
			doquery( "DELETE FROM {{table}} WHERE `id` = '".$bid."';", 'buddy' );
		} elseif ( $buddy['active'] == 0 ) {
			doquery( "UPDATE {{table}} SET `active` = '1' WHERE `id` = '".$bid."';", 'buddy' );
		}
	} elseif ( $buddy['sender'] == $user['id'] ) {
		doquery( "DELETE FROM {{table}} WHERE `id` = '".$bid."';", 'buddy' );
	}
} elseif ( $_POST["s"] == 3 && $_POST["a"] == 1 && $_POST["e"] == 1 && isset( $_POST["u"] ) ) {
	// Traitement de l'enregistrement de la demande d'entree dans la liste d'amis
	$uid = $user["id"];
	$u = intval( $_POST["u"] );

	$buddy = doquery( "SELECT * FROM {{table}} WHERE sender={$uid} AND owner={$u} OR sender={$u} AND owner={$uid}", 'buddy', true );

	if ( !$buddy ) {
		if ( strlen( $_POST['text'] ) > 5000 ) {
			message( "Le texte ne doit pas faire plus de 5000 caractères !", "Erreur" );
		}
		$text = mysql_escape_string( strip_tags( $_POST['text'] ) );
		doquery( "INSERT INTO {{table}} SET sender={$uid}, owner={$u}, active=0, text='{$text}'", 'buddy' );
		message( $lang['Request_sent'], $lang['Buddy_request'], 'buddy.php' );
	} else {
		message( $lang['A_request_exists_already_for_this_user'], $lang['Buddy_request'] );
	}
}

$page = "<br>";

if ( $a == 2 && isset( $u ) ) {
	// Saisie texte de demande d'entree dans la liste d'amis
	$u = doquery( "SELECT * FROM {{table}} WHERE id='$u'", "users", true );
	if ( isset( $u ) && $u["id"] != $user["id"] ) {
		$page .= "
		<script src=\"".SCRIPTS."cntchar.js\" type=\"text/javascript\"></script>
		<script src=\"".SCRIPTS."win.js\" type=\"text/javascript\"></script>
		<center>
			<form action='". INDEX_BASE ."buddy' method=post>
			<input type=hidden name=a value=1>
			<input type=hidden name=s value=3>
			<input type=hidden name=e value=1>
			<input type=hidden name=u value=" . $u["id"] . ">
			<table width='100%'>
			<tr>
				<td class=c colspan=2>{$lang['Buddy_request']}</td>
			</tr><tr>
				<th>{$lang['Player']}</th>
				<th>" . $u["username"] . "</th>
			</tr><tr>
				<th>{$lang['Request_text']} (<span id=\"cntChars\">0</span> / 5000 {$lang['characters']})</th>
				<th><textarea name=text cols=60 rows=10 onKeyUp=\"javascript:cntchar(5000)\"></textarea></th>
			</tr><tr>
				<td class=c><a href=\"javascript:back();\">{$lang['Back']}</a></td>
				<td class=c><input type=submit value='{$lang['Send']}'></td>
			</tr>
		</table></form>
		</center>
		</body>
		</html>";
		display( $page, 'buddy',true);
	} elseif ( $u["id"] == $user["id"] ) {
		message( $lang['You_cannot_ask_yourself_for_a_request'], $lang['Buddy_request'] );
	}
}
// con a indicamos las solicitudes y con e las distiguimos
if ( $a == 1 )
	$TableTitle = ( $e == 1 ) ? $lang['My_requests']:$lang['Anothers_requests'];
else
	$TableTitle = $lang['Buddy_list'];

$page .= "
<table width='100%'>
<tr>
	<td class=c colspan=6>{$TableTitle}</td>
</tr>";

if ( !isset( $a ) ) {
	$page .= "
	<tr>
		<th colspan=6><a href='". INDEX_BASE ."buddy&a=1'>{$lang['Requests']}</a></th>
	</tr><tr>
		<th colspan=6><a href='". INDEX_BASE ."buddy&a=1&e=1'>{$lang['My_requests']}</a></th>
	</tr><tr>
		<td class=c></td>
		<td class=c>{$lang['Name']}</td>
		<td class=c>{$lang['Alliance']}</td>
		<td class=c>{$lang['Coordinates']}</td>
		<td class=c>{$lang['Position']}</td>
		<td class=c></td>
	</tr>";
}

if ( $a == 1 ) {
	$query = ( $e == 1 ) ? "WHERE active=0 AND sender=" . $user["id"] : "WHERE active=0 AND owner=" . $user["id"];
} else {
	$query = "WHERE active=1 AND sender=" . $user["id"] . " OR active=1 AND owner=" . $user["id"];
}
$buddyrow = doquery( "SELECT * FROM {{table}} " . $query, 'buddy' );

while ( $b = mysql_fetch_array( $buddyrow ) ) {
	// para solicitudes
	if ( !isset( $i ) && isset( $a ) ) {
		$page .= "
		<tr>
			<td class=c></td>
			<td class=c>{$lang['User']}</td>
			<td class=c>{$lang['Alliance']}</td>
			<td class=c>{$lang['Coordinates']}</td>
			<td class=c>{$lang['Text']}</td>
			<td class=c></td>
		</tr>";
	}

	$i++;
	$uid = ( $b["owner"] == $user["id"] ) ? $b["sender"] : $b["owner"];
	// query del user
	$u = doquery( "SELECT id,username,galaxy,system,planet,onlinetime,ally_id,ally_name FROM {{table}} WHERE id=" . $uid, "users", true );
	// $g = doquery("SELECT galaxy, system, planet FROM {{table}} WHERE id_planet=".$u["id_planet"],"galaxy",true);
	// $a = doquery("SELECT * FROM {{table}} WHERE id=".$uid,"aliance",true);
	if ( $u["ally_id"] != 0 ) { // Alianza
		// $allyrow = doquery("SELECT id,ally_tag FROM {{table}} WHERE id=".$u["ally_id"],"alliance",true);
		// if($allyrow){
		$UserAlly .= "<a href='". INDEX_BASE ."alliance&mode=ainfo&a=" . $u["id"] . "'>" . $u["ally_name"] . "</a>";
		// }
	}

	if ( isset( $a ) ) {
		$LastOnline = $b["text"];
	} else {
		$LastOnline = "<font color=";
		if ( $u["onlinetime"] + 60 * 10 >= time() ) {
			$LastOnline .= "lime>{$lang['On']}";
		} elseif ( $u["onlinetime"] + 60 * 20 >= time() ) {
			$LastOnline .= "yellow>{$lang['15_min']}";
		} else {
			$LastOnline .= "red>{$lang['Off']}";
		}
		$LastOnline .= "</font>";
	}

	if ( isset( $a ) && isset( $e ) ) {
		$UserCommand = "<a href='". INDEX_BASE ."buddy&s=1&bid=" . $b["id"] . "'>{$lang['Delete_request']}</a>";
	} elseif ( isset( $a ) ) {
		$UserCommand = "<a href='". INDEX_BASE ."buddy&s=1&bid=" . $b["id"] . "'>{$lang['Ok']}</a><br/>";
		$UserCommand .= "<a href='". INDEX_BASE ."buddy&a=1&s=1&bid=" . $b["id"] . "'>{$lang['Reject']}</a></a>";
	} else {
		$UserCommand = "<a href='". INDEX_BASE ."buddy&s=1&bid=" . $b["id"] . "'>{$lang['Delete']}</a>";
	}

	$page .= "
	<tr>
		<th width=20>" . $i . "</th>
		<th><a href='". INDEX_BASE ."messages&mode=write&id=" . $u["id"] . "'>" . $u["username"] . "</a></th>
		<th>{$UserAlly}</th>
		<th><a href='". INDEX_BASE ."galaxie&mode=3&galaxy=" . $u["galaxy"] . "&system=" . $u["system"] . "'>" . $u["galaxy"] . ":" . $u["system"] . ":" . $u["planet"] . "</a></th>
		<th>{$LastOnline}</th>
		<th>{$UserCommand}</th>
	</tr>";
}

if ( !isset( $i ) ) {
	$page .= "
	<tr>
		<th colspan=6>{$lang['There_is_no_request']}</th>
	</tr>";
}

if ( $a == 1 ) {
	$page .= "
	<tr>
		<td colspan=6 class=c><a href=buddy.php>{$lang['Back']}</a></td>
	</tr>";
}

$page .= "
	</table>
	</center>";

display ( $page, $lang['Buddy_list'], true);
// Created by Perberos. All rights reversed (C) 2006
?>
