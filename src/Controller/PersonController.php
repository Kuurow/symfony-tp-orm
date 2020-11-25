<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\EditPersonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/person", name="person")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $doctrine = $this->getDoctrine(); // On récupère l'ORM
        $manager = $doctrine->getManager(); // On récupère le Manager
        $repositoryPerson = $manager->getRepository(Person::class); // On récupère la table Person

        return $this->render('person/index.html.twig', [
            'persons' => $repositoryPerson->findAll(),
        ]);
    }

    /**
     * @Route("/edit", name="personEdit")
     */
    public function edit(Request $request)
    {
        $personForm = new Person();
        $form = $this->createForm(EditPersonType::class, $personForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();
            $manager->persist($person);
            $manager->flush();
        }


        return $this->render('person/edit.html.twig', [
            'formPerson' => $form->createView(),
        ]);
    }
}
