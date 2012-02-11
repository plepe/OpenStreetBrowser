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
$lang_str["lang:current"]="日本語"; // The name of the current language in the native tongue (e.g. "Deutsch" for German)

// General
$lang_str["general_info"]="全般的なお知らせ";
$lang_str["yes"]="はい";
$lang_str["no"]="いいえ";
#$lang_str["ok"]="Ok";
#$lang_str["save"]="保存";
#$lang_str["cancel"]="キャンセル";
#$lang_str["longitude"]=array("経度", "軽度");
#$lang_str["latitude"]=array("緯度", "緯度");
$lang_str["noname"]="(無名)";
$lang_str["info_back"]="概要に戻る";
$lang_str["info_zoom"]="ズーム";
#$lang_str["nothing_found"]="見つかりません";
$lang_str["loading"]="読み込み中";
#$lang_str["more"]="もっと";
#$lang_str["unnamed"]="名前なし";
$lang_str["zoom"]="ズームレベル";

// Headings
$lang_str["head:general_info"]="全般的な情報";
$lang_str["head:stops"]="Stops";
$lang_str["head:routes"]="ルート";
$lang_str["head:members"]="メンバー";
$lang_str["head:address"]="住所";
$lang_str["head:internal"]="OSM 内部";
$lang_str["head:services"]="サービス";
$lang_str["head:culture"]="文化";
$lang_str["head:routing"]="ルーティング";
$lang_str["head:search"]="検索";
$lang_str["head:actions"]="操作";
#$lang_str["head:location"]="位置";
#$lang_str["head:tags"]=array("タグ", "タグ");
#$lang_str["head:whats_here"]="ここは何?";

$lang_str["action_browse"]="OSM で見る";
$lang_str["action_edit"]="OSM で編集";

#$lang_str["geo_click_pos"]="マップ上の自分の位置をクリック";
$lang_str["geo_set_pos"]="自分の位置をセット";
$lang_str["geo_change_pos"]="自分の位置を変更";

$lang_str["routing_type_car"]="車";
$lang_str["routing_type_car_shortest"]="車 (最短)";
$lang_str["routing_type_bicycle"]="自転車";
$lang_str["routing_type_foot"]="徒歩";
$lang_str["routing_type"]="経路種別";
$lang_str["routing_distance"]="距離";
$lang_str["routing_time"]="時間";

$lang_str["list_info"]="カテゴリを選択してマップの内容をブラウズするかオブジェクトをクリックしてマップの詳細を見ます";
$lang_str["list_leisure_sport_tourism"]="レジャー、スポート＆観光";

// Mapkey


$lang_str["main:options"]="オプション設定";
$lang_str["main:about"]="このサイトについて";
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

#$lang_str["user:no_auth"]="ユーザ名かパスワードが間違っています!";
#$lang_str["user:login_text"]="OpenStreetBrowserにログイン:";
#$lang_str["user:create_user"]="新しいユーザを作成:";
#$lang_str["user:username"]="ユーザ名";
#$lang_str["user:email"]="Eメールアドレス";
#$lang_str["user:password"]="パスワード";
#$lang_str["user:password_verify"]="パスワード確認";
#$lang_str["user:old_password"]="古いパスワード";
#$lang_str["user:no_username"]="ユーザ名を入力してください!";
#$lang_str["user:password_no_match"]="パスワードが一致しません!";
#$lang_str["user:full_name"]="フルネーム";
#$lang_str["user:user_exists"]="ユーザ名は既に存在します";
#$lang_str["user:login"]="ログイン";
#$lang_str["user:logged_in_as"]="ログイン中 ";
#$lang_str["user:logout"]="ログアウト";

#$lang_str["error"]="エラー発生: ";
#$lang_str["error:not_logged_in"]="ログインしていません";

#$lang_str["more_categories"]="これ以外のカテゴリ";
#$lang_str["category:status"]="ステータス";
#$lang_str["category:data_status"]="ステータス";
#$lang_str["category:old_version"]="このカテゴリの新しいバージョンが準備中です。";
#$lang_str["category:not_compiled"]="新しいカテゴリが準備中です。";

#$lang_str["category_rule_tag:match"]="一致";
#$lang_str["category_rule_tag:description"]="説明";
#$lang_str["category_chooser:choose"]="カテゴリを選択";
#$lang_str["category_chooser:new"]="新しいカテゴリ";

#$lang_str["basemap:osb"]="OpenStreetBrowser";
#$lang_str["basemap:mapnik"]="標準 (Mapnik)";
#$lang_str["basemap:osmarender"]="標準 (OsmaRender)";
#$lang_str["basemap:cyclemap"]="CycleMap";
