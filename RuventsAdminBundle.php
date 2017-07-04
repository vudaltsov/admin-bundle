<?php

namespace Ruvents\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RuventsAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'EasyAdminBundle';
    }
}
