<?php
/**
 * Created by PhpStorm.
 * User: sarra
 * Date: 15/05/16
 * Time: 14:34
 */

namespace AppBundle\Admin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use AppBundle\Admin\BaseAdmin as Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class VideosAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'created',

    );

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array('label' => 'Miniature', 'template' => 'PostBundle:Videos:thumbnail.html.twig'))
            ->add('channel')
            ->add('name')
            ->add('createdby')
            ->add('created')
            ->add('act', null, array('editable' => true))
            ->add('trt', null, array('editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');
        $datagridMapper
            ->add('channel')
            ->add('createdby')
            ->add('act')
            ->add('trt');

    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Url', array('class' => 'col-md-3 urlzone'))
            ->add('url')
            ->end();
        if ($this->getSubject()->getId() > 0) {
            $formMapper
                ->with('parameters', array('class' => 'col-md-9'))
                ->add('name')
                ->add('created', 'sonata_type_date_picker', array('required' => true, 'dp_language' => 'fr', 'format' => 'dd/MM/yyyy'))
                //->add('category', null, array('label' => 'Catégorie', 'required' => false))
                ->add('body')
                ->add('tags')
                ->end();
        }
    }

    public function prePersist($object)
    {
        //$object = new Videos();
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setCreatedby($user);
        $this->updateId($object);


        $html = 'https://www.googleapis.com/youtube/v3/videos?id=' . $object->getVideosId() . '&key=AIzaSyBGseWi-G-NxC1wO0R4UtTEg0HmSPXSJlI&part=snippet';
        $response = file_get_contents($html);
        $decoded = json_decode($response, true);
        //echo "<pre>";print_r($decoded);exit;
        $data = $decoded['items'][0]['snippet'];

        $channel = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository('PostBundle:Channel')->findOneBy(array('name' => $data['channelTitle']));
        if ($channel == null) {
            $channel = new Channel();
            $channel->setName($data['channelTitle']);
            $channel->setChannelId($data['channelId']);
            $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager()->persist($channel);
            $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager()->flush();
        }
        $object->setChannel($channel);


        $object->setCreated($this->convertdate($data['publishedAt']));
        $object->setBody($data['description']);
        $object->setName($data['title']);
        $strTags = "";
        foreach ($data['tags'] as $value) {
            $strTags .= $value . ", ";
        }
        $object->setTags($strTags);
    }

    private function convertdate($dt)
    {
        $tab = explode("T", $dt);
        $tab2 = explode("-", $tab[0]);
        $todata = new \DateTime();
        $todata->setDate($tab2[0], $tab2[1], $tab2[2]);
        return $todata;
    }

    public function preUpdate($object)
    {
        $this->updateId($object);
    }

    private function updateId($object)
    {
        //$object = new Videos();
        $tab = explode('=', $object->getUrl());
        if (isset($tab[1]))
            $object->setVideosId($tab[1]);
    }

}
