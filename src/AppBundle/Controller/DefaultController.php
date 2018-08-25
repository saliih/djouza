<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $sidePosts = $this->getDoctrine()->getRepository('AppBundle:Posts')->findBy(
            array('status' => true), array('dcr' => "DESC"), 10
        );


        return $this->render('AppBundle:Index:index.html.twig', [
            "sidePosts" => $sidePosts,

        ]);
    }
    public function zone1Action($id, $limit){
        $categories = $this->getCategories($id);
        $posts = $this->getDoctrine()->getRepository("AppBundle:Posts")->getPostsByCategories($categories, $limit);
        return $this->render('AppBundle:Index:zone1.html.twig', [
            "categories" => $categories,
            "posts" => $posts,

        ]);
    }
    /**
     * @Route("/contact", name="contactpage")
     */
    public function contactAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:Index:contact.html.twig', [

        ]);
    }

    public function headerAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Categories')->findBy(array('parent' => null, 'menu' => true), array('ord' => "ASC"));
        return $this->render('AppBundle:Index:header.html.twig', [
            "categories" => $categories
        ]);
    }

    public function mobileAction(Request $request)
    {
        $categories = $this->getAllCat();
        return $this->render('AppBundle:Index:mobile.html.twig', [
            "categories" => $categories
        ]);
    }

    public function footerAction(Request $request)
    {
        $categories = $this->getAllCat();
        return $this->render('AppBundle:Index:footer.html.twig', [
            "categories" => $categories
        ]);
    }

    public function sidebarAction(Request $request)
    {
        $categories = $this->getAllCat();
        return $this->render('AppBundle:Index:sidebar.html.twig', [
            "categories" => $categories
        ]);
    }

    private function getAllCat()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Categories')->findBy(array('parent' => null, 'menu' => true), array('ord' => "ASC"));
    }

    private function getCategories($id)
    {
        $categories = array();
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->find($id);
        $categories[] = $category;
        $this->recurciveCat($category,$categories );
        return $categories;
    }

    private function recurciveCat(Categories $category, &$categories)
    {
        foreach ($category->getChildren() as $child) {
            $categories[] = $child;
            $this->recurciveCat($child,$categories );
        }
    }
}
