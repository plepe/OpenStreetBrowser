<?
// All tags should have a translation, with language strings like "tag:key" for the translation of the key and "tag:key=value" for the translation of the value. E.g. tag:amenity "Amenity;Amenities" resp. tag:amenity=bar "Bar;Bars". You can also define the Gender like "F;Bar;Bars".

// *
$lang_str["tag:*=yes"]="ναι";
$lang_str["tag:*=no"]="όχι";

// accomodation
$lang_str["tag:accomodation"]="Διαμονή";

// address
$lang_str["tag:address"]="Διεύθυνση";

// addr:housenumber
$lang_str["tag:addr:housenumber"]=array("Αριθμός σπιτιού", "Αριθμοί σπιτιών");

// addr:housename
$lang_str["tag:addr:housename"]=array("Όνομα σπιτιού", "Ονόματα σπιτιών");

// addr:street
$lang_str["tag:addr:street"]=array("Οδός", "Οδοί");

// addr:postcode
$lang_str["tag:addr:postcode"]=array("Ταχ. Κώδικας", "Ταχ. Κώδικες");

// addr:city
$lang_str["tag:addr:city"]=array("Πόλη", "Πόλεις");

// addr:country
$lang_str["tag:addr:country"]=array("Χώρα", "Χώρες");

// addr:full
$lang_str["tag:addr:full"]=array("Πλήρης διεύθυνση", "Πλήρεις διευθύνσεις");

// addr:interpolation
#$lang_str["tag:addr:interpolation"]="Interpolated housenumbers";

// aeroway
#$lang_str["tag:aeroway"]="Aeroway";
#$lang_str["tag:aeroway=runway"]="Runway";
#$lang_str["tag:aeroway=taxiway"]="Taxiway";

// admin_level
$lang_str["tag:admin_level=2"]="Όρια Χώρας";
#$lang_str["tag:admin_level=3"]="Divisions";
$lang_str["tag:admin_level=4"]="Όρια Περιφέρειας";
#$lang_str["tag:admin_level=5"]="Community Border";
$lang_str["tag:admin_level=6"]="Όρια Νομού";
$lang_str["tag:admin_level=8"]="Όρια Δήμου";
$lang_str["tag:admin_level=10"]="Διαμέρισμα Δήμου";

// amenity
#$lang_str["tag:amenity"]="Amenity";
$lang_str["tag:amenity=cinema"]=array("Κινηματογράφος", "Κινηματογράφοι");
$lang_str["tag:amenity=restaurant"]=array("Εστιατόριο", "Εστιατόρια");
#$lang_str["tag:amenity=pub"]=array("Pub", "Pubs");

// barrier
$lang_str["tag:barrier"]=array("Μπάρα", "Μπάρες");
#$lang_str["tag:barrier=city_wall"]=array("City wall", "City walls");
$lang_str["tag:barrier=wall"]=array("Τοίχος", "Τοίχοι");
#$lang_str["tag:barrier=retaining_wall"]=array("Retaining Wall", "Retaining Walls");
$lang_str["tag:barrier=fence"]=array("Φράχτης", "Φράχτες");
#$lang_str["tag:barrier=hedge"]=array("Hedge", "Hedges");

// cables
#$lang_str["tag:cables"]="Cables";

// description
$lang_str["tag:description"]="Περιγραφή";

// fixme
#$lang_str["tag:fixme"]="Fix me";

// note
$lang_str["tag:note"]="Σημείωση";

// food
$lang_str["tag:food"]="Σερβίρισμα φαγητού";

// cuisine
$lang_str["tag:cuisine"]="Κουζίνα";
$lang_str["tag:cuisine=regional"]="τοπική";

// highway
$lang_str["tag:highway"]=array("Οδική αρτηρία", "Οδικές αρτηρίες");
$lang_str["tag:highway=motorway"]="Αυτοκινητόδρομος";
$lang_str["tag:highway=motorway_link"]="Σύνδεση Αυτοκινητόδρομου";
#$lang_str["tag:highway=trunk"]="Trunk Road";
#$lang_str["tag:highway=trunk_link"]="Trunk Road Link";
$lang_str["tag:highway=primary"]="Πρωτεύων Δρόμος";
$lang_str["tag:highway=primary_link"]="Σύνδεση Πρωτεύοντος Δρόμου";
$lang_str["tag:highway=secondary"]="Δευτερεύων Δρόμος";
$lang_str["tag:highway=tertiary"]="Τριτεύων Δρόμος";
$lang_str["tag:highway=minor"]="Μικρός Δρόμος";
$lang_str["tag:highway=road"]="Δρόμος";
#$lang_str["tag:highway=residential"]="Residential Road";
#$lang_str["tag:highway=unclassified"]="Unclassified Road";
#$lang_str["tag:highway=service"]="Service Road";
$lang_str["tag:highway=pedestrian"]="Ζώνη πεζών";
#$lang_str["tag:highway=living_street"]="Living Street";
#$lang_str["tag:highway=path"]="Path";
$lang_str["tag:highway=cycleway"]="Ποδηλατόδρομος";
$lang_str["tag:highway=footway"]="Μονοπάτι";
#$lang_str["tag:highway=bridleway"]="Bridleway";
$lang_str["tag:highway=track"]="Χωματόδρομος";
$lang_str["tag:highway=steps"]="Σκαλιά";

// bridge
$lang_str["tag:bridge"]="Γέφυρα";

// tunnel
$lang_str["tag:tunnel"]="Σήραγγα";

// traffic_calming
#$lang_str["tag:traffic_calming"]="Traffic calming";

// service
#$lang_str["tag:service"]="Service road attributes";

// postal_code
$lang_str["tag:postal_code"]="Ταχ. Κώδικας";

// is_in
#$lang_str["tag:is_in"]="Is in";

// leisure
$lang_str["tag:leisure"]="Ελεύθερος Χρόνος";
$lang_str["tag:leisure=sports_centre"]="Αθλητικό Κέντρο";
$lang_str["tag:leisure=golf_course"]="Γήπεδο Γκολφ";
$lang_str["tag:leisure=stadium"]="Στάδιο";
#$lang_str["tag:leisure=track"]="Track";
#$lang_str["tag:leisure=pitch"]="Pitche";
#$lang_str["tag:leisure=water_park"]="Water Park";
$lang_str["tag:leisure=marina"]="Μαρίνα";
$lang_str["tag:leisure=slipway"]="Γλίστρα";
$lang_str["tag:leisure=fishing"]="Ψάρεμα";
#$lang_str["tag:leisure=nature_reserve"]="Nature Reserve";
#$lang_str["tag:leisure=park"]="Leisure Park";
#$lang_str["tag:leisure=playground"]="Playground";
$lang_str["tag:leisure=garden"]="Κήπος";
#$lang_str["tag:leisure=common"]="Common";
#$lang_str["tag:leisure=ice_rink"]="Ice Rink";
$lang_str["tag:leisure=miniature_golf"]="Μίνι Γκολφ";
$lang_str["tag:leisure=swimming_pool"]="Πισίνα";
#$lang_str["tag:leisure=beach_resort"]="Beach Resort";
$lang_str["tag:leisure=bird_hide"]="Καταφύγιο Πουλιών";
$lang_str["tag:leisure=sport"]="Άλλο Άθλημα";

// man_made
$lang_str["tag:man_made"]="Τεχνητές Κατασκευές";
$lang_str["tag:man_made=pipeline"]=array("Αγωγός", "Αγωγοί");

// type
$lang_str["tag:type"]="Τύπος";
$lang_str["tag:type=gas"]="Αέριο";
$lang_str["tag:type=heat"]="Θέρμανση";
$lang_str["tag:type=hot_water"]="Ζεστό Νερό";
$lang_str["tag:type=oil"]="Πετρέλαιο";
$lang_str["tag:type=sewage"]="Απόβλητα";
$lang_str["tag:type=water"]="Νερό";

// name
$lang_str["tag:name"]=array("Όνομα", "Ονόματα");

// alt_name
$lang_str["tag:alt_name"]=array("Εναλλακτικό όνομα", "Εναλλακτικά ονόματα");

// official_name
$lang_str["tag:official_name"]=array("Επίσημο όνομα", "Επίσημα ονόματα");

// int_name
$lang_str["tag:int_name"]=array("Διεθνές όνομα", "Διεθνή ονόματα");

// loc_name
$lang_str["tag:loc_name"]=array("Τοπικό όνομα", "Τοπικά ονόματα");

// old_name
$lang_str["tag:old_name"]="Παλιό(α) Όνομα(τα)";

// ref
#$lang_str["tag:ref"]="Reference";

// network
$lang_str["tag:network"]="Δίκτυο";

// opening_hours
$lang_str["tag:opening_hours"]="Ώρες λειτουργίας";

// operator
$lang_str["tag:operator"]="Διαχειριστής";

// place
$lang_str["tag:place"]="Τόπος";
$lang_str["tag:place=continent"]=array("Ήπειρος", "Ήπειροι");
$lang_str["tag:place=country"]=array("Χώρα", "Χώρες");
$lang_str["tag:place=state"]=array("Πολιτεία", "Πολιτείες");
$lang_str["tag:place=region"]=array("Περιοχή", "Περιοχές");
$lang_str["tag:place=county"]=array("Επαρχία", "Επαρχίες");
$lang_str["tag:place=city"]=array("Πόλη", "Πόλεις");
$lang_str["tag:place=town"]="Κωμόπολη";
$lang_str["tag:place=village"]=array("Χωριό", "Χωριά");
$lang_str["tag:place=suburb"]=array("Προάστιο", "Προάστια");
$lang_str["tag:place=hamlet"]=array("Οικισμός", "Οικισμοί");
$lang_str["tag:place=locality"]=array("Τοποθεσία", "Τοποθεσίες");
$lang_str["tag:place=island"]=array("Νησί", "Νησιά");
$lang_str["tag:place=islet"]=array("Νησίδα", "Νησίδες");
$lang_str["tag:place=ocean"]=array("Ωκεανός", "Ωκεανοί");
$lang_str["tag:place=sea"]=array("Θάλασσα", "Θάλασσες");

// population
$lang_str["tag:population"]="Πληθυσμός";

// power
$lang_str["tag:power"]="Ενέργεια";
$lang_str["tag:power=generator"]="Γενήτρια Ρεύματος";
$lang_str["tag:power=line"]="Γραμμή Ρεύματος";
#$lang_str["tag:power=minor_line"]="Minor Power Line";
$lang_str["tag:power=tower"]="Πυλώνας Ρεύματος";
$lang_str["tag:power=pole"]="Κολόνα Ρεύματος";
$lang_str["tag:power=station"]="Σταθμός ρεύματος";
$lang_str["tag:power=sub_station"]="Υποσταθμός ρεύματος";

// power_source
$lang_str["tag:power_source"]="Πηγή Ισχύος";
$lang_str["tag:power_source=biofuel"]="Βιοκαύσιμο";
$lang_str["tag:power_source=oil"]="Πετρέλαιο";
$lang_str["tag:power_source=coal"]="Κάρβουνο";
$lang_str["tag:power_source=gas"]="Αέριο";
$lang_str["tag:power_source=waste"]="Απορρίμματα";
$lang_str["tag:power_source=hydro"]="Νερό";
$lang_str["tag:power_source=tidal"]="Παλίρροια";
$lang_str["tag:power_source=wave"]="Κύματα";
$lang_str["tag:power_source=geothermal"]="Γεωθερμία";
$lang_str["tag:power_source=nuclear"]="Πυρηνικά";
$lang_str["tag:power_source=fusion"]="Σύντηξη";
$lang_str["tag:power_source=wind"]="Αέρας";
$lang_str["tag:power_source=photovoltaic"]="Φωτοβολταϊκά";
$lang_str["tag:power_source=solar-thermal"]="Ηλιοθερμία";

// railway
$lang_str["tag:railway"]="Σιδηρόδρομος";
$lang_str["tag:railway=rail"]=array("Τροχιά Τρένου", "Τροχιές Τρένου");
$lang_str["tag:railway=tram"]=array("Τροχιά Τραμ", "Τροχιές Τραμ");
$lang_str["tag:railway=platform"]=array("Πλατφόρμα", "Πλατφόρμες");

// real_ale
#$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="Θρησκεία";
$lang_str["tag:religion=christian"]="Χριστιανισμός";
$lang_str["tag:religion=buddhist"]="Βουδισμός";
$lang_str["tag:religion=hindu"]="Ινδουισμός";
$lang_str["tag:religion=jewish"]="Ιουδαϊσμός";
$lang_str["tag:religion=muslim"]="Μουσουλμανισμός";
$lang_str["tag:religion=multifaith"]="Πολυθεϊσμός";

// denomination
$lang_str["tag:denomination"]="Δόγμα";

// route
$lang_str["tag:route"]="Δρομολόγιο";
$lang_str["tag:route=train"]="Τρένο";
$lang_str["tag:route=railway"]="Σιδηρόδρομος";
$lang_str["tag:route=rail"]="Σιδηρόδρομος";
#$lang_str["tag:route=light_rail"]="Light Rail";
$lang_str["tag:route=subway"]="Υπόγειος";
$lang_str["tag:route=tram"]="Τραμ";
$lang_str["tag:route=tram_bus"]="Τραμ και λεωφορείο";
$lang_str["tag:route=trolley"]="Τρόλεϊ";
$lang_str["tag:route=trolleybus"]="Τρόλεϊ";
$lang_str["tag:route=bus"]="Λεωφορείο";
#$lang_str["tag:route=minibus"]="Minibus";
$lang_str["tag:route=ferry"]="Πορθμείο";
$lang_str["tag:route=road"]="Δρόμος";
$lang_str["tag:route=bicycle"]="Ποδήλατο";
$lang_str["tag:route=hiking"]="Πεζοπορία";
$lang_str["tag:route=mtb"]="Ποδήλατο Βουνού";

// shop
$lang_str["tag:shop"]="Κατάστημα";

// sport
$lang_str["tag:sport"]="Άθλητισμός";
#$lang_str["tag:sport=9pin"]="9pin Bowling";
#$lang_str["tag:sport=10pin"]="10pin Bowling";
$lang_str["tag:sport=archery"]="Τοξοβολία";
#$lang_str["tag:sport=athletics"]="Athletics";
$lang_str["tag:sport=australian_football"]="Αυστραλιανό Ποδόσφαιρο";
#$lang_str["tag:sport=baseball"]="Baseball";
$lang_str["tag:sport=basketball"]="Μπάσκετ";
#$lang_str["tag:sport=beachvolleyball"]="Beachvolleyball";
#$lang_str["tag:sport=boules"]="Boules";
#$lang_str["tag:sport=bowls"]="Bowls";
$lang_str["tag:sport=canoe"]="Κανό";
$lang_str["tag:sport=chess"]="Σκάκι";
$lang_str["tag:sport=climbing"]="Ορειβασία";
$lang_str["tag:sport=cricket"]="Κρίκετ";
#$lang_str["tag:sport=cricket_nets"]="Cricket Nets";
#$lang_str["tag:sport=croquet"]="Croquet";
#$lang_str["tag:sport=cycling"]="Cycling";
$lang_str["tag:sport=diving"]="Κατάδυση";
$lang_str["tag:sport=dog_racing"]="Κυνοδρομίες";
#$lang_str["tag:sport=equestrian"]="Equestrian";
$lang_str["tag:sport=football"]="Ποδόσφαιρο";
$lang_str["tag:sport=golf"]="Γκολφ";
$lang_str["tag:sport=gymnastics"]="Γυμναστική";
$lang_str["tag:sport=hockey"]="Χόκεϊ";
$lang_str["tag:sport=horse_racing"]="Ιππόδρομος";
#$lang_str["tag:sport=korfball"]="Korfball";
$lang_str["tag:sport=motor"]="Μηχανοκίνητα";
$lang_str["tag:sport=multi"]="Πολλαπλά";
#$lang_str["tag:sport=orienteering"]="Orienteering";
#$lang_str["tag:sport=paddle_tennis"]="Paddle Tennis";
#$lang_str["tag:sport=paragliding"]="Paragliding";
#$lang_str["tag:sport=pelota"]="Pelota";
#$lang_str["tag:sport=racquet"]="Racquet";
$lang_str["tag:sport=rowing"]="Κωπηλασία";
#$lang_str["tag:sport=rugby"]="Rugby";
$lang_str["tag:sport=shooting"]="Σκοποβολή";
#$lang_str["tag:sport=skating"]="Skating";
#$lang_str["tag:sport=skateboard"]="Skateboard";
#$lang_str["tag:sport=skiing"]="Skiing";
$lang_str["tag:sport=soccer"]="Ποδόσφαιρο";
$lang_str["tag:sport=swimming"]="Κολύμβηση";
$lang_str["tag:sport=table_tennis"]="Πινγκ Πονγκ";
$lang_str["tag:sport=team_handball"]="Χάντμπολ";
$lang_str["tag:sport=tennis"]="Τένις";
$lang_str["tag:sport=volleyball"]="Βόλεϊ";

// tracks
#$lang_str["tag:tracks"]="Tracks";
#$lang_str["tag:tracks=single"]="Single";
#$lang_str["tag:tracks=double"]="Double";
#$lang_str["tag:tracks=multiple"]="Multiple";

// vending
#$lang_str["tag:vending"]="Vending";

// voltage
$lang_str["tag:voltage"]="Τάση";

// wires
$lang_str["tag:wires"]="Καλώδια";

// website
$lang_str["tag:website"]="Ιστότοπος";

// cycleway
$lang_str["tag:cycleway"]="Ποδηλατόδρομος";

// tracktype
#$lang_str["tag:tracktype"]="Track type";

// waterway
$lang_str["tag:waterway"]="Υδατοδιαδρομή";

// aerialway
#$lang_str["tag:aerialway"]="Aerialway";

// public_transport
$lang_str["tag:public_transport"]="Μέσα Μαζικής Μεταφοράς";

// office
#$lang_str["tag:office"]="Office";

// craft
#$lang_str["tag:craft"]="Craft";

// emergency
#$lang_str["tag:emergency"]="Emergency";

// tourism
$lang_str["tag:tourism"]="Τουρισμός";

// historic
$lang_str["tag:historic"]="Ιστορικά";

// landuse
$lang_str["tag:landuse"]="Χρήση Γης";

// wood
#$lang_str["tag:wood"]="Type of wood";

// military
$lang_str["tag:military"]="Στρατιωτικό";

// natural
$lang_str["tag:natural"]="Φυσικό";

// geological
$lang_str["tag:geological"]="Γεωλογικό";

// boundary
$lang_str["tag:boundary"]="Όριο";

// abutters
#$lang_str["tag:abutters"]="Abutters";

// lit
$lang_str["tag:lit"]="Φωτισμός οδού";

// area
$lang_str["tag:area"]="Περιοχή";

// crossing
$lang_str["tag:crossing"]="Διασταύρωση";

// mountain_pass
#$lang_str["tag:mountain_pass"]="Mountain Pass";

// cutting
#$lang_str["tag:cutting"]="Cutting";

// embankment
$lang_str["tag:embankment"]="Ανάχωμα";

// lanes
$lang_str["tag:lanes"]="Λωρίδες";

// layer
$lang_str["tag:layer"]="Επίπεδο";

// surface
$lang_str["tag:surface"]="Επιφάνεια";

// smoothness
$lang_str["tag:smoothness"]="Ομαλότητα";

// ele
$lang_str["tag:ele"]="Υψόμετρο";

// width
$lang_str["tag:width"]="Πλάτος";

// est_width
$lang_str["tag:est_width"]="Αναμενόμενο πλάτος";

// incline
$lang_str["tag:incline"]="Κλίση";

// start_date
$lang_str["tag:start_date"]="Ημερ/νία κατασκευής";

// end_date
$lang_str["tag:end_date"]="Ημερ/νία αφαίρεσης";

// disused
$lang_str["tag:disused"]="Εκτός χρήσης";

// wheelchair
$lang_str["tag:wheelchair"]="Αναπηρική πολυθρόνα";
$lang_str["tag:wheelchair=limited"]="Περιορισμένο";

// tactile_paving
#$lang_str["tag:tactile_paving"]="Tactile paving";

// narrow
$lang_str["tag:narrow"]="Στενό";

// covered
$lang_str["tag:covered"]="Επενδεδυμένο";

// ford
$lang_str["tag:ford"]="Κοιτόστρωση";

// access
$lang_str["tag:access"]="Γενικοί κανόνες πρόσβασης";

// vehicle
$lang_str["tag:vehicle"]="Κανόνες πρόσβασης οχημάτων";

// bicycle
$lang_str["tag:bicycle"]="Κανόνες πρόσβασης ποδηλάτων";

// foot
$lang_str["tag:foot"]="Κανόνες πρόσβασης πεζών";

// goods
#$lang_str["tag:goods"]="LCV access permission";

// hgv
#$lang_str["tag:hgv"]="HGV access permission";

// horse
#$lang_str["tag:horse"]="Horse riders access permission";

// motorcycle
$lang_str["tag:motorcycle"]="Κανόνες πρόσβασης μοτοσυκλετών";

// motorcar
#$lang_str["tag:motorcar"]="Motorcar access permission";

// psv
#$lang_str["tag:psv"]="PSV access permission";

// oneway
$lang_str["tag:oneway"]="Μονόδρομος";

// noexit
$lang_str["tag:noexit"]="Αδιέξοδο";

// maxweight
$lang_str["tag:maxweight"]="Μεγ. βάρος";

// maxheight
$lang_str["tag:maxheight"]="Μεγ. ύψος";

// maxlength
$lang_str["tag:maxlength"]="Μεγ. μήκος";

// maxspeed
$lang_str["tag:maxspeed"]="Μεγ. ταχύτητα";

// minspeed
$lang_str["tag:minspeed"]="Ελαχ. ταχύτητα";

// traffic_sign
#$lang_str["tag:traffic_sign"]="Traffic sign";

// toll
$lang_str["tag:toll"]="Διόδια";

// charge
$lang_str["tag:charge"]="Φόρτιση";

// source
$lang_str["tag:source"]="Πηγή";

// phone
$lang_str["tag:phone"]="Αριθμ. τηλεφώνου";

// fax
$lang_str["tag:fax"]="Αριθμ. φαξ";

// email
$lang_str["tag:email"]="E-mail";

// wikipedia
#$lang_str["tag:wikipedia"]="Wikipedia";

// created_by
$lang_str["tag:created_by"]="Δημιουργήθηκε από";

// construction
#$lang_str["tag:construction"]="Construction";

// proposed
#$lang_str["tag:proposed"]="Proposed";
