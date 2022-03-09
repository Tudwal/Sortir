<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class ModelSearchType
{

    /**
     * @Assert\Choice({"Chartres de Bretagne", "Saint-Herblain", "La Roche sur Yon"})
     * @var string
     */
    public $campus;

    /**
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    public $startDate;

    /**
     * @Assert\DateTime()
     * @var \DateTimeImmutable
     */
    public $endDate;

    /**
     */
    public $eventOrganizer;

    /**
     * 
     */
    public $eventRegister;

    /**
     * 
     */
    public $eventNotRegister;

    /**
     * 
     */
    public $pastEvent;
}
