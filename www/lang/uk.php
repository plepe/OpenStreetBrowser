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
#$lang_str["ok"]="Ok";
#$lang_str["save"]="Save";
#$lang_str["cancel"]="Cancel";
#$lang_str["longitude"]=array("Longitude", "Longitudes");
#$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(без імені)";
$lang_str["info_back"]="back to overview";
$lang_str["info_zoom"]="наблизити";
#$lang_str["nothing_found"]="nothing found";
$lang_str["loading"]="завантаження";
#$lang_str["more"]="more";
#$lang_str["unnamed"]="unnamed";
$lang_str["zoom"]="Zoom level";

// Headings
$lang_str["head:general_info"]="Загальна інформація";
$lang_str["head:stops"]="Зупинки";
$lang_str["head:routes"]="Маршрути";
$lang_str["head:members"]="Members";
$lang_str["head:address"]="Адреси";
$lang_str["head:internal"]="OSM Internal";
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

#$lang_str["geo_click_pos"]="Click on your position on the map";
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

$lang_str["grave_is_on"]="Grave is on";

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
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";

#$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:mapnik"]="Standard (Mapnik)";
#$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";

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
#$lang_str["lang:nl"]="Dutch";
#$lang_str["lang:ast"]="Asturian";
// The following $lang_str were not defined in the English language file and might be deprecated or wrong:
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
$lang_str["tag:place=hamlet"]="Hamlet";
$lang_str["cuisine_regional"]="regional";
