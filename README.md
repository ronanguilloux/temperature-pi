temperature-pi
==============

**temperature-pi** is a simple Raspberry Pi based temperature logger using a DS18B20 1-Wire digital temperature sensor, & a local sqlite database.
It's based on the [php-gpio](https://github.com/ronanguilloux/php-gpio) PHP library

![DS18B20+Resistor](http://robotics.org.za/image/cache/data/Sensor/temperature/af00374-250x250.jpg)


Installation (hardware)
-----------------------

![DS18B20 sensor connection](http://www.cl.cam.ac.uk/freshers/raspberrypi/tutorials/temperature/sensor-connection.png) 

Read this very easy-reading [tutorial](http://www.cl.cam.ac.uk/freshers/raspberrypi/tutorials/temperature) on the Cambridge University CompSci Laboratory Raspberry Pi dedicated pages.

Then install the DS18B20 on your bread board, wired to the #4 gpio pin, following the tutorial indications.

Then add a led & a resistor wired to the #17 gpio pin.

![Circuit snapshot](https://raw.github.com/ronanguilloux/temperature-pi/master/resources/images/mounting.jpg)

Installation (software)
-----------------------

The recommended way to install temperature-pi is through [composer](http://getcomposer.org).

Just create a `composer.json` file for your project:

``` json
{
     "require": {
        "php": ">=5.3.0",
        "ronanguilloux/temperature-pi": "master-dev"
    }
}
```

And run these two commands to install it:

``` bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

Oprionnaly, allow the `vendor/ronanguilloux/php-gpio/blinker` file to be run without sudo:
Edit your `/etc/sudoers` file:

``` bash
$ sudo visudo
```

Then add this two lines in your `/etc/sudoers` file : 
(replace MyLinuxUser with your login name & change the path to the blinker)
This will allow you and Apache2 to run the blinker without `sudo`

``` bash
MyLinuxUser ALL=NOPASSWD: /path/to/blinker
www-data ALL=NOPASSWD: /path/to/blinker
```


Set up the sensor
-----------------

Using the shell, manually add theses kernel modules:

``` bash
$ sudo modprobe w1-gpio
$ sudo modprobe w1-therm
```

To load such kernel modules automatically at boot time, edit the `/etc/modules` file & add these two lines:

```
w1-gpio
w1-therm
```


Run a cronjob & log temperatures
--------------------------------

Run the executable php file to record temperatures
``` bash
$ thermometer
```

To trace temperatures chages, add this into your crontab to log the temperature through the day, each 30 minutes

``` cron
0,30 * * * * /my/path/to/the/temperature-pi/thermometer >> /my/path/to/the/temperature-pi/resources/log
```

Get a graph
-----------

To get the graph, run this app as a webserver
``` bash
$ php -S "`hostname -I`:8080" -t web/
```

![DS18B20+Resistor](https://raw.github.com/ronanguilloux/temperature-pi/master/example.png)


Credits
-------

* Ronan Guilloux <ronan.guilloux@gmail.com>
* [All contributors](https://github.com/ronanguilloux/temperature-pi/contributors)
* Images in the README.md : pi-io.com, robotics.org.za, Cambridge University


License
-------

**temperature-pi** is released under the MIT License. See the bundled LICENSE file for details.
You can find a copy of this software here: https://github.com/ronanguilloux/temperature-pi
