<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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

        // $this->addEvents();
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

        for($i = 0; $i<10; $i++){
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

    // public function addEvents()
    // {
    //     $piscine = new Event();

    //     $piscine->setName('Sortie piscine')

    // }
}