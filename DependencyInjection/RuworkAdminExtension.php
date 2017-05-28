<?php

namespace Ruwork\AdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class RuworkAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('easy_admin', [
            'design' => [
                'form_theme' => 'vertical',
                'assets' => [
                    'css' => [
                        'bundles/ruworkadmin/vendor/node_modules/bootstrap-markdown/css/bootstrap-markdown.min.css',
                    ],
                    'js' => [
                        'bundles/ruworkadmin/vendor/node_modules/markdown/lib/markdown.js',
                        'bundles/ruworkadmin/vendor/node_modules/bootstrap-markdown/js/bootstrap-markdown.js',
                        'bundles/ruworkadmin/vendor/node_modules/bootstrap-markdown/locale/bootstrap-markdown.ru.js',
                        'bundles/ruworkadmin/vendor/node_modules/bootstrap-markdown/locale/bootstrap-markdown.ru.js',
                        'bundles/ruworkadmin/js/ruwork_admin.js',
                    ],
                ],
            ],
        ]);
    }
}
