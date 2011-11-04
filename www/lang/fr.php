<?
// All untranslated strings have a # in front. When you translate a string
// please remove this hash.

//  Every language string looks like this:
//  $lang_str["restaurant"]=array("Restaurant", "Restaurants");
//  the first entry is the translation of the language string in Singular,
//  the second the Plural form.
//
//  Optionally you can define the Gender (F, M, N) of this word, e.g. the
//  German translation for office:
//  $lang_str["office"]=array(N, "Büro", "Büros");
//
//  If a Singular/Plural form is not
//  suitable/necessary you can ignore the array, e.g.
//  $lang_str["help"]="Help";
$lang_str["lang:current"]="Français"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Information générale";
$lang_str["yes"]="oui";
$lang_str["no"]="non";
$lang_str["ok"]="Ok";
$lang_str["save"]="Sauvegarde";
$lang_str["cancel"]="Annuler";
$lang_str["longitude"]=array("Longitude", "Longitudes");
$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(aucun nom)";
$lang_str["info_back"]="retour à la Vue d'ensemble";
$lang_str["info_zoom"]="zoom";
$lang_str["nothing_found"]="n'a rien trouvé";
$lang_str["loading"]="chargement";
$lang_str["more"]="plus";
$lang_str["unnamed"]="sans nom";
$lang_str["zoom"]="Niveau de Zoom";

// Headings
$lang_str["head:general_info"]="Information générale";
$lang_str["head:stops"]="Arrêts / Stops";
$lang_str["head:routes"]="Routes";
$lang_str["head:members"]="Membres";
$lang_str["head:address"]="Addresse";
$lang_str["head:internal"]="OSM Internal";
$lang_str["head:services"]="Services";
$lang_str["head:culture"]="Culture";
$lang_str["head:graves"]="Tombes célèbres";
$lang_str["head:routing"]="Routage";
$lang_str["head:search"]="Recherche";
$lang_str["head:actions"]="Actions";
$lang_str["head:location"]="Emplacement";
$lang_str["head:tags"]=array("Balise", "Balises");
$lang_str["head:whats_here"]="Qu'est-ce qui est ici?";

$lang_str["action_browse"]="visualiser dans OSM";
$lang_str["action_edit"]="éditer dans OSM";

$lang_str["geo_click_pos"]="Cliquez sur votre localisation sur la carte";
$lang_str["geo_set_pos"]="Définir ma localisation";
$lang_str["geo_change_pos"]="Modifier ma localisation";

$lang_str["routing_type_car"]="Voiture";
$lang_str["routing_type_car_shortest"]="Voiture (Le plus court)";
$lang_str["routing_type_bicycle"]="Bicycle";
$lang_str["routing_type_foot"]="Piéton";
$lang_str["routing_type"]="Type de route";
$lang_str["routing_distance"]="Distance";
$lang_str["routing_time"]="Temps";
$lang_str["routing_disclaimer"]="Routage: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

// lang_str["list_info"]="Choose a category to browse map content or click on an object on the map for details";
$lang_str["list_info"]="Cliquez sur un objet pour voir les métadonnées ou choisissez une catégorie pour voir la liste d'objets sur la carte";
$lang_str["list_leisure_sport_tourism"]="Loisir, Sport et Tourisme";

// Mapkey

$lang_str["grave_is_on"]="Moulin à vent";

$lang_str["main:options"]="Options";
$lang_str["main:about"]="À Propos de";
$lang_str["main:donate"]="Donner";
$lang_str["main:licence"]="Données : <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributeurs | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributeurs</a>";
$lang_str["main:permalink"]="Permalink";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Objet non trouvé</h1>Aucun objet avec le ID \"%s\" n'a pu être trouvé.Ceci peut être du à une (ou plus) des raisons suivantes::<ul><li>Le ID is inexistant.</li><li>L'objet a été identifié par un site tiers et n'est pas encore disponible dans OpenStreetBrowser.</li><li>L'objet n'est pas dans la zone supportée.</li><li>Le lien que vous suivez est ancien et a été effacé de from OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Réglage Autozoom";
$lang_str["help:autozoom"]="Lors de la sélection d'un objet, la fenêtre d'affichage focus sur cet objet. Le niveau de zoom peut aussi être modifié. Avec cette option vous pouvez sélectionner à partir de different modes.";
$lang_str["options:autozoom:pan"]="Basculer vers l'objet courant (+ doux)";
$lang_str["options:autozoom:move"]="Se déplacer vers l'objet courant (+ rapide)";
$lang_str["options:autozoom:stay"]="Ne jamais modifier la fenêtre automatiquement";

$lang_str["options:language_support"]="Langue supportée";
$lang_str["help:language_support"]="Vous pouvez sélectionner votre langue préférée avec ces options. La première option modifie la langue de l'interface. La deuxième  option modifie la langue des données. À date, de nombreux objets géographiques ont été traduits dans plusieurs langues. Si aucune traduction n'est disponible ou que l'option \"Langue locale\" est sélectionnée, la langue principale de l'objet est affichée.";
$lang_str["options:ui_lang"]="Langue de l'Interface";
$lang_str["options:data_lang"]="Langue des données";
$lang_str["lang:"]="Langue locale";

$lang_str["overlay:data"]="Données";
$lang_str["overlay:draggable"]="Repères";

$lang_str["user:no_auth"]="Code usager ou mot de passe incorret!";
$lang_str["user:login_text"]="Connexion à OpenStreetBrowser:";
$lang_str["user:create_user"]="Ajouter un nouvel usager:";
$lang_str["user:username"]="Code usager";
$lang_str["user:email"]="Adresse de courriel";
$lang_str["user:password"]="Mot de passe";
$lang_str["user:password_verify"]="Vérifier le mot de passe";
$lang_str["user:old_password"]="Ancien mot de passe";
$lang_str["user:no_username"]="SVP fournir un code usager!";
$lang_str["user:password_no_match"]="Les mots de passe diffèrent!";
$lang_str["user:full_name"]="Nom complet";
$lang_str["user:user_exists"]="le Code usager existe déjà";
$lang_str["user:login"]="Connexion";
$lang_str["user:logged_in_as"]="Connexion avec identifiant ";
$lang_str["user:logout"]="Déconnexion";

$lang_str["error"]="Une erreur est survenue: ";
$lang_str["error:not_logged_in"]="vous n'êtes pas connecté";

$lang_str["more_categories"]="Plus de catégories";
$lang_str["category:status"]="Statut";
$lang_str["category:data_status"]="Statut";
$lang_str["category:old_version"]="Une nouvelle version de cette catégorie est en préparation.";
$lang_str["category:not_compiled"]="Une nouvelle catégorie est en préparation.";

$lang_str["category_rule_tag:match"]="Correspondance";
$lang_str["category_rule_tag:description"]="Description";
$lang_str["category_chooser:choose"]="Choisissez une catégorie";
$lang_str["category_chooser:new"]="Nouvelle catégorie";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";
