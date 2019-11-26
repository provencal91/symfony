<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/", name="wild_show_index")
     */

    public function index()
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Bienvenue sur Wild Series',
        ]);
    }
    /**
     * @Route("/wild/show/{slug}",
     * requirements={"slug"="[a-z0-9-]+"},
     * defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"},
     * name="wild_show")
     */
    public function show(string $slug): Response
    {
        if (preg_match_all("(-)", $slug)) {
            $slug = ucwords(str_replace('-', ' ', $slug));
        }
        // redirection vers la page erreur, correspondant à l'insertion de majuscule dans l'URL
        return $this->render('wild/show.html.twig', [
            'slug' => $slug]);

    }
}
