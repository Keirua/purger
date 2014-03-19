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

        $yaml = new Parser();
        $this->config = $yaml->parse(file_get_contents("app/config.yml"));
    }

    protected function createQueue (){
        $this->connection = new AMQPConnection(
            $this->config['purger']['host'],
            $this->config['purger']['port'],
            $this->config['purger']['login'],
            $this->config['purger']['password']
        );

        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(self::QUEUE_NAME, false, false, false, false);
    }

    protected function closeQueue (){
        $this->channel->close();
        $this->connection->close();
    }
}