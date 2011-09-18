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
$lang_str["tag:accomodation"]="Accomodation";

// address
$lang_str["tag:address"]="Address";

// addr:housenumber
$lang_str["tag:addr:housenumber"]=array("Housenumber", "Housenumbers");

// addr:interpolation
$lang_str["tag:addr:interpolation"]="Interpolated housenumbers";

// aeroway
$lang_str["tag:aeroway"]="Aeroway";
$lang_str["tag:aeroway=runway"]="Runway";
$lang_str["tag:aeroway=taxiway"]="Taxiway";

// admin_level
$lang_str["tag:admin_level=2"]="Country Border";
$lang_str["tag:admin_level=3"]="Divisions";
$lang_str["tag:admin_level=4"]="State Border";
$lang_str["tag:admin_level=5"]="Community Border";
$lang_str["tag:admin_level=6"]="County Border";
$lang_str["tag:admin_level=7"]="";
$lang_str["tag:admin_level=7.5"]="";
$lang_str["tag:admin_level=8"]="Town/Municipality Border";
$lang_str["tag:admin_level=10"]="Subdivisions of Cities";

// amenity
$lang_str["tag:amenity"]="Amenity";
$lang_str["tag:amenity=cinema"]=array("Cinema", "Cinemas");
$lang_str["tag:amenity=restaurant"]=array("Restaurant", "Restaurants");
$lang_str["tag:amenity=pub"]=array("Pub", "Pubs");

// barrier
$lang_str["tag:barrier"]=array("Barrier", "Barriers");
$lang_str["tag:barrier=city_wall"]=array("City wall", "City walls");
$lang_str["tag:barrier=wall"]=array("Wall", "Walls");
$lang_str["tag:barrier=retaining_wall"]=array("Retaining Wall", "Retaining Walls");
$lang_str["tag:barrier=fence"]=array("Fence", "Fences");
$lang_str["tag:barrier=hedge"]=array("Hedge", "Hedges");

// cables
$lang_str["tag:cables"]="Cables";

// cuisine
$lang_str["tag:cuisine"]="Cuisine";
$lang_str["tag:cuisine=regional"]="regional";

// description
$lang_str["tag:description"]="Description";

// domination
$lang_str["tag:domination"]="Domination";

// food
$lang_str["tag:food"]="Serves food";

// highway
$lang_str["tag:highway"]=array("Highway", "Highways");
$lang_str["tag:highway=motorway"]="Motorway";
$lang_str["tag:highway=motorway_link"]="Motorway Link";
$lang_str["tag:highway=trunk"]="Trunk Road";
$lang_str["tag:highway=trunk_link"]="Trunk Road Link";
$lang_str["tag:highway=primary"]="Primary Road";
$lang_str["tag:highway=primary_link"]="Primary Road Link";
$lang_str["tag:highway=secondary"]="Secondary Road";
$lang_str["tag:highway=tertiary"]="Tertiary Road";
$lang_str["tag:highway=minor"]="Minor Road";
$lang_str["tag:highway=road"]="Road";
$lang_str["tag:highway=residential"]="Residential Road";
$lang_str["tag:highway=unclassified"]="Unclassified Road";
$lang_str["tag:highway=service"]="Service Road";
$lang_str["tag:highway=pedestrian"]="Pedestrian Zone";
$lang_str["tag:highway=living_street"]="Living Street";
$lang_str["tag:highway=path"]="Path";
$lang_str["tag:highway=cycleway"]="Cycleway";
$lang_str["tag:highway=footway"]="Footway";
$lang_str["tag:highway=bridleway"]="Bridleway";
$lang_str["tag:highway=track"]="Track";
#$lang_str["tag:highway=path"]="Path";
$lang_str["tag:highway=steps"]="Steps";

// is_in
$lang_str["tag:is_in"]="Is in";

// leisure
$lang_str["tag:leisure=sports_centre"]="Sport Centre";
$lang_str["tag:leisure=golf_course"]="Golf Course";
$lang_str["tag:leisure=stadium"]="Stadium";
$lang_str["tag:leisure=track"]="Track";
$lang_str["tag:leisure=pitch"]="Pitche";
$lang_str["tag:leisure=water_park"]="Water Park";
$lang_str["tag:leisure=marina"]="Marina";
$lang_str["tag:leisure=slipway"]="Slipway";
$lang_str["tag:leisure=fishing"]="Fishing";
$lang_str["tag:leisure=nature_reserve"]="Nature Reserve";
$lang_str["tag:leisure=park"]="Leisure Park";
$lang_str["tag:leisure=playground"]="Playground";
$lang_str["tag:leisure=garden"]="Garden";
$lang_str["tag:leisure=common"]="Common";
$lang_str["tag:leisure=ice_rink"]="Ice Rink";
$lang_str["tag:leisure=miniature_golf"]="Miniature Golf";
$lang_str["tag:leisure=swimming_pool"]="Swimming Pool";
$lang_str["tag:leisure=beach_resort"]="Beach Resort";
$lang_str["tag:leisure=bird_hide"]="Bird Hide";
$lang_str["tag:leisure=sport"]="Other Sport";

// man_made
$lang_str["tag:man_made"]="Artificial structures";
$lang_str["tag:man_made=pipeline"]=array("Pipeline", "Pipelines");

// man_made - type
$lang_str["tag:type"]="Type";
$lang_str["tag:type=gas"]="Gas";
$lang_str["tag:type=heat"]="Heat";
$lang_str["tag:type=hot_water"]="Hot Water";
$lang_str["tag:type=oil"]="Oil";
$lang_str["tag:type=sewage"]="Sewage";
$lang_str["tag:type=water"]="Water";

// name
$lang_str["tag:name"]=array("Name", "Namen");

// network
$lang_str["tag:network"]="Network";

// note
$lang_str["tag:note"]="Note";

// old_name
$lang_str["tag:old_name"]="Old Name(s)";

// opening_hours
$lang_str["tag:opening_hours"]="Opening hours";

// operator
$lang_str["tag:operator"]="Operator";

// place
$lang_str["tag:place"]="Place";
$lang_str["tag:place=continent"]=array("Continent", "Continents");
$lang_str["tag:place=country"]=array("Country", "Countries");
$lang_str["tag:place=state"]=array("State", "States");
$lang_str["tag:place=region"]=array("Region", "Regions");
$lang_str["tag:place=county"]=array("County", "Counties");
$lang_str["tag:place=city"]=array("City", "Cities");
$lang_str["tag:place=town"]="Town";
$lang_str["tag:place=village"]=array("Village", "Villages");
$lang_str["tag:place=suburb"]=array("Suburb", "Suburbs");
$lang_str["tag:place=hamlet"]=array("Hamlet", "Hamlets");
$lang_str["tag:place=locality"]=array("Locality", "Localities");
$lang_str["tag:place=island"]=array("Island", "Islands");
$lang_str["tag:place=islet"]=array("Islet", "Islets");

// population
$lang_str["tag:population"]="Population";
$tag_type["population"]=array("count");

// power
$lang_str["tag:power"]="Power";
$lang_str["tag:power=generator"]="Power Generator";
$lang_str["tag:power=line"]="Power Line";
$lang_str["tag:power=minor_line"]="Minor Power Line";
$lang_str["tag:power=tower"]="Power Tower";
$lang_str["tag:power=pole"]="Power Pole";
$lang_str["tag:power=station"]="Power Station";
$lang_str["tag:power=sub_station"]="Power Substation";

// power_source
$lang_str["tag:power_source"]="Power source";
$lang_str["tag:power_source=biofuel"]="Biofuel";
$lang_str["tag:power_source=oil"]="Oil";
$lang_str["tag:power_source=coal"]="Coal";
$lang_str["tag:power_source=gas"]="Gas";
$lang_str["tag:power_source=waste"]="Waste";
$lang_str["tag:power_source=hydro"]="Hydro";
$lang_str["tag:power_source=tidal"]="Tidal";
$lang_str["tag:power_source=wave"]="Wave";
$lang_str["tag:power_source=geothermal"]="Geothermal";
$lang_str["tag:power_source=nuclear"]="Nuclear";
$lang_str["tag:power_source=fusion"]="Fusion";
$lang_str["tag:power_source=wind"]="Wind";
$lang_str["tag:power_source=photovoltaic"]="Photovoltaic";
$lang_str["tag:power_source=solar-thermal"]="Solar Thermal";

// railway
$lang_str["tag:railway"]="Railway";
$lang_str["tag:railway=rail"]=array("Rail Track", "Rail Tracks");
$lang_str["tag:railway=tram"]=array("Tram Track", "Tram Tracks");
$lang_str["tag:railway=platform"]=array("Platform", "Platforms");

// real_ale
$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="Religion";
$lang_str["tag:religion=christian"]="christian";
$lang_str["tag:religion=buddhist"]="buddhist";
$lang_str["tag:religion=hindu"]="hindu";
$lang_str["tag:religion=jewish"]="jewish";
$lang_str["tag:religion=muslim"]="muslim";
$lang_str["tag:religion=multifaith"]="multifaith";

// route
$lang_str["tag:route"]="Route";
$lang_str["tag:route=train"]="Train";
$lang_str["tag:route=railway"]="Railway";
$lang_str["tag:route=rail"]="Railway";
$lang_str["tag:route=light_rail"]="Light Rail";
$lang_str["tag:route=subway"]="Subway";
$lang_str["tag:route=tram"]="Tram";
$lang_str["tag:route=tram_bus"]="Tram and Bus";
$lang_str["tag:route=trolley"]="Trolley";
$lang_str["tag:route=trolleybus"]="Trolley";
$lang_str["tag:route=bus"]="Bus";
$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Ferry";
$lang_str["tag:route=road"]="Road";
$lang_str["tag:route=bicycle"]="Bicycle";
$lang_str["tag:route=hiking"]="Hiking";
$lang_str["tag:route=mtb"]="Mountainbike";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="Route type";

// shop
$lang_str["tag:shop"]="Shop";

// sport
$lang_str["tag:sport"]="Sport";
$lang_str["tag:sport=9pin"]="9pin Bowling";
$lang_str["tag:sport=10pin"]="10pin Bowling";
$lang_str["tag:sport=archery"]="Archery";
$lang_str["tag:sport=athletics"]="Athletics";
$lang_str["tag:sport=australian_football"]="Australian Football";
$lang_str["tag:sport=baseball"]="Baseball";
$lang_str["tag:sport=basketball"]="Basketball";
$lang_str["tag:sport=beachvolleyball"]="Beachvolleyball";
$lang_str["tag:sport=boules"]="Boules";
$lang_str["tag:sport=bowls"]="Bowls";
$lang_str["tag:sport=canoe"]="Canoe";
$lang_str["tag:sport=chess"]="Chess";
$lang_str["tag:sport=climbing"]="Climbing";
$lang_str["tag:sport=cricket"]="Cricket";
$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Cycling";
$lang_str["tag:sport=diving"]="Diving";
$lang_str["tag:sport=dog_racing"]="Dog Racing";
$lang_str["tag:sport=equestrian"]="Equestrian";
$lang_str["tag:sport=football"]="Football";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Gymnastics";
$lang_str["tag:sport=hockey"]="Hockey";
$lang_str["tag:sport=horse_racing"]="Horse Racing";
$lang_str["tag:sport=korfball"]="Korfball";
$lang_str["tag:sport=motor"]="Motor";
$lang_str["tag:sport=multi"]="Multi";
$lang_str["tag:sport=orienteering"]="Orienteering";
$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
$lang_str["tag:sport=paragliding"]="Paragliding";
$lang_str["tag:sport=pelota"]="Pelota";
$lang_str["tag:sport=racquet"]="Racquet";
$lang_str["tag:sport=rowing"]="Rowing";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Shooting";
$lang_str["tag:sport=skating"]="Skating";
$lang_str["tag:sport=skateboard"]="Skateboard";
$lang_str["tag:sport=skiing"]="Skiing";
$lang_str["tag:sport=soccer"]="Soccer";
$lang_str["tag:sport=swimming"]="Swimming";
$lang_str["tag:sport=table_tennis"]="Table Tennis";
$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Volleyball";

// tracks
$lang_str["tag:tracks"]="Tracks";
$lang_str["tag:tracks=single"]="Single";
$lang_str["tag:tracks=double"]="Double";
$lang_str["tag:tracks=multiple"]="Multiple";

// vending
$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Voltage";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="Wires";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Website";
$tag_type["website"]=array("link");
