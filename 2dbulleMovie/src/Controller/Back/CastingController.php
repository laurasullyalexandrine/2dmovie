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
    #[Route('/admin/liste-casting', name: 'admin_casting')]
    public function browse(CastingRepository $castingRepository): Response
    {
        $allCast = $castingRepository->findBy([], ['personage' => 'ASC']);
        return $this->render('Back/casting/browse.html.twig', [
            'casting_list' => $allCast,
        ]);
    }

    #[Route('/admin/creer-un-cast/nouveau', name: 'admin_casting_add', methods: ['GET', 'POST'])]
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

            $this->addFlash('success', 'L\'acteur.trice `' . $casting->getPersonage() . '` a bien été ajouté !');
            return $this->redirectToRoute('admin_casting');
        }

        return $this->render('Back/casting/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/consulter-votre-cast/{id}', name: 'admin_casting_read', methods: ['GET'])]
    public function read(Casting $casting): Response
    {
        dump($casting);
        return $this->render('Back/casting/read.html.twig', [
            'casting' => $casting,
        ]);
    }

    #[Route('/admin/casting/editer-votre-cast/{id}', name: 'admin_casting_edit', methods: ['GET', 'POST'])]
    public function edit(Casting $casting, Request $request): Response
    {
        $form = $this->createForm(CastingType::class, $casting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $casting->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'L\'acteur.trice `' . $casting->getPersonage() . '` a bien été mis à jour  !');

            return $this->redirectToRoute('admin_casting');
        }

        return $this->render('Back/casting/edit.html.twig', [
            'form' => $form->createView(),
            'casting' => $casting,
        ]);
    }

    #[Route('/admin/casting/surpprimer-votre-cast/{id}', name: 'admin_casting_delete', methods: ['GET'])]
    public function delete(Casting $casting, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($casting);
        $entityManagerInterface->flush();
       
        $this->addFlash('success', 'L\'acteur.trice `' . $casting->getPersonage() . '` a bien été supprimé !');

        return $this->redirectToRoute('admin_casting');
    }
}
