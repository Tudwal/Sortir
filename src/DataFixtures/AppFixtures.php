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


            $angelo = new Participant();
            $angelo->setFirstName('Angelo')
                ->setLastName('Fernandes')
                ->setPhone($faker->phoneNumber)
                ->setPseudo($faker->word(10))
                ->setEmail($faker->email)
                ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
                ->setPassword($this->hasher->hashPassword($angelo, '123'))
                ->setRoles(['ROLE_USER'])
                ->setIsActif(true)
                ->setImgName('licorne5.png');
            $this->manager->persist($angelo);

        $tudwal = new Participant();
        $tudwal->setFirstName('Tudwal')
            ->setLastName('Corlouer')
            ->setPhone($faker->phoneNumber)
            ->setPseudo('bud')
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($tudwal, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne6.png');
        $this->manager->persist($tudwal);

        $jerome = new Participant();
        $jerome->setFirstName('Jérôme')
            ->setLastName('Donal')
            ->setPhone($faker->phoneNumber)
            ->setPseudo($faker->word(10))
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($jerome, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne1.png');
        $this->manager->persist($jerome);

        $claire = new Participant();
        $claire->setFirstName('Claire')
            ->setLastName('Goarnisson')
            ->setPhone($faker->phoneNumber)
            ->setPseudo($faker->word(10))
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($claire, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne7.png');
        $this->manager->persist($claire);

        $sylvain = new Participant();
        $sylvain->setFirstName('Sylvain')
            ->setLastName('Tropée')
            ->setPhone($faker->phoneNumber)
            ->setPseudo('unicornMen')
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($sylvain, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne2.jpg');
        $this->manager->persist($sylvain);

        $annest = new Participant();
        $annest->setFirstName('Annest')
            ->setLastName('Wheldon')
            ->setPhone($faker->phoneNumber)
            ->setPseudo($faker->word(10))
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($annest, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne4.jpg');
        $this->manager->persist($annest);

        $roxane = new Participant();
        $roxane->setFirstName('Roxanne')
            ->setLastName('Houlgatte')
            ->setPhone($faker->phoneNumber)
            ->setPseudo($faker->word(10))
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($roxane, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne3.png');
        $this->manager->persist($roxane);

        $najim = new Participant();
        $najim->setFirstName('Nagim')
            ->setLastName('Amokhtari')
            ->setPhone($faker->phoneNumber)
            ->setPseudo($faker->word(10))
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($najim, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne9.jpg');
        $this->manager->persist($najim);

        $lucas = new Participant();
        $lucas->setFirstName('Lucas')
            ->setLastName('Feat')
            ->setPhone($faker->phoneNumber)
            ->setPseudo($faker->word(10))
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($lucas, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne8.jpg');
        $this->manager->persist($lucas);

        $thimotee = new Participant();
        $thimotee->setFirstName('Timothée')
            ->setLastName('Bertin')
            ->setPhone($faker->phoneNumber)
            ->setPseudo($faker->word(10))
            ->setEmail($faker->email)
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setPassword($this->hasher->hashPassword($thimotee, '123'))
            ->setRoles(['ROLE_USER'])
            ->setIsActif(true)
            ->setImgName('licorne10.jpg');
        $this->manager->persist($thimotee);

        $this->manager->flush();
    }

    public function addState()
    {
        $creee = new State();
        $creee->setLabel('Créée');
        $creee->setCode('CREE');
        $this->manager->persist($creee);

        $ouverte = new State();
        $ouverte->setLabel('Ouverte');
        $ouverte->setCode('OPEN');
        $this->manager->persist($ouverte);

        $cloturee = new State();
        $cloturee->setLabel('Clôturée');
        $cloturee->setCode('CLOS');
        $this->manager->persist($cloturee);

        $enCours = new State();
        $enCours->setLabel('Activité en cours');
        $enCours->setCode('ENCO');
        $this->manager->persist($enCours);

        $passee = new State();
        $passee->setLabel('Terminée');
        $passee->setCode('FINI');
        $this->manager->persist($passee);

        $annulee = new State();
        $annulee->setLabel('Annulée');
        $annulee->setCode('ANNU');
        $this->manager->persist($annulee);

        $historisee = new State();
        $historisee->setLabel('Historisée');
        $historisee->setCode('HIST');
        $this->manager->persist($historisee);

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

        $magicCity = new City();
        $magicCity->setName('Magic City')
                    ->setPostalCode('12345');
        $this->manager->persist($magicCity);

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

        $chateauDesNuages = new Location();
        $chateauDesNuages->setName('Château des nuages')
                        ->setStreet('rue des arcs en ciel')
                        ->setLatitude('789')
                        ->setLongitude('456')
                        ->setCity($this->manager->getRepository(City::class)->findOneBy(array('name'=> 'Magic City')));

        $this->manager->persist($chateauDesNuages);

        $this->manager->flush();
    }


    public function addEvents()
    {
        $faker = Factory::create('fr_FR');

        $piscine = new Event();
        $piscine->setName('Sortie piscine')
            ->setStartDateTime((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+ 2 days'))
            ->setDuration(90)
            ->setEndRegisterDate((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('- 10 days'))
            ->setNbParticipantMax(3)
            ->setDetails('Nager la brasse coulée en toute liberté et sans complexe')
            ->setState($this->manager->getRepository(State::class)->findOneBy(array('code' => 'CLOS'))) /// état clôturée
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Donal')))
            ->addParticipant($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Corlouer')));

        $this->manager->persist($piscine);

        $patinoire = new Event();
        $patinoire->setName('Sortie patinoire')
            ->setStartDateTime((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+ 2 days'))
            ->setDuration(90)
            ->setEndRegisterDate((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('- 1 days'))
            ->setNbParticipantMax(15)
            ->setDetails('Patiner en toute liberté et sans complexe')
            ->setState($this->manager->getRepository(State::class)->findOneBy(array('code' => 'CLOS'))) // état clôturée
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()))
            ->addParticipant($faker->randomElement($this->manager->getRepository(Participant::class)->findAll(), 3));

        $this->manager->persist($patinoire);

        $cinema = new Event();
        $cinema->setName('Sortie cinéma')
            ->setStartDateTime((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('-2 days'))
            ->setDuration(90)
            ->setEndRegisterDate((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('-15 days'))
            ->setNbParticipantMax(5)
            ->setDetails('Aller au cinéma en toute liberté et sans complexe')
            ->setState($this->manager->getRepository(State::class)->findOneBy(array('code' => 'FINI'))) // état terminée
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Tropée')))
            ->addParticipant($faker->randomElement($this->manager->getRepository(Participant::class)->findAll(), 2));

        $this->manager->persist($cinema);

        $karaoke = new Event();
        $karaoke->setName('Sortie karaoké')
            ->setStartDateTime((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+ 25 days'))
            ->setDuration(90)
            ->setEndRegisterDate((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+2 days'))
            ->setNbParticipantMax(3)
            ->setDetails('Chanter en toute liberté et sans complexe')
            ->setState($this->manager->getRepository(State::class)->findOneBy(array('code' => 'OPEN'))) // état ouvert
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Donal')))
            ->addParticipant($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Donal')))
            ->addParticipant($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Corlouer')));

        $this->manager->persist($karaoke);

        $restaurant = new Event();
        $restaurant->setName('Sortie restaurant')
            ->setStartDateTime((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+ 10 days'))
            ->setDuration(90)
            ->setEndRegisterDate((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+5 days'))
            ->setNbParticipantMax(4)
            ->setDetails('Manger en toute liberté et sans complexe')
            ->setState($this->manager->getRepository(State::class)->findOneBy(array('code' => 'OPEN'))) // état ouvert
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Goarnisson')))
            ->addParticipant($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Goarnisson')))
            ->addParticipant($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Corlouer')))
            ->addParticipant($this->manager->getRepository(Participant::class)->findOneBy(array('lastName'=>'Donal')));

        $this->manager->persist($restaurant);

        $bowling = new Event();
        $bowling->setName('Sortie bowling')
            ->setStartDateTime((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+ 10 days'))
            ->setDuration(90)
            ->setEndRegisterDate((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+4 days'))
            ->setNbParticipantMax(10)
            ->setDetails('Bowler en toute liberté et sans complexe')
            ->setState($this->manager->getRepository(State::class)->findOneBy(array('code' => 'OPEN'))) // état ouvert
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()))
            ->addParticipant($faker->randomElement($this->manager->getRepository(Participant::class)->findAll(), 4));

        $this->manager->persist($bowling);

        $plage = new Event();
        $plage->setName('Sortie plage')
            ->setStartDateTime((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+20 days'))
            ->setDuration(90)
            ->setEndRegisterDate((new DateTime('now', new DateTimeZone('Europe/Paris')))->modify('+10 days'))
            ->setNbParticipantMax(10)
            ->setDetails('Nager en toute liberté et sans complexe')
            ->setState($this->manager->getRepository(State::class)->findOneBy(array('code' => 'OPEN'))) // état ouvert
            ->setLocation($faker->randomElement($this->manager->getRepository(Location::class)->findAll()))
            ->setCampus($faker->randomElement($this->manager->getRepository(Campus::class)->findAll()))
            ->setOrganizer($faker->randomElement($this->manager->getRepository(Participant::class)->findAll()));

        $this->manager->persist($plage);

        $this->manager->flush();
    }
}
