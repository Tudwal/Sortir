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
            dump($event);

            // Récupération des dates utiles pour les if
            $startEvent = $event->getStartDateTime();
            $duration = $event->getDuration();
            $endEvent = clone $startEvent;
            $endEvent->add(new DateInterval('PT' . $duration . 'M'));
            $historyEvent = clone $endEvent;
            $historyEvent->add(new DateInterval('P1M'));


            // Si date du jour > date de clôture des inscriptions -> etat = clôturé
            if ($today > $event->getEndRegisterDate()) {
                $cloturee = $this->stateRepository->findOneBy(array('label' => 'Clôturée'));
                $event->setState($cloturee);
            }

            // si date du jour >= date de début et <= date de fin -> etat = en-cours
            if ($today >= $event->getStartDateTime() && $today <= $endEvent) {
                //dd($this->stateRepository->findOneBy(array('label' => 'En-cours')));
                $enCours = $this->stateRepository->findOneBy(array('label' => 'En-cours'));
                $event->setState($enCours);
            }

            // si date > date de fin -> état = terminée
            // if ($today > $endEvent) {
            //     $event->setState($this->stateRepository->findOneBy(["label => Terminée"]));
            // }

            // si date >= date fin + 1 mois -> état = historisée
            if ($today >= $historyEvent) {
                $historisee = $this->stateRepository->findOneBy(array('label' => 'Historisée'));
                $event->setState($historisee);
            }
        }
    }
}
