<?php

namespace App\Controller\Back;

use App\Entity\Casting;
use App\Form\CastingType;
use App\Repository\CastingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CastingController extends AbstractController
{
    #[Route('/admin/casting', name: 'admin_casting')]
    public function browse(CastingRepository $castingRepository): Response
    {
        $allCast = $castingRepository->findBy([], ['role' => 'ASC']);
        return $this->render('back/casting/browse.html.twig', [
            'casting_list' => $allCast,
        ]);
    }

    #[Route('/admin/casting/new', name: 'admin_casting_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $casting = new Casting();
        
        $form = $this->createForm(CastingType::class, $casting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $casting = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($casting);
            $entityManager->flush();

            $this->addFlash('success', 'Casting `' . $casting->getRole() . '` a bien été mis ajouté !');
            return $this->redirectToRoute('admin_casting');
        }

        return $this->render('admin/casting/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/casting/{id}', name: 'admin_casting_read', methods: ['GET'])]
    public function read(Casting $casting): Response
    {
        return $this->render('back/casting/read.html.twig', [
            'casting' => $casting,
        ]);
    }

    #[Route('/admin/casting/edit/{id}', name: 'admin_casting_edit', methods: ['GET', 'POST'])]
    public function edit(Casting $casting, Request $request): Response
    {
        $form = $this->createForm(MovieType::class, $casting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $casting->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Casting `' . $casting->getRole() . '` a bien été mis à jour  !');

            return $this->redirectToRoute('admin_casting');
        }

        return $this->render('back/casting_/edit.html.twig', [
            'form' => $form->createView(),
            'casting' => $casting,
        ]);
    }

    #[Route('/admin/casting/delete/{id}', name: 'admin_casting_delete', methods: ['GET'])]
    public function delete(Casting $casting, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($casting);
        $entityManagerInterface->flush();
       
        $this->addFlash('success', 'Casting `' . $casting->getRole() . '` a bien été supprimé !');

        return $this->redirectToRoute('admin_casting');
    }
}
