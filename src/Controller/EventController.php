<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\State;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Form\ModelSearchType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function eventList(EventRepository $repoE, CampusRepository $repoC, Request $request): Response
    {
        $events = $repoE->findAll();
        $campus = $repoC->findAll();

        $createSearchType = new ModelSearchType();
        $form = $this->createForm(EventSearchType::class, $createSearchType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'campusList' => $campus,
            'formulaire' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {

        $createSearchType = new ModelSearchType();
        $form = $this->createForm(EventSearchType::class, $createSearchType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('event/search.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }


    /**
     * @Route("/event-delete/{id}", name="event_delete")
     */
    public function eventDelete(Event $e, Request $req, EntityManagerInterface $em): Response
    {
        $em->remove($e);
        $em->flush();

        return $this->redirectToRoute('event/index.html.twig');
    }

    /**
     * @Route("/event-create", name="event_create")
     */
    public function eventCreate(Request $req, EntityManagerInterface $em): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($event);
        if ($form->isSubmitted() && $form->isValid()) {

            $state = new State;
            $state->setLabel('Created');


            $event->setState($state);
            $event->setOrganizer($this->getUser()); //getParticipant() n'est pas connu !!
            $event->setCampus($this->getUser()->getCampus);


            $this->addFlash(
                'success',
                'Your new event is creates :' . $event->getName()
            );
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event/index.html.twig');
        } else {
            $this->addFlash(
                'danger',
                'You have a problem with your new event'
            );
        }

        return $this->redirectToRoute('event/create.html.twig');
    }
}
