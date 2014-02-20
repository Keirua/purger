<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FlushCommand extends Command {
    protected function configure() {
        $this->setName("flush")
             ->setDescription("Flush a given set of URLs, based on a regex or not")
             ->setDefinition(
                    array(
                        new InputArgument('source', InputOption::VALUE_REQUIRED, 'The file name containing all the urls', null),
                    )
                )
             ->setHelp(<<<EOT
The <info>test</info> command does things and stuff
EOT
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // echo var_dump();
        // 
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        $urls = file($input->getArgument('source'));

        foreach ($urls as $currUrl) {
            $msg = new AMQPMessage($currUrl);
            $channel->basic_publish($msg, '', 'hello');
        }
        echo ' [x] Sent '.count($urls).' urls'.PHP_EOL;

        $channel->close();
        $connection->close();
    }
}