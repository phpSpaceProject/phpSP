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

if($_GET['mode']=='delete')
{
	$accesmsg = false;
	//on securise un peu ^^
	$QryForumsecM = "SELECT * FROM {{table}} WHERE  `topic_id`='".$idsujet."' AND `post_forum_id`='".$idforum."'";
	$Forum_SecureM_query = doquery($QryForumsecM, 'forum_post');
	while ($ListDesMessages = mysql_fetch_array($Forum_SecureM_query)) 
	{
		if($ListDesMessages['post_id']==$_GET['mgs'])
		{
			//on peux uniquement citer les messages des autres
			if($user['authlevel']>=1 && $user['authlevel']<=3)
			{
				$accesmsg = true;
			}
		}
	}
					
	if(isset($_GET['mgs']))
	{
		if(!is_numeric($_GET['mgs']))
		{
			header("Location:". FORUM_BASE ."accueil&type=section&f=".$idforum."&s=".$idsujet."");
		}
		elseif($accesmsg==false)
		{
			header("Location:". FORUM_BASE ."accueil&type=section&f=".$idforum."&s=".$idsujet."");
		}
		else
		{
			$idmgs = intval($_GET['mgs']);									
						//////////////////////////////////////////////////////
						// on fais la requete pour mettre a jours
						$Qry = "
								DELETE FROM
									{{table}}
								WHERE 
								`post_id`      = '{$idmgs}';";

								$delete=doquery($Qry, 'forum_post');
								if($delete)
								{
									//on selectionne le message édité
									$selectpostDelete = "SELECT * FROM {{table}} WHERE  `topic_id`='".$idsujet."' AND `post_forum_id`='".$idforum."' ORDER BY `post_time` DESC LIMIT 1";
									$PostDelete = doquery($selectpostDelete, 'forum_post',true);
									var_dump($PostDelete['post_id']);
									// on fais la requete pour le sujet vue
									$Qry = "
										UPDATE
												{{table}}
										SET 
												`forum_last_post_id` = '{$PostDelete['post_id']}'
										WHERE 
												`forum_id`      = '{$idforum}';";

									doquery($Qry, 'forum_forum');
									
									// on fais la requete pour le sujet vue
									$Qry = "
										UPDATE
												{{table}}
										SET 
												`topic_last_post` = '{$PostDelete['post_id']}',
												`topic_post` = `topic_post` - '1'
										WHERE 
												`topic_id`      = '{$idsujet}';";

									doquery($Qry, 'forum_topic');
								}
								header("Location:". FORUM_BASE ."accueil&type=section&f=".$idforum."&s=".$idsujet."");			
		}
	}					
}