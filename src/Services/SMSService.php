<?php


namespace App\Services;


use App\Entity\Gol;

class SMSService
{

    /**
     * @var string
     */
    private $APIKey;
    /**
     * @var string
     */
    private $secret;
    /**
     * @var UserRepository
     */
    private $userRepository;


    public function __construct(string $apiKey, string $secret, UserRepository $userRepository)
    {
        $this->APIKey = $apiKey;
        $this->secret = $secret;
        $this->userRepository = $userRepository;
    }

    /**
     * En este metodo buco a todos los usuarios de la web para enviarles el sms
     * No he implementado una entidad usuario por que o se pedia.
     */

    public function searchUsersBySMS($gol)
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $this->sendSms($user, $gol);
        }
    }


    public function sendSms($user,Gol $gol)
    {
        $credentials = new Credentials(
            $this->awsKey,
            $this->secret
        );

        $sesClient = new SesClient([
            'region' => 'eu-west-1',
            'version' => 'latest',
            'credentials' => $credentials
        ]);

        $sesClient->sendSMS(array(

            // Destination is required
            'Destination' => [
                'phone' => $user->getPhone()
            ],

            'Message' => array(


                'Charset' => 'UTF-8',

                // Body is required
                'Body' => array(
                    'Text' => array(
                        // Data is required
                        'Title' => 'Goooool!!!',
                        'Text' => 'A marcado gol ' . $gol->getPlayer()->getName() . ' en el minuto ' . $gol->getMinute(),
                    ),

                ),
            ),

        ));
    }
    }
