<?php

namespace Ruvents\AdminBundle\Controller;

use Ruvents\AdminBundle\Config\Model\EntityConfig;
use Ruvents\Paginator\PaginatorBuilder;
use Ruvents\Paginator\Provider\DoctrineOrmProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends AbstractController
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

        if ($attributes = $entityConfig->list->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $qb = $this->getEntityManager($class)
            ->createQueryBuilder()
            ->select($alias = 'entity')
            ->from($class, $alias);

        $paginator = PaginatorBuilder::create()
            ->setProvider(new DoctrineOrmProvider($qb))
            ->setCurrent($request->query->getInt('page', 1))
            ->getPaginator();

        return $this->render('@RuventsAdmin/list.html.twig', [
            'paginator' => $paginator,
            'config' => $entityConfig->list,
            'entity_name' => $entityConfig->name,
        ]);
    }
}
