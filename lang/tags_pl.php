<?
// All tags should have a translation, with language strings like "tag:key" for the translation of the key and "tag:key=value" for the translation of the value. E.g. tag:amenity "Amenity;Amenities" resp. tag:amenity=bar "Bar;Bars". You can also define the Gender like "F;Bar;Bars".

// *
$lang_str["tag:*=yes"]="tak";
$lang_str["tag:*=no"]="nie";

// accomodation
#$lang_str["tag:accomodation"]="Accomodation";

// address
$lang_str["tag:address"]="Adres";

// addr:housenumber
$lang_str["tag:addr:housenumber"]=array("Numer budynku", "Numery budynków");

// addr:housename
$lang_str["tag:addr:housename"]=array("Nazwa budynku", "Nazwy budynków");

// addr:street
$lang_str["tag:addr:street"]=array("Ulica", "Ulice");

// addr:postcode
$lang_str["tag:addr:postcode"]=array("Kod pocztowy", "Kody pocztowe");

// addr:city
$lang_str["tag:addr:city"]=array("Miasto", "Miasta");

// addr:country
$lang_str["tag:addr:country"]=array("Kraj", "Kraje");

// addr:full
$lang_str["tag:addr:full"]=array("Pełny adres", "Pełne adresy");

// addr:interpolation
$lang_str["tag:addr:interpolation"]="Interpolowane adresy budynków";

// aeroway
#$lang_str["tag:aeroway"]="Aeroway";
#$lang_str["tag:aeroway=runway"]="Runway";
#$lang_str["tag:aeroway=taxiway"]="Taxiway";

// admin_level
$lang_str["tag:admin_level=2"]="Granica państwa";
#$lang_str["tag:admin_level=3"]="Divisions";
#$lang_str["tag:admin_level=4"]="State Border";
#$lang_str["tag:admin_level=5"]="Community Border";
#$lang_str["tag:admin_level=6"]="County Border";
$lang_str["tag:admin_level=8"]="Granica miasta";
$lang_str["tag:admin_level=10"]="Granica dzielnicy";

// amenity
#$lang_str["tag:amenity"]="Amenity";
$lang_str["tag:amenity=cinema"]=array("Kino", "Kina");
$lang_str["tag:amenity=restaurant"]=array("Restauracja", "Restauracje");
$lang_str["tag:amenity=pub"]=array("Pub", "Puby");

// barrier
$lang_str["tag:barrier"]=array("Bariera", "Bariery");
#$lang_str["tag:barrier=city_wall"]=array("City wall", "City walls");
$lang_str["tag:barrier=wall"]=array("Ściana", "Ściany");
#$lang_str["tag:barrier=retaining_wall"]=array("Retaining Wall", "Retaining Walls");
$lang_str["tag:barrier=fence"]=array("Płot", "Płoty");
$lang_str["tag:barrier=hedge"]=array("Żywopłot", "Żywopłoty");

// cables
#$lang_str["tag:cables"]="Cables";

// description
$lang_str["tag:description"]="Opis";

// fixme
$lang_str["tag:fixme"]="Napraw mnie";

// note
$lang_str["tag:note"]="Notatka";

// food
#$lang_str["tag:food"]="Serves food";

// cuisine
$lang_str["tag:cuisine"]="Kuchnia";
$lang_str["tag:cuisine=regional"]="regionalna";

// highway
$lang_str["tag:highway"]=array("Droga", "Drogi");
#$lang_str["tag:highway=motorway"]="Motorway";
#$lang_str["tag:highway=motorway_link"]="Motorway Link";
#$lang_str["tag:highway=trunk"]="Trunk Road";
#$lang_str["tag:highway=trunk_link"]="Trunk Road Link";
#$lang_str["tag:highway=primary"]="Primary Road";
#$lang_str["tag:highway=primary_link"]="Primary Road Link";
#$lang_str["tag:highway=secondary"]="Secondary Road";
#$lang_str["tag:highway=tertiary"]="Tertiary Road";
#$lang_str["tag:highway=minor"]="Minor Road";
#$lang_str["tag:highway=road"]="Road";
#$lang_str["tag:highway=residential"]="Residential Road";
#$lang_str["tag:highway=unclassified"]="Unclassified Road";
$lang_str["tag:highway=service"]="Droga serwisowa";
$lang_str["tag:highway=pedestrian"]="Strefa pieszych";
#$lang_str["tag:highway=living_street"]="Living Street";
$lang_str["tag:highway=path"]="Ścieżka";
$lang_str["tag:highway=cycleway"]="Droga dla rowerów";
#$lang_str["tag:highway=footway"]="Footway";
#$lang_str["tag:highway=bridleway"]="Bridleway";
#$lang_str["tag:highway=track"]="Track";
$lang_str["tag:highway=steps"]="Schody";

// bridge
$lang_str["tag:bridge"]="Most";

// tunnel
$lang_str["tag:tunnel"]="Tunel";

// traffic_calming
$lang_str["tag:traffic_calming"]="Spowalnianie ruchu";

// service
#$lang_str["tag:service"]="Service road attributes";

// postal_code
$lang_str["tag:postal_code"]="Kod pocztowy";

// is_in
$lang_str["tag:is_in"]="Jest w";

// leisure
$lang_str["tag:leisure"]="Wypoczynek";
$lang_str["tag:leisure=sports_centre"]="Centrum Sportowe";
$lang_str["tag:leisure=golf_course"]="Pole Golfowe";
$lang_str["tag:leisure=stadium"]="Stadion";
#$lang_str["tag:leisure=track"]="Track";
#$lang_str["tag:leisure=pitch"]="Pitche";
#$lang_str["tag:leisure=water_park"]="Water Park";
#$lang_str["tag:leisure=marina"]="Marina";
#$lang_str["tag:leisure=slipway"]="Slipway";
$lang_str["tag:leisure=fishing"]="Wędkarstwo";
#$lang_str["tag:leisure=nature_reserve"]="Nature Reserve";
$lang_str["tag:leisure=park"]="Park";
$lang_str["tag:leisure=playground"]="Plac zabaw";
$lang_str["tag:leisure=garden"]="Ogród";
#$lang_str["tag:leisure=common"]="Common";
#$lang_str["tag:leisure=ice_rink"]="Ice Rink";
#$lang_str["tag:leisure=miniature_golf"]="Miniature Golf";
$lang_str["tag:leisure=swimming_pool"]="Basen";
#$lang_str["tag:leisure=beach_resort"]="Beach Resort";
#$lang_str["tag:leisure=bird_hide"]="Bird Hide";
$lang_str["tag:leisure=sport"]="Inny rodzaj sportu";

// man_made
#$lang_str["tag:man_made"]="Artificial structures";
#$lang_str["tag:man_made=pipeline"]=array("Pipeline", "Pipelines");

// type
$lang_str["tag:type"]="Typ";
#$lang_str["tag:type=gas"]="Gas";
$lang_str["tag:type=heat"]="Ogrzewanie";
$lang_str["tag:type=hot_water"]="Gorąca woda";
#$lang_str["tag:type=oil"]="Oil";
#$lang_str["tag:type=sewage"]="Sewage";
$lang_str["tag:type=water"]="Woda";

// name
$lang_str["tag:name"]=array("Nazwa", "Nazwy");

// alt_name
$lang_str["tag:alt_name"]=array("Nazwa alternatywna", "Nazwy alternatywne");

// official_name
$lang_str["tag:official_name"]=array("Nazwa oficjalna", "Nazwy oficjalne");

// int_name
$lang_str["tag:int_name"]=array("Nazwa międzynarodowa", "Nazwy międzynarodowe");

// loc_name
$lang_str["tag:loc_name"]=array("Nazwa miejscowa", "Nazwy miejscowe");

// old_name
$lang_str["tag:old_name"]="Stara nazwa";

// ref
$lang_str["tag:ref"]="Odniesienie";

// network
$lang_str["tag:network"]="Sieć";

// opening_hours
$lang_str["tag:opening_hours"]="Godziny otwarcia";

// operator
$lang_str["tag:operator"]="Operator";

// place
$lang_str["tag:place"]="Miejsce";
$lang_str["tag:place=continent"]=array("Kontynent", "Kontynenty");
$lang_str["tag:place=country"]=array("Kraj", "Kraje");
$lang_str["tag:place=state"]=array("Stan", "Stany");
$lang_str["tag:place=region"]=array("Region", "Regiony");
#$lang_str["tag:place=county"]=array("County", "Counties");
$lang_str["tag:place=city"]=array("Miasto", "Miasta");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=village"]=array("Village", "Villages");
#$lang_str["tag:place=suburb"]=array("Suburb", "Suburbs");
#$lang_str["tag:place=hamlet"]=array("Hamlet", "Hamlets");
#$lang_str["tag:place=locality"]=array("Locality", "Localities");
$lang_str["tag:place=island"]=array("Wyspa", "Wyspy");
#$lang_str["tag:place=islet"]=array("Islet", "Islets");
$lang_str["tag:place=ocean"]=array("Ocean", "Oceany");
$lang_str["tag:place=sea"]=array("Morze", "Morza");

// population
$lang_str["tag:population"]="Ludność";

// power
#$lang_str["tag:power"]="Power";
#$lang_str["tag:power=generator"]="Power Generator";
#$lang_str["tag:power=line"]="Power Line";
#$lang_str["tag:power=minor_line"]="Minor Power Line";
#$lang_str["tag:power=tower"]="Power Tower";
#$lang_str["tag:power=pole"]="Power Pole";
#$lang_str["tag:power=station"]="Power Station";
#$lang_str["tag:power=sub_station"]="Power Substation";

// power_source
#$lang_str["tag:power_source"]="Power source";
$lang_str["tag:power_source=biofuel"]="Biopaliwo";
$lang_str["tag:power_source=oil"]="Olej";
$lang_str["tag:power_source=coal"]="Węgiel";
$lang_str["tag:power_source=gas"]="Gaz";
$lang_str["tag:power_source=waste"]="Odpady";
$lang_str["tag:power_source=hydro"]="Woda";
$lang_str["tag:power_source=tidal"]="Przypływ";
$lang_str["tag:power_source=wave"]="Fale";
$lang_str["tag:power_source=geothermal"]="Geotermiczne";
$lang_str["tag:power_source=nuclear"]="Nuklearne";
#$lang_str["tag:power_source=fusion"]="Fusion";
$lang_str["tag:power_source=wind"]="Wiatrowe";
#$lang_str["tag:power_source=photovoltaic"]="Photovoltaic";
#$lang_str["tag:power_source=solar-thermal"]="Solar Thermal";

// railway
#$lang_str["tag:railway"]="Railway";
$lang_str["tag:railway=rail"]=array("Tor Kolejowy", "Tory Kolejowe");
$lang_str["tag:railway=tram"]=array("Tor Tramwajowy", "Tory Tramwajowe");
$lang_str["tag:railway=platform"]=array("Peron", "Perony");

// real_ale
#$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="Religia";
$lang_str["tag:religion=christian"]="chrześcijańska";
$lang_str["tag:religion=buddhist"]="buddyjska";
$lang_str["tag:religion=hindu"]="hindu";
$lang_str["tag:religion=jewish"]="żydowska";
$lang_str["tag:religion=muslim"]="muzułmańska";
#$lang_str["tag:religion=multifaith"]="multifaith";

// denomination
#$lang_str["tag:denomination"]="Denomination";

// route
$lang_str["tag:route"]="Trasa";
#$lang_str["tag:route=train"]="Train";
#$lang_str["tag:route=railway"]="Railway";
#$lang_str["tag:route=rail"]="Railway";
#$lang_str["tag:route=light_rail"]="Light Rail";
$lang_str["tag:route=subway"]="Metro";
$lang_str["tag:route=tram"]="Tramwaj";
$lang_str["tag:route=tram_bus"]="Tramwai i autobus";
#$lang_str["tag:route=trolley"]="Trolley";
#$lang_str["tag:route=trolleybus"]="Trolley";
#$lang_str["tag:route=bus"]="Bus";
#$lang_str["tag:route=minibus"]="Minibus";
#$lang_str["tag:route=ferry"]="Ferry";
#$lang_str["tag:route=road"]="Road";
$lang_str["tag:route=bicycle"]="Rower";
#$lang_str["tag:route=hiking"]="Hiking";
#$lang_str["tag:route=mtb"]="Mountainbike";

// shop
$lang_str["tag:shop"]="Sklep";

// sport
$lang_str["tag:sport"]="Sport";
#$lang_str["tag:sport=9pin"]="9pin Bowling";
#$lang_str["tag:sport=10pin"]="10pin Bowling";
#$lang_str["tag:sport=archery"]="Archery";
#$lang_str["tag:sport=athletics"]="Athletics";
#$lang_str["tag:sport=australian_football"]="Australian Football";
#$lang_str["tag:sport=baseball"]="Baseball";
$lang_str["tag:sport=basketball"]="Koszykówka";
$lang_str["tag:sport=beachvolleyball"]="Siatkówka plażowa";
#$lang_str["tag:sport=boules"]="Boules";
#$lang_str["tag:sport=bowls"]="Bowls";
#$lang_str["tag:sport=canoe"]="Canoe";
$lang_str["tag:sport=chess"]="Szachy";
#$lang_str["tag:sport=climbing"]="Climbing";
$lang_str["tag:sport=cricket"]="Krikiet";
#$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
#$lang_str["tag:sport=croquet"]="Croquet";
$lang_str["tag:sport=cycling"]="Kolarstwo";
$lang_str["tag:sport=diving"]="Nurkowanie";
$lang_str["tag:sport=dog_racing"]="Wyścigi psów";
#$lang_str["tag:sport=equestrian"]="Equestrian";
$lang_str["tag:sport=football"]="Piłka nożna";
$lang_str["tag:sport=golf"]="Golf";
$lang_str["tag:sport=gymnastics"]="Gimnastyka";
$lang_str["tag:sport=hockey"]="Hokej";
$lang_str["tag:sport=horse_racing"]="Wyścigi konne";
#$lang_str["tag:sport=korfball"]="Korfball";
#$lang_str["tag:sport=motor"]="Motor";
#$lang_str["tag:sport=multi"]="Multi";
#$lang_str["tag:sport=orienteering"]="Orienteering";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
#$lang_str["tag:sport=paragliding"]="Paragliding";
#$lang_str["tag:sport=pelota"]="Pelota";
#$lang_str["tag:sport=racquet"]="Racquet";
#$lang_str["tag:sport=rowing"]="Rowing";
$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Strzelectwo";
#$lang_str["tag:sport=skating"]="Skating";
#$lang_str["tag:sport=skateboard"]="Skateboard";
#$lang_str["tag:sport=skiing"]="Skiing";
#$lang_str["tag:sport=soccer"]="Soccer";
$lang_str["tag:sport=swimming"]="Pływanie";
$lang_str["tag:sport=table_tennis"]="Tenis stołowy";
#$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tenis";
$lang_str["tag:sport=volleyball"]="Siatkówka";

// tracks
#$lang_str["tag:tracks"]="Tracks";
#$lang_str["tag:tracks=single"]="Single";
#$lang_str["tag:tracks=double"]="Double";
#$lang_str["tag:tracks=multiple"]="Multiple";

// vending
#$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Napięcie";

// wires
$lang_str["tag:wires"]="Przewody";

// website
$lang_str["tag:website"]="Witryna";

// cycleway
#$lang_str["tag:cycleway"]="Cycleway";

// tracktype
#$lang_str["tag:tracktype"]="Track type";

// waterway
#$lang_str["tag:waterway"]="Waterway";

// aerialway
#$lang_str["tag:aerialway"]="Aerialway";

// public_transport
$lang_str["tag:public_transport"]="Transport Publiczny";

// office
$lang_str["tag:office"]="Biuro";

// craft
#$lang_str["tag:craft"]="Craft";

// emergency
#$lang_str["tag:emergency"]="Emergency";

// tourism
$lang_str["tag:tourism"]="Turystyka";

// historic
#$lang_str["tag:historic"]="Historic";

// landuse
#$lang_str["tag:landuse"]="Landuse";

// wood
#$lang_str["tag:wood"]="Type of wood";

// military
$lang_str["tag:military"]="Wojskowy";

// natural
#$lang_str["tag:natural"]="Natural";

// geological
#$lang_str["tag:geological"]="Geological";

// boundary
#$lang_str["tag:boundary"]="Boundary";

// abutters
#$lang_str["tag:abutters"]="Abutters";

// lit
$lang_str["tag:lit"]="Droga oświetlona";

// area
$lang_str["tag:area"]="Obszar";

// crossing
$lang_str["tag:crossing"]="Przejście";

// mountain_pass
#$lang_str["tag:mountain_pass"]="Mountain Pass";

// cutting
#$lang_str["tag:cutting"]="Cutting";

// embankment
#$lang_str["tag:embankment"]="Embankment";

// lanes
#$lang_str["tag:lanes"]="Lanes";

// layer
$lang_str["tag:layer"]="Warstwa";

// surface
#$lang_str["tag:surface"]="Surface";

// smoothness
$lang_str["tag:smoothness"]="Gładkość";

// ele
#$lang_str["tag:ele"]="Elevation";

// width
$lang_str["tag:width"]="Szerokość";

// est_width
$lang_str["tag:est_width"]="Oszacowana szerokość";

// incline
#$lang_str["tag:incline"]="incline";

// start_date
$lang_str["tag:start_date"]="Data stworzenia";

// end_date
$lang_str["tag:end_date"]="Data usunięcia";

// disused
#$lang_str["tag:disused"]="Disused";

// wheelchair
#$lang_str["tag:wheelchair"]="Wheelchair";
#$lang_str["tag:wheelchair=limited"]="limited";

// tactile_paving
#$lang_str["tag:tactile_paving"]="Tactile paving";

// narrow
$lang_str["tag:narrow"]="Wąski";

// covered
#$lang_str["tag:covered"]="Covered";

// ford
#$lang_str["tag:ford"]="Ford";

// access
$lang_str["tag:access"]="Dostęp";

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
$lang_str["tag:oneway"]="Jednokierunkowa";

// noexit
#$lang_str["tag:noexit"]="Dead end road";

// maxweight
$lang_str["tag:maxweight"]="Max szerokość";

// maxheight
$lang_str["tag:maxheight"]="Max wysokość";

// maxlength
$lang_str["tag:maxlength"]="Max długość";

// maxspeed
$lang_str["tag:maxspeed"]="Max szybkość";

// minspeed
$lang_str["tag:minspeed"]="Min szybkość";

// traffic_sign
#$lang_str["tag:traffic_sign"]="Traffic sign";

// toll
#$lang_str["tag:toll"]="Toll";

// charge
#$lang_str["tag:charge"]="Charge";

// source
$lang_str["tag:source"]="Źródło";

// phone
$lang_str["tag:phone"]="Nr telefonu";

// fax
$lang_str["tag:fax"]="Nr faxu";

// email
$lang_str["tag:email"]="E-mail";

// wikipedia
$lang_str["tag:wikipedia"]="Wikipedia";

// created_by
$lang_str["tag:created_by"]="Utworzone przez";

// construction
$lang_str["tag:construction"]="W budowie";

// proposed
$lang_str["tag:proposed"]="Propozycja";
