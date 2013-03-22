<?php
namespace Theapi\Robocop\Console\Command;

use Theapi\Robocop\MailParser;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class InboxCommand extends Command
{

    public function __construct()
    {
        parent::__construct('inbox');
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
      $app = $this->getApplication();
      $container = $app->getContainer();
      $config = $container->getParameter('robocop');

      $mailParser = new MailParser($config);
      $mailParser->processIncomingMail();
    }

}
