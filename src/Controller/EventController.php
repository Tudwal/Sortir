<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\State;
use App\Form\EventType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\EventRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function eventList(EventRepository $repoE, CampusRepository $repoC): Response
    {
        $events = $repoE->findAll();
        $campus = $repoC->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'campusList' => $campus
        ]);
    }


    /**
     * @Route("/event-delete/{id}", name="event_delete")
     */
    public function eventDelete(Event $e, EntityManagerInterface $em): Response
    {
        $em->remove($e);
        $em->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/event-create", name="event_create")
     */
    public function eventCreate(EntityManagerInterface $em, Request $req): Response
    {
        $event = new Event();
        $event->setOrganizer($this->getUser()); //getParticipant() n'est pas connu !!
        //$event->setCampus($this->getUser()->getCampus());


        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($req);


        if ($form->isSubmitted() && $form->isValid()) {

            $state = new State;
            $state->setLabel('Created');

            $event->setState($state);

            $this->addFlash(
                'success',
                'Your new event is creates :' . $event->getName()
            );
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('home');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'danger',
                'Tu as un problème avec ta nouvelle sortie'
            );
        }
        return $this->render('event/create.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
}
