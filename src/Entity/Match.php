<?php

namespace App\Entity;

use App\Repository\MatchRepository;
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
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="datetime")
     */
    private $startTime;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $result;

    private function __construct()
    {
        $this->setUuid((new ShortUuid())->encode(Uuid::uuid1()));

    }

    public static function create($local,$visitor,$startDate,$startTime){

        $match = new Match();
        $match->setLocal($local);
        $match->setVisiting($visitor);
        $match->setStartDate($startDate);
        $match->setStartTime($startTime);

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

    public function setStartTime(\DateTimeInterface $startTime)
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


}
