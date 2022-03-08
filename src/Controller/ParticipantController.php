<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/profil/{id}", name="app_view_participant")
     */
    public function view(Participant $p): Response
    {
        return $this->render('participant/index.html.twig', [
            'participant' => $p,
        ]);
    }

    /**
     * @Route("/update/{id}", name="app_update_participant")
     */
    public function update(Participant $p ,Request $req, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProfilType::class, $p);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('participant/updateProfil.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
}