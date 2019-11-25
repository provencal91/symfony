<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavbarController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */

    public function index()
    {
        return $this->render('navbar/navbar.html.twig');
    }

}
