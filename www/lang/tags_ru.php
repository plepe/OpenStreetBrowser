<?
//  All tags should have translations in www/lang/tags_XX.php, with
//  language strings like "tag:key" for the translation of the key and
//  "tag:key=value" for the translation of the value. E.g.
//  $lang_str["tag:amenity"]=array("Amenity", "Amenities");
//  $lang_str["tag:amenity=bar"]=array("Bar", "Bars");
//
//  Furthermore you can describe the tags with the array $tag_type. Every
//  entry is an array again to further specify its type, e.g.:
//  $tag_type["width"]=array("number", "m", "in");
//                ^             ^       ^    ^
//                |             |       |    \-- the preferred unit in this locale
//                |             |       \------- the default unit for this tag
//                |             \--------------- the type of the value
//                \----------------------------- tag
//
//  This defines, that the default value for the tag width is a number, with
//  its default unit m (for meter) and the preferred unit for this locale is
//  in (for inch).
//
//  The following types are valid:
//  * text          default (e.g. religion, name)
//  * number        a value, with default unit and preferred unit as defined
//                  by the second and third entry in this array (e.g. width,
//                  voltage)
//  * count         an integer value (e.g. population)
//  * date          a date
//  * link          an Internet URL
//
//  NOTE: the $tag_type can already be defined, but it's not used yet.
//  There might also be more tag types soon and a way to format the output
//  (e.g. "100.000 m" or "2010-12-24").

// accomodation
$lang_str["tag:accomodation"]="Жильё";

// address
$lang_str["tag:address"]=array("M", "Адрес", "Адреса");

// addr:housenumber
$lang_str["tag:addr:housenumber"]="Номер дома";

// admin_level
$lang_str["tag:admin_level=2"]="Границы Стран";
$lang_str["tag:admin_level=3"]="Границы Федеральных округов";
$lang_str["tag:admin_level=4"]="Границы субъектов";
$lang_str["tag:admin_level=5"]="Границы объединённых районов и округов";
$lang_str["tag:admin_level=6"]="Границы районов и округов";
$lang_str["tag:admin_level=8"]="Границы городов или районов городов";
$lang_str["tag:admin_level=10"]="Границы территориальных органов";

// amenity
$lang_str["tag:amenity"]="Полезные места";
$lang_str["tag:amenity=cinema"]=array("M", "Кинотеатр", "Кинотеатры");
$lang_str["tag:amenity=restaurant"]=array("M", "Ресторан", "Рестораны");
$lang_str["tag:amenity=pub"]=array("M", "Паб", "Пабы");

// building
$lang_str["tag:building=yes"]="Постройки";
$lang_str["tag:building=default"]="Постройки";
$lang_str["tag:building=worship"]="Культовые места";
$lang_str["tag:building=road_amenities"]="Услуги для транспорта (станции, терминалы, пункты сбора дорожной пошлины, ...)";
#$lang_str["tag:building=nature_building"]="Natural Buildings (e.g. Barriers)";
$lang_str["tag:building=industrial"]="Промышленные здания";
$lang_str["tag:building=education"]="Учебные корпуса";
$lang_str["tag:building=shop"]="Магазины";
$lang_str["tag:building=public"]="Общественные здания";
$lang_str["tag:building=military"]="Военные здания";
$lang_str["tag:building=historic"]="Исторические постройки";
$lang_str["tag:building=emergency"]="Здания скорой помощи";
$lang_str["tag:building=health"]="Здания медицинских услуг";
$lang_str["tag:building=communication"]="Здания для услуг связи";
$lang_str["tag:building=residential"]="Жилые здания";
$lang_str["tag:building=culture"]="Культурные сооружения";
$lang_str["tag:building=tourism"]="Туристические постройки";
$lang_str["tag:building=sport"]="Спортивные сооружения";

// cables
$lang_str["tag:cables"]="Кабели";

// cuisine
$lang_str["tag:cuisine"]="Общепит";
$lang_str["tag:cuisine=regional"]="национальная кухня";

// description
$lang_str["tag:description"]=array("N", "Описание", "Описания");

// domination
$lang_str["tag:domination"]="Власти";

// food
$lang_str["tag:food"]="Продукты питания";

// highway
$lang_str["tag:highway"]=array("F", "Дорога", "Дороги");
$lang_str["tag:highway=motorway"]=array("F", "Автомагистраль", "Автомагистрали");
$lang_str["tag:highway=trunk"]="Шоссе";
$lang_str["tag:highway=primary"]="Основная дорога (1-го уровня)";
$lang_str["tag:highway=secondary"]="Основная дорога (2-го уровня)";
$lang_str["tag:highway=tertiary"]="Основная дорога (3-го уровня)";
#$lang_str["tag:highway=minor"]="Minor Road";
$lang_str["tag:highway=service"]="Служебная или внутридворовая дорога";
$lang_str["tag:highway=pedestrian"]="Пешеходная зона";
$lang_str["tag:highway=track"]="Стихийная дорога";
$lang_str["tag:highway=path"]="Пешеходная дорожка";

// is_in
$lang_str["tag:is_in"]="находится в";

// landuse
$lang_str["tag:landuse=park"]="Парк";
$lang_str["tag:landuse=education"]="Район учебных заведений";
$lang_str["tag:landuse=tourism"]="Область туристических объектов";
$lang_str["tag:landuse=garden"]="Фермы, плантации, сады";
$lang_str["tag:landuse=industrial"]="Промышленные и железнодорожные территории";
$lang_str["tag:landuse=sport"]="Территории спортивных сооружений";
$lang_str["tag:landuse=cemetery"]="Кладбища";
$lang_str["tag:landuse=residental"]="Жилые районы";
$lang_str["tag:landuse=nature_reserve"]="Заповедники";
$lang_str["tag:landuse=historic"]="Места исторического значения";
$lang_str["tag:landuse=emergency"]="Районы скорой помощи";
$lang_str["tag:landuse=health"]="Место оказания врачебных услуг";
$lang_str["tag:landuse=public"]="Области государственных служб";
$lang_str["tag:landuse=water"]="Водные территории";
// the following tags are deprecated
#$lang_str["tag:landuse=natural|sub_type=t0"]="Woods and Forest";
#$lang_str["tag:landuse=natural|sub_type=t1"]="Wetland";
#$lang_str["tag:landuse=natural|sub_type=t2"]="Glaciers";
#$lang_str["tag:landuse=natural|sub_type=t3"]="Screes, Heaths";
#$lang_str["tag:landuse=natural|sub_type=t4"]="Mud";
#$lang_str["tag:landuse=natural|sub_type=t5"]="Beaches";

// leisure
$lang_str["tag:leisure=sports_centre"]="Спортивный центр";
$lang_str["tag:leisure=golf_course"]="Курсы гольфа";
$lang_str["tag:leisure=stadium"]="Стадион";
$lang_str["tag:leisure=track"]="Трек";
$lang_str["tag:leisure=pitch"]="Спортивная площадка";
$lang_str["tag:leisure=water_park"]="Аквапарк";
$lang_str["tag:leisure=marina"]="Гавань";
$lang_str["tag:leisure=slipway"]="Места спуска на воду";
$lang_str["tag:leisure=fishing"]="Рыбалка";
$lang_str["tag:leisure=nature_reserve"]="Заповедник";
$lang_str["tag:leisure=park"]="Парк развлечений";
$lang_str["tag:leisure=playground"]="Игровая площадка";
$lang_str["tag:leisure=garden"]="Сад";
$lang_str["tag:leisure=common"]="Общественное место";
$lang_str["tag:leisure=ice_rink"]="Ледовый каток";
$lang_str["tag:leisure=miniature_golf"]="Мини-гольф";
$lang_str["tag:leisure=swimming_pool"]="Бассейн";
$lang_str["tag:leisure=beach_resort"]="Пляжный курорт";
$lang_str["tag:leisure=bird_hide"]="Птицефермы";
$lang_str["tag:leisure=sport"]="Другой спорт";

// name
$lang_str["tag:name"]=array("N", "Название", "Названия");

// network
$lang_str["tag:network"]=array("F", "Компьютерная сеть", "Компьютерные сети");

// note
$lang_str["tag:note"]=array("F", "Заметка", "Заметки");

// old_name
$lang_str["tag:old_name"]=array("N", "Старое название", "Старые названия");

// opening_hours
$lang_str["tag:opening_hours"]="Часы работы";

// operator
$lang_str["tag:operator"]=array("M", "Оператор", "Операторы");

// place
$lang_str["tag:place"]=array("N", "Место", "Места");
$lang_str["tag:place=continent"]=array("M", "Континент", "Континенты");
$lang_str["tag:place=country"]=array("F", "Страна", "Страны");
$lang_str["tag:place=state"]=array("M", "Штат", "Штаты");
$lang_str["tag:place=region"]=array("M", "Регион", "Регионы");
$lang_str["tag:place=county"]=array("M", "Округ", "Округа");
$lang_str["tag:place=city"]=array("M", "Город", "Города");
$lang_str["tag:place=town"]=array("M", "Город", "Города");
$lang_str["tag:place=village"]=array("F", "Деревня", "Деревни");
$lang_str["tag:place=suburb"]=array("M", "Пригород", "Пригороды");
$lang_str["tag:place=locality"]=array("M", "Район", "Районы");
$lang_str["tag:place=island"]=array("M", "Остров", "Острова");
$lang_str["tag:place=islet"]=array("M", "Островок", "Островки");
// the following tags are deprecated
#$lang_str["tag:place=city;population>1000000"]=array("City, > 1 Mio Inhabitants", "Cities, > 1 Mio Inhabitants");
#$lang_str["tag:place=city;population>200000"]=array("City, > 200.000 Inhabitants", "Cities, > 200.000 Inhabitants");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=town;population>30000"]=array("Town, > 30.000 Inhabitants", "Towns, > 30.000 Inhabitants");

// population
$lang_str["tag:population"]="Население";
$tag_type["population"]=array("count");

// power
$lang_str["tag:power"]="Энергетика";
$lang_str["tag:power=generator"]="Электростанция";
$lang_str["tag:power=line"]="Линия электропередачи";
$lang_str["tag:power=tower"]="Опора линии электропередачи";
$lang_str["tag:power=pole"]="Электрический столб";
$lang_str["tag:power=station"]="Электроподстанция";
$lang_str["tag:power=sub_station"]="Трансформаторная будка";

// power_source
$lang_str["tag:power_source"]="Источник энергии";
$lang_str["tag:power_source=biofuel"]="Биотопливо";
$lang_str["tag:power_source=oil"]="Нефть";
$lang_str["tag:power_source=coal"]="Уголь";
$lang_str["tag:power_source=gas"]="Газ";
$lang_str["tag:power_source=waste"]="Отходы";
$lang_str["tag:power_source=hydro"]="Воды";
$lang_str["tag:power_source=tidal"]="Приливы";
$lang_str["tag:power_source=wave"]="Волны";
$lang_str["tag:power_source=geothermal"]="Геотермальная";
$lang_str["tag:power_source=nuclear"]="Ядерная реакция";
#$lang_str["tag:power_source=fusion"]="Fusion";
$lang_str["tag:power_source=wind"]="Ветер";
$lang_str["tag:power_source=photovoltaic"]="Фотоэлектрическая";
$lang_str["tag:power_source=solar-thermal"]="Тепло Солнца";

// real_ale
$lang_str["tag:real_ale"]="Настоящий эль";

// religion
$lang_str["tag:religion"]=array("F", "Религия", "Религии");
$lang_str["tag:religion=christian"]="Христианство";

// route
$lang_str["tag:route=train"]="Поезд";
$lang_str["tag:route=railway"]="Железная дорога";
$lang_str["tag:route=rail"]="Железная дорога";
$lang_str["tag:route=light_rail"]="Железная дорога";
$lang_str["tag:route=subway"]="Метро";
$lang_str["tag:route=tram"]="Трамвай";
$lang_str["tag:route=trolley"]="Троллейбус";
$lang_str["tag:route=trolleybus"]="Троллейбус";
$lang_str["tag:route=bus"]="Автобус";
$lang_str["tag:route=minibus"]="Маршрутное такси";
$lang_str["tag:route=ferry"]="Паром";
$lang_str["tag:route=road"]="Автомобильная дорога";
$lang_str["tag:route=bicycle"]="Велосипед";
$lang_str["tag:route=hiking"]="Пешком";
$lang_str["tag:route=mtb"]="Горный велосипед";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="Тип маршрута";

// shop
$lang_str["tag:shop"]=array("M", "Магазин", "Магазины");

// sport
$lang_str["tag:sport"]="Спорт";
$lang_str["tag:sport=9pin"]="Боулинг (9-ти кеглевый)";
$lang_str["tag:sport=10pin"]="Боулинг (10-ти кеглевый)";
$lang_str["tag:sport=archery"]="Стрельба из лука";
$lang_str["tag:sport=athletics"]="Атлетика";
$lang_str["tag:sport=australian_football"]="Австралийский футбол";
$lang_str["tag:sport=baseball"]="Бейсбол";
$lang_str["tag:sport=basketball"]="Баскетбол";
$lang_str["tag:sport=beachvolleyball"]="Пляжный волейбол";
$lang_str["tag:sport=boules"]="Петанк";
$lang_str["tag:sport=bowls"]="Боулз";
$lang_str["tag:sport=canoe"]="Каноэ";
$lang_str["tag:sport=chess"]="Шахматы";
$lang_str["tag:sport=climbing"]="Скалолазание";
$lang_str["tag:sport=cricket"]="Крикет";
#$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
$lang_str["tag:sport=croquet"]="Крокет";
$lang_str["tag:sport=cycling"]="Велоспорт";
$lang_str["tag:sport=diving"]="Дайвинг";
$lang_str["tag:sport=dog_racing"]="Собачьи бега";
$lang_str["tag:sport=equestrian"]="Конный спорт";
$lang_str["tag:sport=football"]="Футбол";
$lang_str["tag:sport=golf"]="Гольф";
$lang_str["tag:sport=gymnastics"]="Гимнастика";
$lang_str["tag:sport=hockey"]="Хоккей";
$lang_str["tag:sport=horse_racing"]="Лошадиные бега";
$lang_str["tag:sport=korfball"]="Корфбол";
#$lang_str["tag:sport=motor"]="Motor";
$lang_str["tag:sport=multi"]="Разные виды";
$lang_str["tag:sport=orienteering"]="Спортивное ориентирование";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
$lang_str["tag:sport=paragliding"]="Парапланеризм";
$lang_str["tag:sport=pelota"]="Пелота";
#$lang_str["tag:sport=racquet"]="Racquet";
$lang_str["tag:sport=rowing"]="Гребля";
$lang_str["tag:sport=rugby"]="Регби";
$lang_str["tag:sport=shooting"]="Стрельба";
$lang_str["tag:sport=skating"]="Конькобежный спорт";
$lang_str["tag:sport=skateboard"]="Скейтборд";
$lang_str["tag:sport=skiing"]="Лыжный спорт";
$lang_str["tag:sport=soccer"]="Футбол";
$lang_str["tag:sport=swimming"]="Плавание";
$lang_str["tag:sport=table_tennis"]="Настольный теннис";
$lang_str["tag:sport=team_handball"]="Гандбол";
$lang_str["tag:sport=tennis"]="Теннис";
$lang_str["tag:sport=volleyball"]="Волейбол";

// vending
$lang_str["tag:vending"]="Торговые автоматы";

// voltage
$lang_str["tag:voltage"]="Напряжение";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="Провода";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]=array("M", "Сайт", "Сайты");
$tag_type["website"]=array("link");





