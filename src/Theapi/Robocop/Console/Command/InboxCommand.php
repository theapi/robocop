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
            ->setHelp('
The <info>%command.name%</info> command process incoming mail from an MTA (Postfix).
In ~/.redirect for the mail box to be processed, put:
  <info>"| php ' . ROBOCOP_BIN_PATH . '"</info>
            ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $container = $this->getApplication()->getContainer();

      $mailParser = $container->get('mail_parser');
      $mailParser->processIncomingMail();
    }

}
