<?php

namespace TemperaturePi;

use PhpGpio\Sensors\DS18B20;

class Logger extends \SQlite3
{
    private $sensor;
    private $currentTemperature;
    private $dbPath;
    private $db;

    public function __construct()
    {
        $this->sensor = new DS18B20();
        $this->dbPath = __DIR__ . '/../../resources/sqlite/log.db';
        $this->db = 'temperature';
    }

    public function persist($reset = false)
    {
        $this->currentTemperature = $this->sensor->read();
        $this->open($this->dbPath);
        if($reset) {
            $this->exec('DROP TABLE temperature');
        }
        $this->exec('CREATE TABLE IF NOT EXISTS temperature (datetime DATETIME, celsius FLOAT)');
        $this->exec("INSERT INTO temperature (datetime, celsius) VALUES (datetime('NOW'), " . $this->currentTemperature . ")");
        /*
        $result = $this->query('SELECT datetime, celsius FROM temperature');
        while ($row = $result->fetchArray()) {
            var_dump($row);
        }
        */
        echo "\n" . date("d/m/Y H:i:s") . " : " . $this->currentTemperature;
        $this->close();
    }

}
