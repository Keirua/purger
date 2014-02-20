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
                        new InputArgument('regex', InputOption::VALUE_OPTIONAL, 'A regex that urls have to match in order to be flushed', null),
                    )
                )
             ->setHelp(<<<EOT
The <info>test</info> command does things and stuff
EOT
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('remaining_urls', false, false, false, false);

        $regex = $input->getArgument('regex');
        $urls = file($input->getArgument('source'));
        if (null != $regex){
            $urls = array_filter(
                        $urls,
                        function($v) use ($regex) {
                            return preg_match($regex[0], $v);
                        }
                    );
        }
        else {
            echo 'No regex to match, every URL are used'.PHP_EOL;
        }

        foreach ($urls as $currUrl) {
            $msg = new AMQPMessage($currUrl);
            $channel->basic_publish($msg, '', 'remaining_urls');
        }
        echo ' [x] Sent '.count($urls).' urls'.PHP_EOL;

        $channel->close();
        $connection->close();
    }
}