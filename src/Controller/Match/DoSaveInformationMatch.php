<?php


namespace App\Controller\Match;


use App\Entity\Card;
use App\Entity\Gol;
use App\Entity\Match;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\CardRepository;
use App\Repository\GolRepository;
use App\Repository\MatchRepository;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Services\SMSService;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DoSaveInformationMatch
{

    /**
     * @var MatchRepository
     */
    private $matchRepository;
    /**
     * @var TeamRepository
     */
    private $teamRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PlayerRepository
     */
    private $playerRepository;
    /**
     * @var GolRepository
     */
    private $golRepository;
    /**
     * @var CardRepository
     */
    private $cardRepository;

    public function __construct(MatchRepository $matchRepository,
                                TeamRepository $teamRepository,
                                EntityManagerInterface $entityManager,
                                PlayerRepository $playerRepository,
                                GolRepository $golRepository,
                                CardRepository $cardRepository)
    {
        $this->matchRepository = $matchRepository;
        $this->teamRepository = $teamRepository;
        $this->entityManager = $entityManager;
        $this->playerRepository = $playerRepository;
        $this->golRepository = $golRepository;
        $this->cardRepository = $cardRepository;
    }

    /**
     * @Route("/match-information", methods={"POST"})
     */
    public function __invoke(Request $request)
    {
        /** @var  $local Team */
        $local = $this->teamRepository->findOneBy(['uuid' => $request->get('localUuid')]);
        if (!$local) {
            throw new InvalidArgumentException('localUuid not found');
        }

        /** @var  $visitor Team */
        $visitor = $this->teamRepository->findOneBy(['uuid' => $request->get('visitorUuid')]);
        if (!$visitor) {
            throw new InvalidArgumentException('visitorUuid not found');
        }

        //Compruebo si es un partido nuevo o uno ya existente con la clave unica uuid que llega por la request
        /** @var Match $match */
        $match = $this->matchRepository->findOneBy(['uuid' => $request->get('uuid')]);

        if (!$match) {
            $match = Match::create(
                $request->get('uuid'),
                $local,
                $visitor,
                new \DateTime($request->get('date')),
                (new \DateTime($request->get('date')))->format('H:i'),
                $request->get('location')
            );
            $this->matchRepository->persist($match);
        }

        //Compruebo si los jugadores que llegan son nuevos o hay que añadirlos como nuevos participantes
        //Si un jugador no existe no lo añado por que se supone que en basae de datos deberiamos tner a
        // todos los jugadores de la liga, o asi lo he enfocado yo
        /** @var  $participant Player */
        foreach ($request->get('participants') as $participant) {
            /** @var Player $player */
            $player = $this->playerRepository->findOneBy(['uuid' => $participant['uuid']]);
            if ($player) {
                $match->addParticipating($player);
            }
        }

        /* Hago el mismo proceso que con los jugadores pero esta vez si el gol no exite lo creo y se
         * lo añado a este partido. Ademas llamo al servicio de envio de SMS para notificar el gol
         */

        foreach ($request->get('goals') as $gol) {
            /** @var Gol $gol */
            $gol = $this->golRepository->findOneBy(['uuid' => $gol['uuid']]);
            if (!$gol) {
                $gol = Gol::create(
                    $gol['uuid'],
                    $this->playerRepository->findOneBy(['uuid' => $gol['player']['uuid']]),
                    $match,
                    $gol['minute']
                );
                $this->golRepository->persist($gol);
                $match->addGoal($gol);

                //Al saber que es un nuevo gol es aqui donde implemento lallamada al servixio de SMS

                $smsService = new SMSService();
                $smsService->sendSms($gol);
            }
        }


        /*
        * Para las penalizaciones hago lo mismo que con los goles
        */
        foreach ($request->get('penalties') as $card) {
            /** @var Card $card */
            $card = $this->cardRepository->findOneBy(['uuid' => $card['uuid']]);
            if (!$card) {
                $card = Card::create(
                    $card['uuid'],
                    $this->playerRepository->findOneBy(['uuid' => $card['player']['uuid']]),
                    $match,
                    $card['minute'],
                    $card['type']
                );
                $this->cardRepository->persist($card);
                $match->addCard($card);

            }
        }

        $this->entityManager->flush();

        return 'SUCCESS';
    }

}
