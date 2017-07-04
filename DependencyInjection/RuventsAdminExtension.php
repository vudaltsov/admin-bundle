<?php

namespace Ruvents\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class RuventsAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
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
                        'bundles/ruventsadmin/vendor/node_modules/bootstrap-markdown/css/bootstrap-markdown.min.css',
                    ],
                    'js' => [
                        'bundles/ruventsadmin/vendor/node_modules/markdown/lib/markdown.js',
                        'bundles/ruventsadmin/vendor/node_modules/bootstrap-markdown/js/bootstrap-markdown.js',
                        'bundles/ruventsadmin/vendor/node_modules/bootstrap-markdown/locale/bootstrap-markdown.ru.js',
                        'bundles/ruventsadmin/vendor/node_modules/bootstrap-markdown/locale/bootstrap-markdown.ru.js',
                        'bundles/ruventsadmin/js/ruvents_admin.js',
                    ],
                ],
            ],
        ]);
    }
}
