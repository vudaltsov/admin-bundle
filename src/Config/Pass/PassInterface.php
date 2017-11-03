<?php

namespace Ruvents\AdminBundle\Config\Pass;

use Ruvents\AdminBundle\Config\Model\Config;

interface PassInterface
{
    public function process(Config $config, array $data);
}
