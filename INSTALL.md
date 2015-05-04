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

# ol3
wget https://github.com/openlayers/ol3/archive/v3.4.0.tar.gz
mkdir lib/ol3
tar xz -C lib/ol3 --strip-components=1 -f v3.4.0.tar.gz
rm v3.4.0.tar.gz
```
