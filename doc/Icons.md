#### Unicode Icons
Unicode defines many icons which can be used either directly or via their HTML codes, e.g.:
```html
&#127874; &#x1f382; 🎂
```

A drawback of Unicode icons is, that the display will differ from system to system as they depend on the available Fonts.

#### Self defined icons
You may upload images to your repository and use them via a relative image link:
```html
<img src='img/foobar.svg'>
```

This will include the image from your repository (when uploaded to your 'img' directory).

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

You can pass URL options to the icon to modify its look. Note that every icon is a [SVG path](https://developer.mozilla.org/en-US/docs/Web/SVG/Tutorial/Paths) and all [style options](https://developer.mozilla.org/de/docs/Web/SVG/Tutorial/Fills_and_Strokes) are available:
```html
<img src="maki:park?size=11&amp;fill=red&amp;stroke=black&amp;stroke-width=0.5">
```
