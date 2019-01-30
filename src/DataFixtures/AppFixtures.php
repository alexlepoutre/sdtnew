<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Project;
use App\Entity\TypeInter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
         * @var UserPasswordEncoderInterface
         */
        private $encoder;
        /**
         * @var EntityManagerInterface
         */
        private $entityManager;

        /**
         * AppFixtures constructor.
         * @param UserPasswordEncoderInterface $userPasswordEncoder
         * @param EntityManagerInterface $entityManager
         */
        public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
        {
            $this->encoder = $userPasswordEncoder;
            $this->entityManager = $entityManager;
        }

    public function load(ObjectManager $manager )
    {
        $user = new User();
        
        $user->setUsername('Admin');
        $user->setName('Admin');
        $user->setFirstName('Admin');
        $user->setMail('alepoutre@itroom.fr');
        
        $user->setPassword($this->encoder->encodePassword($user, 'admin'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $client = new Client();
        $client->setName('IT-Room');
        $manager->persist($client);

        $project = new Project();
        $project->setName('Skill Shaker');
        $manager->persist($project);

        $typeInter = new TypeInter();
        $typeInter->setName('Dev Ionic');
        $manager->persist($typeInter);

        $task = new Task();
        $task->setSubject('Mise en place des alertes PUSH');
        $task->setContent('Implémentation des nouvelles API');
        $task->setrefMantis('001');
        $task->setDuration(3); 
        $task->setDate(new \DateTime('30-01-2019'));
        $task->setCreatedAt(new \DateTime());
        $task->setUser($user);
        $task->setClient($client);
        $task->setTypeInter($typeInter);
        $task->setProject($project);
        $manager->persist($task);

        $manager->flush();


    }
}
