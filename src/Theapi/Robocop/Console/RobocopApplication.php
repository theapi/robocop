<?php

namespace Theapi\Robocop\Console;

use Theapi\Robocop\Console\Command\InboxCommand;

use Theapi\Robocop\MailParser;

use Symfony\Component\DependencyInjection\Definition;

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
     * Creates main command for application.
     *
     * @param InputInterface $input
     *
     * @return Command
     */
    protected function createCommand(InputInterface $input)
    {
        return $this->createContainer($input)->get('robocop.console.command');
    }

    /**
     * Creates container instance, loads extensions and freezes it.
     *
     * @param InputInterface $input
     *
     * @return ContainerInterface
     */
    protected function createContainer(InputInterface $input)
    {
        $container = new ContainerBuilder();

        //TODO: this should be in yml/xml somewhere
        //$command = new InboxCommand($container);
        //$definition = new Definition($command);
        //$container->setDefinition('robocop.console.command', $definition);

        $loader = new YamlFileLoader($container, new FileLocator(dirname(ROBOCOP_BIN_PATH) . '/config'));
        $loader->load('robocop.yml');
        $container->compile();

        return $container;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'robocop';
    }

}
