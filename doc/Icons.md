#### Font Awesome Icons
Font Awesome 4 is included in OpenStreetBrowser, therefore you can use, e.g.:
```html
<i class="fa fa-compass" aria-hidden="true"></i>
```

You can use normal CSS to modify its look, e.g.
```html
<i style="color: red;" class="fa fa-compass" aria-hidden="true"></i>
```

#### Mapbox Maki Icons
Mapbox Maki Icons 4 are also included in OpenStreetBrowser. They can be accessed as images with protocol 'maki', e.g.:
```html
<img src="maki:park">
```

This will include the park-15.svg icon. Mapbox Maki icons come in two sizes: 11 and 15. Default is 15, if you want to use 11 pass the size parameter with value 11:
```html
<img src="maki:park?size=11">
```

You can pass SVG options to the icon to modify its look. Note that every icon is a path:
```html
<img src="maki:park?size=11&amp;fill=red&amp;stroke=black&amp;stroke-width=0.5">
```
