<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgramRepository")
 */
class Program
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
    private $title;
    /**
     * @ORM\Column(type="text")
     */
    private $summary;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poster;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="program")
     */
    private $category;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Season", mappedBy="program")
     */
    private $seasons;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Season", mappedBy="program")
     */
    private $program;
    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->program = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    public function getSummary(): ?string
    {
        return $this->summary;
    }
    public function setSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }
    public function getPoster(): ?string
    {
        return $this->poster;
    }
    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;
        return $this;
    }
    public function getCategory(): ?Category
    {
        return $this->category;
    }
    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
            $category->setCategory($this);
        }
        return $this;
    }
    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getCategory() === $this) {
                $category->setCategory(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|Episode[]
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }
    public function addSeasons(Season $seasons): self
    {
        if (!$this->seasons->contains($seasons)) {
            $this->seasons[] = $seasons;
            $seasons->setProgram($this);
        }
        return $this;
    }
    public function removeSeasons(Season $seasons): self
    {
        if ($this->seasons->contains($seasons)) {
            $this->seasons->removeElement($seasons);
            // set the owning side to null (unless already changed)
            if ($seasons->getSeason() === $this) {
                $seasons->setSeason(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection|Season[]
     */
    public function getProgram(): Collection
    {
        return $this->program;
    }
    public function addProgram(Season $program): self
    {
        if (!$this->program->contains($program)) {
            $this->program[] = $program;
            $program->setProgram($this);
        }
        return $this;
    }
    public function removeProgram(Season $program): self
    {
        if ($this->program->contains($program)) {
            $this->program->removeElement($program);
            // set the owning side to null (unless already changed)
            if ($program->getProgram() === $this) {
                $program->setProgram(null);
            }
        }
        return $this;
    }
}