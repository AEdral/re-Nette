<?php

namespace App\Presenters;

use App\Classes\MailManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\MyAuthorizator;
use App\Models\UserModel;
use stdClass;
use Nette\Application\Attributes\Persistent;

final class LoginPresenter extends Frontend {

	#[Persistent]
	public string $requestedUrl = ''; //serve a conservare la richiesta dell'utente e reindirizzarlo lì dopo la login

	public function __construct(private array $adminEmail, private MailManager $mailManager, private UserModel $userModel){

	}

	public function renderDefault(){
        # se l'utente è già loggato lo mando direttamente alla dashboard
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect("Indice:default");
        }
    }
	
	protected function createComponentLoginForm(): Form {
		$form = new Form;

		$form->addText('username', 'Username')->setRequired("Campo obbligatorio.");
		$form->addPassword('password', 'Password')->setRequired("Campo obbligatorio.");
		$form->addSubmit('submit','Esegui login');


		$form->onValidate[] = [$this, 'formLoginValidation'];
		$form->onSuccess[] = [$this, 'formLoginSucceeded'];
		return $form;
	}

	public function formLoginValidation(Form $form, stdClass $data) {
		if (empty($data->utente)){
			$form['utente']->addError('Campo obbligtorio.');
		}
	}

	public function formLoginSucceeded(Form $form, $data) {
		try{
			$this->getUser()->login($data->utente, $data->password);
			$authorizator = new MyAuthorizator();
			$this->getUser()->setAuthorizator($authorizator);
			$currentUser = $this->userModel->getUserById($this->getUser()->identity->id);
			$role = $currentUser->ruolo;
			
			//se prima della login l'utente aveva fatto una richiesta (generare una lettera)
			//la richiesta viene restorata e l'utente automaticamente reindirizzato alla risorsa richiesta
			$this->restoreRequest($this->requestedUrl);
			//se non c'era nessuna richiesta allora si manda in dashboard
			$this->redirect('Indice:default');
			
		} catch(Nette\Security\AuthenticationException $e){
			$form->addError($e->getMessage());
			$form['utente']->addError('');
			$form['password']->addError('');
		}
	}

    public function renderLogout(){
        $this->getUser()->logout(true);
        $this->redirect("Indice:default");
    }

}