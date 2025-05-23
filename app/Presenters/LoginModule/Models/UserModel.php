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


    public function updateUser(int $id, array $data): bool
    {
        return $this->database->table('users')
            ->where('id', $id)
            ->update($data) > 0;
    }

    public function deleteUser(int $id): bool
    {
        return $this->database->table('users')
            ->where('id', $id)
            ->delete() > 0;
    }

    public function getAllUsers(): Nette\Database\Table\Selection
    {
        return $this->database->table('users');
    }

    public function changePassword(int $id, string $newPassword, Passwords $passwords): bool
    {
        $hashed = $passwords->hash($newPassword);
        return $this->updateUser($id, ['password' => $hashed]);
    }

    public function isUsernameTaken(string $username, int $excludeUserId = null): bool
    {
        $query = $this->database->table('users')->where('username', $username);
    
        if ($excludeUserId !== null) {
            $query->where('id != ?', $excludeUserId);
        }
    
        return $query->count('*') > 0;
    }
    

    public function isEmailTaken(string $email): bool
    {
        return (bool) $this->getUserByEmail($email);
    }


}