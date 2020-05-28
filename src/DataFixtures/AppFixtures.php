<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @var TeamRepository
     */
    private $teamRepository;
    /**
     * @var PlayerRepository
     */
    private $playerRepository;

    public function __construct(TeamRepository $teamRepository, PlayerRepository $playerRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->playerRepository = $playerRepository;
    }

    public function load(ObjectManager $manager)
    {

        /*
         * Voy a crear unos datos artificiales con los que poder trabajar y tener algunas
         * referencias a la hora de evaluar los datos que se reciben en la peticiones de terceros.
         *
         * El equipo al que gestionamos la web siempre sera el TEAM_1
         */

        //Fixtures

        for ($i = 0;$i<5;$i++){
            $team = Team::create('uuid_TEAM_'.$i,'TEAM_'.$i);

            $this->teamRepository->persist($team);

            for ($h = 0;$h<11;$h++){
                $player = Player::create('uuid_PLAYER_'.$h,'PLAYER_'.$h,$team);
                $this->playerRepository->persist($player);
            }

        }


        $manager->flush();
    }
}
