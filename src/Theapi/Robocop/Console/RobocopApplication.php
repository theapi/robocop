<?php

namespace Theapi\Robocop\Console;

use Symfony\Component\Console\Input\InputArgument;

use Theapi\Robocop\Console\Command\GreetCommand,
    Theapi\Robocop\Console\Command\ImagesCommand,
    Theapi\Robocop\Console\Command\InboxCommand;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\Console\Application,
    Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/**
 * Robocop console application.
 *
 */
class RobocopApplication extends Application
{
    private $basePath;
    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct($version)
    {
        parent::__construct('Robocop', $version);

        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(dirname(ROBOCOP_BIN_PATH) . '/config'));
        $loader->load('robocop.yml');
    }

    public function getContainer() {
      return $this->container;
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
       // $this->add($this->createCommand($input));

        return parent::doRun($input, $output);
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new ImagesCommand();
        $defaultCommands[] = new GreetCommand();

        return $defaultCommands;
    }

    /**
     * Gets the default input definition.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help',           '-h', InputOption::VALUE_NONE, 'Display this help message.'),
            new InputOption('--quiet',          '-q', InputOption::VALUE_NONE, 'Do not output any message.'),
            new InputOption('--verbose',        '-v', InputOption::VALUE_NONE, 'Increase verbosity of messages.'),
            new InputOption('--version',        '-V', InputOption::VALUE_NONE, 'Display this application version.'),
            new InputOption('--ansi',           '',   InputOption::VALUE_NONE, 'Force ANSI output.'),
            new InputOption('--no-ansi',        '',   InputOption::VALUE_NONE, 'Disable ANSI output.'),
            new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question.'),
        ));
    }

   /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        $command = $input->getFirstArgument();
        return $command;
    }

}
