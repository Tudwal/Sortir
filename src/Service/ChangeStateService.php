<?php

namespace App\Service;

use App\Repository\EventRepository;
use App\Repository\StateRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;

class ChangeStateService
{
    private $eventRepository;
    private $stateRepository;
    private $entityManagerInterface;

    public function __construct(EventRepository $eventRepoository, StateRepository $stateRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->eventRepository = $eventRepoository;
        $this->stateRepository = $stateRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function change()
    {
        // Récupération de la date du jour
        $today = new DateTime('now', new DateTimeZone('Europe/Paris'));

        // Récupération de la liste des sorties
        $eventList = $this->eventRepository->findAll();

        foreach ($eventList as $event) {

            // Récupération des dates utiles pour les if
            $startEvent = $event->getStartDateTime();
            $endRegistration = $event->getEndRegisterDate()->add(new DateInterval('PT23H'));
            $duration = $event->getDuration();
            $endEvent = clone $startEvent;
            $endEvent->add(new DateInterval('PT' . $duration . 'M'));
            $historyEvent = clone $startEvent;
            $historyEvent->add(new DateInterval('P1M'));


            // MODIFICATION ETAT OPEN
            if ($today < $endRegistration) {
                $open = $this->stateRepository->findOneBy(array('code' => 'OPEN'));
                $event->setState($open);
            }

            // MODIFICATION ETAT CLOS
            if ($today >= $endRegistration && $today <= $endEvent ) {
                $clos = $this->stateRepository->findOneBy(array('code' => 'CLOS'));
                $event->setState($clos);
            }

            // MODIFICATION ETAT EN-COURS
            if ($today == $startEvent) {
                $enCours = $this->stateRepository->findOneBy(array('code' => 'ENCO'));
                $event->setState($enCours);
            }

            // MODIFICATION ETAT TERMINEE
            if ($today > $endEvent && $today < $historyEvent) {
                $event->setState($this->stateRepository->findOneBy(['code' => 'FINI']));
            }

            // MODIFICATION ETAT HISTORISEE si date >= date fin + 1 mois -> état = historisée
            if ($today >= $historyEvent) {
                $historisee = $this->stateRepository->findOneBy(array('code' => 'HIST'));
                $event->setState($historisee);
            }

            $this->entityManagerInterface->persist($event);
            $this->entityManagerInterface->flush();
        }
    }
}
