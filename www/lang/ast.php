<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Asturianu"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Información Xeneral";
$lang_str["yes"]="si";
$lang_str["no"]="non";
$lang_str["ok"]="Aceutar";
$lang_str["save"]="Guardar";
$lang_str["saved"]="Guardáu"; // for dialog boxes confirming saving
$lang_str["cancel"]="Encaboxar";
$lang_str["show"]="Amosar";
$lang_str["edit"]="Editar";
$lang_str["delete"]="Desaniciar";
$lang_str["history"]="Historial";
$lang_str["choose"]="Escoyer";
$lang_str["help"]="Ayuda";
$lang_str["longitude"]=array("Llonxitú", "Llonxitúes");
$lang_str["latitude"]=array("Llatitú", "Llatitúes");
$lang_str["noname"]="(ensin nome)";
$lang_str["info_back"]="dir a vista xeneral";
$lang_str["info_zoom"]="zoom";
$lang_str["nothing_found"]="nun s'alcontró";
$lang_str["list:zoom_for_obs"]="Aumenta pa ver oxetos menos importantes";
$lang_str["loading"]="Cargando...";
$lang_str["more"]="más";
$lang_str["source"]="Orixe";
$lang_str["unnamed"]="ensin nome";
$lang_str["zoom"]="Nivel de zoom";
$lang_str["no_message"]="dengún mensaxe";
$lang_str["ad"]=array("Anunciu", "Anuncios");

// Headings
$lang_str["head:general_info"]="Información xeneral";
$lang_str["head:stops"]="Paraes";
$lang_str["head:routes"]="Rutes";
$lang_str["head:members"]="Miembros";
$lang_str["head:address"]="Direición";
$lang_str["head:internal"]="OSM Internu";
$lang_str["head:services"]="Servicios";
$lang_str["head:culture"]="Cultura";
$lang_str["head:routing"]="Enrutamientu";
$lang_str["head:search"]="Gueta";
$lang_str["head:actions"]=array("Aición", "Aiciones");
$lang_str["head:location"]="Llocalización";
$lang_str["head:tags"]=array("Etiqueta", "Etiquetes");
$lang_str["head:whats_here"]="¿Qué hai equí?";

$lang_str["action_browse"]="ver en OSM";
$lang_str["action_edit"]="editar en OSM";

$lang_str["geo_click_pos"]="Calca na to posición nel mapa";
$lang_str["geo_set_pos"]="Conseñar la mio posición";
$lang_str["geo_change_pos"]="Camudar la mio posición";

$lang_str["routing_type_car"]="Coche";
$lang_str["routing_type_car_shortest"]="Coche (la más curtia)";
$lang_str["routing_type_bicycle"]="Bicicleta";
$lang_str["routing_type_foot"]="A pie";
$lang_str["routing_type"]="Triba de ruta";
$lang_str["routing_distance"]="Distancia";
$lang_str["routing_time"]="Tiempu";

$lang_str["list_info"]="Escueyi una categoría pa ver el conteníu del mapa o calca nún oxetu del mapa pa ver los detalles";
$lang_str["list_leisure_sport_tourism"]="Recréu, deporte y turismu";

// Mapkey


$lang_str["main:help"]="Ayuda";
$lang_str["main:options"]="Opciones";
$lang_str["main:about"]="Tocante a";
$lang_str["main:donate"]="Donativos";
$lang_str["main:licence"]="Datos del mapa: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> collaboradores de <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> y <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">collaboradores</a>";
$lang_str["main:permalink"]="Enllaz permanente";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Nun s'alcontró l'oxetu</h1>Nun se pudo alcontrar l'oxetu cola ID \"%s\". Esto pue ser por una (o más) de les razones darréu:<ul><li>La ID ta enquivocada.</li><li>L'oxetu ta identificáu pol sitiu d'un terceru y (entá) nun ta disponible nel OpenStreetBrowser.</li><li>L'oxetu ta fuera del área con sofitu.</li><li>L'enllaz que siguisti ye antiguu y l'oxetu se desanició d'OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Comportamientu del zoom automáticu";
$lang_str["help:autozoom"]="Al escoyer un oxetu, el visor muevese al mesmu, y el nivel de zoom tamién puede camudar. Con esta opción puedes  escoyer ente diferentes moos.";
$lang_str["options:autozoom:pan"]="Dir al oxetu actual (más guapo)";
$lang_str["options:autozoom:move"]="Mover al oxetu actual (más rápido)";
$lang_str["options:autozoom:stay"]="Nun camudar el visor automáticamente nunca";

$lang_str["options:language_support"]="Sofitu de llingües";
$lang_str["help:language_support"]="Pues escoyer les tos llingües preferíes con estes opciones. La primer opción camuda la llingua de la interfaz d'usuariu. La segunda opción camuda la llingua de los datos. Los datos de munchos oxetos xeográficos tan traducíos a delles llingües. Si nun hai traducción disponible o si s'escueye \"Llingua llocal\", s'amuesa na llingua principal del oxetu.";
$lang_str["options:ui_lang"]="Llingua de la interfaz";
$lang_str["options:data_lang"]="Llingua de los datos";
$lang_str["lang:"]="Llingua llocal";
$lang_str["lang:auto"]="Igual que la llingua de la interfaz";

$lang_str["overlay:data"]="Datos";
$lang_str["overlay:draggable"]="Marques";

$lang_str["user:no_auth"]="¡Nome d'usuariu o conseña enquivocaos!";
$lang_str["user:login_text"]="Coneutase a OpenStreetBrowser:";
$lang_str["user:create_user"]="Crear un usuariu nuevu:";
$lang_str["user:username"]="Nome d'usuariu";
$lang_str["user:email"]="Señes de corréu";
$lang_str["user:password"]="Conseña";
$lang_str["user:password_verify"]="Comprobación de conseña";
$lang_str["user:old_password"]="Conseña antigua";
$lang_str["user:no_username"]="¡Tienes de dar un nome d'usuariu!";
$lang_str["user:password_no_match"]="¡Les conseñes nun casen!";
$lang_str["user:full_name"]="Nome completu";
$lang_str["user:user_exists"]="El nome d'usuariu yá esiste";
$lang_str["user:login"]="Coneutar";
$lang_str["user:logged_in_as"]="Coneutáu como ";
$lang_str["user:logout"]="Desconeutar";

$lang_str["attention"]="Atención:";
$lang_str["error"]="Hebo un fallu: ";
$lang_str["error:not_logged_in"]="nun tas coneutáu";

$lang_str["category"]=array("Categoría", "Categoríes");
$lang_str["more_categories"]="Más categoríes";
$lang_str["category:status"]="Estáu";
$lang_str["category:data_status"]="Estáu";
$lang_str["category:old_version"]="Ta preparándose una versión nueva d'esta categoría.";
$lang_str["category:not_compiled"]="Ta preparándose una nueva categoría.";

$lang_str["category:new_rule"]="Regla nueva";
$lang_str["category_rule_tag:match"]="Concasa";
$lang_str["category_rule_tag:description"]="Descripción";
$lang_str["category_chooser:choose"]="Escueyi una categoría";
$lang_str["category_chooser:new"]="Nueva categoría";
$lang_str["category:sub_category"]=array("Sub-categoría", "Sub-categoríes");

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:osb_light"]="OpenStreetBrowser (dilíu)";
$lang_str["basemap:mapnik"]="Estándar (Mapnik)";
$lang_str["basemap:osmarender"]="Estándar (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";
