<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
#$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Čeština"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Základní informace";
$lang_str["yes"]="ano";
$lang_str["no"]="ne";
$lang_str["ok"]="Potvrdit";
$lang_str["save"]="Uložit";
$lang_str["saved"]="Uloženo"; // for dialog boxes confirming saving
$lang_str["cancel"]="Zrušit";
$lang_str["show"]="Ukázat";
$lang_str["edit"]="Upravit";
$lang_str["delete"]="Smazat";
$lang_str["history"]="Historie";
$lang_str["choose"]="Vybrat";
$lang_str["help"]="Nápověda";
$lang_str["longitude"]=array(F, "Zeměpisná délka", "Zeměpisné délky");
$lang_str["latitude"]=array(F, "Zeměpisná šířka", "Zeměpisné šířky");
$lang_str["noname"]="(bezejmenný)";
$lang_str["info_back"]="zpět na přehled";
$lang_str["info_zoom"]="přiblížení";
$lang_str["nothing_found"]="nic nenalezeno";
$lang_str["list:zoom_for_obs"]="K zobrazení méně důležitých objektů je nuntno zazoomovat";
$lang_str["loading"]="Načítání...";
$lang_str["more"]="více";
$lang_str["source"]="Zdroj";
$lang_str["unnamed"]="nepojmenováno";
$lang_str["zoom"]="Stupeň přiblížení";
$lang_str["no_message"]="beze zprávy";

// Headings
$lang_str["head:general_info"]="Základní informace";
$lang_str["head:stops"]="Zastávky";
$lang_str["head:routes"]="Linky";
$lang_str["head:members"]="Členové";
$lang_str["head:address"]="Adresa";
#$lang_str["head:internal"]="OSM Internal";
$lang_str["head:services"]="Služby";
$lang_str["head:culture"]="Kultura";
$lang_str["head:graves"]="Významné hroby";
$lang_str["head:routing"]="Hledání trasy";
$lang_str["head:search"]="Vyhledávání";
$lang_str["head:actions"]=array(F, "Akce", "Akce");
$lang_str["head:location"]="Umístění";
$lang_str["head:tags"]=array("Tag", "Tagy");
$lang_str["head:whats_here"]="Co je zde?";

$lang_str["action_browse"]="prohlížet v OSM";
$lang_str["action_edit"]="editovat v OSM";

$lang_str["geo_click_pos"]="Klikněte na vaši pozici na mapě";
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

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:options"]="Nastavení";
$lang_str["main:about"]="O Projektu";
$lang_str["main:donate"]="Sponzorovat";
$lang_str["main:licence"]="Data na mapě: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> přispěvatelé | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> a <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">přispěvatelé</a>";
$lang_str["main:permalink"]="Trvalý odkaz";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>Nebyl nalezen žádný objekt s ID \"%s\". Toto nastalo z jednoho z (nebo více) následujících důvodů:<ul><li>ID je špatné.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>Objekt je mimo podporovanou oblast.</li><li>Váš odkaz je starý a objekt už byl pravděpodobně vymazán z OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Chování autozoomu";
$lang_str["help:autozoom"]="Poté, co vybereme objekt, se k němu pohled přiblíží, přičemž se může změnit úroveň zoomu. S touto možností si můžete vybrat mezi dvěma odlišnými styly.";
$lang_str["options:autozoom:pan"]="Přeletět pohledem k danému objektu (hezčí)";
$lang_str["options:autozoom:move"]="Přiblížit k danému objektu (rychlejší)";
$lang_str["options:autozoom:stay"]="Nikdy neměnit pohled automaticky";

$lang_str["options:language_support"]="Jazyková podpora";
$lang_str["help:language_support"]="Touto volbou můžeš vybrat své preferované jazyky. První volba změní jazyk uživatelského rozhraní. Druhá volba změní jazyk dat. Popis mnoha geografických objektů byl přeložen do různých jazyků. Jestliže překlad není dostupný nebo byl vybrán \"místní jazyk\" , popis bude zobrazen v hlavním jazyku objektu.";
$lang_str["options:ui_lang"]="Jazyk rozhraní";
$lang_str["options:data_lang"]="Jazyk dat";
$lang_str["lang:"]="Místní jazyk";
$lang_str["lang:auto"]="Stejný jako jazyk rozhraní";

$lang_str["overlay:data"]="Data";
$lang_str["overlay:draggable"]="Značky";

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

$lang_str["attention"]="Pozor:";
$lang_str["error"]="Objevila se chyba: ";
$lang_str["error:not_logged_in"]="nejste přihlášen";

$lang_str["category"]=array("Kategorie", "Kategorie");
$lang_str["more_categories"]="Více kategorií";
$lang_str["category:status"]="Stav";
$lang_str["category:data_status"]="Stav";
$lang_str["category:old_version"]="Připravuje se nová verze této kategorie.";
$lang_str["category:not_compiled"]="Připravuje se nová kategorie.";

$lang_str["category:new_rule"]="Nové pravidlo";
$lang_str["category_rule_tag:match"]="Shoda";
$lang_str["category_rule_tag:description"]="Popis";
$lang_str["category_chooser:choose"]="Vyberte kategorii";
$lang_str["category_chooser:new"]="Nová kategorie";
$lang_str["category:sub_category"]=array("Podkategorie", "Podkategorie");

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";
