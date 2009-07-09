.routes[importance=international][zoom>=6],
.routes[importance=national][zoom>=7],
.routes[importance=regional][zoom>=8],
.routes[importance=urban][zoom>=13],
.routes[importance=suburban][zoom>=13],
.routes[importance=local][zoom>=13]
{
  line-width: 2;
}
.routes[importance=international][zoom>=3][zoom<6],
.routes[importance=national][zoom>=5][zoom<7],
.routes[importance=regional][zoom>=7][zoom<8],
.routes[importance=urban][zoom>=9][zoom<13],
.routes[importance=suburban][zoom>=9][zoom<13],
.routes[importance=local][zoom>=9][zoom<13]
{
  line-width: 1;
}
.routes[route=bicycle],
.routestext[route=bicycle] ref
{
  line-color: #68ad03;
  text-fill: #68ad03;
}
.routes[route=hiking] ref,
.routestext[route=hiking] ref
{
  line-color: #935d16;
  text-fill: #935d16;
}
.routes[route=foot] ref,
.routestext[route=foot] ref
{
  line-color: #c65d16;
  text-fill: #c65d16;
}
.routes[route=mtb] ref,
.routestext[route=mtb] ref
{
  line-color: #3a5d16;
  text-fill: #3a5d16;
}
.routestext[importance=international][zoom>=6][zoom<7] ref,
.routestext[importance=national][zoom>=7][zoom<8] ref,
.routestext[importance=regional][zoom>=8][zoom<13] ref,
.routestext[importance=urban][zoom>=13][zoom<14] ref,
.routestext[importance=suburban][zoom>=13][zoom<15] ref,
.routestext[importance=local][zoom>=16] ref
{
  text-face-name: "DejaVu Sans Book";
  text-size: 10;
  text-placement: line;
  text-avoid-edges: false;
  text-halo-radius: 1;
  text-min-distance: 20;
  text-spacing: 150;
}

