<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{

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
    public function update(ParticipantRepository $repo, Participant $p, Request $req, EntityManagerInterface $em): Response
    {
<<<<<<< HEAD
        $pseudo = $p->getPseudo();
        $tab = $repo->findBy([], ["pseudo"]);
        // if(in_array($pseudo, $tab))
=======
       // $pseudo = $p->getPseudo();
      //  $tab = $repo->findBy([], ["pseudo"]);
        // if(in_array($pseudo, $tab))
        //&& !(in_array($pseudo, $tab))
        $p = new Participant();
>>>>>>> 85174e0fc1b050f341f8a340693be0dabfba6431
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
