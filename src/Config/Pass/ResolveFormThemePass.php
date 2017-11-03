<?php

namespace Ruvents\AdminBundle\Config\Pass;

use Ruvents\AdminBundle\Config\Model\Config;

class ResolveFormThemePass implements PassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(Config $config, array $data)
    {
        $defaultTheme = $data['form']['default_theme'];

        foreach ($config->entities as $entity) {
            if (null === $entity->create->theme) {
                $entity->create->theme = $defaultTheme;
            }

            if (null === $entity->edit->theme) {
                $entity->edit->theme = $defaultTheme;
            }
        }
    }
}