<?php

namespace Evozon\TranslatrBundle\DependencyInjection;

use Evozon\TranslatrBundle\DependencyInjection\Compiler\Pass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class TranslatrExtension
 *
 * @package   Evozon\TranslatrBundle\DependencyInjection
 * @author    Balazs Csaba <csaba.balazs@evozon.com>
 * @copyright 2016 Evozon (https://www.evozon.com)
 */
class EvozonTranslatrExtension extends Extension
{
    /**
     * @const string
     */
    const DEFAULT_OUTPUT = '[filename].[locale].[extension]';
    /**
     * @const string
     */
    const DOWNLOAD_MAPPING_FILENAME_POSTFIX = '.tmp_translatr';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->addDefinitions([
            'client' => $this->constructClient($config),
        ]);
    }

    private function constructClient($config)
    {
        $clientDefinition = new Definition('Evozon\TranslatrBundle\Clients\NullAdapter');
        switch ($config['adapter']) {
            case 'onesky':
                $clientDefinition->setClass('Evozon\TranslatrBundle\Clients\OneSkyAdapter');

                $clientDefinition->addArgument(new Reference('event_dispatcher'));
                $clientDefinition->addArgument($config['project']);
                $clientDefinition->addArgument($config['locale_format']);

                $clientDefinition->addMethodCall('setApiKey', [$config['api_key']]);
                $clientDefinition->addMethodCall('setSecret', [$config['secret']]);
                break;

            case 'phraseapp':
                $clientDefinition->setClass('Evozon\TranslatrBundle\Clients\PhraseAppAdapter');

                $clientDefinition->addArgument(new Reference('event_dispatcher'));
                $clientDefinition->addArgument($config['project']);
                $clientDefinition->addArgument($config['locale_format']);

                $clientDefinition->addMethodCall('setApiKey', [$config['api_key']]);
                break;
        }

        return $clientDefinition;
    }
}
