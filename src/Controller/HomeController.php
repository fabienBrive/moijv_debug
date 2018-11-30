<?php

namespace App\Controller;

use App\Exception\DivisionByZeroException;
use App\Repository\ProductRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/",name="root") 
     */
    public function root()
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/home", name="home")
     * @Route("/home/{page}", name="home_paginated")
     * @param ProductRepository $productRepo
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws DivisionByZeroException
     */
    public function index(ProductRepository $productRepo, $page = 1)
    {
        $products = $productRepo->findPaginated($page);
        try {
            $a = 5/0;
        } catch(\Exception $ex){
            $dvsExxception = new DivisionByZeroException(5);
            throw $dvsExxception;
        };

        return $this->render("home.html.twig", [
            'products' => $products
        ]);
    }
}

