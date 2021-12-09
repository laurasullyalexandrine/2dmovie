<?php

namespace App\Controller\Back;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/admin/person', name: 'admin_person')]
    public function browse(PersonRepository $personRepository): Response
    {
        $allPerson = $personRepository->findBy([], ['name' => 'ASC']);
        return $this->render('back/person/browse.html.twig', [
            'person_list' => $allPerson,
        ]);
    }

    #[Route('/admin/person/new', name: 'admin_person_add')]
    public function add(Request $request): Response
    {
        $person = new Person();
       
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
    
            $person = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'Person `' . $person->getName() . '` a bien été mis ajouté !');

            return $this->redirectToRoute('admin_person');
        }

        return $this->render('back/person/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/person/{id}', name: 'admin_person_read', methods: ['GET'])]
    public function read(Person $person): Response
    {
        dump($person);
        return $this->render('back/person/read.html.twig', [
            'person' => $person,
        ]);
    }

    #[Route('/admin/person/edit/{id}', name: 'admin_person_edit', methods: ['GET', 'POST'])]
    public function edit(Person $person, Request $request): Response
    {
        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $person->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Person `' . $person->getName() . '` a bien été mis à jour !');

            return $this->redirectToRoute('admin_person');
        }

        return $this->render('back/person/edit.html.twig', [
            'form' => $form->createView(),
            'person' => $person,
        ]);
    }

    #[Route('/admin/person/delete/{id}', name: 'admin_person_delete', methods: ['GET', 'POST'])]
    public function delete(Person $person, EntityManagerInterface $entityManagerInterface): Response
    {
        $entityManagerInterface->remove($person);

        $entityManagerInterface->flush();

        $this->addFlash('success', 'Person `' . $person->getName() . '` a bien été supprimé !');

        return $this->redirectToRoute('admin_person');
    }
}
