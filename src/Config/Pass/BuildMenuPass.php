<?php

namespace Ruvents\AdminBundle\Config\Pass;

use Ruvents\AdminBundle\Config\Model\Config;
use Ruvents\AdminBundle\Config\Model\Menu\AbstractItemConfig;
use Ruvents\AdminBundle\Config\Model\Menu\ChildrenItemConfig;
use Ruvents\AdminBundle\Config\Model\Menu\EntityItemConfig;
use Ruvents\AdminBundle\Config\Model\Menu\RouteItemConfig;
use Ruvents\AdminBundle\Config\Model\Menu\UrlItemConfig;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BuildMenuPass implements PassInterface
{
    const ENTITY_HREF_REGEXP = '/^(?<entity>\w+):(?<action>list|create)$/';

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Config $config, array $data)
    {
        $config->menu = array_map([$this, 'buildItem'], $data['menu']);
    }

    private function buildItem(array $data): AbstractItemConfig
    {
        if (isset($data['url'])) {
            $item = new UrlItemConfig();
            $item->url = $data['url'];
        } elseif (isset($data['route'])) {
            $item = new RouteItemConfig();
            $item->route = $data['route'];
            $item->routeParams = $data['route_params'];
            $item->activeExpression = $data['active'];
        } elseif (isset($data['entity'])) {
            $item = new EntityItemConfig();
            $route = explode(':', $data['entity']);
            $item->entity = $route[0];
            $item->action = $route[1];
            $item->routeParams = $data['route_params'];
            $item->activeExpression = $data['active'];
        } elseif (isset($data['children'])) {
            $item = new ChildrenItemConfig();
            $item->children = array_map([$this, 'buildItem'], $data['children']);
            $item->activeExpression = $data['active'];
        } else {
            throw new \LogicException(sprintf('Menu %s item is wrongly configured.', json_encode($data)));
        }

        $item->requiresGranted = $data['requires_granted'];
        $item->title = $data['title'];
        $item->attributes = $data['attributes'];

        return $item;
    }
}
