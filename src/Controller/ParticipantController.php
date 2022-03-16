<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function update(ParticipantRepository $repo,  Request $req, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        /**
         * @var Participant $p 
         */
        $p = $this->getUser();


        $form = $this->createForm(ProfilType::class, $this->getUser());
        $form->handleRequest($req);
  
        $cleanPassword = $form->get('password')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            /**@var UploadedFile $imgFile */
            $imgFile = $form->get('image')->getData();

            if ($imgFile) {
                $originalFileName = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $imgFile->guessExtension();

                try {
                    $imgFile->move(
                        $this->getParameter('image_directory'),
                        $newFileName
                    );
                } catch (FileException $exeption) {
                }
                $p->setImgName($newFileName);
                $em->persist($p);
            }

            if ($cleanPassword) {
                //haché le mot de passe et set le password + persit
                $hashedPassword = $passwordHasher->hashPassword(
                    $p,
                    $cleanPassword,
                );
                $p->setPassword($hashedPassword);
                $em->persist($p);
            }
            $this->addFlash(
                'success',
                'Votre profil est modifié'
            );
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('participant/updateProfil.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
}