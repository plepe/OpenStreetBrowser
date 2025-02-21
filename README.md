# OpenStreetBrowser
OpenStreetBrowser is a web application where you can 'browse' the information from [OpenStreetMap](https://www.openstreetmap.org) in thematic categories. A large variety of categories is already available, additional categories can easily be created.

* Visit [OpenStreetBrowser](https://www.openstreetbrowser.org)
* Main repositories on Github: [Software & UI](https://github.com/plepe/OpenStreetBrowser), [Main Categories](https://github.com/plepe/openstreetbrowser-categories-main), [Tag Translations](https://github.com/plepe/openstreetmap-tag-translations)
* Translations: [Weblate](https://hosted.weblate.org/projects/openstreetbrowser/), [Statistics & List of contributors](https://openstreetbrowser.org/translations/)

## INSTALL
```sh
git clone https://github.com/plepe/openstreetbrowser.git
cd openstreetbrowser
npm install
composer install
git submodule init
git submodule update
cp conf.php-dist conf.php
nano conf.php
npm run build
modulekit/build_cache
bin/download_dependencies
```

=> [more detailed Installation instructions](doc/INSTALL.md)

### Upgrade
```sh
cd openstreetbrowser
git pull
npm update
git submodule init
git submodule update
npm run build
```

## DEVELOPMENT
### Develop categories
```
rm -r node_modules/openstreetbrowser-categories-main
git clone https://github.com/plepe/openstreetbrowser-categories-main.git node_modules/openstreetbrowser-categories-main
```
You are welcome to send pull requests via Github!

### Category definition
There are currently two types of categories: `index` (for sub categories) and `overpass` (for OpenStreetMap data, loaded via an Overpass API request). Each of them is defined via a JSON (old) or YAML (recommended) structure. They can be combined into a single file.

Check out the [tutorial](./doc/Tutorial.md)!

#### Category 'index'
File: dir.yaml
```yaml
type: index
subCategories:
  - id: foo
  - id: bar
    type: overpass
    query: node[amenity=bar]
```

This will define a category with the id 'dir' (from the file name) with two sub-categories: 'foo' (which will be loaded from the file `foo.yaml`) and 'bar' (which is defined inline as category of type 'overpass' and will show all nodes with the tag 'amenity' set to value 'bar' - see below for more details).

#### Category 'overpass'
File: foo.yaml
```yaml
type: overpass
query:
  12: (node[highway~'^(motorway_junction)$'];way[highway~'^(motorway|trunk)$'];)
  14: (node[highway~'^(motorway_junction|mini_roundabout|crossing)$'];way[highway~'^(motorway|trunk|primary)$'];)
  16: (node[highway];way[highway];)
feature:
  style:
    color: '{% if tags.highway == ''motorway'' %}#ff0000{% elseif tags.highway == ''trunk'' %}#ff7f00{% elseif tags.highway == ''primary'' %}#ffff00{% else %}#0000ff{% endif %}'
  markerSign: '{% if tags.highway == ''motorway_junction'' %}↗{% elseif tags.highway == ''mini_roundabout'' %}↻{% elseif tags.highway == ''crossing'' %}▤{% endif %}'
  title: '{{ localizedTag(tags, ''name'') |default(localizedTag(tags, ''operator'')) | default(localizedTag(tags, ''ref'')) | default(trans(''unnamed'')) }}'
  description: '{{ tagTrans(''highway'', tags.highway) }}'
  body: 'Foo value: {{ const.foo }}'
const:
  foo: foo value
```

This will define a category with the id 'foo' (from the file name). It will show some highway amenities, depending on the current zoom level.

If you want to know more, please check out the [tutorial](./doc/Tutorial.md). For a full list of parameters, see [the category parameters](doc/CategoryParameters.md).

All values in the "feature" section may use the [TwigJS language](doc/TwigJS.md) for evaluation.

### Hooks
With the function `register_hook` you can hook into several functions. The following hooks are available:

* `state-get`: modules can add values into the current state. Parameters: `state`: an object, which can be modified by modules.
* `state-apply`: when a state is applied to the app. Parameters: `state`: state which should be applied.
* `show-details`: called when details are being displayed. Parameters: data (see properties in doc/TwigJS.md), category, dom, callback.
* `hide-details`: called when the details page is left. No parameters.
* `show-popup`: called when a popup is being displayed. Parameters: data (see properties in doc/TwigJS.md), category, dom, callback.
* `options_save`: called when options are saved. Parameters: options (the new object), old_options (before save)
* `initFinish`: called when the app initialization finishes
* `fullscreen-activate`: called, wenn the fullscreen mode gets activated.
* `fullscreen-deactivate`: called, wenn the fullscreen mode gets deactivated.

### New locale
* Add language code to the `$languages` array in conf.php (and conf.php-dist)
* Create file `locales/CODE.js` with:
```js
global.locale = {
  id: 'CODE',
  moment: require('moment'),
  // replace 'en' by 'CODE', when a translation for date-format has been submitted
  osmDateFormatTemplates: require('openstreetmap-date-format/templates/en')
}

require('moment/locale/CODE')
```
* Run `npm run build-locales`
