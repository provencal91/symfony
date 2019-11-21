<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */

    public function index()
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Bienvenue sur Wild Series',
        ]);
    }
}
