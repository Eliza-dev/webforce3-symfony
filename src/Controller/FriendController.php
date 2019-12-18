<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Form\FriendType;
use App\Repository\FriendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/friend")
 */
class FriendController extends AbstractController
{
    /**
     * @Route("/", name="friend_index", methods={"GET"})
     */
    public function index(FriendRepository $friendRepository): Response
    {
        return $this->render('friend/index.html.twig', [
            'friends' => $friendRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="friend_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $friend = new Friend();
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($friend);
            $entityManager->flush();

            return $this->redirectToRoute('friend_index');
        }

        return $this->render('friend/new.html.twig', [
            'friend' => $friend,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="friend_show", methods={"GET"})
     */
    public function show(Friend $friend): Response
    {
        return $this->render('friend/show.html.twig', [
            'friend' => $friend,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="friend_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Friend $friend): Response
    {
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('friend_index');
        }

        return $this->render('friend/edit.html.twig', [
            'friend' => $friend,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="friend_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Friend $friend): Response
    {
        if ($this->isCsrfTokenValid('delete'.$friend->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($friend);
            $entityManager->flush();
        }

        return $this->redirectToRoute('friend_index');
    }
}
