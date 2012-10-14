<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
#$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Українська"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Загальна інформація";
$lang_str["yes"]="Так";
$lang_str["no"]="Ні";
$lang_str["ok"]="Ок";
$lang_str["save"]="Зберегти";
$lang_str["saved"]="Збережено"; // for dialog boxes confirming saving
$lang_str["cancel"]="Скасувати";
$lang_str["show"]="Показати";
$lang_str["edit"]="Редагувати";
$lang_str["delete"]="Видалити";
$lang_str["history"]="Історія";
$lang_str["choose"]="Вибрати";
$lang_str["help"]="Допомога";
$lang_str["longitude"]=array("Довгота", "Довготи");
$lang_str["latitude"]=array("Широта", "Широти");
$lang_str["noname"]="(невідомо)";
$lang_str["info_back"]="Повернення до огляду";
$lang_str["info_zoom"]="Наблизити";
$lang_str["nothing_found"]="Не знайдено";
$lang_str["list:zoom_for_obs"]="Збільшити менш важливі об'єкти";
$lang_str["loading"]="Завантаження...";
$lang_str["more"]="Ще";
$lang_str["source"]="Джерело";
$lang_str["unnamed"]="безіменний";
$lang_str["zoom"]="Рівень наближення";
$lang_str["no_message"]=array("немає повідомлення", "немає повідомлень");
$lang_str["ad"]=array("Реклама", "Реклами");

// Headings
$lang_str["head:general_info"]="Загальна інформація";
$lang_str["head:stops"]="Зупинки";
$lang_str["head:routes"]="Маршрути";
$lang_str["head:members"]="Члени";
$lang_str["head:address"]="Адреси";
$lang_str["head:internal"]="База OSM";
$lang_str["head:services"]="Послуги";
$lang_str["head:culture"]="Культура";
$lang_str["head:routing"]="Напрямки руху";
$lang_str["head:search"]="Пошук";
$lang_str["head:actions"]=array("Дія", "Дії");
$lang_str["head:location"]="Розташування";
$lang_str["head:tags"]=array("Тег", " Теги");
$lang_str["head:whats_here"]="Що тут?";

$lang_str["action_browse"]="перегляд в OSM";
$lang_str["action_edit"]="редагувати в OSM";

$lang_str["geo_click_pos"]="Клацніть ділянку на карті, на якому знаходитесь";
$lang_str["geo_set_pos"]="Встановити моє місцезнаходження";
$lang_str["geo_change_pos"]="Змінити моє місцезнаходження";

$lang_str["routing_type_car"]="Автомобільний";
$lang_str["routing_type_car_shortest"]="Автомобільний (найкоротший)";
$lang_str["routing_type_bicycle"]="Велосипедний";
$lang_str["routing_type_foot"]="Піший";
$lang_str["routing_type"]="Тип маршруту";
$lang_str["routing_distance"]="Відстань";
$lang_str["routing_time"]="Час";

$lang_str["list_info"]="Виберіть категорію для пошуку на карті або натисніть на об'єкт для отримання інформації";
$lang_str["list_leisure_sport_tourism"]="Дозвілля, Спорт та Туризм";

// Mapkey


$lang_str["main:help"]="Допомога";
$lang_str["main:options"]="Налаштування";
$lang_str["main:about"]="Инфо";
$lang_str["main:donate"]="Допомогти проекту";
$lang_str["main:licence"]="Map Data: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">contributors</a>";
$lang_str["main:permalink"]="Постійне посилання";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Автомасштабування";
$lang_str["help:autozoom"]="При виборі обєкту на мапі, точка огляду змінюється так, щоб вмістити весь обєкт. Масштаб при цьому теж може змінитись. З цією опцією ви можете вибрати один з наступних режимів:";
$lang_str["options:autozoom:pan"]="Плавно переміститись в обрану точку (ефектніше)";
$lang_str["options:autozoom:move"]="Моментально переміститись в обрану точку (швидше)";
$lang_str["options:autozoom:stay"]="Ніколи не змінювати точку огляду автоматично";

$lang_str["options:language_support"]="Підтримка мови";
$lang_str["help:language_support"]="You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.";
$lang_str["options:ui_lang"]="Мова інтерфейсу";
$lang_str["options:data_lang"]="Мова мапи";
$lang_str["lang:"]="Місцева мова";
$lang_str["lang:auto"]="Те ж, що мова інтерфейсу";

$lang_str["overlay:data"]="Дані";
$lang_str["overlay:draggable"]="Маркери";

#$lang_str["user:no_auth"]="Username or password wrong!";
$lang_str["user:login_text"]="Входимо в OpenStreetBrowser:";
$lang_str["user:create_user"]="Створити нового користувача:";
$lang_str["user:username"]="Ім'я користувача";
$lang_str["user:email"]="E-mail адреса";
$lang_str["user:password"]="Пароль";
$lang_str["user:password_verify"]="Перевірити пароль";
$lang_str["user:old_password"]="Старий пароль";
$lang_str["user:no_username"]="Будь ласка, вкажіть ім'я користувача!";
$lang_str["user:password_no_match"]="Паролі не збігаються!";
$lang_str["user:full_name"]="Повне ім'я";
$lang_str["user:user_exists"]="Користувач вже існує";
$lang_str["user:login"]="Увійти";
$lang_str["user:logged_in_as"]="Ви увійшли як";
$lang_str["user:logout"]="Вийти";

$lang_str["attention"]="Увага:";
$lang_str["error"]="Помилка:";
$lang_str["error:not_logged_in"]="Ви не увійшли в систему";

$lang_str["category"]=array("Категорія", "Категорії");
$lang_str["more_categories"]="Більше категорій";
$lang_str["category:status"]="Статус";
$lang_str["category:data_status"]="Статус";
$lang_str["category:old_version"]="Готується нова версія цієї категорії.";
$lang_str["category:not_compiled"]="Нова категорія була підготовлена.";

$lang_str["category:new_rule"]="Нове правило";
$lang_str["category_rule_tag:match"]="Заголовок";
$lang_str["category_rule_tag:description"]="Опис";
$lang_str["category_chooser:choose"]="Вибрати категорію";
$lang_str["category_chooser:new"]="Нова категорія";
$lang_str["category:sub_category"]=array("Під-категорія", "Під-категорії");

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:osb_light"]="OpenStreetBrowser (блідий)";
$lang_str["basemap:mapnik"]="Стандартний (Mapnik)";
$lang_str["basemap:osmarender"]="Стандартний (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";
