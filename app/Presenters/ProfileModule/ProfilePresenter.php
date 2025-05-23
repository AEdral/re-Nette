<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Models\DefaultModel;

use Nette\Application\Responses\FileResponse;
use Nette;
use Shuchkin\SimpleXLSX;

final class ProfilePresenter extends Backend {

    

    public $dataset_example = [
        ["id" => 1, "name" => "John Doe", "email" => "john@example.com"],
        ["id" => 2, "name" => "Jane Smith", "email" => "jane@example.com"],
        ["id" => 3, "name" => "Bob Johnson", "email" => "bob@example.com"],
        ["id" => 4, "name" => "Alice Brown", "email" => "alice@example.com"],
        ["id" => 5, "name" => "Charlie Davis", "email" => "charlie@example.com"],
        ["id" => 6, "name" => "Eva Wilson", "email" => "eva@example.com"],
        ["id" => 7, "name" => "Frank Miller", "email" => "frank@example.com"],
        ["id" => 8, "name" => "Grace Lee", "email" => "grace@example.com"],
        ["id" => 9, "name" => "Henry Clark", "email" => "henry@example.com"],
        ["id" => 10, "name" => "Isabel Turner", "email" => "isabel@example.com"],
        ["id" => 11, "name" => "Jack Adams", "email" => "jack@example.com"],
        ["id" => 12, "name" => "Karen White", "email" => "karen@example.com"],
        ["id" => 13, "name" => "Larry Harris", "email" => "larry@example.com"],
        ["id" => 14, "name" => "Megan Scott", "email" => "megan@example.com"],
        ["id" => 15, "name" => "Nathan Baker", "email" => "nathan@example.com"],
        ["id" => 16, "name" => "Olivia Green", "email" => "olivia@example.com"],
    ];


    public function __construct(private DefaultModel $model) {  
    }

    protected function startup(): void {
        parent::startup();
    
    }

        

    //DEEFAULT_______________________________________________________________
    
    public function renderDefault(){    
        $this->template->title = "Profile";
        $user = ['name' => 'John', 
                 'surname' => 'Doe',
                 'username' => 'johndoe',
                 'email' => 'john@example.com',
                 'role' => 'user admin'];

        $this->template->cur_user = $user;

        bdump(dirname(dirname(__DIR__)));
    }







    //EDIT_______________________________________________________________

    public function actionEdit(): void {
        // Recupera i dati utente
        $user = [
            'name' => 'John',
            'surname' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'role' => 'user admin',
        ];
    
        $this->template->cur_user = $user;
    }
    
    public function renderEdit(): void {
        $this->template->title = "Modifica Profilo";
    }
    
    public function handleSave(): void {
        $name = $this->getHttpRequest()->getPost('name');
        $surname = $this->getHttpRequest()->getPost('surname');
        $username = $this->getHttpRequest()->getPost('username');
    
        // Qui andrebbe il salvataggio nel DB. Per ora solo dump:
        bdump([
            'name' => $name,
            'surname' => $surname,
            'username' => $username
        ]);
    
        $this->flashMessage('Profilo aggiornato con successo.', 'success');
        $this->redirect('default'); // Torna al profilo
    }
    
    

    
}
