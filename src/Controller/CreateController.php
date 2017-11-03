<?php

namespace Ruvents\AdminBundle\Controller;

use Ruvents\AdminBundle\Config\Model\EntityConfig;
use Ruvents\AdminBundle\Form\Type\ButtonGroupType;
use Ruvents\AdminBundle\Form\Type\FieldsFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends AbstractController
{
    /**
     * @param EntityConfig $entityConfig
     * @param Request      $request
     *
     * @return Response
     */
    public function __invoke(EntityConfig $entityConfig, Request $request): Response
    {
        $class = $entityConfig->class;
        $manager = $this->getEntityManager($class);

        if ($attributes = $entityConfig->create->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $entity = new $class();

        $builder = $this
            ->get('form.factory')
            ->createBuilder(FieldsFormType::class, $entity, [
                'fields' => $entityConfig->create->fields,
                'data_class' => $entityConfig->class,
            ])
            ->add('__buttons', ButtonGroupType::class);

        $builder->get('__buttons')
            ->add('submit', SubmitType::class, [
                'label' => 'Save and continue editing',
                'attr' => ['class' => 'btn-success'],
            ])
            ->add('submit_and_list', SubmitType::class, [
                'label' => 'Save and go to list',
                'attr' => ['class' => 'btn-primary'],
            ])
            ->add('submit_and_create', SubmitType::class, [
                'label' => 'Save and create new',
                'attr' => ['class' => 'btn-secondary'],
            ]);

        $form = $builder
            ->getForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($entity);
            $manager->flush();

            if ($this->isClicked($form->get('__buttons'), 'submit_and_list')) {
                return $this->redirectToList($entityConfig->name);
            }

            if ($this->isClicked($form->get('__buttons'), 'submit_and_create')) {
                return $this->redirectToCreate($entityConfig->name);
            }

            return $this->redirectToEdit($entityConfig->name, $entity);
        }

        return $this->render('@RuventsAdmin/create.html.twig', [
            'form' => $form->createView(),
            'config' => $entityConfig,
        ]);
    }
}
