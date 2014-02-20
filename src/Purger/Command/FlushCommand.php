<?php
namespace Purger\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FlushCommand extends ConsoleCommand {
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

    private function getUrlList ($filename, $regex){
        $urls = file($filename);
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
        return $urls;
    }

    private function sendUrlsToQueue($urls){
        foreach ($urls as $currUrl) {
            $msg = new AMQPMessage($currUrl);
            $this->channel->basic_publish($msg, '', ConsoleCommand::QUEUE_NAME);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->createQueue ();

        $regex = $input->getArgument('regex');
        $filename = $input->getArgument('source');

        $urls = $this->getUrlList ($filename, $regex);
        $this->sendUrlsToQueue ($urls);
        
        echo ' [x] Sent '.count($urls).' urls'.PHP_EOL;

        $this->closeQueue();
    }
}