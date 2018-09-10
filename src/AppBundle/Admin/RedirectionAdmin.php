<?php
/**
 * Created by PhpStorm.
 * User: salah
 * Date: 06/08/2018
 * Time: 14:59
 */

namespace AppBundle\Admin;

use AppBundle\Admin\BaseAdmin as Admin;
use AppBundle\Entity\Posts;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\AdminInterface;


class RedirectionAdmin extends Admin
{
    /**
     * @var string
     */
    protected $parentAssociationMapping = 'post';

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('new')
            ->add('old')
            ->add('post')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'delete' => array(),
                )
            ));
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('remove');
    }
}