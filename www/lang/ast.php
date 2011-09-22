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
$lang_str["base_language"]="es";

// General
$lang_str["general_info"]="Información Xeneral";
$lang_str["yes"]="si";
$lang_str["no"]="non";
$lang_str["ok"]="Aceutar";
$lang_str["save"]="Guardar";
$lang_str["cancel"]="Encaboxar";
$lang_str["longitude"]=array("Llonxitú", "Llonxitúes");
$lang_str["latitude"]=array("Llatitú", "Llatitúes");
$lang_str["noname"]="(ensin nome)";
$lang_str["info_back"]="dir a vista xeneral";
$lang_str["info_zoom"]="zoom";
$lang_str["nothing_found"]="nun s'alcontró";
$lang_str["loading"]="Cargando...";
$lang_str["more"]="más";
$lang_str["unnamed"]="ensin nome";
$lang_str["zoom"]="Nivel de zoom";

// Headings
$lang_str["head:general_info"]="Información Xeneral";
$lang_str["head:stops"]="Paraes";
$lang_str["head:routes"]="Rutes";
$lang_str["head:members"]="Miembros";
$lang_str["head:address"]="Direición";
$lang_str["head:internal"]="OSM Internu";
$lang_str["head:services"]="Servicios";
$lang_str["head:culture"]="Cultura";
$lang_str["head:graves"]="Sepultures importantes";
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
$lang_str["routing_disclaimer"]="Enrutamientu: (c) de <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Escueyi una categoría pa ver el conteníu del mapa o calca nún oxetu del mapa pa ver los detalles";
$lang_str["list_leisure_sport_tourism"]="Recréu, deporte y turismu";

// Mapkey

$lang_str["grave_is_on"]="Acentu grave activáu";

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
$lang_str["help:language_support"]="Puedes escoyer les tos llingües preferíes con estes opciones. La primer opción camuda la llingua de la interfaz d'usuariu. La segunda opción camuda la llingua de los datos. Los datos de munchos oxetos xeográficos tan traducíos a delles llingües. Si nun hai traducción disponible o si s'escueye \"Llingua llocal\", s'amuesa na llingua principal del oxetu.";
$lang_str["options:ui_lang"]="Llingua de la interfaz";
$lang_str["options:data_lang"]="Llingua de los datos";
$lang_str["lang:"]="Llingua llocal";

$lang_str["overlay:data"]="Datos";
$lang_str["overlay:draggable"]="Marques";

$lang_str["user:no_auth"]="Nome d'usuariu o contraseña enquivocaos";
$lang_str["user:login_text"]="Coneutase a OpenStreetBrowser:";
$lang_str["user:create_user"]="Crear un usuariu nuevu:";
$lang_str["user:username"]="Nome d'usuariu";
$lang_str["user:email"]="Señes de corréu";
$lang_str["user:password"]="Contraseña";
$lang_str["user:password_verify"]="Comprobación de contraseña";
$lang_str["user:old_password"]="Contraseña antigua";
$lang_str["user:no_username"]="¡Tienes de dar un nome d'usuariu!";
$lang_str["user:password_no_match"]="¡Les contraseñes nun casen!";
$lang_str["user:full_name"]="Nome completu";
$lang_str["user:user_exists"]="El nome d'usuariu yá esiste";
$lang_str["user:login"]="Coneutar";
$lang_str["user:logged_in_as"]="Coneutáu como ";
$lang_str["user:logout"]="Desconeutar";

$lang_str["error"]="Hebo un fallu: ";
$lang_str["error:not_logged_in"]="nun tas coneutáu";

$lang_str["more_categories"]="Más categoríes";
$lang_str["category:status"]="Estáu";
$lang_str["category:data_status"]="Estáu";
$lang_str["category:old_version"]="Ta preparándose una versión nueva d'esta categoría.";
$lang_str["category:not_compiled"]="Ta preparándose una nueva categoría.";

$lang_str["category_rule_tag:match"]="Concasa";
$lang_str["category_rule_tag:description"]="Descripción";
$lang_str["category_chooser:choose"]="Escueyi una categoría";
$lang_str["category_chooser:new"]="Nueva categoría";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Estándar (Mapnik)";
$lang_str["basemap:osmarender"]="Estándar (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";

// please finish this list, see list.php for full list of languages
$lang_str["lang:de"]="Alemán";
$lang_str["lang:bg"]="Búlgaru";
$lang_str["lang:cs"]="Checu";
$lang_str["lang:en"]="Inglés";
$lang_str["lang:es"]="Español";
$lang_str["lang:it"]="Italianu";
$lang_str["lang:fr"]="Francés";
$lang_str["lang:uk"]="Ucraín";
$lang_str["lang:ru"]="Rusu";
$lang_str["lang:ja"]="Xaponés";
$lang_str["lang:hu"]="Húngaru";
$lang_str["lang:nl"]="Neerlandés";
$lang_str["lang:ast"]="Asturianu";
