<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Models\DefaultModel;
use App\Models\UserModel;
use Nette\Application\Responses\FileResponse;
use Nette;
use Shuchkin\SimpleXLSX;

final class ProfilePresenter extends Backend {

    

    public function __construct(private DefaultModel $model, private UserModel $userModel) {
        parent::__construct();
    }

    protected function startup(): void {
        parent::startup();
    
    }

        

    //DEEFAULT_______________________________________________________________

    public function renderDefault(){    
        $this->template->title = "Profile";

        $userId = $this->getUser()->getId();
        $user = $this->userModel->getUserById($userId);
        

        bdump($user);


        $this->template->cur_user = $user;


    }



    public function handleProfileUpload(): void
    {
        $httpRequest = $this->getHttpRequest();
        $file = $httpRequest->getFile('image');
        $userId = $this->getUser()->getId();

        if (!$file || !$file->isImage()) {
            $this->sendJson(['success' => false, 'message' => 'File non valido.']);
            return;
        }

        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($file->getContentType(), $allowedTypes)) {
            $this->sendJson(['success' => false, 'message' => 'Formato immagine non supportato.']);
            return;
        }

        $uploadDir = __DIR__ . '/../../../www/uploads/profile_pictures/' . $userId . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        

        $extension = pathinfo($file->getSanitizedName(), PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $path = $uploadDir . $filename;

        $file->move($path);

        $relativePath = 'uploads/profile_pictures/' . $userId . '/' . $filename;
        $this->userModel->updateUser($userId, ['profile_image_path' => $relativePath]);

        $this->sendJson([
            'success' => true,
            'newImageUrl' => $this->getHttpRequest()->getUrl()->getBasePath() . $relativePath,
        ]);
    }

    public function handleRemoveProfileImage(): void
    {
        $userId = $this->getUser()->getId();



        // Aggiorna il database
        $this->userModel->updateUser($userId, [
            'profile_image_path' => 'uploads/profile_pictures/default.png', // oppure imposta a 'assets/img/default-avatar.svg' se vuoi
        ]);

        $this->flashMessage('Foto del profilo rimossa con successo.', 'success');
        $this->redirect('this');
    }





    //EDIT_______________________________________________________________

    public function actionEdit(): void {
        // Recupera i dati utente        
        $userId = $this->getUser()->getId();
        $user = $this->userModel->getUserById($userId);  
        $this->template->cur_user = $user;
    }
    
    public function renderEdit(): void {
        $this->template->title = "Modifica Profilo";
    }
    
    public function handleSave(): void {
        $userId = $this->getUser()->getId();
    
        $name = trim($this->getHttpRequest()->getPost('name'));
        $surname = trim($this->getHttpRequest()->getPost('surname'));
        $username = trim($this->getHttpRequest()->getPost('username'));
    
        // Controlli base
        if (empty($name) || empty($surname) || empty($username)) {
            $this->flashMessage('Tutti i campi sono obbligatori.', 'danger');
            $this->redirect('edit');
            return;
        }
    
        // Controlla se lo username è già preso da un altro utente
        if ($this->userModel->isUsernameTaken($username, $userId)) {
            $this->flashMessage('Questo username è già in uso da un altro utente.', 'danger');
            $this->redirect('edit');
            return;
        }
    
        // Salva le modifiche
        $updated = $this->userModel->updateUser($userId, [
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
        ]);
    
        if ($updated) {
            $this->flashMessage('Profilo aggiornato con successo.', 'success');
        } else {
            $this->flashMessage('Nessuna modifica effettuata.', 'info');
        }
    
        $this->redirect('default');
    }
    
    
    

    
}
