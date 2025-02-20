# How to add an additional language to OpenStreetBrowser
Assume the language code 'xy'.

## Update modulekit-lang
Clone [modulekit-lang](https://github.com/plepe/modulekit-lang)

Run on the shell:
```sh
git clone https://github.com/plepe/modulekit-lang
cd modulekit-lang
git checkout lang-options
./bin/import_cldr xy
git add lang/xy.json lang/list.json
git commit -m "Adding language xy"
git push
```

## Update OpenStreetBrowser
Clone [OpenStreetBrowser](https://github.com/plepe/modulekit-lang)

Run on the shell:
```sh
git clone https://github.com/plepe/OpenStreetBrowser
cd OpenStreetBrowser

cd lib/modulekit/lang
git pull origin lang-options
cd ../../..
git add lib/modulekit/lang

cp locales/tr.js locales/xy.js
nano locales/xy.js
# replace all 'tr' by 'xy'
git add locales/th.js

nano conf.php-dist
# Enter an entry for the language to the list of available languages
git add conf.php-dist

git commit -m "Adding language xy"
```
