<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\EventRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comment')]
final class CommentController extends AbstractController
{
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    #[Route(name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $this->isGranted('ROLE_ADMIN')
            ? $commentRepository->findAll()
            : $commentRepository->findBy(['autor' => $this->getUser()]);

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/new/{eventId}', name: 'app_comment_new', methods: ['POST'])]
    public function new(
        Request $request,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager,
        int $eventId
    ): Response {
        $event = $eventRepository->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $content = $request->request->get('content');
        if (!$content) {
            $this->addFlash('danger', 'Le commentaire ne peut pas être vide.');
            return $this->redirectToRoute('app_home');
        }

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setEvents($event);
        $comment->setAutor($this->getUser());

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and comment.autor == user)")]
    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and comment.autor == user)")]
    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/event/{eventId}', name: 'app_comment_event', methods: ['GET'])]
    public function commentsByEvent(int $eventId, CommentRepository $commentRepository, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $comments = $commentRepository->findBy(['events' => $event]);

        return $this->render('comment/comments_by_event.html.twig', [
            'event' => $event,
            'comments' => $comments,
        ]);
    }
}
