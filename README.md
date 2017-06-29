## INSTALL
```sh
git clone https://github.com/plepe/openstreetbrowser.git
cd openstreetbrowser
npm install
git submodule init
git submodule update
cp conf.php-dist conf.php
nano conf.php
npm run build
```

I plan to replace all PHP dependencies through JS replacements.

## API
```js
new OpenStreetBrowserCategory(query, options)
```
