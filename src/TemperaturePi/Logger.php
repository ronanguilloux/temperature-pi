<?php

namespace TemperaturePi;

use PhpGpio\Sensors\DS18B20();

class Logger
{
    private $sensor;
    private $currentTemperature;

    public function __construct()
    {
        $this->sensor = new DS18B20();
        $this->currentTemperature = sensor->read();
    }
}
