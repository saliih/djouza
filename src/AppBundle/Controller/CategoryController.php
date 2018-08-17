<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/contact/{slug}", name="categorypage")
     */
    public function indexAction($slug)
    {
        $categoy = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('slug'=>$slug));
        return $this->render('@App/Category/index.html.twig', [
            "category" => $categoy
        ]);
    }
}
