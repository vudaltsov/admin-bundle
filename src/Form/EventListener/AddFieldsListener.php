<?php

namespace Ruvents\AdminBundle\Form\EventListener;

use Ruvents\AdminBundle\Config\Model\Field\FormFieldConfig;
use Ruvents\AdminBundle\Form\Type\GroupType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AddFieldsListener implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var FormBuilderInterface
     */
    private $builder;

    /**
     * @var FormFieldConfig[]
     */
    private $fields;

    public function __construct(AuthorizationCheckerInterface $authChecker, FormBuilderInterface $builder, array $fields)
    {
        $this->authChecker = $authChecker;
        $this->builder = $builder;
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $entity = $event->getData();

        foreach ($this->fields as $field) {
            if ($field->requiresGranted && !$this->authChecker->isGranted($field->requiresGranted, $entity)) {
                continue;
            }

            $childBuilder = $this->builder
                ->create($field->name, $field->type, $field->options + ['auto_initialize' => false]);

            if ($childBuilder->getAttribute('ruvents_admin.is_group', false)) {
                $form->add($group = $childBuilder->getForm());

                continue;
            }

            if (!isset($group)) {
                $group = $form->add('__group0', GroupType::class)->get('__group0');
            }

            $group->add($childBuilder->getForm());
        }
    }
}
