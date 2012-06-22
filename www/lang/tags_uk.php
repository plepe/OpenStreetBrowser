<?
// All tags should have a translation, with language strings like "tag:key" for the translation of the key and "tag:key=value" for the translation of the value. E.g. tag:amenity "Amenity;Amenities" resp. tag:amenity=bar "Bar;Bars". You can also define the Gender like "F;Bar;Bars".

// *
$lang_str["tag:*=yes"]="так";
$lang_str["tag:*=no"]="ні";

// accomodation
$lang_str["tag:accomodation"]="Житло";

// address
$lang_str["tag:address"]="Адреса";

// addr:housenumber
$lang_str["tag:addr:housenumber"]=array("Номер будинку", "Номери будинків");

// addr:housename
$lang_str["tag:addr:housename"]=array("Ім'я будинку", "Імена будинків");

// addr:street
$lang_str["tag:addr:street"]=array("Вулиця", "Вулиці");

// addr:postcode
$lang_str["tag:addr:postcode"]=array("Поштовий індекс", "Поштові індекси");

// addr:city
$lang_str["tag:addr:city"]=array("Місто", "Міста");

// addr:country
$lang_str["tag:addr:country"]=array("Країна", "Країни");

// addr:full
$lang_str["tag:addr:full"]=array("Повна адреса", "Повні адреси");

// addr:interpolation
#$lang_str["tag:addr:interpolation"]="Interpolated housenumbers";

// aeroway
#$lang_str["tag:aeroway"]="Aeroway";
#$lang_str["tag:aeroway=runway"]="Runway";
#$lang_str["tag:aeroway=taxiway"]="Taxiway";

// admin_level
$lang_str["tag:admin_level=2"]="Межі Країн";
$lang_str["tag:admin_level=3"]="Межі Федеральних округів";
$lang_str["tag:admin_level=4"]="Межі суб'єктів";
$lang_str["tag:admin_level=5"]="Межі об'єднаних районів та округів";
$lang_str["tag:admin_level=6"]="Межі районів та округів";
$lang_str["tag:admin_level=8"]="Межі міст або районів міст";
$lang_str["tag:admin_level=10"]="Межі територіальних органів";

// amenity
$lang_str["tag:amenity"]="Корисні місця";
$lang_str["tag:amenity=cinema"]=array("Кінотеатр", "Кінотеатри");
$lang_str["tag:amenity=restaurant"]=array("Ресторан", "Ресторани");
$lang_str["tag:amenity=pub"]=array("Паб", "Паби");

// barrier
$lang_str["tag:barrier"]=array("Перешкода", "Перешкоди");
$lang_str["tag:barrier=city_wall"]=array("Міська стіна", "Міські стіни");
$lang_str["tag:barrier=wall"]=array("Стіна", "Стіни");
$lang_str["tag:barrier=retaining_wall"]=array("Підпірна стінка", "Підпірні стінки");
$lang_str["tag:barrier=fence"]=array("Паркан", "Паркани");
$lang_str["tag:barrier=hedge"]=array("Кущ", "Кущі");

// cables
$lang_str["tag:cables"]="Кабелі";

// description
$lang_str["tag:description"]="Опис";

// fixme
$lang_str["tag:fixme"]="Потрібно виправити";

// note
$lang_str["tag:note"]="Замітка";

// food
$lang_str["tag:food"]="Продукти харчування";

// cuisine
$lang_str["tag:cuisine"]="Громадське харчування";
$lang_str["tag:cuisine=regional"]="національна кухня";

// highway
$lang_str["tag:highway"]=array("Дорога", "Дороги");
$lang_str["tag:highway=motorway"]="Автомагістраль";
$lang_str["tag:highway=motorway_link"]="Виїзд на автомагістраль";
$lang_str["tag:highway=trunk"]="Шосе";
$lang_str["tag:highway=trunk_link"]="Виїзд на шосе";
$lang_str["tag:highway=primary"]="Основна дорога (1-го рівня)";
$lang_str["tag:highway=primary_link"]="Виїзд на основну дорогу 1-го рівня";
$lang_str["tag:highway=secondary"]="Основна дорога (2-го рівня)";
$lang_str["tag:highway=tertiary"]="Основна дорога (3-го рівня)";
$lang_str["tag:highway=minor"]="Другорядна дорога";
$lang_str["tag:highway=road"]="Дорога з невизначеним типом";
$lang_str["tag:highway=residential"]="Міська вулиця";
$lang_str["tag:highway=unclassified"]="Не класифікована дорога";
$lang_str["tag:highway=service"]="Службова або внутрішньодворових дорога";
$lang_str["tag:highway=pedestrian"]="Пішохідна зона";
$lang_str["tag:highway=living_street"]="Житлова вулиця";
$lang_str["tag:highway=path"]="Стежка";
$lang_str["tag:highway=cycleway"]="Велодоріжка";
$lang_str["tag:highway=footway"]="Пішохідна доріжка";
$lang_str["tag:highway=bridleway"]="Дорога для верхової їзди";
$lang_str["tag:highway=track"]="Стихійна дорога";
$lang_str["tag:highway=steps"]="Сходинки";

// bridge
$lang_str["tag:bridge"]="Міст";

// tunnel
$lang_str["tag:tunnel"]="Тунель";

// traffic_calming
#$lang_str["tag:traffic_calming"]="Traffic calming";

// service
#$lang_str["tag:service"]="Service road attributes";

// postal_code
#$lang_str["tag:postal_code"]="Postal Code";

// is_in
$lang_str["tag:is_in"]="знаходиться в";

// leisure
$lang_str["tag:leisure"]="Дозвілля";
$lang_str["tag:leisure=sports_centre"]="Спортивний центр";
$lang_str["tag:leisure=golf_course"]="Курси гольфу";
$lang_str["tag:leisure=stadium"]="Стадіон";
$lang_str["tag:leisure=track"]="Трек";
$lang_str["tag:leisure=pitch"]="Спортивний майданчик";
$lang_str["tag:leisure=water_park"]="Аквапарк";
$lang_str["tag:leisure=marina"]="Гавань";
$lang_str["tag:leisure=slipway"]="Місця спуску на воду";
$lang_str["tag:leisure=fishing"]="Риболовля";
$lang_str["tag:leisure=nature_reserve"]="Заповідник";
$lang_str["tag:leisure=park"]="Парк розваг";
$lang_str["tag:leisure=playground"]="Ігровий майданчик";
$lang_str["tag:leisure=garden"]="Сад";
$lang_str["tag:leisure=common"]="Громадське місце";
$lang_str["tag:leisure=ice_rink"]="Льодовий каток";
$lang_str["tag:leisure=miniature_golf"]="Міні-гольф";
$lang_str["tag:leisure=swimming_pool"]="Басейн";
$lang_str["tag:leisure=beach_resort"]="Пляжний курорт";
$lang_str["tag:leisure=bird_hide"]="Птахоферми";
$lang_str["tag:leisure=sport"]="Інший спорт";

// man_made
$lang_str["tag:man_made"]="Штучні споруди";
$lang_str["tag:man_made=pipeline"]=array("Трубопровід", "Трубопроводи");

// type
$lang_str["tag:type"]="Тип";
$lang_str["tag:type=gas"]="Газ";
$lang_str["tag:type=heat"]="Тепло";
$lang_str["tag:type=hot_water"]="Гряча вода";
$lang_str["tag:type=oil"]="Масло";
$lang_str["tag:type=sewage"]="Каналізація";
$lang_str["tag:type=water"]="Вода";

// name
$lang_str["tag:name"]=array("Назва", "Назви");

// alt_name
$lang_str["tag:alt_name"]=array("Альтернативне ім'я", "Альтернативні імена");

// official_name
$lang_str["tag:official_name"]=array("Офіційне ім'я", "Офіційні імена");

// int_name
$lang_str["tag:int_name"]=array("Міжнародна назва", "Міжнародні назви");

// loc_name
$lang_str["tag:loc_name"]=array("Місцева назва", "Місцеві назви");

// old_name
$lang_str["tag:old_name"]="Стара назва(и)";

// ref
$lang_str["tag:ref"]="Посилання";

// network
$lang_str["tag:network"]="Комп'ютерна мережа";

// opening_hours
$lang_str["tag:opening_hours"]="Години роботи";

// operator
$lang_str["tag:operator"]="Оператор";

// place
$lang_str["tag:place"]="Місце";
$lang_str["tag:place=continent"]=array("Континент", "Континенти");
$lang_str["tag:place=country"]=array("Країна", "Країни");
$lang_str["tag:place=state"]=array("Штат", "Штати");
$lang_str["tag:place=region"]=array("Регіон", "Регіони");
$lang_str["tag:place=county"]=array("Округ", "Округа");
$lang_str["tag:place=city"]=array("Місто", "Міста");
$lang_str["tag:place=town"]=array("Місто", "Міста");
$lang_str["tag:place=village"]=array("Село", "Села");
$lang_str["tag:place=suburb"]=array("Передмістя", "Передмістя");
$lang_str["tag:place=hamlet"]=array("Хутір", "Хутора");
$lang_str["tag:place=locality"]=array("Район", "Райони");
$lang_str["tag:place=island"]=array("Острів", "Острови");
$lang_str["tag:place=islet"]=array("Острівець", "Острівці");
$lang_str["tag:place=ocean"]=array("Океан", "Океани");
$lang_str["tag:place=sea"]=array("Море", "Моря");

// population
$lang_str["tag:population"]="Населення";

// power
$lang_str["tag:power"]="Енергетика";
$lang_str["tag:power=generator"]="Електростанція";
$lang_str["tag:power=line"]="Лінія електропередачі";
#$lang_str["tag:power=minor_line"]="Minor Power Line";
$lang_str["tag:power=tower"]="Power Tower";
$lang_str["tag:power=pole"]="Power Pole";
$lang_str["tag:power=station"]="Power Station";
$lang_str["tag:power=sub_station"]="Power Substation";

// power_source
$lang_str["tag:power_source"]="Power source";
$lang_str["tag:power_source=biofuel"]="Biofuel";
$lang_str["tag:power_source=oil"]="Oil";
$lang_str["tag:power_source=coal"]="Coal";
$lang_str["tag:power_source=gas"]="Gas";
$lang_str["tag:power_source=waste"]="Waste";
$lang_str["tag:power_source=hydro"]="Hydro";
$lang_str["tag:power_source=tidal"]="Tidal";
$lang_str["tag:power_source=wave"]="Wave";
$lang_str["tag:power_source=geothermal"]="Geothermal";
$lang_str["tag:power_source=nuclear"]="Nuclear";
$lang_str["tag:power_source=fusion"]="Fusion";
$lang_str["tag:power_source=wind"]="Wind";
$lang_str["tag:power_source=photovoltaic"]="Photovoltaic";
$lang_str["tag:power_source=solar-thermal"]="Solar Thermal";

// railway
#$lang_str["tag:railway"]="Railway";
#$lang_str["tag:railway=rail"]=array("Rail Track", "Rail Tracks");
#$lang_str["tag:railway=tram"]=array("Tram Track", "Tram Tracks");
#$lang_str["tag:railway=platform"]=array("Platform", "Platforms");

// real_ale
$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="Religion";
$lang_str["tag:religion=christian"]="християнська";
#$lang_str["tag:religion=buddhist"]="buddhist";
#$lang_str["tag:religion=hindu"]="hindu";
#$lang_str["tag:religion=jewish"]="jewish";
#$lang_str["tag:religion=muslim"]="muslim";
#$lang_str["tag:religion=multifaith"]="multifaith";

// denomination
#$lang_str["tag:denomination"]="Denomination";

// route
#$lang_str["tag:route"]="Route";
$lang_str["tag:route=train"]="Train";
$lang_str["tag:route=railway"]="Railway";
$lang_str["tag:route=rail"]="Railway";
$lang_str["tag:route=light_rail"]="Light Rail";
$lang_str["tag:route=subway"]="Subway";
$lang_str["tag:route=tram"]="Tram";
#$lang_str["tag:route=tram_bus"]="Tram and Bus";
$lang_str["tag:route=trolley"]="Trolley";
$lang_str["tag:route=trolleybus"]="Trolley";
$lang_str["tag:route=bus"]="Bus";
$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Ferry";
$lang_str["tag:route=road"]="Road";
$lang_str["tag:route=bicycle"]="Bicycle";
$lang_str["tag:route=hiking"]="Hiking";
$lang_str["tag:route=mtb"]="Mountainbike";

// shop
$lang_str["tag:shop"]="Магазин";

// sport
$lang_str["tag:sport"]="Спорт";
$lang_str["tag:sport=9pin"]="Боулінг (9-ти кеглевий)";
$lang_str["tag:sport=10pin"]="Боулінг (10-ти кеглевий)";
$lang_str["tag:sport=archery"]="Стрільба з лука";
$lang_str["tag:sport=athletics"]="Атлетика";
$lang_str["tag:sport=australian_football"]="Австралійський футбол";
$lang_str["tag:sport=baseball"]="Бейсбол";
$lang_str["tag:sport=basketball"]="Баскетбол";
$lang_str["tag:sport=beachvolleyball"]="Beachvolleyball";
$lang_str["tag:sport=boules"]="Boules";
$lang_str["tag:sport=bowls"]="Bowls";
$lang_str["tag:sport=canoe"]="Canoe";
$lang_str["tag:sport=chess"]="Chess";
$lang_str["tag:sport=climbing"]="Climbing";
$lang_str["tag:sport=cricket"]="Cricket";
$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Cycling";
$lang_str["tag:sport=diving"]="Diving";
$lang_str["tag:sport=dog_racing"]="Dog Racing";
$lang_str["tag:sport=equestrian"]="Equestrian";
$lang_str["tag:sport=football"]="Football";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Gymnastics";
$lang_str["tag:sport=hockey"]="Hockey";
$lang_str["tag:sport=horse_racing"]="Horse Racing";
$lang_str["tag:sport=korfball"]="Korfball";
$lang_str["tag:sport=motor"]="Motor";
$lang_str["tag:sport=multi"]="Multi";
$lang_str["tag:sport=orienteering"]="Orienteering";
$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
$lang_str["tag:sport=paragliding"]="Paragliding";
$lang_str["tag:sport=pelota"]="Pelota";
$lang_str["tag:sport=racquet"]="Racquet";
$lang_str["tag:sport=rowing"]="Rowing";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Shooting";
$lang_str["tag:sport=skating"]="Skating";
$lang_str["tag:sport=skateboard"]="Skateboard";
$lang_str["tag:sport=skiing"]="Skiing";
$lang_str["tag:sport=soccer"]="Soccer";
$lang_str["tag:sport=swimming"]="Swimming";
$lang_str["tag:sport=table_tennis"]="Table Tennis";
$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Volleyball";

// tracks
#$lang_str["tag:tracks"]="Tracks";
#$lang_str["tag:tracks=single"]="Single";
#$lang_str["tag:tracks=double"]="Double";
#$lang_str["tag:tracks=multiple"]="Multiple";

// vending
$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Voltage";

// wires
$lang_str["tag:wires"]="Wires";

// website
$lang_str["tag:website"]="Website";

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
#$lang_str["tag:office"]="Office";

// craft
#$lang_str["tag:craft"]="Craft";

// emergency
#$lang_str["tag:emergency"]="Emergency";

// tourism
#$lang_str["tag:tourism"]="Tourism";

// historic
#$lang_str["tag:historic"]="Historic";

// landuse
#$lang_str["tag:landuse"]="Landuse";

// wood
#$lang_str["tag:wood"]="Type of wood";

// military
#$lang_str["tag:military"]="Military";

// natural
#$lang_str["tag:natural"]="Natural";

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
#$lang_str["tag:layer"]="Layer";

// surface
#$lang_str["tag:surface"]="Surface";

// smoothness
#$lang_str["tag:smoothness"]="Smoothness";

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
$lang_str["tag:route_type"]="Route type";
