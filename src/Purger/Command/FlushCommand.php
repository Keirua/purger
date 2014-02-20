<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FlushCommand extends Command {
    protected function configure() {
        $this->setName("flush")
             ->setDescription("Flush a given set of URLs, based on a regex or not")
             ->setDefinition(array(
             ))
             ->setHelp(<<<EOT
The <info>test</info> command does things and stuff
EOT
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        //...
    }
}