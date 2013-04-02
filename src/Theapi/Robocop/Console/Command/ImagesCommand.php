<?php
namespace Theapi\Robocop\Console\Command;

use Theapi\Robocop\Images;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
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
                'date',
                InputArgument::OPTIONAL,
                'Which date to process (Y-m-d)? or <info>purge</info> to delete old directories'
            )
            ->addOption(
                'threshold',
                 't',
                 InputOption::VALUE_OPTIONAL,
                 'The difference number that makes an image noteworthy.'
            )
            ->addOption(
                'processed',
                 'P',
                 InputOption::VALUE_NONE,
                 'When purging, purge the processed directories.'
            )
            ->setHelp('
Compare images for the amount of difference in <info>dir</info>.
            ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $container = $this->getApplication()->getContainer();

      $images = $container->get('images');
      $images->setOutput($output);

      $date = $input->getArgument('date');
      if (!empty($date) && $date == 'purge') {
        $images->deleteOldDirectories($input->getOption('processed'));
      } else {
        $date = $input->getArgument('date');
        $images->compareDateDir($date, $input->getOption('threshold'));
      }
    }

}
