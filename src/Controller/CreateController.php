<?php

namespace Ruvents\AdminBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Ruvents\AdminBundle\Config\Model\EntityConfig;
use Ruvents\AdminBundle\Form\Type\ButtonGroupType;
use Ruvents\AdminBundle\Form\Type\FieldsFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateController extends AbstractController
{
    /**
     * @param EntityConfig  $config
     * @param ObjectManager $manager
     * @param Request       $request
     *
     * @return Response
     */
    public function __invoke(EntityConfig $config, ObjectManager $manager, Request $request): Response
    {
        $class = $config->class;

        if ($attributes = $config->create->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $entity = new $class();

        $builder = $this
            ->get('form.factory')
            ->createBuilder(FieldsFormType::class, $entity, [
                'fields' => $config->create->fields,
                'data_class' => $config->class,
            ])
            ->add('__buttons', ButtonGroupType::class);

        $builder->get('__buttons')
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn-success']])
            ->add('submit_and_list', SubmitType::class, ['attr' => ['class' => 'btn-primary']])
            ->add('submit_and_create', SubmitType::class, ['attr' => ['class' => 'btn-secondary']]);

        $form = $builder
            ->getForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($entity);
            $manager->flush();

            /**
             * @var SubmitButton $submitAndList
             * @var SubmitButton $submitAndCreate
             */
            $submitAndList = $form->get('__buttons')->get('submit_and_list');
            $submitAndCreate = $form->get('__buttons')->get('submit_and_create');

            if ($submitAndList->isClicked()) {
                return $this->redirectToRoute('ruvents_admin_list', [
                    'ruvents_admin_entity' => $config->name,
                ]);
            }

            if ($submitAndCreate->isClicked()) {
                return $this->redirectToRoute('ruvents_admin_create', [
                    'ruvents_admin_entity' => $config->name,
                ]);
            }

            $id = $manager->getClassMetadata($class)->getIdentifierValues($entity);

            return $this->redirectToRoute('ruvents_admin_edit', [
                'ruvents_admin_entity' => $config->name,
                'id' => reset($id),
            ]);
        }

        return $this->render('@RuventsAdmin/create.html.twig', [
            'form' => $form->createView(),
            'config' => $config,
        ]);
    }
}
