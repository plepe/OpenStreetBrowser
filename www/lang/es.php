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
$lang_str["general_info"]="Información General";
$lang_str["yes"]="si";
$lang_str["no"]="no";
$lang_str["save"]=array("Guardar");
$lang_str["cancel"]=array("Cancelar");
$lang_str["longitude"]=array("Longitud", "Longitudes");
$lang_str["latitude"]=array("Latitud", "Latitudes");
$lang_str["noname"]="(sin_nombre)";
#$lang_str["info_back"]="back to overview";
$lang_str["info_zoom"]="zoom";
#$lang_str["nothing_found"]=array("nothing found");
$lang_str["loading"]=array("Cargando...");
$lang_str["more"]="más";

// Headings
$lang_str["head:general_info"]="Información General";
#$lang_str["head:stops"]="Stops";
$lang_str["head:routes"]="Rutas";
$lang_str["head:members"]="Miembros";
$lang_str["head:address"]="Direccion";
#$lang_str["head:internal"]="OSM Internal";
$lang_str["head:wikipedia"]="Wikipedia";
#$lang_str["head:housenumbers"]="Housenumbers";
$lang_str["head:roads"]="Calles";
#$lang_str["head:rails"]="Railroads";
$lang_str["head:places"]="Lugares";
$lang_str["head:borders"]="Fronteras";
#$lang_str["head:landuse"]="Landuse";
$lang_str["head:buildings"]="Edificios";
$lang_str["head:pt"]="Transporte Público";
$lang_str["head:services"]="Servicios";
$lang_str["head:culture"]="Cultura";
$lang_str["head:graves"]="Tumbas Importantes";
#$lang_str["head:routing"]="Routing";
$lang_str["head:search"]="Buscar";
$lang_str["head:actions"]=array("Acción", "Acciones");
$lang_str["head:location"]="Ubicación";
$lang_str["head:tags"]=array("Etiqueta", "Etiquetas");
$lang_str["head:whats_here"]="Qué hay aquí?";

$lang_str["action_browse"]="ver en OSM";
$lang_str["action_edit"]="editar en OSM";

#$lang_str["geo_click_pos"]=array("Click on your position on the map");
$lang_str["geo_set_pos"]="Fijar mi posición";
$lang_str["geo_change_pos"]="Cambiar mi posición";

$lang_str["routing_type_car"]="Automóbil";
#$lang_str["routing_type_car_shortest"]="Car (Shortest)";
$lang_str["routing_type_bicycle"]="Bicicleta";
$lang_str["routing_type_foot"]="a pie";
$lang_str["routing_type"]="Tipo de Ruta";
$lang_str["routing_distance"]="Distancia";
$lang_str["routing_time"]="Tiempo";
#$lang_str["routing_disclaimer"]="Routing: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

#$lang_str["list_info"]="Choose a category to browse map content or click on an object on the map for details";
#$lang_str["list_leisure_sport_tourism"]="Leisure, Sport and Tourism";

// Mapkey
#$lang_str["map_key:head"]="Map key";
$lang_str["map_key:zoom"]="Nivel de Zoom";

#$lang_str["grave_is_on"]="Grave is on";

#$lang_str["main:map_key"]="Map Key";
$lang_str["main:options"]="Opciones";
$lang_str["main:about"]="Acerca de...";
$lang_str["main:donate"]="Donar";
#$lang_str["main:licence"]="Map Data: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
$lang_str["main:permalink"]="Enlace Permanente";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

#$lang_str["options:autozoom"]=array("Autozoom behaviour");
#$lang_str["help:autozoom"]=array("When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.");
#$lang_str["options:autozoom:pan"]=array("Pan to current object (nicer)");
#$lang_str["options:autozoom:move"]=array("Move to current object (faster)");
#$lang_str["options:autozoom:stay"]=array("Never change viewport automatically");

#$lang_str["options:language_support"]=array("Language Support");
#$lang_str["help:language_support"]=array("You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.");
$lang_str["options:ui_lang"]=array("Idioma de interfaz");
$lang_str["options:data_lang"]=array("Idioma de datos");
$lang_str["lang:"]=array("Idioma local");

#$lang_str["overlay:data"]=array("Data");
#$lang_str["overlay:draggable"]=array("Markers");

$lang_str["wikipedia:read_more"]="leer más";

$lang_str["user:no_auth"]="Usuario o contraseña equivocados!";
#$lang_str["user:login_text"]="Log in to OpenStreetBrowser:";
$lang_str["user:create_user"]="Crear nuevo usuario:";
$lang_str["user:username"]="Usuario";
$lang_str["user:email"]="Correo electrónico";
$lang_str["user:password"]="Contraseña";
$lang_str["user:password_verify"]="Verificar contraseña";
$lang_str["user:old_password"]="Contraseña anterior";
$lang_str["user:no_username"]="Por favor, indica un usuario!";
$lang_str["user:password_no_match"]="Las contraseñas no concuerdan!";
$lang_str["user:full_name"]="Nombre completo";
$lang_str["user:user_exists"]="El usuario ya existe";
#$lang_str["user:login"]="Login";
#$lang_str["user:logged_in_as"]="Logged in as ";
$lang_str["user:logout"]="Cerrar sesión";

$lang_str["error"]="Ha ocurrido un error: ";
#$lang_str["error:not_logged_in"]="you are not logged in";

$lang_str["more_categories"]="Más categorías";
$lang_str["category:status"]="Estatus";
$lang_str["category:data_status"]="Estatus";
#$lang_str["category:old_version"]="A new version of this category is being prepared.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

#$lang_str["category_rule_tag:match"]="Match";
$lang_str["category_rule_tag:description"]="Descripción";

#$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Estándar (Mapnik)";
$lang_str["basemap:osmarender"]="Estándar (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

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


