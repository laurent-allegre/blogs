<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\VerificationComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="liste_articles", methods={"GET"})
     */
    public function listeArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([
            'state' => 'publier'
        ]);


        return $this->render('default/index.html.twig',[
             'articles' => $articles,
             'brouillon' => false
        ]);
    }

    /**
     * @Route("/{id}", name="vue_article", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function vueArticle(Article $article, Request $request, EntityManagerInterface $manager, VerificationComment $verifService, FlashBagInterface $session)
    {


        $comment = new Comment();
        $comment->setArticle($article);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if($verifService->commentaireNonAutorise($comment)===false){
                $manager->persist($comment);
                $manager->flush();

                return $this->redirectToRoute('vue_article', ['id' => $article->getId()]);
            }
            else{
                $session->add("danger", "le commentaire contient un mot interdit ");
            }

        }


        return $this->render('default/vue.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/article/ajouter", name="ajout-article")
     * @Route("/article/{id}/edition", name="edition_article", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function ajouter(Article $article = null,  Request $request, EntityManagerInterface $manager)
    {
      if ($article === null){

          $article = new Article();
      }


      $form = $this->createForm(ArticleType::class, $article);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()){

          if ($form->get('brouillon')->isClicked()){
              $article->setState('brouillon');
          }
          else{
              $article->setState('a publier');
          }

          if ($article->getId() === null){

              $manager->persist($article);
          }

          $manager->flush();

          return $this->redirectToRoute('liste_articles');

      }

      return $this->render('default/ajout.html.twig', [
          'form' => $form->createView()
    ]);
    }

    /**
     * @Route ("/article/brouillon", name="brouillon-article")
     */
    public function brouillon(ArticleRepository $articleRepository){
        $articles = $articleRepository->findBy([
           'state' => 'brouillon'
        ]);

        return $this->render('default/index.html.twig',[
            'articles' => $articles,
            'brouillon' => true
        ]);
    }
}
