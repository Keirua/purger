<?php

namespace Purger\Config;

use Symfony\Component\Yaml\Yaml,
    Symfony\Component\Yaml\Parser;

class Config {

    private $config;

    private $filename;

    public function __construct($filename){
        $this->filename = $filename;
        $yaml = new Parser();
        $this->config = $yaml->parse(file_get_contents( $filename ));
    }

    public function get ($data){
        return $this->config['purger'][$data];
    }
}