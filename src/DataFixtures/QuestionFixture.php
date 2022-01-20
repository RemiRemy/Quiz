<?php

namespace App\DataFixtures;

use App\Entity\Response;
use App\Entity\Category;
use App\Entity\Question;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixture extends Fixture
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            (new Question())
                ->setStatement('Qui a écrit «Le vieil homme et la mer» ?')
                ->setFirstCategory($this->getCategory("Littérature"))
                ->addResponse(
                    $this->createResponse("Emile Zola"),
                    $this->createResponse("Victor Hugo"),
                    $this->createResponse("Ernest Hemingway", null, true),
                    $this->createResponse("Marcel Pagnol"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement('Qui était Roland Garros ?')
                ->setFirstCategory($this->getCategory("Sports"))
                ->addCategory($this->getCategory("Culture Générale"))
                ->addResponse(
                    $this->createResponse("Un écrivain"),
                    $this->createResponse("Un aviateur", null, true),
                    $this->createResponse("Un joueur de tennis"),
                    $this->createResponse("Un homme politique"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("Quel roi a signé l'édit de Nantes ?")
                ->setFirstCategory($this->getCategory("Histoire"))
                ->addCategory(
                    $this->getCategory("Culture Générale"),
                    $this->getCategory("Politique")
                )
                ->addResponse(
                    $this->createResponse("Louis XIV"),
                    $this->createResponse("François 1er"),
                    $this->createResponse("Henri IV", null, true),
                    $this->createResponse("Clovis 1er"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("En quelle année a été adopté le traité de maastricht ?")
                ->setFirstCategory($this->getCategory("Politique"))
                ->addCategory(
                    $this->getCategory("Culture Générale"),
                    $this->getCategory("Histoire")
                )
                ->addResponse(
                    $this->createResponse("1992", null, true),
                    $this->createResponse("1989"),
                    $this->createResponse("1968"),
                    $this->createResponse("2001"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("Comment s'appelle la tortue dans Boule & Bill ?")
                ->setFirstCategory($this->getCategory("Bande Dessinée"))
                ->addResponse(
                    $this->createResponse("Rosie"),
                    $this->createResponse("Caroline", null, true),
                    $this->createResponse("Franklin"),
                    $this->createResponse("Joséphine"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("«Bercy» désigne le ministère :")
                ->setFirstCategory($this->getCategory("Politique"))
                ->addCategory($this->getCategory("Économie"))
                ->addResponse(
                    $this->createResponse("de l'économie et des finances", null, true),
                    $this->createResponse("de l'éducation"),
                    $this->createResponse("des sports"),
                    $this->createResponse("de la culture"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("Qui a présidé le festival de Cannes en mai 2012 ?")
                ->setFirstCategory($this->getCategory("Films"))
                ->addCategory($this->getCategory("Art"))
                ->addResponse(
                    $this->createResponse("Jean Dujardin"),
                    $this->createResponse("Marilyn Monroe"),
                    $this->createResponse("Nanni Moretti", null, true),
                    $this->createResponse("Robert De Niro"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("Comment est désigné le Premier ministre en France ?")
                ->setFirstCategory($this->getCategory("Politique"))
                ->addCategory($this->getCategory("Culture Générale"))
                ->addResponse(
                    $this->createResponse("Le président de la République", null, true),
                    $this->createResponse("Le président du Sénat"),
                    $this->createResponse("Conseil des ministres"),
                    $this->createResponse("L'assemblée Nationale"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("Dans quelle ville siège la Banque centrale européenne ?")
                ->setFirstCategory($this->getCategory("Économie"))
                ->addResponse(
                    $this->createResponse("Paris"),
                    $this->createResponse("Strasbourg"),
                    $this->createResponse("Bruxelles"),
                    $this->createResponse("Francfort", null, true),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("Quel est le nom du principal indice boursier espagnol ?")
                ->setFirstCategory($this->getCategory("Économie"))
                ->addResponse(
                    $this->createResponse("Ibex35", null, true),
                    $this->createResponse("Cac40"),
                    $this->createResponse("Nasdaq"),
                    $this->createResponse("Dax"),
                )
                ->setStatus(true)
        );

        $manager->persist(
            (new Question())
                ->setStatement("Quels sont les prénoms des frères Winchester dans «Supernatural» ?")
                ->setFirstCategory($this->getCategory("Series"))
                ->addResponse(
                    $this->createResponse("Sam & Dean", null, true),
                    $this->createResponse("Brandon & Dylan"),
                    $this->createResponse("John & Dave"),
                    $this->createResponse("Kevin & Marvin"),
                )
                ->setStatus(true)
        );

        $manager->flush();
    }

    private function getCategory(string $label): Category {
        return $this->categoryRepository->firstOrCreate($label);
    }

    private function createResponse(string $answer, ?string $image = null, bool $isGood = false): Response {
        return (new Response())->setAnswer($answer)->setImage($image)->setGood($isGood);
    }
}
