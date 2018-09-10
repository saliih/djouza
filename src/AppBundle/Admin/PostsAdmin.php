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

class PostsAdmin extends Admin
{
    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'AppBundle:AdminPost:edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('image','string', array('label'=>"Photo",'template'=>'@App/AdminPost/image.html.twig'))
            ->add('title')
            ->add('category')
            ->add('dcr')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Recette')
                ->with('Recette', array('class' => "col-md-5"))
                    ->add('title')
                    ->add('slug','text')

                ->end()
                ->with('Parametre affichage', array('class' => "col-md-4"))
                    ->add('category')
                    ->add('dcr', 'sonata_type_date_picker', array('dp_language' => 'fr', 'format' => 'dd/MM/yyyy', 'label' => 'date de publication'))
                    ->add('status')
                    ->add('commentStatus')
                    ->add('nofollow')
                ->end()
                ->with('Images à la une', array('class' => "col-md-3"))
                        ->add('image')
                ->end()

            ->with('Contenu de la recette', array('class' => "col-md-12"))
            ->add('body')
            ->end()
            ->end()
            ->tab('Seo')
                ->with('Balise meta')
                    ->add('seoTitle')
                    ->add('seoDescription')
                    ->add('seoKeywords')
                ->end()
            ->end()
            ->tab('Recette paramètre')
                ->with('Recette paramètre')
                    ->add('preptime')
                    ->add('cooktime')
                    ->add('totaltime')
                ->end()
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title');
    }
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit', 'show'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');
        /** @var Posts $post */
        $post = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository('AppBundle:Posts')->find($id);
        $comment = $post->getComments()->count();
        $menu->addChild('Recette', array('uri' => $admin->generateUrl('edit', array('id' => $id))));

        //$menu->addChild('Import File', array('uri' => $admin->generateUrl('importfile')));
        $menu->addChild('Commentaires '."($comment)", array('uri' => $admin->generateUrl('admin.comments.list', array('id' => $id))))
            ->setAttribute('icon', 'fa fa-comment')
            ->setLinkAttribute('class', 'tabulataion');
        $menu->addChild('Redirections', array('uri' => $admin->generateUrl('admin.redirection.list', array('id' => $id))))
            ->setAttribute('icon', 'fa fa-link')
            ->setLinkAttribute('class', 'tabulataion');
    }
}