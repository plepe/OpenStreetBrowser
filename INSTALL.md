== Initialize database ==
You need PostgreSQL 9.0 and PostGIS 1.5 installed.
First run 'bin/init' to initialize database structure

Install:
```sh
apt-get install php5-curl
```

== Configure ==
Copy conf.php-dist to conf.php and adapt to your needs.
Especially choose modules appropriate for your project.

== OpenStreetBrowser-Daemon ==
Then run 'bin/osbd' which will initialize the rest and will take care of a
clean database. It also re-compiles categories and similar stuff.

There's a tiny command line interface. Supported commands:
  status	Print status of all modules which support it
  stop		Shutdown daemon
  quit		Force daemon shutdown

'MCP' is an alias for the OpenStreetBrowser-Daemon, used internally

== Libraries ==
```sh
git clone https://github.com/bermi/eventify.git lib/eventify
git clone git://github.com/twigphp/Twig.git lib/Twig
git clone https://github.com/justjohn/twig.js lib/twig.js/
git clone https://github.com/plepe/ol4pgm.git lib/ol4pgm

# ol3
wget https://github.com/openlayers/ol3/releases/download/v3.4.0/v3.4.0-dist.zip
unzip -d lib/ v3.4.0-dist.zip
mv lib/v3.4.0-dist lib/ol3
v3.4.0-dist.zip

wget -O lib/jquery.min.js http://code.jquery.com/jquery-2.1.3.min.js
```

Other stuff:
```sh
# create icons directory -> make writeable for web server
mkdir icons
chmod 777 icons
```
