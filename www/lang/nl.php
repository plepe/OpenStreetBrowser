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
$lang_str["lang:current"]="Nederlands"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Algemene Informatie";
$lang_str["yes"]="ja";
$lang_str["no"]="nee";
$lang_str["ok"]="Ok";
$lang_str["save"]="Opslaan";
$lang_str["cancel"]="Annuleren";
$lang_str["longitude"]=array("Lengtegraad", "Lengtegraden");
$lang_str["latitude"]=array("Breedtegraad", "Breedtegraden");
$lang_str["noname"]="(geen naam)";
$lang_str["info_back"]="terug naar overzicht";
$lang_str["info_zoom"]="inzoomen";
$lang_str["nothing_found"]="niets gevonden";
$lang_str["loading"]="Laden...";
$lang_str["more"]="meer";
$lang_str["unnamed"]="naamloos";
$lang_str["zoom"]="Zoomniveau";

// Headings
$lang_str["head:general_info"]="Algemene Informatie";
$lang_str["head:stops"]="Haltes";
$lang_str["head:routes"]="Routes";
$lang_str["head:members"]="Leden";
$lang_str["head:address"]="Adres";
$lang_str["head:internal"]="Interne OSM-data";
$lang_str["head:services"]="Dienstverleningen";
$lang_str["head:culture"]="Cultuur";
$lang_str["head:routing"]="Routeplanning";
$lang_str["head:search"]="Zoeken";
$lang_str["head:actions"]=array("Actie", "Acties");
$lang_str["head:location"]="Locatie";
$lang_str["head:tags"]=array("Tag", "Tags");
$lang_str["head:whats_here"]="Wat is hier?";

$lang_str["action_browse"]="in OSM bekijken";
$lang_str["action_edit"]="in OSM bewerken";

$lang_str["geo_click_pos"]="Klik op uw positie op de kaart";
$lang_str["geo_set_pos"]="Mijn positie vastleggen";
$lang_str["geo_change_pos"]="Mijn positie wijzigen";

$lang_str["routing_type_car"]="Auto";
$lang_str["routing_type_car_shortest"]="Auto (Kortste)";
$lang_str["routing_type_bicycle"]="Fiets";
$lang_str["routing_type_foot"]="Te voet";
$lang_str["routing_type"]="Soort route";
$lang_str["routing_distance"]="Afstand";
$lang_str["routing_time"]="Tijd";

$lang_str["list_info"]="Kies een categorie om de kaartinhoud door te bladeren of klik op een object op de kaart voor details";
$lang_str["list_leisure_sport_tourism"]="Vrije tijd, Sport and Toerisme";

// Mapkey


$lang_str["main:options"]="Opties";
$lang_str["main:about"]="Over";
$lang_str["main:donate"]="Doneer";
$lang_str["main:licence"]="Kaartdata: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> bijdragers | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> en <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">bijdragers</a>";
$lang_str["main:permalink"]="Permalink";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object niet gevonden</h1>Er kan geen object met het ID \"%s\" worden gevonden. Dit kan te wijten zijn aan een (of meer) van de volgende redenen:<ul><li>Het ID is fout.</li><li>Het object werd geïdentificeerd door een site van derden en is (nog) niet beschikbaar in de OpenStreetBrowser.</li><li>Het object valt buiten de ondersteunde gebied.</li><li>De link die u volgde was oud en het object is verwijderd uit OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Auto-zoom gedrag";
$lang_str["help:autozoom"]="Bij het kiezen van een object, zwenkt de kaartuitsnede naar dat object, en wordt het zoomniveau mogelijk ook aangepast. Met deze optie kunt u kiezen tussen verschillende modi.";
$lang_str["options:autozoom:pan"]="Naar het huidige object pannen (mooier)";
$lang_str["options:autozoom:move"]="Naar het huidige object verplaatsen (sneller)";
$lang_str["options:autozoom:stay"]="De kaartuitsnede nooit automatisch veranderen";

$lang_str["options:language_support"]="Taalondersteuning";
$lang_str["help:language_support"]="Met deze optie kunnen de gewenste talen worden ingesteld. De eerste optie verandert de taal van de gebruikers interface. Met de tweede optie kan de taal voor de gegevensdata worden gewijzigd. De gegevens van een groot aantal geografische objecten zijn vertaald in verschillende talen. Als er geen vertaling beschikbaar is, of \"lokale taal\" werd gekozen, wordt de hoofdtaal van het object weergegeven.";
$lang_str["options:ui_lang"]="Interfacetaal";
$lang_str["options:data_lang"]="Taal voor data";
$lang_str["lang:"]="Lokale taal";

$lang_str["overlay:data"]="Data";
$lang_str["overlay:draggable"]="Markeringen";

$lang_str["user:no_auth"]="Foutieve gebruikersnaam of wachtwoord!";
$lang_str["user:login_text"]="Bij OpenStreetBrowser aanmelden:";
$lang_str["user:create_user"]="Nieuwe gebruiker registreren:";
$lang_str["user:username"]="Gebruikersnaam";
$lang_str["user:email"]="E-mailadres";
$lang_str["user:password"]="Wachtwoord";
$lang_str["user:password_verify"]="Wachtwoord verifiëren";
$lang_str["user:old_password"]="Oud wachtwoord";
$lang_str["user:no_username"]="Gelieve een gebruikersnaam invoeren!";
$lang_str["user:password_no_match"]="Paswoorden komen niet overeen!";
$lang_str["user:full_name"]="Volledige naam";
$lang_str["user:user_exists"]="Gebruikersnaam bestaat al";
$lang_str["user:login"]="Aanmelden";
$lang_str["user:logged_in_as"]="Aangemeld als ";
$lang_str["user:logout"]="Afmelden";

$lang_str["error"]="Er is een fout opgetreden: ";
$lang_str["error:not_logged_in"]="U bent niet aangemeld";

$lang_str["more_categories"]="Meer categorieën";
$lang_str["category:status"]="Status";
$lang_str["category:data_status"]="Status";
$lang_str["category:old_version"]="Een nieuwe versie van deze categorie wordt voorbereid.";
$lang_str["category:not_compiled"]="Nieuwe categorie wordt voorbereid.";

$lang_str["category_rule_tag:match"]="Overeenkomst";
$lang_str["category_rule_tag:description"]="Omschrijving";
$lang_str["category_chooser:choose"]="Kies een categorie";
$lang_str["category_chooser:new"]="Nieuwe categorie aanmaken";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standaard (Mapnik)";
$lang_str["basemap:osmarender"]="Standaard (OsmaRender)";
$lang_str["basemap:cyclemap"]="FietsKaart";
