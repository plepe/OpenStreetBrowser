/*
alias place=country_large place=country|population>20000000
alias place=country_medium place=country|population>1000000
alias place=country place=country|population<=1000000
alias place=city_large place=city|population>=1000000
alias place=city_medium place=city|population>=200000
alias place=town_large place=town|population>=30000
*/
.places_high name,
.places_high name_en,
.places_db name,
.places_db name_en {
  text-vertical-align: middle;
}
.places_high_point,
.places_db_point {
  point-allow-overlap: true;
}
.places_high[place=continent][zoom>=2][zoom<4] name,
.places_high[place=city_large][zoom>=10][zoom<11] name,
.places_high[place=city_medium][zoom>=10][zoom<11] name,
.places_db[place=city_large][zoom>=11][zoom<15] name,
.places_db[place=city_medium][zoom>=11][zoom<15] name,
.places_high[place=city][zoom>=10][zoom<11] name,
.places_db[place=city][zoom>=11][zoom<15] name,
.places_db[place=town_large][zoom>=13][zoom<15] name,
.places_db[place=town][zoom>=13][zoom<15] name,
.places_db[place=suburb][zoom>=15] name,
.places_db[place=village][zoom>=15] name
{
  text-size: 12;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-avoid-edges: true;
  text-halo-radius: 2;
  text-vertical-align: middle;
}
.places_high[place=continent][zoom>=2][zoom<4] name_en,
.places_high[place=city_large][zoom>=10][zoom<11] name_en,
.places_high[place=city_medium][zoom>=10][zoom<11] name_en,
.places_db[place=city_large][zoom>=11][zoom<15] name_en,
.places_db[place=city_medium][zoom>=11][zoom<15] name_en,
.places_high[place=city][zoom>=10][zoom<11] name_en,
.places_db[place=city][zoom>=11][zoom<15] name_en,
.places_db[place=town_large][zoom>=13][zoom<15] name_en,
.places_db[place=town][zoom>=13][zoom<15] name_en,
.places_db[place=suburb][zoom>=15] name_en,
.places_db[place=village][zoom>=15] name_en
{
  text-size: 10;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-avoid-edges: true;
  text-halo-radius: 2;
  text-dy: 9;
}
.places_high[place=continent][zoom=1] name,
.places_db[place=suburb][zoom>=13][zoom<15] name,
.places_db[place=hamlet][zoom>=15] name,
.places_db[place=isolated_dwelling][zoom>=17] name
{
  text-size: 10;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-avoid-edges: true;
  text-halo-radius: 1;
}
.places_high[place=continent][zoom=1] name_en,
.places_db[place=suburb][zoom>=13][zoom<15] name_en,
.places_db[place=hamlet][zoom>=15] name_en,
.places_db[place=isolated_dwelling][zoom>=17] name_en
{
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-dy: 8;
}
.places_db[place=locality][zoom>=15] name
{
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #222222;
  text-avoid-edges: true;
  text-halo-radius: 1;
}
.places_db[place=locality][zoom>=15] name_en
{
  text-size: 6;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #222222;
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-dy: 5;
}
.places_high[place=country_large][zoom>=6][zoom<9] name,
.places_high[place=country_medium][zoom>=8][zoom<11] name,
.places_high[place=country][zoom>=10][zoom<11] name,
.places_db[place=country][zoom>=11][zoom<13] name,
.places_high[place=state][zoom>=9][zoom<11] name,
.places_db[place=state][zoom>=11][zoom<12] name,
.places_db[place=region][zoom>=12][zoom<15] name
{
  text-size: 12;
  text-placement: point;
  text-face-name: "DejaVu Sans Bold";
  text-fill: #000000;
  text-avoid-edges: true;
  text-halo-radius: 2;
}
.places_high[place=country_large][zoom>=6][zoom<9] name_en,
.places_high[place=country_medium][zoom>=8][zoom<11] name_en,
.places_high[place=country][zoom>=10][zoom<11] name_en,
.places_high[place=state][zoom>=9][zoom<11] name_en,
.places_db[place=state][zoom>=11][zoom<12] name_en,
.places_db[place=region][zoom>=12][zoom<15] name_en
{
  text-size: 10;
  text-placement: point;
  text-face-name: "DejaVu Sans Bold";
  text-fill: #000000;
  text-avoid-edges: true;
  text-halo-radius: 2;
  text-dy: 12;
}

.places_high[place=country_large][zoom>=4][zoom<6] name,
.places_high[place=country_medium][zoom>=6][zoom<8] name,
.places_high[place=country][zoom>=8][zoom<10] name,
.places_high[place=state][zoom>=7][zoom<9] name,
.places_high[place=region][zoom>=9][zoom<11] name,
.places_db[place=region][zoom>=11][zoom<12] name
{
  text-size: 10;
  text-placement: point;
  text-face-name: "DejaVu Sans Bold";
  text-fill: #222222;
  text-avoid-edges: true;
  text-halo-radius: 1;
}
.places_high[place=country_large][zoom>=4][zoom<6] name_en,
.places_high[place=country_medium][zoom>=6][zoom<8] name_en,
.places_high[place=country][zoom>=8][zoom<10] name_en,
.places_high[place=state][zoom>=7][zoom<9] name_en,
.places_high[place=region][zoom>=9][zoom<11] name_en,
.places_db[place=region][zoom>=11][zoom<12] name_en
{
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Bold";
  text-fill: #222222;
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-dy: 8;
}
.places_high[place=country_large][zoom>=2][zoom<4] name,
.places_high[place=country_medium][zoom>=4][zoom<6] name,
.places_high[place=country][zoom>=6][zoom<8] name
{
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Bold";
  text-fill: #333333;
  text-avoid-edges: true;
  text-halo-radius: 1;
}
.places_high[place=country_large][zoom>=2][zoom<4] name_en,
.places_high[place=country_medium][zoom>=4][zoom<6] name_en,
.places_high[place=country][zoom>=6][zoom<8] name_en
{
  text-size: 6;
  text-placement: point;
  text-face-name: "DejaVu Sans Bold";
  text-fill: #333333;
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-dy: 6;
}
.places_high_point[place=city_large][zoom>=4][zoom<6] ,
.places_high_point[place=city_medium][zoom>=6][zoom<8] ,
.places_high_point[place=city][zoom>=7][zoom<8] ,
.places_high_point[place=town_large][zoom>=8][zoom<10] ,
.places_high_point[place=town][zoom>=9][zoom<10] ,
.places_db_point[place=village][zoom>=11][zoom<13] ,
.places_db_point[place=hamlet][zoom>=13][zoom<15],
.places_db_point[place=isolated_dwelling][zoom>=15][zoom<17]
{
  point-file: url('img/city5.png');
}
.places_high[place=city_large][zoom>=4][zoom<6] name,
.places_high[place=city_medium][zoom>=6][zoom<8] name,
.places_high[place=city][zoom>=7][zoom<8] name,
.places_high[place=town_large][zoom>=8][zoom<10] name,
.places_high[place=town][zoom>=9][zoom<10] name,
.places_db[place=village][zoom>=11][zoom<13] name,
.places_db[place=hamlet][zoom>=13][zoom<15] name,
.places_db[place=isolated_dwelling][zoom>=15][zoom<17] name
{
  text-dy: 4;
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #222222;
  text-avoid-edges: false;
  text-halo-radius: 1;
}
.places_high[place=city_large][zoom>=4][zoom<6] name_en,
.places_high[place=city_medium][zoom>=6][zoom<8] name_en,
.places_high[place=city][zoom>=7][zoom<8] name_en,
.places_high[place=town_large][zoom>=8][zoom<10] name_en,
.places_high[place=town][zoom>=9][zoom<10] name_en,
.places_db[place=village][zoom>=11][zoom<13] name_en,
.places_db[place=hamlet][zoom>=13][zoom<15] name_en,
.places_db[place=isolated_dwelling][zoom>=15][zoom<17] name_en
{
  text-size: 6;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #222222;
  text-avoid-edges: false;
  text-halo-radius: 1;
  text-dy: 14;
}
.places_high_point[place=city_large][zoom>=6][zoom<10] ,
.places_high_point[place=city_medium][zoom>=8][zoom<10] ,
.places_high_point[place=city][zoom>=8][zoom<10] ,
.places_high_point[place=town_large][zoom>=10][zoom<11] ,
.places_db_point[place=town_large][zoom>=11][zoom<13] ,
.places_high_point[place=town][zoom>=10][zoom<11] ,
.places_db_point[place=town][zoom>=11][zoom<13] ,
.places_db_point[place=village][zoom>=13][zoom<15] 
{
  point-file: url('img/city7.png');
}
.places_high[place=city_large][zoom>=6][zoom<10] name,
.places_high[place=city_medium][zoom>=8][zoom<10] name,
.places_high[place=city][zoom>=8][zoom<10] name,
.places_high[place=town_large][zoom>=10][zoom<11] name,
.places_db[place=town_large][zoom>=11][zoom<13] name,
.places_high[place=town][zoom>=10][zoom<11] name,
.places_db[place=town][zoom>=11][zoom<13] name,
.places_db[place=village][zoom>=13][zoom<15] name
{
  text-dy: 5;
  text-size: 10;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-avoid-edges: false;
  text-halo-radius: 1;
}
.places_high[place=city_large][zoom>=6][zoom<10] name_en,
.places_high[place=city_medium][zoom>=8][zoom<10] name_en,
.places_high[place=city][zoom>=8][zoom<10] name_en,
.places_high[place=town_large][zoom>=10][zoom<11] name_en,
.places_db[place=town_large][zoom>=11][zoom<13] name_en,
.places_high[place=town][zoom>=10][zoom<11] name_en,
.places_db[place=town][zoom>=11][zoom<13] name_en,
.places_db[place=village][zoom>=13][zoom<15] name_en
{
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-avoid-edges: false;
  text-halo-radius: 1;
  text-dy: 17;
}

.places_high[place=ocean][zoom>=2][zoom<6] name {
  text-face-name: "DejaVu Sans Oblique";
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-fill: #156299;
  text-halo-fill: #7eb9e3;
}
.places_high[place=ocean][zoom>=2][zoom<3] name {
  text-size: 8;
}
.places_high[place=ocean][zoom>=3][zoom<5] name {
  text-size: 10;
}
.places_high[place=ocean][zoom>=5][zoom<6] name {
  text-size: 13;
}

.places_high[place=sea][zoom>=4][zoom<10] name {
  text-face-name: "DejaVu Sans Oblique";
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-fill: #156299;
  text-halo-fill: #7eb9e3;
  text-dy: -1;
}
.places_high[place=sea][zoom>=4][zoom<10] name_en {
  text-face-name: "DejaVu Sans Oblique";
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-fill: #156299;
  text-halo-fill: #7eb9e3;
  text-dy: 1;
}
.places_high[place=sea][zoom>=4][zoom<5] name {
  text-size: 9;
}
.places_high[place=sea][zoom>=4][zoom<5] name_en {
  text-size: 7;
}
.places_high[place=sea][zoom>=5][zoom<8] name {
  text-size: 11;
}
.places_high[place=sea][zoom>=5][zoom<8] name_en {
  text-size: 10;
}
.places_high[place=sea][zoom>=8][zoom<10] name {
  text-size: 13;
}
.places_high[place=sea][zoom>=8][zoom<10] name_en {
  text-size: 11;
}


.places_db[place=island][zoom>=12][zoom<16] name,
.places_db[place=islet][zoom>=15] name {
  text-face-name: "DejaVu Sans Oblique";
  text-size: 10;
  text-placement: point;
  text-fill: #56533f;
  text-halo-fill: #f2efd9;
  text-halo-radius: 1;
}
.places_db[place=island][zoom>=12][zoom<16] name_en,
.places_db[place=islet][zoom>=15] name_en {
  text-face-name: "DejaVu Sans Oblique";
  text-size: 8;
  text-placement: point;
  text-fill: #56533f;
  text-halo-fill: #f2efd9;
  text-halo-radius: 1;
  text-dy: 9;
}
