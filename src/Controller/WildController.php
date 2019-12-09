<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Repository\CategoryRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;

class WildController extends AbstractController
{
    /**
     * @Route("/", name="wild_index")
     * @return Response
     */

    public function index()
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs){
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render(
            'wild/index.html.twig', [
            'programs' => $programs
        ]);
    }
    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/wild/show/{slug}",
     * requirements={"slug"="[a-z0-9-]+"},
     * defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"},
     * name="wild_show")
     * @return Response
     */
    public function showByProgram(string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine()->getRepository(Season::class)
            ->findAll();

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
            'link' => $_SERVER['REQUEST_URI'],
            'seasons' =>$seasons
            ]);

    }

    /**
     * @param string $categoryName
     * @Route("wild/category/{categoryName}", name="wild_category")
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        $category= $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name'=>$categoryName]);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' =>$category], ['id'=>'DESC'], 3);


        return $this->render('wild/category.html.twig', [
            'programsByCategory' => $programs,
            'categoryName' => $category,
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *@param string $slug The slugger
     * @Route("/wild/show/{slug}/{id}",
     *requirements={"slug"="[a-z0-9-]+"},
     * defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"},
     * name="wild_season")
     * @return Response
     */
    public function showBySeason($id): Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class);
        $seasons = $season -> findOneBy(['id'=>$id]);

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->find($id);

        $episode = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season'=>$id]);


        return $this->render('wild/episode.html.twig', [
            'seasons' => $seasons,
            'episodes' => $episode,
            'program' => $program
        ]);

    }
}
