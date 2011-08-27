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
$lang_str["general_info"]="General Information";
$lang_str["yes"]="はい";
$lang_str["no"]="いいえ";
#$lang_str["ok"]="Ok";
#$lang_str["save"]=array("Save");
#$lang_str["cancel"]=array("Cancel");
#$lang_str["longitude"]=array("Longitude", "Longitudes");
#$lang_str["latitude"]=array("Latitude", "Latitudes");
$lang_str["noname"]="(無名)";
$lang_str["info_back"]="概要に戻る";
$lang_str["info_zoom"]="ズーム";
#$lang_str["nothing_found"]=array("nothing found");
$lang_str["loading"]="読み込み中";
#$lang_str["more"]="more";
#$lang_str["unnamed"]="unnamed";

// Headings
$lang_str["head:general_info"]="全般的な情報";
$lang_str["head:stops"]="Stops";
$lang_str["head:routes"]="ルート";
$lang_str["head:members"]="メンバー";
$lang_str["head:address"]="住所";
$lang_str["head:internal"]="OSM 内部";
$lang_str["head:services"]="サービス";
$lang_str["head:culture"]="文化";
$lang_str["head:graves"]="重要な墓地";
$lang_str["head:routing"]="ルーティング";
$lang_str["head:search"]="検索";
$lang_str["head:actions"]="操作";
#$lang_str["head:location"]="Location";
#$lang_str["head:tags"]=array("Tag", "Tags");
#$lang_str["head:whats_here"]="What's here?";

$lang_str["action_browse"]="OSM で見る";
$lang_str["action_edit"]="OSM で編集";

#$lang_str["geo_click_pos"]=array("Click on your position on the map");
$lang_str["geo_set_pos"]="自分の位置をセット";
$lang_str["geo_change_pos"]="自分の位置を変更";

$lang_str["routing_type_car"]="車";
$lang_str["routing_type_car_shortest"]="車 (最短)";
$lang_str["routing_type_bicycle"]="自転車";
$lang_str["routing_type_foot"]="徒歩";
$lang_str["routing_type"]="経路種別";
$lang_str["routing_distance"]="距離";
$lang_str["routing_time"]="時間";
$lang_str["routing_disclaimer"]="ルーティング: (c) by <a href='http://www.cloudmade.com'>Cloudmade</a>";

$lang_str["list_info"]="カテゴリを選択してマップの内容をブラウズするかオブジェクトをクリックしてマップの詳細を見ます";
$lang_str["list_leisure_sport_tourism"]="レジャー、スポート＆観光";

// Mapkey
$lang_str["map_key:head"]="マップキー";
$lang_str["map_key:zoom"]="ズームレベル";

$lang_str["grave_is_on"]="Grave is on";

$lang_str["main:options"]="オプション設定";
$lang_str["main:about"]="About";
$lang_str["main:donate"]="寄付";
$lang_str["main:licence"]="マップデータ: <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">cc-by-sa</a> <a href=\"http://www.openstreetmap.org\">OpenStreetMap</a> contributors | OSB: <a href=\"http://wiki.openstreetmap.org/wiki/User:Skunk\">Stephan Plepelits</a> and <a href=\"http://wiki.openstreetmap.org/wiki/OpenStreetBrowser#People_involved\">投稿者</a>";
$lang_str["main:permalink"]="パーマリンク";

$lang_str["help:no_object"]="<div class='obj_actions'><a class='zoom' href='#'></a></div><h1>オブジェクトがみつかりません</h1>ID \"%s\" のオブジェクトが見つかりません。次のいずれかの理由でしょう:<ul><li>IDが間違っている。</li><li>オブジェクトがまだサードパーティサイトに認識されておらず、OpenStreetBrowserでは(まだ)利用できない。</li><li>オブジェクトはサポート外のエリアにある。</li><li>リンクが古く、オブジェクトはOpenStreetMapから削除されている。</li></ul>";

$lang_str["options:autozoom"]="オートズームの挙動";
$lang_str["help:autozoom"]="オブジェクトを選択すると、閲覧ポートはそのオブジェクトにパンし、ズームレベルも切り替えられます。このオプションで異なるモード間を選択できます。";
$lang_str["options:autozoom:pan"]="現在のオブジェクトにパン(精度優先)";
$lang_str["options:autozoom:move"]="現在のオブジェクトに移動(速度優先)";
$lang_str["options:autozoom:stay"]="自動的に視点の変更をしない";

$lang_str["options:language_support"]="言語サポート";
$lang_str["help:language_support"]="このオプションであなたの言語設定を選択できます。最初のオプションはユーザインタフェースの言語を変更します。２番目のオプションはデータの言語を切り替えます。多くの地物のデータはいくつかの言語に翻訳されています。翻訳が利用できなかったり \"Local language\" が選択されている場合は、オブジェクトのメイン言語が表示されます。";
$lang_str["options:ui_lang"]="インターフェース表示";
$lang_str["options:data_lang"]="データ表示";
$lang_str["lang:"]="ブラウザの設定言語";

$lang_str["overlay:data"]="データ";
$lang_str["overlay:draggable"]="マーカー";

#$lang_str["user:no_auth"]="Username or password wrong!";
#$lang_str["user:login_text"]="Log in to OpenStreetBrowser:";
#$lang_str["user:create_user"]="Create a new user:";
#$lang_str["user:username"]="Username";
#$lang_str["user:email"]="E-mail address";
#$lang_str["user:password"]="Password";
#$lang_str["user:password_verify"]="Verify password";
#$lang_str["user:old_password"]="Old password";
#$lang_str["user:no_username"]="Please supply a username!";
#$lang_str["user:password_no_match"]="Passwords do not match!";
#$lang_str["user:full_name"]="Full name";
#$lang_str["user:user_exists"]="Username already exists";
#$lang_str["user:login"]="Login";
#$lang_str["user:logged_in_as"]="Logged in as ";
#$lang_str["user:logout"]="Logout";

#$lang_str["error"]="An error occured: ";
#$lang_str["error:not_logged_in"]="you are not logged in";

#$lang_str["more_categories"]="More categories";
#$lang_str["category:status"]="Status";
#$lang_str["category:data_status"]="Status";
#$lang_str["category:old_version"]="A new version of this category is being prepared.";
#$lang_str["category:not_compiled"]="New category is being prepared.";

#$lang_str["category_rule_tag:match"]="Match";
#$lang_str["category_rule_tag:description"]="Description";
#$lang_str["category_chooser:choose"]="Choose a category";
#$lang_str["category_chooser:new"]="New category";

#$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:mapnik"]="Standard (Mapnik)";
#$lang_str["basemap:osmarender"]="Standard (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";

// please finish this list, see list.php for full list of languages
#$lang_str["lang:de"]="German";
#$lang_str["lang:bg"]="Bulgarian";
#$lang_str["lang:cs"]="Czech";
#$lang_str["lang:en"]="English";
#$lang_str["lang:es"]="Spanish";
#$lang_str["lang:it"]="Italian";
#$lang_str["lang:fr"]="French";
#$lang_str["lang:uk"]="Ukrainian";
#$lang_str["lang:ru"]="Russian";
#$lang_str["lang:ja"]="Japanese";
#$lang_str["lang:hu"]="Hungarian";
#$lang_str["lang:ast"]="Asturian";
// The following $lang_str were not defined in the English language file and might be deprecated or wrong:
$lang_str["search_field"]="検索...";
$lang_str["search_tip"]="例 'London', 'Cromwell Road', 'post box near Hyde Park',...";
$lang_str["search_clear"]="検索フィールドをクリア";
$lang_str["result_no"]="見つかりません";
$lang_str["search_process"]="検索中";
$lang_str["search_more"]="もっと表示";
$lang_str["search_results"]="検索結果";
$lang_str["search_nominatim"]="検索機能提供元";
$lang_str["list_"]="";
$lang_str["place=continent"]="大陸";
$lang_str["place=country"]="国";
$lang_str["place=state"]="州";
$lang_str["place=region"]="地方";
$lang_str["place=county"]="郡";
$lang_str["place=city_large"]="100万人以上居住する市";
$lang_str["place=city_medium"]="20万人以上居住する市";
$lang_str["place=city"]="市";
$lang_str["place=town_large"]="30万人以上居住する市";
$lang_str["place=town"]="町";
$lang_str["place=suburb"]="郊外";
$lang_str["place=village"]="村";
$lang_str["place=hamlet"]="小村";
$lang_str["place=locality"]="地域の通称";
$lang_str["place=island"]="島";
$lang_str["sub_type=t3|type="]="";
$lang_str["landuse=park"]="公園";
$lang_str["landuse=education"]="教育施設エリア";
$lang_str["landuse=tourism"]="観光施設エリア";
$lang_str["landuse=garden"]="農場、農園、庭園";
$lang_str["landuse=industrial"]="工業および鉄道エリア";
$lang_str["landuse=sport"]="スポーツ施設エリア";
$lang_str["landuse=cemetery"]="霊園";
$lang_str["landuse=residental"]="住居エリア";
$lang_str["landuse=nature_reserve"]="自然保護区域";
$lang_str["landuse=historic"]="歴史的価値のあるエリア";
$lang_str["landuse=emergency"]="緊急施設のエリア";
$lang_str["landuse=health"]="健康施設エリア";
$lang_str["landuse=public"]="公共サービス向けエリア";
$lang_str["landuse=water"]="水域";
$lang_str["landuse=natural|sub_type=t0"]="森林";
$lang_str["landuse=natural|sub_type=t1"]="湿地";
$lang_str["landuse=natural|sub_type=t2"]="氷河";
$lang_str["landuse=natural|sub_type=t3"]="がれ、ヒース";
$lang_str["landuse=natural|sub_type=t4"]="泥地";
$lang_str["landuse=natural|sub_type=t5"]="砂浜";
$lang_str["building=default"]="建物";
$lang_str["building=worship"]="宗教用建物";
$lang_str["building=road_amenities"]="輸送施設 (駅、ターミナル、料金所、 ...)";
$lang_str["building=nature_building"]="自然の建物(例. バリア)";
$lang_str["building=industrial"]="工業用建物";
$lang_str["building=education"]="教育用建物";
$lang_str["building=shop"]="店舗";
$lang_str["building=public"]="公共用建物";
$lang_str["building=military"]="軍事用建物";
$lang_str["building=historic"]="歴史的建物";
$lang_str["building=emergency"]="緊急施設の建物";
$lang_str["building=health"]="健康サービス用建物";
$lang_str["building=communication"]="コミュニケーション用建物";
$lang_str["building=residential"]="住居用建物";
$lang_str["building=culture"]="文化的建物";
$lang_str["building=tourism"]="観光用建物";
$lang_str["building=sport"]="スポーツ用建物";
$lang_str["housenumber"]="家番号";
$lang_str["tag:place=hamlet"]="小村(未使用)";
$lang_str["cuisine_regional"]="地域料理";
