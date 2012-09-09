<?
// All tags should have a translation, with language strings like "tag:key" for the translation of the key and "tag:key=value" for the translation of the value. E.g. tag:amenity "Amenity;Amenities" resp. tag:amenity=bar "Bar;Bars". You can also define the Gender like "F;Bar;Bars".

// *
$lang_str["tag:*=yes"]="jah";
$lang_str["tag:*=no"]="ei";

// accomodation
#$lang_str["tag:accomodation"]="Accomodation";

// address
$lang_str["tag:address"]="Aadress";

// addr:housenumber
$lang_str["tag:addr:housenumber"]=array("Majanumber", "Majanumbrid");

// addr:housename
$lang_str["tag:addr:housename"]=array("Majanimi", "Majanimed");

// addr:street
$lang_str["tag:addr:street"]=array("Tänav", "Tänavad");

// addr:postcode
$lang_str["tag:addr:postcode"]=array("Postiindeks", "Postiindeksid");

// addr:city
$lang_str["tag:addr:city"]=array("Linn", "Linnad");

// addr:country
$lang_str["tag:addr:country"]=array("Riik", "Riigid");

// addr:full
$lang_str["tag:addr:full"]=array("Täielik aadress", "Täielikud aadressid");

// addr:interpolation
#$lang_str["tag:addr:interpolation"]="Interpolated housenumbers";

// aeroway
#$lang_str["tag:aeroway"]="Aeroway";
#$lang_str["tag:aeroway=runway"]="Runway";
#$lang_str["tag:aeroway=taxiway"]="Taxiway";

// admin_level
$lang_str["tag:admin_level=2"]="Riigipiir";
#$lang_str["tag:admin_level=3"]="Divisions";
$lang_str["tag:admin_level=4"]="Osariigi piir";
#$lang_str["tag:admin_level=5"]="Community Border";
#$lang_str["tag:admin_level=6"]="County Border";
#$lang_str["tag:admin_level=8"]="Town/Municipality Border";
$lang_str["tag:admin_level=10"]="Linnaosad";

// amenity
$lang_str["tag:amenity"]="Hüve";
$lang_str["tag:amenity=cinema"]=array("Kino", "Kinod");
$lang_str["tag:amenity=restaurant"]=array("Restoran", "Restoranid");
$lang_str["tag:amenity=pub"]=array("Pubi", "Pubid");

// barrier
#$lang_str["tag:barrier"]=array("Barrier", "Barriers");
$lang_str["tag:barrier=city_wall"]=array("Linnamüür", "Linnamüürid");
$lang_str["tag:barrier=wall"]="Müür,Müürid";
#$lang_str["tag:barrier=retaining_wall"]=array("Retaining Wall", "Retaining Walls");
$lang_str["tag:barrier=fence"]=array("Aed", "Aiad");
$lang_str["tag:barrier=hedge"]=array("Hekk", "Hekid");

// cables
#$lang_str["tag:cables"]="Cables";

// description
$lang_str["tag:description"]="Kirjeldus";

// fixme
#$lang_str["tag:fixme"]="Fix me";

// note
$lang_str["tag:note"]="Märkus";

// food
#$lang_str["tag:food"]="Serves food";

// cuisine
$lang_str["tag:cuisine"]="Köök";
#$lang_str["tag:cuisine=regional"]="regional";

// highway
#$lang_str["tag:highway"]=array("Highway", "Highways");
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
#$lang_str["tag:highway=service"]="Service Road";
#$lang_str["tag:highway=pedestrian"]="Pedestrian Zone";
#$lang_str["tag:highway=living_street"]="Living Street";
#$lang_str["tag:highway=path"]="Path";
#$lang_str["tag:highway=cycleway"]="Cycleway";
#$lang_str["tag:highway=footway"]="Footway";
#$lang_str["tag:highway=bridleway"]="Bridleway";
#$lang_str["tag:highway=track"]="Track";
$lang_str["tag:highway=steps"]="Trepp";

// bridge
$lang_str["tag:bridge"]="Sild";

// tunnel
$lang_str["tag:tunnel"]="Tunnel";

// traffic_calming
$lang_str["tag:traffic_calming"]="Liikluse rahustamine";

// service
#$lang_str["tag:service"]="Service road attributes";

// postal_code
$lang_str["tag:postal_code"]="Postiindeks";

// is_in
#$lang_str["tag:is_in"]="Is in";

// leisure
#$lang_str["tag:leisure"]="Leisure";
$lang_str["tag:leisure=sports_centre"]="Spordikeskus";
$lang_str["tag:leisure=golf_course"]="Golfiväljak";
$lang_str["tag:leisure=stadium"]="Staadion";
#$lang_str["tag:leisure=track"]="Track";
#$lang_str["tag:leisure=pitch"]="Pitche";
$lang_str["tag:leisure=water_park"]="Veepark";
#$lang_str["tag:leisure=marina"]="Marina";
#$lang_str["tag:leisure=slipway"]="Slipway";
#$lang_str["tag:leisure=fishing"]="Fishing";
#$lang_str["tag:leisure=nature_reserve"]="Nature Reserve";
#$lang_str["tag:leisure=park"]="Leisure Park";
#$lang_str["tag:leisure=playground"]="Playground";
#$lang_str["tag:leisure=garden"]="Garden";
#$lang_str["tag:leisure=common"]="Common";
#$lang_str["tag:leisure=ice_rink"]="Ice Rink";
#$lang_str["tag:leisure=miniature_golf"]="Miniature Golf";
#$lang_str["tag:leisure=swimming_pool"]="Swimming Pool";
#$lang_str["tag:leisure=beach_resort"]="Beach Resort";
#$lang_str["tag:leisure=bird_hide"]="Bird Hide";
#$lang_str["tag:leisure=sport"]="Other Sport";

// man_made
#$lang_str["tag:man_made"]="Artificial structures";
#$lang_str["tag:man_made=pipeline"]=array("Pipeline", "Pipelines");

// type
#$lang_str["tag:type"]="Type";
$lang_str["tag:type=gas"]="Gaas";
#$lang_str["tag:type=heat"]="Heat";
#$lang_str["tag:type=hot_water"]="Hot Water";
#$lang_str["tag:type=oil"]="Oil";
#$lang_str["tag:type=sewage"]="Sewage";
#$lang_str["tag:type=water"]="Water";

// name
$lang_str["tag:name"]=array("Nimi", "Nimed");

// alt_name
$lang_str["tag:alt_name"]=array("Alternatiivne nimi", "Alternatiivsed nimed");

// official_name
$lang_str["tag:official_name"]=array("Ametlik nimi", "Ametlikud nimed");

// int_name
$lang_str["tag:int_name"]=array("Rahvusvaheline nimi", "Rahvusvahelised nimed");

// loc_name
$lang_str["tag:loc_name"]=array("Kohalik nimi", "Kohalikud nimed");

// old_name
$lang_str["tag:old_name"]="Vana nimi(nimed)";

// ref
$lang_str["tag:ref"]="Viide";

// network
$lang_str["tag:network"]="Võrk";

// opening_hours
$lang_str["tag:opening_hours"]="Avatud";

// operator
#$lang_str["tag:operator"]="Operator";

// place
#$lang_str["tag:place"]="Place";
$lang_str["tag:place=continent"]=array("Kontinent", "Kontinendid");
$lang_str["tag:place=country"]=array("Riik", "Riigid");
#$lang_str["tag:place=state"]=array("State", "States");
#$lang_str["tag:place=region"]=array("Region", "Regions");
#$lang_str["tag:place=county"]=array("County", "Counties");
#$lang_str["tag:place=city"]=array("City", "Cities");
#$lang_str["tag:place=town"]="Town";
#$lang_str["tag:place=village"]=array("Village", "Villages");
#$lang_str["tag:place=suburb"]=array("Suburb", "Suburbs");
#$lang_str["tag:place=hamlet"]=array("Hamlet", "Hamlets");
#$lang_str["tag:place=locality"]=array("Locality", "Localities");
$lang_str["tag:place=island"]=array("Saar", "Saared");
#$lang_str["tag:place=islet"]=array("Islet", "Islets");
$lang_str["tag:place=ocean"]="Ookean,Ookeanid";
$lang_str["tag:place=sea"]=array("Meri", "Mered");

// population
#$lang_str["tag:population"]="Population";

// power
#$lang_str["tag:power"]="Power";
#$lang_str["tag:power=generator"]="Power Generator";
#$lang_str["tag:power=line"]="Power Line";
#$lang_str["tag:power=minor_line"]="Minor Power Line";
#$lang_str["tag:power=tower"]="Power Tower";
#$lang_str["tag:power=pole"]="Power Pole";
$lang_str["tag:power=station"]="Elektrijaam";
#$lang_str["tag:power=sub_station"]="Power Substation";

// power_source
#$lang_str["tag:power_source"]="Power source";
$lang_str["tag:power_source=biofuel"]="Biokütus";
#$lang_str["tag:power_source=oil"]="Oil";
$lang_str["tag:power_source=coal"]="Kivisüsi";
$lang_str["tag:power_source=gas"]="Gaas";
#$lang_str["tag:power_source=waste"]="Waste";
#$lang_str["tag:power_source=hydro"]="Hydro";
#$lang_str["tag:power_source=tidal"]="Tidal";
#$lang_str["tag:power_source=wave"]="Wave";
#$lang_str["tag:power_source=geothermal"]="Geothermal";
#$lang_str["tag:power_source=nuclear"]="Nuclear";
#$lang_str["tag:power_source=fusion"]="Fusion";
#$lang_str["tag:power_source=wind"]="Wind";
#$lang_str["tag:power_source=photovoltaic"]="Photovoltaic";
#$lang_str["tag:power_source=solar-thermal"]="Solar Thermal";

// railway
#$lang_str["tag:railway"]="Railway";
#$lang_str["tag:railway=rail"]=array("Rail Track", "Rail Tracks");
#$lang_str["tag:railway=tram"]=array("Tram Track", "Tram Tracks");
#$lang_str["tag:railway=platform"]=array("Platform", "Platforms");

// real_ale
#$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="Religioon";
$lang_str["tag:religion=christian"]="kristlik";
$lang_str["tag:religion=buddhist"]="budistlik";
$lang_str["tag:religion=hindu"]="hinduistlik";
$lang_str["tag:religion=jewish"]="juudi";
$lang_str["tag:religion=muslim"]="moslemi";
#$lang_str["tag:religion=multifaith"]="multifaith";

// denomination
$lang_str["tag:denomination"]="Usutunnistus";
$lang_str["tag:denomination=anglican"]="anglikaani";
$lang_str["tag:denomination=baptist"]="baptistlik";
$lang_str["tag:denomination=catholic"]="katoliiklik";
$lang_str["tag:denomination=lutheran"]="luteri";
$lang_str["tag:denomination=orthodox"]="ortodokslik";
$lang_str["tag:denomination=protestant"]="protestantlik";
$lang_str["tag:denomination=russian_orthodox"]="vene õigeusklik";

// route
#$lang_str["tag:route"]="Route";
$lang_str["tag:route=train"]="Rong";
#$lang_str["tag:route=railway"]="Railway";
#$lang_str["tag:route=rail"]="Railway";
#$lang_str["tag:route=light_rail"]="Light Rail";
#$lang_str["tag:route=subway"]="Subway";
$lang_str["tag:route=tram"]="Tramm";
$lang_str["tag:route=tram_bus"]="Tramm ja buss";
$lang_str["tag:route=trolley"]="Troll";
$lang_str["tag:route=trolleybus"]="Troll";
$lang_str["tag:route=bus"]="Buss";
#$lang_str["tag:route=minibus"]="Minibus";
#$lang_str["tag:route=ferry"]="Ferry";
#$lang_str["tag:route=road"]="Road";
$lang_str["tag:route=bicycle"]="Jalgratas";
#$lang_str["tag:route=hiking"]="Hiking";
#$lang_str["tag:route=mtb"]="Mountainbike";

// shop
$lang_str["tag:shop"]="Pood";

// sport
$lang_str["tag:sport"]="Sport";
#$lang_str["tag:sport=9pin"]="9pin Bowling";
#$lang_str["tag:sport=10pin"]="10pin Bowling";
#$lang_str["tag:sport=archery"]="Archery";
#$lang_str["tag:sport=athletics"]="Athletics";
#$lang_str["tag:sport=australian_football"]="Australian Football";
#$lang_str["tag:sport=baseball"]="Baseball";
$lang_str["tag:sport=basketball"]="Korvpall";
#$lang_str["tag:sport=beachvolleyball"]="Beachvolleyball";
#$lang_str["tag:sport=boules"]="Boules";
#$lang_str["tag:sport=bowls"]="Bowls";
#$lang_str["tag:sport=canoe"]="Canoe";
$lang_str["tag:sport=chess"]="Male";
#$lang_str["tag:sport=climbing"]="Climbing";
#$lang_str["tag:sport=cricket"]="Cricket";
#$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
#$lang_str["tag:sport=croquet"]="Croquet";
#$lang_str["tag:sport=cycling"]="Cycling";
#$lang_str["tag:sport=diving"]="Diving";
#$lang_str["tag:sport=dog_racing"]="Dog Racing";
#$lang_str["tag:sport=equestrian"]="Equestrian";
#$lang_str["tag:sport=football"]="Football";
$lang_str["tag:sport=golf"]="Golf";
#$lang_str["tag:sport=gymnastics"]="Gymnastics";
#$lang_str["tag:sport=hockey"]="Hockey";
#$lang_str["tag:sport=horse_racing"]="Horse Racing";
#$lang_str["tag:sport=korfball"]="Korfball";
#$lang_str["tag:sport=motor"]="Motor";
#$lang_str["tag:sport=multi"]="Multi";
#$lang_str["tag:sport=orienteering"]="Orienteering";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
#$lang_str["tag:sport=paragliding"]="Paragliding";
#$lang_str["tag:sport=pelota"]="Pelota";
#$lang_str["tag:sport=racquet"]="Racquet";
#$lang_str["tag:sport=rowing"]="Rowing";
#$lang_str["tag:sport=rugby"]="Rugby";
#$lang_str["tag:sport=shooting"]="Shooting";
#$lang_str["tag:sport=skating"]="Skating";
#$lang_str["tag:sport=skateboard"]="Skateboard";
#$lang_str["tag:sport=skiing"]="Skiing";
#$lang_str["tag:sport=soccer"]="Soccer";
#$lang_str["tag:sport=swimming"]="Swimming";
$lang_str["tag:sport=table_tennis"]="Lauatennis";
#$lang_str["tag:sport=team_handball"]="Handball";
$lang_str["tag:sport=tennis"]="Tennis";
$lang_str["tag:sport=volleyball"]="Võrkpall";

// tracks
#$lang_str["tag:tracks"]="Tracks";
#$lang_str["tag:tracks=single"]="Single";
#$lang_str["tag:tracks=double"]="Double";
#$lang_str["tag:tracks=multiple"]="Multiple";

// vending
#$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Pinge";

// wires
#$lang_str["tag:wires"]="Wires";

// website
$lang_str["tag:website"]="Veebileht";

// cycleway
$lang_str["tag:cycleway"]="Jalgrattatee";

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
$lang_str["tag:tourism"]="Turism";

// historic
$lang_str["tag:historic"]="Ajalooline";

// landuse
$lang_str["tag:landuse"]="Maakasutus";

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
$lang_str["tag:oneway"]="Ühesuunaline";

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
$lang_str["tag:phone"]="Telefoninumber";

// fax
$lang_str["tag:fax"]="Faksinumber";

// email
$lang_str["tag:email"]="E-post";

// wikipedia
$lang_str["tag:wikipedia"]="Vikipeedia";

// created_by
#$lang_str["tag:created_by"]="Created by";

// construction
#$lang_str["tag:construction"]="Construction";

// proposed
#$lang_str["tag:proposed"]="Proposed";
