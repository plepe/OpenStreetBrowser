<?
// All tags should have a translation, with language strings like "tag:key" for the translation of the key and "tag:key=value" for the translation of the value. E.g. tag:amenity "Amenity;Amenities" resp. tag:amenity=bar "Bar;Bars". You can also define the Gender like "F;Bar;Bars".

// *
$lang_str["tag:*=yes"]="да";
$lang_str["tag:*=no"]="нет";

// accomodation
$lang_str["tag:accomodation"]="Жильё";

// address
$lang_str["tag:address"]=array("Адрес", "Адреса");

// addr:housenumber
$lang_str["tag:addr:housenumber"]="Номер дома";

// addr:housename
#$lang_str["tag:addr:housename"]=array("House name", "House names");

// addr:street
$lang_str["tag:addr:street"]=array("Улица", "Улицы");

// addr:postcode
$lang_str["tag:addr:postcode"]=array("Почтовый индекс", "Почтовые индексы");

// addr:city
$lang_str["tag:addr:city"]=array("Город", "Города");

// addr:country
$lang_str["tag:addr:country"]=array("Страна", "Страны");

// addr:full
$lang_str["tag:addr:full"]=array("Полный адрес", "Полные адреса");

// addr:interpolation
$lang_str["tag:addr:interpolation"]="Интерполированные адреса";

// aeroway
$lang_str["tag:aeroway"]="Аэродромные дорожки";
$lang_str["tag:aeroway=runway"]="Взлётные полосы";
$lang_str["tag:aeroway=taxiway"]="Рулёжные дорожки";

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
$lang_str["tag:amenity=cinema"]=array("Кинотеатр", "Кинотеатры");
$lang_str["tag:amenity=restaurant"]=array("Ресторан", "Рестораны");
$lang_str["tag:amenity=pub"]=array("Паб", "Пабы");

// barrier
$lang_str["tag:barrier"]=array("Преграда", "Преграды");
$lang_str["tag:barrier=city_wall"]=array("Городская стена", "Городские стены");
$lang_str["tag:barrier=wall"]=array("Стена", "Стены");
$lang_str["tag:barrier=retaining_wall"]=array("Подпорная стенка", "Подпорные стенки");
$lang_str["tag:barrier=fence"]=array("Забор", "Заборы");
#$lang_str["tag:barrier=hedge"]=array("Hedge", "Hedges");

// cables
$lang_str["tag:cables"]="Кабели";

// description
$lang_str["tag:description"]=array(N, "Описание", "Описания");

// fixme
#$lang_str["tag:fixme"]="Fix me";

// note
$lang_str["tag:note"]=array(F, "Заметка", "Заметки");

// food
$lang_str["tag:food"]="Продукты питания";

// cuisine
$lang_str["tag:cuisine"]="Общепит";
$lang_str["tag:cuisine=regional"]="национальная кухня";

// highway
$lang_str["tag:highway"]=array("Дорога", "Дороги");
$lang_str["tag:highway=motorway"]="Автомагистраль";
$lang_str["tag:highway=motorway_link"]="Выезд на автомагистраль";
$lang_str["tag:highway=trunk"]="Шоссе";
$lang_str["tag:highway=trunk_link"]="Выезд на шоссе";
$lang_str["tag:highway=primary"]="Основная дорога (1-го уровня)";
$lang_str["tag:highway=primary_link"]="Выезд на основную дорогу 1-го уровня";
$lang_str["tag:highway=secondary"]="Основная дорога (2-го уровня)";
$lang_str["tag:highway=tertiary"]="Основная дорога (3-го уровня)";
$lang_str["tag:highway=minor"]="Второстепенная дорога";
$lang_str["tag:highway=road"]="Дорога с неопределённым типом";
$lang_str["tag:highway=residential"]="Городская улица";
$lang_str["tag:highway=unclassified"]="Не классифицированная дорога";
$lang_str["tag:highway=service"]="Служебная или внутридворовая дорога";
$lang_str["tag:highway=pedestrian"]="Пешеходная зона";
$lang_str["tag:highway=living_street"]="Жилая улица";
$lang_str["tag:highway=path"]="Тропинка";
$lang_str["tag:highway=cycleway"]="Велодорожка";
$lang_str["tag:highway=footway"]="Пешеходная дорожка";
$lang_str["tag:highway=bridleway"]="Дорога для верховой езды";
$lang_str["tag:highway=track"]="Стихийная дорога";
$lang_str["tag:highway=steps"]="Ступени";

// bridge
$lang_str["tag:bridge"]="Мост";

// tunnel
$lang_str["tag:tunnel"]="Тоннель";

// traffic_calming
#$lang_str["tag:traffic_calming"]="Traffic calming";

// service
#$lang_str["tag:service"]="Service road attributes";

// postal_code
#$lang_str["tag:postal_code"]="Postal Code";

// is_in
$lang_str["tag:is_in"]="находится в";

// leisure
$lang_str["tag:leisure"]="Досуг";
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

// man_made
$lang_str["tag:man_made"]="Искусственные сооружения";
$lang_str["tag:man_made=pipeline"]=array("Трубопровод", "Трубопроводы");

// type
$lang_str["tag:type"]="Тип";
$lang_str["tag:type=gas"]="Газ";
$lang_str["tag:type=heat"]="Тепло";
$lang_str["tag:type=hot_water"]="Грячая вода";
$lang_str["tag:type=oil"]="Масло";
$lang_str["tag:type=sewage"]="Канализация";
$lang_str["tag:type=water"]="Вода";

// name
$lang_str["tag:name"]=array("Название", "Названия");

// alt_name
#$lang_str["tag:alt_name"]=array("Alternative name", "Alternative names");

// official_name
#$lang_str["tag:official_name"]=array("Official name", "Official names");

// int_name
#$lang_str["tag:int_name"]=array("International name", "International names");

// loc_name
#$lang_str["tag:loc_name"]=array("Local name", "Local names");

// old_name
$lang_str["tag:old_name"]="Старое название(я)";

// ref
#$lang_str["tag:ref"]="Reference";

// network
$lang_str["tag:network"]="Компьютерная сеть";

// opening_hours
$lang_str["tag:opening_hours"]="Часы работы";

// operator
$lang_str["tag:operator"]="Оператор";

// place
$lang_str["tag:place"]=array(N, "Место", "Места");
$lang_str["tag:place=continent"]=array(M, "Континент", "Континенты");
$lang_str["tag:place=country"]=array(F, "Страна", "Страны");
$lang_str["tag:place=state"]=array(M, "Штат", "Штаты");
$lang_str["tag:place=region"]=array(M, "Регион", "Регионы");
$lang_str["tag:place=county"]=array(M, "Округ", "Округа");
$lang_str["tag:place=city"]=array(M, "Город", "Города");
$lang_str["tag:place=town"]=array(M, "Город", "Города");
$lang_str["tag:place=village"]=array(F, "Деревня", "Деревни");
$lang_str["tag:place=suburb"]=array(M, "Пригород", "Пригороды");
$lang_str["tag:place=hamlet"]=array("Хутор", "Хутора");
$lang_str["tag:place=locality"]=array(M, "Район", "Районы");
$lang_str["tag:place=island"]=array(M, "Остров", "Острова");
$lang_str["tag:place=islet"]=array(M, "Островок", "Островки");
$lang_str["tag:place=ocean"]=array("Океан", "Океаны");
$lang_str["tag:place=sea"]=array("Море", "Моря");

// population
$lang_str["tag:population"]="Население";

// power
$lang_str["tag:power"]="Энергетика";
$lang_str["tag:power=generator"]="Электростанция";
$lang_str["tag:power=line"]="Линия электропередачи";
$lang_str["tag:power=minor_line"]="Низковольтные линии электропередач";
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

// railway
$lang_str["tag:railway"]="Рельсовые пути";
$lang_str["tag:railway=rail"]=array("Ж/д путь", "Ж/д пути");
$lang_str["tag:railway=tram"]=array("Трамвайный путь", "Трамвайные пути");
$lang_str["tag:railway=platform"]=array("Платформа", "Платформы");

// real_ale
$lang_str["tag:real_ale"]="Настоящий эль";

// religion
$lang_str["tag:religion"]="Религия";
$lang_str["tag:religion=christian"]="Христианство";
$lang_str["tag:religion=buddhist"]="Буддизм";
$lang_str["tag:religion=hindu"]="Индуизм";
$lang_str["tag:religion=jewish"]="Иудаизм";
$lang_str["tag:religion=muslim"]="Мусульманство";
#$lang_str["tag:religion=multifaith"]="multifaith";

// denomination
#$lang_str["tag:denomination"]="Denomination";

// route
$lang_str["tag:route"]="Маршрут";
$lang_str["tag:route=train"]="Поезд";
$lang_str["tag:route=railway"]="Железная дорога";
$lang_str["tag:route=rail"]="Железная дорога";
$lang_str["tag:route=light_rail"]="Железная дорога";
$lang_str["tag:route=subway"]="Метро";
$lang_str["tag:route=tram"]="Трамвай";
$lang_str["tag:route=tram_bus"]="Трамвай и автобус";
$lang_str["tag:route=trolley"]="Троллейбус";
$lang_str["tag:route=trolleybus"]="Троллейбус";
$lang_str["tag:route=bus"]="Автобус";
$lang_str["tag:route=minibus"]="Маршрутное такси";
$lang_str["tag:route=ferry"]="Паром";
$lang_str["tag:route=road"]="Автомобильная дорога";
$lang_str["tag:route=bicycle"]="Велосипед";
$lang_str["tag:route=hiking"]="Пешком";
$lang_str["tag:route=mtb"]="Горный велосипед";

// shop
$lang_str["tag:shop"]="Магазин";

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

// tracks
#$lang_str["tag:tracks"]="Tracks";
#$lang_str["tag:tracks=single"]="Single";
#$lang_str["tag:tracks=double"]="Double";
#$lang_str["tag:tracks=multiple"]="Multiple";

// vending
$lang_str["tag:vending"]="Торговые автоматы";

// voltage
$lang_str["tag:voltage"]="Напряжение";

// wires
$lang_str["tag:wires"]="Провода";

// website
$lang_str["tag:website"]=array("Сайт", "Сайты");

// cycleway
#$lang_str["tag:cycleway"]="Cycleway";

// tracktype
#$lang_str["tag:tracktype"]="Track type";

// waterway
#$lang_str["tag:waterway"]="Waterway";

// aerialway
#$lang_str["tag:aerialway"]="Aerialway";

// public_transport
#$lang_str["tag:public_transport"]="Public Transport";

// office
$lang_str["tag:office"]="Офис";

// craft
#$lang_str["tag:craft"]="Craft";

// emergency
#$lang_str["tag:emergency"]="Emergency";

// tourism
$lang_str["tag:tourism"]="Туризм";

// historic
#$lang_str["tag:historic"]="Historic";

// landuse
#$lang_str["tag:landuse"]="Landuse";

// wood
#$lang_str["tag:wood"]="Type of wood";

// military
$lang_str["tag:military"]="Военный";

// natural
$lang_str["tag:natural"]="Природа";

// geological
#$lang_str["tag:geological"]="Geological";

// boundary
#$lang_str["tag:boundary"]="Boundary";

// abutters
#$lang_str["tag:abutters"]="Abutters";

// lit
#$lang_str["tag:lit"]="Street lighting";

// area
#$lang_str["tag:area"]="Area";

// crossing
#$lang_str["tag:crossing"]="crossing";

// mountain_pass
#$lang_str["tag:mountain_pass"]="Mountain Pass";

// cutting
#$lang_str["tag:cutting"]="Cutting";

// embankment
#$lang_str["tag:embankment"]="Embankment";

// lanes
#$lang_str["tag:lanes"]="Lanes";

// layer
$lang_str["tag:layer"]="Слой";

// surface
$lang_str["tag:surface"]="Поверхность";

// smoothness
$lang_str["tag:smoothness"]="Ровная";

// ele
#$lang_str["tag:ele"]="Elevation";

// width
#$lang_str["tag:width"]="Width";

// est_width
#$lang_str["tag:est_width"]="Estimated width";

// incline
#$lang_str["tag:incline"]="incline";

// start_date
#$lang_str["tag:start_date"]="Date of creation";

// end_date
#$lang_str["tag:end_date"]="Date of removal";

// disused
#$lang_str["tag:disused"]="Disused";

// wheelchair
#$lang_str["tag:wheelchair"]="Wheelchair";
#$lang_str["tag:wheelchair=limited"]="limited";

// tactile_paving
#$lang_str["tag:tactile_paving"]="Tactile paving";

// narrow
#$lang_str["tag:narrow"]="Narrow";

// covered
#$lang_str["tag:covered"]="Covered";

// ford
#$lang_str["tag:ford"]="Ford";

// access
#$lang_str["tag:access"]="General access permission";

// vehicle
#$lang_str["tag:vehicle"]="Vehicle access permission";

// bicycle
#$lang_str["tag:bicycle"]="Bicycle access permission";

// foot
#$lang_str["tag:foot"]="Foot access permission";

// goods
#$lang_str["tag:goods"]="LCV access permission";

// hgv
#$lang_str["tag:hgv"]="HGV access permission";

// horse
#$lang_str["tag:horse"]="Horse riders access permission";

// motorcycle
#$lang_str["tag:motorcycle"]="Motorcycle access permission";

// motorcar
#$lang_str["tag:motorcar"]="Motorcar access permission";

// psv
#$lang_str["tag:psv"]="PSV access permission";

// oneway
#$lang_str["tag:oneway"]="Oneway";

// noexit
#$lang_str["tag:noexit"]="Dead end road";

// maxweight
#$lang_str["tag:maxweight"]="Max. weight";

// maxheight
#$lang_str["tag:maxheight"]="Max. height";

// maxlength
#$lang_str["tag:maxlength"]="Max. length";

// maxspeed
#$lang_str["tag:maxspeed"]="Max. speed";

// minspeed
#$lang_str["tag:minspeed"]="Min. speed";

// traffic_sign
#$lang_str["tag:traffic_sign"]="Traffic sign";

// toll
#$lang_str["tag:toll"]="Toll";

// charge
#$lang_str["tag:charge"]="Charge";

// source
#$lang_str["tag:source"]="Source";

// phone
#$lang_str["tag:phone"]="Phone number";

// fax
#$lang_str["tag:fax"]="Fax number";

// email
#$lang_str["tag:email"]="E-mail";

// wikipedia
#$lang_str["tag:wikipedia"]="Wikipedia";

// created_by
#$lang_str["tag:created_by"]="Created by";

// construction
#$lang_str["tag:construction"]="Construction";

// proposed
#$lang_str["tag:proposed"]="Proposed";

// route_type
$lang_str["tag:route_type"]="Тип маршрута";
