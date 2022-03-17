<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    #[Route("/trick/category/{id}", name:"app_trick_category", methods: 'GET')]
    public function index(TrickRepository $trickRepository, CategoryRepository $categoryRepository, int $id = null): Response
    {
        $trick = $id ? $trickRepository->findTrickByCategory($id) : $trickRepository->findAll();

        return $this->render('trick/index.html.twig', [
            'tricks' => $trick,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrickRepository $trickRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $trick = new Trick();
        $trick
            ->setUser($security->getUser())
            ->setCreatedAt(new DateTime());

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickRepository->add($trick);
            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/trick/{id}', name: 'app_trick_show', methods: ['GET', 'POST'])]
    #[Entity("trick", expr:"repository.findTrickWithCategories(id)")]
    public function show(int $id,Trick $trick, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $comments = $commentRepository->findCommentsWithUser($trick);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setUser($security->getUser());
            $comment->setTrick($trick);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_trick_show',
                    ['id' => $id,]) . '#comments');
        }
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form'=> $form->createView(),
            'comments'=> $comments,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository, EntityManagerInterface $entityManager): Response
    {
        $trick->setUpdatedAt(new DateTime());

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickRepository->add($trick);
            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $trickRepository->remove($trick);
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
