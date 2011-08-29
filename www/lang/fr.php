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

// General
$lang_str["general_info"]="Information générale";
$lang_str["yes"]="oui";
$lang_str["no"]="non";
#$lang_str["ok"]="Ok";
$lang_str["save"]=array("Sauvegarde");
$lang_str["cancel"]=array("Annuler");
$lang_str["longitude"]=array("Longitude", "Longitudes");
$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(aucun nom)";
$lang_str["info_back"]="retour à la Vue d'ensemble";
$lang_str["info_zoom"]="zoom";
$lang_str["nothing_found"]=array("n'a rien trouvé");
$lang_str["loading"]="chargement";
#$lang_str["more"]="more";
#$lang_str["unnamed"]="unnamed";

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
#$lang_str["head:tags"]=array("Tag", "Tags");
#$lang_str["head:whats_here"]="What's here?";

$lang_str["action_browse"]="visualiser dans OSM";
$lang_str["action_edit"]="éditer dans OSM";

#$lang_str["geo_click_pos"]=array("Click on your position on the map");
#$lang_str["geo_set_pos"]="Set my position";
#$lang_str["geo_change_pos"]="Change my position";

$lang_str["routing_type_car"]="Voiture";
$lang_str["routing_type_car_shortest"]="Voiture (Le plus court)";
$lang_str["routing_type_bicycle"]="Bicycle";
$lang_str["routing_type_foot"]="Piéton";
$lang_str["routing_type"]="Type de route";
$lang_str["routing_distance"]="Distance";
$lang_str["routing_time"]="Temps";
$lang_str["routing_disclaimer"]="Routage: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Choose a category to browse map content or click on an object on the map for details";
$lang_str["list_leisure_sport_tourism"]="Loisir, Sport et Tourisme";

// Mapkey
$lang_str['zoom']="Niveau de Zoom";

$lang_str["grave_is_on"]="Moulin à vent";

$lang_str["main:options"]="Options";
$lang_str["main:about"]="À Propos de";
$lang_str["main:donate"]="Donner";
$lang_str["main:licence"]="Données : <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributeurs | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributeurs</a>";
$lang_str["main:permalink"]="Permalink";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Objet non trouvé</h1>Aucun objet avec le ID \"%s\" n'a pu être trouvé.Ceci peut être du à une (ou plus) des raisons suivantes::<ul><li>Le ID is inexistant.</li><li>L'objet a été identifié par un site tiers et n'est pas encore disponible dans OpenStreetBrowser.</li><li>L'objet n'est pas dans la zone supportée.</li><li>Le lien que vous suivez est ancien et a été effacé de from OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Réglage Autozoom";
$lang_str["help:autozoom"]="Lors de la sélection d'un objet, la fenêtre d'affichage focus sur cet objet. Le niveau de zoom peut aussi être modifié. Avec cette option vous pouvez sélectionner à partir de different modes.";
$lang_str["options:autozoom:pan"]="Bascule vers l'objet courant (+ doux)";
$lang_str["options:autozoom:move"]="Se déplace vers l'objet courant (+ rapide)";
$lang_str["options:autozoom:stay"]="Ne jamais modifier la fenêtre automatiquement";

$lang_str["options:language_support"]="Langue supportée";
$lang_str["help:language_support"]=array("Vous pouvez sélectionner votre langue préférée avec ces options. La première option modifie la langue de l'interface. La deuxième  option modifie la langue des données. À date, de nombreux objets géographiques ont été traduits dans plusieurs langues. Si aucune traduction n'est disponible ou que l'option \"Langue locale\" est sélectionnée, la langue principale de l'objet est affichée.");
$lang_str["options:ui_lang"]="Langue de l'Interface";
$lang_str["options:data_lang"]="Langue des données";
$lang_str["lang:"]="Langue locale";

$lang_str["overlay:data"]="Données";
$lang_str["overlay:draggable"]="Repères";

#$lang_str["user:no_auth"]="Username or password wrong!";
#$lang_str["user:login_text"]="Log in to OpenStreetBrowser:";
#$lang_str["user:create_user"]="Create a new user:";
#$lang_str["user:username"]="Username";
#$lang_str["user:email"]="E-mail address";
#$lang_str["user:password"]="Password";
#$lang_str["user:password_verify"]="Verify password";
#$lang_str["user:old_password"]="Old password";
#$lang_str["user:no_username"]="Please supply a username!";
#$lang_str["user:password_no_match"]="Passwords do not match!";
#$lang_str["user:full_name"]="Full name";
#$lang_str["user:user_exists"]="Username already exists";
#$lang_str["user:login"]="Login";
#$lang_str["user:logged_in_as"]="Logged in as ";
#$lang_str["user:logout"]="Logout";

#$lang_str["error"]="An error occured: ";
#$lang_str["error:not_logged_in"]="you are not logged in";

#$lang_str["more_categories"]="More categories";
#$lang_str["category:status"]="Status";
#$lang_str["category:data_status"]="Status";
#$lang_str["category:old_version"]="A new version of this category is being prepared.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

#$lang_str["category_rule_tag:match"]="Match";
#$lang_str["category_rule_tag:description"]="Description";
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";

// please finish this list, see list.php for full list of languages
$lang_str["lang:de"]="Allemand";
$lang_str["lang:bg"]="Bulgare";
#$lang_str["lang:cs"]="Czech";
$lang_str["lang:en"]="Englais";
$lang_str["lang:es"]="Espagnol";
$lang_str["lang:it"]="Italien";
$lang_str["lang:fr"]="Frençais";
$lang_str["lang:uk"]="Ukrainien";
$lang_str["lang:ru"]="Russe";
$lang_str["lang:ja"]="Japonais";
#$lang_str["lang:hu"]="Hungarian";
#$lang_str["lang:ast"]="Asturian";
// The following $lang_str were not defined in the English language file and might be deprecated or wrong:
$lang_str["search_field"]="Recherche...";
$lang_str["search_tip"]="e.g. 'Montréal', 'Rue Papineau', 'boite postale près du Jardin botanique',...";
$lang_str["search_clear"]="Effacer le champs de recherche";
$lang_str["result_no"]="aucun résultat";
$lang_str["search_process"]="recherche";
$lang_str["search_more"]="plus de résultats";
$lang_str["search_results"]="Recherche résultats";
$lang_str["search_nominatim"]="recherche fournie par";
$lang_str["list_"]="";
$lang_str["place=continent"]="Continent";
$lang_str["place=country"]="Pays";
$lang_str["place=state"]="État / Province";
$lang_str["place=region"]="Région / MRC";
$lang_str["place=county"]="Comté";
$lang_str["place=city_large"]="Cité, > 1 Mio Habitants";
$lang_str["place=city_medium"]="Cité, > 200.000 Habitants";
$lang_str["place=city"]="Cité";
$lang_str["place=town_large"]="Ville, > 30.000  Habitants";
$lang_str["place=town"]="Municipalité";
$lang_str["place=suburb"]="Banlieu";
$lang_str["place=village"]="Village";
$lang_str["place=hamlet"]="Hameau";
$lang_str["place=locality"]="Localité";
$lang_str["place=island"]="Île";
$lang_str["place=islet"]="Îlot";
$lang_str["tag:admin_level=2"]="Frontières nationales";
$lang_str["tag:admin_level=3"]="Limites territoriales";
$lang_str["tag:admin_level=4"]="Limites, État/ Province / Lander";
$lang_str["tag:admin_level=5"]="Limites de Régions administratives";
$lang_str["tag:admin_level=6"]="Limites des MRC (Québec)";
$lang_str["tag:admin_level=7"]="Limites de Comtés";
$lang_str["tag:admin_level=7.5"]="Limites, Agglomérations urbaines";
$lang_str["tag:admin_level=8"]="Limites, Municipalités";
$lang_str["tag:admin_level=10"]="Limites, Arrondissements municipaux";
$lang_str["sub_type=t3|type="]="";
$lang_str["highway_type=residential"]="Rue résidentielle";
$lang_str["highway_type=hiking"]="Piste de randonnée pédestre";
$lang_str["landuse=park"]="Parc";
$lang_str["landuse=education"]="Établissements scolaires";
$lang_str["landuse=tourism"]="Zone de tourisme";
$lang_str["landuse=garden"]="Fermes, Plantations, Jardins";
$lang_str["landuse=industrial"]="Zones industrielles et ferroviaires";
$lang_str["landuse=recreation_ground"]="Aires de Jeux";
$lang_str["landuse=sport"]="Aires de Sports";
$lang_str["landuse=cemetery"]="Cimetières";
$lang_str["landuse=residental"]="Zones résidentielles";
$lang_str["landuse=nature_reserve"]="Réserves naturelles";
$lang_str["landuse=historic"]="Lieux historiques / patrimoniaux";
$lang_str["landuse=emergency"]="Zones d'Urgence";
$lang_str["landuse=health"]="Services de santé";
$lang_str["landuse=public"]="Services publics";
$lang_str["landuse=water"]="Eau";
$lang_str["landuse=natural|sub_type=t0"]="Boisés et Forêts";
$lang_str["landuse=natural|sub_type=t1"]="Zones humides";
$lang_str["landuse=natural|sub_type=t2"]="Glaciers";
$lang_str["landuse=natural|sub_type=t3"]="Éboulis, Landes";
$lang_str["landuse=natural|sub_type=t4"]="Terrain boueux, vase";
$lang_str["landuse=natural|sub_type=t5"]="Plages";
$lang_str["building=default"]="Immeubles";
$lang_str["building=worship"]="Lieux de culte";
$lang_str["building=road_amenities"]="Équipements de transport (Gares, Terminaux, Postes de péage, ...)";
$lang_str["building=nature_building"]="Barrière naturelle";
$lang_str["building=industrial"]="Immeubles industriels";
$lang_str["building=education"]="Établissements scolaire";
$lang_str["building=shop"]="Boutiques";
$lang_str["building=public"]="Immeubles publics";
$lang_str["building=military"]="Immeubles militaires";
$lang_str["building=historic"]="Immeubles Historiques";
$lang_str["building=emergency"]="Immbeubles : Services d'urgence";
$lang_str["building=health"]="Immeubles : Services de santé";
$lang_str["building=communication"]="Immeubles : Services de télécommunication";
$lang_str["building=residential"]="Immeubles résidentiels";
$lang_str["building=culture"]="Immeubles : Services culturels";
$lang_str["building=tourism"]="Immeubles, Services touristiques";
$lang_str["building=sport"]="Immeubles, Activités sportives";
$lang_str["housenumber"]="Addresse";
$lang_str["tag:amenity"]="Équipement";
$lang_str["tag:website"]="Site internet";
$lang_str["tag:address"]="Addresse";
$lang_str["tag:description"]="Description";
$lang_str["tag:cuisine"]="Cuisine";
$lang_str["tag:food"]="Servent aliments";
$lang_str["tag:accomodation"]="Hébergement";
$lang_str["tag:domination"]="Domination";
$lang_str["tag:place=hamlet"]="Hameau";
$lang_str["tag:sport=canoe"]="Canoe";
$lang_str["tag:sport=chess"]="Échecs";
$lang_str["tag:sport=climbing"]="Escalade";
$lang_str["tag:sport=cricket"]="Cricket";
$lang_str["tag:sport=cricket_nets"]="Filets de Cricket";
$lang_str["tag:sport=cycling"]="Vélo";
$lang_str["tag:sport=diving"]="Plongée";
$lang_str["tag:sport=dog_racing"]="Courses de chiens";
$lang_str["tag:sport=football"]="Football";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Gymnastique";
$lang_str["tag:sport=hockey"]="Hockey";
$lang_str["tag:sport=hiking"]="Randonnée pédestre";
$lang_str["tag:sport=horse_racing"]="Courses de chiens";
$lang_str["tag:sport=motor"]="Motor";
$lang_str["tag:sport=multi"]="Multi";
$lang_str["tag:sport=pelota"]="Pelote";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Tir";
$lang_str["tag:sport=skating"]="Patinage";
$lang_str["tag:sport=skiing"]="Ski";
$lang_str["tag:sport=soccer"]="Soccer";
$lang_str["tag:sport=swimming"]="Natation";
$lang_str["tag:sport=table_tennis"]="Tennis de table";
$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Volleyball";
$lang_str["tag:voltage"]="Voltage";
$lang_str["tag:cables"]="Cables";
$lang_str["cuisine_regional"]="régional";
$lang_str["tag:amenity=cinema"]=array("Cinéma", "Cinémas");
$lang_str["tag:amenity=restaurant"]=array("restaurant", "restaurants");
$lang_str["tag:amenity=pub"]=array("Pub", "Pubs");
$lang_str["tag:highway"]=array("Route", "Routes");
