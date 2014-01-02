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
includeLang('officier');
$gala=intval($_GET["galaxie"]);
$syst=intval($_GET["systeme"]);
$plant=intval($_GET["planete"]);

$utili = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '" . $gala . "' AND `system` = '" . $syst . "' AND `planet` = '" . $plant . "';", 'planets', true);

			echo "<table width=\"519\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
			foreach ($reslist['defense']  as $n => $i) 
			{
						echo "<tr height=\"20\">";
						echo "<th>".$lang['tech'][$i]."</th>";
						echo "<th><span id='txtHint'></span></th>";
						if($i == 407)
						{
							if($utili[$resource[407]]>=1)
							{
								echo "<th>max(<span style='color:red;'>".pretty_number($utili[$resource[$i]])."</span>)</th>";
								echo "<th>".$utili[$resource[$i]]."</th>";
							}
							else
							{
								echo "<th>actuel(<span style='color:lime;'>".pretty_number($utili[$resource[$i]])."</span>)</th>";
								echo "<th><input name=\"defense". $i ."\" size=\"10\" value=\"0\"/></th>";
							}
						}
						elseif($i == 408)
						{
							if($utili[$resource[408]]>=1)
							{
								echo "<th>max(<span style='color:red;'>".pretty_number($utili[$resource[$i]])."</span>)</th>";
								echo "<th>".$utili[$resource[408]]."</th>";
							}
							else
							{
								echo "<th>actuel(<span style='color:lime;'>".pretty_number($utili[$resource[$i]])."</span>)</th>";
								echo "<th><input name=\"defense". $i ."\" size=\"10\" value=\"0\"/></th>";
							}
						}
						else
						{
						echo "<th>actuel(<span style='color:lime;'>".pretty_number($utili[$resource[$i]])."</span>)</th>";
						echo "<th><input name=\"defense". $i ."\" size=\"10\" value=\"0\"/></th>";
						}						
						echo "</tr>";
			}
			echo "</table>";
?>