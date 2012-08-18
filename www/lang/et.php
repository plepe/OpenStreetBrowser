<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Eesti"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Üldinfo";
$lang_str["yes"]="jah";
$lang_str["no"]="ei";
$lang_str["ok"]="Ok";
$lang_str["save"]="Salvesta";
$lang_str["saved"]="Salvestatud"; // for dialog boxes confirming saving
$lang_str["cancel"]="Loobu";
$lang_str["show"]="Näita";
$lang_str["edit"]="Muuda";
$lang_str["delete"]="Kustuta";
$lang_str["history"]="Ajalugu";
$lang_str["choose"]="Vali";
$lang_str["help"]="Abi";
$lang_str["longitude"]=array("Pikkuskraad", "Pikkuskraadid");
$lang_str["latitude"]=array("Laiuskraad", "Laiuskraadid");
$lang_str["noname"]="(nimeta)";
$lang_str["info_back"]="tagasi ülevaatesse";
$lang_str["info_zoom"]="suurenda";
$lang_str["nothing_found"]="ei leitud midagi";
$lang_str["list:zoom_for_obs"]="Suurenda, et näha vähem olulisi objekte";
$lang_str["loading"]="Laen...";
$lang_str["more"]="lisaks";
$lang_str["source"]="Allikas";
$lang_str["unnamed"]="nimeta";
$lang_str["zoom"]="Suurendusaste";
#$lang_str["no_message"]=array("no message", "no messages");
$lang_str["ad"]=array("Reklaam", "Reklaamid");

// Headings
$lang_str["head:general_info"]="Üldinfo";
$lang_str["head:stops"]="Peatused";
$lang_str["head:routes"]="Teed";
$lang_str["head:members"]="Liikmed";
$lang_str["head:address"]="Aadressid";
#$lang_str["head:internal"]="OSM Internal";
$lang_str["head:services"]="Teenused";
$lang_str["head:culture"]="Kultuur";
$lang_str["head:routing"]="Teekond";
$lang_str["head:search"]="Otsing";
$lang_str["head:actions"]=array("Tegevus", "Tegevused");
$lang_str["head:location"]="Asukoht";
$lang_str["head:tags"]=array("Silt", "Sildid");
$lang_str["head:whats_here"]="Mis siin on?";

$lang_str["action_browse"]="sirvi OSM-is";
$lang_str["action_edit"]="redigeeri OSM-is";

$lang_str["geo_click_pos"]="Kliki kaardil oma asukohale";
$lang_str["geo_set_pos"]="Sea minu asukoht";
$lang_str["geo_change_pos"]="Muuda minu asukohta";

$lang_str["routing_type_car"]="Auto";
$lang_str["routing_type_car_shortest"]="Auto (Lühim)";
$lang_str["routing_type_bicycle"]="Jalgratas";
$lang_str["routing_type_foot"]="Jalgsi";
$lang_str["routing_type"]="Teekonna liik";
$lang_str["routing_distance"]="Vahemaa";
$lang_str["routing_time"]="Aeg";

$lang_str["list_info"]="Klõpsa kategooriale, et sirvida kaardi sisu või klõpsa kaardi objektile üksikasjade nägemiseks";
$lang_str["list_leisure_sport_tourism"]="Vaba aeg, sport ja turism";

// Mapkey


$lang_str["main:help"]="Abi";
$lang_str["main:options"]="Valikud";
$lang_str["main:about"]="Teave rakenduse kohta";
$lang_str["main:donate"]="Anneta";
#$lang_str["main:licence"]="Map Data: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
$lang_str["main:permalink"]="Püsilink";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Automaatsuurenduse käitumine";
#$lang_str["help:autozoom"]="When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.";
#$lang_str["options:autozoom:pan"]="Pan to current object (nicer)";
#$lang_str["options:autozoom:move"]="Move to current object (faster)";
#$lang_str["options:autozoom:stay"]="Never change viewport automatically";

$lang_str["options:language_support"]="Keeletugi";
#$lang_str["help:language_support"]="You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.";
$lang_str["options:ui_lang"]="Kasutajaliidese keel";
$lang_str["options:data_lang"]="Andmete keel";
$lang_str["lang:"]="Kohalik keel";
$lang_str["lang:auto"]="Sama mis kasutajaliidesel";

$lang_str["overlay:data"]="Andmed";
#$lang_str["overlay:draggable"]="Markers";

$lang_str["user:no_auth"]="Vale kasutajatunnus või parool";
$lang_str["user:login_text"]="Logi sisse OpenStreetBrowserisse:";
$lang_str["user:create_user"]="Loo uus kasutaja:";
$lang_str["user:username"]="Kasutajatunnus";
$lang_str["user:email"]="E-posti aadress";
$lang_str["user:password"]="Parool";
$lang_str["user:password_verify"]="Parool uuesti";
$lang_str["user:old_password"]="Vana parool";
$lang_str["user:no_username"]="Puudub kasutajatunnus.";
$lang_str["user:password_no_match"]="Paroolid ei ühti.";
$lang_str["user:full_name"]="Täisnimi";
$lang_str["user:user_exists"]="Kasutajatunnus on juba kasutusel";
$lang_str["user:login"]="Logi sisse";
$lang_str["user:logged_in_as"]="Sisse logitud nimega";
$lang_str["user:logout"]="Logi välja";

$lang_str["attention"]="Tähelepanu:";
#$lang_str["error"]="An error occured: ";
$lang_str["error:not_logged_in"]="sa ei ole sisse logitud";

$lang_str["category"]=array("Kategooria", "Kategooriad");
#$lang_str["more_categories"]="More categories";
#$lang_str["category:status"]="Status";
#$lang_str["category:data_status"]="Status";
#$lang_str["category:old_version"]="A new version of this category is being prepared.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

#$lang_str["category:new_rule"]="New Rule";
#$lang_str["category_rule_tag:match"]="Match";
#$lang_str["category_rule_tag:description"]="Description";
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";
#$lang_str["category:sub_category"]=array("Sub-category", "Sub-categories");

#$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:osb_light"]="OpenStreetBrowser (pale)";
#$lang_str["basemap:mapnik"]="Standard (Mapnik)";
#$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";
