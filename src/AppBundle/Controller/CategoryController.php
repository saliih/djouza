<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/category/{slug}", name="categorypage")
     * @Route("/category/{slug}/page/{page}", name="categorypaging")
     */
    public function indexAction($slug,$page=1, Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $categoy = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('slug'=>$slug));
        $posts =  $this->getDoctrine()->getRepository('AppBundle:Posts')->getPostsByCategories($categoy);

        $pagination = $paginator->paginate(
            $posts,
            $request->query->getInt('page', $page)/*page number*/,
            10/*limit per page*/
        );
        return $this->render('@App/Category/index.html.twig', [
            "category" => $categoy,
            "posts" => $pagination,
        ]);
    }
}
