<?php
/**
 * Created by PhpStorm.
 * User: sarra
 * Date: 15/05/16
 * Time: 14:34
 */

namespace AppBundle\Admin;

use AppBundle\Admin\BaseAdmin as Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;

class ChannelAdmin extends Admin
{

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('channelId')
            ->add('act', null, array('editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'delete' => array(),
                )
            ));
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('channelId')
           ;

    }
    public function prePersist($object)
    {
        $url = "https://www.googleapis.com/youtube/v3/channels?id=".$object->getChannelId()."&key=AIzaSyBGseWi-G-NxC1wO0R4UtTEg0HmSPXSJlI&part=contentDetails";
        $data = file_get_contents($url);
        echo "<pre>";
        print_r($data);exit;
    }
}
