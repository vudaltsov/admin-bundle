<?php

namespace Ruvents\AdminBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Ruvents\AdminBundle\Config\Model\EntityConfig;
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
        $maxResults = 2;
        $class = $entityConfig->class;

        if ($attributes = $entityConfig->list->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $page = $request->query->getInt('page', 1);

        $qb = $this->getEntityManager($class)
            ->createQueryBuilder()
            ->select($alias = 'entity')
            ->from($class, $alias)
            ->setMaxResults($maxResults)
            ->setFirstResult(($page - 1) * $maxResults);

        $entities = new Paginator($qb);

        return $this->render('@RuventsAdmin/list.html.twig', [
            'entities' => $entities,
            'max_results' => $maxResults,
            'config' => $entityConfig->list,
        ]);
    }
}
