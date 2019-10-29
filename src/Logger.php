<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 29.10.19
 * Time: 14:10
 */

namespace GUIHelper;


class Logger
{
    private $pathToLog;

    public function __construct($pathToLog = './logger0x.log')
    {
        $this->pathToLog = $pathToLog;

    }

    public function log($str){
        $tmp_file = fopen($this->pathToLog, 'a');
        $date = date('Y-m-d H:i:s');
        $str_res = $date . " =>  " . $str;
        fwrite($tmp_file, $str_res . "\r\n");
        fclose($tmp_file);
    }

    public function logInitNow(){
        $this->pathToLog = substr_replace($this->pathToLog, -1, 4).'_'.date().'.log';
    }
}