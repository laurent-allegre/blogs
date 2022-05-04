<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="liste_articles", methods={"GET"})
     */
    public function listeArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();


        return $this->render('default/index.html.twig',[
             'articles' => $articles
        ]);
    }

    /**
     * @Route("/{id}", name="vue_article", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function vueArticle(ArticleRepository $articleRepository, $id)
    {
        $article = $articleRepository->find($id);

        return $this->render('default/vue.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route ("/article/ajouter", name="ajout-article")
     */
    public function ajouter(Request $request, EntityManagerInterface $manager)
    {
      $article = new Article();

      $form = $this->createForm(ArticleType::class, $article);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()){

          $manager->persist($article);
          $manager->flush();

          return $this->redirectToRoute('liste_articles');

      }

      return $this->render('default/ajout.html.twig', [
          'form' => $form->createView()
    ]);
    }
}
