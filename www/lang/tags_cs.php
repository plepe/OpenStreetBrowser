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
$lang_str["tag:accomodation"]="Ubytování";

// address
$lang_str["tag:address"]="Adresa";

// addr:housenumber
$lang_str["tag:addr:housenumber"]="Číslo popisné";

// admin_level
$lang_str["tag:admin_level=2"]="Státní hranice";
#$lang_str["tag:admin_level=3"]="Divisions";
$lang_str["tag:admin_level=4"]="Region";
#$lang_str["tag:admin_level=5"]="Community Border";
$lang_str["tag:admin_level=6"]="Kraj";
$lang_str["tag:admin_level=8"]="Hranice obce";
$lang_str["tag:admin_level=10"]="Městské části";

// amenity
$lang_str["tag:amenity"]="Občanská vybavenost";
$lang_str["tag:amenity=cinema"]=array("Kino", "Kina");
$lang_str["tag:amenity=restaurant"]=array("Restaurace", "Restaurace");
$lang_str["tag:amenity=pub"]=array("Hospoda", "Hospody");

// building
$lang_str["tag:building=yes"]="Budovy";
$lang_str["tag:building=default"]="Budovy";
$lang_str["tag:building=worship"]="Náboženské budovy";
$lang_str["tag:building=road_amenities"]="Budovy související s dopravou (Stanice, Terminály, Budky pro výběr mýtného, ...)";
$lang_str["tag:building=nature_building"]="Přírodní budovy (např. Překážky)";
$lang_str["tag:building=industrial"]="Průmyslové budovy";
$lang_str["tag:building=education"]="Vzdělávací budovy";
$lang_str["tag:building=shop"]="Obchody";
$lang_str["tag:building=public"]="Veřejné budovy";
$lang_str["tag:building=military"]="Vojenské budovy";
$lang_str["tag:building=historic"]="Historické budovy";
$lang_str["tag:building=emergency"]="Budovy zařízení pohotovostních složek";
$lang_str["tag:building=health"]="Budovy zdravotní péče";
$lang_str["tag:building=communication"]="Komunikační budovy";
$lang_str["tag:building=residential"]="Obytné budovy";
$lang_str["tag:building=culture"]="Kulturní budovy";
$lang_str["tag:building=tourism"]="Turistické budovy";
$lang_str["tag:building=sport"]="Budovy sportovních aktivit";

// cables
$lang_str["tag:cables"]="Kabely";

// cuisine
$lang_str["tag:cuisine"]="Kuchyně";
$lang_str["tag:cuisine=regional"]="regionální";

// description
$lang_str["tag:description"]="Popis";

// domination
#$lang_str["tag:domination"]="Domination";

// food
$lang_str["tag:food"]="Podává jídlo";

// highway
$lang_str["tag:highway"]=array("Pozemní komunikace", "Pozemní komunikace");
$lang_str["tag:highway=motorway"]="Dálnice";
$lang_str["tag:highway=trunk"]="Rychlostní komunikace";
$lang_str["tag:highway=primary"]="Silnice I. třídy";
$lang_str["tag:highway=secondary"]="Silnice II. třídy";
$lang_str["tag:highway=tertiary"]="Silnice III. třídy";
$lang_str["tag:highway=minor"]="Vedlejší cesta";
$lang_str["tag:highway=service"]="Účelová komunikace";
$lang_str["tag:highway=pedestrian"]="Pěší zóna";
$lang_str["tag:highway=track"]="Lesní/Polní cesta";
$lang_str["tag:highway=path"]="Stezka";

// is_in
$lang_str["tag:is_in"]="Je v";

// landuse
$lang_str["tag:landuse=park"]="Park";
$lang_str["tag:landuse=education"]="Oblasti vzdělávacích zařízení";
$lang_str["tag:landuse=tourism"]="Oblasti turistických zařízení";
$lang_str["tag:landuse=garden"]="Farmy, Sady, Zahrady";
$lang_str["tag:landuse=industrial"]="Průmyslové a železniční oblasti";
$lang_str["tag:landuse=sport"]="Oblasti sportovních zařízení";
$lang_str["tag:landuse=cemetery"]="Hřbitovy";
$lang_str["tag:landuse=residental"]="Obytné oblasti";
$lang_str["tag:landuse=nature_reserve"]="Přírodní rezervace";
$lang_str["tag:landuse=historic"]="Oblasti historické hodnoty";
$lang_str["tag:landuse=emergency"]="Oblasti zařízení pohotovosti";
$lang_str["tag:landuse=health"]="Oblast zdravotnických zařízení";
$lang_str["tag:landuse=public"]="Oblast veřejných služeb";
$lang_str["tag:landuse=water"]="Vodní plochy";
// the following tags are deprecated
#$lang_str["tag:landuse=natural|sub_type=t0"]="Woods and Forest";
$lang_str["tag:landuse=natural|sub_type=t1"]="Mokřiny";
$lang_str["tag:landuse=natural|sub_type=t2"]="Ledovce";
#$lang_str["tag:landuse=natural|sub_type=t3"]="Screes, Heaths";
$lang_str["tag:landuse=natural|sub_type=t4"]="Bláto";
$lang_str["tag:landuse=natural|sub_type=t5"]="Pláže";

// leisure
$lang_str["tag:leisure=sports_centre"]="Sportovní centrum";
$lang_str["tag:leisure=golf_course"]="Golfové hiště";
$lang_str["tag:leisure=stadium"]="Stadion";
$lang_str["tag:leisure=track"]="Dráha";
$lang_str["tag:leisure=pitch"]="Hřiště";
$lang_str["tag:leisure=water_park"]="Aquapark";
#$lang_str["tag:leisure=marina"]="Marina";
$lang_str["tag:leisure=slipway"]="Slipway";
$lang_str["tag:leisure=fishing"]="Rybaření";
$lang_str["tag:leisure=nature_reserve"]="Přirodní rezervace";
#$lang_str["tag:leisure=park"]="Leisure Park";
$lang_str["tag:leisure=playground"]="Hřiště";
$lang_str["tag:leisure=garden"]="Zahrada";
#$lang_str["tag:leisure=common"]="Common";
$lang_str["tag:leisure=ice_rink"]="Kluziště";
$lang_str["tag:leisure=miniature_golf"]="Minigolf";
$lang_str["tag:leisure=swimming_pool"]="Bazén";
#$lang_str["tag:leisure=beach_resort"]="Beach Resort";
#$lang_str["tag:leisure=bird_hide"]="Bird Hide";
$lang_str["tag:leisure=sport"]="Jiný sport";

// name
$lang_str["tag:name"]=array("Jméno", "Jména");

// network
$lang_str["tag:network"]="Síť";

// note
$lang_str["tag:note"]="Poznámka";

// old_name
$lang_str["tag:old_name"]="Staré jméno(-a)";

// opening_hours
$lang_str["tag:opening_hours"]="Otevírací doba";

// operator
#$lang_str["tag:operator"]="Operator";

// place
$lang_str["tag:place"]="Místo";
$lang_str["tag:place=continent"]=array("Kontinent", "Kontinenty");
$lang_str["tag:place=country"]=array("Stát", "Stát");
#$lang_str["tag:place=state"]=array("State", "States");
$lang_str["tag:place=region"]=array("Region", "Regiony");
$lang_str["tag:place=county"]=array("Kraj", "Kraje");
$lang_str["tag:place=city"]=array("Město", "Města");
$lang_str["tag:place=town"]="Město";
$lang_str["tag:place=village"]=array("Vesnice", "Vesnice");
$lang_str["tag:place=suburb"]=array("Městská část", "Městské části");
$lang_str["tag:place=locality"]=array("Lokalita", "Lokality");
$lang_str["tag:place=island"]=array("Ostrov", "Ostrovy");
$lang_str["tag:place=islet"]=array("Ostrůvek", "Ostrůvky");
// the following tags are deprecated
$lang_str["tag:place=city;population>1000000"]=array("Město, > 1 Mil. obyvatel", "Města, > 1 Mil. obyvatel");
$lang_str["tag:place=city;population>200000"]=array("Město, > 200 000 obyvatel", "Města, > 200 000 obyvatel");
$lang_str["tag:place=town"]="Město";
$lang_str["tag:place=town;population>30000"]=array("Město, > 30 000 obyvatel", "Města, > 30 000 obyvatel");

// population
$lang_str["tag:population"]="Počet obyvatel";
#$tag_type["population"]=array("count");

// power
$lang_str["tag:power"]="Elektrická síť";
$lang_str["tag:power=generator"]="Elektrárna";
$lang_str["tag:power=line"]="Elektrické vedení";
$lang_str["tag:power=tower"]="Stožár elektrického vedení";
$lang_str["tag:power=pole"]="Sloup elektrického vedení";
$lang_str["tag:power=station"]="Rozvodna VVN/VN";
$lang_str["tag:power=sub_station"]="Trafostanice VN/NN";

// power_source
$lang_str["tag:power_source"]="Zdroj energie";
$lang_str["tag:power_source=biofuel"]="Biopalivo";
$lang_str["tag:power_source=oil"]="Ropa";
$lang_str["tag:power_source=coal"]="Uhlí";
$lang_str["tag:power_source=gas"]="Zemní plyn";
$lang_str["tag:power_source=waste"]="Odpad";
$lang_str["tag:power_source=hydro"]="Vodní";
$lang_str["tag:power_source=tidal"]="Příboj";
$lang_str["tag:power_source=wave"]="Vlny";
$lang_str["tag:power_source=geothermal"]="Geotermální energie";
$lang_str["tag:power_source=nuclear"]="Jádro";
$lang_str["tag:power_source=fusion"]="Fúze";
$lang_str["tag:power_source=wind"]="Vítr";
$lang_str["tag:power_source=photovoltaic"]="Solární články";
$lang_str["tag:power_source=solar-thermal"]="Solární kolektory";

// real_ale
#$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="Náboženství";
$lang_str["tag:religion=christian"]=array("křesťanství");

// route
$lang_str["tag:route=train"]="Vlak";
$lang_str["tag:route=railway"]="Železnice";
$lang_str["tag:route=rail"]="Železnice";
$lang_str["tag:route=light_rail"]="Rychlodráha";
$lang_str["tag:route=subway"]="Metro";
$lang_str["tag:route=tram"]="Tramvaj";
$lang_str["tag:route=trolley"]="Trolejbus";
$lang_str["tag:route=trolleybus"]="Trolejbus";
$lang_str["tag:route=bus"]="Autobus";
$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Trajekt";
$lang_str["tag:route=road"]="Cesta";
$lang_str["tag:route=bicycle"]="Kolo";
$lang_str["tag:route=hiking"]="Pěší turistika";
$lang_str["tag:route=mtb"]="Horské kolo";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="Typ cesty";

// shop
$lang_str["tag:shop"]="Obchod";

// sport
$lang_str["tag:sport"]="Sport";
#$lang_str["tag:sport=9pin"]="9pin Bowling";
#$lang_str["tag:sport=10pin"]="10pin Bowling";
$lang_str["tag:sport=archery"]="Lukostřelba";
$lang_str["tag:sport=athletics"]="Atletika";
#$lang_str["tag:sport=australian_football"]="Australian Football";
$lang_str["tag:sport=baseball"]="Baseball";
$lang_str["tag:sport=basketball"]="Basketbal";
$lang_str["tag:sport=beachvolleyball"]="Beachvolejbal";
#$lang_str["tag:sport=boules"]="Boules";
#$lang_str["tag:sport=bowls"]="Bowls";
$lang_str["tag:sport=canoe"]="Kánoe";
$lang_str["tag:sport=chess"]="Šachy";
$lang_str["tag:sport=climbing"]="Horolezectví";
$lang_str["tag:sport=cricket"]="Kriket";
#$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
#$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Cyklistika";
$lang_str["tag:sport=diving"]="Potápění";
$lang_str["tag:sport=dog_racing"]="Závody psů";
$lang_str["tag:sport=equestrian"]="Krasojezdectví";
$lang_str["tag:sport=football"]="Americký Fotbal";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Gymnastika";
$lang_str["tag:sport=hockey"]="Hokej";
$lang_str["tag:sport=horse_racing"]="Dostihy";
#$lang_str["tag:sport=korfball"]="Korfball";
#$lang_str["tag:sport=motor"]="Motor";
#$lang_str["tag:sport=multi"]="Multi";
$lang_str["tag:sport=orienteering"]="Orientační závod";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
#$lang_str["tag:sport=paragliding"]="Paragliding";
#$lang_str["tag:sport=pelota"]="Pelota";
#$lang_str["tag:sport=racquet"]="Racquet";
$lang_str["tag:sport=rowing"]="Veslování";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Střelba";
$lang_str["tag:sport=skating"]="Brusle";
$lang_str["tag:sport=skateboard"]="Skateboard";
$lang_str["tag:sport=skiing"]="Lyžování";
$lang_str["tag:sport=soccer"]="Fotbal";
$lang_str["tag:sport=swimming"]="Plavání";
$lang_str["tag:sport=table_tennis"]="Stolní tenis";
$lang_str["tag:sport=team_handball"]="Házená";
$lang_str["tag:sport=tennis"]="Tenis";
$lang_str["tag:sport=volleyball"]="Volejbal";

// vending
#$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Napětí";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="Dráty";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Webová stránka";
$tag_type["website"]=array("link");
// The following $lang_str were not defined in the English language file and might be deprecated or wrong:
$lang_str["tag:religion=buddhist"]=array("buddhismus");
$lang_str["tag:religion=hindu"]=array("hinduismus");
$lang_str["tag:religion=jewish"]=array("judaismus");
$lang_str["tag:religion=muslim"]=array("islám");
$lang_str["tag:religion=multifaith"]=array("více náboženství");
