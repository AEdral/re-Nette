<?php

namespace App\Models;

use DateInterval;
use DateTime;
use Nette;
use Nette\Security\Passwords;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Random;

class UserModel {
    use Nette\SmartObject;

    private $database;


    public function __construct(Explorer $database) {
        //la variabile in input dichiara che questa classe prende in input l'ORM del db
        $this->database = $database;
    }


    public function getUserByUsername(string $username): ?ActiveRow
    {
        return $this->database->table('users')->where('username', $username)->fetch();
    }

    public function getUserByEmail(string $email): ?ActiveRow
    {
        return $this->database->table('users')->where('email', $email)->fetch();
    }

    public function getUserById(int $id): ?ActiveRow
    {
        return $this->database->table('users')->where('id', $id)->fetch();
    }

    public function createUser(array $data): ActiveRow
    {
        return $this->database->table('users')->insert($data);
    }

    public function verifyPassword(string $username, string $password, Passwords $passwords): ?ActiveRow
    {
        $user = $this->getUserByUsername($username);
        if ($user && $passwords->verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

}