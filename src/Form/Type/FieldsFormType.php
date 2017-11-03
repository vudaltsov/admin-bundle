<?php

namespace Ruvents\AdminBundle\Form\Type;

use Ruvents\AdminBundle\Config\Model\Field\FormFieldConfig;
use Ruvents\AdminBundle\Form\EventListener\AddFieldsListener;
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
        $builder->addEventSubscriber(new AddFieldsListener($this->authChecker, $builder, $options['fields']));
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
            ->setAllowedTypes('fields', FormFieldConfig::class.'[]');
    }
}
