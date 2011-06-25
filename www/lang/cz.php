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
$lang_str["general_info"]="Základní informace";
$lang_str["yes"]="ano";
$lang_str["no"]="ne";
$lang_str["save"]=array("Uložit");
$lang_str["cancel"]=array("Zrušit");
$lang_str["longitude"]=array("F", "Zeměpisná délka", "Zeměpisné délky");
$lang_str["latitude"]=array("F", "Zeměpisná šířka", "Zeměpisné šířky");
$lang_str["noname"]="(bezejmenný)";
$lang_str["info_back"]="zpět na přehled";
$lang_str["info_zoom"]="přiblížení";
$lang_str["nothing_found"]=array("nic nenalezeno");
$lang_str["loading"]=array("Načítání...");
#$lang_str["more"]="more";

// Headings
$lang_str["head:general_info"]="Základní informace";
$lang_str["head:stops"]="Zastávky";
$lang_str["head:routes"]="Linky";
$lang_str["head:members"]="Členové";
$lang_str["head:address"]="Adresa";
#$lang_str["head:internal"]="OSM Internal";
$lang_str["head:wikipedia"]="Wikipedie";
$lang_str["head:housenumbers"]="Čísla domů";
$lang_str["head:roads"]="Cesty";
$lang_str["head:rails"]="Železnice";
$lang_str["head:places"]="Místa";
$lang_str["head:borders"]="Hranice";
$lang_str["head:landuse"]="Využití krajiny";
$lang_str["head:buildings"]="Budovy";
$lang_str["head:pt"]="Veřejná doprava";
$lang_str["head:services"]="Služby";
$lang_str["head:culture"]="Kultura";
#$lang_str["head:graves"]="Important Graves";
$lang_str["head:routing"]="Hledání trasy";
$lang_str["head:search"]="Vyhledávání";
$lang_str["head:actions"]=array("F", "Akce", "Akce");
$lang_str["head:location"]="Umístění";
#$lang_str["head:tags"]=array("Tag", "Tags");
#$lang_str["head:whats_here"]="What's here?";

$lang_str["action_browse"]="prohlížet v OSM";
$lang_str["action_edit"]="editovat v OSM";

$lang_str["geo_click_pos"]=array("Klikněte na vaši pozici na mapě");
$lang_str["geo_set_pos"]="Nastavit moji pozici";
$lang_str["geo_change_pos"]="Změnit moji pozici";

$lang_str["routing_type_car"]="Auto";
$lang_str["routing_type_car_shortest"]="Auto (Nejkratší)";
$lang_str["routing_type_bicycle"]="Kolo";
$lang_str["routing_type_foot"]="Chodec";
$lang_str["routing_type"]="Typ trasy";
$lang_str["routing_distance"]="Vzdálenost";
$lang_str["routing_time"]="Čas";
$lang_str["routing_disclaimer"]="Hledání trasy: (c) by Cloudmade";

$lang_str["list_info"]="Vyber kategorii pro prohlížení obsahu mapy nebo klikni na nějaký objekt na mapě pro detaiy";
$lang_str["list_leisure_sport_tourism"]="Odpočinek, Sport and Turismus";

// Mapkey
$lang_str["map_key:head"]="Legenda";
$lang_str["map_key:zoom"]="Stupeň přiblížení";

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:map_key"]="Legenda";
$lang_str["main:options"]="Nastavení";
#$lang_str["main:about"]="About";
$lang_str["main:donate"]="Sponzorovat";
#$lang_str["main:licence"]="Map Data: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
#$lang_str["main:permalink"]="Permalink";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

$lang_str["start:choose"]=array("Zvol pohled na mapu");
$lang_str["start:geolocation"]=array("získat souřadnice");
$lang_str["start:lastview"]=array("minulý pohled");
$lang_str["start:savedview"]=array("minulý trvalý odkaz (permalink)");
#$lang_str["start:startnormal"]=array("keep view");
#$lang_str["start:remember"]=array("remember decision");
$lang_str["start:edit"]=array("editovat...");

#$lang_str["options:autozoom"]=array("Autozoom behaviour");
#$lang_str["help:autozoom"]=array("When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.");
#$lang_str["options:autozoom:pan"]=array("Pan to current object (nicer)");
#$lang_str["options:autozoom:move"]=array("Move to current object (faster)");
#$lang_str["options:autozoom:stay"]=array("Never change viewport automatically");

$lang_str["options:language_support"]=array("Jazyková podpora");
$lang_str["help:language_support"]=array("Touto volbou můžeš vybrat své preferované jazyky. První volba změní jazyk uživatelského rozhraní. Druhá volba změní jazyk dat. Popis mnoha geografických objektů byl přeložen do různých jazyků. Jestliže překlad není dostupný nebo byl vybrán \"místní jazyk\" , popis bude zobrazen v hlavním jazyku objektu.");
$lang_str["options:ui_lang"]=array("Jazyk rozhraní");
$lang_str["options:data_lang"]=array("Jazyk dat");
$lang_str["lang:"]=array("Místní jazyk");

$lang_str["overlay:data"]=array("Data");
$lang_str["overlay:draggable"]=array("Značky");

$lang_str["wikipedia:read_more"]="číst více";

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

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";

if(function_exists("lang"))
#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

// please finish this list, see list.php for full list of languages
$lang_str["lang:de"]="Němčina";
$lang_str["lang:bg"]="Bulharština";
$lang_str["lang:cs"]="Čeština";
$lang_str["lang:en"]="Angličtina";
$lang_str["lang:es"]="Španělština";
$lang_str["lang:it"]="Italština";
$lang_str["lang:fr"]="Francouzština";
$lang_str["lang:uk"]="Ukrajinština";
$lang_str["lang:ru"]="Ruština";
$lang_str["lang:ja"]="Japonština";
#$lang_str["lang:hu"]="Hungarian";

