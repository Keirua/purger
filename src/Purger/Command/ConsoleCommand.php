<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command,
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
        $this->connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(self::QUEUE_NAME, false, false, false, false);
    }

    protected function closeQueue (){
        $this->channel->close();
        $this->connection->close();
    }
}