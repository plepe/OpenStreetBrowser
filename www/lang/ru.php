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
//  $lang_str["office"]=array(N, "Buro", "Buros");
//
//  If a Singular/Plural form is not
//  suitable/necessary you can ignore the array, e.g.
//  $lang_str["help"]="Help";

// General
$lang_str["general_info"]="Общая информация";
$lang_str["yes"]="Да";
$lang_str["no"]="Нет";
$lang_str["save"]="Сохранить";
$lang_str["cancel"]="Отмена";
$lang_str["longitude"]="Долготы";
$lang_str["latitude"]="Широты";
$lang_str["noname"]="(неизвестно)";
$lang_str["info_back"]="Возврат к обзору";
$lang_str["info_zoom"]="Приближение";
$lang_str["nothing_found"]="Не найдено";
$lang_str["loading"]="Загрузка...";
$lang_str["more"]="Ещё";

// Headings
$lang_str["head:general_info"]="Общая информация";
$lang_str["head:stops"]="Остановки";
$lang_str["head:routes"]="Маршруты";
$lang_str["head:members"]="Члены";
$lang_str["head:address"]="Адреса";
$lang_str["head:internal"]="База OSM";
$lang_str["head:wikipedia"]="Википедия";
$lang_str["head:housenumbers"]="Номера домов";
$lang_str["head:roads"]="Дороги";
$lang_str["head:rails"]="Железные дороги";
$lang_str["head:places"]="Места";
$lang_str["head:borders"]="Границы";
$lang_str["head:landuse"]="Землепользования";
$lang_str["head:buildings"]="Здания";
$lang_str["head:pt"]="Общественный транспорт";
$lang_str["head:services"]="Услуги";
$lang_str["head:culture"]="Культура";
$lang_str["head:graves"]="Места погребения";
$lang_str["head:routing"]="Направления движения";
$lang_str["head:search"]="Поиск";
$lang_str["head:actions"]=array("N", "Действие", "Действия");
$lang_str["head:location"]="Местоположение";
$lang_str["head:tags"]=array("M", "Тег", "Теги");
$lang_str["head:whats_here"]="Что тут?";

$lang_str["action_browse"]="просмотреть в OSM";
$lang_str["action_edit"]="править в OSM";

$lang_str["geo_click_pos"]="Кликните на карте на участок, на котором находитесь";
$lang_str["geo_set_pos"]="Определить моё местоположение";
$lang_str["geo_change_pos"]="Задать моё местоположение";

$lang_str["routing_type_car"]="Автомобиль";
$lang_str["routing_type_car_shortest"]="Автомобиль (короткий)";
$lang_str["routing_type_bicycle"]="Велосипед";
$lang_str["routing_type_foot"]="Пешком";
$lang_str["routing_type"]="Тип маршрута";
$lang_str["routing_distance"]="Расстояние";
$lang_str["routing_time"]="Время";
$lang_str["routing_disclaimer"]="Маршруты: (c) от <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Выберите категорию для поиска на карте или нажмите на объект для получения информации";
$lang_str["list_leisure_sport_tourism"]="Отдых, туризм и спорт";

// Mapkey
$lang_str["map_key:head"]="Обозначения на карте";
$lang_str["map_key:zoom"]="Уровень приближения";

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:map_key"]="Обозначения на карте";
$lang_str["main:options"]="Настройки";
$lang_str["main:about"]="Информация";
$lang_str["main:donate"]="Помочь проекту";
$lang_str["main:licence"]="Информация о карте: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> авторы | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Стефан Плепелти</a> и <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">другие</a>";
$lang_str["main:permalink"]="Ссылка";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Объект не найден</h1>Не найдено объектов соответствующих ID \"%s\". Это могло произойти по таким причинам:<ul><li>Неверный ID.</li><li>Объект был идентифицирован сайтом третьей стороны и (пока что) не добавлен в OpenStreetMap.</li><li>Объект находится не в зоне покрытия карты.</li><li>Ссылка, которая Вас сюда привела не является актуальной для OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Автомасштабирование";
$lang_str["help:autozoom"]="При выборе объекта, карта сфокусируется на нём, текущие настройки приближения могут измениться. С помощью этой функции Вы можете выбрать режим отображения.";
$lang_str["options:autozoom:pan"]="Сфокусироваться на объекте (качественней)";
$lang_str["options:autozoom:move"]="Переход к текущему объекту (быстрее)";
$lang_str["options:autozoom:stay"]="Никогда не менять режим просмотра";

$lang_str["options:language_support"]="Языковая поддержка";
$lang_str["help:language_support"]="С помощью этой опции Вы можете выбрать нужный язык. Первый пункт изменяет язык интерфейса. Второй пункт — изменение языка данных на карте. Информация о многих объектах переведена на несколько языков. Если перевод недоступен или выбрана опция \"Определить язык автоматически\", то информация отображается на основном языке.";
$lang_str["options:ui_lang"]="Язык интерфейса";
$lang_str["options:data_lang"]="Язык информации на карте";
$lang_str["lang:"]="Определить язык автоматически";

$lang_str["overlay:data"]="Данные";
$lang_str["overlay:draggable"]="Отметки";


$lang_str["user:no_auth"]="Неправильный пароль или имя пользователя!";
$lang_str["user:login_text"]="Входим в OpenStreetBrowser:";
$lang_str["user:create_user"]="Создать нового пользователя:";
$lang_str["user:username"]="Имя пользователя";
$lang_str["user:email"]="E-mail адрес";
$lang_str["user:password"]="Пароль";
$lang_str["user:password_verify"]="Проверить пароль";
$lang_str["user:old_password"]="Старый пароль";
$lang_str["user:no_username"]="Пожалуйста, укажите имя пользователя!";
$lang_str["user:password_no_match"]="Пароли не совпадают!";
$lang_str["user:full_name"]="Полное имя";
$lang_str["user:user_exists"]="Пользователь уже существует";
$lang_str["user:login"]="Войти";
$lang_str["user:logged_in_as"]="Вы вошли как ";
$lang_str["user:logout"]="Выйти";

$lang_str["error"]="Ошибка: ";
$lang_str["error:not_logged_in"]="Вы не вошли в систему";

$lang_str["more_categories"]="Больше категорий";
$lang_str["category:status"]="Статус";
$lang_str["category:data_status"]="Статус";
$lang_str["category:old_version"]="Готовится новая версия этой категории.";
$lang_str["category:not_compiled"]="Новая категория была подготовлена.";

$lang_str["category_rule_tag:match"]="Заголовок";
$lang_str["category_rule_tag:description"]="Описание";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Mapnik";
$lang_str["basemap:osmarender"]="OsmaRender";
$lang_str["basemap:cyclemap"]="CycleMap";

if(function_exists("lang"))
$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Объект не найден</h1>Объектов с ID \"%s\" не найдено. Это может быть связано с одной (или более) из следующих причин: <ul><li>Не правильный ID.</li><li>Объект был определен сайтами сторонних производителей и не является (пока) доступным в OpenStreetBrowser.</li><li>Объект находится вне поддерживается области.</li><li>Ссылка по которой Вы сюда пришли оказалась старой и объект уже был удалён из OpenStreetMap.</li></ul>";

// please finish this list, see list.php for full list of languages
$lang_str["lang:de"]="Немецкий";
$lang_str["lang:bg"]="Болгарский";
$lang_str["lang:cs"]="Чешский";
$lang_str["lang:en"]="Английский";
$lang_str["lang:es"]="Испанский";
$lang_str["lang:it"]="Итальянский";
$lang_str["lang:fr"]="Французский";
$lang_str["lang:uk"]="Украинский";
$lang_str["lang:ru"]="Русский";
$lang_str["lang:ja"]="Японский";
$lang_str["lang:hu"]="Венгерский";


