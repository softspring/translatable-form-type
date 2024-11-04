<?php

namespace Softspring\TranslatableBundle\DependencyInjection;

use Exception;
use Softspring\TranslatableBundle\Doctrine\Type\TranslationType;
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config/services'));
        $loader->load('form.yaml');
        // $loader->load('twig_extension.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $doctrineConfig = [];

        // add custom doctrine type
        $doctrineConfig['dbal']['types']['sfs_translation'] = TranslationType::class;

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}
