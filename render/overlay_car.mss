.amenity_car[amenity=fuel][zoom>=13]
{
  point-file: url('img/fuel.png');
}
.amenity_car[amenity=car_repair][zoom>=13]
{
  point-file: url('img/car_repair.png');
}
.amenity_car[amenity=parking][zoom>=15]
{
  point-file: url('img/parking.png');
}
.amenity_car[amenity=parking][zoom>=14][zoom<15]
{
  point-file: url('img/parking_small.png');
}
.amenity_car[amenity=traffic_signals][zoom>=15]
{
  point-file: url('img/traffic_signals.png');
}
.amenity_car[amenity=stop][zoom>=15]
{
  point-file: url('img/stop.png');
}
.text[highway=motorway_junction][zoom>=11][zoom<15] ref
{
  text-fill: #000000;
  text-halo-radius: 1;
  text-halo-fill: #ff6838;
  text-face-name: "DejaVu Sans Book";
  text-size: 9;
  text-wrap-width: 12;
  text-min-distance: 20;
}
.text[highway=motorway_junction][zoom>=12][zoom<15] name
{
  text-fill: #000000;
  text-halo-radius: 1;
  text-halo-fill: #ff6838;
  text-face-name: "DejaVu Sans Book";
  text-size: 8;
  text-dy: -8;
  text-wrap-width: 12;
  text-min-distance: 20;
}
.text[highway=motorway_junction][zoom>=15] ref,
.text[highway=motorway_junction][zoom>=15] name
{
  text-fill: #6666ff;
  text-halo-radius: 1;
  text-face-name: "DejaVu Sans Book";
  text-wrap-width: 12;
  text-min-distance: 100;
}
.text[highway=motorway_junction][zoom>=15] ref
{
  text-size: 13;
}
.text[highway=motorway_junction][zoom>=15] name
{
  text-size: 14;
  text-dy: -14;
}
.roadstext[highway=motorway][zoom>=9] ref
{
  shield-size: 11;
  shield-fill: #809bc0;
  shield-face-name: "DejaVu Sans Book";
  shield-min-distance: 100;
  shield-height: 17;
}
.roadstext[highway=motorway][zoom>=9][length=1] ref
{
  shield-file: url('img/motorway_shield1.png');
  shield-width: 17;
}
.roadstext[highway=motorway][zoom>=9][length=2] ref
{
  shield-file: url('img/motorway_shield2.png');
  shield-width: 24;
}
.roadstext[highway=motorway][zoom>=9][length=3] ref
{
  shield-file: url('img/motorway_shield3.png');
  shield-width: 31;
}
.roadstext[highway=motorway][zoom>=9][length=4] ref
{
  shield-file: url('img/motorway_shield4.png');
  shield-width: 38;
}
.roadstext[highway=motorway][zoom>=9][length=5] ref
{
  shield-file: url('img/motorway_shield5.png');
  shield-width: 45;
}
.roadstext[highway=motorway][zoom>=9][length=6] ref
{
  shield-file: url('img/motorway_shield6.png');
  shield-width: 52;
}
.roadstext[highway=trunk][zoom>=10] ref
{
  shield-size: 11;
  shield-fill: #7fc97f;
  shield-face-name: "DejaVu Sans Book";
  shield-min-distance: 100;
  shield-height: 17;
}
.roadstext[highway=trunk][zoom>=10][length=1] ref
{
  shield-file: url('img/trunk_shield1.png');
  shield-width: 17;
}
.roadstext[highway=trunk][zoom>=10][length=2] ref
{
  shield-file: url('img/trunk_shield2.png');
  shield-width: 24;
}
.roadstext[highway=trunk][zoom>=10][length=3] ref
{
  shield-file: url('img/trunk_shield3.png');
  shield-width: 31;
}
.roadstext[highway=trunk][zoom>=10][length=4] ref
{
  shield-file: url('img/trunk_shield4.png');
  shield-width: 38;
}
.roadstext[highway=trunk][zoom>=10][length=5] ref
{
  shield-file: url('img/trunk_shield5.png');
  shield-width: 45;
}
.roadstext[highway=trunk][zoom>=10][length=6] ref
{
  shield-file: url('img/trunk_shield6.png');
  shield-width: 52;
}
.roadstext[highway=primary][zoom>=11] ref
{
  shield-size: 11;
  shield-fill: #e46d71;
  shield-face-name: "DejaVu Sans Book";
  shield-min-distance: 100;
  shield-height: 17;
}
.roadstext[highway=primary][zoom>=11][length=1] ref
{
  shield-file: url('img/primary_shield1.png');
  shield-width: 17;
}
.roadstext[highway=primary][zoom>=11][length=2] ref
{
  shield-file: url('img/primary_shield2.png');
  shield-width: 24;
}
.roadstext[highway=primary][zoom>=11][length=3] ref
{
  shield-file: url('img/primary_shield3.png');
  shield-width: 31;
}
.roadstext[highway=primary][zoom>=11][length=4] ref
{
  shield-file: url('img/primary_shield4.png');
  shield-width: 38;
}
.roadstext[highway=primary][zoom>=11][length=5] ref
{
  shield-file: url('img/primary_shield5.png');
  shield-width: 45;
}
.roadstext[highway=primary][zoom>=11][length=6] ref
{
  shield-file: url('img/primary_shield6.png');
  shield-width: 52;
}
.roadstext[network=e-road][zoom>=7] ref
{
  shield-size: 11;
  shield-fill: #ffffff;
  shield-face-name: "DejaVu Sans Book";
  shield-min-distance: 100;
  shield-height: 17;
}
.roadstext[network=e-road][zoom>=7][length=1] ref
{
  shield-file: url('img/europa_shield1.png');
  shield-width: 17;
}
.roadstext[network=e-road][zoom>=7][length=2] ref
{
  shield-file: url('img/europa_shield2.png');
  shield-width: 24;
}
.roadstext[network=e-road][zoom>=7][length=3] ref
{
  shield-file: url('img/europa_shield3.png');
  shield-width: 31;
}
.roadstext[network=e-road][zoom>=7][length=4] ref
{
  shield-file: url('img/europa_shield4.png');
  shield-width: 38;
}
.roadstext[network=e-road][zoom>=7][length=5] ref
{
  shield-file: url('img/europa_shield5.png');
  shield-width: 45;
}
.roadstext[network=e-road][zoom>=7][length=6] ref
{
  shield-file: url('img/europa_shield6.png');
  shield-width: 52;
}
.directions[oneway=forward][zoom>=14][zoom<16],
.directions[oneway=forward][type=motorway][zoom>=12][zoom<13],
.directions[oneway=forward][type=major][zoom>=12][zoom<15] {
  line-pattern-file: url('img/oneway_small.png');
}
.directions[oneway=backward][zoom>=14][zoom<16],
.directions[oneway=backward][type=motorway][zoom>=12][zoom<13],
.directions[oneway=backward][type=major][zoom>=12][zoom<15] {
  line-pattern-file: url('img/oneway_small_back.png');
}
.directions[oneway=forward][zoom>=16],
.directions[oneway=forward][type=motorway][zoom>=13],
.directions[oneway=forward][type=major][zoom>=15] {
  line-pattern-file: url('img/oneway.png');
}
.directions[oneway=backward][zoom>=16],
.directions[oneway=backward][type=motorway][zoom>=13],
.directions[oneway=backward][type=major][zoom>=15] {
  line-pattern-file: url('img/oneway_back.png');
}

