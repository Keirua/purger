<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use PhpAmqpLib\Connection\AMQPConnection;

class ListenCommand extends Command {
    protected function configure() {
        $this->setName("listen")
             ->setDescription("Starts a listener for flushing URLs")
             ->setDefinition(array(
             ))
             ->setHelp(<<<EOT
Starts a <info>listener</info> for flushing URLs
EOT
             );
    }

    private function flushUrl ($url){
        // $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PURGE");
        // curl_exec($curl);
    }

    private function waitMax ($duration, $delta){
        $sleepDuration = max(0, $duration - $delta);
        echo $sleepDuration.PHP_EOL;

        sleep(max(0, $duration - $delta));
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

        $callback = function($msg) {
            echo " Received ", $msg->body, "\n";
            $t0 = microtime(true);
            $this->flushUrl($msg->body);
            $t1 = microtime(true);
            $delta = $t1 - $t0;
            $this->waitMax(2000000, $delta);
            echo " [x] Flushed ", $msg->body, "\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}