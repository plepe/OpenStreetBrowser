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
#$lang_str["general_info"]="General Information";
$lang_str["yes"]="igen";
$lang_str["no"]="nem";
$lang_str["save"]=array("Mentés");
$lang_str["cancel"]=array("Mégse");
#$lang_str["longitude"]=array("Longitude", "Longitudes");
#$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(névtelen)";
#$lang_str["info_back"]="back to overview";
$lang_str["info_zoom"]="nagyítás";
#$lang_str["nothing_found"]=array("nothing found");
$lang_str["loading"]=array("Betöltés...");
#$lang_str["more"]="more";

// Headings
#$lang_str["head:general_info"]="General Information";
#$lang_str["head:stops"]="Stops";
$lang_str["head:routes"]="Utvonalak";
#$lang_str["head:members"]="Members";
$lang_str["head:address"]="Címek";
#$lang_str["head:internal"]="OSM Internal";
$lang_str["head:wikipedia"]="Wikipédia";
$lang_str["head:housenumbers"]="Házszámok";
$lang_str["head:roads"]="Utak";
$lang_str["head:rails"]="Vasutak";
$lang_str["head:places"]="Helyek";
$lang_str["head:borders"]="Határok";
$lang_str["head:landuse"]="Területfunkciók";
$lang_str["head:buildings"]="Épületek";
#$lang_str["head:pt"]="Public Transportation";
#$lang_str["head:services"]="Services";
$lang_str["head:culture"]="Kulúra";
#$lang_str["head:graves"]="Important Graves";
#$lang_str["head:routing"]="Routing";
$lang_str["head:search"]="Keresés";
$lang_str["head:actions"]=array("Lehetőség", "Lehetőségek");
#$lang_str["head:location"]="Location";
#$lang_str["head:tags"]=array("Tag", "Tags");
#$lang_str["head:whats_here"]="What's here?";

$lang_str["action_browse"]="böngészés az OSM-ben";
$lang_str["action_edit"]="szerkesztés az OSM-ben";

#$lang_str["geo_click_pos"]=array("Click on your position on the map");
$lang_str["geo_set_pos"]="Saját pozícióm beállítása";
$lang_str["geo_change_pos"]="Saját pozícióm választása";

$lang_str["routing_type_car"]="Autóval";
$lang_str["routing_type_car_shortest"]="Autóval (rövidebb út)";
$lang_str["routing_type_bicycle"]="Biciklivel";
$lang_str["routing_type_foot"]="Gyalog";
#$lang_str["routing_type"]="Route type";
$lang_str["routing_distance"]="Távolság";
$lang_str["routing_time"]="Idó";
#$lang_str["routing_disclaimer"]="Routing: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

#$lang_str["list_info"]="Choose a category to browse map content or click on an object on the map for details";
$lang_str["list_leisure_sport_tourism"]="Szabadidő, sport és turizmus";

// Mapkey
#$lang_str["map_key:head"]="Map key";
$lang_str["map_key:zoom"]="Nagyítási színt";

#$lang_str["grave_is_on"]="Grave is on";

#$lang_str["main:map_key"]="Map Key";
$lang_str["main:options"]="Beállítások";
$lang_str["main:about"]="Névjegy";
$lang_str["main:donate"]="Támogatás";
#$lang_str["main:licence"]="Map Data: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
#$lang_str["main:permalink"]="Permalink";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

#$lang_str["start:choose"]=array("Choose map view");
#$lang_str["start:geolocation"]=array("get geolocation");
#$lang_str["start:lastview"]=array("last view");
#$lang_str["start:savedview"]=array("last permalink");
#$lang_str["start:startnormal"]=array("keep view");
#$lang_str["start:remember"]=array("remember decision");
$lang_str["start:edit"]=array("szerkesztés...");

#$lang_str["options:autozoom"]=array("Autozoom behaviour");
#$lang_str["help:autozoom"]=array("When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.");
#$lang_str["options:autozoom:pan"]=array("Pan to current object (nicer)");
#$lang_str["options:autozoom:move"]=array("Move to current object (faster)");
#$lang_str["options:autozoom:stay"]=array("Never change viewport automatically");

#$lang_str["options:language_support"]=array("Language Support");
#$lang_str["help:language_support"]=array("You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.");
$lang_str["options:ui_lang"]=array("Interfész nyelv");
#$lang_str["options:data_lang"]=array("Data language");
#$lang_str["lang:"]=array("Local language");

$lang_str["overlay:data"]=array("Adat");
#$lang_str["overlay:draggable"]=array("Markers");

#$lang_str["wikipedia:read_more"]="read more";

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
$lang_str["basemap:mapnik"]="Eredeti (Mapnik)";
#$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";

if(function_exists("lang"))
#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

// please finish this list, see list.php for full list of languages
$lang_str["lang:de"]="Német";
$lang_str["lang:bg"]="Bulgár";
#$lang_str["lang:cs"]="Czech";
$lang_str["lang:en"]="Angol";
$lang_str["lang:es"]="Spanyol";
$lang_str["lang:it"]="Olasz";
$lang_str["lang:fr"]="Francia";
$lang_str["lang:uk"]="Ukrán";
$lang_str["lang:ru"]="Orosz";
$lang_str["lang:ja"]="Japán";
#$lang_str["lang:hu"]="Hungarian";

