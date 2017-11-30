<?php

namespace Ruvents\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Ruvents\AdminBundle\Form\EventListener\AddFieldsListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractController extends SymfonyAbstractController
{
    protected function isClicked(FormInterface $form, string $name): bool
    {
        if (!$form->has($name)) {
            return false;
        }

        $button = $form->get($name);

        if (!$button instanceof ClickableInterface) {
            throw new \InvalidArgumentException(sprintf('Form element "%s" is not clickable.', $name));
        }

        return $button->isClicked();
    }

    protected function getEntityManager(string $class): EntityManagerInterface
    {
        $manager = $this->getDoctrine()->getManagerForClass($class);

        if (!$manager instanceof EntityManagerInterface) {
            throw new \InvalidArgumentException(sprintf('%s is not an entity.', $class));
        }

        return $manager;
    }

    protected function redirectToList(string $name): RedirectResponse
    {
        return $this->redirectToRoute('ruvents_admin_list', [
            'ruvents_admin_entity' => $name,
        ]);
    }

    protected function redirectToCreate(string $name): RedirectResponse
    {
        return $this->redirectToRoute('ruvents_admin_create', [
            'ruvents_admin_entity' => $name,
        ]);
    }

    protected function redirectToEdit(string $name, $entity): RedirectResponse
    {
        $class = get_class($entity);

        $id = $this->getEntityManager($class)
            ->getClassMetadata($class)
            ->getIdentifierValues($entity);

        return $this->redirectToRoute('ruvents_admin_edit', [
            'ruvents_admin_entity' => $name,
            'id' => reset($id),
        ]);
    }

    protected function createCustomFormBuilder(string $type, string $class, array $options = []): FormBuilderInterface
    {
        return $this->get('form.factory')->createBuilder($type, null, ['data_class' => $class] + $options);
    }

    protected function createEntityFormBuilder(array $fields, string $class, array $options = []): FormBuilderInterface
    {
        return $this->createFormBuilder(null, ['data_class' => $class] + $options)
            ->addEventSubscriber(new AddFieldsListener($this->get('security.authorization_checker'), $fields));
    }
}
