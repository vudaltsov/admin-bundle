<?php

namespace Ruvents\AdminBundle\Form\Type;

use Ruvents\AdminBundle\Config\Model\Field\FormFieldConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FieldsFormType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FormFieldConfig[] $fields */
        $fields = $options['fields'];

        foreach ($fields as $field) {
            if ($field->requiresGranted && !$this->authChecker->isGranted($field->requiresGranted)) {
                continue;
            }

            $child = $builder->create($field->name, $field->type, $field->options);

            if ($child->getAttribute('ruvents_admin.is_group', false)) {
                $builder->add($groupBuilder = $child);

                continue;
            }

            if (!isset($groupBuilder)) {
                $groupBuilder = $builder->add('__group0', GroupType::class)->get('__group0');
            }

            $groupBuilder->add($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'fields' => [],
            ])
            ->setAllowedTypes('fields', 'array');
    }
}
