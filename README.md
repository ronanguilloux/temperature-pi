temperature-pi
==============

**temperature-pi** is a simple Raspberry Pi based temperature logger using a DS18B20 1-Wire digital temperature sensor, & a local sqlite database.
It's based on the [php-gpio](https://github.com/ronanguilloux/php-gpio) PHP library

![DS18B20+Resistor](http://robotics.org.za/image/cache/data/Sensor/temperature/af00374-250x250.jpg)

![DS18B20 mounting schema](http://pi-io.com/wp-content/uploads/2012/11/ds18b20.jpeg)


Installation (hardware)
-----------------------

First, install the DS18B20 on your bread board, wired to the #4 gpio pin: 

![DS18B20 sensor connection](http://www.cl.cam.ac.uk/freshers/raspberrypi/tutorials/temperature/sensor-connection.png) 

See this very easy-reading [tutorial](http://www.cl.cam.ac.uk/freshers/raspberrypi/tutorials/temperature) on the Cambridge University CompSci Laboratory Raspberry Pi dedicated pages.

Then add a led & a resistor & wired it to the #17 gpio pin.


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

Then allow your raspberry pi to blink the #17 gpio pin wired LED without sudo.

First, add a symlink to the blinker (see ronanguilloux/php-gpio)

    $ ln -s vendor/ronanguilloux/php-gpio/blinker.php blinker.php

Then allow the blinker to be run without sudo. Edit your `/etc/sudoers` file:

``` bash
$ sudo visudo
```

Add this two lines in your `/etc/sudoers` file : (replace MyLinuxUser with your login name)
This will allow you and Apache2 to run the blinker without `sudo`

``` bash
MyLinuxUser ALL=NOPASSWD: /path/to/blinker.php
www-data ALL=NOPASSWD: /path/to/blinker.php
```


Set up the sensor
-----------------

Add kernel modules from the Linux Kernel:

``` bash
$ sudo modprobe w1-gpio
$ sudo modprobe w1-therm
```

Run a cronjob & log temperatures
--------------------------------

Run the executable php file to record temperatures
``` bash
$ thermometer
```

This previous command line can easely be added in your crontab to log the temperature through the day (and night)

``` cron
30 * * * * /my/path/to/the/temperature-pi/thermometer >> /my/path/to/the/temperature-pi/resources/log
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
