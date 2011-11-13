<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
$lang_str["base_language"]="el"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Ελληνικά"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Γενικές Πληροφορίες";
$lang_str["yes"]="ναί";
$lang_str["no"]="όχι";
$lang_str["ok"]="Εντάξει";
$lang_str["save"]="Αποθήκευση";
$lang_str["saved"]="Αποθηκεύτηκε"; // for dialog boxes confirming saving
$lang_str["cancel"]="Ακύρωση";
$lang_str["show"]="Εμφάνιση";
$lang_str["edit"]="Επεξεργασία";
$lang_str["delete"]="Διαγραφή";
$lang_str["history"]="Ιστορικό";
$lang_str["choose"]="Επιλογή";
$lang_str["help"]="Βοήθεια";
$lang_str["longitude"]=array("Γεωγ. Μήκος", "Γεωγ. Μήκη");
$lang_str["latitude"]=array("Γεωγ. Πλάτος", "Γεωγ. Πλάτη");
$lang_str["noname"]="(χωρίς όνομα)";
#$lang_str["info_back"]="back to overview";
$lang_str["info_zoom"]="Εστίαση";
$lang_str["nothing_found"]="δεν βρέθηκε τίποτα";
$lang_str["loading"]="Φόρτωση...";
$lang_str["more"]="περισσότερα";
$lang_str["source"]="Πηγή";
$lang_str["unnamed"]="ανώνυμο";
$lang_str["zoom"]="Επίπεδο Εστίασης";
$lang_str["no_message"]=array("χωρίς μήνυμα", "χωρίς μηνύματα");

// Headings
$lang_str["head:general_info"]="Γενικές Πληροφορίες";
$lang_str["head:stops"]="Στάσεις";
$lang_str["head:routes"]="Δρομολόγια";
$lang_str["head:members"]="Μέλη";
$lang_str["head:address"]="Διεύθυνση";
#$lang_str["head:internal"]="OSM Internal";
$lang_str["head:services"]="Υπηρεσίες";
$lang_str["head:culture"]="Πολιτισμός";
#$lang_str["head:graves"]="Important Graves";
$lang_str["head:routing"]="Δρομολόγηση";
$lang_str["head:search"]="Αναζήτηση";
$lang_str["head:actions"]=array("Ενέργεια", "Ενέργειες");
$lang_str["head:location"]="Τοποθεσία";
$lang_str["head:tags"]=array("Ετικέτα", "Ετικέτες");
$lang_str["head:whats_here"]=array("Τι είναι εδώ", "");

$lang_str["action_browse"]="Εμφάνιση στο OSM";
$lang_str["action_edit"]="Επεξεργασία στο OSM";

$lang_str["geo_click_pos"]="Κλικ στη θέση σου στο χάρτη";
$lang_str["geo_set_pos"]="Καθορισμός της θέσης μου";
$lang_str["geo_change_pos"]="Αλλαγή της θέσης μου";

$lang_str["routing_type_car"]="Αυτοκίνητο";
$lang_str["routing_type_car_shortest"]="Αυτοκίνητο (πιο σύντομη)";
$lang_str["routing_type_bicycle"]="Ποδήλατο";
$lang_str["routing_type_foot"]="Πεζός";
$lang_str["routing_type"]="Τύπος δρομολογίου";
$lang_str["routing_distance"]="Απόσταση";
$lang_str["routing_time"]="Χρόνος";
$lang_str["routing_disclaimer"]="Δρομολόγηση: (c) από <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Επέλεξε μια κατηγορία για εμφάνιση περιεχομένων του χάρτη ή κλικ σε ένα αντικείμενο στο χάρτη για λεπτομέρειες";
#$lang_str["list_leisure_sport_tourism"]="Leisure, Sport and Tourism";

// Mapkey

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:options"]="Επιλογές";
$lang_str["main:about"]="Περί";
#$lang_str["main:donate"]="Donate";
$lang_str["main:licence"]="Δεδομένα Χάρτη: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> συνεργάτες | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> και <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">συνεργάτες</a>";
#$lang_str["main:permalink"]="Permalink";

#$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Object not found</h1>No object with the ID \"%s\" could be found. This can be due to one (or more) of the following reasons:<ul><li>The ID is wrong.</li><li>The object has been identified by a third party site and is not (yet) available in the OpenStreetBrowser.</li><li>The object is outside of the supported area.</li><li>The link you were following was old and the object has been deleted from OpenStreetMap.</li></ul>";

#$lang_str["options:autozoom"]="Autozoom behaviour";
#$lang_str["help:autozoom"]="When choosing an object, the view port pans to that object, the zoom level might also get changed. With this option you can choose between different modes.";
#$lang_str["options:autozoom:pan"]="Pan to current object (nicer)";
#$lang_str["options:autozoom:move"]="Move to current object (faster)";
#$lang_str["options:autozoom:stay"]="Never change viewport automatically";

#$lang_str["options:language_support"]="Language Support";
#$lang_str["help:language_support"]="You can choose your prefered languages with this options. The first option changes the language of the user interface. The second option changes the data language. Date of many geographic objects has been translated to several languages. If no translation is available or \"Local language\" was chosen, the main language of the object is displayed.";
$lang_str["options:ui_lang"]="Γλώσσα διεπαφής";
$lang_str["options:data_lang"]="Γλωσσα δεδομένων";
$lang_str["lang:"]="Τοπική γλώσσα";
$lang_str["lang:auto"]="Ίδια με τη γλώσσα διεπαφής";

$lang_str["overlay:data"]="Δεδομένα";
#$lang_str["overlay:draggable"]="Markers";

$lang_str["user:no_auth"]="Όνομα χρήστη και κωδικός λάθος!";
$lang_str["user:login_text"]="Σύνδεση στο OpenStreetBrowser:";
$lang_str["user:create_user"]="Δημιουργία νέου χρήστη:";
$lang_str["user:username"]="Όνομα χρήστη";
$lang_str["user:email"]="Διεύθυνση e-mail";
$lang_str["user:password"]="Κωδικός";
$lang_str["user:password_verify"]="Επαλήθευση κωδικού";
$lang_str["user:old_password"]="Παλιός κωδικός";
$lang_str["user:no_username"]="Παρακαλώ δώστε ένα όνομα χρήστη!";
$lang_str["user:password_no_match"]="Οι κωδικοί δεν ταιριάζουν!";
$lang_str["user:full_name"]="Πλήρες όνομα";
$lang_str["user:user_exists"]="Το όνομα χρήστη υπάρχει ήδη";
$lang_str["user:login"]="Σύνδεση";
$lang_str["user:logged_in_as"]="Συνδεδεμένος ως";
$lang_str["user:logout"]="Αποσύνδεση";

$lang_str["attention"]="Προσοχή:";
$lang_str["error"]="Προέκυψε ένα σφάλμα:";
$lang_str["error:not_logged_in"]="δεν έχεις συνδεθεί";

$lang_str["category"]=array("Κατηγορία", "Κατηγορίες");
$lang_str["more_categories"]="Περισσότερες κατηγορίες";
$lang_str["category:status"]="Κατάσταση";
$lang_str["category:data_status"]="Κατάσταση";
#$lang_str["category:old_version"]="A new version of this category is being prepared.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

$lang_str["category:new_rule"]="Νέος Ρόλος";
#$lang_str["category_rule_tag:match"]="Match";
#$lang_str["category_rule_tag:description"]="Description";
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";
#$lang_str["category:sub_category"]=array("Sub-category", "Sub-categories");

#$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:mapnik"]="Standard (Mapnik)";
#$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";
