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
$lang_str["tag:building=worship"]="Edificios relixosos";
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
$lang_str["tag:leisure=fishing"]="Pesca";
$lang_str["tag:leisure=nature_reserve"]="Reserva natural";
$lang_str["tag:leisure=park"]="Parque";
$lang_str["tag:leisure=playground"]="Xuegos infantiles";
$lang_str["tag:leisure=garden"]="Xardín";
$lang_str["tag:leisure=common"]="Campu comunal";
$lang_str["tag:leisure=ice_rink"]="Pista de xelu";
$lang_str["tag:leisure=miniature_golf"]="Minigolf";
$lang_str["tag:leisure=swimming_pool"]="Piscina";
$lang_str["tag:leisure=beach_resort"]="Balneariu de playa";
$lang_str["tag:leisure=bird_hide"]="Observatoriu de páxaros";
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
$lang_str["tag:place=town"]=array("Villa", "Villes");
$lang_str["tag:place=village"]=array("Pueblu", "Pueblos");
$lang_str["tag:place=suburb"]=array("Barriu", "Barrios");
$lang_str["tag:place=locality"]=array("Llugar", "Llugares");
$lang_str["tag:place=island"]=array("Islla", "Islles");
$lang_str["tag:place=islet"]=array("Castru (islla)", "Castros (islles)");
// the following tags are deprecated
#$lang_str["tag:place=city;population>1000000"]=array("City, > 1 Mio Inhabitants", "Cities, > 1 Mio Inhabitants");
#$lang_str["tag:place=city;population>200000"]=array("City, > 200.000 Inhabitants", "Cities, > 200.000 Inhabitants");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=town;population>30000"]=array("Town, > 30.000 Inhabitants", "Towns, > 30.000 Inhabitants");

// population
$lang_str["tag:population"]="Población";
$tag_type["population"]=array("count");

// power
$lang_str["tag:power"]="Enerxía";
$lang_str["tag:power=generator"]="Xenerador eléctricu";
$lang_str["tag:power=line"]="Llinia eléctrica";
$lang_str["tag:power=tower"]="Torre d'alta tensión";
$lang_str["tag:power=pole"]="Poste eléctricu";
$lang_str["tag:power=station"]="Estación tresformadora";
$lang_str["tag:power=sub_station"]="Subestación tresformadora";

// power_source
$lang_str["tag:power_source"]="Fonte d'enerxía";
$lang_str["tag:power_source=biofuel"]="Biocombustible";
$lang_str["tag:power_source=oil"]="Petroleu";
$lang_str["tag:power_source=coal"]="Carbón";
$lang_str["tag:power_source=gas"]="Gas";
$lang_str["tag:power_source=waste"]="Basoria";
$lang_str["tag:power_source=hydro"]="Hidroeléctrica";
$lang_str["tag:power_source=tidal"]="Marees";
$lang_str["tag:power_source=wave"]="Oles";
$lang_str["tag:power_source=geothermal"]="Xeotérmica";
$lang_str["tag:power_source=nuclear"]="Nuclear";
$lang_str["tag:power_source=fusion"]="Fusión";
$lang_str["tag:power_source=wind"]="Vientu";
$lang_str["tag:power_source=photovoltaic"]="Fotovoltaica";
$lang_str["tag:power_source=solar-thermal"]="Térmica solar";

// real_ale
$lang_str["tag:real_ale"]="Tien cerveza ale auténtica";

// religion
$lang_str["tag:religion"]="Relixón";
$lang_str["tag:religion=christian"]=array("cristiana");

// route
$lang_str["tag:route=train"]="Tren";
$lang_str["tag:route=railway"]="Ferrocarril";
$lang_str["tag:route=rail"]="Ferrocarril";
$lang_str["tag:route=light_rail"]="Tren llixeru";
$lang_str["tag:route=subway"]="Metro";
$lang_str["tag:route=tram"]="Tranvía";
$lang_str["tag:route=trolley"]="Trolebús";
$lang_str["tag:route=trolleybus"]="Trolebús";
$lang_str["tag:route=bus"]="Autobús";
$lang_str["tag:route=minibus"]="Minibús";
$lang_str["tag:route=ferry"]="Tresbordador";
$lang_str["tag:route=road"]="Carretera";
$lang_str["tag:route=bicycle"]="Bicicleta";
$lang_str["tag:route=hiking"]="Senderismu";
$lang_str["tag:route=mtb"]="Bici de monte";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="Triba de ruta";

// shop
$lang_str["tag:shop"]="Tienda";

// sport
$lang_str["tag:sport"]="Deportes";
$lang_str["tag:sport=9pin"]="Bolera americana 9pin";
$lang_str["tag:sport=10pin"]="Bolera americana 10pin";
$lang_str["tag:sport=archery"]="Tiru con arcu";
$lang_str["tag:sport=athletics"]="Atletismu";
$lang_str["tag:sport=australian_football"]="Fútbol australianu";
$lang_str["tag:sport=baseball"]="Beisbol";
$lang_str["tag:sport=basketball"]="Baloncestu";
$lang_str["tag:sport=beachvolleyball"]="Voley playa";
$lang_str["tag:sport=boules"]="Petanca";
$lang_str["tag:sport=bowls"]="Bolos";
$lang_str["tag:sport=canoe"]="Piragüismu";
$lang_str["tag:sport=chess"]="Axedrez";
$lang_str["tag:sport=climbing"]="Escalada";
$lang_str["tag:sport=cricket"]="Cricket";
$lang_str["tag:sport=cricket_nets"]="Práctiques de cricket";
$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Ciclismu";
$lang_str["tag:sport=diving"]="Merguyu";
$lang_str["tag:sport=dog_racing"]="Carreres de perros";
$lang_str["tag:sport=equestrian"]="Hípica";
$lang_str["tag:sport=football"]="Fútbol americanu";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Ximnasia";
$lang_str["tag:sport=hockey"]="Ḥoquei";
$lang_str["tag:sport=horse_racing"]="Carreres de caballos";
$lang_str["tag:sport=korfball"]="Korfball";
$lang_str["tag:sport=motor"]="Motor";
$lang_str["tag:sport=multi"]="Polideportivu";
$lang_str["tag:sport=orienteering"]="Orientación";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
$lang_str["tag:sport=paragliding"]="Parapente";
$lang_str["tag:sport=pelota"]="Pelota";
$lang_str["tag:sport=racquet"]="Deportes de raqueta";
$lang_str["tag:sport=rowing"]="Remu";
$lang_str["tag:sport=rugby"]="Rugbi";
$lang_str["tag:sport=shooting"]="Tiru";
$lang_str["tag:sport=skating"]="Patinaxe";
$lang_str["tag:sport=skateboard"]="Monopatín";
$lang_str["tag:sport=skiing"]="Esquí";
$lang_str["tag:sport=soccer"]="Fútbol";
$lang_str["tag:sport=swimming"]="Natación";
$lang_str["tag:sport=table_tennis"]="Tenis de mesa";
$lang_str["tag:sport=team_handball"]="Balonmanu";
$lang_str["tag:sport=tennis"]="Tenis";
$lang_str["tag:sport=volleyball"]="Voleibol";

// vending
$lang_str["tag:vending"]="Venta automática";

// voltage
$lang_str["tag:voltage"]="Voltaxe";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="Cables";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Páxina Web";
$tag_type["website"]=array("link");
