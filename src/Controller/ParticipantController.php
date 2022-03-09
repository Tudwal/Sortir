<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
     * @Route("/update", name="app_update_participant")
     */
    public function update(ParticipantRepository $repo,  Request $req, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        /**
         * @var Participant $p 
         */
        $p = $this->getUser();


        $form = $this->createForm(ProfilType::class, $this->getUser());
        $form->handleRequest($req);

        $cleanPassword = $form->get('password')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            if ($cleanPassword) {
                dd('je suis dans le elseif');
                //haché le mot de passe et set le password + persit
                $hashedPassword = $passwordHasher->hashPassword(
                    $p,
                    $cleanPassword,
                );
                $p->setPassword($hashedPassword);
                $em->persist($p);
            }

            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('participant/updateProfil.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
}