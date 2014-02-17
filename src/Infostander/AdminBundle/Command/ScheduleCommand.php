<?php

namespace Infostander\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('infostander:schedule')
             ->setDescription('Run the infostander scheduler')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start updating schedule...");

    }
}