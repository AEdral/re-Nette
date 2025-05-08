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


    public function getAllUsers():array|null{
        $table = $this->database->table(USERSTABLE);
        return $table->fetchAll();
    }

    public function getUsersByRole($role):array|null{
        $table = $this->database->table(USERSTABLE);
        return $table->where('ruolo', $role)->fetchAll();
    }

    public function countUsersByRole($role){
        $table = $this->database->query("SELECT count(id) as numero_utenti FROM ".USERSTABLE." WHERE ruolo = ?", $role);
        return $table->fetch();
    }

    public function getUserByEmail($email):ActiveRow|null{
        $table = $this->database->table(USERSTABLE); 
        return $table->where('email', $email)->fetch();
    }

    public function getUserByUsername($username):ActiveRow|null{
        $table = $this->database->table(USERSTABLE); 
        return $table->where('utente', $username)->fetch();
    }

    public function getUserById($id):ActiveRow|null{
        $table = $this->database->table(USERSTABLE); 
        return $table->where('id', $id)->fetch();
    }

    public function updateUser($data):void{
        $table = $this->database->table(USERSTABLE);
        $table->where('id', $data['id'])->update($data);
    }

    public function getRoles(){
        return RUOLI;
    }

}