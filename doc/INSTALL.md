These install instructions are tested on a plain Ubuntu 22 or Debian 11 Server installation.

You either need to [install a modern nodejs version](https://nodejs.org/en/download/package-manager/#debian-and-ubuntu-based-linux-distributions)
or replace the `openstreetbrowser.min.js` with `openstreetbrowser.js` in `index.html`.

```sh
sudo apt install apache2 libapache2-mod-php curl git php-cli composer nodejs npm php-curl php-yaml
sudo chmod 777 /var/www/html
cd /var/www/html
git clone https://github.com/plepe/openstreetbrowser.git
cd openstreetbrowser
npm install
composer install
git submodule update --init
cp conf.php-dist conf.php
nano conf.php
mkdir data
bin/download_dependencies
```

For improved performance you should also run:
```sh
modulekit/build_cache
```

Have fun on http://localhost/openstreetbrowser which is now served via apache from php!

# Debugging

For debugging add the following line to conf.php:
```php
$modulekit_nocache = true;
```

And then run:
```sh
npm run watch
```

This is very similar to `npm run build`,
but watches JavaScript files for changes and updates the dist/openstreetbrowser.js file automatically.
It also adds debugging information to the final JavaScript file.
