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
$lang_str["general_info"]="Allgemeine Informationen";
$lang_str["yes"]="ja";
$lang_str["no"]="nein";
$lang_str["save"]=array("Speichern");
$lang_str["cancel"]=array("Abbrechen");
$lang_str["longitude"]=array("Longitude", "Longituden");
$lang_str["latitude"]=array("Latitude", "Latituden");
$lang_str["noname"]="(kein Name)";
$lang_str["info_back"]="zur Übersicht";
$lang_str["info_zoom"]="zoomen";
$lang_str["nothing_found"]=array("nichts gefunden");
$lang_str["loading"]="lade";
$lang_str["more"]="mehr";
$lang_str["source"]="Quelle";

// Headings
$lang_str["head:general_info"]="Allgemeine Informationen";
$lang_str["head:stops"]="Haltestellen";
$lang_str["head:routes"]="Routen";
$lang_str["head:members"]="Mitglieder";
$lang_str["head:address"]="Adresse";
$lang_str["head:internal"]="Interne OSM-Daten";
$lang_str["head:wikipedia"]="Wikipedia";
$lang_str["head:housenumbers"]="Hausnummern";
$lang_str["head:roads"]="Straßen";
$lang_str["head:rails"]="Eisenbahnstrecken";
$lang_str["head:places"]="Orte";
$lang_str["head:borders"]="Grenzen";
$lang_str["head:landuse"]="Landnutzung";
$lang_str["head:buildings"]="Gebäude";
$lang_str["head:pt"]="Öffentlicher Verkehr";
$lang_str["head:services"]="Dienstleistungen";
$lang_str["head:culture"]="Kultureinrichtungen";
$lang_str["head:graves"]="Wichtige Gräber";
$lang_str["head:routing"]="Routenplanung";
$lang_str["head:search"]="Suche";
$lang_str["head:actions"]="Aktionen";
#$lang_str["head:location"]="Location";
$lang_str["head:tags"]=array("Tag", "Tags");

$lang_str["action_browse"]="In OSM ansehen";
$lang_str["action_edit"]="Auf OSM editieren";

$lang_str["geo_click_pos"]=array("Klicke auf deine Position auf der Karte");
$lang_str["geo_set_pos"]="Meine Position festlegen";
$lang_str["geo_change_pos"]="Meine Position ändern";

$lang_str["routing_type_car"]="Auto";
$lang_str["routing_type_car_shortest"]="Auto (kürzeste)";
$lang_str["routing_type_bicycle"]="Fahrrad";
$lang_str["routing_type_foot"]="Zu Fuß";
$lang_str["routing_type"]="Routentyp";
$lang_str["routing_distance"]="Entfernung";
$lang_str["routing_time"]="Zeit";
$lang_str["routing_disclaimer"]="Routing: (c) von <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Wähle eine Kategorie, um den Karteninhalt zu durchstöbern oder klicke auf ein Objekt auf der Karte für Details";
$lang_str["list_leisure_sport_tourism"]="Freizeit, Sport und Tourismus";

// Mapkey
$lang_str["map_key:head"]="Legende";
$lang_str["map_key:zoom"]="Zoomstufe";

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:map_key"]="Legende";
$lang_str["main:options"]="Optionen";
$lang_str["main:about"]="Impressum";
$lang_str["main:donate"]="Spende";
$lang_str["main:licence"]="Kartendaten: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a>-Mitwirkende | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> und <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">Mitwirkende</a>";
$lang_str["main:permalink"]="Permalink";

$lang_str["help:no_object"]="Ein Objekt mit der ID \"%s\" konnte nicht gefunden werden. Das kann eine (oder mehrere) der folgenden Ursachen haben:<ul><li>Die ID ist falsch.</li><li>Das Objekt wurde von einer anderen Applikation identifiziert und ist im OpenStreetBrowser (noch) nicht verfügbar</li><li>Das Objekt liegt ausserhalb des unterstützten Gebiets</li><li>Der Link, dem Du gefolgt bist, war alt und das Objekt wurde inzwischen aus der OpenStreetMap gelöscht.</li>";

$lang_str["start:choose"]=array("Wähle einen Kartenausschnitt");
$lang_str["start:geolocation"]=array("automatische Geolokalisierung");
$lang_str["start:lastview"]=array("letzter Kartenausschnitt");
$lang_str["start:savedview"]=array("letzter Permalink");
$lang_str["start:startnormal"]=array("behalte Ansicht");
$lang_str["start:remember"]=array("Auswahl merken");
$lang_str["start:edit"]=array("editieren...");

$lang_str["options:autozoom"]="Autozoomverhalten";
$lang_str["help:autozoom"]="Wenn ein Objekt ausgewählt wird, schwenkt der Kartenausschnitt zu dem Objekt, auch der Zoomlevel wird angepasst. Mit dieser Option kann zwischen verschiedenen Modi gewählt werden.";
$lang_str["options:autozoom:pan"]="Auf das aktuelle Objekt schwenken (schöner)";
$lang_str["options:autozoom:move"]="Zum aktuellen Objekt springen (schneller)";
$lang_str["options:autozoom:stay"]="Den Kartenausschnitt nie verschieben";

$lang_str["options:language_support"]="Sprachunterstützung";
$lang_str["help:language_support"]="In diesen Optionen können die verwendeten Sprachen eingestellt werden. In der ersten Einstellung kann die Sprache der Anwendungsoberfläche gewählt werden. Mit der zweiten Einstellung kann die Datensprache eingestellt werden. Die Daten vieler geographischer Objekte sind in mehrere Sprachen übersetzt. Wenn keine Übersetzung vorhanden ist, oder \"Lokale Sprache\" ausgewählt wurde, wird die Hauptsprache des Objektes angezeigt.";
$lang_str["options:ui_lang"]="Anwendungssprache";
$lang_str["options:data_lang"]="Datensprache";
$lang_str["lang:"]="Lokale Sprache";
$lang_str["lang:auto"]="Gleich wie Anwendungssprache";

$lang_str["overlay:data"]="Data";
$lang_str["overlay:draggable"]="Markierungen";

$lang_str["wikipedia:read_more"]="weiterlesen";

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
$lang_str["user:login"]="Anmelden";
$lang_str["user:logged_in_as"]="Angemeldet als ";
$lang_str["user:logout"]="Abmelden";

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
$lang_str["lang:de"]="Deutsch";
$lang_str["lang:bg"]="Bulgarisch";
$lang_str["lang:cs"]="Tschechisch";
$lang_str["lang:en"]="Englisch";
$lang_str["lang:es"]="Spanisch";
$lang_str["lang:it"]="Italienisch";
$lang_str["lang:fr"]="Französisch";
$lang_str["lang:uk"]="Ukrainisch";
$lang_str["lang:ru"]="Russisch";
$lang_str["lang:ja"]="Japanisch";
#$lang_str["lang:hu"]="Hungarian";

