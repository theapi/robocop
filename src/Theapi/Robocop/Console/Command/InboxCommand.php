<?php
namespace Theapi\Robocop\Console\Command;

use Theapi\Robocop\MailParser;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;

class InboxCommand extends Command
{
    private $container;

    public function __construct()
    {
        parent::__construct('inbox');
        //$this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('inbox')
            ->setDescription('Mail inbox for handling incoming mail')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      //var_dump(get_class_methods($this));
      $app = $this->getApplication();
      //var_dump(get_class_methods($app));
      $container = $app->getContainer();
      var_dump(get_class_methods($container));

       // $mailParser = new MailParser($config);
    }


}
