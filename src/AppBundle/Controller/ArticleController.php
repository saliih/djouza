<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("article/{slug}", name="article")
     */
    public function indexAction($slug)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Posts')->findOneBy(array('slug'=>$slug));
        return $this->render('AppBundle:Article:index.html.twig', array("article"=>$post));
    }
}
