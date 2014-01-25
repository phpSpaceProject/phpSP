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
$lang['Tech']         = "Technologies";
$lang['Requirements'] = "Nécessite";
$lang['Metal']        = "Métal";
$lang['Crystal']      = "Cristal";
$lang['Deuterium']    = "Deutérium";
$lang['Energy']       = "énergie";
$lang['Message']      = "Message";
$lang['level']        = "Niveau";
$lang['treeinfo']     = "[i]";
$lang['comingsoon']   = "Bientot";
$lang['te_dt_tx_pre'] = "Prérequis pour";


$lang['type_mission'][1]  = "Attaquer";
$lang['type_mission'][2]  = "Attaque groupée";
$lang['type_mission'][3]  = "Transporter";
$lang['type_mission'][4]  = "Stationner";
$lang['type_mission'][5]  = "Stationner chez un allié";
$lang['type_mission'][6]  = "Espionner";
$lang['type_mission'][7]  = "Coloniser";
$lang['type_mission'][8]  = "Recycler";
$lang['type_mission'][9]  = "Détruire";
$lang['type_mission'][15] = "Expéditions";
$lang['type_mission'][25] = "Occultation";

// Short names for fight rapport
$lang['tech_rc'] = array (
202 => 'P.transp.',
203 => 'G.transp.',
204 => 'Ch.léger',
205 => 'Ch.lourd',
206 => 'Croiseur',
207 => 'V.bataille',
208 => 'V.colo.',
209 => 'Recycleur',
210 => 'Sonde',
211 => 'Bombardier',
212 => 'Sat.sol.',
213 => 'Destr.',
214 => 'Rip',
215 => 'Traqueur',

401 => 'Missile',
402 => 'L.léger.',
403 => 'L.lourd',
404 => 'Can.Gauss',
405 => 'Art.ions',
406 => 'Lanc.plasma',
407 => 'P.bouclier',
408 => 'G.bouclier',
);

$lang['tech'] = array(
  0 => "B&acirc;timents",
  1 => "Mine de métal",
  2 => "Mine de cristal",
  3 => "Synthétiseur de deutérium",
  4 => "Centrale électrique solaire",
 5 => "Centrale électrique de fusion",
 6 => "Usine de robots",
 7 => "Usine de nanites",
 8 => "Chantier spatial",
 15 => "Centre de défense",
 9 => "Hangar de métal",
 10 => "Hangar de cristal",
 11 => "Réservoir de deutérium",
 12 => "Laboratoire de recherche",
 13 => "Terraformeur",

// Technologies
100 => 'Recherches',
101 => 'Technologie Espionnage',
102 => 'Technologie Ordinateur',
103 => 'Technologie Armes',
104 => 'Technologie Bouclier',
105 => 'Technologie Protection des vaisseaux spatiaux',
106 => 'Technologie Energie',
107 => 'Technologie Propulsion',
108 => 'Réacteur à combustion',
109 => 'Réacteur à impulsion',
110 => 'Propulsion hyperespace',
111 => 'Technologie Laser',
112 => 'Technologie Ions',
113 => 'Technologie Plasma',
114 => 'Réseau de recherche intergalactique',
116 => 'Technologie Plénitude',
117 => 'Technologie Expansion',
118 => 'Teleportation',
119 => 'Technologie Graviton',

200 => 'Vaisseaux',
202 => 'Petit transporteur',
203 => 'Grand transporteur',
204 => 'Chasseur léger',
205 => 'Chasseur lourd',
206 => 'Croiseur',
207 => 'Vaisseau de bataille',
208 => 'Vaisseau de colonisation',
209 => 'Recycleur',
210 => 'Sonde espionnage',
211 => 'Bombardier',
212 => 'Satellite solaire',
213 => 'Destructeur',
214 => 'Etoile de la mort',
215 => 'Traqueur',


400 => 'Défense',
401 => 'Lanceur de missiles',
402 => 'Artillerie laser légère',
403 => 'Artillerie laser lourde',
404 => 'Canon de Gauss',
405 => 'Artillerie à ions',
406 => 'Lanceur de plasma',
407 => 'Petit bouclier',
408 => 'Grand bouclier'
);

$lang['res']['descriptions'] = array(
1 => "Principal fournisseur de matières premières pour la construction de structures portantes et de vaisseaux.",
2 => "Fournisseur principal de ressources pour les installations électroniques et pour les alliages.",
3 => "Extrait la petite quantité de deutérium de l'eau d'une planète.",
4 => "Les centrales électriques solaires transforment les rayons de soleil en énergie. Presque tous les b&acirc;timents ont besoin d'énergie pour fonctionner.",
5 => "La centrale électrique de fusion produit de l'énergie en fusionnant 2 atomes d'hydrogène en un atome d'hélium.",
6 => "Les usines de robots produisent des robots ouvriers qui servent à la construction de l'infrastructure planétaire. Chaque niveau augmente la vitesse de construction des différents b&acirc;timents.",
7 => "C'est le perfectionnement de la technologie de robots. Chaque niveau augmente la vitesse de construction des vaisseaux et des b&acirc;timents.",
8 => "Le chantier spatial permet de construire les vaisseaux.",
15 => "Le Centre de défense permet de construire comme son nom l'indique des installations de défense.",
9 => "Hangar pour minerai avant le traitement.",
10 => "Hangar pour cristal avant le traitement.",
11 => "Réservoirs géants pour le stockage de deutérium.",
12 => "Le laboratoire de recherche est nécessaire pour développer de nouvelles technologies.",
13 => "Le terraformeur permet d'agrandir la surface utile des planètes.",

101 => "Cette technologie permet d'obtenir des informations sur les autres planètes de l'univers.",
102 => "Avec l'augmentation des capacités des ordinateurs, plus de flottes peuvent être commandées. Chaque niveau de technologie ordinateur augmente d'une le nombre total de flottes commandables.",
103 => "La technologie armes rend les systèmes d'armes plus efficaces. Chaque niveau de technologie armes augmente la puissance des armes des unités par tranche de 10% de la valeur de base.",
104 => "Chaque niveau de la technologie de bouclier augmente l'efficacité des boucliers par tranche de 10%.",
105 => "Des alliages spéciaux rendent les vaisseaux spatiaux de plus en plus résistants. L'efficacité des protections peut être augmentée de 10% par niveau.",
106 => "Maîtriser les différents types d'énergie est nécessaire pour de nombreuses technologies.",
107 => "L'intégration de la 4eme et de la 5eme dimension permet le développement d'un nouveau genre de propulsion plus puissant et efficace.",
108 => "Le développement de ces réacteurs rend les vaisseaux plus rapides mais chaque niveau n'augmente la vitesse que de 10%.",
109 => "Le réacteur à impulsion est basé sur le principe de réaction disant que la plus grande part de la masse du rayon est gagnée comme sous-produit de la fusion d'atomes qui sert à produire l'énergie nécessaire.",
110 => "Par une déformation spatiale et temporelle dans l'environnement du vaisseau, l'espace est comprimé ce qui permet de parcourir de longues distances dans un minimum de temps.",
111 => "La concentration de lumière crée un rayon pouvant créer des dég&acirc;ts importants  en touchant un objet.",
112 => "Rayon mortel composé d'ions accélérés. En touchant un objet, il cause des dég&acirc;ts importants.",
113 => "Une amélioration de la technologie d'ions qui n'accélère pas des ions mais du plasma très énergétique. Ceci a un effet dévastateur en touchant un objet.",
114 => "Les chercheurs de plusieurs planètes utilisent ce réseau pour communiquer.",
115 => "Fusion entre le plasma et le ions, elle permettrait dis-t-on de convertir de la matière en antimatière ce qui aboutirait à une occultation partielle de b&acirc;timents ou vaisseaux.",
116 => "Permet d augmenter la capacité de stockage  des hangars",
117 => "Le terraformage de certaines planètes n'étant pas assez rentable pour les grandes entreprises, des scientifiques ont mis au point une amélioration, en y ajoutant des expansions.",
118 => "les Scientifiques ont travaillés durant des années dans le secret , pour decouvrir un moyen de déplacement instantané des ressources.",
119 => "Une quantité concentrée de particules de graviton, réseau artificiel de gravitation, est propulsée, capable de détruire des vaisseaux ou même des lunes.",

202 => "Le petit transporteur est un vaisseau très maniable et capable de transporter des matières premières sur d'autres planètes rapidement.",
203 => "Le développement du transporteur augmente la capacité de fret et rend le vaisseau plus rapide que le petit transporteur.",
204 => "Le chasseur léger est un vaisseau très manoeuvrable qui est stationné sur presque toutes les planètes. Les co&ucirc;ts ne sont pas très importants, mais la puissance du bouclier et la capacité de fret sont très limitées.",
205 => "Cette version améliorée du chasseur léger possède une meilleure protection et une capacité d'attaque plus importante.",
206 => "Les croiseurs ont une protection presque trois fois plus importante que celle des chasseurs lourds et leur puissance de tir est plus de deux fois plus grande. De plus, ils sont très rapides.",
207 => "Les vaisseaux de bataille jouent un r&ocirc;le central dans les flottes. Avec leur artillerie lourde, leur vitesse considérable et la grande capacité de fret, ils sont des adversaires respectables.",
208 => "Les nouvelles planètes peuvent être colonisées avec ce vaisseau.",
209 => "Le recycleur collecte les ressources dans les Champs de débris.",
210 => "Les sondes d'espionnage sont des petits drones manoeuvrables qui espionnent les planètes même à grande distance.",
211 => "Le bombardier a été développé pour pouvoir détruire les installations de défense des planètes.",
212 => "Les satellites solaires sont des plates-formes couvertes de cellules solaires, qui se trouvent dans une orbite très élevée et stationnaire. Ils collectent la lumière du soleil et la transmettent par laser à la station de base.",
213 => "Le destructeur est le roi des vaisseaux de guerre.",
214 => "La puissance de destruction de l'étoile de la mort est imbattable.",
215 => "Le traqueur est spécialisé dans l'interception de flottes ennemies.",

401 => "Le lanceur de missiles est une fa&ccedil;on simple et bon marché de se défendre.",
402 => "Le bombardement concentré de photons peut causer des dég&acirc;ts nettement plus important que les armes balistiques habituelles.",
403 => "L'artillerie lourde au laser est l'évolution conséquente de l'artillerie légère au laser.",
404 => "Le canon de Gauss (canon électromagnétique) accélère un projectile pesant des tonnes en consommant une gigantesque quantité d'énergie.",
405 => "L'artillerie d'ions lance des vagues d'ions sur l'objet, ce qui déstabilise les boucliers et endommage l'électronique.",
406 => "Les lanceurs de plasma disposent de la puissance d'une éruption solaire et peuvent donc être plus destructeurs que les destructeurs eux-mêmes.",
407 => "Le petit bouclier couvre toute une planète avec un champ infranchissable qui peut absorber une quantité énorme d'énergie.",
408 => "L'amélioration du petit bouclier peut se servir de nettement plus d'énergie pour se défendre.",

);

// Created by Perberos. All rights reversed (C) 2006
// Complet by XNova Team. All rights reversed (C) 2008
?>