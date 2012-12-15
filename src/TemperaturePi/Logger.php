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

    public function fetchAll()
    {
        $return = array();
        $this->open($this->dbPath);
        $result = $this->query('SELECT datetime, celsius FROM temperature');
        while ($row = $result->fetchArray()) {
            //$return[$row['datetime']] = $row['celsius'];
            $return[] = $row['celsius'];
        }
        $this->close();

        return $return;
    }

    /**
     * Write a ./web/js/data.js json file (see ajax calls in ./web)
     * @return $this
     */
    public function writeJsDatas()
    {
        $jsFile = __DIR__ . "/../../web/js/data.js";
        $datas = $this->fetchAll();
        $jsContent = '{ "table": [["Date", "Celsius"]';
        foreach ($datas as $index=>$celsius) {
            $jsContent .= "\n,[$index,$celsius]";
        }
        $jsContent .= "]}";
        file_put_contents($jsFile, $jsContent);

        return $this;
    }

    public function persist($trace = false, $reset = false)
    {
        $this->currentTemperature = $this->sensor->read();
        if($trace){
            echo sprintf("%s : %s celsius\n", date('Y-m-d H:i:s'), $this->currentTemperature);
        }
        $this->open($this->dbPath);
        if ($reset) {
            $this->exec('DROP TABLE temperature');
        }
        $this->exec('CREATE TABLE IF NOT EXISTS temperature (datetime DATETIME, celsius FLOAT)');
        $this->exec("INSERT INTO temperature (datetime, celsius) VALUES (datetime('NOW'), " . $this->currentTemperature . ")");

        //echo "\n" . date("d/m/Y H:i:s") . "|" . $this->currentTemperature;
        $this->close();
    }

}
