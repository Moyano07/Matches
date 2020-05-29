<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Match::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchs;

    private function __construct()
    {
    }

    public static function create($uuid, $player, Match $match, $minute,$type)
    {
        $card = new Card();
        $card->setUuid($uuid);
        $card->setPlayer($player);
        $card->setMatchs($match);
        $card->setMinute($minute);
        $card->setType($type);

        return $card;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
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
