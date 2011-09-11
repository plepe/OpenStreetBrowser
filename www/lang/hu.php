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
$lang_str["general_info"]="Általános információ";
$lang_str["yes"]="igen";
$lang_str["no"]="nem";
#$lang_str["ok"]="Ok";
$lang_str["save"]="Mentés";
$lang_str["cancel"]="Mégse";
#$lang_str["longitude"]=array("Longitude", "Longitudes");
#$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(névtelen)";
$lang_str["info_back"]="vissza a főmenübe";
$lang_str["info_zoom"]="nagyítás";
$lang_str["nothing_found"]="nincs találat";
$lang_str["loading"]="Betöltés...";
$lang_str["more"]="több";
#$lang_str["unnamed"]="unnamed";
$lang_str["zoom"]="Nagyítási színt";

// Headings
$lang_str["head:general_info"]="Általános információ";
$lang_str["head:stops"]="Megállók";
$lang_str["head:routes"]="Útvonalak";
#$lang_str["head:members"]="Members";
$lang_str["head:address"]="Címek";
#$lang_str["head:internal"]="OSM Internal";
$lang_str["head:services"]="Szolgáltatás";
$lang_str["head:culture"]="Kultúra";
$lang_str["head:graves"]="Fontos sírok";
$lang_str["head:routing"]="Útvonaltervezés";
$lang_str["head:search"]="Keresés";
$lang_str["head:actions"]=array("Lehetőség", "Lehetőségek");
$lang_str["head:location"]="Hely";
$lang_str["head:tags"]=array("Címke", "Címkék");
$lang_str["head:whats_here"]="Mi van itt?";

$lang_str["action_browse"]="böngészés az OSM-ben";
$lang_str["action_edit"]="szerkesztés az OSM-ben";

$lang_str["geo_click_pos"]="Kattints a saját pozíciódra";
$lang_str["geo_set_pos"]="Saját pozícióm beállítása";
$lang_str["geo_change_pos"]="Saját pozícióm választása";

$lang_str["routing_type_car"]="Autóval";
$lang_str["routing_type_car_shortest"]="Autóval (rövidebb út)";
$lang_str["routing_type_bicycle"]="Biciklivel";
$lang_str["routing_type_foot"]="Gyalog";
$lang_str["routing_type"]="Útvonal típusa";
$lang_str["routing_distance"]="Távolság";
$lang_str["routing_time"]="Idő";
$lang_str["routing_disclaimer"]="Útvonaltervezés: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Válassz ki egy kategóriát, ami megjelenik a térképen, vagy kattints egy a térképen megjelenô pontra";
$lang_str["list_leisure_sport_tourism"]="Szabadidő, sport és turizmus";

// Mapkey

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:options"]="Beállítások";
$lang_str["main:about"]="Névjegy";
$lang_str["main:donate"]="Támogatás";
$lang_str["main:licence"]="Térképadatok: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
$lang_str["main:permalink"]="Állandó hivatkozás";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Automatikus nagyítás beállításai";
$lang_str["help:autozoom"]="Egy objektum kiválasztásakor a térkép odaugrik, és esetleg a nagyítás is változik. Ezzel a beállítási lehetőséggel különböz ő viselkedési módok között választhatsz.";
$lang_str["options:autozoom:pan"]="Mozgás az objektumhoz (szebb)";
$lang_str["options:autozoom:move"]="Ugrás az objektumhoz (gyorsabb)";
$lang_str["options:autozoom:stay"]="Ne legyen automatikus térkép-elmozdítás";

$lang_str["options:language_support"]="Nyelv-támogatás";
$lang_str["help:language_support"]="Itt kiválaszthatod a kedvenc nyelvedet. Az első pont a menük nyelvét, a második pedig a térképadatok nyelvét befolyásolja. Sok térképelem mindenféle nyelvre le van fordítva. Ha az elem nincs lefordítva, vagy a \"Helyi nyelv\" van kiválasztva, akkor az elem fő nyelve jelenik meg.";
$lang_str["options:ui_lang"]="Menünyelv";
$lang_str["options:data_lang"]="Adatnyelv";
$lang_str["lang:"]="Helyi nyelv";

$lang_str["overlay:data"]="Adat";
$lang_str["overlay:draggable"]="Jelölők";

$lang_str["user:no_auth"]="Rossz felhasználói név vagy jelszó!";
$lang_str["user:login_text"]="Beszállás az OpenStreetBrowserbe:";
$lang_str["user:create_user"]="Új felhasználó:";
$lang_str["user:username"]="Felhasználói név";
$lang_str["user:email"]="E-mail-cím";
$lang_str["user:password"]="Jelszó";
$lang_str["user:password_verify"]="Jelszó, még egyszer";
$lang_str["user:old_password"]="Régi jelszó";
$lang_str["user:no_username"]="Adj be egy felhasználói nevet!";
$lang_str["user:password_no_match"]="Nem ugyanaz a két jelszó!";
$lang_str["user:full_name"]="Teljes név";
$lang_str["user:user_exists"]="Már van ilyen nevű felhasználó";
$lang_str["user:login"]="Bejelentkezés";
$lang_str["user:logged_in_as"]="Bejelentkezve ezen a néven: ";
$lang_str["user:logout"]="Kijelentkezés";

$lang_str["error"]="Hiba történt: ";
$lang_str["error:not_logged_in"]="nem vagy bejelentkezve";

$lang_str["more_categories"]="Több kategória";
$lang_str["category:status"]="Állapot";
$lang_str["category:data_status"]="Állapot";
$lang_str["category:old_version"]="A kategória új verziója készül elő.";
$lang_str["category:not_compiled"]="Új kategória készül elő.";

$lang_str["category_rule_tag:match"]="Illeszkedés";
$lang_str["category_rule_tag:description"]="Leírás";
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Eredeti (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";

// please finish this list, see list.php for full list of languages
$lang_str["lang:de"]="Német";
$lang_str["lang:bg"]="Bulgár";
$lang_str["lang:cs"]="Cseh";
$lang_str["lang:en"]="Angol";
$lang_str["lang:es"]="Spanyol";
$lang_str["lang:it"]="Olasz";
$lang_str["lang:fr"]="Francia";
$lang_str["lang:uk"]="Ukrán";
$lang_str["lang:ru"]="Orosz";
$lang_str["lang:ja"]="Japán";
$lang_str["lang:hu"]="Magyar";
#$lang_str["lang:nl"]="Dutch";
#$lang_str["lang:ast"]="Asturian";
