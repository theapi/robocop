<?php
namespace Theapi\Robocop\Console\Command;


use Theapi\Robocop\Console\RobocopApplication;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class ClearCacheCommand extends Command
{

    public function __construct()
    {
        parent::__construct('cache:clear');
    }

    protected function configure()
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clears the Robocop caches')
            ->setHelp('Clears the <info>config cache</info> so new changes to config.xml will be used.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $file = RobocopApplication::getContainerCacheFile();
      if (file_exists($file)) {
        unlink($file);
      }
      $output->writeln(sprintf('Cache cleared'));
    }

}
