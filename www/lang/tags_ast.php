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
$lang_str["tag:accomodation"]="Agospiamientu";

// address
$lang_str["tag:address"]="Direición";

// addr:housenumber
$lang_str["tag:addr:housenumber"]="Númberu de portal";

// admin_level
$lang_str["tag:admin_level=2"]="Frontera de país";
$lang_str["tag:admin_level=3"]="Divisiones";
$lang_str["tag:admin_level=4"]="Frontera d'estáu";
$lang_str["tag:admin_level=5"]="Llende de comunidá";
$lang_str["tag:admin_level=6"]="Llende de condáu";
$lang_str["tag:admin_level=8"]="Llende de ciudá/conceyu";
$lang_str["tag:admin_level=10"]="Subdivisiones de ciudaes";

// amenity
$lang_str["tag:amenity"]="Infraestructura";
$lang_str["tag:amenity=cinema"]=array("Cine", "Cines");
$lang_str["tag:amenity=restaurant"]=array("Restaurante", "Restaurantes");
$lang_str["tag:amenity=pub"]=array("Pub", "Pubs");

// building
$lang_str["tag:building=yes"]="Edificios";
$lang_str["tag:building=default"]="Edificios";
$lang_str["tag:building=worship"]="Edificios religiosos";
$lang_str["tag:building=road_amenities"]="Instalaciones de tresporte (Estaciones, Terminales, Cabines de peaxes, ...)";
$lang_str["tag:building=nature_building"]="Construcciones naturales (p.ex. Barreres)";
$lang_str["tag:building=industrial"]="Edificios industriales";
$lang_str["tag:building=education"]="Edificios educativos";
$lang_str["tag:building=shop"]="Tiendes";
$lang_str["tag:building=public"]="Edificios públicos";
$lang_str["tag:building=military"]="Edificios militares";
$lang_str["tag:building=historic"]="Edificios históricos";
$lang_str["tag:building=emergency"]="Edificios de servicios d'emerxencia";
$lang_str["tag:building=health"]="Edificios de servicios sanitarios";
$lang_str["tag:building=communication"]="Edificios de comunicaciones";
$lang_str["tag:building=residential"]="Edificios de viviendes";
$lang_str["tag:building=culture"]="Edificios culturales";
$lang_str["tag:building=tourism"]="Edificios turísticos";
$lang_str["tag:building=sport"]="Edificios p'actividaes deportives";

// cables
$lang_str["tag:cables"]="Cables";

// cuisine
$lang_str["tag:cuisine"]="Cocina";
$lang_str["tag:cuisine=regional"]="rexonal";

// description
$lang_str["tag:description"]="Descripción";

// domination
$lang_str["tag:domination"]="Dominiu";

// food
$lang_str["tag:food"]="Sirve de comer";

// highway
$lang_str["tag:highway"]=array("Carretera", "Carreteres");
$lang_str["tag:highway=motorway"]="Autopista";
$lang_str["tag:highway=trunk"]="Carretera nacional";
$lang_str["tag:highway=primary"]="Carretera primaria";
$lang_str["tag:highway=secondary"]="Carretera secundaria";
$lang_str["tag:highway=tertiary"]="Carretera terciaria";
$lang_str["tag:highway=minor"]="Carretera menor";
$lang_str["tag:highway=service"]="Carretera de serviciu";
$lang_str["tag:highway=pedestrian"]="Zona peatonal";
$lang_str["tag:highway=track"]="Pista";
$lang_str["tag:highway=path"]="Camín";

// is_in
$lang_str["tag:is_in"]="Ta en";

// landuse
$lang_str["tag:landuse=park"]="Parque";
$lang_str["tag:landuse=education"]="Área d'instalaciones educatives";
$lang_str["tag:landuse=tourism"]="Área d'instalaciones turístiques";
$lang_str["tag:landuse=garden"]="Caseríes, plantaciones, xardinos";
$lang_str["tag:landuse=industrial"]="Árees industriales y ferroviaries";
$lang_str["tag:landuse=sport"]="Árees d'instalaciones deportives";
$lang_str["tag:landuse=cemetery"]="Cementerios";
$lang_str["tag:landuse=residental"]="Árees de viviendes";
$lang_str["tag:landuse=nature_reserve"]="Reserves naturales";
$lang_str["tag:landuse=historic"]="Árees con valor históricu";
$lang_str["tag:landuse=emergency"]="Árees d'instalaciones d'emerxencia";
$lang_str["tag:landuse=health"]="Árees d'instalaciones sanitaries";
$lang_str["tag:landuse=public"]="Árees de servicios públicos";
$lang_str["tag:landuse=water"]="Árees acuátiques";
// the following tags are deprecated
$lang_str["tag:landuse=natural|sub_type=t0"]="Viesca y forestal";
$lang_str["tag:landuse=natural|sub_type=t1"]="Humedal";
$lang_str["tag:landuse=natural|sub_type=t2"]="Glaciares";
$lang_str["tag:landuse=natural|sub_type=t3"]="Lleres, grandes";
$lang_str["tag:landuse=natural|sub_type=t4"]="Llamuerga";
$lang_str["tag:landuse=natural|sub_type=t5"]="Playes";

// leisure
$lang_str["tag:leisure=sports_centre"]="Centru deportivu";
$lang_str["tag:leisure=golf_course"]="Campu de golf";
$lang_str["tag:leisure=stadium"]="Estadiu";
$lang_str["tag:leisure=track"]="Pista";
$lang_str["tag:leisure=pitch"]="Campu";
$lang_str["tag:leisure=water_park"]="Parque acuáticu";
$lang_str["tag:leisure=marina"]="Puertu deportivu";
$lang_str["tag:leisure=slipway"]="Rampla de botadura";
$lang_str["tag:leisure=fishing"]="Piesca";
$lang_str["tag:leisure=nature_reserve"]="Reserva natural";
$lang_str["tag:leisure=park"]="Parque";
$lang_str["tag:leisure=playground"]="Xuegos infantiles";
$lang_str["tag:leisure=garden"]="Xardín";
$lang_str["tag:leisure=common"]="Campu comunal";
$lang_str["tag:leisure=ice_rink"]="Pista de xelu";
$lang_str["tag:leisure=miniature_golf"]="Minigolf";
$lang_str["tag:leisure=swimming_pool"]="Piscina";
#$lang_str["tag:leisure=beach_resort"]="Beach Resort";
#$lang_str["tag:leisure=bird_hide"]="Bird Hide";
$lang_str["tag:leisure=sport"]="Otros deportes";

// name
$lang_str["tag:name"]=array("Nome", "Nomes");

// network
$lang_str["tag:network"]="Rede";

// note
$lang_str["tag:note"]="Nota";

// old_name
$lang_str["tag:old_name"]="Nome(s) antiguu";

// opening_hours
$lang_str["tag:opening_hours"]="Hores d'apertura";

// operator
$lang_str["tag:operator"]="Operador";

// place
$lang_str["tag:place"]="Llugar";
$lang_str["tag:place=continent"]=array("Continente", "Continentes");
$lang_str["tag:place=country"]=array("País", "Países");
$lang_str["tag:place=state"]=array("Estáu", "Estaos");
$lang_str["tag:place=region"]=array("Rexón", "Rexones");
$lang_str["tag:place=county"]=array("Condáu", "Condaos");
$lang_str["tag:place=city"]=array("Ciudá", "Ciudaes");
#$lang_str["tag:place=town"]="Town";
$lang_str["tag:place=village"]=array("Villa", "Villes");
$lang_str["tag:place=suburb"]=array("Barriu", "Barrios");
$lang_str["tag:place=locality"]=array("Llocalida", "Llocalidaes");
$lang_str["tag:place=island"]=array("Islla", "Islles");
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
#$lang_str["tag:sport=canoe"]="Canoe";
#$lang_str["tag:sport=chess"]="Chess";
#$lang_str["tag:sport=climbing"]="Climbing";
#$lang_str["tag:sport=cricket"]="Cricket";
#$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
#$lang_str["tag:sport=croquet"]="Croquet";
#$lang_str["tag:sport=cycling"]="Cycling";
#$lang_str["tag:sport=diving"]="Diving";
#$lang_str["tag:sport=dog_racing"]="Dog Racing";
#$lang_str["tag:sport=equestrian"]="Equestrian";
#$lang_str["tag:sport=football"]="Football";
#$lang_str["tag:sport=golf"]="Golf";
#$lang_str["tag:sport=gymnastics"]="Gymnastics";
#$lang_str["tag:sport=hockey"]="Hockey";
#$lang_str["tag:sport=horse_racing"]="Horse Racing";
#$lang_str["tag:sport=korfball"]="Korfball";
#$lang_str["tag:sport=motor"]="Motor";
#$lang_str["tag:sport=multi"]="Multi";
#$lang_str["tag:sport=orienteering"]="Orienteering";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
#$lang_str["tag:sport=paragliding"]="Paragliding";
#$lang_str["tag:sport=pelota"]="Pelota";
#$lang_str["tag:sport=racquet"]="Racquet";
#$lang_str["tag:sport=rowing"]="Rowing";
#$lang_str["tag:sport=rugby"]="Rugby";
#$lang_str["tag:sport=shooting"]="Shooting";
#$lang_str["tag:sport=skating"]="Skating";
#$lang_str["tag:sport=skateboard"]="Skateboard";
#$lang_str["tag:sport=skiing"]="Skiing";
#$lang_str["tag:sport=soccer"]="Soccer";
#$lang_str["tag:sport=swimming"]="Swimming";
#$lang_str["tag:sport=table_tennis"]="Table Tennis";
#$lang_str["tag:sport=team_handball"]="Handball";
#$lang_str["tag:sport=tennis"]="Tennis";
#$lang_str["tag:sport=volleyball"]="Volleyball";

// vending
#$lang_str["tag:vending"]="Vending";

// voltage
#$lang_str["tag:voltage"]="Voltage";
$tag_type["voltage"]=array("number", "V", "V");

// wires
#$lang_str["tag:wires"]="Wires";
$tag_type["wires"]=array("count");

// website
#$lang_str["tag:website"]="Website";
$tag_type["website"]=array("link");





