<?php

namespace Ruvents\AdminBundle\Controller;

use Ruvents\AdminBundle\Config\Model\EntityConfig;
use Ruvents\AdminBundle\Form\Type\ButtonGroupType;
use Ruvents\AdminBundle\Form\Type\DeleteType;
use Ruvents\AdminBundle\Form\Type\FieldsFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditController extends AbstractController
{
    /**
     * @param string       $id
     * @param EntityConfig $entityConfig
     * @param Request      $request
     *
     * @return Response
     */
    public function __invoke(string $id, EntityConfig $entityConfig, Request $request): Response
    {
        $class = $entityConfig->class;
        $manager = $this->getEntityManager($class);

        if ($attributes = $entityConfig->edit->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $entity = $manager->find($class, $id);

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        $builder = $this
            ->get('form.factory')
            ->createBuilder(FieldsFormType::class, $entity, [
                'fields' => $entityConfig->edit->fields,
                'data_class' => $class,
            ])
            ->add('__buttons', ButtonGroupType::class);

        $buttonsBuilder = $builder->get('__buttons')
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn-success']]);

        if ($entityConfig->delete->enabled) {
            $buttonsBuilder
                ->add('delete', DeleteType::class, [
                    'attr' => ['class' => 'btn-danger'],
                ]);
        }

        $form = $builder
            ->getForm()
            ->handleRequest($request);

        if ($entityConfig->delete->enabled) {
            /** @var SubmitButton $deleteButton */
            $deleteButton = $form->get('__buttons')->get('delete');

            if ($deleteButton->isClicked()) {
                $manager->remove($entity);
                // todo: redirect
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            return $this->redirect($request->getRequestUri());
        }

        return $this->render('@RuventsAdmin/edit.html.twig', [
            'form' => $form->createView(),
            'config' => $entityConfig,
        ]);
    }
}
