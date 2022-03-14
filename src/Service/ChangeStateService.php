<?php

namespace App\Service;

use App\Repository\EventRepository;
use App\Repository\StateRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping\Id;

class ChangeStateService
{
    private $eventRepository;
    private $stateRepository;

    public function __construct(EventRepository $eventRepoository, StateRepository $stateRepository)
    {
        $this->eventRepository = $eventRepoository;
        $this->stateRepository = $stateRepository;
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
            $endRegistration = $event->getEndRegisterDate();
            $duration = $event->getDuration();
            $endEvent = clone $startEvent;
            $endEvent->add(new DateInterval('PT' . $duration . 'M'));
            $historyEvent = clone $endEvent;
            $historyEvent->add(new DateInterval('P1M'));

            // MODIFICATION ETAT CLOTURE
            if ($today > $endRegistration && $event->getState()->getCode() == 'OPEN') {
                $cloturee = $this->stateRepository->findOneBy(array('code' => 'CLOS'));
                $event->setState($cloturee);
            }

            // MODIFICATION ETAT EN-COURS
            if ($today >= $endRegistration && $today <= $endEvent) {
                $enCours = $this->stateRepository->findOneBy(array('code' => 'ENCO'));
                $event->setState($enCours);
            }

            // MODIFICATION ETAT TERMINEE
            if ($today > $endEvent && $today < $historyEvent) {
                $event->setState($this->stateRepository->findOneBy(['code' => 'PAST']));
            }

            // MODIFICATION ETAT HISTORISEE si date >= date fin + 1 mois -> état = historisée
            // if ($today >= $historyEvent) {
            //     // dd('je suis dans le if 3');
            //     $historisee = $this->stateRepository->findOneBy(array('code' => 'PAST'));
            //     $event->setState($historisee);
            // }
        }
    }
}
