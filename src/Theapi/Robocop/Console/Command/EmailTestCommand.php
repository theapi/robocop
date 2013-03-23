<?php
namespace Theapi\Robocop\Console\Command;

use Theapi\Robocop\EmailSender;

use Symfony\Component\Console\Input\InputArgument;

use Theapi\Robocop\Images;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input,
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
            ->setDescription('Send a test email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $app = $this->getApplication();
      $container = $app->getContainer();
      $config = $container->getParameter('robocop');

      $mailer = new EmailSender($config);
      $result = $mailer->sendTestMail();
      var_dump($result);
      //$output->writeln($out);
    }

}
