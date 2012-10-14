/*
hide  landuse=water|landuse_sub_type=t0
hide  landuse=water|landuse_sub_type=t1
hide  landuse=water|landuse_sub_type=t2
hide  landuse=water|landuse_sub_type=t3
alias landuse=land|landuse_sub_type=t0 landuse=land
hide  landuse=land|landuse_sub_type=t1
hide  landuse=land|landuse_sub_type=t2
hide  landuse=land|landuse_sub_type=t3
alias landuse=park|landuse_sub_type=t0 landuse=park
hide  landuse=park|landuse_sub_type=t1
hide  landuse=park|landuse_sub_type=t2
hide  landuse=park|landuse_sub_type=t3
alias landuse=water|landuse_sub_type=t0 landuse=water
hide  landuse=water|landuse_sub_type=t1
hide  landuse=water|landuse_sub_type=t2
hide  landuse=water|landuse_sub_type=t3
alias landuse=education|landuse_sub_type=t0 landuse=education
hide  landuse=education|landuse_sub_type=t1
hide  landuse=education|landuse_sub_type=t2
hide  landuse=education|landuse_sub_type=t3
alias landuse=tourism|landuse_sub_type=t0 landuse=tourism
hide  landuse=tourism|landuse_sub_type=t1
hide  landuse=tourism|landuse_sub_type=t2
hide  landuse=tourism|landuse_sub_type=t3
alias landuse=garden|landuse_sub_type=t0 landuse=garden
hide  landuse=garden|landuse_sub_type=t1
hide  landuse=garden|landuse_sub_type=t2
hide  landuse=garden|landuse_sub_type=t3
alias landuse=shop|landuse_sub_type=t0 landuse=shop
hide  landuse=shop|landuse_sub_type=t1
hide  landuse=shop|landuse_sub_type=t2
hide  landuse=shop|landuse_sub_type=t3
alias landuse=industrial|landuse_sub_type=t0 landuse=industrial
alias landuse=industrial|landuse_sub_type=t1 landuse=military
hide  landuse=industrial|landuse_sub_type=t2
hide  landuse=industrial|landuse_sub_type=t3
alias landuse=sport|landuse_sub_type=t0 landuse=sport
alias landuse=sport|landuse_sub_type=t1 landuse=piste
hide  landuse=sport|landuse_sub_type=t2
hide  landuse=sport|landuse_sub_type=t3
alias landuse=cemetery|landuse_sub_type=t0 landuse=cemetery
hide  landuse=cemetery|landuse_sub_type=t1
hide  landuse=cemetery|landuse_sub_type=t2
hide  landuse=cemetery|landuse_sub_type=t3
alias landuse=residential|landuse_sub_type=t0 landuse=residential
hide  landuse=residential|landuse_sub_type=t1
hide  landuse=residential|landuse_sub_type=t2
hide  landuse=residential|landuse_sub_type=t3
alias landuse=natural0|landuse_sub_type=t0 landuse=natural_forest
alias landuse=natural0|landuse_sub_type=t1 landuse=natural_wetland
alias landuse=natural0|landuse_sub_type=t2 landuse=natural_glacier
alias landuse=natural0|landuse_sub_type=t3 landuse=natural_scree
alias landuse=natural1|landuse_sub_type=t0 landuse=natural_mud
alias landuse=natural1|landuse_sub_type=t1 landuse=natural_beach
alias landuse=natural1|landuse_sub_type=t2 landuse=natural_rock
hide  landuse=natural1|landuse_sub_type=t3
alias landuse=nature_reserve|landuse_sub_type=t0 landuse=nature_reserve
hide  landuse=nature_reserve|landuse_sub_type=t1
hide  landuse=nature_reserve|landuse_sub_type=t2
hide  landuse=nature_reserve|landuse_sub_type=t3
alias landuse=historic|landuse_sub_type=t0 landuse=historic
hide  landuse=historic|landuse_sub_type=t1
hide  landuse=historic|landuse_sub_type=t2
hide  landuse=historic|landuse_sub_type=t3
alias landuse=emergency|landuse_sub_type=t0 landuse=emergency
hide  landuse=emergency|landuse_sub_type=t1
hide  landuse=emergency|landuse_sub_type=t2
hide  landuse=emergency|landuse_sub_type=t3
alias landuse=health|landuse_sub_type=t0 landuse=health
hide  landuse=health|landuse_sub_type=t1
hide  landuse=health|landuse_sub_type=t2
hide  landuse=health|landuse_sub_type=t3
alias landuse=public|landuse_sub_type=t0 landuse=public
hide  landuse=public|landuse_sub_type=t1
hide  landuse=public|landuse_sub_type=t2
hide  landuse=public|landuse_sub_type=t3
*/
Map {
  map-bgcolor: #7eb9e3;
}
/* .Map is fake, just for map key
.Map {
  polygon-fill: #7eb9e3;
}
*/
.coastpoly {
  polygon-fill: #f2efd9;
  polygon-gamma: 0.65;
}
.builtup[zoom<11] {
  polygon-fill: #ddd;
}
.landuse_extract[landuse=water][zoom>=5][zoom<10],
.landuse[landuse=water][zoom>=10] {
  polygon-fill: #7eb9e3;
}
.landuse[landuse=water][zoom>=14] {
  line-color: #7eb9e3;
  line-width: 1;
  line-join: round;
  line-cap: round;
}
.landuse_extract[landuse=land][zoom>=5][zoom<10],
.landuse[landuse=land][zoom>=10] {
  polygon-fill: #f2efe9;
}
.landuse_extract[landuse=park][zoom>=5][zoom<10],
.landuse[landuse=park][zoom>=10] {
  polygon-fill: #9ce69c;
}
.landuse_extract[landuse=education][zoom>=5][zoom<10],
.landuse[landuse=education][zoom>=10] {
  polygon-fill: #e39ccf;
}
.landuse_extract[landuse=tourism][zoom>=5][zoom<10],
.landuse[landuse=tourism][zoom>=10] {
  polygon-fill: #cc8bbf;
}
.landuse_extract[landuse=garden][zoom>=5][zoom<10],
.landuse[landuse=garden][zoom>=10] {
  polygon-fill: #b0cc8a;
}
.landuse_extract[landuse=shop][zoom>=5][zoom<10],
.landuse[landuse=shop][zoom>=10] {
  polygon-fill: #ffe285;
}
.landuse_extract[landuse=industrial][zoom>=5][zoom<10],
.landuse[landuse=industrial][zoom>=10] {
  polygon-fill: #b7b8cc;
}
.landuse_extract[landuse=industrial][landuse_sub_type=t1][zoom>=5][zoom<10],
.landuse[landuse=industrial][landuse_sub_type=t1][zoom>=10] {
  polygon-fill: #93a65b;
}
.landuse_extract[landuse=sport][landuse_sub_type=t0][zoom>=5][zoom<10],
.landuse[landuse=sport][landuse_sub_type=t0][zoom>=10] {
  polygon-fill: #8bccb3;
}
.landuse_extract[landuse=sport][landuse_sub_type=t1][zoom>=5][zoom<10],
.landuse[landuse=sport][landuse_sub_type=t1][zoom>=10] {
  polygon-fill: #faf7e0;
}
.landuse_extract[landuse=cemetery][zoom>=5][zoom<10],
.landuse[landuse=cemetery][zoom>=10][zoom<14] {
  polygon-fill: #8acb94;
}
.landuse[landuse=cemetery][zoom>=14] {
  polygon-pattern-file: url('img/grave_yard.png');
}
.landuse_extract[landuse=residential][zoom>=5][zoom<10],
.landuse[landuse=residential][zoom>=10] {
  polygon-fill: #ccb18b;
}
.landuse_extract[landuse=natural0][landuse_sub_type=t0][zoom>=5][zoom<10],
.landuse[landuse=natural0][landuse_sub_type=t0][zoom>=10] {
  polygon-fill: #72c063;
}
.landuse_extract[landuse=nature_reserve][zoom>=5][zoom<10] {
}
.landuse_extract[landuse=natural0][landuse_sub_type=t1][zoom>=5][zoom<10],
.landuse[landuse=natural0][landuse_sub_type=t1][zoom>=10] {
  polygon-fill: #96f992;
}
.landuse_extract[landuse=natural0][landuse_sub_type=t2][zoom>=5][zoom<10],
.landuse[landuse=natural0][landuse_sub_type=t2][zoom>=10] {
  polygon-fill: #84f9ea;
}
.landuse_extract[landuse=natural0][landuse_sub_type=t3][zoom>=5][zoom<10],
.landuse[landuse=natural0][landuse_sub_type=t3][zoom>=10] {
  polygon-fill: #92da4e;
}
.landuse_extract[landuse=natural1][landuse_sub_type=t0][zoom>=5][zoom<10],
.landuse[landuse=natural1][landuse_sub_type=t0][zoom>=10] {
  polygon-fill: #cdc950;
}
.landuse_extract[landuse=natural1][landuse_sub_type=t1][zoom>=5][zoom<10],
.landuse[landuse=natural1][landuse_sub_type=t1][zoom>=10] {
  polygon-fill: #fad16c;
}
.landuse_extract[landuse=natural1][landuse_sub_type=t2][zoom>=5][zoom<10],
.landuse[landuse=natural1][landuse_sub_type=t2][zoom>=10] {
  polygon-fill: #dedede;
}
.landuse[landuse=nature_reserve][zoom>=10][zoom<12] {
  line-color: #7acc49;
  line-width: 1;
}
.landuse[landuse=nature_reserve][zoom>=12][zoom<14] {
  line-color: #7acc49;
  line-width: 2;
}
.landuse[landuse=nature_reserve][zoom>=14] {
  line-color: #7acc49;
  line-width: 4;
}
.landuse_extract[landuse=historic][zoom>=5][zoom<10],
.landuse[landuse=historic][zoom>=10] {
  polygon-fill: #e0c28d;
}
.landuse_extract[landuse=emergency][zoom>=5][zoom<10],
.landuse[landuse=emergency][zoom>=10] {
  polygon-fill: #df9ea5;
}
.landuse_extract[landuse=health][zoom>=5][zoom<10],
.landuse[landuse=health][zoom>=10] {
  polygon-fill: #abffcb;
}
.landuse_extract[landuse=public][zoom>=5][zoom<10],
.landuse[landuse=public][zoom>=10] {
  polygon-fill: #f0dba5;
}

/*
- when no translation of the text is available, the 'name_only'-styles are
  used, which will be wrapped.
- in case of translated texts, the 'name'- and 'name_en'-styles are used, which
  are not wrapped, and the translation is written below with smaller font.
*/
.area_text_extract name_only,
.area_text name_only,
.area_text_extract name,
.area_text name {
  text-face-name: "DejaVu Sans Book";
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-vertical-align: middle;
}
.area_text_extract name_en,
.area_text name_en {
  text-face-name: "DejaVu Sans Book";
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-vertical-align: middle;
}
.area_text_extract[way_area_k>=32000000][zoom=6] name_only,
.area_text_extract[way_area_k>=8000000][zoom=7] name_only,
.area_text_extract[way_area_k<400000000][way_area_k>=2000000][zoom=8] name_only,
.area_text_extract[way_area_k<100000000][way_area_k>=500000][zoom=9] name_only,
.area_text_extract[way_area_k<32000000][way_area_k>=125000][zoom=10] name_only,
.area_text_extract[way_area_k<8000000][way_area_k>=32000][zoom=11] name_only,
.area_text_extract[way_area_k<2000000][way_area_k>=8000][zoom=12] name_only,
.area_text_extract[way_area_k<500000][way_area_k>=2000][zoom=13] name_only,
/* the following zoom levels don't follow the exponential curve. when more
 * objects are displayed this should be adapted too, to not clutter the map */
.area_text[way_area<125000000][way_area>=150000][zoom=14] name_only,
.area_text[way_area<32000000][way_area>=75000][zoom=15] name_only,
.area_text[way_area<8000000][way_area>=10000][zoom=16] name_only,
.area_text[way_area<2000000][zoom=17] name_only,
.area_text[way_area<500000][zoom=18] name_only {
  text-size: 10;
  text-wrap-width: 20;
}
.area_text_extract[way_area_k>=32000000][zoom=6] name,
.area_text_extract[way_area_k>=8000000][zoom=7] name,
.area_text_extract[way_area_k<400000000][way_area_k>=2000000][zoom=8] name,
.area_text_extract[way_area_k<100000000][way_area_k>=500000][zoom=9] name,
.area_text_extract[way_area_k<32000000][way_area_k>=125000][zoom=10] name,
.area_text_extract[way_area_k<8000000][way_area_k>=32000][zoom=11] name,
.area_text_extract[way_area_k<2000000][way_area_k>=8000][zoom=12] name,
.area_text_extract[way_area_k<500000][way_area_k>=2000][zoom=13] name,
/* the following zoom levels don't follow the exponential curve. when more
 * objects are displayed this should be adapted too, to not clutter the map */
.area_text[way_area<125000000][way_area>=150000][zoom=14] name,
.area_text[way_area<32000000][way_area>=75000][zoom=15] name,
.area_text[way_area<8000000][way_area>=10000][zoom=16] name,
.area_text[way_area<2000000][zoom=17] name,
.area_text[way_area<500000][zoom=18] name {
  text-size: 10;
}
.area_text_extract[way_area_k>=32000000][zoom=6] name_en,
.area_text_extract[way_area_k>=8000000][zoom=7] name_en,
.area_text_extract[way_area_k<400000000][way_area_k>=2000000][zoom=8] name_en,
.area_text_extract[way_area_k<100000000][way_area_k>=500000][zoom=9] name_en,
.area_text_extract[way_area_k<32000000][way_area_k>=125000][zoom=10] name_en,
.area_text_extract[way_area_k<8000000][way_area_k>=32000][zoom=11] name_en,
.area_text_extract[way_area_k<2000000][way_area_k>=8000][zoom=12] name_en,
.area_text_extract[way_area_k<500000][way_area_k>=2000][zoom=13] name_en,
/* the following zoom levels don't follow the exponential curve. when more
 * objects are displayed this should be adapted too, to not clutter the map */
.area_text[way_area<125000000][way_area>=150000][zoom=14] name_en,
.area_text[way_area<32000000][way_area>=75000][zoom=15] name_en,
.area_text[way_area<8000000][way_area>=10000][zoom=16] name_en,
.area_text[way_area<2000000][zoom=17] name_en,
.area_text[way_area<500000][zoom=18] name_en {
  text-size: 8;
  text-dy: 9;
}
.area_text_extract[type=water] name_only,
.area_text[type=water] name_only,
.area_text_extract[type=water] name_en,
.area_text[type=water] name_en,
.area_text_extract[type=water] name,
.area_text[type=water] name {
  text-face-name: "DejaVu Sans Oblique";
  text-fill: #156299;
  text-halo-fill: #7eb9e3;
}
.area_text[type=pedestrian] name_only,
.area_text[type=pedestrian] name_en,
.area_text[type=pedestrian] name {
  text-fill: #000000;
  text-halo-fill: #cdcdcd;
}
.area_text[type=pedestrian_tunnel] name_only,
.area_text[type=pedestrian_tunnel] name_en,
.area_text[type=pedestrian_tunnel] name {
  text-fill: #303030;
  text-halo-radius: 0;
}
.area_text_extract[type=park] name_only,
.area_text[type=park] name_only,
.area_text_extract[type=park] name_en,
.area_text[type=park] name_en,
.area_text_extract[type=park] name,
.area_text[type=park] name {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
}
.area_text_extract[type=mountain_range] name_only,
.area_text[type=mountain_range] name_only,
.area_text_extract[type=mountain_range] name_en,
.area_text[type=mountain_range] name_en,
.area_text_extract[type=mountain_range] name,
.area_text[type=mountain_range] name {
  text-face-name: "DejaVu Sans Oblique";
  text-fill: #707070;
  text-halo-fill: #a0a0a0;
}
.area_text_extract[type=island] name_only,
.area_text[type=island] name_only,
.area_text_extract[type=island] name_en,
.area_text[type=island] name_en,
.area_text_extract[type=island] name,
.area_text[type=island] name {
  text-face-name: "DejaVu Sans Oblique";
  text-fill: #56533f;
  text-halo-fill: #f2efd9;
}
