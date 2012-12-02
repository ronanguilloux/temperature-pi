temperature-pi
==============

**temperature-pi** is a simple Raspberry Pi based temperature logger using a DS18B20 1-Wire digital temperature sensor, & a local sqlite database.
It's based on the [php-gpio](https://github.com/ronanguilloux/php-gpio) PHP library

[![sensor](http://avrlab.com/upload_files/ds18b20-dallas.JPG)]

Installation (hardware)
-----------------------

[![mounting schema](http://www.cl.cam.ac.uk/freshers/raspberrypi/tutorials/temperature/sensor-connection.png)]

See this [tutorial](http://www.cl.cam.ac.uk/freshers/raspberrypi/tutorials/temperature)
from the Cambridge University CompSci Laboratory Raspberry Pi dedicated Pages

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

Now you can add the autoloader, and you will have access to the library:

``` php
<?php

require 'vendor/autoload.php';
```

If you don't use neither **Composer** nor a _ClassLoader_ in your application, just require the provided autoloader:

``` php
<?php

require_once 'src/autoload.php';
```


Usage
-----

Add kernel modules from the Linux Kernel:

``` bash
$ sudo modprobe w1-gpio
$ sudo modprobe w1-therm
```

Run the executable php file to record temperatures
``` bash
$ thermometer
```

Run as a webserver to get the graph
``` bash
$ php -S "`hostname -I`:8080" -t web/
```


Credits
-------

* Ronan Guilloux <ronan.guilloux@gmail.com>
* [All contributors](https://github.com/ronanguilloux/temperature-pi/contributors)


License
-------

**temperature-pi** is released under the MIT License. See the bundled LICENSE file for details.
You can find a copy of this software here: https://github.com/ronanguilloux/temperature-pi
