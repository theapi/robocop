<?php
namespace Theapi\Robocop\Console\Command;

use Symfony\Component\Console\Input\InputArgument;

use Theapi\Robocop\Images;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class ImagesCommand extends Command
{

    public function __construct()
    {
        parent::__construct('images');
    }

    protected function configure()
    {
        $this
            ->setName('images')
            ->setDescription('Analyse images that have been received')
            ->addArgument(
                'dir',
                InputArgument::REQUIRED,
                'Where are the images?'
            )
            ->setHelp('
Compare images for the amount of difference in <info>dir</info>.
            ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $app = $this->getApplication();
      $container = $app->getContainer();
      $config = $container->getParameter('robocop');

      $dir = $input->getArgument('dir');
      $images = new Images($config, $output);
      $images->compareDir($dir);
    }

}
