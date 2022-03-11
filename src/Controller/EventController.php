<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\State;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Form\EventTypeAPI;
use App\Form\ModelSearchType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use App\Repository\StateRepository;
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
            $search = $form->get('search')->getData();
            $user = $this->getUser();
            $eventList = $repoEvent->searchByFilter($data, $search, $user);
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
        $this->addFlash(
            'success',
            'Votre ' . $e->getName() . ' est supprimée!'
        );

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/event-create", name="event_create")
     */
    public function eventCreate(EntityManagerInterface $em, Request $req, StateRepository $stateRepo, CityRepository $cityRepo): Response
    {
        //dd('je suis dans la fonction event-create');
        $event = new Event();
        $cityList = $cityRepo->findAll();
        $participant = $this->getUser();

        /**
         * @var Participant $participant
         */
        $event->setCampus($this->getUser()->getCampus());
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setState($stateRepo->findOneBy(array('label' => 'Créée')));
            $event->setOrganizer($this->getUser());
            $event->addParticipant($this->getUser());
            $em->persist($event);
            $em->flush();
            $this->addFlash(
                'success',
                'Féliciation, votre ' . $event->getName() . ' est créée!'
            );

            return $this->redirectToRoute('home');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'danger',
                'Tu as un problème avec ta nouvelle sortie'
            );
        }
        return $this->render('event/create.html.twig', [
            'formulaire' => $form->createView(),
            'cityList' => $cityList,
        ]);
    }

    /**
     * @Route("/api/", name="api")
     */
    public function api(CityRepository $cityRepo): Response
    {
        $tab = $cityRepo->findAll();

        return $this->json($tab);
    }



    /**
     * @Route("/event-createAPI", name="event_createAPI")
     */
    public function eventCreate2(EntityManagerInterface $em, Request $req, StateRepository $stateRepo): Response
    {
        //dd('je suis dans la fonction event-createAPI');
        $event = new Event();
        $participant = $this->getUser();

        /**
         * @var Participant $participant
         */
        $event->setCampus($this->getUser()->getCampus());
        $form = $this->createForm(EventTypeAPI::class, $event);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setState($stateRepo->findOneBy(array('label' => 'Créée')));
            $event->setOrganizer($this->getUser());
            $event->addParticipant($this->getUser());
            $em->persist($event);
            $em->flush();

            $this->addFlash(
                'success',
                'Your new event is creates :' . $event->getName()
            );
            return $this->redirectToRoute('home');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'danger',
                'Tu as un problème avec ta nouvelle sortie'
            );
        }
        return $this->render('event/createAPI.html.twig', [
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

    /**
     * @Route("/register/{id}", name="event_register")
     */
    public function register($id, EventRepository $eventRepository, Event $event): Response
    {
        $nbParticipant = $eventRepository->findNbParticipant($id);
        dd($nbParticipant);
        $nbMaxParticipant = $event->getNbParticipantMax();

        if ($nbParticipant < $nbMaxParticipant) {
        }
        //     $nbParticipant = $this->getUser()->get
        // 
    }

    /**
     * @Route("/event-update/{id}", name="event_update")
     */
    public function eventUpdate(Event $event, EntityManagerInterface $em, Request $req, StateRepository $stateRepo, CityRepository $cityRepo): Response
    {
        $cityList = $cityRepo->findAll();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash(
                'success',
                'Féliciation, votre ' . $event->getName() . ' est modifiée!'
            );

            return $this->redirectToRoute('home');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'danger',
                'Tu as un problème avec la modification de ta sortie'
            );
        }
        return $this->render('event/update.html.twig', [
            'formulaire' => $form->createView(),
            'cityList' => $cityList,
        ]);
    }
}
