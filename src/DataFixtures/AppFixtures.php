<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use App\Entity\Participant;
use App\Entity\State;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private ObjectManager $manager;
    private UserPasswordHasherInterface $hacher;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->addCampus();

        $this->addParticipants();

        $this->addState();

        $this->addCity();

        $this->addLocations();

        $this->addEvents();
    }

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {

        $this->hasher = $passwordHasher;
    }


    public function addCampus()
    {

        $chartresDeBretagne = new Campus();
        $chartresDeBretagne->setName('Chartres de Bretagne');
        $campus[0] = $chartresDeBretagne;
        $this->manager->persist($chartresDeBretagne);

        $stHerblain = new Campus();
        $stHerblain->setName('Saint-Herblain');
        $campus[1] = $stHerblain;
        $this->manager->persist($stHerblain);

        $laRocheSurYon = new Campus();
        $laRocheSurYon->setName('La Roche sur Yon');
        $campus[2] = $laRocheSurYon;
        $this->manager->persist($laRocheSurYon);

        $this->manager->flush();
    }

    public function addParticipants()
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $participant = new Participant();

            $participant->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPhone($faker->phoneNumber)
                ->setPseudo($faker->word(10))
                ->setEmail($faker->email)
                ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
                ->setPassword($this->hasher->hashPassword($participant, '123'))
                ->setRoles(['ROLE_USER'])
                ->setIsActif(true);

            $this->manager->persist($participant);
        }

        $this->manager->flush();
    }

    public function addState()
    {
        $creee = new State();
        $creee->setLabel('Créée');
        $this->manager->persist($creee);

        $ouverte = new State();
        $ouverte->setLabel('Ouverte');
        $this->manager->persist($ouverte);

        $cloturee = new State();
        $cloturee->setLabel('Clôturée');
        $this->manager->persist($cloturee);

        $enCours = new State();
        $enCours->setLabel('Activité en cours');
        $this->manager->persist($enCours);

        $passee = new State();
        $passee->setLabel('Passée');
        $this->manager->persist($passee);

        $annulee = new State();
        $annulee->setLabel('Annulée');
        $this->manager->persist($annulee);

        $this->manager->flush();
    }


    public function addCity()
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $city = new City();
            $city->setName($faker->city)
                ->setPostalCode(Address::postcode());

            $this->manager->persist($city);
        }


        $this->manager->flush();
    }

    public function addLocations()
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $location = new Location();

            $location->setName($faker->streetName)
                ->setStreet($faker->streetAddress)
                ->setLatitude($faker->randomNumber(5, true))
                ->setLongitude($faker->randomNumber(5, true))
                ->setCity($faker->randomElement($this->manager->getRepository(City::class)->findAll()));

            $this->manager->persist($location);
        }

        $this->manager->flush();
    }


    public function addEvents()
    {
        $faker = Factory::create('fr_FR');

        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $past = (new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('-18 days');
        $today = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $end = $today->add(new DateInterval('P15D'));

        $piscine = new Event();
        $piscine->setName('Sortie piscine')
            ->setStartDateTime($past)
            ->setDuration(90)
            ->setEndRegisterDate($end)
            ->setNbParticipantMax(10)
            ->setDetails('Nager la brasse coulée en toute liberté et sans complexe')
            ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($piscine);

        $patinoire = new Event();
        $patinoire->setName('Sortie patinoire')
            ->setStartDateTime($now)
            ->setDuration(90)
            ->setEndRegisterDate($end)
            ->setNbParticipantMax(10)
            ->setDetails('Patiner en toute liberté et sans complexe')
            ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($patinoire);

        $cinema = new Event();
        $cinema->setName('Sortie cinéma')
            ->setStartDateTime($now)
            ->setDuration(90)
            ->setEndRegisterDate($end)
            ->setNbParticipantMax(10)
            ->setDetails('Aller au cinéma en toute liberté et sans complexe')
            ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($cinema);

        $karaoke = new Event();
        $karaoke->setName('Sortie karaoké')
            ->setStartDateTime($now)
            ->setDuration(90)
            ->setEndRegisterDate($end)
            ->setNbParticipantMax(10)
            ->setDetails('Chanter en toute liberté et sans complexe')
            ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($karaoke);

        $restaurant = new Event();
        $restaurant->setName('Sortie restaurant')
            ->setStartDateTime($now)
            ->setDuration(90)
            ->setEndRegisterDate($end)
            ->setNbParticipantMax(10)
            ->setDetails('Manger en toute liberté et sans complexe')
            ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($restaurant);

        $bowling = new Event();
        $bowling->setName('Sortie bowling')
            ->setStartDateTime($now)
            ->setDuration(90)
            ->setEndRegisterDate($end)
            ->setNbParticipantMax(10)
            ->setDetails('Bowler en toute liberté et sans complexe')
            ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($bowling);

        $plage = new Event();
        $plage->setName('Sortie plage')
            ->setStartDateTime($now)
            ->setDuration(90)
            ->setEndRegisterDate($end)
            ->setNbParticipantMax(10)
            ->setDetails('Nager en toute liberté et sans complexe')
            ->setState($faker->randomElement($this->manager->getRepository(State::class)->findAll()))
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($plage);

        $this->manager->flush();
    }
}
