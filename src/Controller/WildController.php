<?php
namespace App\Controller;

use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        $form = $this->createForm(ProgramSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy(["title" => $data]);
        }


        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
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
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }
        $seasons = $this->getDoctrine()->getRepository(Season::class)
            ->findAll();
        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'slug' => $slug,
            'link' => $_SERVER['REQUEST_URI'],
            'seasons' => $seasons
        ]);
    }

    /**
     * @param string $categoryName
     * @Route("wild/category/{categoryName}", name="wild_category")
     * @return Response
     */
    public function showByCategory(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);
        return $this->render('wild/category.html.twig', [
            'programsByCategory' => $programs,
            'categoryName' => $category,
        ]);
    }

    /**
     * @Route("/wild/show/{season}/{id}",
     * name="wild_show_season")
     * @return Response
     */
    public function showBySeason(Program $program): Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($program);
        $episodes =$this->showSeason($program);
        return $this->render('wild/season.html.twig', [
            'season' => $season,
            'episodes' => $episodes,
            'program' => $program
        ]);
    }

    public function showSeason(Program $program): Response
    {
        return $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($program);
    }

    /**
     * @Route("wild/episode/{id}", name="wild_show_episode")
     */
    public function showEpisode(Episode $episode)
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render('wild/episode.html.twig', [
            'season' => $season,
            'program' => $program,
            'episode' => $episode
        ]);
    }
}
