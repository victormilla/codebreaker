<?php

namespace App\Command;

use App\View\ConsoleView;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CodebreakerPlayCommand extends CodebreakerBaseCommand
{
    protected static $defaultName = 'codebreaker:play';

    protected function configure()
    {
        $this->setDescription('Play a game of codebreaker.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->game->play(new ConsoleView(new SymfonyStyle($input, $output)));
    }
}
