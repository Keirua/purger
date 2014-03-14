<?php
namespace Purger\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class BlankCommand extends ConsoleCommand {
    protected function configure() {
        $this->setName("blank")
             ->setDescription("Demo command")
             ;

    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        echo var_dump($this->config);
        echo "plop";
    }
}