<?php

namespace Ruvents\AdminBundle\Config\Pass;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Type;
use Ruvents\AdminBundle\Config\Model\Config;
use Ruvents\AdminBundle\Config\Model\Field\ListFieldConfig;

class ListFieldsPass implements PassInterface
{
    private $defaultTypes = [
        Type::TARRAY => '',
        Type::SIMPLE_ARRAY => '',
        Type::JSON_ARRAY => '',
        Type::JSON => '',
        Type::BIGINT => 'plain',
        Type::BOOLEAN => 'bool',
        Type::DATETIME => '',
        Type::DATETIME_IMMUTABLE => '',
        Type::DATETIMETZ => '',
        Type::DATETIMETZ_IMMUTABLE => '',
        Type::DATE => '',
        Type::DATE_IMMUTABLE => '',
        Type::TIME => '',
        Type::TIME_IMMUTABLE => '',
        Type::DECIMAL => 'plain',
        Type::INTEGER => 'plain',
        Type::OBJECT => '',
        Type::SMALLINT => 'plain',
        Type::STRING => 'plain',
        Type::TEXT => 'plain',
        Type::BINARY => '',
        Type::BLOB => '',
        Type::FLOAT => '',
        Type::GUID => '',
        Type::DATEINTERVAL => '',
    ];

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Config $config, array $data)
    {
        foreach ($config->entities as $entityConfig) {
            foreach ($entityConfig->list->fields as $fieldConfig) {
                $this->resolveField($fieldConfig, $entityConfig->class);
            }
        }
    }

    private function resolveField(ListFieldConfig $config, string $class)
    {
        if (null === $config->type) {
            $metadata = $this->registry->getManagerForClass($class)->getClassMetadata($class);

            if ($metadata->hasField($config->name)) {
                $config->type = $this->defaultTypes[$metadata->getTypeOfField($config->name)];
            }
        }
    }
}
