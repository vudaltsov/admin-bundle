<?php

namespace Ruvents\AdminBundle;

use Ruvents\AdminBundle\DependencyInjection\Compiler\ListFieldTypeContextProcessorsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuventsAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ListFieldTypeContextProcessorsPass());
    }
}
