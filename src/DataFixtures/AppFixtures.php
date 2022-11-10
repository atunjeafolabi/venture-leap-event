<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $types = [];
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        $this->seedTypes($manager);
        $this->seedEvents($manager);
    }

    private function seedTypes(ObjectManager $manager): void
    {
        foreach ($this->getBaseTypes() as $typeName) {
            $type = new Type();
            $type->setName($typeName);
            $this->types[] = $type;

            $manager->persist($type);
        }

        $manager->flush();
    }

    private function seedEvents(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 25; $i++) {
            $event = new Event();
            $event->setDetails($this->faker->text());
            $event->setType($this->types[array_rand($this->types)]);
            $event->setTimestamp();

            $manager->persist($event);
        }

        $manager->flush();
    }

    private function getBaseTypes(): array
    {
        return [
            'info',
            'warning',
            'error',
        ];
    }
}
