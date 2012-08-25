<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
#$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Français"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Informations générales";
$lang_str["yes"]="oui";
$lang_str["no"]="non";
$lang_str["ok"]="Ok";
$lang_str["save"]="Sauvegarder";
$lang_str["saved"]="Sauvegarde effectuée"; // for dialog boxes confirming saving
$lang_str["cancel"]="Annuler";
$lang_str["show"]="Afficher";
$lang_str["edit"]="Editer";
$lang_str["delete"]="Supprimer";
$lang_str["history"]="Historique";
$lang_str["choose"]="Choisir";
$lang_str["help"]="Aide";
$lang_str["longitude"]=array("Longitude", "Longitudes");
$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(sans nom)";
$lang_str["info_back"]="retour à la Vue d'ensemble";
$lang_str["info_zoom"]="zoom";
$lang_str["nothing_found"]="aucun résultat";
$lang_str["list:zoom_for_obs"]="Zoom vers des objets mineurs";
$lang_str["loading"]="Chargement en cours ...";
$lang_str["more"]="plus";
$lang_str["source"]="Source";
$lang_str["unnamed"]="sans nom";
$lang_str["zoom"]="Niveau de Zoom";
$lang_str["no_message"]=array("aucun message", "aucun message");
$lang_str["ad"]=array("Publicité", "Publicités");

// Headings
$lang_str["head:general_info"]="Informations générales";
$lang_str["head:stops"]="Arrêts / Stops";
$lang_str["head:routes"]="Routes";
$lang_str["head:members"]="Membres";
$lang_str["head:address"]="Adresse";
$lang_str["head:internal"]="OSM Internal";
$lang_str["head:services"]="Services";
$lang_str["head:culture"]="Culture";
$lang_str["head:routing"]="Itinéraire";
$lang_str["head:search"]="Recherche";
$lang_str["head:actions"]=array("Action", "Actions");
$lang_str["head:location"]="Emplacement";
$lang_str["head:tags"]=array("Balise", "Balises");
$lang_str["head:whats_here"]="Qu'y a-t-il ici ?";

$lang_str["action_browse"]="visualiser dans OSM";
$lang_str["action_edit"]="éditer dans OSM";

$lang_str["geo_click_pos"]="Cliquez sur votre position sur la carte";
$lang_str["geo_set_pos"]="Définir ma position";
$lang_str["geo_change_pos"]="Modifier ma position";

$lang_str["routing_type_car"]="Voiture";
$lang_str["routing_type_car_shortest"]="Voiture (Le plus court)";
$lang_str["routing_type_bicycle"]="Vélo";
$lang_str["routing_type_foot"]="Piéton";
$lang_str["routing_type"]="Type de route";
$lang_str["routing_distance"]="Distance ";
$lang_str["routing_time"]="Temps ";

$lang_str["list_info"]="Cliquez sur un objet pour voir les métadonnées ou choisissez une catégorie pour voir la liste d'objets sur la carte";
$lang_str["list_leisure_sport_tourism"]="Loisir, Sport et Tourisme";

// Mapkey


$lang_str["main:help"]="Aide";
$lang_str["main:options"]="Options";
$lang_str["main:about"]="À Propos de";
$lang_str["main:donate"]="Faire un don";
$lang_str["main:licence"]="Données : <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributeurs | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> et <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributeurs</a>";
$lang_str["main:permalink"]="Lien permanent";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Objet non trouvé</h1>Aucun objet avec l'ID \"%s\" n'a pu être trouvé. Ceci peut être dû à une (ou plus) des raisons suivantes::<ul><li>L'ID est inexistant.</li><li>L'objet a été identifié par un site tiers et n'est pas encore disponible dans OpenStreetBrowser.</li><li>L'objet n'est pas dans la zone supportée.</li><li>Le lien que vous suivez est ancien et a été effacé sur OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Réglage Autozoom";
$lang_str["help:autozoom"]="Lors de la sélection d'un objet, la fenêtre d'affichage focus sur cet objet, le niveau de zoom peut aussi être modifié. Avec cette option vous pouvez sélectionner à partir de différents modes.";
$lang_str["options:autozoom:pan"]="Basculer vers l'objet courant (+ doux)";
$lang_str["options:autozoom:move"]="Se déplacer vers l'objet courant (+ rapide)";
$lang_str["options:autozoom:stay"]="Ne jamais modifier la fenêtre automatiquement";

$lang_str["options:language_support"]="Langue supportée";
$lang_str["help:language_support"]="Vous pouvez sélectionner votre langue préférée avec ces options. La première option modifie la langue de l'interface. La deuxième option modifie la langue des données. À ce jour, de nombreux objets géographiques ont été traduits dans différentes langues. Si aucune traduction n'est disponible ou que l'option \"Langue locale\" est sélectionnée, la langue principale de l'objet est affichée.";
$lang_str["options:ui_lang"]="Langue de l'interface";
$lang_str["options:data_lang"]="Langue des données";
$lang_str["lang:"]="Langue locale";
$lang_str["lang:auto"]="Même lanque que pour l'interface";

$lang_str["overlay:data"]="Données";
$lang_str["overlay:draggable"]="Marqueurs";

$lang_str["user:no_auth"]="Nom d'utilisateur ou mot de passe incorrect !";
$lang_str["user:login_text"]="Connexion à OpenStreetBrowser :";
$lang_str["user:create_user"]="Créer un nouvel utilisateur :";
$lang_str["user:username"]="Nom d'utilisateur";
$lang_str["user:email"]="Adresse e-mail";
$lang_str["user:password"]="Mot de passe";
$lang_str["user:password_verify"]="Vérifier le mot de passe";
$lang_str["user:old_password"]="Ancien mot de passe";
$lang_str["user:no_username"]="Veuillez fournir un nom d'utilisateur !";
$lang_str["user:password_no_match"]="Les mots de passe ne correspondent pas !";
$lang_str["user:full_name"]="Nom complet";
$lang_str["user:user_exists"]="Ce nom d'utilisateur existe déjà";
$lang_str["user:login"]="Connexion";
$lang_str["user:logged_in_as"]="Connecté en tant que ";
$lang_str["user:logout"]="Déconnexion";

$lang_str["attention"]="Attention :";
$lang_str["error"]="Une erreur est survenue :";
$lang_str["error:not_logged_in"]="vous n'êtes pas connecté";

$lang_str["category"]=array("Catégorie", "Catégories");
$lang_str["more_categories"]="Plus de catégories";
$lang_str["category:status"]="Statut";
$lang_str["category:data_status"]="Statut";
$lang_str["category:old_version"]="Une nouvelle version de cette catégorie est en préparation.";
$lang_str["category:not_compiled"]="Une nouvelle catégorie est en préparation.";

$lang_str["category:new_rule"]="Nouvelle règle";
$lang_str["category_rule_tag:match"]="Correspondance";
$lang_str["category_rule_tag:description"]="Description";
$lang_str["category_chooser:choose"]="Choisissez une catégorie";
$lang_str["category_chooser:new"]="Nouvelle catégorie";
$lang_str["category:sub_category"]=array("Sous-catégorie", "Sous-catégories");

$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:osb_light"]="OpenStreetBrowser (pale)";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="Carte cyclable";
