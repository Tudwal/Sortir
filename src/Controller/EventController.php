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
use Symfony\Component\Security\Core\User\UserInterface;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function eventList(EventRepository $repoEvent, CampusRepository $repoCampus, Request $request): Response
    {

        $eventList = $repoEvent->findAll();
        $campus = $repoCampus->findAll();

        $createSearchType = new ModelSearchType();
        $form = $this->createForm(EventSearchType::class, $createSearchType);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getUser();
            $eventList = $repoEvent->searchByFilter($data, $user);
        }

        return $this->render('event/index.html.twig', [
            'events' => $eventList,
            'campusList' => $campus,
            'formulaire' => $form->createView(),
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
    public function eventCreate(EntityManagerInterface $em, Request $req, UserInterface $user): Response
    {
        $event = new Event();
        $event->setOrganizer($this->getUser());
        //dd($this->getUser());

        $participant = $this->getUser();

        /**
         * @var Participant $participant
         */


        $event->setCampus($this->getUser()->getCampus());




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

    /**
     * @Route("/details/{id}", name="event_details")
     */
    public function detail(Event $e, EventRepository $repo, $id): Response
    {
        $events = $repo->find($id);
        
        return $this->render('event/detail.html.twig', [
            'event' => $e,
            'events' => $events,
        ]);
    }
}