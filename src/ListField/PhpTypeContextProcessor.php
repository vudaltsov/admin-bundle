<?php
declare(strict_types=1);

namespace Ruvents\AdminBundle\ListField;

class PhpTypeContextProcessor implements TypeContextProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return 'php_type';
    }

    /**
     * {@inheritdoc}
     */
    public function process(array &$context)
    {
        $context['type'] = gettype($context['value']);
    }
}
