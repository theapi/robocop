<?php
namespace Theapi\Robocop\Console\Command;

use Theapi\Robocop\Images;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input,
    Symfony\Component\Console\Input\ArrayInput,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends Command
{

    public function __construct()
    {
        parent::__construct('cron');
    }

    protected function configure()
    {
        $this
            ->setName('cron')
            ->setDescription('Commands to be run daily by cron')
            ->setHelp('
15 7 * * * ' . ROBOCOP_BIN_PATH . ' cron -q
            ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

      // Process last night's images
      $command = $this->getApplication()->find('images');
      $input = new ArrayInput(array('command' => 'images'));
      $returnCode = $command->run($input, $output);

      // Process all of yesterday's images
      $command = $this->getApplication()->find('images');
      $arguments = array(
          'command' => 'images',
          'date'    => 'yesterday',
      );
      $input = new ArrayInput($arguments);
      $returnCode = $command->run($input, $output);

      // Purge old inbox directories
      $command = $this->getApplication()->find('images');
      $arguments = array(
          'command' => 'images',
          'date'    => 'purge',
      );
      $input = new ArrayInput($arguments);
      $returnCode = $command->run($input, $output);

      // Purge old processed directories
      $command = $this->getApplication()->find('images');
      $arguments = array(
          'command'     => 'images',
          'date'        => 'purge',
          '--processed' => true,
      );
      $input = new ArrayInput($arguments);
      $returnCode = $command->run($input, $output);

    }

}
