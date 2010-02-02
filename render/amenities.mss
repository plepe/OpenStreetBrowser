.amenity name {
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-dy: 14;
  text-halo-fill: #ffffff;
  text-halo-radius: 1;
  point-allow-overlap: true;
}
.amenity desc {
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #000000;
  text-dy: 24;
  text-halo-fill: #ffffff;
  text-halo-radius: 1;
}

.amenity[type=aeroway][zoom>=11][zoom<14] name {
  text-fill: #636;
  text-size: 8;
}
.amenity[type=aeroway][zoom>=14] name {
  text-fill: #636;
  text-size: 10;
}
.amenity[type=aeroway][zoom>=14] desc {
  text-fill: #636;
  text-size: 10;
}
.amenity[type=aeroway][sub_type=t1][zoom>=11] { /* t1=airport */
  point-file: url('img/airport.png');
}
.amenity[type=aeroway][sub_type=t2][zoom>=11] { /* t2=aerodrome*/
  point-file: url('img/airport.png');
}

.amenity[type=transport][sub_type=t4][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
  text-size: 10;
  text-allow-overlap: true;
}
.amenity[type=transport][sub_type=t4][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
  text-size: 8;
}
.amenity[type=transport][sub_type=t1][zoom>=16] { /* t1=level_crossing */
  point-file: url('img/level_crossing.png');
  point-allow-overlap: true;
}
.amenity[type=transport][sub_type=t2][zoom>=16] { /* t2=mini_roundabout */
  point-file: url('img/mini_round.png');
  point-allow-overlap: true;
}
.amenity[type=transport][sub_type=t3][zoom>=16] { /* t3=gate */
  point-file: url('img/gate.png');
  point-allow-overlap: true;
}
/*
.amenity[type=transport][sub_type=t4][zoom>=12] {  t3=mountain_pass 
  point-file: url('img/mountain_pass.png');
  point-allow-overlap: true;
} */
.amenity[type=obstacle][sub_type=t1][zoom>=16] { /* t1=fountain */
  point-file: url('img/fountain.png');
  point-allow-overlap: true;
}
.amenity[type=obstacle][sub_type=t2][zoom>=16] { /* t1=monument/memorial */
  point-file: url('img/monument.png');
  point-allow-overlap: true;
}

.amenity[type=man_made][sub_type=t1][zoom>=11] { /* t1=mast */
  point-file: url('img/tower.png');
}
.amenity[type=man_made][sub_type=t2][zoom>=11] { /* t2=power_wind */
  point-file: url('img/power_wind.png');
}
.amenity[type=man_made][sub_type=t3][zoom>=11] { /* t3=windmill */
  point-file: url('img/windmill.png');
}

.amenity[type=natural_big][zoom>=14] name {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
  text-size: 8;
}
.amenity[type=natural_big][zoom>=16] name {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
  text-size: 10;
}
.amenity[type=natural_big][zoom>=16] desc {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
  text-size: 8;
}
.amenity[type=natural_big][sub_type=t1][zoom>=11] name {
  point-file: url('img/peak.png');
}
.amenity[type=natural_big][sub_type=t5][zoom>=11] name {
  point-file: url('img/volcano.png');
}
.amenity[type=natural_big][sub_type=t3][zoom>=11] name {
  point-file: url('img/cave.png');
}
.amenity[type=natural][zoom>=17] name {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
  text-size: 10;
}
.amenity[type=natural][zoom>=17] desc {
  text-fill: #000000;
  text-halo-fill: #9ce69c;
  text-size: 8;
}
.amenity[type=natural][sub_type=t4][zoom>=16] {
  point-file: url('img/tree.png');
}

.amenity[type=power][sub_type=t1][zoom>=15] {
  point-file: url('img/power_tower.png');
}
.amenity[type=power][sub_type=t2][zoom>=15] {
  point-file: url('img/power_sub_station.png');
}
.amenity[type=power][sub_type=t3][zoom>=17] {
  point-file: url('img/power_pole.png');
}
