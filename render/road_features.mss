.housenumbers[zoom>=17] number {
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #505050;
  text-avoid-edges: false;
  point-allow-overlap: true;
  text-halo-radius: 0;
  text-halo-fill: #f2efd9;
}
.housenumber_lines[zoom>=17] {
  line-color: #505050;
  line-width: 0.5;
  line-dasharray: 2, 4;
}
.roads_features[highway_type=path][sub_type=t1][zoom>=13][zoom<15] {
  line-dasharray: 1,2;
  line-color: #707070;
}
.roads_features[highway_type=path][sub_type=t1][zoom>=15][zoom<17] {
  line-dasharray: 1,3;
  line-color: #707070;
}
.roads_features[highway_type=path][sub_type=t1][zoom>=17] {
  line-dasharray: 1,4;
  line-color: #707070;
}
.hn[zoom>=17] number {
  text-size: 8;
  text-placement: line;
  text-face-name: "DejaVu Sans Book";
  text-fill: #505050;
  text-avoid-edges: false;
  text-halo-radius: 0;
  text-halo-fill: #f2efd9;
  text-allow-overlap: true;
}
