<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Yaml\Yaml,
    Symfony\Component\Yaml\Parser,
    PhpAmqpLib\Connection\AMQPConnection;

class ConsoleCommand extends Command {
    const QUEUE_NAME = 'remaining_urls';

    protected $config = null;

    protected $channel = null;
    protected $connection = null;

    public function __construct() {
        parent::__construct();
    }

    protected function createQueue (){
        $this->connection = new AMQPConnection(
            $this->config->get('host'),
            $this->config->get('port'),
            $this->config->get('login'),
            $this->config->get('password')
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(self::QUEUE_NAME, false, false, false, false);
    }

    protected function closeQueue (){
        $this->channel->close();
        $this->connection->close();
    }

    public function setConfig ($config){
        $this->config = $config;
    }
}