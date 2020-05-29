<?php

namespace App\Controller\Match;


use App\Entity\Match;
use App\Entity\Team;
use App\Repository\MatchRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DoSaveInformationMatches
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

    public function __construct(MatchRepository $matchRepository,
                                TeamRepository $teamRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->matchRepository = $matchRepository;
        $this->teamRepository = $teamRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/matches-information", methods={"POST"})
     */
    public function __invoke(Request $request)
    {
        /**
         * En la estructura de datos que recibo yo doy por hecho que la empresa de terceros
         * nos envia los uuids (o algun dato unico por equipo) de los equipos involucrados y que previamente nosotros ya hemos
         * analizado esos datos para que tener esa clave unica y la usammos cuando creamos los equipos
         * en nuestra base de datos con esos mismo uuids.
         */

        $matches = json_decode($request->get('matches'),true);



        foreach ($matches as $match) {

            /** @var  $local Team */
            $local = $this->teamRepository->findOneBy(['uuid' => $match['localUuid']]);
            if (!$local) {
                throw new InvalidArgumentException('localUuid not found');
            }

            /** @var  $visitor Team */
            $visitor = $this->teamRepository->findOneBy(['uuid' => $match['visitorUuid']]);
            if (!$visitor) {
                throw new InvalidArgumentException('visitorUuid not found');
            }



            //En esta parte del codigo compruebo si algunio de los 2 equipos es
            // el equipo del que nos interesa la informacion (DEL EQUIPO AL QUE PERTENECE LA WEB) y si
            // no lo es no la guardo,
            //El equipo de la web siempre sera 'TEAM_1'
            // HABIA PENSADO HACERLO TAMBIEN POR UUID PERO SE VOLVERIA MAS COMPLEJO.


            if ($local->getName() !== 'TEAM_1' && $visitor->getName() !== 'TEAM_1') {
                continue;
            }


            //En el caso de las fechas habitualmente yo uso Datetime, por eso lo uso aqui.
            $newMatch = Match::create(
                $match['uuid'],
                $local,
                $visitor,
                new \DateTime($match['date']),
                (new \DateTime($match['date']))->format('H:i'),
                $match['location']
            );

            if (in_array('result',$match)) {
                $newMatch->setResult($match['result']);
            }
            $this->matchRepository->persist($newMatch);
        }


        $this->entityManager->flush();

        return 'SUCCESS';
    }

}
