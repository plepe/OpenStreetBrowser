<?
//  All tags should have translations in www/lang/tags_XX.php, with
//  language strings like "tag:key" for the translation of the key and
//  "tag:key=value" for the translation of the value. E.g.
//  $lang_str["tag:amenity"]=array("Amenity", "Amenities");
//  $lang_str["tag:amenity=bar"]=array("Bar", "Bars");
//
//  Furthermore you can describe the tags with the array $tag_type. Every
//  entry is an array again to further specify its type, e.g.:
//  $tag_type["width"]=array("number", "m", "in");
//                ^             ^       ^    ^
//                |             |       |    \-- the preferred unit in this locale
//                |             |       \------- the default unit for this tag
//                |             \--------------- the type of the value
//                \----------------------------- tag
//
//  This defines, that the default value for the tag width is a number, with
//  its default unit m (for meter) and the preferred unit for this locale is
//  in (for inch).
//
//  The following types are valid:
//  * text          default (e.g. religion, name)
//  * number        a value, with default unit and preferred unit as defined
//                  by the second and third entry in this array (e.g. width,
//                  voltage)
//  * count         an integer value (e.g. population)
//  * date          a date
//  * link          an Internet URL
//
//  NOTE: the $tag_type can already be defined, but it's not used yet.
//  There might also be more tag types soon and a way to format the output
//  (e.g. "100.000 m" or "2010-12-24").

// accomodation
$lang_str["tag:accomodation"]="宿泊";

// address
$lang_str["tag:address"]="住所";

// addr:housenumber
$lang_str["tag:addr:housenumber"]="家番号";

// addr:interpolation
#$lang_str["tag:addr:interpolation"]="Interpolated housenumbers";

// aeroway
#$lang_str["tag:aeroway"]="Aeroway";
#$lang_str["tag:aeroway=runway"]="Runway";
#$lang_str["tag:aeroway=taxiway"]="Taxiway";

// admin_level
$lang_str["tag:admin_level=2"]="国境";
$lang_str["tag:admin_level=3"]="行政区分(未使用)";
$lang_str["tag:admin_level=4"]="道州境界";
$lang_str["tag:admin_level=5"]="コミュニティ境界(未使用)";
$lang_str["tag:admin_level=6"]="都道府県境";
#$lang_str["tag:admin_level=7"]="";
#$lang_str["tag:admin_level=7.5"]="";
$lang_str["tag:admin_level=8"]="市町村境";
$lang_str["tag:admin_level=10"]="町名・街区";

// amenity
$lang_str["tag:amenity"]="生活環境";
$lang_str["tag:amenity=cinema"]=array("映画館", "映画館");
$lang_str["tag:amenity=restaurant"]="レストラン";
$lang_str["tag:amenity=pub"]=array("パブ", "パブ");

// barrier
#$lang_str["tag:barrier"]=array("Barrier", "Barriers");
#$lang_str["tag:barrier=city_wall"]=array("City wall", "City walls");
#$lang_str["tag:barrier=wall"]=array("Wall", "Walls");
#$lang_str["tag:barrier=retaining_wall"]=array("Retaining Wall", "Retaining Walls");
#$lang_str["tag:barrier=fence"]=array("Fence", "Fences");
#$lang_str["tag:barrier=hedge"]=array("Hedge", "Hedges");

// cables
$lang_str["tag:cables"]="ケーブル数";

// cuisine
$lang_str["tag:cuisine"]="料理";
$lang_str["tag:cuisine=regional"]="地域料理";

// description
$lang_str["tag:description"]="説明";

// domination
$lang_str["tag:domination"]="宗派";

// food
$lang_str["tag:food"]="食事つき";

// highway
$lang_str["tag:highway"]=array("道路", "道路");
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
#$lang_str["tag:highway=path"]="Path";
#$lang_str["tag:highway=steps"]="Steps";

// is_in
$lang_str["tag:is_in"]="Is in";

// leisure
$lang_str["tag:leisure=sports_centre"]="スポーツセンター";
$lang_str["tag:leisure=golf_course"]="ゴルフコース";
$lang_str["tag:leisure=stadium"]="スタジアム";
$lang_str["tag:leisure=track"]="トラック";
$lang_str["tag:leisure=pitch"]="競技場";
$lang_str["tag:leisure=water_park"]="Water Park";
$lang_str["tag:leisure=marina"]="マリーナ";
$lang_str["tag:leisure=slipway"]="スリップウェイ";
$lang_str["tag:leisure=fishing"]="釣り";
$lang_str["tag:leisure=nature_reserve"]="自然保護区域";
$lang_str["tag:leisure=park"]="レジャーパーク";
$lang_str["tag:leisure=playground"]="遊び場";
$lang_str["tag:leisure=garden"]="庭園";
$lang_str["tag:leisure=common"]="共有地";
$lang_str["tag:leisure=ice_rink"]="アイスリンク";
$lang_str["tag:leisure=miniature_golf"]="ミニゴルフ";
$lang_str["tag:leisure=swimming_pool"]="プール";
$lang_str["tag:leisure=beach_resort"]="ビーチリゾート";
$lang_str["tag:leisure=bird_hide"]="バードウォッチング小屋";
$lang_str["tag:leisure=sport"]="その他スポーツ";

// man_made
#$lang_str["tag:man_made"]="Artificial structures";
#$lang_str["tag:man_made=pipeline"]=array("Pipeline", "Pipelines");

// man_made - type
#$lang_str["tag:type"]="Type";
#$lang_str["tag:type=gas"]="Gas";
#$lang_str["tag:type=heat"]="Heat";
#$lang_str["tag:type=hot_water"]="Hot Water";
#$lang_str["tag:type=oil"]="Oil";
#$lang_str["tag:type=sewage"]="Sewage";
#$lang_str["tag:type=water"]="Water";

// name
$lang_str["tag:name"]=array("名前", "名前");

// network
$lang_str["tag:network"]="ネットワーク";

// note
$lang_str["tag:note"]="メモ";

// old_name
$lang_str["tag:old_name"]="旧名";

// opening_hours
$lang_str["tag:opening_hours"]="営業時間";

// operator
$lang_str["tag:operator"]="管理者";

// place
$lang_str["tag:place"]="場所";
$lang_str["tag:place=continent"]="大陸";
$lang_str["tag:place=country"]="国";
$lang_str["tag:place=state"]="州";
$lang_str["tag:place=region"]="地方";
$lang_str["tag:place=county"]="郡";
$lang_str["tag:place=city"]="都市";
$lang_str["tag:place=town"]="町";
$lang_str["tag:place=village"]="村";
$lang_str["tag:place=suburb"]="近郊(未使用)";
$lang_str["tag:place=hamlet"]="小村(未使用)";
$lang_str["tag:place=locality"]="地域の通称";
$lang_str["tag:place=island"]="島";
$lang_str["tag:place=hamlet"]="小村(未使用)";
#$lang_str["tag:place=islet"]=array("Islet", "Islets");

// population
$lang_str["tag:population"]="人口";
$tag_type["population"]=array("count");

// power
#$lang_str["tag:power"]="Power";
$lang_str["tag:power=generator"]="発電所";
$lang_str["tag:power=line"]="電力線";
#$lang_str["tag:power=minor_line"]="Minor Power Line";
$lang_str["tag:power=tower"]="送電塔";
$lang_str["tag:power=pole"]="電柱";
$lang_str["tag:power=station"]="変電所";
$lang_str["tag:power=sub_station"]="小規模変電所";

// power_source
$lang_str["tag:power_source"]="電力源";
$lang_str["tag:power_source=biofuel"]="バイオ燃料";
$lang_str["tag:power_source=oil"]="石油";
$lang_str["tag:power_source=coal"]="石炭";
$lang_str["tag:power_source=gas"]="ガス";
$lang_str["tag:power_source=waste"]="廃棄物";
$lang_str["tag:power_source=hydro"]="水素";
$lang_str["tag:power_source=tidal"]="潮流";
$lang_str["tag:power_source=wave"]="波";
$lang_str["tag:power_source=geothermal"]="地熱";
$lang_str["tag:power_source=nuclear"]="原子力";
$lang_str["tag:power_source=fusion"]="核融合";
$lang_str["tag:power_source=wind"]="風力";
$lang_str["tag:power_source=photovoltaic"]="太陽電池";
$lang_str["tag:power_source=solar-thermal"]="太陽熱";

// railway
#$lang_str["tag:railway"]="Railway";
#$lang_str["tag:railway=rail"]=array("Rail Track", "Rail Tracks");
#$lang_str["tag:railway=tram"]=array("Tram Track", "Tram Tracks");
#$lang_str["tag:railway=platform"]=array("Platform", "Platforms");

// real_ale
$lang_str["tag:real_ale"]="Real ale offered";

// religion
$lang_str["tag:religion"]="宗教";
$lang_str["tag:religion=christian"]="キリスト教";
#$lang_str["tag:religion=buddhist"]="buddhist";
#$lang_str["tag:religion=hindu"]="hindu";
#$lang_str["tag:religion=jewish"]="jewish";
#$lang_str["tag:religion=muslim"]="muslim";
#$lang_str["tag:religion=multifaith"]="multifaith";

// route
#$lang_str["tag:route"]="Route";
$lang_str["tag:route=train"]="列車";
$lang_str["tag:route=railway"]="鉄道";
$lang_str["tag:route=rail"]="鉄道";
$lang_str["tag:route=light_rail"]="ライトレール";
$lang_str["tag:route=subway"]="地下鉄";
$lang_str["tag:route=tram"]="路面電車";
#$lang_str["tag:route=tram_bus"]="Tram and Bus";
$lang_str["tag:route=trolley"]="トローリー";
$lang_str["tag:route=trolleybus"]="トローリーバス";
$lang_str["tag:route=bus"]="バス";
$lang_str["tag:route=minibus"]="ミニバス";
$lang_str["tag:route=ferry"]="フェリー";
$lang_str["tag:route=road"]="道路";
$lang_str["tag:route=bicycle"]="自転車";
$lang_str["tag:route=hiking"]="ハイキング";
$lang_str["tag:route=mtb"]="マウンテンバイク";

// route_type
// the following tags are deprecated
$lang_str["tag:route_type"]="ルート種別";

// shop
$lang_str["tag:shop"]="店";

// sport
#$lang_str["tag:sport"]="Sport";
$lang_str["tag:sport=9pin"]="9ピンボウリング";
$lang_str["tag:sport=10pin"]="10ピンボウリング";
$lang_str["tag:sport=archery"]="アーチェリー";
$lang_str["tag:sport=athletics"]="アスレチックス";
$lang_str["tag:sport=australian_football"]="オージーフットボール";
$lang_str["tag:sport=baseball"]="野球";
$lang_str["tag:sport=basketball"]="バスケットボール";
$lang_str["tag:sport=beachvolleyball"]="ビーチバレー";
$lang_str["tag:sport=boules"]="金属ボールゲーム";
$lang_str["tag:sport=bowls"]="ローンボウリング";
$lang_str["tag:sport=canoe"]="カヌー";
$lang_str["tag:sport=chess"]="チェス";
$lang_str["tag:sport=climbing"]="登山";
$lang_str["tag:sport=cricket"]="クリケット";
$lang_str["tag:sport=cricket_nets"]="クリケットのネット";
$lang_str["tag:sport=croquet"]="クロッケー";
$lang_str["tag:sport=cycling"]="サイクリング";
$lang_str["tag:sport=diving"]="ダイビング";
$lang_str["tag:sport=dog_racing"]="ドッグレース";
$lang_str["tag:sport=equestrian"]="馬術";
$lang_str["tag:sport=football"]="フットボール";
$lang_str["tag:sport=golf"]="ゴルフ";
$lang_str["tag:sport=gymnastics"]="ジム";
$lang_str["tag:sport=hockey"]="ホッケー";
$lang_str["tag:sport=horse_racing"]="競馬";
$lang_str["tag:sport=korfball"]="コーフボール";
$lang_str["tag:sport=motor"]="自動車";
$lang_str["tag:sport=multi"]="複合競技";
$lang_str["tag:sport=orienteering"]="オリエンテーリング";
$lang_str["tag:sport=paddle_tennis"]="パドルテニス";
$lang_str["tag:sport=paragliding"]="パラグライダー";
$lang_str["tag:sport=pelota"]="Pelota(ボール)";
$lang_str["tag:sport=racquet"]="ラケット";
$lang_str["tag:sport=rowing"]="ボート";
$lang_str["tag:sport=rugby"]="ラグビー";
$lang_str["tag:sport=shooting"]="射撃";
$lang_str["tag:sport=skating"]="スケート";
$lang_str["tag:sport=skateboard"]="スケートボード";
$lang_str["tag:sport=skiing"]="スキー";
$lang_str["tag:sport=soccer"]="サッカー";
$lang_str["tag:sport=swimming"]="水泳";
$lang_str["tag:sport=table_tennis"]="テーブルテニス";
$lang_str["tag:sport=team_handball"]="ハンドボール";
$lang_str["tag:sport=tennis"]="テニス";
$lang_str["tag:sport=volleyball"]="バレーボール";

// tracks
#$lang_str["tag:tracks"]="Tracks";
#$lang_str["tag:tracks=single"]="Single";
#$lang_str["tag:tracks=double"]="Double";
#$lang_str["tag:tracks=multiple"]="Multiple";

// vending
$lang_str["tag:vending"]="自販機";

// voltage
$lang_str["tag:voltage"]="電圧";
$tag_type["voltage"]=array("number", "V", "V");

// wires
$lang_str["tag:wires"]="ワイヤー数";
$tag_type["wires"]=array("count");

// website
$lang_str["tag:website"]="Webサイト";
$tag_type["website"]=array("link");
