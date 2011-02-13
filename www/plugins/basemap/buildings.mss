.buildings_trans[zoom>=15][zoom<16] {
  line-color: #000000;
  line-width: 0.5;
  line-opacity: 0.5;
  polygon-opacity: 0.25;
}
.buildings_trans[zoom>=16][zoom<17] {
  line-color: #000000;
  line-width: 0.5;
  line-opacity: 0.75;
  polygon-opacity: 0.375;
}
.buildings_trans[zoom>=17] {
  polygon-opacity: 0.5;
  line-color: #000000;
  line-width: 0.5;
}
.buildings[zoom>=14] {
  line-color: #000000;
  line-width: 0.5;
}
.buildings[building=default][zoom>=13],
.buildings_trans[building=default][zoom>=15] {
  polygon-fill: #cc5029;
}
.buildings[building=worship][zoom>=13],
.buildings_trans[building=worship][zoom>=15] {
  polygon-fill: #af29cc;
}
.buildings[building=road_amenities][zoom>=13],
.buildings_trans[building=road_amenities][zoom>=15] {
  polygon-fill: #2935cc;
}
.buildings[building=nature_building][zoom>=13],
.buildings_trans[building=nature_building][zoom>=15] {
  polygon-fill: #57cc29;
}
.buildings[building=industrial][zoom>=13],
.buildings_trans[building=industrial][zoom>=15] {
  polygon-fill: #2935cc;
}
.buildings[building=education][zoom>=13],
.buildings_trans[building=education][zoom>=15] {
  polygon-fill: #e327ae;
}
.buildings[building=shop][zoom>=13],
.buildings_trans[building=shop][zoom>=15] {
  polygon-fill: #ffce33;
}
.buildings[building=public][zoom>=13],
.buildings_trans[building=public][zoom>=15] {
  polygon-fill: #f0b215;
}
.buildings[building=military][zoom>=13],
.buildings_trans[building=military][zoom>=15] {
  polygon-fill: #6a8420;
}
.buildings[building=historic][zoom>=13],
.buildings_trans[building=historic][zoom>=15] {
  polygon-fill: #e09c25;
}
.buildings[building=emergency][zoom>=13],
.buildings_trans[building=emergency][zoom>=15] {
  polygon-fill: #df0e26;
}
.buildings[building=health][zoom>=13],
.buildings_trans[building=health][zoom>=15] {
  polygon-fill: #13ff6c;
}
.buildings[building=communication][zoom>=13],
.buildings_trans[building=communication][zoom>=15] {
  polygon-fill: #ffd71f;
}
.buildings[building=residential][zoom>=13],
.buildings_trans[building=residential][zoom>=15] {
  polygon-fill: #cc8725;
}
.buildings[building=culture][zoom>=13],
.buildings_trans[building=culture][zoom>=15] {
  polygon-fill: #ff5745;
}
.buildings[building=tourism][zoom>=13],
.buildings_trans[building=tourism][zoom>=15] {
  polygon-fill: #2045d8;
}
.buildings[building=sport][zoom>=13],
.buildings_trans[building=sport][zoom>=15] {
  polygon-fill: #16cc86;
}
