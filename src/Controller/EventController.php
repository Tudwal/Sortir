<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Form\EventTypeAPI;
use App\Form\ModelSearchType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Service\ChangeStateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/home")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function eventList(StateRepository $repoState, ChangeStateService $changeStateService, EventRepository $repoEvent, CampusRepository $repoCampus, Request $request): Response
    {
        $changeStateService->change();

        // recup les id state 1, 2 et 3
        $eventList = $repoEvent->findAll();
        $campus = $repoCampus->findAll();
        $stateCrea = $repoState->findBy(array('code' => 'CREE'));
        $stateOpen = $repoState->findBy(array('code' => 'OPEN'));
        $stateClos = $repoState->findBy(array('code' => 'CLOS'));
        $createSearchType = new ModelSearchType();
        $form = $this->createForm(EventSearchType::class, $createSearchType);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData(); // $data = $createSearchType il contient la même chose
            $search = $form->get('search')->getData();
            $eventList = $repoEvent->searchByFilter($data, $search);
        }

        return $this->render('event/index.html.twig', [
            'stateCrea' => $stateCrea,
            'stateOpen' => $stateOpen,
            'stateClos' => $stateClos,
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
        $user = $this->getUser();
        //Test si l'event est ouvert ou cloturé
        if ($e->getState()->getCode() == 'OPEN' or $e->getState()->getCode() == 'CLOS') {
            //Test si l'user est organisateur
            if ($e->getOrganizer() == $user) {
                $em->remove($e);
                $em->flush();
                $this->addFlash(
                    'success',
                    'Votre ' . $e->getName() . ' est supprimée!'
                );
            }
        }


        return $this->redirectToRoute('home');
    }

    // /**
    //  * @Route("/event-create", name="event_create")
    //  */
    // public function eventCreate(EntityManagerInterface $em, Request $req, StateRepository $stateRepo, CityRepository $cityRepo): Response
    // {
    //     //dd('je suis dans la fonction event-create');
    //     $event = new Event();
    //     $cityList = $cityRepo->findAll();
    //     $participant = $this->getUser();

    //     /**
    //      * @var Participant $participant
    //      */
    //     $event->setCampus($this->getUser()->getCampus());
    //     $form = $this->createForm(EventType::class, $event);
    //     $form->handleRequest($req);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $event->setState($stateRepo->findOneBy(array('label' => 'Créée')));
    //         $event->setOrganizer($this->getUser());
    //         $event->addParticipant($this->getUser());
    //         $em->persist($event);
    //         $em->flush();
    //         $this->addFlash(
    //             'success',
    //             'Féliciation, votre ' . $event->getName() . ' est créée!'
    //         );

    //         return $this->redirectToRoute('home');
    //     } elseif ($form->isSubmitted() && !$form->isValid()) {
    //         $this->addFlash(
    //             'danger',
    //             'Tu as un problème avec ta nouvelle sortie'
    //         );
    //     }
    //     return $this->render('event/create.html.twig', [
    //         'formulaire' => $form->createView(),
    //         'cityList' => $cityList,
    //     ]);
    // }

    /**
     * @Route("/api/", name="api")
     */
    public function api(CityRepository $cityRepo): Response
    {
        $tab = $cityRepo->findAll();

        return $this->json($tab,200,[],['groups'=>"villes"]);
    }



    /**
     * @Route("/event-create", name="event_create")
     */
    public function eventCreate(EntityManagerInterface $em, Request $req, StateRepository $stateRepo): Response
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
     * @Route("/publish/{id}", name="event_publish")
     */
    public function publish(StateRepository $stateRepo ,Event $e,EntityManagerInterface $em , EventRepository $repo, $id): Response
    {
        $e->setState($stateRepo->findOneBy(array('code' => 'OPEN')));
                        $em->persist($e);
                        $em->flush();
                        
                        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/cancel/{id}", name="event_cancel")
     */
    public function cancel(StateRepository $stateRepo ,Event $e,EntityManagerInterface $em , EventRepository $repo, $id, Request $req): Response
    {
        $events = $repo->find($id);
                       
        if($req->get('motif_cancel'))
        {
        $e->setDetails($req->get('motif_cancel'));
        $e->setState($stateRepo->findOneBy(array('code' => 'ANNU')));
        $em->persist($e);
        $em->flush();
        
        return $this->redirectToRoute('home');
        }        
        return $this->render('event/cancel.html.twig',[
            'event'=>$e,
            'events'=>$events,
        ]);
    }

    /**
     * @Route("/register/{id}", name="event_register")
     */
    public function register(StateRepository $stateRepo, EventRepository $eventRepository, Event $event, EntityManagerInterface $em, $id): Response
    {
        $nbParticipants = count($event->getParticipants());
        $nbParticipantsMax = $event->getNbParticipantMax();
        $user = $this->getUser();
        // $tabEvent = $eventRepository->findBy(array("participants=>$participants"));

        //dd($tabEvent);

        //Test si l'event est ouvert
        if ($event->getState()->getCode() == 'OPEN') {
            //Test l'user est orga?
            if ($event->getOrganizer() != $user) {
                //Test le user est deja dans l'event?
                if (!$event->getParticipants()->contains($user)) {
                    //Test du nombre de participant dans l'event
                    if ($nbParticipants < $nbParticipantsMax) {
                        $event->addParticipant($user);
                        $em->persist($user);
                        $em->flush();
                    }
                    $newNbParticipants = count($event->getParticipants());
                    //dd($newNbParticipants);
                    if ($newNbParticipants == $nbParticipantsMax) {
                        $event->setState($stateRepo->findOneBy(array('code' => 'CLOS')));
                        $em->persist($event);
                        $em->flush();
                    }
                }
            }
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/unRegister/{id}", name="event_unRegister")
     */
    public function unRegister(StateRepository $stateRepo, EventRepository $eventRepository, Event $event, EntityManagerInterface $em): Response
    {
        $nbParticipants = count($event->getParticipants());
        $nbParticipantsMax = $event->getNbParticipantMax();
        $user = $this->getUser();

        //Test, l'event est il ouvert?
        if ($event->getState()->getCode() == 'OPEN' || $event->getState()->getCode() == 'CLOS') {
            //Test si l'user est inscrit
            if ($event->getParticipants()->contains($user)) {
                //Test si il y'a des participants inscrits
                if ($nbParticipants > 0) {
                    $event->removeParticipant($user);
                    $em->persist($user);
                    $em->flush();
                }
                $newNbParticipants = count($event->getParticipants());
                if ($newNbParticipants < $nbParticipantsMax) {
                    $event->setState($stateRepo->findOneBy(array('code' => 'OPEN')));
                    $em->persist($event);
                    $em->flush();
                }
            }
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/event-update/{id}", name="event_update")
     */
    public function eventUpdate(Event $event, EntityManagerInterface $em, Request $req, StateRepository $stateRepo, CityRepository $cityRepo): Response
    {
        $user = $this->getUser();
        $cityList = $cityRepo->findAll();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($req);

        //Test si l'event n'est pas encore publier
        if ($event->getState()->getCode() == 'CREE') {
            //Test si l'user est organisateur
            if ($event->getOrganizer() == $user) {
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
            }
        }



        return $this->render('event/update.html.twig', [
            'formulaire' => $form->createView(),
            'cityList' => $cityList,
        ]);
    }
    /**
     * @Route("/event-updateAPI/{id}", name="event_updateAPI")
     */
    public function eventUpdateAPI(Event $event, EntityManagerInterface $em, Request $req, StateRepository $stateRepo, CityRepository $cityRepo): Response
    {
        $user = $this->getUser();
        $cityList = $cityRepo->findAll();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($req);

        //Test si l'event n'est pas encore publier
        if ($event->getState()->getId() == 1) {
            //Test si l'user est organisateur
            if ($event->getOrganizer() == $user) {
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
            }
        }



        return $this->render('event/updateAPI.html.twig', [
            'formulaire' => $form->createView(),

        ]);
    }
}
