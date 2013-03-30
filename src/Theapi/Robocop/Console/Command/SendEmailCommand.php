<?php
namespace Theapi\Robocop\Console\Command;

use Theapi\Robocop\EmailSender;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends Command
{

    public function __construct()
    {
        parent::__construct('email:send');
    }

    protected function configure()
    {
        $this
            ->setName('email:send')
            ->setDescription('Sends emails from the spool')
            ->addOption('message-limit', 0, InputOption::VALUE_OPTIONAL, 'The maximum number of messages to send.')
            ->addOption('time-limit', 0, InputOption::VALUE_OPTIONAL, 'The time limit for sending messages (in seconds).')
            ->addOption('recover-timeout', 0, InputOption::VALUE_OPTIONAL, 'The timeout for recovering messages that have taken too long to send (in seconds).')
            ->setHelp(<<<EOF
The <info>swiftmailer:spool:send</info> command sends all emails from the spool.

<info>php app/robocop email:send --message-limit=10 --time-limit=10 --recover-timeout=900</info>

EOF
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $container = $this->getApplication()->getContainer();

      $mailer = $container->get('mailer');

      $sent = $mailer->sendSpool(
        $input->getOption('message-limit'),
        $input->getOption('time-limit'),
        $input->getOption('recover-timeout')
      );
      $output->writeln(sprintf('Sent <info>%s</info>', $sent));
    }

}
