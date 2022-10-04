These install instructions assume a plain Ubuntu 22.04 server installation.

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

Browse to http://localhost/openstreetbrowser

Have fun!

# Debugging
For debugging you should add the following line to conf.php:
```php
$modulekit_nocache = true;
```

Also you should run:
```sh
npm run watch
```
This is very similar to `npm run build`, but watches JavaScript files for
changes and will update the dist/openstreetbrowser.js file. Also it will add
debugging information to the final JavaScript file.
