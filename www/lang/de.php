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

// General
$lang_str["general_info"]="Allgemeine Informationen";
$lang_str["yes"]="ja";
$lang_str["no"]="nein";
$lang_str["save"]=array("Speichern");
$lang_str["cancel"]=array("Abbrechen");
$lang_str["longitude"]=array("Longitude", "Longituden");
$lang_str["latitude"]=array("Latitude", "Latituden");
$lang_str["noname"]="(kein Name)";
$lang_str["info_back"]="zur Übersicht";
$lang_str["info_zoom"]="zoomen";
$lang_str["nothing_found"]=array("nichts gefunden");
$lang_str["loading"]="lade";

// Headings
$lang_str["head:general_info"]="Allgemeine Informationen";
$lang_str["head:stops"]="Haltestellen";
$lang_str["head:routes"]="Routen";
$lang_str["head:members"]="Mitglieder";
$lang_str["head:address"]="Adresse";
$lang_str["head:internal"]="Interne OSM-Daten";
$lang_str["head:wikipedia"]="Wikipedia";
$lang_str["head:housenumbers"]="Hausnummern";
$lang_str["head:roads"]="Straßen";
$lang_str["head:rails"]="Eisenbahnstrecken";
$lang_str["head:places"]="Orte";
$lang_str["head:borders"]="Grenzen";
$lang_str["head:landuse"]="Landnutzung";
$lang_str["head:buildings"]="Gebäude";
$lang_str["head:pt"]="Öffentlicher Verkehr";
$lang_str["head:services"]="Dienstleistungen";
$lang_str["head:culture"]="Kultureinrichtungen";
$lang_str["head:graves"]="Wichtige Gräber";
$lang_str["head:routing"]="Routenplanung";
$lang_str["head:search"]="Suche";
$lang_str["head:actions"]="Aktionen";
#$lang_str["head:location"]="Location";

$lang_str["action_browse"]="In OSM ansehen";
$lang_str["action_edit"]="Auf OSM editieren";

$lang_str["geo_click_pos"]=array("Klicke auf deine Position auf der Karte");
$lang_str["geo_set_pos"]="Meine Position festlegen";
$lang_str["geo_change_pos"]="Meine Position ändern";

$lang_str["routing_type_car"]="Auto";
$lang_str["routing_type_car_shortest"]="Auto (kürzeste)";
$lang_str["routing_type_bicycle"]="Fahrrad";
$lang_str["routing_type_foot"]="Zu Fuß";
$lang_str["routing_type"]="Routentyp";
$lang_str["routing_distance"]="Entfernung";
$lang_str["routing_time"]="Zeit";
$lang_str["routing_disclaimer"]="Routing: (c) von <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Wähle eine Kategorie, um den Karteninhalt zu durchstöbern oder klicke auf ein Objekt auf der Karte für Details";
$lang_str["list_leisure_sport_tourism"]="Freizeit, Sport und Tourismus";

// Mapkey
$lang_str["map_key:head"]="Legende";
$lang_str["map_key:zoom"]="Zoomstufe";

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:map_key"]="Legende";
$lang_str["main:options"]="Optionen";
$lang_str["main:about"]="Impressum";
$lang_str["main:donate"]="Spende";
$lang_str["main:licence"]="Kartendaten: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a>-Mitwirkende | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> und <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">Mitwirkende</a>";
$lang_str["main:permalink"]="Permalink";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Objekt nicht gefunden</h1>Ein Objekt mit der ID \"%s\" konnte nicht gefunden werden. Das kann eine (oder mehrere) der folgenden Ursachen haben:<ul><li>Die ID ist falsch.</li><li>Das Objekt wurde von einer anderen Applikation identifiziert und ist im OpenStreetBrowser (noch) nicht verfügbar</li><li>Das Objekt liegt ausserhalb des unterstützten Gebiets</li><li>Der Link, dem Du gefolgt bist, war alt und das Objekt wurde inzwischen aus der OpenStreetMap gelöscht.</li>";

$lang_str["start:choose"]=array("Wähle einen Kartenausschnitt");
$lang_str["start:geolocation"]=array("automatische Geolokalisierung");
$lang_str["start:lastview"]=array("letzter Kartenausschnitt");
$lang_str["start:savedview"]=array("letzter Permalink");
$lang_str["start:startnormal"]=array("behalte Ansicht");
$lang_str["start:remember"]=array("Auswahl merken");
$lang_str["start:edit"]=array("editieren...");

$lang_str["options:autozoom"]="Autozoomverhalten";
$lang_str["help:autozoom"]="Wenn ein Objekt ausgewählt wird, schwenkt der Kartenausschnitt zu dem Objekt, auch der Zoomlevel wird angepasst. Mit dieser Option kann zwischen verschiedenen Modi gewählt werden.";
$lang_str["options:autozoom:pan"]="Auf das aktuelle Objekt schwenken (schöner)";
$lang_str["options:autozoom:move"]="Zum aktuellen Objekt springen (schneller)";
$lang_str["options:autozoom:stay"]="Den Kartenausschnitt nie verschieben";

$lang_str["options:language_support"]="Sprachunterstützung";
$lang_str["help:language_support"]="In diesen Optionen können die verwendeten Sprachen eingestellt werden. In der ersten Einstellung kann die Sprache der Anwendungsoberfläche gewählt werden. Mit der zweiten Einstellung kann die Datensprache eingestellt werden. Die Daten vieler geographischer Objekte sind in mehrere Sprachen übersetzt. Wenn keine Übersetzung vorhanden ist, oder \"Lokale Sprache\" ausgewählt wurde, wird die Hauptsprache des Objektes angezeigt.";
$lang_str["options:ui_lang"]="Anwendungssprache";
$lang_str["options:data_lang"]="Datensprache";
$lang_str["lang:"]="Lokale Sprache";

$lang_str["overlay:data"]="Data";
$lang_str["overlay:draggable"]="Markierungen";

$lang_str["wikipedia:read_more"]="weiterlesen";

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";

// please finish this list, see list.php for full list of languages
$lang_str["lang:de"]="Deutsch";
$lang_str["lang:bg"]="Bulgarisch";
$lang_str["lang:cs"]="Tschechisch";
$lang_str["lang:en"]="Englisch";
$lang_str["lang:es"]="Spanisch";
$lang_str["lang:it"]="Italienisch";
$lang_str["lang:fr"]="Französisch";
$lang_str["lang:uk"]="Ukrainisch";
$lang_str["lang:ru"]="Russisch";
$lang_str["lang:ja"]="Japanisch";

// the following language strings are deprecated
$lang_str["cat:leisure"]="Freizeit, Sport und Einkauf";
$lang_str["cat:leisure/gastro"]="Gastronomie";
$lang_str["cat:leisure/leisure"]="Freizeit";
$lang_str["cat:leisure/sport"]="Sport";
$lang_str["cat:leisure/shop"]="Einkauf";
$lang_str["cat:culture"]="Kultur und Religion";
$lang_str["cat:culture/culture"]="Kultur";
$lang_str["cat:culture/religion"]="Religion";
$lang_str["cat:culture/historic"]="Geschichte";
$lang_str["cat:culture/tourism"]="Tourismus";
$lang_str["cat:shop"]="Einkaufen";
$lang_str["cat:services"]="Dienste";
$lang_str["cat:services/communication"]="Kommunikation";
$lang_str["cat:services/financial"]="Finanzen";
$lang_str["cat:services/emergency"]="Notfalldienste";
$lang_str["cat:services/health"]="Gesundheitsdienste";
$lang_str["cat:services/education"]="Bildungseinrichtungen";
$lang_str["cat:services/public"]="Öffentliche Dienste";
$lang_str["cat:services/tourism"]="Tourismusdienste";
$lang_str["cat:places"]=array("Ort", "Orte");
$lang_str["cat:places/places"]=array("Ort", "Orte");
$lang_str["cat:places/residential"]="Wohngebiete";
$lang_str["cat:places/streets"]="Straßenverzeichnis";
$lang_str["cat:places/natural"]="Geographische Objekte";
$lang_str["cat:transport"]="Transport";
$lang_str["cat:transport/car"]="Motorisierter Individualverkehr";
$lang_str["cat:transport/car/amenities"]="Einrichtungen";
$lang_str["cat:transport/car/routes"]=array("Routen");
$lang_str["cat:transport/car/furniture"]="Straßenausstattung";
$lang_str["cat:transport/pt"]="Öffentlicher Verkehr";
$lang_str["cat:transport/pt/amenities"]="Einrichtungen";
$lang_str["cat:transport/pt/routes"]="Routen";
$lang_str["cat:transport/pt/stops"]="Haltestellen";
$lang_str["cat:transport/alternative"]="Alternativ (Rad, Wandern, ...)";
$lang_str["cat:transport/alternative/amenities"]="Einrichtungen";
$lang_str["cat:transport/alternative/routes"]="Routes";
$lang_str["cat:transport/other"]="Andere";
$lang_str["cat:agri_ind"]="Landwirtschaft und Industrie";
$lang_str["cat:agri_ind/power"]="Energie";
$lang_str["cat:agri_ind/works"]="Fabriken";
$lang_str["cat:agri_ind/agriculture"]="Landwirtschaft";
$lang_str["cat:agri_ind/construction"]="Baustellen";
$lang_str["cat:agri_ind/railway"]="Eisenbahn";
$lang_str["cat:agri_ind/resources"]="Ressourcengewinnung";
$lang_str["cat:agri_ind/landfill"]="Entsorgung";
$lang_str["cat:agri_ind/military"]="Militär";

$lang_str["sub_type=t3|type=historic"]="UNESCO Welterbe";
$lang_str["sub_type=|type=place_of_worship"]="Religiöser Ort";
$lang_str["sub_type=t1|type=place_of_worship"]="Christliche Kirche";
$lang_str["sub_type=t2|type=place_of_worship"]="Islamische Moschee";
$lang_str["sub_type=t3|type=place_of_worship"]="Jüdische Synagoge";
#$lang_str["sub_type=t4|type=place_of_worship"]="Sikh ?";

$lang_str["highway_type=motorway"]="Autobahn";
$lang_str["highway_type=trunk"]="Schnellstraße";
$lang_str["highway_type=primary"]="Bundesstraße";
$lang_str["highway_type=secondary"]="Landstraße";
$lang_str["highway_type=tertiary"]="Kreisstraße";
$lang_str["highway_type=minor"]="Nebenstraße";
$lang_str["highway_type=service"]="Zufahrtstraße";
$lang_str["highway_type=pedestrian"]="Fußgängerzone";
$lang_str["highway_type=track"]="Weg";
$lang_str["highway_type=path"]="Pfad";
$lang_str["square_type=pedestrian"]="Platz";
$lang_str["square_type=parking"]="Parkzone";
$lang_str["highway_type=aero_run"]="Start- und Landebahn";
$lang_str["highway_type=aero_taxi"]="Rollbahn";

$lang_str["sub_type=t1|type=communication"]="Postämter";
$lang_str["sub_type=t2|type=communication"]="Briefkasten";
$lang_str["sub_type=t1|type=economic"]="Geldautomat";
$lang_str["sub_type=t2|type=economic"]="Banken";
$lang_str["sub_type=t1|type=services"]="Recycling";
$lang_str["sub_type=t1|type=man_made"]="Turm";
$lang_str["sub_type=t2|type=man_made"]="Windkraftanlage";
$lang_str["sub_type=t3|type=man_made"]="Windmühle";
$lang_str["sub_type=t1|type=emergency"]="Krankenhäuser";
$lang_str["sub_type=t1|type=health"]="Apotheke";
$lang_str["sub_type=t1|type=tourism"]="Hotels, Hostels, ...";
$lang_str["sub_type=t2|type=tourism"]="Campingplatz";
$lang_str["sub_type=t3|type=tourism"]="Touristeninformation";

  // Foos & Drink
  $lang_str["list_food_drink"]="Essen und Trinken";
    $lang_str["list_amenity_biergarten"]="Biergärten";
    $lang_str["list_amenity_restaurant"]="Restaurants";
    $lang_str["list_amenity_fast_food"]="Fast Food-Restaurants";
    $lang_str["list_amenity_cafe"]="Cafes";
    $lang_str["list_amenity_vending_machine"]="Automaten";
    $lang_str["list_amenity_pub"]="Pubs";
    $lang_str["list_amenity_bar"]="Bars";
    $lang_str["list_amenity_nightclub"]="Nachtclubs";
    $lang_str["list_amenity_casino"]="Casinos";
// Leisure
  $lang_str["list_leisure"]="Freizeit";
    $lang_str["list_leisure_sports_centre"]="Sportzentren";
    $lang_str["list_leisure_golf_course"]="Golfplätze";
    $lang_str["list_leisure_stadium"]="Stadien";
    $lang_str["list_leisure_track"]="Rennbahnen";
    $lang_str["list_leisure_pitch"]="Spielfelder";
    $lang_str["list_leisure_water_park"]="Wasserpark";
    $lang_str["list_leisure_marina"]="Yachthäfen";
    $lang_str["list_leisure_slipway"]="Slipanlagen";
    $lang_str["list_leisure_fishing"]="Angeln";
    $lang_str["list_leisure_nature_reserve"]="Naturschutzgebiete";
    $lang_str["list_leisure_park"]="Parks";
    $lang_str["list_leisure_playground"]="Spielplätze";
    $lang_str["list_leisure_garden"]="Gärten";
    $lang_str["list_leisure_common"]="Commons";
    $lang_str["list_leisure_ice_rink"]="Eisbahnen";
    $lang_str["list_leisure_miniature_golf"]="Minigolf";
    $lang_str["list_leisure_swimming_pool"]="Schwimmbecken";
    $lang_str["list_leisure_beach_resort"]="Seebad";
    $lang_str["list_leisure_bird_hide"]="Vogelbeobachtungshütten";
    $lang_str["list_leisure_sport"]="anderer Sport";
// Sport
    $lang_str["list_sport_9pin"]="Kegeln";
    $lang_str["list_sport_10pin"]="Bowling";
    $lang_str["list_sport_archery"]="Bogenschießen";
    $lang_str["list_sport_athletics"]="Leichtathletik";
    $lang_str["list_sport_australian_football"]="Australian Football";
    $lang_str["list_sport_baseball"]="Baseball";
    $lang_str["list_sport_basketball"]="Basketball";
    $lang_str["list_sport_beachvolleyball"]="Beachvolleyball";
    $lang_str["list_sport_boules"]="Boule";
    $lang_str["list_sport_bowls"]="Bowls";
    $lang_str["list_sport_canoe"]="Kanu";
    $lang_str["list_sport_chess"]="Schach";
    $lang_str["list_sport_climbing"]="Klettern";
    $lang_str["list_sport_cricket"]="Cricket";
    $lang_str["list_sport_cricket_nets"]="Cricket-Netze";
    $lang_str["list_sport_croquet"]="Croquet";
    $lang_str["list_sport_cycling"]="Radsport";
    $lang_str["list_sport_diving"]="Tauchen";
    $lang_str["list_sport_dog_racing"]="Hunderennen";
    $lang_str["list_sport_equestrian"]="Reitsport";
    $lang_str["list_sport_football"]="Fußball";
    $lang_str["list_sport_golf"]="Golf";
    $lang_str["list_sport_gymnastics"]="Turnen";
    $lang_str["list_sport_hockey"]="Hockey";
    $lang_str["list_sport_horse_racing"]="Pferderennen";
    $lang_str["list_sport_korfball"]="Korfball";
    $lang_str["list_sport_motor"]="Motorsport";
#    $lang_str["list_sport_multi"]="Multi";
    $lang_str["list_sport_orienteering"]="Orientierungslauf";
#    $lang_str["list_sport_paddle_tennis"]="Paddle Tennis";
    $lang_str["list_sport_paragliding"]="Paragliding";
    $lang_str["list_sport_pelota"]="Pelota";
#    $lang_str["list_sport_racquet"]="Racquet";
    $lang_str["list_sport_rowing"]="Rudern";
    $lang_str["list_sport_rugby"]="Rugby";
    $lang_str["list_sport_shooting"]="Schießen";
    $lang_str["list_sport_skating"]="Skating";
    $lang_str["list_sport_skateboard"]="Skateboard";
    $lang_str["list_sport_skiing"]="Ski";
    $lang_str["list_sport_soccer"]="Fußball";
    $lang_str["list_sport_swimming"]="Schwimmen";
    $lang_str["list_sport_table_tennis"]="Tischtennis";
    $lang_str["list_sport_team_handball"]="Handball";
    $lang_str["list_sport_tennis"]="Tennis";
    $lang_str["list_sport_volleyball"]="Volleyball";
// Cycle & Hiking
  $lang_str["list_cycle_hiking"]="Rad- und Wandereinrichtungen";
  $lang_str["list_ch_routes"]="Rad- und Wanderwege";
    $lang_str["list_shop_bicycle"]="Fahrradgeschäfte";
    $lang_str["list_shop_outdoor"]="Outdoorläden";
    $lang_str["list_amenity_bicycle_rental"]="Fahrradvermietungen";
    $lang_str["list_amenity_bicycle_parking"]="Fahrradparkplätze";
    $lang_str["list_amenity_shelter"]="Schutzhütten";
    $lang_str["list_amenity_drinking_water"]="Trinkwasserstellen";
    $lang_str["list_amenity_signpost"]="Wegweiser";
    $lang_str["list_amenity_alpine_hut"]="Berghütten";
    $lang_str["list_tourism_alpine_hut"]="Berghütten";
    $lang_str["list_amenity_mountain_hut"]="Berghütten";
    $lang_str["list_tourism_picnic_site"]="Picknick-Plätze";
    $lang_str["list_tourism_viewpoint"]="Aussichtspunkte";
  // Tourism
  $lang_str["list_tourism"]="Tourismuseinrichtungen";
    $lang_str["list_tourism_hotel"]="Hotels";
    $lang_str["list_tourism_motel"]="Motels";
    $lang_str["list_tourism_guest_house"]="Gasthäuser";
    $lang_str["list_tourism_hostel"]="Hostels";
    $lang_str["list_tourism_chalet"]="Sennhütten";
    $lang_str["list_tourism_camp_site"]="Zeltplätze";
    $lang_str["list_tourism_caravan_site"]="Campingplätze";
    $lang_str["list_tourism_information"]="Tourist-Informationen";
  // Attractions
  $lang_str["list_attractions"]="Attraktionen";
    $lang_str["list_tourism_theme_park"]="Themenparks";
    $lang_str["list_tourism_zoo"]="Zoos";
    $lang_str["list_tourism_attraction"]="Attraktionen";

$lang_str["list_shopping"]="Einkaufen";
  $lang_str["list_general"]="Allgemein";
    $lang_str["list_shop_mall"]="Einkaufszentren";
    $lang_str["list_shop_shopping_center"]="Einkaufszentren";
    $lang_str["list_shop_shopping_centre"]="Einkaufszentren";
    $lang_str["list_shop_convenience"]="Lebensmittelläden";
    $lang_str["list_shop_department_store"]="Kaufhäuser";
    $lang_str["list_shop_general"]="Warenhäuser";
    $lang_str["list_amenity_marketplace"]="Marktplätze";
  $lang_str["list_food"]="Essen";
    $lang_str["list_shop_supermarket"]="Supermärkte";
    $lang_str["list_shop_groceries"]="Lebensmittelläden";
    $lang_str["list_shop_grocery"]="Lebensmittelläden";
    $lang_str["list_shop_alcohol"]="Alkoholgeschäfte";
    $lang_str["list_shop_bakery"]="Bäckereien";
    $lang_str["list_shop_beverages"]="Getränkemärkte";
    $lang_str["list_shop_butcher"]="Metzgereien";
    $lang_str["list_shop_organic"]="Naturkostläden";
    $lang_str["list_shop_wine"]="Weinhandlungen";
    $lang_str["list_shop_fish"]="Fischgeschäfte";
    $lang_str["list_shop_market"]="Märkte";
  $lang_str["list_sport"]="Sport";
    $lang_str["list_shop_sports"]="Sportgeschäfte";
    $lang_str["list_shop_bicycle"]="Fahrradhändler";
    $lang_str["list_shop_outdoor"]="Outdoorhändler";
  $lang_str["list_culture"]="Kultur";
    $lang_str["list_shop_books"]="Buchläden";
    $lang_str["list_shop_kiosk"]="Kiosks";
    $lang_str["list_shop_video"]="Videotheken";
    $lang_str["list_shop_newsagent"]="Zeitungshändler";
    $lang_str["list_shop_ticket"]="Fahrscheine";
    $lang_str["list_shop_music"]="Musikgeschäfte";
    $lang_str["list_shop_photo"]="Fotogeschäfte";
    $lang_str["list_shop_travel_agency"]="Reisebüros";
  $lang_str["list_car"]="Car &amp; Motorcycle";
    $lang_str["list_shop_car"]="Autowerkstätten";
    $lang_str["list_shop_car_dealer"]="Autohändler";
    $lang_str["list_shop_car_repair"]="Autowerkstätten";
    $lang_str["list_shop_car_parts"]="Autoteilehandel";
    $lang_str["list_shop_motorcycle"]="Motorradhandel";
  $lang_str["list_fashion"]="Mode";
    $lang_str["list_shop_clothes"]="Bekleidungsgeschäfte";
    $lang_str["list_shop_florist"]="Blumenhändler";
    $lang_str["list_shop_hairdresser"]="Friseure";
    $lang_str["list_shop_shoes"]="Schuhgeschäfte";
    $lang_str["list_shop_fashion"]="Modegeschäfte";
    $lang_str["list_shop_jewelry"]="Juweliere";
    $lang_str["list_shop_apparel"]="Bekleidungsgeschäfte";
    $lang_str["list_shop_second_hand"]="Second-Hand-Geschäfte";
  $lang_str["list_home_office"]="Einrichtungsgeschäfte";
    $lang_str["list_shop_computer"]="Computerhändler";
    $lang_str["list_shop_doityourself"]="Baumärkte";
    $lang_str["list_shop_electronics"]="Elektrogeschäfte";
    $lang_str["list_shop_hardware"]="Eisenwarenhändler";
    $lang_str["list_shop_hifi"]="Hifi-Fachhändler";
    $lang_str["list_shop_dry_cleaning"]="Chemische Reinigungen";
    $lang_str["list_shop_furniture"]="Möbelhäuser";
    $lang_str["list_shop_garden_centre"]="Gartenzentren";
    $lang_str["list_shop_laundry"]="Wäschereien";
    $lang_str["list_shop_stationery"]="Schreibwarenhändler";
    $lang_str["list_shop_toys"]="Spieleläden";
    $lang_str["list_shop_estate_agent"]="Immobilienmakler";
    $lang_str["list_shop_pet"]="Tierhandlungen";
//$lang_str["list_health"]="Health";
    $lang_str["list_shop_optician"]="Optiker";
    $lang_str["list_shop_chemist"]="Drogeriemärkte";
    $lang_str["list_shop_drugstore"]="Drogeriemärkte";
    $lang_str["list_shop_pharmacy"]="Apotheken";
  $lang_str["list_othershops"]="Andere";
    $lang_str["list_shop_fixme"]="zu überprüfen";
    $lang_str["list_shop_shop"]="Unbekannte Geschäfte";
    $lang_str["list_shop_other"]="Andere Geschäfte";
    $lang_str["list_shop_vending_machine"]="Verkaufsautomaten";

$lang_str["list_education_culture"]="Bildung und Kultur";
  $lang_str["list_culture"]="Kultur";
    $lang_str["list_amenity_arts_centre"]="Kunstzentren";
    $lang_str["list_amenity_theatre"]="Theater";
    $lang_str["list_tourism_museum"]="Museen";
    $lang_str["list_tourism_artwork"]="Kunstwerke";
    $lang_str["list_amenity_fountain"]="Springbrunnen";
    $lang_str["list_amenity_cinema"]="Kinos";
    $lang_str["list_amenity_studio"]="TV/Radio/Aufnahme-Studios";
    $lang_str["list_shop_trumpet"]="Musikgeschäfte";

  $lang_str["list_education"]="Bildung";
    $lang_str["list_amenity_university"]="Universitäten";
    $lang_str["list_amenity_college"]="Hochschulen";
    $lang_str["list_amenity_school"]="Schulen";
    $lang_str["list_amenity_preschool"]="Vorschulen";
    $lang_str["list_amenity_kindergarten"]="Kindergärten";
    $lang_str["list_amenity_library"]="Bibliotheken";
    $lang_str["list_shop_books"]="Buchhändler";

  $lang_str["list_historic"]="Historische Stätten";
    $lang_str["list_historic_monument"]="Denkmäler";
    $lang_str["list_historic_castle"]="Burg";
    $lang_str["list_historic_ruins"]="Ruinen";
    $lang_str["list_historic_icon"]="Ikonen";
    $lang_str["list_historic_memorial"]="Gedenkstätten";
    $lang_str["list_historic_archaeological_site"]="Grabungsstätten";
    $lang_str["list_historic_unesco_world_heritage"]="UNESCO Weltkulturerbestätten";
    $lang_str["list_historic_UNESCO_world_heritage"]="UNESCO Weltkulturerbestätten";
    $lang_str["list_historic_battlefield"]="Schlachtfelder";
    $lang_str["list_historic_wreck"]="Wracks";
    $lang_str["list_historic_wayside_cross"]="Wegkreuze";
    $lang_str["list_historic_wayside_shrine"]="Bildstöcke";

  $lang_str["list_religion"]="Religion";
    $lang_str["list_amenity_place_of_worship"]="Andachtsorte";
    $lang_str["list_amenity_grave_yard"]="Friedhöfe";
    $lang_str["list_landuse_cemetery"]="Friedhöfe";
    $lang_str["list_amenity_crematorium"]="Krematorien";
    $lang_str["list_cemetery_grave"]="Gräber";
    $lang_str["list_amenity_grave"]="Gräber";

$lang_str["list_services"]="Dienstleistungen";
  $lang_str["list_health"]="Gesundheit";
    $lang_str["list_amenity_hospital"]="Krankenhäuser";
    $lang_str["list_amenity_doctor"]="Ärzte";
    $lang_str["list_amenity_doctors"]="Ärzte";
    $lang_str["list_amenity_dentist"]="Zahnärzte";
    $lang_str["list_amenity_pharmacy"]="Apotheken";
    $lang_str["list_amenity_veterinary"]="Tierärzte";
    $lang_str["list_amenity_red_cross"]="Rettungsdienste";
    $lang_str["list_amenity_baby_hatch"]="Babyklappen";

  $lang_str["list_public"]="Öffentliche Einrichtungen";
    $lang_str["list_amenity_townhall"]="Rathäuser";
    $lang_str["list_amenity_public_building"]="Öffentliche Gebäude";
    $lang_str["list_amenity_fire_station"]="Feuerwachen";
    $lang_str["list_amenity_police"]="Polizeiwachen";
    $lang_str["list_amenity_embassy"]="Botschaften";
    $lang_str["list_amenity_courthouse"]="Gerichte";
    $lang_str["list_amenity_prison"]="Gefängnisse";

  $lang_str["list_communication"]="Kommunikation";
    $lang_str["list_amenity_telephone"]="Telefone";
    $lang_str["list_amenity_emergency_phone"]="Notfalltelefone";
    $lang_str["list_amenity_post_office"]="Postämter";
    $lang_str["list_amenity_post_box"]="Briefkästen";
    $lang_str["list_amenity_wlan"]="WLAN Accesspoints";
    $lang_str["list_amenity_WLAN"]="WLAN Accesspoints";

  $lang_str["list_disposal"]="Entsorgung";
    $lang_str["list_amenity_recycling"]="Recyclinghöfe";
    $lang_str["list_amenity_toilets"]="Toiletten";
    $lang_str["list_amenity_waste_disposal"]="Mülldeponien";

$lang_str["list_transport"]="Transport";
  $lang_str["list_car_motorcycle"]="Auto und Motorrad";
    $lang_str["list_amenity_fuel"]="Tankstellen";
    $lang_str["list_amenity_car_rental"]="Autovermietungen";
    $lang_str["list_amenity_car_sharing"]="Carsharing-Stationen";
    $lang_str["list_amenity_parking"]="Parkplätze";
    $lang_str["list_shop_car"]="Autogeschäfte";
    $lang_str["list_shop_car_repair"]="Autowerkstätten";

  $lang_str["list_pt_amenities"]="Einrichtungen des öff. Verkehrs";
    $lang_str["list_amenity_taxi"]="Taxistände";
    $lang_str["list_amenity_ticket_counter"]="Fahrkartenschalter";

  $lang_str["list_pt_stops"]="Haltestellen des öff. Verkehrs";
  $lang_str["list_pt_routes"]="Routen des öff. Verkehrs";

#  $lang_str["list_pipes"]="Goods (Pipes, Power, ...)";
    $lang_str["list_power_line"]="Stromleitungen";
    $lang_str["list_man_made_pipeline"]="Pipelines";

$lang_str["list_places"]="Orte";
  $lang_str["list_streets"]="Straßenverzeichnis";
  $lang_str["list_nature_recreation"]="Natur &amp; Erholung";
    $lang_str["list_leisure_park"]="Parks";
    $lang_str["list_leisure_nature_reserve"]="Naturschutzgebiete";
    $lang_str["list_leisure_common"]="Commons";
    $lang_str["list_leisure_garden"]="Gärten";
  $lang_str["list_natural"]="Natürliche Formationen";
    $lang_str["list_natural_peaks"]="Gipfel";
    $lang_str["list_natural_spring"]="Quellen";
    $lang_str["list_natural_glacier"]="Gletscher";
    $lang_str["list_natural_volcano"]="Vulkane";
    $lang_str["list_natural_cliff"]="Klippen";
    $lang_str["list_natural_scree"]="Geröllhalden";
    $lang_str["list_natural_fell"]="Fjells";
    $lang_str["list_natural_heath"]="Heiden";
    $lang_str["list_natural_wood"]="Wälder";
    $lang_str["list_landuse_forest"]="Forste";
    $lang_str["list_natural_marsh"]="Marschen";
    $lang_str["list_natural_wetland"]="Moore";
    $lang_str["list_natural_water"]="Gewässer";
    $lang_str["list_natural_beach"]="Strände";
    $lang_str["list_natural_bay"]="Buchten";
    $lang_str["list_natural_land"]="Inseln";
    $lang_str["list_natural_cave_entrance"]="Höhleneingänge";
    $lang_str["list_natural_tree"]="Bäume";

$lang_str["list_industry"]="Industrie";
$lang_str["list_power"]="Energieversorgung";
  $lang_str["list_power_generator"]="Kraftwerke";
  $lang_str["list_power_station"]="Umspannwerke";
  $lang_str["list_power_sub_station"]="Umspannstationen";
$lang_str["list_works"]="Industrieanlagen";
  $lang_str["list_landuse_industrial"]="Industriegebiete";
  $lang_str["list_man_made_works"]="Industrieanlagen";

$lang_str["route_international"]="Internationale Routen";
$lang_str["route_national"]="Nationale Routen";
$lang_str["route_region"]="Regionale Routen";
$lang_str["route_urban"]="Städtische Routen";
$lang_str["route_suburban"]="Vorstädtische Routen";
$lang_str["route_local"]="Lokale Routen";
$lang_str["route_no"]="Keine Routen gefunden";
$lang_str["route_zoom"]="Hereinzoomen für Routenliste";

$lang_str["station_type_amenity_bus_station"]="Busbahnhöfe";
$lang_str["station_type_amenity_ferry_terminal"]="Fähranleger";
$lang_str["station_type_highway_bus_stop"]="Bushaltestellen";
$lang_str["station_type_railway_tram_stop"]="Straßenbahnhaltestellen";
$lang_str["station_type_railway_station"]="Bahnhöfe";
$lang_str["station_type_railway_halt"]="Haltepunkte";
// ATTENTION: the last >400 language strings are deprecated


// The following $lang_str are not defined in www/lang/en.php and might be 
// deprecated/mislocated/wrong:
$lang_str["result_no"]="nichts gefunden";
$lang_str["place_continent"]="Kontinent";
$lang_str["place_country"]="Staat";
$lang_str["place_state"]="Land";
$lang_str["place_region"]="Region";
$lang_str["place_county"]="Kreis, Bezirk";
$lang_str["place_city"]="Großstadt";
$lang_str["place_town"]="Stadt";
$lang_str["place_village"]="Dorf";
$lang_str["place_hamlet"]="Weiler";
$lang_str["place_suburb"]="Ortsteil";
$lang_str["place_locality"]="Ort";
$lang_str["place_island"]="Insel";
$lang_str["tag_place"]="Ort";
$lang_str["tag_operator"]="Betreiber";
$lang_str["tag_population"]="Bevölkerung";
$lang_str["tag_is_in"]="Befindet sich in";
$lang_str["tag_website"]="Homepage";
$lang_str["tag_address"]="Adresse";
$lang_str["tag_capital"]="Hauptstadt";
$lang_str["tag_note"]="Interne Notiz";
$lang_str["tag_stops"]="Haltestellen";
$lang_str["tag_religion"]="Religion";
$lang_str["tag_denomination"]="Denomination";
$lang_str["tag_amenity"]="Einrichtung";
$lang_str["amenity_grave_yard"]="Friedhof";
$lang_str["amenity_cemetery"]="Friedhof";
$lang_str["christian"]="christlich";
$lang_str["muslim"]="muslimisch";
$lang_str["catholic"]="katholisch";
$lang_str["tag:amenity=restaurant"]=array("Restaurant", "Restaurants");
$lang_str["cat:industry"]="Industrie";
$lang_str["cat:industry/power"]="Energie";
$lang_str["cat:industry/works"]="Fabriken";

