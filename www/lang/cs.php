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
#$lang_str["ok"]="Ok";
$lang_str["save"]=array("Uložit");
$lang_str["cancel"]=array("Zrušit");
$lang_str["longitude"]=array("F", "Zeměpisná délka", "Zeměpisné délky");
$lang_str["latitude"]=array("F", "Zeměpisná šířka", "Zeměpisné šířky");
$lang_str["noname"]="(bezejmenný)";
$lang_str["info_back"]="zpět na přehled";
$lang_str["info_zoom"]="přiblížení";
$lang_str["nothing_found"]=array("nic nenalezeno");
$lang_str["loading"]=array("Načítání...");
$lang_str["more"]="více";
$lang_str["unnamed"]="nepojmenováno";

// Headings
$lang_str["head:general_info"]="Základní informace";
$lang_str["head:stops"]="Zastávky";
$lang_str["head:routes"]="Linky";
$lang_str["head:members"]="Členové";
$lang_str["head:address"]="Adresa";
#$lang_str["head:internal"]="OSM Internal";
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
$lang_str["head:tags"]=array("Tag", "Tagy");
$lang_str["head:whats_here"]="Co je zde?";

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
$lang_str["main:about"]="O Projektu";
$lang_str["main:donate"]="Sponzorovat";
$lang_str["main:licence"]="Data na mapě: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> přispěvatelé | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> a <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">přispěvatelé</a>";
$lang_str["main:permalink"]="Trvalý odkaz";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>Nebyl nalezen žádný objekt s ID \"%s\". Toto nastalo z jednoho z (nebo více) následujících důvodů:<ul><li>ID je špatné.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>Objekt je mimo podporovanou oblast.</li><li>Váš odkaz je starý a objekt už byl pravděpodobně vymazán z OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]=array("Chování autozoomu");
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

$lang_str["user:no_auth"]="Špatné uživatelské jméno nebo heslo!";
$lang_str["user:login_text"]="Přihlásit se do OpenStreetBrowseru:";
$lang_str["user:create_user"]="Vytvořit nového uživatele:";
$lang_str["user:username"]="Uživatelské jméno";
$lang_str["user:email"]="E-mailová adresa";
$lang_str["user:password"]="Heslo";
$lang_str["user:password_verify"]="Ověřit heslo";
$lang_str["user:old_password"]="Staré heslo";
$lang_str["user:no_username"]="Prosím, napište uživatelské jméno!";
$lang_str["user:password_no_match"]="Hesla se neshodují!";
$lang_str["user:full_name"]="Celé jméno";
$lang_str["user:user_exists"]="Toto uživatelské jméno už existuje";
$lang_str["user:login"]="Přihlásit se";
$lang_str["user:logged_in_as"]="Přihlášen jako ";
$lang_str["user:logout"]="Odhlásit se";

$lang_str["error"]="Objevila se chyba: ";
$lang_str["error:not_logged_in"]="nejste přihlášen";

$lang_str["more_categories"]="Více kategorií";
$lang_str["category:status"]="Stav";
$lang_str["category:data_status"]="Stav";
$lang_str["category:old_version"]="Připravuje se nová verze této kategorie.";
$lang_str["category:not_compiled"]="Připravuje se nová kategorie.";

$lang_str["category_rule_tag:match"]="Shoda";
$lang_str["category_rule_tag:description"]="Popis";
$lang_str["category_chooser:choose"]="Vyberte kategorii";
$lang_str["category_chooser:new"]="Nová kategorie";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";

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
$lang_str["lang:hu"]="Maďarština";
$lang_str["lang:ast"]="Asturština";
// The following $lang_str were not defined in the English language file and might be deprecated or wrong:
$lang_str["head:wikipedia"]="Wikipedie";
