<?php
namespace App\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Program", mappedBy="category")
     */
    private $program;
    public function __construct()
    {
        $this->program = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @return Collection|Program[]
     */
    public function getProgram(): Collection
    {
        return $this->program;
    }
    public function addProgram(Program $program): self
    {
        if (!$this->program->contains($program)) {
            $this->program[] = $program;
            $program->addCategory($this);
        }
        return $this;
    }
    public function removeProgram(Program $program): self
    {
        if ($this->program->contains($program)) {
            $this->program->removeElement($program);
            $program->removeCategory($this);
        }
        return $this;
    }
}