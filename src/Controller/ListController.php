<?php
declare(strict_types=1);

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
        $listConfig = $entityConfig->list;
        $class = $entityConfig->class;

        if ($attributes = $listConfig->requiresGranted) {
            $this->denyAccessUnlessGranted($attributes, $class);
        }

        $qb = $this->getEntityManager($class)
            ->createQueryBuilder()
            ->select($alias = 'entity')
            ->from($class, $alias)
            ->orderBy('entity.'.$this->getIdField($class));

        $paginator = PaginatorBuilder::create()
            ->setProvider(new DoctrineOrmProvider($qb))
            ->setPerPage($listConfig->perPage)
            ->setCurrent($request->query->getInt('page', 1))
            ->getPaginator();

        return $this->render('@RuventsAdmin/list.html.twig', [
            'entity_config' => $entityConfig,
            'paginator' => $paginator,
        ]);
    }
}
