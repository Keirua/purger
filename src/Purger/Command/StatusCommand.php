<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class StatusCommand extends ConsoleCommand {
    protected function configure() {
        $this->setName("status")
             ->setDescription("Returns the current status for the requested flushing of URLs")
             ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
    	// Not pretty, I agree. However, there is actually no way to do this through the API
        system ('sudo rabbitmqctl list_queues | grep remaining_urls');
    }
}