<?php
namespace Theapi\Robocop\Console\Command;

use Theapi\Robocop\EmailSender;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class EmailTestCommand extends Command
{

    public function __construct()
    {
        parent::__construct('email:test');
    }

    protected function configure()
    {
        $this
            ->setName('email:test')
            ->addOption(
               'spool',
               null,
               InputOption::VALUE_NONE,
               'Send it via the spool'
            )
            ->setDescription('Send a test email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $container = $this->getApplication()->getContainer();

      $mailer = $container->get('mailer');

      $viaSpool = $input->getOption('spool');

      if (empty($viaSpool)) {
        // Use the spool setting from config.yml
        $emailConfig = $container->getParameter('email');
        $viaSpool = (bool) $emailConfig['spool'];
      }

      $sent = $mailer->sendTestMail($viaSpool);
      if ($viaSpool) {
        $output->writeln(sprintf('Spooled <info>%s</info>', $sent));
      }
      else {
        $output->writeln(sprintf('Sent <info>%s</info>', $sent));
      }
    }

}
