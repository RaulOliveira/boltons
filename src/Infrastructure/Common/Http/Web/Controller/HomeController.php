<?php

namespace App\Infrastructure\Common\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index() 
    {
        return $this->render('@Common/home.html.twig');
    }

    public function index2() 
    {
       die('aqui');
    }
}