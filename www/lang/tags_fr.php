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
#$lang_str["tag:is_in"]="Is in";

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
#$lang_str["tag:leisure=sports_centre"]="Sport Centre";
#$lang_str["tag:leisure=golf_course"]="Golf Course";
#$lang_str["tag:leisure=stadium"]="Stadium";
#$lang_str["tag:leisure=track"]="Track";
#$lang_str["tag:leisure=pitch"]="Pitche";
#$lang_str["tag:leisure=water_park"]="Water Park";
#$lang_str["tag:leisure=marina"]="Marina";
#$lang_str["tag:leisure=slipway"]="Slipway";
#$lang_str["tag:leisure=fishing"]="Fishing";
#$lang_str["tag:leisure=nature_reserve"]="Nature Reserve";
#$lang_str["tag:leisure=park"]="Leisure Park";
#$lang_str["tag:leisure=playground"]="Playground";
#$lang_str["tag:leisure=garden"]="Garden";
#$lang_str["tag:leisure=common"]="Common";
#$lang_str["tag:leisure=ice_rink"]="Ice Rink";
#$lang_str["tag:leisure=miniature_golf"]="Miniature Golf";
#$lang_str["tag:leisure=swimming_pool"]="Swimming Pool";
#$lang_str["tag:leisure=beach_resort"]="Beach Resort";
#$lang_str["tag:leisure=bird_hide"]="Bird Hide";
#$lang_str["tag:leisure=sport"]="Other Sport";

// name
#$lang_str["tag:name"]=array("Name", "Namen");

// network
#$lang_str["tag:network"]="Network";

// note
#$lang_str["tag:note"]="Note";

// old_name
#$lang_str["tag:old_name"]="Old Name(s)";

// opening_hours
#$lang_str["tag:opening_hours"]="Opening hours";

// operator
#$lang_str["tag:operator"]="Operator";

// place
#$lang_str["tag:place"]="Place";
#$lang_str["tag:place=continent"]=array("Continent", "Continents");
#$lang_str["tag:place=country"]=array("Country", "Countries");
#$lang_str["tag:place=state"]=array("State", "States");
#$lang_str["tag:place=region"]=array("Region", "Regions");
#$lang_str["tag:place=county"]=array("County", "Counties");
#$lang_str["tag:place=city"]=array("City", "Cities");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=village"]=array("Village", "Villages");
#$lang_str["tag:place=suburb"]=array("Suburb", "Suburbs");
#$lang_str["tag:place=locality"]=array("Locality", "Localities");
#$lang_str["tag:place=island"]=array("Island", "Islands");
#$lang_str["tag:place=islet"]=array("Islet", "Islets");
// the following tags are deprecated
#$lang_str["tag:place=city;population>1000000"]=array("City, > 1 Mio Inhabitants", "Cities, > 1 Mio Inhabitants");
#$lang_str["tag:place=city;population>200000"]=array("City, > 200.000 Inhabitants", "Cities, > 200.000 Inhabitants");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=town;population>30000"]=array("Town, > 30.000 Inhabitants", "Towns, > 30.000 Inhabitants");

// population
#$lang_str["tag:population"]="Population";
$tag_type["population"]=array("count");

// power
#$lang_str["tag:power"]="Power";
#$lang_str["tag:power=generator"]="Power Generator";
#$lang_str["tag:power=line"]="Power Line";
#$lang_str["tag:power=tower"]="Power Tower";
#$lang_str["tag:power=pole"]="Power Pole";
#$lang_str["tag:power=station"]="Power Station";
#$lang_str["tag:power=sub_station"]="Power Substation";

// power_source
#$lang_str["tag:power_source"]="Power source";
#$lang_str["tag:power_source=biofuel"]="Biofuel";
#$lang_str["tag:power_source=oil"]="Oil";
#$lang_str["tag:power_source=coal"]="Coal";
#$lang_str["tag:power_source=gas"]="Gas";
#$lang_str["tag:power_source=waste"]="Waste";
#$lang_str["tag:power_source=hydro"]="Hydro";
#$lang_str["tag:power_source=tidal"]="Tidal";
#$lang_str["tag:power_source=wave"]="Wave";
#$lang_str["tag:power_source=geothermal"]="Geothermal";
#$lang_str["tag:power_source=nuclear"]="Nuclear";
#$lang_str["tag:power_source=fusion"]="Fusion";
#$lang_str["tag:power_source=wind"]="Wind";
#$lang_str["tag:power_source=photovoltaic"]="Photovoltaic";
#$lang_str["tag:power_source=solar-thermal"]="Solar Thermal";

// real_ale
#$lang_str["tag:real_ale"]="Real ale offered";

// religion
#$lang_str["tag:religion"]="Religion";
#$lang_str["tag:religion=christian"]=array("christian");

// route
#$lang_str["tag:route=train"]="Train";
#$lang_str["tag:route=railway"]="Railway";
#$lang_str["tag:route=rail"]="Railway";
#$lang_str["tag:route=light_rail"]="Light Rail";
#$lang_str["tag:route=subway"]="Subway";
#$lang_str["tag:route=tram"]="Tram";
#$lang_str["tag:route=trolley"]="Trolley";
#$lang_str["tag:route=trolleybus"]="Trolley";
#$lang_str["tag:route=bus"]="Bus";
#$lang_str["tag:route=minibus"]="Minibus";
#$lang_str["tag:route=ferry"]="Ferry";
#$lang_str["tag:route=road"]="Road";
#$lang_str["tag:route=bicycle"]="Bicycle";
#$lang_str["tag:route=hiking"]="Hiking";
#$lang_str["tag:route=mtb"]="Mountainbike";

// route_type
// the following tags are deprecated
#$lang_str["tag:route_type"]="Route type";

// shop
#$lang_str["tag:shop"]="Shop";

// sport
#$lang_str["tag:sport"]="Sport";
#$lang_str["tag:sport=9pin"]="9pin Bowling";
#$lang_str["tag:sport=10pin"]="10pin Bowling";
#$lang_str["tag:sport=archery"]="Archery";
#$lang_str["tag:sport=athletics"]="Athletics";
#$lang_str["tag:sport=australian_football"]="Australian Football";
#$lang_str["tag:sport=baseball"]="Baseball";
#$lang_str["tag:sport=basketball"]="Basketball";
#$lang_str["tag:sport=beachvolleyball"]="Beachvolleyball";
#$lang_str["tag:sport=boules"]="Boules";
#$lang_str["tag:sport=bowls"]="Bowls";
$lang_str["tag:sport=canoe"]="Canoe";
$lang_str["tag:sport=chess"]="Échecs";
$lang_str["tag:sport=climbing"]="Escalade";
$lang_str["tag:sport=cricket"]="Cricket";
$lang_str["tag:sport=cricket_nets"]="Filets de Cricket";
#$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Vélo";
$lang_str["tag:sport=diving"]="Plongée";
$lang_str["tag:sport=dog_racing"]="Course de chiens";
#$lang_str["tag:sport=equestrian"]="Equestrian";
$lang_str["tag:sport=football"]="Football";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Gymnastique";
$lang_str["tag:sport=hockey"]="Hockey";
$lang_str["tag:sport=horse_racing"]="Course de chevaux";
#$lang_str["tag:sport=korfball"]="Korfball";
$lang_str["tag:sport=motor"]="Moteur";
$lang_str["tag:sport=multi"]="Multi";
#$lang_str["tag:sport=orienteering"]="Orienteering";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
#$lang_str["tag:sport=paragliding"]="Paragliding";
$lang_str["tag:sport=pelota"]="Pelote";
#$lang_str["tag:sport=racquet"]="Racquet";
#$lang_str["tag:sport=rowing"]="Rowing";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Tir";
$lang_str["tag:sport=skating"]="Patin";
#$lang_str["tag:sport=skateboard"]="Skateboard";
$lang_str["tag:sport=skiing"]="Ski";
$lang_str["tag:sport=soccer"]="Soccer";
$lang_str["tag:sport=swimming"]="Natation";
$lang_str["tag:sport=table_tennis"]="Tennis de table";
$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Volleyball";

// vending
#$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Voltage";
$tag_type["voltage"]=array("number", "V", "V");

// wires
#$lang_str["tag:wires"]="Wires";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Site Web";
$tag_type["website"]=array("link");



// The following $lang_str are not defined in www/lang/tags_en.php and might be 
// deprecated/mislocated/wrong:
$lang_str["tag:admin_level=7"]="Limites de Comtés";
$lang_str["tag:admin_level=7.5"]="Limites, Agglomérations urbaines";

