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
        echo 'flushing...'.$url.PHP_EOL;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PURGE");
        curl_exec($curl);
    }


    protected function execute(InputInterface $input, OutputInterface $output) {
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

        $callback = function($msg) {
            echo " Received ", $msg->body, "\n";
            $minWaitDuration = 2;
            $this->flushUrl($msg->body);
            time_sleep_until(microtime(true) + $minWaitDuration);

            echo " [x] Flushed ", $msg->body, "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('hello', '', false, false, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}