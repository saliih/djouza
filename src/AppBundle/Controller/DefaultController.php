<?php

namespace AppBundle\Controller;

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
        // replace this example code with whatever you need
        return $this->render('AppBundle:Index:index.html.twig', [

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
        $categories = $this->getDoctrine()->getRepository('AppBundle:Categories')->findBy(array('parent' => null, 'menu'=>true), array('ord' => "ASC"));
        return $this->render('AppBundle:Index:header.html.twig', [
            "categories" => $categories
        ]);
    }

    public function mobileAction(Request $request){
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
    private function getAllCat(){
        return  $this->getDoctrine()->getRepository('AppBundle:Categories')->findBy(array('parent' => null, 'menu'=>true), array('ord' => "ASC"));
    }
}
