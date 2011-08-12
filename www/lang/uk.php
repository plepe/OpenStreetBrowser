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
$lang_str["general_info"]="Загальна інформація";
$lang_str["yes"]="yes";
$lang_str["no"]="no";
#$lang_str["save"]=array("Save");
#$lang_str["cancel"]=array("Cancel");
#$lang_str["longitude"]=array("Longitude", "Longitudes");
#$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(без імені)";
$lang_str["info_back"]="back to overview";
$lang_str["info_zoom"]="наблизити";
#$lang_str["nothing_found"]=array("nothing found");
$lang_str["loading"]="завантаження";
#$lang_str["more"]="more";

// Headings
$lang_str["head:general_info"]="Загальна інформація";
$lang_str["head:stops"]="Зупинки";
$lang_str["head:routes"]="Маршрути";
$lang_str["head:members"]="Members";
$lang_str["head:address"]="Адреси";
$lang_str["head:internal"]="OSM Internal";
$lang_str["head:housenumbers"]="Нумерація будинків";
$lang_str["head:roads"]="Дороги";
$lang_str["head:rails"]="Залізниця";
$lang_str["head:places"]="Місцевість";
$lang_str["head:borders"]="Кордони";
$lang_str["head:landuse"]="Землевикористання";
$lang_str["head:buildings"]="Будівлі";
$lang_str["head:pt"]="Громадський транспорт";
$lang_str["head:services"]="Services";
$lang_str["head:culture"]="Культура";
$lang_str["head:graves"]="Important Graves";
$lang_str["head:routing"]="Маршрутизація";
$lang_str["head:search"]="Пошук";
$lang_str["head:actions"]="Actions";
#$lang_str["head:location"]="Location";
#$lang_str["head:tags"]=array("Tag", "Tags");
#$lang_str["head:whats_here"]="What's here?";

$lang_str["action_browse"]="перегляд в OSM";
$lang_str["action_edit"]="редагувати в OSM";

#$lang_str["geo_click_pos"]=array("Click on your position on the map");
$lang_str["geo_set_pos"]="Встановити моє місцезнаходження";
$lang_str["geo_change_pos"]="Змінити моє місцезнаходження";

$lang_str["routing_type_car"]="Автомобільний";
$lang_str["routing_type_car_shortest"]="Автомобільний (найкоротший)";
$lang_str["routing_type_bicycle"]="Велосипедний";
$lang_str["routing_type_foot"]="Піший";
$lang_str["routing_type"]="Тип маршруту";
$lang_str["routing_distance"]="Відстань";
$lang_str["routing_time"]="Час";
$lang_str["routing_disclaimer"]="Routing: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Choose a category to browse map content or click on an object on the map for details";
$lang_str["list_leisure_sport_tourism"]="Дозвілля, Спорт та Туризм";

// Mapkey
$lang_str["map_key:head"]="Map key";
$lang_str["map_key:zoom"]="Zoom level";

$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:map_key"]="Map Key";
$lang_str["main:options"]="Options";
$lang_str["main:about"]="About";
$lang_str["main:donate"]="Donate";
$lang_str["main:licence"]="Map Data: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
$lang_str["main:permalink"]="Permalink";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Автозум";
$lang_str["help:autozoom"]="При виборі обєкту на мапі, точка огляду змінюється так, щоб вмістити весь обєкт. Масштаб при цьому теж може змінитись. З цією опцією ви можете вибрати один з наступних режимів:";
$lang_str["options:autozoom:pan"]="Плавно переміститись в обрану точку (ефектніше)";
$lang_str["options:autozoom:move"]="Моментально переміститись в обрану точку (швидше)";
$lang_str["options:autozoom:stay"]="Ніколи не змінювати точку огляду автоматично";

$lang_str["options:language_support"]="Підтримка мови";
$lang_str["help:language_support"]="You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.";
$lang_str["options:ui_lang"]="Мова інтерфейсу";
$lang_str["options:data_lang"]="Мова мапи";
$lang_str["lang:"]="Місцева мова";

$lang_str["overlay:data"]="Дані";
$lang_str["overlay:draggable"]="Маркери";


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

#$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:mapnik"]="Standard (Mapnik)";
#$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";

if(function_exists("lang"))
#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \\"%s\\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

// please finish this list, see list.php for full list of languages
#$lang_str["lang:de"]="German";
#$lang_str["lang:bg"]="Bulgarian";
#$lang_str["lang:cs"]="Czech";
#$lang_str["lang:en"]="English";
#$lang_str["lang:es"]="Spanish";
#$lang_str["lang:it"]="Italian";
#$lang_str["lang:fr"]="French";
#$lang_str["lang:uk"]="Ukrainian";
#$lang_str["lang:ru"]="Russian";
#$lang_str["lang:ja"]="Japanese";
#$lang_str["lang:hu"]="Hungarian";


// The following $lang_str are not defined in www/lang/en.php and might be 
// deprecated/mislocated/wrong:
$lang_str["search_field"]="Пошук...";
$lang_str["search_tip"]="наприклад 'Київ', 'Грінченка Миколи вулиця', 'post box near Hyde Park',...";
$lang_str["search_clear"]="Очистити поле пошуку";
$lang_str["result_no"]="нічого не знайдено";
$lang_str["search_process"]="пошук";
$lang_str["search_more"]="шукати далі";
$lang_str["search_results"]="Результати пошуку";
$lang_str["search_nominatim"]="search provided by";
$lang_str["list_"]="";
$lang_str["place=continent"]="Continent";
$lang_str["place=country"]="Country";
$lang_str["place=state"]="State";
$lang_str["place=region"]="Region";
$lang_str["place=county"]="County";
$lang_str["place=city_large"]="City, > 1 Mio Inhabitants";
$lang_str["place=city_medium"]="City, > 200.000 Inhabitants";
$lang_str["place=city"]="City";
$lang_str["place=town_large"]="Town, > 30.000 Inhabitants";
$lang_str["place=town"]="Town";
$lang_str["place=suburb"]="Suburb";
$lang_str["place=village"]="Village";
$lang_str["place=hamlet"]="Hamlet";
$lang_str["place=locality"]="Locality";
$lang_str["place=island"]="Island";
$lang_str["tag:admin_level=2"]="Country Border";
$lang_str["tag:admin_level=3"]="Divisions";
$lang_str["tag:admin_level=4"]="State Border";
$lang_str["tag:admin_level=5"]="Community Border";
$lang_str["tag:admin_level=6"]="County Border";
$lang_str["tag:admin_level=8"]="Town/Municipality Border";
$lang_str["tag:admin_level=10"]="Subdivisions of Cities";
$lang_str["sub_type=t3|type="]="";
$lang_str["landuse=park"]="Park";
$lang_str["landuse=education"]="Area of educational facilities";
$lang_str["landuse=tourism"]="Area of touristic facilities";
$lang_str["landuse=garden"]="Farms, Plantages, Gardens";
$lang_str["landuse=industrial"]="Industrial and Railway Areas";
$lang_str["landuse=sport"]="Areas of sport facilities";
$lang_str["landuse=cemetery"]="Cemeteries";
$lang_str["landuse=residental"]="Residental areas";
$lang_str["landuse=nature_reserve"]="Nature Reserves";
$lang_str["landuse=historic"]="Areas with historical value";
$lang_str["landuse=emergency"]="Areas of emergency facilities";
$lang_str["landuse=health"]="Areas of health facilities";
$lang_str["landuse=public"]="Areas for public services";
$lang_str["landuse=water"]="Water Areas";
$lang_str["landuse=natural|sub_type=t0"]="Woods and Forest";
$lang_str["landuse=natural|sub_type=t1"]="Wetland";
$lang_str["landuse=natural|sub_type=t2"]="Glaciers";
$lang_str["landuse=natural|sub_type=t3"]="Screes, Heaths";
$lang_str["landuse=natural|sub_type=t4"]="Mud";
$lang_str["landuse=natural|sub_type=t5"]="Beaches";
$lang_str["building=default"]="Buildings";
$lang_str["building=worship"]="Religious Buildings";
$lang_str["building=road_amenities"]="Facilities for Transportation (Stations, Terminals, Toll Booths, ...)";
$lang_str["building=nature_building"]="Natural Buildings (e.g. Barriers)";
$lang_str["building=industrial"]="Industrial Buildings";
$lang_str["building=education"]="Educational Buildings";
$lang_str["building=shop"]="Shops";
$lang_str["building=public"]="Public Buildings";
$lang_str["building=military"]="Military Buildings";
$lang_str["building=historic"]="Historical Buildings";
$lang_str["building=emergency"]="Buildings of emergency facilities";
$lang_str["building=health"]="Buildings of health services";
$lang_str["building=communication"]="Buildings for communication";
$lang_str["building=residential"]="Residential Buildings";
$lang_str["building=culture"]="Cultural Buildings";
$lang_str["building=tourism"]="Touristic Buildings";
$lang_str["building=sport"]="Buildings for sport activities";
$lang_str["housenumber"]="Housenumber";
$lang_str["tag:name"]=array("Name", "Namen");
$lang_str["tag:amenity"]="Amenity";
$lang_str["tag:place"]="Place";
$lang_str["tag:operator"]="Operator";
$lang_str["tag:population"]="Population";
$lang_str["tag:is_in"]="Is in";
$lang_str["tag:website"]="Website";
$lang_str["tag:address"]="Address";
$lang_str["tag:description"]="Description";
$lang_str["tag:note"]="Note";
$lang_str["tag:cuisine"]="Cuisine";
$lang_str["tag:old_name"]="Old Name(s)";
$lang_str["tag:food"]="Serves food";
$lang_str["tag:accomodation"]="Accomodation";
$lang_str["tag:real_ale"]="Real ale offered";
$lang_str["tag:route_type"]="Route type";
$lang_str["tag:network"]="Network";
$lang_str["tag:religion"]="Religion";
$lang_str["tag:domination"]="Domination";
$lang_str["tag:shop"]="Shop";
$lang_str["tag:vending"]="Vending";
$lang_str["tag:opening_hours"]="Opening hours";
$lang_str["tag:place=continent"]="Continent";
$lang_str["tag:place=country"]="Country";
$lang_str["tag:place=state"]="State";
$lang_str["tag:place=region"]="Region";
$lang_str["tag:place=county"]="County";
$lang_str["tag:place=city"]="City";
$lang_str["tag:place=town"]="Town";
$lang_str["tag:place=village"]="Village";
$lang_str["tag:place=hamlet"]="Hamlet";
$lang_str["tag:place=suburb"]="Suburb";
$lang_str["tag:place=locality"]="Locality";
$lang_str["tag:place=island"]="Island";
$lang_str["tag:route=train"]="Train";
$lang_str["tag:route=railway"]="Railway";
$lang_str["tag:route=rail"]="Railway";
$lang_str["tag:route=light_rail"]="Light Rail";
$lang_str["tag:route=subway"]="Subway";
$lang_str["tag:route=tram"]="Tram";
$lang_str["tag:route=trolley"]="Trolley";
$lang_str["tag:route=trolleybus"]="Trolley";
$lang_str["tag:route=bus"]="Bus";
$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Ferry";
$lang_str["tag:route=road"]="Road";
$lang_str["tag:route=bicycle"]="Bicycle";
$lang_str["tag:route=hiking"]="Hiking";
$lang_str["tag:route=mtb"]="Mountainbike";
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
$lang_str["tag:power=generator"]="Power Generator";
$lang_str["tag:power=line"]="Power Line";
$lang_str["tag:power=tower"]="Power Tower";
$lang_str["tag:power=pole"]="Power Pole";
$lang_str["tag:power=station"]="Power Station";
$lang_str["tag:power=sub_station"]="Power Substation";
$lang_str["tag:voltage"]="Voltage";
$lang_str["tag:wires"]="Wires";
$lang_str["tag:cables"]="Cables";
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
$lang_str["cuisine_regional"]="regional";
$lang_str["tag:amenity=cinema"]=array("Cinema", "Cinemas");
$lang_str["tag:amenity=restaurant"]=array("ресторан", "ресторан");
$lang_str["tag:amenity=pub"]=array("Pub", "Pubs");
$lang_str["tag:highway"]=array("Highway", "Highways");
$lang_str["tag:religion=christian"]="християнська";

