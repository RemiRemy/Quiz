<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $statement;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'boolean')]
    private $status;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'ManyToMany')]
    private $categories;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Response::class, cascade: ['persist', 'remove'])]
    private $responses;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->responses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatement(): ?string
    {
        return $this->statement;
    }

    public function setStatement(string $statement): self
    {
        $this->statement = $statement;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFirstCategory(): ?Category
    {
        return $this->category;
    }

    public function setFirstCategory(?Category $category): self
    {
        $this->category = $category;
        $this->addCategory($category);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category ...$categories): self
    {
        foreach ($categories as $category) {
            if (!$this->categories->contains($category)) {
                $this->categories[] = $category;
            }
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response ...$responses): self
    {
        foreach ( $responses as $response) {
            if (!$this->responses->contains($response)) {
                $this->responses[] = $response;
                $response->setQuestion($this);
            }
        }

        return $this;
    }

    /**
     * Ajoute des réponses vide à une question
     *
     * @param int $amount - nombre de réponses vide à ajouter
     * @return $this
     */
    public function requiredResponse(int $amount): self {
        for($i = 0; $i < $amount; $i++) {
            $this->addResponse(new Response());
        }

        return $this;
    }

    public function removeAnswer(Response $response): self
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getQuestion() === $this) {
                $response->setQuestion(null);
            }
        }

        return $this;
    }
}
