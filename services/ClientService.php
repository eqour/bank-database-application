<?php

namespace app\services;

use app\application\Application;
use app\models\Client;
use DateTime;
use Exception;

class ClientService {
    public function createClient(int $id,
            string $name,
            DateTime $birth_date,
            string $passport,
            string $residence_address,
            string $phone_number,
            int $gender): Client {
        $client = new Client();
        $client->id = $id;
        $client->name = $name;
        $client->birth_date = $birth_date;
        $client->passport = $passport;
        $client->residence_address = $residence_address;
        $client->phone_number = $phone_number;
        $client->gender = $gender;
        return $client;
    }

    public function findByPassport(string $passport): ?Client {
        $stm = Application::$pdo->prepare('SELECT * FROM `client` WHERE `passport` = :passport;');
        $stm->bindValue('passport', $passport);
        $stm->execute();
        $client = $stm->fetch();
        return $client === false ? null : $this->createClient(
            $client['id'],
            $client['name'],
            new DateTime($client['birth_date']),
            $client['passport'],
            $client['residence_address'],
            $client['phone_number'],
            $client['gender']
        );  
    }

    public function findById(string $id): ?Client {
        $stm = Application::$pdo->prepare('SELECT * FROM `client` WHERE `id` = :id;');
        $stm->bindValue('id', $id);
        $stm->execute();
        $client = $stm->fetch();
        return $client === false ? null : $this->createClient(
            $client['id'],
            $client['name'],
            new DateTime($client['birth_date']),
            $client['passport'],
            $client['residence_address'],
            $client['phone_number'],
            $client['gender']
        );  
    }

    public function gender(Client $client): string {
        switch ($client->gender) {
            case Client::GENDER_MALE: return 'Мужской';
            case Client::GENDER_FEMALE: return 'Женский';
            default: throw new Exception();
        }
    }
}
