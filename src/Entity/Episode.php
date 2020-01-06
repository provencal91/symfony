<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\EpisodeRepository")
 */
class Episode
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
     * @ORM\Column(type="integer")
     */
    private $number;
    /**
     * @ORM\Column(type="string", length=500)
     */
    private $synopsis;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;
    public function __construct()
    {
        $this->season = new ArrayCollection();
    }
    /**
     * @return Collection|Season[]
     */
    public function getSeason(): ?Season
    {
        return $this->season;
    }
    public function setSeason(?Season $season): self
    {
        $this->season = $season;
        return $this;
    }
    /**
     * param Season $season
     * @return Episode
     */
    public function addSeason(Season $season): self
    {
        if (!$this->season->contains($season)) {
            $this->season[] = $season;
            $season->setEpisode($this);
        }
        return $this;
    }
    /**
     * param Season $season
     * @return Episode
     */
    public function removeSeason(Season $season): self
    {
        if ($this->season->contains($season)) {
            $this->season->removeElement($season);
            // set the owning side to null (unless already changed)
            if ($season->getEpisode() === $this) {
                $season->setEpisode(null);
            }
        }
        return $this;
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
    public function getNumber(): ?int
    {
        return $this->number;
    }
    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }
    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;
        return $this;
    }
}