<?
// In this editor you can translate all strings. In the third column you can compare the strings to another language (set it in the select box on the bottom of the window). Please note that changes will not appear right away, they need to be imported by a developer.
// Every language string can have a singular and plural variant by separating them by ";", e.g. "Restaurant;Restaurants". The first string is the singular form, the second the plural form.  Optionally you can define the Gender (F, M, N) for the word by prepending one of those characters, e.g. "N;Büro;Büros" (German for "office").
// When translating a language variant (e.g. 'British English', code 'en-gb') please translate only strings which are different from the base language.
$lang_str["base_language"]="en"; // Set the language code for a base language which should be used if a string has not been translated to this language. Usually you want to set it to 'en' (English), but for a language variants and dialects set it to the main language. Some world regions might also consider another base language as more appropriate.

$lang_str["lang:current"]="Polski"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="Ogólne informacje";
$lang_str["yes"]="tak";
$lang_str["no"]="nie";
$lang_str["ok"]="OK";
$lang_str["save"]="Zapisz";
$lang_str["saved"]="Zapisane"; // for dialog boxes confirming saving
$lang_str["cancel"]="Anuluj";
$lang_str["show"]="Pokaż";
$lang_str["edit"]="Edytuj";
$lang_str["delete"]="Usuń";
$lang_str["history"]="Historia";
$lang_str["choose"]="Wybierz";
$lang_str["help"]="Pomoc";
$lang_str["longitude"]=array("Długość geograficzna", "Długości geograficzne");
$lang_str["latitude"]=array("Szerokość geograficzna", "Szerokości geograficzne");
$lang_str["noname"]="(brak nazwy)";
$lang_str["info_back"]="powrót do omówienia";
$lang_str["info_zoom"]="powiększenie";
$lang_str["nothing_found"]="nic nie znaleziono";
$lang_str["list:zoom_for_obs"]="Powiększ by zobaczyć mniej ważne obiekty";
$lang_str["loading"]="Wczytuję...";
$lang_str["more"]="więcej";
$lang_str["source"]="Źródło";
$lang_str["unnamed"]="nienazwane";
$lang_str["zoom"]="Poziom powiększenia";
$lang_str["no_message"]="brak wiadomości";

// Headings
$lang_str["head:general_info"]="Ogólne Informacje";
$lang_str["head:stops"]="Przystanki";
$lang_str["head:routes"]="Drogi";
$lang_str["head:members"]="Członkowie";
$lang_str["head:address"]="Adresy";
$lang_str["head:internal"]="Wewnętrzne OSM";
$lang_str["head:services"]="Serwisy";
$lang_str["head:culture"]="Kultura";
$lang_str["head:graves"]="Ważne Groby";
$lang_str["head:routing"]="Wyznaczanie trasa";
$lang_str["head:search"]="Szukanie";
$lang_str["head:actions"]=array("Akcja", "Akcje");
$lang_str["head:location"]="Położenie";
$lang_str["head:tags"]=array("Tag", "Tagi");
$lang_str["head:whats_here"]="Co tu jest?";

$lang_str["action_browse"]="obejrzyj w OSM";
$lang_str["action_edit"]="edytuj w OSM";

$lang_str["geo_click_pos"]="Kliknij swoją pozycję na mapie";
$lang_str["geo_set_pos"]="Ustaw moją pozycję";
$lang_str["geo_change_pos"]="Zmień moją pozycję";

$lang_str["routing_type_car"]="Samochód";
$lang_str["routing_type_car_shortest"]="Samochód (Najkrótsza)";
$lang_str["routing_type_bicycle"]="Rower";
$lang_str["routing_type_foot"]="Pieszo";
$lang_str["routing_type"]="Typ trasy";
$lang_str["routing_distance"]="Odległość";
$lang_str["routing_time"]="Czas";
$lang_str["routing_disclaimer"]="Wyznaczanie trasy: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="Wybierz kategorię by przeglądać zawartość mapy lub kliknij obiekt na mapie by zobaczyć więcej informacji o nim";
$lang_str["list_leisure_sport_tourism"]="Rozrywka, Sport i Turystyka";

// Mapkey

#$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:options"]="Opcje";
$lang_str["main:about"]="O programie";
$lang_str["main:donate"]="Podaruj";
$lang_str["main:licence"]="Dane mapy: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> współpracownicy | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> i <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">współpracownicy OSM</a>";
$lang_str["main:permalink"]="Stały link";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>Obiekt nie został znaleziony</h1>Obiekt ID \"%s\" nie został znaleziony. Może wynikać z jednego (lub kilku) poniższych powodów:<ul><li>ID jest nieprawidłowe.</li><li>Obiekt został stworzony przez zewnętrzną witrynę i (jeszcze) nie jest dostępny w OpenStreetBrowser.</li><li>Obiekt znajduje się poza obszarem tej mapy.</li><li>Link, którego użyłeś, był stary i obiekt został już skasowany z OpenStreetMap.</li></ul>";

$lang_str["options:autozoom"]="Zachowanie funkcji automatycznego powiększania";
$lang_str["help:autozoom"]="Przy wyborze obiektu obszar mapy i powiększenie mogą zostać zmienione. Określ, jak ma się to odbywać.";
$lang_str["options:autozoom:pan"]="Przesuń się do wybranego obiektu (przyjemniejsze)";
$lang_str["options:autozoom:move"]="Pokaż od razu wybrany obiekt (szybsze)";
$lang_str["options:autozoom:stay"]="Nigdy nie zmieniaj automatycznie widoku mapy";

$lang_str["options:language_support"]="Obsługiwane języki";
$lang_str["help:language_support"]="Dzięki tej opcji możesz wybrać swoje preferowane języki. Pierwsza opcja zmienia język interfejsu użytkownika. Druga określa język opisu danych. Dane wielu obiektów geograficznych mogą być tłumaczone na wiele języków. Jeśli tłumaczenie nie jest dostępne lub został wybrany \"Język lokalny\", nazwa w głównym języku obiektu zostanie wyświetlona.";
$lang_str["options:ui_lang"]="Język interfejsu";
$lang_str["options:data_lang"]="Język danych";
$lang_str["lang:"]="Język lokalny";
$lang_str["lang:auto"]="Taki sam jak język interfejsu";

$lang_str["overlay:data"]="Dane";
$lang_str["overlay:draggable"]="Markery";

$lang_str["user:no_auth"]="Zła nazwa użytkownika lub hasło!";
$lang_str["user:login_text"]="Zaloguj się do OpenStreetBrowser:";
$lang_str["user:create_user"]="Utwórz nowego użytkownika:";
$lang_str["user:username"]="Nazwa użytkownika";
$lang_str["user:email"]="Adres email";
$lang_str["user:password"]="Hasło";
$lang_str["user:password_verify"]="Potwierdzenie hasła";
$lang_str["user:old_password"]="Stare hasło";
$lang_str["user:no_username"]="Proszę podać nazwę użytkownika!";
$lang_str["user:password_no_match"]="Hasła nie są takie same!";
$lang_str["user:full_name"]="Pełne imię i nazwisko";
$lang_str["user:user_exists"]="Nazwa użytkownika już istnieje";
$lang_str["user:login"]="Login";
$lang_str["user:logged_in_as"]="Zalogowany jako";
$lang_str["user:logout"]="Wyloguj";

$lang_str["attention"]="Uwaga:";
$lang_str["error"]="Wystąpił błąd:";
$lang_str["error:not_logged_in"]="nie jesteś zalogowany";

$lang_str["category"]=array("Kategoria", "Kategorie");
$lang_str["more_categories"]="Więcej kategorii";
$lang_str["category:status"]="Stan";
$lang_str["category:data_status"]="Stan";
$lang_str["category:old_version"]="Nowa wersja kategorii jest przygotowywana.";
$lang_str["category:not_compiled"]="Nowa kategoria jest przygotowywana.";

$lang_str["category:new_rule"]="Nowa reguła";
$lang_str["category_rule_tag:match"]="Dopasowanie";
$lang_str["category_rule_tag:description"]="Opis";
$lang_str["category_chooser:choose"]="Wybierz kategorię";
$lang_str["category_chooser:new"]="Nowa kategoria";
$lang_str["category:sub_category"]=array("Podkategoria", "Podkategorie");

$lang_str["basemap:osb"]="OpenStreetBrowser";
$lang_str["basemap:mapnik"]="Standardowa (Mapnik)";
$lang_str["basemap:osmarender"]="Standardowa (OsmaRender)";
$lang_str["basemap:cyclemap"]="CycleMap";
