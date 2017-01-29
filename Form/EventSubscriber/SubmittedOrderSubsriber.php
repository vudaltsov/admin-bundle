<?php

namespace Ruwork\AdminBundle\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SubmittedOrderSubsriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => ['preSubmit', -10000],
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (empty($data)) {
            return;
        }

        $all = $form->all();

        foreach ($all as $item) {
            $form->remove($item->getName());
        }

        foreach ($data as $name => $value) {
            $form->add($all[$name]);
        }
    }
}
