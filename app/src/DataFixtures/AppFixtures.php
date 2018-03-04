<?php

namespace App\DataFixtures;

use App\Entity\Meal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMeals($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function loadMeals(ObjectManager $manager)
    {
        $hoursInterval = 0;
        foreach ($this->getMealData($manager) as [$text, $caloriesNumber, $user]) {
            $createdAt = (new \DateTime('yesterday'))->add((new \DateInterval("PT{$hoursInterval}H")));
            if ($createdAt >= new \DateTime()) {
                $hoursInterval = 0;
                $createdAt = new \DateTime();
            }

            $meal = (new Meal())
                ->setText($text)
                ->setCaloriesNumber($caloriesNumber)
                ->setUser($user)->setCreatedAt(
                   $createdAt
                );

            $manager->persist($meal);
            $hoursInterval += 2;
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
            ['Adam Doe', 'adam_user', 'kitten', 'adam_user@symfony.com', ['ROLE_USER']],
        ];
    }

    private function getMealData(ObjectManager $manager)
    {
        $meals = [];
        $users = $manager->getRepository(User::class)->findAll();
        for ($i = 0; $i <= 55; $i++) {
            $randomUserKey = array_rand($users, 1);
            $meals[] = [
                'Dummy meal - ' . $i,
                rand(50, 150),
                $users[$randomUserKey]
            ];
        }

        return $meals;
    }
}
