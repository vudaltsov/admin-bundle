<?php

namespace Ruvents\AdminBundle\Twig;

use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Ruvents\AdminBundle\Config\Model\Config;
use Ruvents\AdminBundle\ListField\TypeGuesserInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ListExtension extends AbstractExtension
{
    private $config;

    private $guessers;

    private $processors;

    private $typesTemplate;

    private $registry;

    private $accessor;

    /**
     * @param iterable|TypeGuesserInterface[] $guessers
     */
    public function __construct(
        Config $config,
        ManagerRegistry $registry,
        PropertyAccessorInterface $accessor = null,
        iterable $guessers,
        ContainerInterface $processors,
        string $typesTemplate
    ) {
        $this->config = $config;
        $this->guessers = $guessers;
        $this->processors = $processors;
        $this->typesTemplate = $typesTemplate;
        $this->registry = $registry;
        $this->accessor = $accessor ?? PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ruvents_admin_render_list_field', [$this, 'renderListField'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    public function renderListField(Environment $twig, string $entityName, $entity, ?string $propertyPath = null, ?string $type = null): string
    {
        $class = get_class($entity);

        if (null === $propertyPath) {
            if (null === $type) {
                throw new \LogicException('$propertyPath and $type cannot be null at the same time.');
            }

            $value = null;
        } else {
            $value = $this->accessor->getValue($entity, $propertyPath);

            if (null === $type) {
                $type = $this->guessType($class, $propertyPath, $value);
            }
        }

        $context = [
            'type' => $type,
            'entity' => $entity,
            'entity_config' => $this->config->entities[$entityName],
            'entity_name' => $entityName,
            'id' => $id = $this->getId($entity),
            'entity_title' => method_exists($entity, '__toString') ? (string)$entity : $class.'#'.$id,
            'value' => $value ?? null,
        ];

        if ($this->processors->has($type)) {
            $this->processors->get($type)->process($context);
        }

        return $twig->load($this->typesTemplate)->renderBlock($type, $context);
    }

    private function guessType(string $class, string $propertyPath, $value): string
    {
        foreach ($this->guessers as $guesser) {
            if (null !== $type = $guesser->guess($class, $propertyPath)) {
                return $type;
            }
        }

        if (null === $value) {
            return 'null';
        }

        if (is_bool($value)) {
            return 'bool';
        }

        if (is_scalar($value)) {
            return 'plain';
        }

        return 'php_type';
    }

    private function getId($entity)
    {
        $id = $this->registry
            ->getManagerForClass($class = get_class($entity))
            ->getClassMetadata($class)
            ->getIdentifierValues($entity);

        return reset($id) ?: null;
    }
}
