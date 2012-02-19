<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
$lang_str["base_language"]="sr"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Српски"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Опште информације";
$lang_str["yes"]="да";
$lang_str["no"]="не";
$lang_str["ok"]="У реду";
$lang_str["save"]="Запамти";
$lang_str["saved"]="Запамћено"; // for dialog boxes confirming saving
$lang_str["cancel"]="Прекини";
$lang_str["show"]="Прикажи";
$lang_str["edit"]="Преправи";
$lang_str["delete"]="Обриши";
$lang_str["history"]="Историја";
$lang_str["choose"]="Одабери";
$lang_str["help"]="Помоћ";
$lang_str["longitude"]=array("Дужина", "Дужине");
$lang_str["latitude"]=array("Ширина", "Ширине");
$lang_str["noname"]="(без имена)";
$lang_str["info_back"]="назад на преглед";
$lang_str["info_zoom"]="увећање";
$lang_str["nothing_found"]="ништа није пронађено";
$lang_str["list:zoom_for_obs"]="Увећај да видиш мање битне објекте";
$lang_str["loading"]="Учитавање";
$lang_str["more"]="још";
$lang_str["source"]="Извор";
$lang_str["unnamed"]="без имена";
$lang_str["zoom"]="Степен увећања";
$lang_str["no_message"]=array("нема поруке", "нема порука");
$lang_str["ad"]=array("Реклама", "Рекламе");

// Headings
$lang_str["head:general_info"]="Опште информације";
$lang_str["head:stops"]=array("Станица", "Станице");
$lang_str["head:routes"]=array("Траса", "Трасе");
$lang_str["head:members"]=array("Члан", "Чланови");
$lang_str["head:address"]=array("Адреса", "Адресе");
$lang_str["head:internal"]="ОУК унутрашње";
$lang_str["head:services"]="Услуге";
$lang_str["head:culture"]="Култура";
$lang_str["head:routing"]="Трасирање";
$lang_str["head:search"]="Претрага";
$lang_str["head:actions"]=array("Акција", "Акције");
$lang_str["head:location"]="Локација";
$lang_str["head:tags"]=array("Ознака", "Ознаке");
$lang_str["head:whats_here"]="Шта је овде?";

$lang_str["action_browse"]="отвори у ОУК";
$lang_str["action_edit"]="преправи у ОУК";

$lang_str["geo_click_pos"]="Кликни на своју позицију на карти";
$lang_str["geo_set_pos"]="Одреди моју позицију";
$lang_str["geo_change_pos"]="Промени моју позицију";

$lang_str["routing_type_car"]="Аутомобил";
$lang_str["routing_type_car_shortest"]="Аутомобил (Најкраће)";
$lang_str["routing_type_bicycle"]="Бицикл";
$lang_str["routing_type_foot"]="Пешке";
$lang_str["routing_type"]="Врста трасе";
$lang_str["routing_distance"]="Раздаљина";
$lang_str["routing_time"]="Време";

$lang_str["list_info"]="Одабери категорију за преглед карте или кликни на објекат на карти за детаље";
$lang_str["list_leisure_sport_tourism"]="Одмор, Спорт и Туризам";

// Mapkey


$lang_str["main:help"]="Помоћ";
$lang_str["main:options"]="Опције";
$lang_str["main:about"]="О";
$lang_str["main:donate"]="Донирај";
$lang_str["main:licence"]="Картографски подаци: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap (ОтворенаУличнаКарта)</a> учествовали | ОУК: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
#$lang_str["main:permalink"]="Permalink";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

#$lang_str["options:autozoom"]="Autozoom behaviour";
#$lang_str["help:autozoom"]="When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.";
#$lang_str["options:autozoom:pan"]="Pan to current object (nicer)";
#$lang_str["options:autozoom:move"]="Move to current object (faster)";
#$lang_str["options:autozoom:stay"]="Never change viewport automatically";

#$lang_str["options:language_support"]="Language Support";
#$lang_str["help:language_support"]="You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.";
#$lang_str["options:ui_lang"]="Interface language";
#$lang_str["options:data_lang"]="Data language";
#$lang_str["lang:"]="Local language";
#$lang_str["lang:auto"]="Same as interface language";

#$lang_str["overlay:data"]="Data";
#$lang_str["overlay:draggable"]="Markers";

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

#$lang_str["attention"]="Attention: ";
#$lang_str["error"]="An error occured: ";
#$lang_str["error:not_logged_in"]="you are not logged in";

#$lang_str["category"]=array("Category", "Categories");
#$lang_str["more_categories"]="More categories";
#$lang_str["category:status"]="Status";
#$lang_str["category:data_status"]="Status";
#$lang_str["category:old_version"]="A new version of this category is being prepared.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

#$lang_str["category:new_rule"]="New Rule";
#$lang_str["category_rule_tag:match"]="Match";
#$lang_str["category_rule_tag:description"]="Description";
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";
#$lang_str["category:sub_category"]=array("Sub-category", "Sub-categories");

#$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:mapnik"]="Standard (Mapnik)";
#$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";
