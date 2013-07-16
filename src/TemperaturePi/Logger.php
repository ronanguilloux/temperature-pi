<?php

namespace TemperaturePi;

use PhpGpio\Sensors\DS18B20;
use PhpGpio\PhpGpio;

class Logger extends \SQlite3
{
    private $sensor;
    private $currentTemperature;
    private $dbPath;
    private $dataPath;
    private $db;

    public function __construct()
    {
        $this->sensor = new DS18B20();
        $this->dbPath = __DIR__ . '/../../resources/sqlite/log.db';
        $this->dataPath = __DIR__ . "/../../web/js/data.js";
        $this->db = 'temperature';
    }

    public function fetchAll()
    {
        $return = array();
        $this->open($this->dbPath);
        $result = $this->query('SELECT datetime, celsius FROM temperature');
        while ($row = $result->fetchArray()) {
            $return[$row['datetime']] = $row['celsius'];
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
        $datas = $this->fetchAll();
        $jsContent = '{ "table": [';
        $index = 0;
        foreach ($datas as $date=>$celsius) {
            $date = date_parse_from_format("Y-m-d H:i:s", $date);
            $date = sprintf("%d,%d,%d,%d,%d,%d",
                $date['year']
                , $date['month']
                , $date['day']
                , $date['hour']
                , $date['minute']
                , $date['second']);
            $date = sprintf("new Date(%s)", $date);
            $jsContent .= "\n";
            if(0 < $index) {
                $jsContent .= ",";
            }
            $jsContent .= "[".'"' . $date . '"' . "," . str_replace(',','.',$celsius) . "]";
            $index++;
        }
        $jsContent .= "]}";
        if(false === file_put_contents($this->dataPath, $jsContent)){
            throw new \Exception("can't write into " . $this->dataPath);
        }

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
        $insert = sprintf("INSERT INTO temperature (datetime, celsius) VALUES (datetime('NOW', 'localtime'), %s)", str_replace(',', '.', $this->currentTemperature));
        $result = $this->exec($insert);
        $this->close();

        return $this;
    }

}
