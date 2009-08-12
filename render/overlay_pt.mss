.routestext[highway_type=motorway]   
{ text-halo-fill: #ff6838; }
.routestext[highway_type=motorway_link] 
{ text-halo-fill: #ff6838; }
.routestext[highway_type=trunk]      
{ text-halo-fill: #ff8a00; }
.routestext[highway_type=trunk_link] 
{ text-halo-fill: #ff8a00; }
.routestext[highway_type=primary]    
{ text-halo-fill: #ffe800; }
.routestext[highway_type=secondary]  
{ text-halo-fill: #ffef4c; }
.routestext[highway_type=tertiary]   
{ text-halo-fill: #fff8b5; }
.routestext[highway_type=minor]      
{ text-halo-fill: #ffffff; }
.routestext[highway_type=service]    
{ text-halo-fill: #ffffff; }
.routestext[highway_type=pedestrian] 
{ text-halo-fill: #cdcdcd; }
.routes[route=rail],
.routes[route=railway],
.routes[route=train],
.routestext[route=rail] ref,
.routestext[route=railway] ref,
.routestext[route=train] ref
{
  line-color: #202020;
  text-fill: #202020;
}
.routes[route=subway],
.routestext[route=subway] ref
{
  line-color: #d4009f;
  text-fill: #d4009f;
}
.routes[route=tram],
.routestext[route=tram] ref
{
  text-fill: #ff0000;
  line-color: #ff0000;
}
.routes[route=ferry],
.routestext[route=ferry] ref
{
  line-color: #00ffff;
  text-fill: #00ffff;
}
.routes[route=bus],
.routestext[route=bus] ref
{
  line-color: #0000ff;
  text-fill: #0000ff;
}
.routes[route=tram_bus],
.routestext[route=tram_bus] ref
{
  line-color: #be007f;
  text-fill: #be007f;
}
.routes[tunnel=yes],
.routestext[tunnel=yes] ref {
  line-dasharray: 5,5;
  text-halo-radius: 0;
}

.routes[importance=international][zoom>=6],
.routes[importance=national][zoom>=7],
.routes[importance=regional][zoom>=8],
.routes[importance=urban][zoom>=11],
.routes[importance=suburban][zoom>=14],
.routes[importance=local][zoom>=16]
{
  line-width: 2;
}
.routes[dir!=both][importance=international][zoom>=6],
.routes[dir!=both][importance=national][zoom>=7],
.routes[dir!=both][importance=regional][zoom>=8],
.routes[dir!=both][importance=urban][zoom>=11],
.routes[dir!=both][importance=suburban][zoom>=14],
.routes[dir!=both][importance=local][zoom>=16]
{
  line-width: 1;
}
.routes[importance=international][zoom>=3][zoom<6],
.routes[importance=national][zoom>=5][zoom<7],
.routes[importance=regional][zoom>=7][zoom<8],
.routes[importance=urban][zoom>=9][zoom<11],
.routes[importance=suburban][zoom>=12][zoom<14],
.routes[importance=local][zoom>=14][zoom<16]
{
  line-width: 1;
}
.routes[importance=national][zoom>=4][zoom<5],
.routes[importance=regional][zoom>=6][zoom<7],
.routes[importance=urban][zoom>=8][zoom<9],
.routes[importance=suburban][zoom>=10][zoom<12],
.routes[importance=local][zoom>=12][zoom<14]
{
  line-width: 0.5;
}
.routes_features[route=tram][dir=forward][importance=international][zoom>=6],
.routes_features[route=tram][dir=forward][importance=national][zoom>=7],
.routes_features[route=tram][dir=forward][importance=regional][zoom>=8],
.routes_features[route=tram][dir=forward][importance=urban][zoom>=11],
.routes_features[route=tram][dir=forward][importance=suburban][zoom>=14],
.routes_features[route=tram][dir=forward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/tram_forward.png');
}
.routes_features[route=tram][dir=backward][importance=international][zoom>=6],
.routes_features[route=tram][dir=backward][importance=national][zoom>=7],
.routes_features[route=tram][dir=backward][importance=regional][zoom>=8],
.routes_features[route=tram][dir=backward][importance=urban][zoom>=11],
.routes_features[route=tram][dir=backward][importance=suburban][zoom>=14],
.routes_features[route=tram][dir=backward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/tram_backward.png');
}
.routes_features[route=bus][dir=forward][importance=international][zoom>=6],
.routes_features[route=bus][dir=forward][importance=national][zoom>=7],
.routes_features[route=bus][dir=forward][importance=regional][zoom>=8],
.routes_features[route=bus][dir=forward][importance=urban][zoom>=11],
.routes_features[route=bus][dir=forward][importance=suburban][zoom>=14],
.routes_features[route=bus][dir=forward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/bus_forward.png');
}
.routes_features[route=bus][dir=backward][importance=international][zoom>=6],
.routes_features[route=bus][dir=backward][importance=national][zoom>=7],
.routes_features[route=bus][dir=backward][importance=regional][zoom>=8],
.routes_features[route=bus][dir=backward][importance=urban][zoom>=11],
.routes_features[route=bus][dir=backward][importance=suburban][zoom>=14],
.routes_features[route=bus][dir=backward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/bus_backward.png');
}
.routes_features[route=tram_bus][dir=forward][importance=international][zoom>=6],
.routes_features[route=tram_bus][dir=forward][importance=national][zoom>=7],
.routes_features[route=tram_bus][dir=forward][importance=regional][zoom>=8],
.routes_features[route=tram_bus][dir=forward][importance=urban][zoom>=11],
.routes_features[route=tram_bus][dir=forward][importance=suburban][zoom>=14],
.routes_features[route=tram_bus][dir=forward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/tram_bus_forward.png');
}
.routes_features[route=tram_bus][dir=backward][importance=international][zoom>=6],
.routes_features[route=tram_bus][dir=backward][importance=national][zoom>=7],
.routes_features[route=tram_bus][dir=backward][importance=regional][zoom>=8],
.routes_features[route=tram_bus][dir=backward][importance=urban][zoom>=11],
.routes_features[route=tram_bus][dir=backward][importance=suburban][zoom>=14],
.routes_features[route=tram_bus][dir=backward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/tram_bus_backward.png');
}
.routes_features[route=subway][dir=forward][importance=international][zoom>=6],
.routes_features[route=subway][dir=forward][importance=national][zoom>=7],
.routes_features[route=subway][dir=forward][importance=regional][zoom>=8],
.routes_features[route=subway][dir=forward][importance=urban][zoom>=11],
.routes_features[route=subway][dir=forward][importance=suburban][zoom>=14],
.routes_features[route=subway][dir=forward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/subway_forward.png');
}
.routes_features[route=subway][dir=backward][importance=international][zoom>=6],
.routes_features[route=subway][dir=backward][importance=national][zoom>=7],
.routes_features[route=subway][dir=backward][importance=regional][zoom>=8],
.routes_features[route=subway][dir=backward][importance=urban][zoom>=11],
.routes_features[route=subway][dir=backward][importance=suburban][zoom>=14],
.routes_features[route=subway][dir=backward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/subway_backward.png');
}
.routes_features[route=ferry][dir=forward][importance=international][zoom>=6],
.routes_features[route=ferry][dir=forward][importance=national][zoom>=7],
.routes_features[route=ferry][dir=forward][importance=regional][zoom>=8],
.routes_features[route=ferry][dir=forward][importance=urban][zoom>=11],
.routes_features[route=ferry][dir=forward][importance=suburban][zoom>=14],
.routes_features[route=ferry][dir=forward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/ferry_forward.png');
}
.routes_features[route=ferry][dir=backward][importance=international][zoom>=6],
.routes_features[route=ferry][dir=backward][importance=national][zoom>=7],
.routes_features[route=ferry][dir=backward][importance=regional][zoom>=8],
.routes_features[route=ferry][dir=backward][importance=urban][zoom>=11],
.routes_features[route=ferry][dir=backward][importance=suburban][zoom>=14],
.routes_features[route=ferry][dir=backward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/ferry_backward.png');
}
.routes_features[route=rail][dir=forward][importance=international][zoom>=6],
.routes_features[route=rail][dir=forward][importance=national][zoom>=7],
.routes_features[route=rail][dir=forward][importance=regional][zoom>=8],
.routes_features[route=rail][dir=forward][importance=urban][zoom>=11],
.routes_features[route=rail][dir=forward][importance=suburban][zoom>=14],
.routes_features[route=rail][dir=forward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/rail_forward.png');
}
.routes_features[route=rail][dir=backward][importance=international][zoom>=6],
.routes_features[route=rail][dir=backward][importance=national][zoom>=7],
.routes_features[route=rail][dir=backward][importance=regional][zoom>=8],
.routes_features[route=rail][dir=backward][importance=urban][zoom>=11],
.routes_features[route=rail][dir=backward][importance=suburban][zoom>=14],
.routes_features[route=rail][dir=backward][importance=local][zoom>=16]
{
  line-pattern-file: url('img/rail_backward.png');
}
.routes[route=rail],
.routes[route=railway],
.routes[route=train],
.routestext[route=rail] ref,
.routestext[route=railway] ref,
.routestext[route=train] ref
{
  line-color: #202020;
  text-fill: #202020;
}
.routes[route=tram],
.routes[route=light_rail],
.routestext[route=tram] ref,
.routestext[route=light_rail] ref
{
  line-color: #ff0000;
  text-fill: #ff0000;
}
.routes[route=ferry],
.routestext[route=ferry] ref
{
  line-color: #00ffff;
  text-fill: #00ffff;
}
.routes[route=bus],
.routestext[route=bus] ref
{
  line-color: #0000ff;
  text-fill: #0000ff;
}
.routestext[importance=international][zoom>=6][zoom<7] ref,
.routestext[importance=national][zoom>=7][zoom<8] ref,
.routestext[importance=regional][zoom>=8][zoom<11] ref,
.routestext[importance=urban][zoom>=11][zoom<13] ref,
.routestext[importance=suburban][zoom>=13][zoom<15] ref,
.routestext[importance=local][zoom>=15] ref
{
  text-face-name: "DejaVu Sans Book";
  text-size: 11;
  text-placement: line;
  text-avoid-edges: false;
  text-halo-radius: 1;
  text-min-distance: 20;
  text-spacing: 150;
  text-max-char-angle-delta: 20;
}
.stations_center[importance=local][zoom>=15][zoom<16] name,
.stations_center[importance=suburban][zoom>=14][zoom<16] name,
.stations_center[importance=urban][zoom>=13][zoom<14] name,
.stations_center[importance=regional][zoom>=11][zoom<12] name,
.stations_center[importance=national][zoom>=10][zoom<11] name,
.stations_center[importance=international][zoom>=9][zoom<10] name
{
  text-size: 8;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #007fff;
  text-avoid-edges: false;
  text-halo-radius: 1;
}
.stations_center[importance=urban][zoom>=14][zoom<15] name,
.stations_center[importance=regional][zoom>=13][zoom<15] name,
.stations_center[importance=national][zoom>=11][zoom<14] name,
.stations_center[importance=international][zoom>=10][zoom<14] name
{
  text-size: 10;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #007fff;
  text-avoid-edges: false;
  text-halo-radius: 1;
}
.stations_center[importance=local][zoom>=15][zoom<16],
.stations_center[importance=suburban][zoom>=14][zoom<16],
.stations_center[importance=urban][zoom>=13][zoom<14],
.stations_center[importance=regional][zoom>=11][zoom<12],
.stations_center[importance=national][zoom>=10][zoom<11],
.stations_center[importance=international][zoom>=9][zoom<10]
{
  point-file: url('img/hst_small.png');
  text-dy: -7;
  point-allow-overlap: false;
}
.stations_center[importance=urban][zoom>=14][zoom<15],
.stations_center[importance=regional][zoom>=12][zoom<15],
.stations_center[importance=national][zoom>=11][zoom<13],
.stations_center[importance=international][zoom>=10][zoom<12]
{
  point-file: url('img/hst_middle.png');
  text-dy: -9;
  point-allow-overlap: false;
}
.stations_center[importance=national][zoom>=13][zoom<14] name,
.stations_center[importance=international][zoom>=12][zoom<14] name
{
  point-file: url('img/hst_large.png');
  text-dy: -12;
  point-allow-overlap: false;
}
.stations_bbox[importance=local][zoom>=16],
.stations_bbox[importance=suburban][zoom>=16],
.stations_bbox[importance=urban][zoom>=15],
.stations_bbox[importance=regional][zoom>=15],
.stations_bbox[importance=national][zoom>=14],
.stations_bbox[importance=international][zoom>=14]
{
  line-pattern-file: url('img/hst_border.png');
  line-join: round;
}
.stations_top[importance=local][zoom>=16] name,
.stations_top[importance=suburban][zoom>=16] name,
.stations_top[importance=urban][zoom>=15] name,
.stations_top[importance=regional][zoom>=15] name,
.stations_top[importance=national][zoom>=14] name,
.stations_top[importance=international][zoom>=14] name
{
  text-size: 10;
  text-placement: point;
  text-face-name: "DejaVu Sans Book";
  text-fill: #007fff;
  text-avoid-edges: false;
  text-halo-radius: 1;
  text-dy: -13;
}
.stops_p[direction=forward][angle=72][zoom>=14][zoom<15][importance=international],
.stops_p[direction=forward][angle=72][zoom>=14][zoom<15][importance=national],
.stops_p[direction=forward][angle=72][zoom>=15][zoom<16][importance=regional],
.stops_p[direction=forward][angle=72][zoom>=15][zoom<16][importance=urban],
.stops_p[direction=forward][angle=72][zoom>=16][importance=suburban],
.stops_p[direction=forward][angle=72][zoom>=16][importance=local]
{
  point-file: url('img/rotate/stop_p_for_small.png');
  point-allow-overlap: true;
}
.stops_p[direction=backward][angle=72][zoom>=14][zoom<15][importance=international],
.stops_p[direction=backward][angle=72][zoom>=14][zoom<15][importance=national],
.stops_p[direction=backward][angle=72][zoom>=15][zoom<16][importance=regional],
.stops_p[direction=backward][angle=72][zoom>=15][zoom<16][importance=urban],
.stops_p[direction=backward][angle=72][zoom>=16][importance=suburban],
.stops_p[direction=backward][angle=72][zoom>=16][importance=local]
{
  point-file: url('img/rotate/stop_p_back_small.png');
  point-allow-overlap: true;
}
.stops_p[direction=forward][angle=72][zoom>=15][importance=international],
.stops_p[direction=forward][angle=72][zoom>=15][importance=national],
.stops_p[direction=forward][angle=72][zoom>=16][importance=regional],
.stops_p[direction=forward][angle=72][zoom>=16][importance=urban]
{
  point-file: url('img/rotate/stop_p_for_large.png');
  point-allow-overlap: true;
}
.stops_p[direction=backward][angle=72][zoom>=15][importance=international],
.stops_p[direction=backward][angle=72][zoom>=15][importance=national],
.stops_p[direction=backward][angle=72][zoom>=16][importance=regional],
.stops_p[direction=backward][angle=72][zoom>=16][importance=urban]
{
  point-file: url('img/rotate/stop_p_back_large.png');
  point-allow-overlap: true;
}
.stops_p[direction=both][zoom>=14][zoom<15][importance=international],
.stops_p[direction=both][zoom>=14][zoom<15][importance=national],
.stops_p[direction=both][zoom>=15][zoom<16][importance=regional],
.stops_p[direction=both][zoom>=15][zoom<16][importance=urban],
.stops_p[direction=both][zoom>=16][importance=suburban],
.stops_p[direction=both][zoom>=16][importance=local]
{
  point-file: url('img/rotate/stop_small.png');
}
.stops_p[direction=both][zoom>=14][zoom<15][importance=international],
.stops_p[direction=both][zoom>=14][zoom<15][importance=national],
.stops_p[direction=both][zoom>=15][zoom<16][importance=regional],
.stops_p[direction=both][zoom>=15][zoom<16][importance=urban],
.stops_p[direction=both][zoom>=16][importance=suburban],
.stops_p[direction=both][zoom>=16][importance=local]
{
  point-file: url('img/rotate/stop_large.png');
}
.stops_n[direction=forward][angle=72][zoom>=14][zoom<15][importance=international],
.stops_n[direction=forward][angle=72][zoom>=14][zoom<15][importance=national],
.stops_n[direction=forward][angle=72][zoom>=15][zoom<16][importance=regional],
.stops_n[direction=forward][angle=72][zoom>=15][zoom<16][importance=urban],
.stops_n[direction=forward][angle=72][zoom>=16][importance=suburban],
.stops_n[direction=forward][angle=72][zoom>=16][importance=local]
{
  point-file: url('img/rotate/stop_n_for_small.png');
  point-allow-overlap: true;
}
.stops_n[direction=backward][angle=72][zoom>=14][zoom<15][importance=international],
.stops_n[direction=backward][angle=72][zoom>=14][zoom<15][importance=national],
.stops_n[direction=backward][angle=72][zoom>=15][zoom<16][importance=regional],
.stops_n[direction=backward][angle=72][zoom>=15][zoom<16][importance=urban],
.stops_n[direction=backward][angle=72][zoom>=16][importance=suburban],
.stops_n[direction=backward][angle=72][zoom>=16][importance=local]
{
  point-file: url('img/rotate/stop_n_back_small.png');
  point-allow-overlap: true;
}
.stops_n[direction=forward][angle=72][zoom>=15][importance=international],
.stops_n[direction=forward][angle=72][zoom>=15][importance=national],
.stops_n[direction=forward][angle=72][zoom>=16][importance=regional],
.stops_n[direction=forward][angle=72][zoom>=16][importance=urban]
{
  point-file: url('img/rotate/stop_n_for_large.png');
  point-allow-overlap: true;
}
.stops_n[direction=backward][angle=72][zoom>=15][importance=international],
.stops_n[direction=backward][angle=72][zoom>=15][importance=national],
.stops_n[direction=backward][angle=72][zoom>=16][importance=regional],
.stops_n[direction=backward][angle=72][zoom>=16][importance=urban]
{
  point-file: url('img/rotate/stop_n_back_large.png');
  point-allow-overlap: true;
}
.stops_p[type=tram_stop_color_000000_FF0000],
.stops_n[type=tram_stop_color_000000_FF0000]
{
  point-allow-overlap: true;
}
.stops_p[type=bus_stop_color_000000_0000FF],
.stops_n[type=bus_stop_color_000000_0000FF]
{
  point-allow-overlap: true;
}
.stops_p[type=tram_bus_stop_color_000000_BE007F],
.stops_n[type=tram_bus_stop_color_000000_BE007F]
{
  point-allow-overlap: true;
}
.stops_p[type=subway_station_color_000000_d4009f],
.stops_n[type=subway_station_color_000000_d4009f]
{
  point-allow-overlap: true;
}
.stop_o[type=subway_entrance][zoom>=16]
{
  point-file: url('img/subway_entrance.png');
  point-allow-overlap: true;
}
