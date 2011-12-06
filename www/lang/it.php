<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Italiano"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Informazioni generali";
$lang_str["yes"]="si";
$lang_str["no"]="no";
$lang_str["ok"]="Ok";
$lang_str["save"]="Salva";
$lang_str["saved"]="Salvato"; // for dialog boxes confirming saving
$lang_str["cancel"]="Annulla";
$lang_str["show"]="Mostra";
$lang_str["edit"]="Modifica";
$lang_str["delete"]="Elimina";
$lang_str["history"]="Storia";
$lang_str["choose"]="Scegli";
$lang_str["help"]="Aiuto";
$lang_str["longitude"]=array("Longitudine", "Longitudini");
$lang_str["latitude"]=array("Latitudine", "Latitudini");
$lang_str["noname"]="(nessun nome)";
$lang_str["info_back"]="torna alla vista d'insieme";
$lang_str["info_zoom"]="zoom";
$lang_str["nothing_found"]="non trovato";
$lang_str["list:zoom_for_obs"]="Zuma in avanti per gli oggetti meno importanti";
$lang_str["loading"]="In caricamento...";
$lang_str["more"]="altri";
$lang_str["source"]="Provenienza";
$lang_str["unnamed"]="privo di nome";
$lang_str["zoom"]="Livello di zoom";
$lang_str["no_message"]="nessun messaggio";

// Headings
$lang_str["head:general_info"]="Informazioni generali";
$lang_str["head:stops"]="Stop";
$lang_str["head:routes"]="Itinerari";
$lang_str["head:members"]="Membri";
$lang_str["head:address"]="Indirizzo";
$lang_str["head:internal"]="tag OSM interni";
$lang_str["head:services"]="Servizi";
$lang_str["head:culture"]="Cultura";
$lang_str["head:graves"]="Tombe importanti";
$lang_str["head:routing"]="Pianificazione del percorso";
$lang_str["head:search"]="Cerca";
$lang_str["head:actions"]=array("Azione", "Azioni");
$lang_str["head:location"]="Posizione";
$lang_str["head:tags"]=array("Etichetta", "Etichette");
$lang_str["head:whats_here"]="Cosa c'è qui?";

$lang_str["action_browse"]="visualizza in OSM";
$lang_str["action_edit"]="modifica in OSM";

$lang_str["geo_click_pos"]="Clicca sulla tua posizione sulla mappa";
$lang_str["geo_set_pos"]="Fissa la mia posizione";
$lang_str["geo_change_pos"]="Modifica la mia posizione";

$lang_str["routing_type_car"]="Automobile";
$lang_str["routing_type_car_shortest"]="Automobile (il più breve)";
$lang_str["routing_type_bicycle"]="Bicicletta";
$lang_str["routing_type_foot"]="A piedi";
$lang_str["routing_type"]="Tipo di itinerario";
$lang_str["routing_distance"]="Distanza";
$lang_str["routing_time"]="Tempi";

$lang_str["list_info"]="Scegli una categoria per sfogliare i contenuti della mappa o clicca su un oggetto sulla mappa per visualizzarne i dettagli";
$lang_str["list_leisure_sport_tourism"]="Tempo libero, sport e turismo";

// Mapkey

$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:options"]="Opzioni";
$lang_str["main:about"]="Informazioni";
$lang_str["main:donate"]="Donazioni";
$lang_str["main:licence"]="Dati cartografici: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> e <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">collaboratori</a>";
$lang_str["main:permalink"]="Permalink";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Oggetto non trovato</h1>Non è stato trovato alcun oggetto con ID \"%s\". Ciò può essere dovuto ad uno (o più) dei seguenti motivi:<ul><li>L'ID è sbagliato.</li><li>L'oggetto è stato identificato tramite strumenti di terze parti, ma non è ancora disponibile in OpenStreetBrowser.</li><li>L'oggetto è situato all'esterno dell'area supportata.</li><li>Il link che hai seguito è obsoleto e nel frattempo l'oggetto è stato eliminato da OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Zoom automatico";
$lang_str["help:autozoom"]="Quando un oggetto viene selezionato, l'inquadratura viene spostata sull'oggetto, e anche il livello di zoom potrebbe venire adattato. Con questa opzione è possibile scegliere tra diverse modalità.";
$lang_str["options:autozoom:pan"]="Scorri al punto selezionato (più gradevole)";
$lang_str["options:autozoom:move"]="Salta al punto selezionato (più rapido)";
$lang_str["options:autozoom:stay"]="Non cambiare automaticamente l'inquadratura";

$lang_str["options:language_support"]="Selezione della lingua";
$lang_str["help:language_support"]="Con queste opzioni puoi scegliere le tue lingue preferite. La prima opzione cambia la lingua dell'interfaccia. La seconda opzione cambia la lingua dei contenuti. I dati di molti oggetti geografici sono stati tradotti in diverse lingue. Se non è disponibile alcuna traduzione, o se e stato scelto \"Lingua locale\", viene visualizzata la  lingua principale dell'oggetto. ";
$lang_str["options:ui_lang"]="Lingua dell'interfaccia";
$lang_str["options:data_lang"]="Lingua dei dati";
$lang_str["lang:"]="Lingua del tuo browser";
$lang_str["lang:auto"]="La stessa lingua dell'interfaccia";

$lang_str["overlay:data"]="Dati";
$lang_str["overlay:draggable"]="Contrassegni";

$lang_str["user:no_auth"]="Nome utente o password errati";
$lang_str["user:login_text"]="Entra in OpenStreetBrowser";
$lang_str["user:create_user"]="Crea un nuovo utente:";
$lang_str["user:username"]="Username";
$lang_str["user:email"]="Indirizzo E-mail";
$lang_str["user:password"]="Password";
$lang_str["user:password_verify"]="Conferma la password";
$lang_str["user:old_password"]="Vecchia password";
$lang_str["user:no_username"]="Si prega di inserire uno username";
$lang_str["user:password_no_match"]="Le password non sono identiche";
$lang_str["user:full_name"]="Nome e cognome";
$lang_str["user:user_exists"]="Lo username esiste già";
$lang_str["user:login"]="Entra";
$lang_str["user:logged_in_as"]="Entra come";
$lang_str["user:logout"]="Esci";

$lang_str["attention"]="Attenzione:";
$lang_str["error"]="Si è verificato un errore:";
$lang_str["error:not_logged_in"]="Non sei registrato";

$lang_str["category"]=array("Categoria", "Categorie");
$lang_str["more_categories"]="Altre categorie";
$lang_str["category:status"]="Stato";
$lang_str["category:data_status"]="Stato";
$lang_str["category:old_version"]="Una nuova versione di questa categoria è in preparazione";
$lang_str["category:not_compiled"]="Nuova categoria in preparazione";

$lang_str["category:new_rule"]="Nuova regola";
$lang_str["category_rule_tag:match"]="Corrispondenza";
$lang_str["category_rule_tag:description"]="Descrizione";
$lang_str["category_chooser:choose"]="Scegli una categoria";
$lang_str["category_chooser:new"]="Crea una nuova categoria";
$lang_str["category:sub_category"]=array("Sottocategoria", "Sottocategorie");

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standard (Mapnik)";
$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";
