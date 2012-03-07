<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Romana"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Informatii generale";
$lang_str["yes"]="Da";
$lang_str["no"]="Nu";
$lang_str["ok"]="Ok";
$lang_str["save"]="Salveaza";
$lang_str["saved"]="Salvat"; // for dialog boxes confirming saving
$lang_str["cancel"]="Anuleaza";
$lang_str["show"]="Arata";
$lang_str["edit"]="Editare";
$lang_str["delete"]="Sterge";
$lang_str["history"]="Istoric";
$lang_str["choose"]="Alege";
$lang_str["help"]="Ajutor";
$lang_str["longitude"]="Longitudine";
$lang_str["latitude"]="Latitudine";
$lang_str["noname"]="(fara nume)";
#$lang_str["info_back"]="back to overview";
#$lang_str["info_zoom"]="zoom";
$lang_str["nothing_found"]="Nu s-a gasit nimic";
#$lang_str["list:zoom_for_obs"]="Zoom in for less important objects";
$lang_str["loading"]="Se incarca...";
$lang_str["more"]="Mai mult";
$lang_str["source"]="Sursa";
#$lang_str["unnamed"]="unnamed";
#$lang_str["zoom"]="Zoom level";
#$lang_str["no_message"]=array("no message", "no messages");
#$lang_str["ad"]=array("Advertisement", "Advertisements");

// Headings
$lang_str["head:general_info"]="Informatii generale";
#$lang_str["head:stops"]="Stops";
$lang_str["head:routes"]="Rute";
$lang_str["head:members"]="Membrii";
$lang_str["head:address"]="Adresa";
#$lang_str["head:internal"]="OSM Internal";
#$lang_str["head:services"]="Services";
#$lang_str["head:culture"]="Culture";
#$lang_str["head:routing"]="Routing";
$lang_str["head:search"]="Cauta";
#$lang_str["head:actions"]=array("Action", "Actions");
$lang_str["head:location"]="Locatie";
#$lang_str["head:tags"]=array("Tag", "Tags");
$lang_str["head:whats_here"]="Ce se afla aici ?";

$lang_str["action_browse"]="cauta in OSM";
$lang_str["action_edit"]="editeaza in OSM";

#$lang_str["geo_click_pos"]="Click on your position on the map";
$lang_str["geo_set_pos"]="Fixeaza pozitia mea ";
$lang_str["geo_change_pos"]="Schimba pozitia mea";

$lang_str["routing_type_car"]="Masina";
$lang_str["routing_type_car_shortest"]="Masina (Shortest)";
$lang_str["routing_type_bicycle"]="Bicicleta";
#$lang_str["routing_type_foot"]="Foot";
#$lang_str["routing_type"]="Route type";
$lang_str["routing_distance"]="Distanta";
$lang_str["routing_time"]="Timp";

#$lang_str["list_info"]="Choose a category to browse map content or click on an object on the map for details";
#$lang_str["list_leisure_sport_tourism"]="Leisure, Sport and Tourism";

// Mapkey


$lang_str["main:help"]="Ajutor";
$lang_str["main:options"]="Optiuni";
$lang_str["main:about"]="Despre";
$lang_str["main:donate"]="Donatie";
$lang_str["main:licence"]="Licenta";
$lang_str["main:permalink"]="Permalink";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

#$lang_str["options:autozoom"]="Autozoom behaviour";
#$lang_str["help:autozoom"]="When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.";
#$lang_str["options:autozoom:pan"]="Pan to current object (nicer)";
$lang_str["options:autozoom:move"]="Muta la obiectul curent (rapid)";
#$lang_str["options:autozoom:stay"]="Never change viewport automatically";

$lang_str["options:language_support"]="Suport pentru limba";
#$lang_str["help:language_support"]="You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.";
$lang_str["options:ui_lang"]="Limba interfata";
$lang_str["options:data_lang"]="Limba date";
$lang_str["lang:"]="Limba locala";
$lang_str["lang:auto"]="La fel ca limba interfata";

#$lang_str["overlay:data"]="Data";
#$lang_str["overlay:draggable"]="Markers";

$lang_str["user:no_auth"]="Numele utilizatorului sau parola este gresit !";
$lang_str["user:login_text"]="Autentifica-te pe OpenStreetBrowser:";
$lang_str["user:create_user"]="Creeaza un utilizator nou :";
$lang_str["user:username"]="Nume utilizator";
$lang_str["user:email"]="Adresa e-mail";
$lang_str["user:password"]="Parola";
$lang_str["user:password_verify"]="Verifica parola";
$lang_str["user:old_password"]="Parola veche";
$lang_str["user:no_username"]="Va rugam alegeti un nume de utilizator !";
$lang_str["user:password_no_match"]="Parolele nu se potrivesc!";
$lang_str["user:full_name"]="Numele complet";
$lang_str["user:user_exists"]="Numele de utilizator exista deja";
$lang_str["user:login"]="Autentificare";
$lang_str["user:logged_in_as"]="Conectat ca";
$lang_str["user:logout"]="Deconectare";

$lang_str["attention"]="Atentie:";
$lang_str["error"]="A avut loc o eroare:";
$lang_str["error:not_logged_in"]="Nu sunteti conectat in  ";

$lang_str["category"]="Categorii";
$lang_str["more_categories"]="Mai multe categorii";
$lang_str["category:status"]="Stare";
$lang_str["category:data_status"]="Stare";
$lang_str["category:old_version"]="O versiune nouă a acestei categorii este în curs de elaborare.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

$lang_str["category:new_rule"]="Noi reguli";
#$lang_str["category_rule_tag:match"]="Match";
#$lang_str["category_rule_tag:description"]="Description";
$lang_str["category_chooser:choose"]="Alege o categorie";
$lang_str["category_chooser:new"]="Categorie noua";
$lang_str["category:sub_category"]="Sub categorie";

$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:osb_light"]="OpenStreetBrowser (pale)";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";
