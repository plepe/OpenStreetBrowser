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
$lang_str["tag:accomodation"]="Accomodatie";

// address
$lang_str["tag:address"]="Adres";

// addr:housenumber
$lang_str["tag:addr:housenumber"]="Huisnummer";

// addr:interpolation
$lang_str["tag:addr:interpolation"]="Geïnterpoleerde huisnummers";

// aeroway
$lang_str["tag:aeroway"]="Luchthaven";
$lang_str["tag:aeroway=runway"]="Startbaan";
$lang_str["tag:aeroway=taxiway"]="Taxibaan";

// admin_level
$lang_str["tag:admin_level=2"]="Landsgrens";
#$lang_str["tag:admin_level=3"]="Divisions";
$lang_str["tag:admin_level=4"]="Provinciegrens";
#$lang_str["tag:admin_level=5"]="Community Border";
$lang_str["tag:admin_level=6"]="Regiogrens";
$lang_str["tag:admin_level=8"]="Gemeentegrens";
$lang_str["tag:admin_level=10"]="Woonplaatsgrens";

// amenity
$lang_str["tag:amenity"]="Voorzieningen";
$lang_str["tag:amenity=cinema"]=array("Bioscoop", "Bioscopen");
$lang_str["tag:amenity=restaurant"]=array("Restaurant", "Restaurants");
$lang_str["tag:amenity=pub"]=array("Kroeg", "Kroegen");

// cables
$lang_str["tag:cables"]="Kabels";

// cuisine
$lang_str["tag:cuisine"]="Keuken";
$lang_str["tag:cuisine=regional"]="Regionaal";

// description
$lang_str["tag:description"]="Description";

// domination
$lang_str["tag:domination"]="Overheersing";

// food
$lang_str["tag:food"]="Serveert eten";

// highway
$lang_str["tag:highway"]=array("Weg", "Wegen");
$lang_str["tag:highway=motorway"]="Autosnelweg";
$lang_str["tag:highway=motorway_link"]="Autosnelwegverbinding";
$lang_str["tag:highway=trunk"]="Autoweg";
$lang_str["tag:highway=trunk_link"]="Autowegverbinding";
$lang_str["tag:highway=primary"]="Provinciale weg";
$lang_str["tag:highway=primary_link"]="Verbindingsweg";
$lang_str["tag:highway=secondary"]="Secundaire weg";
$lang_str["tag:highway=tertiary"]="Tertiare weg";
$lang_str["tag:highway=minor"]="Kleine weg";
$lang_str["tag:highway=road"]="Weg (classificatie onbekend)";
$lang_str["tag:highway=residential"]="Woonstraat";
$lang_str["tag:highway=unclassified"]="Ongeclassificeerde weg";
$lang_str["tag:highway=service"]="Toegangsweg";
$lang_str["tag:highway=pedestrian"]="Voetgangersgebied";
$lang_str["tag:highway=living_street"]="Woonerf";
$lang_str["tag:highway=path"]="Pad";
$lang_str["tag:highway=cycleway"]="Fietspad";
$lang_str["tag:highway=footway"]="Voetpad";
$lang_str["tag:highway=bridleway"]="Ruiterpad";
$lang_str["tag:highway=track"]="Veldweg";
$lang_str["tag:highway=path"]="Pad";
$lang_str["tag:highway=steps"]="Trappen";

// is_in
$lang_str["tag:is_in"]="Ligt in";

// leisure
$lang_str["tag:leisure=sports_centre"]="Sportcentrum";
$lang_str["tag:leisure=golf_course"]="Golfbaan";
$lang_str["tag:leisure=stadium"]="Stadion";
$lang_str["tag:leisure=track"]="Sportbaan";
$lang_str["tag:leisure=pitch"]="Sportveld";
$lang_str["tag:leisure=water_park"]="Waterpark";
$lang_str["tag:leisure=marina"]="Jachthaven";
$lang_str["tag:leisure=slipway"]="Botenhelling";
$lang_str["tag:leisure=fishing"]="Visplek";
$lang_str["tag:leisure=nature_reserve"]="Natuurreservaat";
$lang_str["tag:leisure=park"]="Park";
$lang_str["tag:leisure=playground"]="Speelplaats";
$lang_str["tag:leisure=garden"]="Tuin";
$lang_str["tag:leisure=common"]="Algemeen";
$lang_str["tag:leisure=ice_rink"]="IJsbaan";
$lang_str["tag:leisure=miniature_golf"]="Midgetgolfbaan";
$lang_str["tag:leisure=swimming_pool"]="Zwembad";
#$lang_str["tag:leisure=beach_resort"]="Beach Resort";
$lang_str["tag:leisure=bird_hide"]="Vogelkijkhut";
$lang_str["tag:leisure=sport"]="Andere sport";

// man_made
$lang_str["tag:man_made"]="Kunstmatige constructies";
$lang_str["tag:man_made=pipeline"]=array("Pijpleiding", "Pijpleidingen");

// man_made - type
$lang_str["tag:type"]="Soort";
$lang_str["tag:type=gas"]="Gas";
$lang_str["tag:type=heat"]="Stadsverwarming";
$lang_str["tag:type=hot_water"]="Heet water";
$lang_str["tag:type=oil"]="Olie";
$lang_str["tag:type=sewage"]="Rioolwater";
$lang_str["tag:type=water"]="Water";

// name
$lang_str["tag:name"]=array("Naam", "Namen");

// network
$lang_str["tag:network"]="Netwerk";

// note
$lang_str["tag:note"]="Aantekening";

// old_name
$lang_str["tag:old_name"]="Oude naam";

// opening_hours
$lang_str["tag:opening_hours"]="Openingstijden";

// operator
$lang_str["tag:operator"]="Exploitant";

// place
$lang_str["tag:place"]="Plaats";
$lang_str["tag:place=continent"]=array("Continent", "Continenten");
$lang_str["tag:place=country"]=array("Land", "Landen");
$lang_str["tag:place=state"]=array("Staat", "Staten");
$lang_str["tag:place=region"]=array("Regio", "Regio's");
$lang_str["tag:place=county"]=array("Provincie", "Provincies");
$lang_str["tag:place=city"]=array("Stad", "Steden");
$lang_str["tag:place=town"]=array("Kleine stad", "Kleine steden");
$lang_str["tag:place=village"]=array("Dorp", "Dorpen");
$lang_str["tag:place=suburb"]=array("Buitenwijk", "Buitenwijken");
$lang_str["tag:place=locality"]=array("Lokaliteit", "Lokaliteiten");
$lang_str["tag:place=island"]=array("Eiland", "Eilanden");
$lang_str["tag:place=islet"]=array("Eilandje", "Eilandjes");

// population
$lang_str["tag:population"]="Bevolking";
$tag_type["population"]=array("count");

// power
$lang_str["tag:power"]="Stroomvoorziening";
$lang_str["tag:power=generator"]="Elektriciteitsgenerator";
$lang_str["tag:power=line"]="Hoogspanningsleiding";
$lang_str["tag:power=minor_line"]="Electriciteitsleiding";
$lang_str["tag:power=tower"]="Hoogspanningsmast";
$lang_str["tag:power=pole"]="Elektriciteitspaal";
$lang_str["tag:power=station"]="Verdeelstation";
$lang_str["tag:power=sub_station"]="Transformatorstation";

// power_source
$lang_str["tag:power_source"]="Energiebron";
$lang_str["tag:power_source=biofuel"]="Biobrandstof";
$lang_str["tag:power_source=oil"]="Olie";
$lang_str["tag:power_source=coal"]="Kolen";
$lang_str["tag:power_source=gas"]="Aardgas";
$lang_str["tag:power_source=waste"]="Afval";
$lang_str["tag:power_source=hydro"]="Water";
$lang_str["tag:power_source=tidal"]="Getijden";
$lang_str["tag:power_source=wave"]="Golven";
$lang_str["tag:power_source=geothermal"]="Geothermisch";
$lang_str["tag:power_source=nuclear"]="Nucleair";
$lang_str["tag:power_source=fusion"]="Fusie";
$lang_str["tag:power_source=wind"]="Wind";
$lang_str["tag:power_source=photovoltaic"]="Fotovoltaïsch";
$lang_str["tag:power_source=solar-thermal"]="Thermische zonne-energie";

// railway
$lang_str["tag:railway"]="Spoorweg";
$lang_str["tag:railway=rail"]=array("Spoorbaan", "Spoorbanen");
$lang_str["tag:railway=tram"]=array("Trambaan", "Trambanen");
$lang_str["tag:railway=platform"]=array("Perron", "Perrons");

// real_ale
$lang_str["tag:real_ale"]="Real Ale aangeboden";

// religion
$lang_str["tag:religion"]="Religie";
$lang_str["tag:religion=christian"]="Christelijk";
$lang_str["tag:religion=buddhist"]="Boeddhistisch";
$lang_str["tag:religion=hindu"]="Hindoe";
$lang_str["tag:religion=jewish"]="Joods";
$lang_str["tag:religion=muslim"]="Moslim";
$lang_str["tag:religion=multifaith"]="Multireligieus";

// route
#$lang_str["tag:route"]="Route";
$lang_str["tag:route=train"]="Trein";
$lang_str["tag:route=railway"]="Spoorweg";
$lang_str["tag:route=rail"]="Spoorweg";
$lang_str["tag:route=light_rail"]="Sneltram";
$lang_str["tag:route=subway"]="Metro";
$lang_str["tag:route=tram"]="Tram";
$lang_str["tag:route=tram_bus"]="Tram en Bus";
$lang_str["tag:route=trolley"]="Trolley";
$lang_str["tag:route=trolleybus"]="Trolley";
$lang_str["tag:route=bus"]="Bus";
$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Veerboot";
$lang_str["tag:route=road"]="Straat";
$lang_str["tag:route=bicycle"]="Fiets";
$lang_str["tag:route=hiking"]="Wandel";
$lang_str["tag:route=mtb"]="Mountainbike";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="Soort route";

// shop
$lang_str["tag:shop"]="Winkel";

// sport
$lang_str["tag:sport"]="Sport";
$lang_str["tag:sport=9pin"]="Kegelen";
$lang_str["tag:sport=10pin"]="Bowling";
$lang_str["tag:sport=archery"]="Boogschieten";
$lang_str["tag:sport=athletics"]="Atletiek";
$lang_str["tag:sport=australian_football"]="Australisch voetbal";
$lang_str["tag:sport=baseball"]="Honkbal";
$lang_str["tag:sport=basketball"]="Basketbal";
$lang_str["tag:sport=beachvolleyball"]="Beachvolleybal";
$lang_str["tag:sport=boules"]="Petanque";
$lang_str["tag:sport=bowls"]="Bowls";
$lang_str["tag:sport=canoe"]="Kanovaren";
$lang_str["tag:sport=chess"]="Schaken";
$lang_str["tag:sport=climbing"]="Klimsport";
$lang_str["tag:sport=cricket"]="Cricket";
$lang_str["tag:sport=cricket_nets"]="Cricket oefenbaan";
$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Wielersport";
$lang_str["tag:sport=diving"]="Sportduiken";
$lang_str["tag:sport=dog_racing"]="Hondenrennen";
$lang_str["tag:sport=equestrian"]="Paardensport";
$lang_str["tag:sport=football"]="Football";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Turnen";
$lang_str["tag:sport=hockey"]="Hockey";
$lang_str["tag:sport=horse_racing"]="Paardenrennen";
$lang_str["tag:sport=korfball"]="Korfbal";
$lang_str["tag:sport=motor"]="Motorsport";
$lang_str["tag:sport=multi"]="Multi";
$lang_str["tag:sport=orienteering"]="Oriëntatieloop";
$lang_str["tag:sport=paddle_tennis"]="Paddle tennis";
$lang_str["tag:sport=paragliding"]="Paragliding";
$lang_str["tag:sport=pelota"]="Pelota";
$lang_str["tag:sport=racquet"]="Racquet";
$lang_str["tag:sport=rowing"]="Roeien";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Schietsport";
$lang_str["tag:sport=skating"]="Skaten";
$lang_str["tag:sport=skateboard"]="Skateboarden";
$lang_str["tag:sport=skiing"]="Skiën";
$lang_str["tag:sport=soccer"]="Voetbal";
$lang_str["tag:sport=swimming"]="Zwemmen";
$lang_str["tag:sport=table_tennis"]="Tafeltennis";
$lang_str["tag:sport=team_handball"]="Handbal";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Volleybal";

// tracks
$lang_str["tag:tracks"]="Sportbanen";
$lang_str["tag:tracks=single"]="Enkelvoudig";
$lang_str["tag:tracks=double"]="Dubbelvoudig";
$lang_str["tag:tracks=multiple"]="Meervoudig";

// vending
$lang_str["tag:vending"]="Automaat";

// voltage
$lang_str["tag:voltage"]="Voltage";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="Draden";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Website";
$tag_type["website"]=array("link");
