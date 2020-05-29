<?php

namespace App\Entity;

use App\Repository\GolRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GolRepository::class)
 */
class Gol
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
     * @ORM\ManyToOne(targetEntity=Player::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $minute;

    /**
     * @ORM\ManyToOne(targetEntity=Match::class, inversedBy="Goals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchs;


    private function __construct()
    {
    }

    public static function create($uuid,$player,$minute,$match)
    {
        $gol = new Gol();
        $gol->setUuid($uuid);
        $gol->setPlayer($player);
        $gol->setMatchs($match);
        $gol->setMinute($minute);

        return $gol;

    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }



    public function getMinute(): ?string
    {
        return $this->minute;
    }

    public function setMinute(string $minute): self
    {
        $this->minute = $minute;

        return $this;
    }

    public function getMatchs(): ?Match
    {
        return $this->matchs;
    }

    public function setMatchs(?Match $matchs): self
    {
        $this->matchs = $matchs;

        return $this;
    }
}
