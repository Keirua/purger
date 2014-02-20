<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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

    protected function execute(InputInterface $input, OutputInterface $output) {
        //...
    }
}