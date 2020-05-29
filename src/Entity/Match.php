<?php

namespace App\Entity;

use App\Repository\MatchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PascalDeVink\ShortUuid\ShortUuid;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=MatchRepository::class)
 * @ORM\Table(name="`match`")
 */
class Match
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
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     */
    private $local;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="matches")
     */
    private $visiting;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="string")
     */
    private $startTime;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $result;

    /**
     * @ORM\ManyToMany(targetEntity=Player::class)
     */
    private $participating;

    /**
     * @ORM\OneToMany(targetEntity=Gol::class, mappedBy="matchs")
     */
    private $Goals;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="matchs")
     */
    private $cards;


    private function __construct()
    {


        $this->participating = new ArrayCollection();
        $this->Goals = new ArrayCollection();
        $this->cards = new ArrayCollection();

    }

    public static function create($uuid,$local,$visitor,$startDate,$startTime,$location){

        $match = new Match();
        $match->setUuid($uuid);
        $match->setLocal($local);
        $match->setVisiting($visitor);
        $match->setStartDate($startDate);
        $match->setStartTime($startTime);
        $match->setLocation($location);

        return $match;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid( $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getLocal()
    {
        return $this->local;
    }

    public function setLocal(Team $local)
    {
        $this->local = $local;

        return $this;
    }

    public function getVisiting()
    {
        return $this->visiting;
    }

    public function setVisiting(Team $visiting)
    {
        $this->visiting = $visiting;

        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult(string $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return Collection|Player[]
     */
    public function getParticipating(): Collection
    {
        return $this->participating;
    }

    public function addParticipating(Player $participating): self
    {
        if (!$this->participating->contains($participating)) {
            $this->participating[] = $participating;
        }

        return $this;
    }

    public function removeParticipating(Player $participating): self
    {
        if ($this->participating->contains($participating)) {
            $this->participating->removeElement($participating);
        }

        return $this;
    }

    public function updatePlayers($participants)
    {

    }

    /**
     * @return Collection|Gol[]
     */
    public function getGoals(): Collection
    {
        return $this->Goals;
    }

    public function addGoal(Gol $goal): self
    {
        if (!$this->Goals->contains($goal)) {
            $this->Goals[] = $goal;
            $goal->setMatchs($this);
        }

        return $this;
    }

    public function removeGoal(Gol $goal): self
    {
        if ($this->Goals->contains($goal)) {
            $this->Goals->removeElement($goal);
            // set the owning side to null (unless already changed)
            if ($goal->getMatchs() === $this) {
                $goal->setMatchs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setMatchs($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getMatchs() === $this) {
                $card->setMatchs(null);
            }
        }

        return $this;
    }


}
