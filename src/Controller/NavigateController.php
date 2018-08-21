<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NavigateController extends AbstractController
{
    /**
     * @Route("/", name="mainPage")
     */
    public function mainPage()
    {

        return $this->render('navigate/index.html.twig', [
        ]);
    }
    
}
