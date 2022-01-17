<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create();

        $categories = [];
        foreach (["Histoire", "Film", "Sport", "Littérature"] as $categoryLabel) {
            $category = new Category();
            $category->setLabel($categoryLabel);
            $manager->persist($category);

            $categories[] = $category;
        }

        for($i = 0; $i < 10; $i++) {
            $question = new Question();

            $question->setStatement($faker->sentence . " ?")
                ->setFirstCategory($faker->randomElement($categories))
                ->addCategory(...$faker->randomElements($categories, rand(0, 2)))
                ->setStatus($faker->randomElement([0, 1]));

            $manager->persist($question);
        }

        $manager->flush();
    }
}
