<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\Config\Pass;

use Ruvents\AdminBundle\Config\Model\Config;

interface PassInterface
{
    public function process(Config $config, array $data);
}
