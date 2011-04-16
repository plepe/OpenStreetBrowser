.water[waterway=river],
.water[waterway=stream],
.water[waterway=canal] {
  line-color: #7eb9e3;
  line-join: round;
  line-cap: round;
}
.water[waterway=river][zoom>=5][zoom<10],
.water[waterway=stream][zoom>=11][zoom<14],
.water[waterway=canal][zoom>=5][zoom<12] {
  line-width: 1;
}
.water[waterway=river][zoom>=10][zoom<14],
.water[waterway=stream][zoom>=14],
.water[waterway=canal][zoom>=12][zoom<15] {
  line-width: 2;
}
.water[waterway=river][zoom>=14][zoom<15],
.water[waterway=canal][zoom>=15][zoom<16] {
  line-width: 5;
}
.water[waterway=river][zoom>=15][zoom<16],
.water[waterway=canal][zoom>=16] {
  line-width: 8;
}
.water[waterway=river][zoom>=16] {
  line-width: 12;
}
