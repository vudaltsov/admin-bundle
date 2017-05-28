<?php

namespace Ruwork\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuworkAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'EasyAdminBundle';
    }
}
