<?
// У всех непереведённых строк стоит # в начале.
// Когда строка переведена, пожалуйста, удалите этот символ.

//  Каждая строка выглядит следующим образом:
//  $lang_str["restaurant"]=array("Ресторан", "Рестораны");
//  первым элементом является перевод строки в Единственном числе,
//  вторым - во Множественном.
//
//  Так же, Вы можете определить Род (F, M, N) слова, например
//  русский перевод слова офис:
//  $lang_str["office"]=array(M, "Офис", "Офисы");
//  Где M - прописная латинская (не кириллическая) буква "m".
//
//  Если Единственное/Множественное число неупотребимо,
//  вы можете отбросить массив, например:
//  $lang_str["help"]="Помощь";

// General
$lang_str["general_info"]="Общая информация";
$lang_str["yes"]="Да";
$lang_str["no"]="Нет";
$lang_str["save"]=array("Сохранить");
$lang_str["cancel"]=array("Отмена");
$lang_str["longitude"]=array( F, "Долгота", "Долготы" );
$lang_str["latitude"]=array( F, "Широта", "Широты" );
$lang_str["noname"]="(неизвестно)";
$lang_str["info_back"]="Возврат к обзору";
$lang_str["info_zoom"]="Приближение";
$lang_str["nothing_found"]=array("Не найдено");
$lang_str["loading"]=array("Загрузка...");

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
$lang_str["head:actions"]=array( N, "Действие", "Действия" );
$lang_str["head:location"]="Местоположение";

$lang_str["action_browse"]="просмотреть в OSM";
$lang_str["action_edit"]="править в OSM";

$lang_str["geo_click_pos"]=array("Кликните на карте на участок, на котором находитесь");
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

$lang_str["start:choose"]=array("Выберите перспективу для карты");
$lang_str["start:geolocation"]=array("Определить местоположение");
$lang_str["start:lastview"]=array("Последняя перспектива");
$lang_str["start:savedview"]=array("Последняя ссылка");
$lang_str["start:startnormal"]=array("Сохранить перспективу");
$lang_str["start:remember"]=array("Запомнить решение");
$lang_str["start:edit"]=array("Правка...");

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

$lang_str["wikipedia:read_more"]="Прочесть ещё";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Стандарт (Mapnik)";
$lang_str["basemap:osmarender"]="Стандарт (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";

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


