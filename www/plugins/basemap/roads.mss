.roads_casing_end {
  line-join: round;
  line-cap: round;
}
.roads_casing {
  line-join: round;
  line-cap: butt;
}
.roads_casing,
.roads_casing_end {
  line-color: #909090; 
}
.roads_casing[highway_type=railway][highway_sub_type=t1],
.roads_casing_end[highway_type=railway][highway_sub_type=t1] {
  line-color: #b7b8cc;
}
.roads_casing[bridge=yes],
.roads_casing_end[bridge=yes] {
  line-color: #303030; 
  line-cap: butt;
}
.roads_casing[tunnel=yes],
.roads_casing_end[tunnel=yes] {
  line-dasharray: 5,5;
  line-cap: butt;
}
.roads_casing[highway_type=waterway] {
  line-color: #7eb9e3;
  line-join: round;
  line-cap: round;
}
.roads_extract[highway_type=aeroway][highway_sub_type=t1],
.roads_casing[highway_type=aeroway][highway_sub_type=t1],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t1],
.roads_fill[highway_type=aeroway][highway_sub_type=t1] {
  line-cap: square;
}
.roads_extract[highway_type=waterway] {
  line-color: #7eb9e3;
}
.roads_casing_end[highway_type=barrier],
.roads_casing[highway_type=barrier]
{ line-color: #000000; }
.roads_casing_end[highway_type=service][highway_sub_type=t2],
.roads_casing[highway_type=service][highway_sub_type=t2]
{
  line-color: #a77300;
}

.roads_fill {
  line-join: round;
  line-cap: round;
}
.roads_fill[bridge=yes] {
  line-cap: round;
}
.roads_fill[tunnel=yes] {
  line-cap: butt;
}
.roads_rail[tunnel=yes] {
  line-dasharray: 5,5;
}
.roads_fill[highway_type=power] {
  line-color: #ff0000;
}
.roads_extract[highway_type=motorway][highway_sub_type=t1],
.roads_fill[highway_type=motorway][highway_sub_type=t1],
.roads_extract[highway_type=motorway][highway_sub_type=t2],
.roads_fill[highway_type=motorway][highway_sub_type=t2] {
  line-color: #ff6838;
}
.roads_extract[highway_type=motorway][highway_sub_type=t3],
.roads_fill[highway_type=motorway][highway_sub_type=t3],
.roads_extract[highway_type=motorway][highway_sub_type=t4],
.roads_fill[highway_type=motorway][highway_sub_type=t4] { 
  line-color: #ff8a00;
}
.roads_extract[highway_type=major][highway_sub_type=t1][zoom<10],
.roads_fill[highway_type=major][highway_sub_type=t1]][zoom<10],
.roads_extract[highway_type=major][highway_sub_type=t2][zoom<10],
.roads_fill[highway_type=major][highway_sub_type=t2][zoom<10] {
  line-color: #deca00;
}
.roads_extract[highway_type=major][highway_sub_type=t1]zoom>=10],
.roads_fill[highway_type=major][highway_sub_type=t1][zoom>=10],
.roads_extract[highway_type=major][highway_sub_type=t2][zoom>=10],
.roads_fill[highway_type=major][highway_sub_type=t2][zoom>=10] {
  line-color: #ffce00;
}
.roads_extract[highway_type=major][highway_sub_type=t3][zoom>=10],
.roads_fill[highway_type=major][highway_sub_type=t3][zoom>=10]
{ line-color: #ffef4c; }
.roads_extract[highway_type=major][highway_sub_type=t3][zoom<10],
.roads_fill[highway_type=major][highway_sub_type=t3][zoom<10]
{ line-color: #ded042; }
.roads_extract[highway_type=major][highway_sub_type=t4][zoom>=11],
.roads_fill[highway_type=major][highway_sub_type=t4][zoom>=11]
{ line-color: #fff8b5; }
.roads_extract[highway_type=major][highway_sub_type=t4][zoom<11],
.roads_fill[highway_type=major][highway_sub_type=t4][zoom<11]
{ line-color: #ded89d; }
.roads_fill[highway_type=minor][zoom<14]
{ line-color: #a0a0a0; }
.roads_fill[highway_type=minor][zoom>=14]
{ line-color: #ffffff; }
.roads_fill[highway_type=service][highway_sub_type=t1][zoom<15]
{ line-color: #a0a0a0; }
.roads_fill[highway_type=service][highway_sub_type=t1][zoom>=15]
{ line-color: #ffffff; }
.roads_fill[highway_type=pedestrian][zoom<14]
{ line-color: #808080; }
.roads_fill[highway_type=pedestrian][zoom>=14]
{ line-color: #cdcdcd; }
.roads_fill[highway_type=path]
{ line-color: #cdcdcd; }
.roads_fill[highway_type=railway][highway_sub_type=t1]
{ line-color: #a0a0a0; }
.roads_fill[highway_type=railway][highway_sub_type=t2]
{ line-color: #b7b8cc; }
.roads_fill[highway_type=barrier]
{ line-color: #bf685c; }
.roads_fill[highway_type=natural][highway_sub_type=t1][zoom>=12] /* natural=cliff */
{ line-pattern-file: url('img/cliff.png'); }
.roads_fill[highway_type=service][highway_sub_type=t2]
{ line-color: #a77300; }
.roads_extract[highway_type=aeroway][highway_sub_type=t1],
.roads_fill[highway_type=aeroway][highway_sub_type=t1],
.roads_fill[highway_type=aeroway][highway_sub_type=t2]
{
  line-color: #bbbbcc;
}

.roads_extract[tunnel=yes][highway_type=motorway][highway_sub_type=t1],
.roads_fill[tunnel=yes][highway_type=motorway][highway_sub_type=t1],
.roads_extract[tunnel=yes][highway_type=motorway][highway_sub_type=t2],
.roads_fill[tunnel=yes][highway_type=motorway][highway_sub_type=t2] {
  line-color: #ff916e;
}
.roads_extract[tunnel=yes][highway_type=motorway][highway_sub_type=t3],
.roads_fill[tunnel=yes][highway_type=motorway][highway_sub_type=t3],
.roads_extract[tunnel=yes][highway_type=motorway][highway_sub_type=t4],
.roads_fill[tunnel=yes][highway_type=motorway][highway_sub_type=t4] {
  line-color: #ffac4a;}
}
.roads_extract[tunnel=yes][highway_type=major][highway_sub_type=t1],
.roads_fill[tunnel=yes][highway_type=major][highway_sub_type=t1],
.roads_extract[tunnel=yes][highway_type=major][highway_sub_type=t2],
.roads_fill[tunnel=yes][highway_type=major][highway_sub_type=t2] {
  line-color: #d8b321;
}
.roads_extract[tunnel=yes][highway_type=major][highway_sub_type=t3],
.roads_fill[tunnel=yes][highway_type=major][highway_sub_type=t3]
{ line-color: #d8cd57; }
.roads_extract[tunnel=yes][highway_type=major][highway_sub_type=t4],
.roads_fill[tunnel=yes][highway_type=major][highway_sub_type=t4]
{ line-color: #d8d39a; }
.roads_fill[tunnel=yes][highway_type=minor]
{ line-color: #d9d9d9; }
.roads_fill[tunnel=yes][highway_type=service][highway_sub_type=t1]
{ line-color: #d9d9d9; }
.roads_fill[tunnel=yes][highway_type=pedestrian]
{ line-color: #a7a7a7; }
.roads_fill[tunnel=yes][highway_type=path]
{ line-color: #a7a7a7; }
.roads_fill[tunnel=yes][highway_type=railway][highway_sub_type=t1]
{ line-color: #b0b0b0; }
.roads_fill[tunnel=yes][highway_type=railway][highway_sub_type=t2]
{ line-color: #b7b8cc; }

.roads_text {
  line-opacity: 0.2;
}
.roads_text[highway_type=motorway][highway_sub_type=t1],   
.routestext[highway_type=motorway][highway_sub_type=t1],
.roads_text[highway_type=motorway][highway_sub_type=t2], 
.routestext[highway_type=motorway][highway_sub_type=t2] {
  text-halo-fill: #ff6838;
}
.roads_text[highway_type=motorway][highway_sub_type=t3],      
.routestext[highway_type=motorway][highway_sub_type=t3],
.roads_text[highway_type=motorway][highway_sub_type=t4], 
.routestext[highway_type=motorway][highway_sub_type=t4]  {
  text-halo-fill: #ff8a00;
}
.roads_text[highway_type=major][highway_sub_type=t1],    
.routestext[highway_type=major][highway_sub_type=t1],
.roads_text[highway_type=major][highway_sub_type=t2],    
.routestext[highway_type=major][highway_sub_type=t2] {
  text-halo-fill: #ffce00;
}
.roads_text[highway_type=major][highway_sub_type=t3],  
.routestext[highway_type=major][highway_sub_type=t3]  
{ text-halo-fill: #ffef4c; }
.roads_text[highway_type=major][highway_sub_type=t4],  
.routestext[highway_type=major][highway_sub_type=t4]   
{ text-halo-fill: #fff8b5; }
.roads_text[highway_type=minor],   
.routestext[highway_type=minor]      
{ text-halo-fill: #ffffff; }
.roads_text[highway_type=service][highway_sub_type=t1],
.routestext[highway_type=service][highway_sub_type=t1]    
{ text-halo-fill: #ffffff; }
.roads_text[highway_type=path],
.routestext[highway_type=path],
.roads_text[highway_type=pedestrian],
.routestext[highway_type=pedestrian] 
{ text-halo-fill: #cdcdcd; }
.roads_text[highway_type=service][highway_sub_type=t2],
.routestext[highway_type=service][highway_sub_type=t2] 
{
  text-halo-fill: #a77300;
}
.roads_text {
  text-face-name: "DejaVu Sans Book";
  text-placement: line;
  text-avoid-edges: true;
  text-halo-radius: 1;
  text-min-distance: 20;
  text-spacing: 100;
  text-fill: #000000;
  text-max-char-angle-delta: 20;
}
.roads_text[tunnel=yes] {
  text-halo-radius: 0;
  text-fill: #303030;
}

.roads_extract[highway_type=motorway][highway_sub_type=t1][zoom>=5][zoom<6],
.roads_extract[highway_type=motorway][highway_sub_type=t3][zoom>=5][zoom<6],
.roads_extract[highway_type=major][highway_sub_type=t1][zoom>=6][zoom<7],
.roads_extract[highway_type=major][highway_sub_type=t4][zoom>=7][zoom<8] {
  line-width: 0.5;
}
.roads_fill[highway_type=minor][zoom>=11][zoom<12],
.roads_fill[highway_type=pedestrian][zoom>=11][zoom<12],
.roads_fill[highway_type=path][zoom>=12][zoom<13],
.roads_fill[highway_type=service][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_fill[highway_type=service][highway_sub_type=t2][zoom>=12][zoom<14] {
  line-width:  0.5;
}

.roads_extract[highway_type=motorway][highway_sub_type=t1][zoom>=6][zoom<7],
.roads_extract[highway_type=motorway][highway_sub_type=t3][zoom>=6][zoom<7],
.roads_extract[highway_type=major][highway_sub_type=t1][zoom>=7][zoom<8],
.roads_extract[highway_type=major][highway_sub_type=t3][zoom>=7][zoom<8],
.roads_extract[highway_type=major][highway_sub_type=t4][zoom>=8][zoom<9] {
  line-width: 1;
}
.roads_fill[highway_type=minor][zoom>=12][zoom<13],
.roads_fill[highway_type=pedestrian][zoom>=12][zoom<13],
.roads_fill[highway_type=path][zoom>=13][zoom<14],
.roads_fill[highway_type=service][highway_sub_type=t2][zoom>=14][zoom<16],
.roads_fill[highway_type=service][highway_sub_type=t1][zoom>=13][zoom<14] {
  line-width:  1;
}

.roads_extract[highway_type=motorway][highway_sub_type=t1][zoom>=7][zoom<8],
.roads_extract[highway_type=motorway][highway_sub_type=t3][zoom>=7][zoom<8],
.roads_extract[highway_type=major][highway_sub_type=t1][zoom>=8][zoom<10],
.roads_extract[highway_type=major][highway_sub_type=t3][zoom>=8][zoom<10],
.roads_extract[highway_type=major][highway_sub_type=t4][zoom>=9][zoom<10],
.roads_fill[highway_type=major][highway_sub_type=t4][zoom>=10][zoom<11] {
  line-width: 1.5;
}
.roads_fill[highway_type=minor][zoom>=13][zoom<14],
.roads_fill[highway_type=pedestrian][zoom>=13][zoom<14],
.roads_fill[highway_type=path][zoom>=14][zoom<15],
.roads_fill[highway_type=service][highway_sub_type=t2][zoom>=16],
.roads_fill[highway_type=service][highway_sub_type=t1][zoom>=14][zoom<15],
.roads_fill[highway_type=railway][highway_sub_type=t1][zoom>=14][zoom<15] {
  line-width:  1.5;
}

.roads_extract[highway_type=motorway][highway_sub_type=t1][zoom>=8][zoom<9],
.roads_extract[highway_type=motorway][highway_sub_type=t3][zoom>=8][zoom<9],
.roads_extract[highway_type=aeroway][highway_sub_type=t1][zoom>=8][zoom<9],
.roads_fill[highway_type=aeroway][highway_sub_type=t2][zoom>=11][zoom<13] {
  line-width: 2;
}

.roads_casing[highway_type=motorway][highway_sub_type=t2][zoom>=9][zoom<10],
.roads_casing[highway_type=motorway][highway_sub_type=t4][zoom>=9][zoom<10],
.roads_casing[highway_type=major][highway_sub_type=t2][zoom>=10][zoom<12],
.roads_casing[highway_type=major][highway_sub_type=t1][zoom>=10][zoom<12],
.roads_casing[highway_type=major][highway_sub_type=t3][zoom>=10][zoom<12],
.roads_casing[highway_type=major][highway_sub_type=t4][zoom>=11][zoom<13],
.roads_casing[highway_type=railway][highway_sub_type=t2][zoom>=10][zoom<12],
.roads_casing[highway_type=path][zoom>=15][zoom<17],
.roads_casing[highway_type=aeroway][highway_sub_type=t1][zoom>=9][zoom<12],
.roads_casing[highway_type=aeroway][highway_sub_type=t2][zoom>=13][zoom<14],
.roads_casing[highway_type=service][highway_sub_type=t2][zoom>=16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t2][zoom>=9][zoom<10],
.roads_casing_end[highway_type=motorway][highway_sub_type=t4][zoom>=9][zoom<10],
.roads_casing_end[highway_type=major][highway_sub_type=t2][zoom>=10][zoom<13],
.roads_casing_end[highway_type=major][highway_sub_type=t1][zoom>=10][zoom<12],
.roads_casing_end[highway_type=major][highway_sub_type=t3][zoom>=10][zoom<12],
.roads_casing_end[highway_type=major][highway_sub_type=t4][zoom>=11][zoom<13],
.roads_casing_end[highway_type=path][zoom>=15][zoom<17],
.roads_casing_end[highway_type=railway][highway_sub_type=t2][zoom>=10][zoom<12],
.roads_casing_end[highway_type=service][highway_sub_type=t2][zoom>=16],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t1][zoom>=9][zoom<12],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t2][zoom>=13][zoom<14],
.roads_text[highway_type=motorway][highway_sub_type=t2][zoom>=9][zoom<10],
.roads_text[highway_type=motorway][highway_sub_type=t4][zoom>=9][zoom<10],
.roads_text[highway_type=major][highway_sub_type=t2][zoom>=10][zoom<12],
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=10][zoom<12],
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=10][zoom<12],
.roads_text[highway_type=major][highway_sub_type=t4][zoom>=11][zoom<13],
.roads_text[highway_type=railway][highway_sub_type=t2][zoom>=10][zoom<12],
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=9][zoom<12],
.roads_text[highway_type=aeroway][highway_sub_type=t2][zoom>=13][zoom<14],
.roads_text[highway_type=path][zoom>=15][zoom<17] {
  line-width: 3;
}
.roads_fill[highway_type=motorway][highway_sub_type=t2][zoom>=9][zoom<10],
.roads_fill[highway_type=motorway][highway_sub_type=t4][zoom>=9][zoom<10],
.roads_fill[highway_type=major][highway_sub_type=t2][zoom>=10][zoom<13],
.roads_fill[highway_type=railway][highway_sub_type=t2][zoom>=10][zoom<12],
.roads_fill[highway_type=path][zoom>=15][zoom<17],
.roads_fill[highway_type=service][highway_sub_type=t2][zoom>=16],
.roads_features[highway_type=path][zoom>=15][zoom<17],
.roads_fill[highway_type=major][highway_sub_type=t1][zoom>=10][zoom<12],
.roads_fill[highway_type=major][highway_sub_type=t3][zoom>=10][zoom<12],
.roads_fill[highway_type=major][highway_sub_type=t4][zoom>=11][zoom<13],
.roads_fill[highway_type=aeroway][highway_sub_type=t1][zoom>=9][zoom<12],
.roads_fill[highway_type=aeroway][highway_sub_type=t2][zoom>=13][zoom<14] {
  line-width: 2;
}

.roads_casing[highway_type=motorway][highway_sub_type=t1][zoom>=9][zoom<10],
.roads_casing[highway_type=motorway][highway_sub_type=t3][zoom>=9][zoom<10],
.roads_casing[highway_type=motorway][highway_sub_type=t2][zoom>=10][zoom<13],
.roads_casing[highway_type=motorway][highway_sub_type=t4][zoom>=10][zoom<13],
.roads_casing[highway_type=major][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_casing[highway_type=major][highway_sub_type=t2][zoom>=13][zoom<15],
.roads_casing[highway_type=major][highway_sub_type=t3][zoom>=12][zoom<13],
.roads_casing[highway_type=major][highway_sub_type=t4][zoom>=13][zoom<14],
.roads_casing[highway_type=minor][zoom>=14][zoom<15],
.roads_casing[highway_type=pedestrian][zoom>=14][zoom<15],
.roads_casing[highway_type=path][zoom>=17],
.roads_casing[highway_type=service][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_casing[highway_type=railway][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_casing[highway_type=railway][highway_sub_type=t2][zoom>=12][zoom<14],
.roads_casing[highway_type=aeroway][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_casing[highway_type=aeroway][highway_sub_type=t2][zoom>=14][zoom<16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t1][zoom>=9][zoom<10],
.roads_casing_end[highway_type=motorway][highway_sub_type=t3][zoom>=9][zoom<10],
.roads_casing_end[highway_type=motorway][highway_sub_type=t2][zoom>=10][zoom<13],
.roads_casing_end[highway_type=motorway][highway_sub_type=t4][zoom>=10][zoom<13],
.roads_casing_end[highway_type=major][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_casing_end[highway_type=major][highway_sub_type=t2][zoom>=13][zoom<15],
.roads_casing_end[highway_type=major][highway_sub_type=t3][zoom>=12][zoom<13],
.roads_casing_end[highway_type=major][highway_sub_type=t4][zoom>=13][zoom<14],
.roads_casing_end[highway_type=minor][zoom>=14][zoom<15],
.roads_casing_end[highway_type=pedestrian][zoom>=14][zoom<15],
.roads_casing_end[highway_type=path][zoom>=17],
.roads_casing_end[highway_type=service][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_casing_end[highway_type=railway][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_casing_end[highway_type=railway][highway_sub_type=t2][zoom>=12][zoom<14],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t2][zoom>=14][zoom<16],
.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=9][zoom<10],
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=9][zoom<10],
.roads_text[highway_type=motorway][highway_sub_type=t2][zoom>=10][zoom<13],
.roads_text[highway_type=motorway][highway_sub_type=t4][zoom>=10][zoom<13],
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_text[highway_type=major][highway_sub_type=t2][zoom>=13][zoom<15],
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=12][zoom<13],
.roads_text[highway_type=major][highway_sub_type=t4][zoom>=13][zoom<14],
.roads_text[highway_type=minor][zoom>=14][zoom<15],
.roads_text[highway_type=pedestrian][zoom>=14][zoom<15],
.roads_text[highway_type=path][zoom>=17],
.roads_text[highway_type=service][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_text[highway_type=railway][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_text[highway_type=railway][highway_sub_type=t2][zoom>=12][zoom<14],
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_text[highway_type=aeroway][highway_sub_type=t2][zoom>=14][zoom<16] {
  line-width: 5;
}
.roads_fill[highway_type=motorway][highway_sub_type=t1][zoom>=9][zoom<10],
.roads_fill[highway_type=motorway][highway_sub_type=t3][zoom>=9][zoom<10],
.roads_fill[highway_type=motorway][highway_sub_type=t2][zoom>=10][zoom<13],
.roads_fill[highway_type=motorway][highway_sub_type=t4][zoom>=10][zoom<13],
.roads_fill[highway_type=major][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_fill[highway_type=major][highway_sub_type=t2][zoom>=13][zoom<15],
.roads_fill[highway_type=major][highway_sub_type=t3][zoom>=12][zoom<13],
.roads_fill[highway_type=major][highway_sub_type=t4][zoom>=13][zoom<14],
.roads_fill[highway_type=minor][zoom>=14][zoom<15],
.roads_fill[highway_type=railway][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_fill[highway_type=railway][highway_sub_type=t2][zoom>=12][zoom<14],
.roads_fill[highway_type=pedestrian][zoom>=14][zoom<15],
.roads_features[highway_type=pedestrian][zoom>=14][zoom<15],
.roads_fill[highway_type=path][zoom>=17],
.roads_features[highway_type=path][zoom>=17],
.roads_fill[highway_type=service][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_fill[highway_type=aeroway][highway_sub_type=t1][zoom>=12][zoom<13],
.roads_fill[highway_type=aeroway][highway_sub_type=t2][zoom>=14][zoom<16] {
  line-width: 3;
}

.roads_casing[highway_type=service][highway_sub_type=t1][zoom>=16],
.roads_casing[highway_type=railway][highway_sub_type=t1][zoom>=16],
.roads_casing_end[highway_type=service][highway_sub_type=t1][zoom>=16],
.roads_casing_end[highway_type=railway][highway_sub_type=t1][zoom>=16] {
  line-width: 7;
}
.roads_fill[highway_type=railway][highway_sub_type=t1][zoom>=16],
.roads_fill[highway_type=service][highway_sub_type=t1][zoom>=16] {
  line-width: 4.5;
}

.roads_casing[highway_type=motorway][highway_sub_type=t1][zoom>=10][zoom<13],
.roads_casing[highway_type=motorway][highway_sub_type=t3][zoom>=10][zoom<13],
.roads_casing[highway_type=motorway][highway_sub_type=t2][zoom>=13][zoom<16],
.roads_casing[highway_type=motorway][highway_sub_type=t4][zoom>=13][zoom<16],
.roads_casing[highway_type=major][highway_sub_type=t1][zoom>=13][zoom<15],
.roads_casing[highway_type=major][highway_sub_type=t2][zoom>=15][zoom<17],
.roads_casing[highway_type=major][highway_sub_type=t3][zoom>=13][zoom<15],
.roads_casing[highway_type=major][highway_sub_type=t4][zoom>=14][zoom<16],
.roads_casing[highway_type=minor][zoom>=15][zoom<16],
.roads_casing[highway_type=pedestrian][zoom>=15][zoom<16],
.roads_casing[highway_type=railway][highway_sub_type=t2][zoom>=14][zoom<16],
.roads_casing[highway_type=aeroway][highway_sub_type=t1][zoom>=13][zoom<14],
.roads_casing[highway_type=aeroway][highway_sub_type=t2][zoom>=16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t1][zoom>=10][zoom<13],
.roads_casing_end[highway_type=motorway][highway_sub_type=t3][zoom>=10][zoom<13],
.roads_casing_end[highway_type=motorway][highway_sub_type=t2][zoom>=13][zoom<16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t4][zoom>=13][zoom<16],
.roads_casing_end[highway_type=major][highway_sub_type=t1][zoom>=13][zoom<15],
.roads_casing_end[highway_type=major][highway_sub_type=t2][zoom>=15][zoom<17],
.roads_casing_end[highway_type=major][highway_sub_type=t3][zoom>=13][zoom<15],
.roads_casing_end[highway_type=major][highway_sub_type=t4][zoom>=14][zoom<16],
.roads_casing_end[highway_type=minor][zoom>=15][zoom<16],
.roads_casing_end[highway_type=pedestrian][zoom>=15][zoom<16],
.roads_casing_end[highway_type=railway][highway_sub_type=t2][zoom>=14][zoom<16],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t1][zoom>=13][zoom<14],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t2][zoom>=16],
.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=10][zoom<13],
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=10][zoom<13],
.roads_text[highway_type=motorway][highway_sub_type=t2][zoom>=13][zoom<16],
.roads_text[highway_type=motorway][highway_sub_type=t4][zoom>=13][zoom<16],
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=13][zoom<15],
.roads_text[highway_type=major][highway_sub_type=t2][zoom>=15][zoom<17],
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=13][zoom<15],
.roads_text[highway_type=major][highway_sub_type=t4][zoom>=14][zoom<16],
.roads_text[highway_type=minor][zoom>=15][zoom<16],
.roads_text[highway_type=pedestrian][zoom>=15][zoom<16],
.roads_text[highway_type=railway][highway_sub_type=t2][zoom>=14][zoom<16],
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=13][zoom<14],
.roads_text[highway_type=aeroway][highway_sub_type=t2][zoom>=16] {
  line-width: 8;
}
.roads_fill[highway_type=railway][highway_sub_type=t2][zoom>=14][zoom<16],
.roads_fill[highway_type=motorway][highway_sub_type=t1][zoom>=10][zoom<13],
.roads_fill[highway_type=motorway][highway_sub_type=t3][zoom>=10][zoom<13],
.roads_fill[highway_type=motorway][highway_sub_type=t2][zoom>=13][zoom<16],
.roads_fill[highway_type=motorway][highway_sub_type=t4][zoom>=13][zoom<16],
.roads_fill[highway_type=major][highway_sub_type=t1][zoom>=13][zoom<15],
.roads_fill[highway_type=major][highway_sub_type=t2][zoom>=15][zoom<17],
.roads_fill[highway_type=major][highway_sub_type=t3][zoom>=13][zoom<15],
.roads_fill[highway_type=major][highway_sub_type=t4][zoom>=14][zoom<16],
.roads_fill[highway_type=minor][zoom>=15][zoom<16],
.roads_fill[highway_type=pedestrian][zoom>=15][zoom<16],
.roads_features[highway_type=pedestrian][zoom>=15][zoom<16],
.roads_fill[highway_type=aeroway][highway_sub_type=t1][zoom>=13][zoom<14],
.roads_fill[highway_type=aeroway][highway_sub_type=t2][zoom>=16] {
  line-width: 6;
}

.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=10][zoom<13] name,
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=10][zoom<13] name,
.roads_text[highway_type=motorway][highway_sub_type=t2][zoom>=13][zoom<16] name,
.roads_text[highway_type=motorway][highway_sub_type=t4][zoom>=13][zoom<16] name,
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=13][zoom<15] name,
.roads_text[highway_type=major][highway_sub_type=t2][zoom>=15][zoom<16] name,
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=13][zoom<15] name,
.roads_text[highway_type=major][highway_sub_type=t4][zoom>=14][zoom<16] name,
.roads_text[highway_type=minor][zoom>=15][zoom<16] name,
.roads_text[highway_type=pedestrian][zoom>=15][zoom<16] name,
.roads_text[highway_type=service][highway_sub_type=t1][zoom>=16] name,
.roads_text[highway_type=service][highway_sub_type=t2][zoom>=16] name,
.roads_text[highway_type=path][zoom>=16] name,
.roads_fill[highway_type=aeroway][highway_sub_type=t1][zoom>=13][zoom<14] name,
.roads_fill[highway_type=aeroway][highway_sub_type=t2][zoom>=16] name {
  text-size: 8;
}

.roads_casing[highway_type=motorway][highway_sub_type=t1][zoom>=13][zoom<16],
.roads_casing[highway_type=motorway][highway_sub_type=t3][zoom>=13][zoom<16],
.roads_casing[highway_type=motorway][highway_sub_type=t2][zoom>=16],
.roads_casing[highway_type=motorway][highway_sub_type=t4][zoom>=16],
.roads_casing[highway_type=major][highway_sub_type=t1][zoom>=15][zoom<17],
.roads_casing[highway_type=major][highway_sub_type=t2][zoom>=17],
.roads_casing[highway_type=major][highway_sub_type=t3][zoom>=15][zoom<17],
.roads_casing[highway_type=major][highway_sub_type=t4][zoom>=16],
.roads_casing[highway_type=minor][zoom>=16],
.roads_casing[highway_type=pedestrian][zoom>=16],
.roads_casing[highway_type=railway][highway_sub_type=t2][zoom>=16],
.roads_casing[highway_type=aeroway][highway_sub_type=t1][zoom>=14][zoom<15],
.roads_casing_end[highway_type=motorway][highway_sub_type=t1][zoom>=13][zoom<16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t3][zoom>=13][zoom<16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t2][zoom>=16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t4][zoom>=16],
.roads_casing_end[highway_type=major][highway_sub_type=t1][zoom>=15][zoom<17],
.roads_casing_end[highway_type=major][highway_sub_type=t2][zoom>=17],
.roads_casing_end[highway_type=major][highway_sub_type=t3][zoom>=15][zoom<17],
.roads_casing_end[highway_type=major][highway_sub_type=t4][zoom>=16],
.roads_casing_end[highway_type=minor][zoom>=16],
.roads_casing_end[highway_type=pedestrian][zoom>=16],
.roads_casing_end[highway_type=railway][highway_sub_type=t2][zoom>=16],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t1][zoom>=14][zoom<15],
.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=13][zoom<16],
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=13][zoom<16],
.roads_text[highway_type=motorway][highway_sub_type=t2][zoom>=16],
.roads_text[highway_type=motorway][highway_sub_type=t4][zoom>=16],
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=15][zoom<17],
.roads_text[highway_type=major][highway_sub_type=t2][zoom>=17],
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=15][zoom<17],
.roads_text[highway_type=major][highway_sub_type=t4][zoom>=16],
.roads_text[highway_type=minor][zoom>=16],
.roads_text[highway_type=pedestrian][zoom>=16],
.roads_text[highway_type=railway][highway_sub_type=t2][zoom>=16],
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=14][zoom<15] {
  line-width: 12;
}
.roads_fill[highway_type=railway][highway_sub_type=t2][zoom>=16],
.roads_fill[highway_type=motorway][highway_sub_type=t1][zoom>=13][zoom<17],
.roads_fill[highway_type=motorway][highway_sub_type=t3][zoom>=13][zoom<17],
.roads_fill[highway_type=motorway][highway_sub_type=t2][zoom>=16],
.roads_fill[highway_type=motorway][highway_sub_type=t4][zoom>=16],
.roads_fill[highway_type=major][highway_sub_type=t1][zoom>=15][zoom<17],
.roads_fill[highway_type=major][highway_sub_type=t2][zoom>=17],
.roads_fill[highway_type=major][highway_sub_type=t3][zoom>=15][zoom<17],
.roads_fill[highway_type=major][highway_sub_type=t4][zoom>=16],
.roads_fill[highway_type=minor][zoom>=16],
.roads_fill[highway_type=pedestrian][zoom>=16],
.roads_features[highway_type=pedestrian][zoom>=16],
.roads_fill[highway_type=aeroway][highway_sub_type=t1][zoom>=14][zoom<15] {
  line-width: 9;
}
.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=13][zoom<17] name,
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=13][zoom<17] name,
.roads_text[highway_type=motorway][highway_sub_type=t2][zoom>=17] name,
.roads_text[highway_type=motorway][highway_sub_type=t4][zoom>=17] name,
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=15][zoom<17] name,
.roads_text[highway_type=major][highway_sub_type=t2][zoom>=16] name,
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=15][zoom<17] name,
.roads_text[highway_type=major][highway_sub_type=t4][zoom>=16] name,
.roads_text[highway_type=minor][zoom>=16] name,
.roads_text[highway_type=pedestrian][zoom>=16] name,
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=14][zoom<15] name {
  text-size: 9;
}

.roads_casing[highway_type=motorway][highway_sub_type=t1][zoom>=16][zoom<18],
.roads_casing[highway_type=motorway][highway_sub_type=t3][zoom>=16][zoom<18],
.roads_casing[highway_type=major][highway_sub_type=t1][zoom>=17],
.roads_casing[highway_type=major][highway_sub_type=t3][zoom>=17],
.roads_casing[highway_type=aeroway][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t1][zoom>=16][zoom<18],
.roads_casing_end[highway_type=motorway][highway_sub_type=t3][zoom>=16][zoom<18],
.roads_casing_end[highway_type=major][highway_sub_type=t1][zoom>=17],
.roads_casing_end[highway_type=major][highway_sub_type=t3][zoom>=17],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=16][zoom<18],
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=16][zoom<18],
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=17],
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=17],
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=15][zoom<16] {
  line-width: 16;
}
.roads_fill[highway_type=motorway][highway_sub_type=t1][zoom>=16][zoom<18],
.roads_fill[highway_type=motorway][highway_sub_type=t3][zoom>=16][zoom<18],
.roads_fill[highway_type=major][highway_sub_type=t1][zoom>=17],
.roads_fill[highway_type=major][highway_sub_type=t3][zoom>=17],
.roads_fill[highway_type=aeroway][highway_sub_type=t1][zoom>=15][zoom<16] {
  line-width: 12.5;
}
.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=16] name,
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=16] name,
.roads_text[highway_type=major][highway_sub_type=t1][zoom>=17] name,
.roads_text[highway_type=major][highway_sub_type=t3][zoom>=17] name,
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=15] name {
  text-size: 11;
}

.roads_casing[highway_type=motorway][highway_sub_type=t1][zoom>=18],
.roads_casing[highway_type=motorway][highway_sub_type=t3][zoom>=18],
.roads_casing[highway_type=aeroway][highway_sub_type=t1][zoom>=16],
.roads_casing_end[highway_type=motorway][highway_sub_type=t1][zoom>=18],
.roads_casing_end[highway_type=motorway][highway_sub_type=t3][zoom>=18],
.roads_casing_end[highway_type=aeroway][highway_sub_type=t1][zoom>=16],
.roads_text[highway_type=motorway][highway_sub_type=t1][zoom>=18],
.roads_text[highway_type=motorway][highway_sub_type=t3][zoom>=18],
.roads_text[highway_type=aeroway][highway_sub_type=t1][zoom>=16] {
  line-width: 20;
}
.roads_fill[highway_type=motorway][highway_sub_type=t1][zoom>=18],
.roads_fill[highway_type=motorway][highway_sub_type=t3][zoom>=18],
.roads_fill[highway_type=aeroway][highway_sub_type=t1][zoom>=16] {
  line-width: 16;
}

.square_fill[zoom>=11][zoom<14] {
  polygon-fill: #a0a0a0;
}
.square_fill[tunnel=yes][zoom>=11][zoom<14] {
  polygon-fill: #a7a7a7;
}
.square_fill[zoom>=14] { 
  line-join: round;
  line-cap: round;
}
.square_fill[highway_poly_type=pedestrian][tunnel=yes][zoom>=14] {
  polygon-fill: #a7a7a7;
  line-color: #a7a7a7;
}
.square_fill[highway_poly_type=pedestrian][zoom>=14] {
  polygon-fill: #cdcdcd;
  line-color: #cdcdcd;
}
.square_fill[highway_poly_type=parking][zoom>=10][zoom<14] {
  polygon-fill: #e0e0e0;
}
.square_fill[highway_poly_type=parking][tunnel=yes][zoom>=14] {
  polygon-fill: #b7c6d1;
  line-color: #b7c6d1;
}
.square_fill[highway_poly_type=parking][zoom>=14] {
  polygon-fill: #e0e0e0;
  line-color: #e0e0e0;
}
.square_fill[zoom>=14][zoom<15] {
  line-width: 3;
}
.square_fill[zoom>=15][zoom<16] {
  line-width: 6;
}
.square_fill[zoom>=16] {
  line-width: 9;
}
.square_casing[zoom>=14] {
  polygon-fill: #cdcdcd;
  line-color: #a0a0a0;
  line-join: round;
  line-cap: round;
}
.square_casing[bridge=yes] {
  line-color: #000000;
}
.square_casing[tunnel=yes][zoom>=14] {
  line-dasharray: 5,5;
  polygon-fill: #a7a7a7;
}
.square_casing[zoom>=14][zoom<15] {
  line-width: 5;
}
.square_casing[zoom>=15][zoom<16] {
  line-width: 8;
}
.square_casing[zoom>=16] {
  line-width: 12;
}

.roads_rail[railway=tram],
.roads_rail[railway=rail] {
  line-join: round;
  line-cap: butt;
}
.roads_rail[railway=tram] {
  line-color: #707070;
}
.roads_extract[highway_type=railway][highway_sub_type=t2],
.roads_rail[railway=rail] {
  line-color: #404040;
}
.roads_extract[highway_type=railway][highway_sub_type=t2][zoom>=6][zoom<8],
.roads_rail[railway=tram][zoom>=11][zoom<13] {
  line-width: 0.5;
}
.roads_extract[highway_type=railway][highway_sub_type=t2][zoom>=8][zoom<10],
.roads_rail[railway=tram][zoom>=13][zoom<15] {
  line-width: 1;
}
.roads_rail[railway=tram][tracks=single][zoom>=15] {
  line-pattern-file: url('img/tram_single.png');
}
.roads_rail[railway=tram][tracks=left][zoom>=15] {
  line-pattern-file: url('img/tram_left.png');
}
.roads_rail[railway=tram][tracks=right][zoom>=15] {
  line-pattern-file: url('img/tram_right.png');
}
.roads_rail[railway=tram][tracks=double][zoom>=15],
.roads_rail[railway=tram][tracks=multiple][zoom>=15] {
  line-pattern-file: url('img/tram_double.png');
}

.roads_rail[railway=rail][zoom>=10][zoom<13] {
  line-pattern-file: url('img/rail_single1.png');
}
.roads_rail[railway=rail][tracks=single][zoom>=13][zoom<15]  {
  line-pattern-file: url('img/rail_single1.png');
}
.roads_rail[railway=rail][tracks=single][zoom>=15] {
  line-pattern-file: url('img/rail_single2.png');
}
.roads_rail[railway=rail][tracks=double][zoom>=13][zoom<15],
.roads_rail[railway=rail][tracks=multiple][zoom>=13][zoom<15] {
  line-pattern-file: url('img/rail_double1.png');
}
.roads_rail[railway=rail][tracks=double][zoom>=15],
.roads_rail[railway=rail][tracks=multiple][zoom>=15] {
  line-pattern-file: url('img/rail_double2.png');
}
.roads_rail[railway=tram][tunnel=yes][tracks=single][zoom>=15] {
  line-pattern-file: url('img/tram_tunnel_single.png');
}
.roads_rail[railway=tram][tunnel=yes][tracks=left][zoom>=15] {
  line-pattern-file: url('img/tram_tunnel_left.png');
}
.roads_rail[railway=tram][tunnel=yes][tracks=right][zoom>=15] {
  line-pattern-file: url('img/tram_tunnel_right.png');
}
.roads_rail[railway=tram][tunnel=yes][tracks=double][zoom>=15],
.roads_rail[railway=tram][tunnel=yes][tracks=multiple][zoom>=15] {
  line-pattern-file: url('img/tram_tunnel_double.png');
}
.roads_rail[railway=rail][tunnel=yes][zoom>=10][zoom<13] {
  line-pattern-file: url('img/rail_tunnel_single1.png');
}
.roads_rail[railway=rail][tunnel=yes][tracks=single][zoom>=13][zoom<15]  {
  line-pattern-file: url('img/rail_tunnel_single1.png');
}
.roads_rail[railway=rail][tunnel=yes][tracks=single][zoom>=15] {
  line-pattern-file: url('img/rail_tunnel_single2.png');
}
.roads_rail[railway=rail][tunnel=yes][tracks=double][zoom>=13][zoom<15],
.roads_rail[railway=rail][tunnel=yes][tracks=multiple][zoom>=13][zoom<15] {
  line-pattern-file: url('img/rail_tunnel_double1.png');
}
.roads_rail[railway=rail][tunnel=yes][tracks=double][zoom>=15],
.roads_rail[railway=rail][tunnel=yes][tracks=multiple][zoom>=15] {
  line-pattern-file: url('img/rail_tunnel_double2.png');
}
.roads_casing_end[highway_type=barrier][zoom>=14][zoom<17],
.roads_casing[highway_type=barrier][zoom>=14][zoom<17] {
  line-width: 2;
}
.roads_fill[highway_type=barrier][zoom>=14][zoom<17] {
  line-width: 1;
}
.roads_casing_end[highway_type=barrier][zoom>=17],
.roads_casing[highway_type=barrier][zoom>=17] {
  line-width: 4;
}
.roads_fill[highway_type=barrier][zoom>=17] {
  line-width: 3;
}
.roads_extract[highway_type=power][highway_sub_type=t1][zoom>=5][zoom<8],
.roads_extract[highway_type=power][highway_sub_type=t2][zoom>=8][zoom<10],
.roads_fill[highway_type=power][highway_sub_type=t3][zoom>=11][zoom<13],
.roads_fill[highway_type=power][highway_sub_type=t4][zoom>=13][zoom<15],
.roads_fill[highway_type=power][highway_sub_type=t5][zoom>=15][zoom<16],
.roads_fill[highway_type=power][highway_sub_type=t6][zoom>=17][zoom<18] {
  line-width: 0.5;
  line-opacity: 0.8;
}
.roads_extract[highway_type=power][highway_sub_type=t1][zoom>=8][zoom<10],
.roads_fill[highway_type=power][highway_sub_type=t1][zoom>=10][zoom<13],
.roads_fill[highway_type=power][highway_sub_type=t2][zoom>=10][zoom<13],
.roads_fill[highway_type=power][highway_sub_type=t3][zoom>=13][zoom<15],
.roads_fill[highway_type=power][highway_sub_type=t4][zoom>=15],
.roads_fill[highway_type=power][highway_sub_type=t5][zoom>=16],
.roads_fill[highway_type=power][highway_sub_type=t6][zoom>=18] {
  line-width: 1;
  line-opacity: 0.6;
}
.roads_fill[highway_type=power][highway_sub_type=t1][zoom>=13],
.roads_fill[highway_type=power][highway_sub_type=t2][zoom>=13],
.roads_fill[highway_type=power][highway_sub_type=t3][zoom>=15] {
  line-width: 2;
  line-opacity: 0.6;
}
.roads_extract[highway_type=pipeline],
.roads_casing_end[highway_type=pipeline],
.roads_casing[highway_type=pipeline] {
  line-color: #00009f;
}
.roads_casing_end[highway_type=pipeline][tunnel=yes],
.roads_casing[highway_type=pipeline][tunnel=yes] {
  line-color: #6b6b9f;
}
.roads_extract[highway_type=pipeline][zoom>=7][zoom<10] {
  line-width: 0.5;
}
.roads_casing_end[highway_type=pipeline][zoom>=10][zoom<13],
.roads_casing[highway_type=pipeline][zoom>=10][zoom<13] {
  line-width: 1;
}
.roads_casing_end[highway_type=pipeline][zoom>=13][zoom<15],
.roads_casing[highway_type=pipeline][zoom>=13][zoom<15] {
  line-width: 3;
}
.roads_casing_end[highway_type=pipeline][zoom>=15][zoom<17],
.roads_casing[highway_type=pipeline][zoom>=15][zoom<17] {
  line-width: 4;
}
.roads_casing_end[highway_type=pipeline][zoom>=17],
.roads_casing[highway_type=pipeline][zoom>=17] {
  line-width: 5;
}
.roads_fill[highway_type=pipeline][highway_sub_type=t1] {
  line-color: #0000ff;
}
.roads_fill[highway_type=pipeline][highway_sub_type=t2] {
  line-color: #000000;
}
.roads_fill[highway_type=pipeline][highway_sub_type=t3] {
  line-color: #00e000;
}
.roads_fill[highway_type=pipeline][highway_sub_type=t4] {
  line-color: #647718;
}
.roads_fill[highway_type=pipeline][highway_sub_type=t5] {
  line-color: #ff0000;
}
.roads_fill[highway_type=pipeline][zoom>=13][zoom<15] {
  line-width: 1;
  line-dasharray: 3,3;
}
.roads_fill[highway_type=pipeline][zoom>=15][zoom<17] {
  line-width: 1.5;
  line-dasharray: 4,4;
}
.roads_fill[highway_type=pipeline][zoom>=17] {
  line-width: 2;
  line-dasharray: 5,5;
}


.roads_fillX[zoom>=15] text {
  text-face-name: "DejaVu Sans Book";
  text-placement: line;
  text-min-distance: 0;
  text-spacing: 1;
  text-fill: #000000;
  text-size: 10;
}
.roads_fillX[zoom>=13] text {
  text-face-name: "DejaVu Sans Book";
  text-placement: line;
  text-min-distance: 0;
  text-spacing: 1;
  text-fill: #000000;
  text-size: 5;
}
.roads_extract[highway_type=waterway][highway_sub_type=t1][zoom>=5][zoom<8],
.roads_extract[highway_type=waterway][highway_sub_type=t2][zoom>=6][zoom<9],
.roads_casing[highway_type=waterway][highway_sub_type=t3][zoom>=10][zoom<11] {
  line-width: 0.5;
}
.roads_extract[highway_type=waterway][highway_sub_type=t1][zoom>=8][zoom<10],
.roads_extract[highway_type=waterway][highway_sub_type=t2][zoom>=9][zoom<10],
.roads_casing[highway_type=waterway][highway_sub_type=t2][zoom>=10][zoom<12],
.roads_casing[highway_type=waterway][highway_sub_type=t3][zoom>=12][zoom<14] {
  line-width: 1;
}
.roads_casing[highway_type=waterway][highway_sub_type=t1][zoom>=10][zoom<14],
.roads_casing[highway_type=waterway][highway_sub_type=t2][zoom>=12][zoom<15],
.roads_casing[highway_type=waterway][highway_sub_type=t3][zoom>=14] {
  line-width: 2;
}
.roads_casing[highway_type=waterway][highway_sub_type=t1][zoom>=14][zoom<15],
.roads_casing[highway_type=waterway][highway_sub_type=t2][zoom>=15][zoom<16] {
  line-width: 5;
}
.roads_casing[highway_type=waterway][highway_sub_type=t1][zoom>=15][zoom<16],
.roads_casing[highway_type=waterway][highway_sub_type=t2][zoom>=16] {
  line-width: 8;
}
.roads_casing[highway_type=waterway][highway_sub_type=t1][zoom>=16][zoom<17] {
  line-width: 12;
}
.roads_casing[highway_type=waterway][highway_sub_type=t1][zoom>=17][zoom<18] {
  line-width: 20;
}
.roads_casing[highway_type=waterway][highway_sub_type=t1][zoom>=18] {
  line-width: 36;
}
.roads_text[highway_type=waterway] {
  text-spacing: 300;
  text-halo-fill: #7eb9e3;
  text-fill: #000000;
}
.roads_text[highway_type=waterway][highway_sub_type=t1][zoom>=12][zoom<13] name,
.roads_text[highway_type=waterway][highway_sub_type=t2][zoom>=13][zoom<15] name,
.roads_text[highway_type=waterway][highway_sub_type=t3][zoom>=15][zoom<17] name {
  text-size: 8;
}
.roads_text[highway_type=waterway][highway_sub_type=t1][zoom>=13] name,
.roads_text[highway_type=waterway][highway_sub_type=t2][zoom>=15] name,
.roads_text[highway_type=waterway][highway_sub_type=t3][zoom>=17] name {
  text-size: 10;
}
