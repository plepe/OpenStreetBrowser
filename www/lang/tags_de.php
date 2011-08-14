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
#$lang_str["tag:accomodation"]="Accomodation";

// address
$lang_str["tag:address"]="Adresse";

// addr:housenumber
$lang_str["tag:addr:housenumber"]="Hausnummer";

// admin_level
$lang_str["tag:admin_level=2"]="Landesgrenze";
#$lang_str["tag:admin_level=3"]="Divisions";
#$lang_str["tag:admin_level=4"]="State Border";
#$lang_str["tag:admin_level=5"]="Community Border";
#$lang_str["tag:admin_level=6"]="County Border";
#$lang_str["tag:admin_level=8"]="Town/Municipality Border";
#$lang_str["tag:admin_level=10"]="Subdivisions of Cities";

// amenity
#$lang_str["tag:amenity"]="Amenity";
$lang_str["tag:amenity=cinema"]=array("Kino", "Kinos");
$lang_str["tag:amenity=restaurant"]=array("Restaurant", "Restaurants");
$lang_str["tag:amenity=pub"]=array("Pub", "Pubs");

// building
$lang_str["tag:building=yes"]="Gebäude";
$lang_str["tag:building=default"]="Gebäude";
$lang_str["tag:building=worship"]="Sakralbauten";
#$lang_str["tag:building=road_amenities"]="Facilities for Transportation (Stations, Terminals, Toll Booths, ...)";
#$lang_str["tag:building=nature_building"]="Natural Buildings (e.g. Barriers)";
$lang_str["tag:building=industrial"]="Industriegebäude";
$lang_str["tag:building=education"]="Schulgebäude";
$lang_str["tag:building=shop"]="Geschäfte";
$lang_str["tag:building=public"]="Öffentliche Gebäude";
$lang_str["tag:building=military"]="Militärgebäude";
$lang_str["tag:building=historic"]="Historische Gebäude";
#$lang_str["tag:building=emergency"]="Buildings of emergency facilities";
#$lang_str["tag:building=health"]="Buildings of health services";
#$lang_str["tag:building=communication"]="Buildings for communication";
$lang_str["tag:building=residential"]="Wohnhäuser";
#$lang_str["tag:building=culture"]="Cultural Buildings";
#$lang_str["tag:building=tourism"]="Touristic Buildings";
#$lang_str["tag:building=sport"]="Buildings for sport activities";

// cables
$lang_str["tag:cables"]="Stromkabel";

// cuisine
$lang_str["tag:cuisine"]="Küche";
$lang_str["tag:cuisine=regional"]="regional";

// description
$lang_str["tag:description"]="Beschreibung";

// domination
#$lang_str["tag:domination"]="Domination";

// food
#$lang_str["tag:food"]="Serves food";

// highway
$lang_str["tag:highway"]=array("Straße", "Straßen");
$lang_str["tag:highway=motorway"]="Autobahn";
$lang_str["tag:highway=trunk"]="Fernstraße";
#$lang_str["tag:highway=primary"]="Primary Road";
#$lang_str["tag:highway=secondary"]="Secondary Road";
#$lang_str["tag:highway=tertiary"]="Tertiary Road";
#$lang_str["tag:highway=minor"]="Minor Road";
#$lang_str["tag:highway=service"]="Service Road";
$lang_str["tag:highway=pedestrian"]="Fußgängerzone";
#$lang_str["tag:highway=track"]="Track";
#$lang_str["tag:highway=path"]="Path";

// is_in
$lang_str["tag:is_in"]="ist in";

// landuse
$lang_str["tag:landuse=park"]="Park";
#$lang_str["tag:landuse=education"]="Area of educational facilities";
#$lang_str["tag:landuse=tourism"]="Area of touristic facilities";
#$lang_str["tag:landuse=garden"]="Farms, Plantages, Gardens";
#$lang_str["tag:landuse=industrial"]="Industrial and Railway Areas";
#$lang_str["tag:landuse=sport"]="Areas of sport facilities";
#$lang_str["tag:landuse=cemetery"]="Cemeteries";
#$lang_str["tag:landuse=residental"]="Residental areas";
$lang_str["tag:landuse=nature_reserve"]="Naturschutzgebiet";
#$lang_str["tag:landuse=historic"]="Areas with historical value";
#$lang_str["tag:landuse=emergency"]="Areas of emergency facilities";
#$lang_str["tag:landuse=health"]="Areas of health facilities";
#$lang_str["tag:landuse=public"]="Areas for public services";
$lang_str["tag:landuse=water"]="Gewässer";
// the following tags are deprecated
$lang_str["tag:landuse=natural|sub_type=t0"]="Wald und Forst";
$lang_str["tag:landuse=natural|sub_type=t1"]="Moor";
$lang_str["tag:landuse=natural|sub_type=t2"]="Gletscher";
$lang_str["tag:landuse=natural|sub_type=t3"]="Geröll, Heide";
$lang_str["tag:landuse=natural|sub_type=t4"]="Matsch";
$lang_str["tag:landuse=natural|sub_type=t5"]="Strände";

// leisure
$lang_str["tag:leisure=sports_centre"]="Sportzentrum";
$lang_str["tag:leisure=golf_course"]="Golfplatz";
$lang_str["tag:leisure=stadium"]="Stadion";
$lang_str["tag:leisure=track"]="Laufbahn";
$lang_str["tag:leisure=pitch"]="Spielfeld";
$lang_str["tag:leisure=water_park"]="Schwimmbad";
$lang_str["tag:leisure=marina"]="Jachthafen";
$lang_str["tag:leisure=slipway"]="Slipanlage";
$lang_str["tag:leisure=fishing"]="Angeln";
$lang_str["tag:leisure=nature_reserve"]="Naturschutzgebiet";
$lang_str["tag:leisure=park"]="Freizeitpark";
$lang_str["tag:leisure=playground"]="Spielplatz";
$lang_str["tag:leisure=garden"]="Garten";
#$lang_str["tag:leisure=common"]="Common";
$lang_str["tag:leisure=ice_rink"]="Eislaufbahn";
$lang_str["tag:leisure=miniature_golf"]="Minigolf";
$lang_str["tag:leisure=swimming_pool"]="Schwimmbecken";
$lang_str["tag:leisure=beach_resort"]="Seebad";
$lang_str["tag:leisure=bird_hide"]="Vogelbeobachtungshütte";
$lang_str["tag:leisure=sport"]="Anderer Sport";

// name
$lang_str["tag:name"]=array("Name", "Namen");

// network
$lang_str["tag:network"]="Netzwerk";

// note
$lang_str["tag:note"]="Anmerkung";

// old_name
$lang_str["tag:old_name"]="Alte Bezeichnung(en)";

// opening_hours
$lang_str["tag:opening_hours"]="Öffnungszeiten";

// operator
$lang_str["tag:operator"]="Betreiber";

// place
$lang_str["tag:place"]="Ort";
$lang_str["tag:place=continent"]=array("Kontinent", "Kontinente");
$lang_str["tag:place=country"]=array("Land", "Länder");
$lang_str["tag:place=state"]=array("Staat", "Staaten");
$lang_str["tag:place=region"]=array("Region", "Regionen");
$lang_str["tag:place=county"]=array("Bezirk", "Bezirke");
$lang_str["tag:place=city"]=array("Stadt", "Städte");
$lang_str["tag:place=town"]=array("Kleinstadt", "Kleinstädte");
$lang_str["tag:place=village"]=array("Dorf", "Dörfer");
$lang_str["tag:place=suburb"]=array("Vorort", "Vororte");
$lang_str["tag:place=locality"]=array("Lokalität", "Lokalitäten");
$lang_str["tag:place=island"]=array("Insel", "Inseln");
$lang_str["tag:place=islet"]=array("Inselchen", "Inselchen");
// the following tags are deprecated
#$lang_str["tag:place=city;population>1000000"]=array("City, > 1 Mio Inhabitants", "Cities, > 1 Mio Inhabitants");
#$lang_str["tag:place=city;population>200000"]=array("City, > 200.000 Inhabitants", "Cities, > 200.000 Inhabitants");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=town;population>30000"]=array("Town, > 30.000 Inhabitants", "Towns, > 30.000 Inhabitants");

// population
$lang_str["tag:population"]="Einwohner";
$tag_type["population"]=array("count");

// power
$lang_str["tag:power"]="Strom";
$lang_str["tag:power=generator"]="Kraftwerk";
$lang_str["tag:power=line"]="Stromleitung";
$lang_str["tag:power=tower"]="Freileitungsmast";
$lang_str["tag:power=pole"]="Strommast";
$lang_str["tag:power=station"]="Umspannwerk";
$lang_str["tag:power=sub_station"]="Umspannstation";

// power_source
$lang_str["tag:power_source"]="Energiequelle";
$lang_str["tag:power_source=biofuel"]="Bioethanol";
$lang_str["tag:power_source=oil"]="Erdöl";
$lang_str["tag:power_source=coal"]="Kohle";
$lang_str["tag:power_source=gas"]="Gas";
$lang_str["tag:power_source=waste"]="Abfall";
$lang_str["tag:power_source=hydro"]="Wasser";
$lang_str["tag:power_source=tidal"]="Gezeiten";
$lang_str["tag:power_source=wave"]="Wellen";
$lang_str["tag:power_source=geothermal"]="Geothermisch";
$lang_str["tag:power_source=nuclear"]="Nuklear";
$lang_str["tag:power_source=fusion"]="Fusion";
$lang_str["tag:power_source=wind"]="Wind";
$lang_str["tag:power_source=photovoltaic"]="Photovoltaik";
$lang_str["tag:power_source=solar-thermal"]="Solarthermisch";

// real_ale
#$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="Religion";
$lang_str["tag:religion=christian"]=array("christlich");

// route
$lang_str["tag:route=train"]="Zug";
$lang_str["tag:route=railway"]="Eisenbahn";
$lang_str["tag:route=rail"]="Eisenbahn";
$lang_str["tag:route=light_rail"]="Stadtbahn";
$lang_str["tag:route=subway"]="U-Bahn";
$lang_str["tag:route=tram"]="Straßenbahn";
$lang_str["tag:route=trolley"]="O-Bus";
$lang_str["tag:route=trolleybus"]="O-Bus";
$lang_str["tag:route=bus"]="Bus";
$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Fähre";
$lang_str["tag:route=road"]="Straße";
$lang_str["tag:route=bicycle"]="Fahrrad";
$lang_str["tag:route=hiking"]="Wandern";
$lang_str["tag:route=mtb"]="Mountainbike";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="Routentyp";

// shop
$lang_str["tag:shop"]="Geschäft";

// sport
$lang_str["tag:sport"]="Sport";
$lang_str["tag:sport=9pin"]="Kegeln";
$lang_str["tag:sport=10pin"]="Bowling";
$lang_str["tag:sport=archery"]="Bogenschießen";
$lang_str["tag:sport=athletics"]="Leichtathletik";
#$lang_str["tag:sport=australian_football"]="Australian Football";
$lang_str["tag:sport=baseball"]="Baseball";
$lang_str["tag:sport=basketball"]="Basketball";
$lang_str["tag:sport=beachvolleyball"]="Beachvolleyball";
$lang_str["tag:sport=boules"]="Boule";
$lang_str["tag:sport=bowls"]="Bowls";
$lang_str["tag:sport=canoe"]="Kanu";
$lang_str["tag:sport=chess"]="Schach";
$lang_str["tag:sport=climbing"]="Klettern";
$lang_str["tag:sport=cricket"]="Cricket";
#$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Radsport";
$lang_str["tag:sport=diving"]="Tauchen";
$lang_str["tag:sport=dog_racing"]="Hunderennen";
$lang_str["tag:sport=equestrian"]="Pferdesport";
$lang_str["tag:sport=football"]="Fußball";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Turnen";
$lang_str["tag:sport=hockey"]="Hockey";
$lang_str["tag:sport=horse_racing"]="Pferderennen";
$lang_str["tag:sport=korfball"]="Korfball";
$lang_str["tag:sport=motor"]="Motorsport";
#$lang_str["tag:sport=multi"]="Multi";
$lang_str["tag:sport=orienteering"]="Orientierungslauf";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
$lang_str["tag:sport=paragliding"]="Paragliding";
$lang_str["tag:sport=pelota"]="Pelota";
#$lang_str["tag:sport=racquet"]="Racquet";
$lang_str["tag:sport=rowing"]="Rudern";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Schießen";
$lang_str["tag:sport=skating"]="Skating";
$lang_str["tag:sport=skateboard"]="Skateboard";
$lang_str["tag:sport=skiing"]="Ski";
$lang_str["tag:sport=soccer"]="Fußball";
$lang_str["tag:sport=swimming"]="Schwimmen";
$lang_str["tag:sport=table_tennis"]="Tischtennis";
$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Volleyball";

// vending
#$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Spannung";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="Kabel";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Webseite";
$tag_type["website"]=array("link");





