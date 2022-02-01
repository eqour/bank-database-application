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

    private function createClientFromFetchResult(array $result): Client {
        return $this->createClient(
            $result['id'],
            $result['name'],
            new DateTime($result['birth_date']),
            $result['passport'],
            $result['residence_address'],
            $result['phone_number'],
            $result['gender']
        );
    }

    public function findByPassport(string $passport): ?Client {
        $stm = Application::$pdo->prepare('SELECT * FROM `client` WHERE `passport` = :passport;');
        $stm->bindValue('passport', $passport);
        $stm->execute();
        $client = $stm->fetch();
        return $client === false ? null : $this->createClientFromFetchResult($client);
    }

    public function findById(string $id): ?Client {
        $stm = Application::$pdo->prepare('SELECT * FROM `client` WHERE `id` = :id;');
        $stm->bindValue('id', $id);
        $stm->execute();
        $client = $stm->fetch();
        return $client === false ? null : $this->createClientFromFetchResult($client);
    }

    public function gender(Client $client): string {
        switch ($client->gender) {
            case Client::GENDER_MALE: return 'Мужской';
            case Client::GENDER_FEMALE: return 'Женский';
            default: throw new Exception();
        }
    }
}
