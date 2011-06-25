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

// the following language strings are deprecated
$lang_str["cat:leisure"]="Покупки, развлечения, спорт ";
$lang_str["cat:leisure/gastro"]="Гастрономия";
$lang_str["cat:leisure/leisure"]="Досуг";
$lang_str["cat:leisure/sport"]="Спорт";
$lang_str["cat:leisure/shop"]="Покупки";
$lang_str["cat:culture"]="Культура и религия";
$lang_str["cat:culture/culture"]="Культура";
$lang_str["cat:culture/religion"]="Религия";
$lang_str["cat:culture/historic"]="Историческое";
$lang_str["cat:culture/tourism"]="Туризм";
$lang_str["cat:shop"]="Покупки";
$lang_str["cat:services"]="Услуги";
$lang_str["cat:services/communication"]="Связь";
$lang_str["cat:services/financial"]="Финансы";
$lang_str["cat:services/emergency"]="Скорая помощь / Госпитализация";
$lang_str["cat:services/health"]="Здоровье";
$lang_str["cat:services/education"]="Образование";
$lang_str["cat:services/public"]="Комменальные услуги";
$lang_str["cat:services/tourism"]="Туристические услуги";
$lang_str["cat:places"]=array( N, "Место", "Места" );
$lang_str["cat:places/places"]=array( N, "Место", "Места" );
$lang_str["cat:places/residential"]="Жилые районы";
$lang_str["cat:places/streets"]="Муниципальные";
$lang_str["cat:places/natural"]="Природные образования";
$lang_str["cat:transport"]="Транспорт";
$lang_str["cat:transport/car"]="Частный транспорт";
$lang_str["cat:transport/car/amenities"]="Транспортные принадлежности";
$lang_str["cat:transport/car/routes"]="Маршруты";
$lang_str["cat:transport/car/furniture"]="Уличные строения";
$lang_str["cat:transport/pt"]="Общественный транспорт";
$lang_str["cat:transport/pt/amenities"]="Транспортные принадлежности";
$lang_str["cat:transport/pt/routes"]="Маршруты";
$lang_str["cat:transport/pt/stops"]="Остановки и станции";
$lang_str["cat:transport/alternative"]="Альтернативное передвижение (Велоспорт, Пешие прогулки, ...)";
$lang_str["cat:transport/alternative/amenities"]="Принадлежности";
$lang_str["cat:transport/alternative/routes"]="Маршруты";
$lang_str["cat:transport/other"]="Прочее";
$lang_str["cat:agri_ind"]="Сельское хозяйство и промышленность";
$lang_str["cat:agri_ind/power"]="Производство";
$lang_str["cat:agri_ind/works"]="Фабрики и заводы";
$lang_str["cat:agri_ind/agriculture"]="Сельское хозяйство";
$lang_str["cat:agri_ind/construction"]="Строительсво";
$lang_str["cat:agri_ind/railway"]="Железная дорога";
$lang_str["cat:agri_ind/resources"]="Добыча полезных ископаемых";
$lang_str["cat:agri_ind/landfill"]="Переработка отходов";
$lang_str["cat:agri_ind/military"]="Военное";

$lang_str["sub_type=t3|type=historic"]="Список Всемирного наследия ЮНЕСКО";
$lang_str["sub_type=|type=place_of_worship"]="Места молебен";
$lang_str["sub_type=t1|type=place_of_worship"]="Христианская церковь";
$lang_str["sub_type=t2|type=place_of_worship"]="Исламская мечеть";
$lang_str["sub_type=t3|type=place_of_worship"]="Еврейская синагога";
$lang_str["sub_type=t4|type=place_of_worship"]="Сикхийский храм";

$lang_str["highway_type=motorway"]="Автомагистраль";
$lang_str["highway_type=trunk"]="Дорога для автомобилей";
$lang_str["highway_type=primary"]="Главная дорога";
$lang_str["highway_type=secondary"]="Второстепенная дорога";
$lang_str["highway_type=tertiary"]="Просёлочная дорога";
$lang_str["highway_type=minor"]="Узкая дорога";
$lang_str["highway_type=service"]="Служебная дорога";
$lang_str["highway_type=pedestrian"]="Переходная зона";
$lang_str["highway_type=track"]="Дорожка";
$lang_str["highway_type=path"]="Путь (пеший, велосипедный, гужевой)";
$lang_str["square_type=pedestrian"]="Square";
$lang_str["square_type=parking"]="Parking Zone";
$lang_str["highway_type=aero_run"]="Airport Runway";
$lang_str["highway_type=aero_taxi"]="Airport Taxiway";

$lang_str["sub_type=t1|type=communication"]="Почтовые отделения";
$lang_str["sub_type=t2|type=communication"]="Почтовые ящики";
$lang_str["sub_type=t1|type=economic"]="Банкоматы";
$lang_str["sub_type=t2|type=economic"]="Банки";
$lang_str["sub_type=t1|type=services"]="Переработка";
$lang_str["sub_type=t1|type=man_made"]="Башня";
$lang_str["sub_type=t2|type=man_made"]="Энергия ветра";
$lang_str["sub_type=t3|type=man_made"]="Ветряная мельница";
$lang_str["sub_type=t1|type=emergency"]="Больницы";
$lang_str["sub_type=t1|type=health"]="Аптеки";
$lang_str["sub_type=t1|type=tourism"]="Отели, Гостиницы, ...";
$lang_str["sub_type=t2|type=tourism"]="Лагерь &amp; Кемпинг";
$lang_str["sub_type=t3|type=tourism"]="Туристическая информация";

  // Foos & Drink
  $lang_str["list_food_drink"]="Еда и Напитки";
    $lang_str["list_amenity_biergarten"]="Пивные";
    $lang_str["list_amenity_restaurant"]="Рестораны";
    $lang_str["list_amenity_fast_food"]="Рестораны быстрого питания";
    $lang_str["list_amenity_cafe"]="Кафе";
    $lang_str["list_amenity_vending_machine"]="Торговые автоматы";
    $lang_str["list_amenity_pub"]="Пабы";
    $lang_str["list_amenity_bar"]="Бары";
    $lang_str["list_amenity_nightclub"]="Ночные клубы";
    $lang_str["list_amenity_casino"]="Казино";
// Leisure
  $lang_str["list_leisure"]="Досуг";
    $lang_str["list_leisure_sports_centre"]="Спортивные центры";
    $lang_str["list_leisure_golf_course"]="Гольф";
    $lang_str["list_leisure_stadium"]="Стадионы";
    $lang_str["list_leisure_track"]="Треки";
    $lang_str["list_leisure_pitch"]="Площадки";
    $lang_str["list_leisure_water_park"]="Аквапарки";
    $lang_str["list_leisure_marina"]="Гавани";
    $lang_str["list_leisure_slipway"]="Эллинги";
    $lang_str["list_leisure_fishing"]="Рыбалка";
    $lang_str["list_leisure_nature_reserve"]="Заповедники";
    $lang_str["list_leisure_park"]="Парки";
    $lang_str["list_leisure_playground"]="Игровые площадки";
    $lang_str["list_leisure_garden"]="Сады";
    $lang_str["list_leisure_common"]="Общественные земли";
    $lang_str["list_leisure_ice_rink"]="Ледовые катки";
    $lang_str["list_leisure_miniature_golf"]="Минигольф";
    $lang_str["list_leisure_swimming_pool"]="Бассеный";
    $lang_str["list_leisure_beach_resort"]="Пляжные курорты";
    $lang_str["list_leisure_bird_hide"]="Птицефермы";
    $lang_str["list_leisure_sport"]="Другой спорт";
// Sport
    $lang_str["list_sport_9pin"]="9-ти кеглевый Боулинг";
    $lang_str["list_sport_10pin"]="10-ти кеглевый Боулинг";
    $lang_str["list_sport_archery"]="Стрельба из лука";
    $lang_str["list_sport_athletics"]="Атлетика";
    $lang_str["list_sport_australian_football"]="Австралийский футбол";
    $lang_str["list_sport_baseball"]="Бейсбол";
    $lang_str["list_sport_basketball"]="Баскетбол";
    $lang_str["list_sport_beachvolleyball"]="Пляжный волейбол";
    $lang_str["list_sport_boules"]="Петанк";
    $lang_str["list_sport_bowls"]="Боулз";
    $lang_str["list_sport_canoe"]="Каноэ";
#    $lang_str["list_sport_chess"]="Chess";
#    $lang_str["list_sport_climbing"]="Climbing";
#    $lang_str["list_sport_cricket"]="Cricket";
#    $lang_str["list_sport_cricket_nets"]="Cricket Nets";
#    $lang_str["list_sport_croquet"]="Croquet";
#    $lang_str["list_sport_cycling"]="Cycling";
#    $lang_str["list_sport_diving"]="Diving";
#    $lang_str["list_sport_dog_racing"]="Dog Racing";
#    $lang_str["list_sport_equestrian"]="Equestrian";
#    $lang_str["list_sport_football"]="Football";
#    $lang_str["list_sport_golf"]="Golf";
#    $lang_str["list_sport_gymnastics"]="Gymnastics";
#    $lang_str["list_sport_hockey"]="Hockey";
#    $lang_str["list_sport_horse_racing"]="Horse Racing";
#    $lang_str["list_sport_korfball"]="Korfball";
#    $lang_str["list_sport_motor"]="Motor";
#    $lang_str["list_sport_multi"]="Multi";
#    $lang_str["list_sport_orienteering"]="Orienteering";
#    $lang_str["list_sport_paddle_tennis"]="Paddle Tennis";
#    $lang_str["list_sport_paragliding"]="Paragliding";
#    $lang_str["list_sport_pelota"]="Pelota";
#    $lang_str["list_sport_racquet"]="Racquet";
#    $lang_str["list_sport_rowing"]="Rowing";
#    $lang_str["list_sport_rugby"]="Rugby";
#    $lang_str["list_sport_shooting"]="Shooting";
#    $lang_str["list_sport_skating"]="Skating";
#    $lang_str["list_sport_skateboard"]="Skateboard";
#    $lang_str["list_sport_skiing"]="Skiing";
#    $lang_str["list_sport_soccer"]="Soccer";
#    $lang_str["list_sport_swimming"]="Swimming";
#    $lang_str["list_sport_table_tennis"]="Table Tennis";
#    $lang_str["list_sport_team_handball"]="Handball";
#    $lang_str["list_sport_tennis"]="Tennis";
#    $lang_str["list_sport_volleyball"]="Volleyball";
// Cycle & Hiking
#  $lang_str["list_cycle_hiking"]="Amenities for Cycling and Hiking";
#  $lang_str["list_ch_routes"]="Cycle and Hiking Routes";
#    $lang_str["list_shop_bicycle"]="Bicycle Shops";
#    $lang_str["list_shop_outdoor"]="Outdoor Shops";
#    $lang_str["list_amenity_bicycle_rental"]="Bicycle Rentals";
#    $lang_str["list_amenity_bicycle_parking"]="Bicycle Parkings";
#    $lang_str["list_amenity_shelter"]="Shelters";
#    $lang_str["list_amenity_drinking_water"]="Drinking Water Supplies";
#    $lang_str["list_amenity_signpost"]="Signposts";
#    $lang_str["list_amenity_alpine_hut"]="Alpine Huts";
#    $lang_str["list_tourism_alpine_hut"]="Alpine Huts";
#    $lang_str["list_amenity_mountain_hut"]="Mountain Huts";
#    $lang_str["list_tourism_picnic_site"]="Picnic Sites";
#    $lang_str["list_tourism_viewpoint"]="Viewpoints";
  // Tourism
#  $lang_str["list_tourism"]="Tourism";
#    $lang_str["list_tourism_hotel"]="Hotels";
#    $lang_str["list_tourism_motel"]="Motels";
#    $lang_str["list_tourism_guest_house"]="Guest Houses";
#    $lang_str["list_tourism_hostel"]="Hostels";
#    $lang_str["list_tourism_chalet"]="Chalets";
#    $lang_str["list_tourism_camp_site"]="Camp Sites";
#    $lang_str["list_tourism_caravan_site"]="Caravan Sites";
#    $lang_str["list_tourism_information"]="Tourist Informations";
  // Attractions
#  $lang_str["list_attractions"]="Attractions";
#    $lang_str["list_tourism_theme_park"]="Theme Parks";
#    $lang_str["list_tourism_zoo"]="Zoos";
#    $lang_str["list_tourism_attraction"]="Attractions";

#$lang_str["list_shopping"]="Shopping";
#  $lang_str["list_general"]="General";
#    $lang_str["list_shop_mall"]="Shopping Malls";
#    $lang_str["list_shop_shopping_center"]="Shopping Centers";
#    $lang_str["list_shop_shopping_centre"]="Shopping Centres";
#    $lang_str["list_shop_convenience"]="Convenience Stores";
#    $lang_str["list_shop_department_store"]="Department Stores";
#    $lang_str["list_shop_general"]="General Stores";
#    $lang_str["list_amenity_marketplace"]="Marketplaces";
#  $lang_str["list_food"]="Food";
#    $lang_str["list_shop_supermarket"]="Supermarkets";
#    $lang_str["list_shop_groceries"]="Groceries";
#    $lang_str["list_shop_grocery"]="Groceries";
#    $lang_str["list_shop_alcohol"]="Alcohol";
#    $lang_str["list_shop_bakery"]="Bakeries";
#    $lang_str["list_shop_beverages"]="Beverages";
#    $lang_str["list_shop_butcher"]="Butchers";
#    $lang_str["list_shop_organic"]="Organic Food";
#    $lang_str["list_shop_wine"]="Wine Shops";
#    $lang_str["list_shop_fish"]="Fish Shops";
#    $lang_str["list_shop_market"]="Markets";
#  $lang_str["list_sport"]="Sport";
#    $lang_str["list_shop_sports"]="General Sports";
#    $lang_str["list_shop_bicycle"]="Bicycle Shops";
#    $lang_str["list_shop_outdoor"]="Outdoor Shops";
#  $lang_str["list_culture"]="Culture";
#    $lang_str["list_shop_books"]="Book Shops";
#    $lang_str["list_shop_kiosk"]="Kiosks";
#    $lang_str["list_shop_video"]="Video Shops";
#    $lang_str["list_shop_newsagent"]="Newsagents";
#    $lang_str["list_shop_ticket"]="Ticket Sales";
#    $lang_str["list_shop_music"]="Music Shops";
#    $lang_str["list_shop_photo"]="Photo Shops";
#    $lang_str["list_shop_travel_agency"]="Travel Agencies";
#  $lang_str["list_car"]="Car &amp; Motorcycle";
#    $lang_str["list_shop_car"]="Car Shop";
#    $lang_str["list_shop_car_dealer"]="Car Dealers";
#    $lang_str["list_shop_car_repair"]="Car Repair";
#    $lang_str["list_shop_car_parts"]="Car Parts";
#    $lang_str["list_shop_motorcycle"]="Motorcycle Shops";
#  $lang_str["list_fashion"]="Fashion";
#    $lang_str["list_shop_clothes"]="Clothes";
#    $lang_str["list_shop_florist"]="Florists";
#    $lang_str["list_shop_hairdresser"]="Hair dressers";
#    $lang_str["list_shop_shoes"]="Shoe Shops";
#    $lang_str["list_shop_fashion"]="Fashion Shops";
#    $lang_str["list_shop_jewelry"]="Jewelries";
#    $lang_str["list_shop_apparel"]="Apparel Shops";
#    $lang_str["list_shop_second_hand"]="Second Hand Shops";
#  $lang_str["list_home_office"]="Home &amp; Office";
#    $lang_str["list_shop_computer"]="Computer Stores";
#    $lang_str["list_shop_doityourself"]="Do it yourself";
#    $lang_str["list_shop_electronics"]="Electronics";
#    $lang_str["list_shop_hardware"]="Hardware Shops";
#    $lang_str["list_shop_hifi"]="Hifi Shops";
#    $lang_str["list_shop_dry_cleaning"]="Dry Cleaning";
#    $lang_str["list_shop_furniture"]="Furniture Shops";
#    $lang_str["list_shop_garden_centre"]="Garden Centres";
#    $lang_str["list_shop_laundry"]="Laundries";
#    $lang_str["list_shop_stationery"]="Stationery Shops";
#    $lang_str["list_shop_toys"]="Toys";
#    $lang_str["list_shop_estate_agent"]="Estate Agents";
#    $lang_str["list_shop_pet"]="Pet Shops";
//$lang_str["list_health"]="Health";
#    $lang_str["list_shop_optician"]="Opticians";
#    $lang_str["list_shop_chemist"]="Chemists";
#    $lang_str["list_shop_drugstore"]="Drug Stores";
#    $lang_str["list_shop_pharmacy"]="Pharmacies";
#  $lang_str["list_othershops"]="Other";
#    $lang_str["list_shop_fixme"]="To be fixed";
#    $lang_str["list_shop_shop"]="Unknown Shops";
#    $lang_str["list_shop_other"]="Other Shops";
#    $lang_str["list_shop_vending_machine"]="Vending Machines";

#$lang_str["list_education_culture"]="Education and Culture";
#  $lang_str["list_culture"]="Culture";
#    $lang_str["list_amenity_arts_centre"]="Arts Centres";
#    $lang_str["list_amenity_theatre"]="Theatres";
#    $lang_str["list_tourism_museum"]="Museums";
#    $lang_str["list_tourism_artwork"]="Artworks";
#    $lang_str["list_amenity_fountain"]="Fountains";
#    $lang_str["list_amenity_cinema"]="Cinemas";
#    $lang_str["list_amenity_studio"]="TV/Radio/Recording Studios";
#    $lang_str["list_shop_trumpet"]="Music Shops";

#  $lang_str["list_education"]="Education";
#    $lang_str["list_amenity_university"]="Universities";
#    $lang_str["list_amenity_college"]="Colleges";
#    $lang_str["list_amenity_school"]="Schools";
#    $lang_str["list_amenity_preschool"]="Preschools";
#    $lang_str["list_amenity_kindergarten"]="Kindergartens";
#    $lang_str["list_amenity_library"]="Libraries";
#    $lang_str["list_shop_books"]="Book Shops";

#  $lang_str["list_historic"]="Historic Places";
#    $lang_str["list_historic_monument"]="Monuments";
#    $lang_str["list_historic_castle"]="Castles";
#    $lang_str["list_historic_ruins"]="Ruins";
#    $lang_str["list_historic_icon"]="Icons";
#    $lang_str["list_historic_memorial"]="Memorial Sites";
#    $lang_str["list_historic_archaeological_site"]="Archaelogical Sites";
#    $lang_str["list_historic_unesco_world_heritage"]="UNESCO World Heritage Sites";
#    $lang_str["list_historic_UNESCO_world_heritage"]="UNESCO World Heritage Sites";
#    $lang_str["list_historic_battlefield"]="Battlefields";
#    $lang_str["list_historic_wreck"]="Wrecks";
#    $lang_str["list_historic_wayside_cross"]="Wayside Crosses";
#    $lang_str["list_historic_wayside_shrine"]="Wayside Shrines";

#  $lang_str["list_religion"]="Religion";
#    $lang_str["list_amenity_place_of_worship"]="Places of Worship";
#    $lang_str["list_amenity_grave_yard"]="Grave Yards";
#    $lang_str["list_landuse_cemetery"]="Cemeteries";
#    $lang_str["list_amenity_crematorium"]="Crematoriums";
#    $lang_str["list_cemetery_grave"]="Graves";
#    $lang_str["list_amenity_grave"]="Graves";

#$lang_str["list_services"]="Services";
#  $lang_str["list_health"]="Health";
#    $lang_str["list_amenity_hospital"]="Hospitals";
#    $lang_str["list_amenity_doctor"]="Doctors";
#    $lang_str["list_amenity_doctors"]="Doctors";
#    $lang_str["list_amenity_dentist"]="Dentists";
#    $lang_str["list_amenity_pharmacy"]="Pharmacies";
#    $lang_str["list_amenity_veterinary"]="Veterinaries";
#    $lang_str["list_amenity_red_cross"]="Ambulance Services";
#    $lang_str["list_amenity_baby_hatch"]="Baby Hatch";

#  $lang_str["list_public"]="Public Services";
#    $lang_str["list_amenity_townhall"]="Townhalls";
#    $lang_str["list_amenity_public_building"]="Public Buildings";
#    $lang_str["list_amenity_fire_station"]="Fire Stations";
#    $lang_str["list_amenity_police"]="Police Stations";
#    $lang_str["list_amenity_embassy"]="Embassies";
#    $lang_str["list_amenity_courthouse"]="Court Houses";
#    $lang_str["list_amenity_prison"]="Prisons";

#  $lang_str["list_communication"]="Communication";
#    $lang_str["list_amenity_telephone"]="Telephones";
#    $lang_str["list_amenity_emergency_phone"]="Emergency Phones";
#    $lang_str["list_amenity_post_office"]="Post Offices";
#    $lang_str["list_amenity_post_box"]="Post Boxes";
#    $lang_str["list_amenity_wlan"]="WLAN Accesspoints";
#    $lang_str["list_amenity_WLAN"]="WLAN Accesspoints";

#  $lang_str["list_disposal"]="Disposal";
#    $lang_str["list_amenity_recycling"]="Recyclings Centres";
#    $lang_str["list_amenity_toilets"]="Toilets";
#    $lang_str["list_amenity_waste_disposal"]="Waste Disposals";

#$lang_str["list_transport"]="Transportation";
#  $lang_str["list_car_motorcycle"]="Car and Motorcycle";
#    $lang_str["list_amenity_fuel"]="Fuel Stations";
#    $lang_str["list_amenity_car_rental"]="Car Rentals";
#    $lang_str["list_amenity_car_sharing"]="Car Sharings";
#    $lang_str["list_amenity_parking"]="Parkings";
#    $lang_str["list_shop_car"]="Car Shop";
#    $lang_str["list_shop_car_repair"]="Car Repair";

#  $lang_str["list_pt_amenities"]="Public Transport amenities";
#    $lang_str["list_amenity_taxi"]="Taxi Stations";
#    $lang_str["list_amenity_ticket_counter"]="Ticket Counter";

#  $lang_str["list_pt_stops"]="Public Transport stops";
#  $lang_str["list_pt_routes"]="Public Transport routes";

#  $lang_str["list_pipes"]="Goods (Pipes, Power, ...)";
#    $lang_str["list_power_line"]="Power lines";
#    $lang_str["list_man_made_pipeline"]="Pipelines";

$lang_str["list_places"]="Места";
  $lang_str["list_streets"]="Муниципальные";
  $lang_str["list_nature_recreation"]="Природа &amp; Отдых";
    $lang_str["list_leisure_park"]="Парки";
    $lang_str["list_leisure_nature_reserve"]="Заповедники";
    $lang_str["list_leisure_common"]="Общественные земли";
    $lang_str["list_leisure_garden"]="Сады";
  $lang_str["list_natural"]="Природные образования";
    $lang_str["list_natural_peaks"]="Вершины";
    $lang_str["list_natural_spring"]="Родники";
    $lang_str["list_natural_glacier"]="Ледники";
    $lang_str["list_natural_volcano"]="Вулканы";
    $lang_str["list_natural_cliff"]="Скалы";
    $lang_str["list_natural_scree"]="Осыпи";
    $lang_str["list_natural_fell"]="Буреломы";
    $lang_str["list_natural_heath"]="Пустоши";
    $lang_str["list_natural_wood"]="Рощи";
    $lang_str["list_landuse_forest"]="Леса";
    $lang_str["list_natural_marsh"]="Болота";
    $lang_str["list_natural_wetland"]="Болотистая местность";
    $lang_str["list_natural_water"]="Озёра";
    $lang_str["list_natural_beach"]="Пляжи";
    $lang_str["list_natural_bay"]="Бухты";
    $lang_str["list_natural_land"]="Острова";
    $lang_str["list_natural_cave_entrance"]="Входы в пещеру";
    $lang_str["list_natural_tree"]="Деревья";

$lang_str["list_industry"]="Промышленность";
$lang_str["list_power"]="Энергетика";
  $lang_str["list_power_generator"]="Электрогенераторы";
  $lang_str["list_power_station"]="Электростанции";
  $lang_str["list_power_sub_station"]="Подстанции";
#$lang_str["list_works"]="Works";
#  $lang_str["list_landuse_industrial"]="Industrial Areas";
#  $lang_str["list_man_made_works"]="Works";

$lang_str["route_international"]="Международные маршруты";
$lang_str["route_national"]="Национальные маршруты";
$lang_str["route_region"]="Региональные маршруты";
$lang_str["route_urban"]="Городские маршруты";
$lang_str["route_suburban"]="Пригородные маршруты";
$lang_str["route_local"]="Местные маршруты";
$lang_str["route_no"]="Маршрутов не найдено";
$lang_str["route_zoom"]="Увеличте для отображения маршрутов";

#$lang_str["station_type_amenity_bus_station"]="Bus Station";
#$lang_str["station_type_amenity_ferry_terminal"]="Ferry Terminal";
#$lang_str["station_type_highway_bus_stop"]="Bus Stop";
#$lang_str["station_type_railway_tram_stop"]="Tram Stop";
#$lang_str["station_type_railway_station"]="Railway Station";
#$lang_str["station_type_railway_halt"]="Railway Halt";
// ATTENTION: the last >400 language strings are deprecated


