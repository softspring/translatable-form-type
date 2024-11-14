<?php

namespace Softspring\TranslatableBundle\DependencyInjection;

use Exception;
use Softspring\TranslatableBundle\Doctrine\Type\TranslationType;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SfsTranslatableExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config/services'));

        if ($config['api']['enabled']) {
            $driver = $config['api']['driver'];
            $loader->load('api.yaml');
            $loader->load("api_driver/$driver.yaml");

            $container->setParameter('sfs_translatable.api.driver', $driver);
        } else {
            $container->setParameter('sfs_translatable.api.driver', null);
        }

        $container->setParameter('sfs_translatable.api.enabled', $config['api']['enabled']);

        $loader->load('form.yaml');
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $doctrineConfig = [];

        // add custom doctrine type
        $doctrineConfig['dbal']['types']['sfs_translation'] = TranslationType::class;

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}
