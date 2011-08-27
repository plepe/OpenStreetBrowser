<?
//  All tags should have translations in www/lang/tags_XX.php, with
//  language strings like "tag:key" for the translation of the key and
//  "tag:key=value" for the translation of the value. E.g.
//  $lang_str["tag:amenity"]=array("Amenity", "Amenities");
//  $lang_str["tag:amenity=bar"]=array("Bar", "Bars");
//
//  Furthermore you can describe the tags with the array $tag_type. Every
//  entry is an array again to further specify its type, e.g.:
//  $tag_type["width"]=array("number", "m", "in");
//                ^             ^       ^    ^
//                |             |       |    \-- the preferred unit in this locale
//                |             |       \------- the default unit for this tag
//                |             \--------------- the type of the value
//                \----------------------------- tag
//
//  This defines, that the default value for the tag width is a number, with
//  its default unit m (for meter) and the preferred unit for this locale is
//  in (for inch).
//
//  The following types are valid:
//  * text          default (e.g. religion, name)
//  * number        a value, with default unit and preferred unit as defined
//                  by the second and third entry in this array (e.g. width,
//                  voltage)
//  * count         an integer value (e.g. population)
//  * date          a date
//  * link          an Internet URL
//
//  NOTE: the $tag_type can already be defined, but it's not used yet.
//  There might also be more tag types soon and a way to format the output
//  (e.g. "100.000 m" or "2010-12-24").

// accomodation
$lang_str["tag:accomodation"]="HÉbergement";

// address
$lang_str["tag:address"]="Adresse";

// addr:housenumber
$lang_str["tag:addr:housenumber"]="no. Addresse";

// admin_level
$lang_str["tag:admin_level=2"]="Frontières nationales";
$lang_str["tag:admin_level=3"]="Limites territoriales";
$lang_str["tag:admin_level=4"]="Limites, État / Province / Lander";
$lang_str["tag:admin_level=5"]="Limites de Régions administratives";
$lang_str["tag:admin_level=6"]="Limites des MRC (Québec)";
$lang_str["tag:admin_level=8"]="Limites, Municipalités";
$lang_str["tag:admin_level=10"]="Limites, Arrondissements municipaux";

// amenity
$lang_str["tag:amenity"]="Équipement";
$lang_str["tag:amenity=cinema"]=array("Cinéma", "Cinémas");
$lang_str["tag:amenity=restaurant"]=array("Restaurant", "Restaurants");
$lang_str["tag:amenity=pub"]=array("Pub", "Pubs");

// building
$lang_str["tag:building=yes"]="Immeubles";
$lang_str["tag:building=default"]="Immeubles";
$lang_str["tag:building=worship"]="Lieux de culte";
$lang_str["tag:building=road_amenities"]="Équipements de transport (Gares, Terminaux, Postes de péage, ...)";
$lang_str["tag:building=nature_building"]="Barrière naturelle";
$lang_str["tag:building=industrial"]="Immeubles industriels";
$lang_str["tag:building=education"]="Établissements scolaire";
$lang_str["tag:building=shop"]="Boutiques";
$lang_str["tag:building=public"]="Immeubles publics";
$lang_str["tag:building=military"]="Immeubles militaires";
$lang_str["tag:building=historic"]="Immeubles Historiques";
$lang_str["tag:building=emergency"]="Immbeubles : Services d'urgence";
$lang_str["tag:building=health"]="Immeubles : Services de santé";
$lang_str["tag:building=communication"]="Immeubles : Services de télécommunication";
$lang_str["tag:building=residential"]="Immeubles résidentiels";
$lang_str["tag:building=culture"]="Immeubles : Services culturels";
$lang_str["tag:building=tourism"]="Immeubles, Services touristiques";
$lang_str["tag:building=sport"]="Immeubles, Activités sportives";

// cables
$lang_str["tag:cables"]="Cables";

// cuisine
$lang_str["tag:cuisine"]="Cuisine";
$lang_str["tag:cuisine=regional"]="régionale";

// description
$lang_str["tag:description"]="Description";

// domination
$lang_str["tag:domination"]="Domination";

// food
$lang_str["tag:food"]="Servent aliments";

// highway
$lang_str["tag:highway"]=array("Route", "Routes");
$lang_str["tag:highway=motorway"]="Autoroute";
$lang_str["tag:highway=trunk"]="Voies rapides/Express";
$lang_str["tag:highway=primary"]="Route primaire";
$lang_str["tag:highway=secondary"]="Route secondaire";
$lang_str["tag:highway=tertiary"]="Route tertiaire";
$lang_str["tag:highway=minor"]="Route mineure";
$lang_str["tag:highway=service"]="Route de service";
$lang_str["tag:highway=pedestrian"]="Zone piétonne";
$lang_str["tag:highway=track"]="Route non goudronnée";
$lang_str["tag:highway=path"]="Piste (Pied, Vélo, Équitation)";

// is_in
$lang_str["tag:is_in"]="Est compris dans";

// landuse
#$lang_str["tag:landuse=park"]="Park";
#$lang_str["tag:landuse=education"]="Area of educational facilities";
#$lang_str["tag:landuse=tourism"]="Area of touristic facilities";
#$lang_str["tag:landuse=garden"]="Farms, Plantages, Gardens";
#$lang_str["tag:landuse=industrial"]="Industrial and Railway Areas";
#$lang_str["tag:landuse=sport"]="Areas of sport facilities";
#$lang_str["tag:landuse=cemetery"]="Cemeteries";
#$lang_str["tag:landuse=residental"]="Residental areas";
#$lang_str["tag:landuse=nature_reserve"]="Nature Reserves";
#$lang_str["tag:landuse=historic"]="Areas with historical value";
#$lang_str["tag:landuse=emergency"]="Areas of emergency facilities";
#$lang_str["tag:landuse=health"]="Areas of health facilities";
#$lang_str["tag:landuse=public"]="Areas for public services";
#$lang_str["tag:landuse=water"]="Water Areas";
// the following tags are deprecated
#$lang_str["tag:landuse=natural|sub_type=t0"]="Woods and Forest";
#$lang_str["tag:landuse=natural|sub_type=t1"]="Wetland";
#$lang_str["tag:landuse=natural|sub_type=t2"]="Glaciers";
#$lang_str["tag:landuse=natural|sub_type=t3"]="Screes, Heaths";
#$lang_str["tag:landuse=natural|sub_type=t4"]="Mud";
#$lang_str["tag:landuse=natural|sub_type=t5"]="Beaches";

// leisure
$lang_str["tag:leisure=sports_centre"]="Centre sportif";
$lang_str["tag:leisure=golf_course"]="Golf";
$lang_str["tag:leisure=stadium"]="Stade";
$lang_str["tag:leisure=track"]="Piste";
$lang_str["tag:leisure=pitch"]="Stade sportifs";
$lang_str["tag:leisure=water_park"]="Parc aquatique";
$lang_str["tag:leisure=marina"]="Marina";
$lang_str["tag:leisure=slipway"]="Rampes";
$lang_str["tag:leisure=fishing"]="Pêche";
$lang_str["tag:leisure=nature_reserve"]="Réserve naturelle";
$lang_str["tag:leisure=park"]="Parc de loisir";
$lang_str["tag:leisure=playground"]="Terrain de jeux";
$lang_str["tag:leisure=garden"]="Jardin";
$lang_str["tag:leisure=common"]="Loisirs";
$lang_str["tag:leisure=ice_rink"]="Patinoire";
$lang_str["tag:leisure=miniature_golf"]="Golf miniature";
$lang_str["tag:leisure=swimming_pool"]="Patinoire";
$lang_str["tag:leisure=beach_resort"]="Station balnéaire";
$lang_str["tag:leisure=bird_hide"]="Sanctuaire d'oiseaux";
$lang_str["tag:leisure=sport"]="Autres sports";

// name
$lang_str["tag:name"]=array("Nom", "Nom");

// network
$lang_str["tag:network"]="Réseau";

// note
$lang_str["tag:note"]="Note";

// old_name
$lang_str["tag:old_name"]="Ancien nom(s)";

// opening_hours
$lang_str["tag:opening_hours"]="Heures d'ouverture";

// operator
$lang_str["tag:operator"]="Opérateur";

// place
$lang_str["tag:place"]="Lieu";
$lang_str["tag:place=continent"]="Continent";
$lang_str["tag:place=country"]="Pays";
$lang_str["tag:place=state"]="État / Province / Lander";
$lang_str["tag:place=region"]="Région";
$lang_str["tag:place=county"]="Comté";
$lang_str["tag:place=city"]="Cité";
$lang_str["tag:place=town"]="Ville";
$lang_str["tag:place=village"]="Village";
$lang_str["tag:place=suburb"]="banlieu";
$lang_str["tag:place=locality"]="Localité";
$lang_str["tag:place=island"]="Île";
$lang_str["tag:place=islet"]="Îlot";
// the following tags are deprecated
#$lang_str["tag:place=city;population>1000000"]=array("City, > 1 Mio Inhabitants", "Cities, > 1 Mio Inhabitants");
#$lang_str["tag:place=city;population>200000"]=array("City, > 200.000 Inhabitants", "Cities, > 200.000 Inhabitants");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=town;population>30000"]=array("Town, > 30.000 Inhabitants", "Towns, > 30.000 Inhabitants");

// population
$lang_str["tag:population"]="Population";
$tag_type["population"]=array("count");

// power
#$lang_str["tag:power"]="Power";
$lang_str["tag:power=generator"]="Génératrice";
$lang_str["tag:power=line"]="Lignes de transport d'électricité";
$lang_str["tag:power=tower"]="Pilones électriques";
$lang_str["tag:power=pole"]="Pilones électriques à un seul pied";
$lang_str["tag:power=station"]="Centrale électrique";
$lang_str["tag:power=sub_station"]="Sous-station élecriue";

// power_source
$lang_str["tag:power_source"]="Source d'énergie";
$lang_str["tag:power_source=biofuel"]="Biofuel";
$lang_str["tag:power_source=oil"]="Huile";
$lang_str["tag:power_source=coal"]="Charbon";
$lang_str["tag:power_source=gas"]="Gaù";
$lang_str["tag:power_source=waste"]="Rebuts";
$lang_str["tag:power_source=hydro"]="Hydro";
$lang_str["tag:power_source=tidal"]="Marée";
$lang_str["tag:power_source=wave"]="Vague";
$lang_str["tag:power_source=geothermal"]="Géothermie";
$lang_str["tag:power_source=nuclear"]="Nucléiaire";
$lang_str["tag:power_source=fusion"]="Fusion atomique";
$lang_str["tag:power_source=wind"]="Vent";
$lang_str["tag:power_source=photovoltaic"]="Photovoltaique";
$lang_str["tag:power_source=solar-thermal"]="Énergie solaire";

// real_ale
$lang_str["tag:real_ale"]="Micros-Brasseries";

// religion
$lang_str["tag:religion"]="Religion";
$lang_str["tag:religion=christian"]="chrétien";

// route
$lang_str["tag:route=train"]="Train";
$lang_str["tag:route=railway"]="Chemin de fer";
$lang_str["tag:route=rail"]="Chemin de fer";
$lang_str["tag:route=light_rail"]="Tramway";
$lang_str["tag:route=subway"]="Méro";
$lang_str["tag:route=tram"]="Tram";
$lang_str["tag:route=trolley"]="Trolley";
$lang_str["tag:route=trolleybus"]="Trolley";
$lang_str["tag:route=bus"]="Bus";
$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Traversier";
$lang_str["tag:route=road"]="Route";
$lang_str["tag:route=bicycle"]="Vélo";
$lang_str["tag:route=hiking"]="Randonnée";
$lang_str["tag:route=mtb"]="Vélo de montagne";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="Type de Route";

// shop
$lang_str["tag:shop"]="Boutique";

// sport
#$lang_str["tag:sport"]="Sport";
$lang_str["tag:sport=9pin"]="9pin Bowling 9pin";
$lang_str["tag:sport=10pin"]="10pin Bowling 10 pin";
$lang_str["tag:sport=archery"]="Tir à l'arc / Arbalète";
$lang_str["tag:sport=athletics"]="Athlétisme";
$lang_str["tag:sport=australian_football"]="Football australien";
$lang_str["tag:sport=baseball"]="Baseball";
$lang_str["tag:sport=basketball"]="Basketball";
$lang_str["tag:sport=beachvolleyball"]="Volleyball de plage";
$lang_str["tag:sport=boules"]="Boules";
$lang_str["tag:sport=bowls"]="Bowling";
$lang_str["tag:sport=canoe"]="Canoe";
$lang_str["tag:sport=chess"]="Échecs";
$lang_str["tag:sport=climbing"]="Escalade";
$lang_str["tag:sport=cricket"]="Cricket";
$lang_str["tag:sport=cricket_nets"]="Filets de Cricket";
$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Vélo";
$lang_str["tag:sport=diving"]="Plongée";
$lang_str["tag:sport=dog_racing"]="Course de chiens";
$lang_str["tag:sport=equestrian"]="Equestre";
$lang_str["tag:sport=football"]="Football";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Gymnastique";
$lang_str["tag:sport=hockey"]="Hockey";
$lang_str["tag:sport=horse_racing"]="Course de chevaux";
$lang_str["tag:sport=korfball"]="Korfball";
$lang_str["tag:sport=motor"]="Moteur";
$lang_str["tag:sport=multi"]="Multi";
$lang_str["tag:sport=orienteering"]="Orienteering";
$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
$lang_str["tag:sport=paragliding"]="Paragliding";
$lang_str["tag:sport=pelota"]="Pelote";
$lang_str["tag:sport=racquet"]="Raquette";
$lang_str["tag:sport=rowing"]="Aviron";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Tir";
$lang_str["tag:sport=skating"]="Patin";
$lang_str["tag:sport=skateboard"]="Skateboard";
$lang_str["tag:sport=skiing"]="Ski";
$lang_str["tag:sport=soccer"]="Soccer";
$lang_str["tag:sport=swimming"]="Natation";
$lang_str["tag:sport=table_tennis"]="Tennis de table";
$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Volleyball";

// vending
$lang_str["tag:vending"]="Machines distributrices";

// voltage
$lang_str["tag:voltage"]="Voltage";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="Fils";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Site Web";
$tag_type["website"]=array("link");
// The following $lang_str were not defined in the English language file and might be deprecated or wrong:
$lang_str["tag:admin_level=7"]="Limites de Comtés";
$lang_str["tag:admin_level=7.5"]="Limites, Agglomérations urbaines";
