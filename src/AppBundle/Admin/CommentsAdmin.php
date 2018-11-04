<?php
/**
 * Created by PhpStorm.
 * User: salah
 * Date: 06/08/2018
 * Time: 14:59
 */

namespace AppBundle\Admin;

use AppBundle\Admin\BaseAdmin as Admin;
use AppBundle\Entity\Comments;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;

class CommentsAdmin extends Admin
{
    protected $parentAssociationMapping = 'post';
    protected $datagridValues = array(
        '_sort_by' => 'id',
        '_sort_order' => 'DESC',
    );
    public function getNewInstance()
    {
        /** @var Comments $object */
        $object = parent::getNewInstance();
        $container = $this->getConfigurationPool()->getContainer();
        $request = $container->get('request_stack')->getCurrentRequest();
        $user = $container->get('security.token_storage')->getToken()->getUser();
        if($request->query->has('parent')){
            $comment = $container->get('doctrine')->getRepository('AppBundle:Comments')->find($request->query->get('parent'));
            $object->setParent($comment);
            $object->setDcr(new \DateTime());
            // TODO : after creating user
            // $object->setAuthor($user->getUserName());
        }
        return $object;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email', 'string', array('label'=>"Avatar",'template'=>'@App/AdminComments/avatar.html.twig'))
            ->add('dcr', null, array( 'label'=>"Avatar",'template'=>'@App/AdminComments/dcr.html.twig'))
            ->add('author')
            ->add('post', null, array( 'header_style' => 'width: 15%'))
            ->add('body', null, array('header_style' => 'width: 35%'))
            ->add('act', null, array('editable'=>true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'show' => array(),
                    'response' => array('template'=>"@App/AdminComments/btresponse.html.twig"),
                    'delete' => array(),
                )
            ));
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        //$collection->remove('create');
        $collection->remove('export');
    }
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('email')
            ->add('dcr',DateTimePickerType::class)
            ->add('author')
            ->add('post')
            ->add('body')
        ;
    }
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('email')
            ->add('dcr')
            ->add('author')
            ->add('post')
            ->add('body')
            ->add('act', null, ['editable' => true])
        ;
    }
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('author')
            ->add('email')
            ->add('url')
            ->add('dcr')
            ->add('body')
            ->add('act')
            ->add('post')
       ;
    }
}