<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class ModelSearchType
{

    /**
     * @var string
     */
    public $campus;

    /**
     * 
     * @var string
     */
    public $search;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @var \DateTimeImmutable
     */
    public $startDate;

    /**
     * @Assert\Type("\DateTimeInterface")
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
