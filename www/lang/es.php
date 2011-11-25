<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
#$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Español"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Información General";
$lang_str["yes"]="si";
$lang_str["no"]="no";
#$lang_str["ok"]="Ok";
$lang_str["save"]="Guardar";
#$lang_str["saved"]="Saved"; // for dialog boxes confirming saving
$lang_str["cancel"]="Cancelar";
#$lang_str["show"]="Show";
#$lang_str["edit"]="Edit";
#$lang_str["delete"]="Delete";
#$lang_str["history"]="History";
#$lang_str["choose"]="Choose";
#$lang_str["help"]="Help";
$lang_str["longitude"]=array("Longitud", "Longitudes");
$lang_str["latitude"]=array("Latitud", "Latitudes");
$lang_str["noname"]="(sin_nombre)";
#$lang_str["info_back"]="back to overview";
$lang_str["info_zoom"]="zoom";
#$lang_str["nothing_found"]="nothing found";
#$lang_str["list:zoom_for_obs"]="Zoom in for less important objects";
$lang_str["loading"]="Cargando...";
$lang_str["more"]="más";
#$lang_str["source"]="Source";
#$lang_str["unnamed"]="unnamed";
$lang_str["zoom"]="Nivel de Zoom";
#$lang_str["no_message"]=array("no message", "no messages");

// Headings
$lang_str["head:general_info"]="Información General";
#$lang_str["head:stops"]="Stops";
$lang_str["head:routes"]="Rutas";
$lang_str["head:members"]="Miembros";
$lang_str["head:address"]="Direccion";
#$lang_str["head:internal"]="OSM Internal";
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

#$lang_str["geo_click_pos"]="Click on your position on the map";
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

$lang_str["list_info"]="Elige una categoría para explorar el contenido del mapa, o haga clic en un objeto en el mapa para ver los detalles";
#$lang_str["list_leisure_sport_tourism"]="Leisure, Sport and Tourism";

// Mapkey

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:options"]="Opciones";
$lang_str["main:about"]="Acerca de...";
$lang_str["main:donate"]="Donar";
#$lang_str["main:licence"]="Map Data: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
$lang_str["main:permalink"]="Enlace Permanente";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

#$lang_str["options:autozoom"]="Autozoom behaviour";
#$lang_str["help:autozoom"]="When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.";
#$lang_str["options:autozoom:pan"]="Pan to current object (nicer)";
#$lang_str["options:autozoom:move"]="Move to current object (faster)";
#$lang_str["options:autozoom:stay"]="Never change viewport automatically";

#$lang_str["options:language_support"]="Language Support";
#$lang_str["help:language_support"]="You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.";
$lang_str["options:ui_lang"]="Idioma de interfaz";
$lang_str["options:data_lang"]="Idioma de datos";
$lang_str["lang:"]="Idioma local";
#$lang_str["lang:auto"]="Same as interface language";

#$lang_str["overlay:data"]="Data";
#$lang_str["overlay:draggable"]="Markers";

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

#$lang_str["attention"]="Attention: ";
$lang_str["error"]="Ha ocurrido un error: ";
#$lang_str["error:not_logged_in"]="you are not logged in";

#$lang_str["category"]=array("Category", "Categories");
$lang_str["more_categories"]="Más categorías";
$lang_str["category:status"]="Estatus";
$lang_str["category:data_status"]="Estatus";
#$lang_str["category:old_version"]="A new version of this category is being prepared.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

#$lang_str["category:new_rule"]="New Rule";
#$lang_str["category_rule_tag:match"]="Match";
$lang_str["category_rule_tag:description"]="Descripción";
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";
#$lang_str["category:sub_category"]=array("Sub-category", "Sub-categories");

#$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Estándar (Mapnik)";
$lang_str["basemap:osmarender"]="Estándar (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";
