<?php

namespace App\Presenters;

use App\Classes\MailManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\MyAuthorizator;
use App\Models\UserModel;
use stdClass;
use Nette\Application\Attributes\Persistent;
use Nette\Security\Passwords;
use Tester\DataProvider;

final class LoginPresenter extends Frontend {

	#[Persistent]
	public string $requestedUrl = ''; //serve a conservare la richiesta dell'utente e reindirizzarlo lì dopo la login
    private $passwords;

	public function __construct(private array $adminEmail, private MailManager $mailManager, private UserModel $userModel, Passwords $passwords){
        $this->passwords = $passwords;
	}

	public function renderDefault(){
        # se l'utente è già loggato lo mando direttamente alla home
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect("Home:default");
        }
    }

	public function renderSignUp(): void
    {
        # se l'utente è già loggato lo mando direttamente alla home
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect("Home:default");
        }
    }

	

	//LOGIN
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
			$form['username']->addError('Campo obbligtorio.');
		}
	}

    public function formLoginSucceeded(Form $form, $data) {
        // Log per monitorare i dati di input
        bdump("Tentativo di login con username: {$data->utente} e password: {$data->password}");
    
        try {
            // Esegui il login
            $this->getUser()->login($data->utente, $data->password);
            
            // Controllo se il login è riuscito
            if ($this->getUser()->isLoggedIn()) {
                bdump("Login riuscito per l'utente: {$data->utente}");
            } else {
                bdump("Login non riuscito, l'utente non è stato autenticato.");
                $form->addError('Credenziali non valide.');
                return;
            }
    
            // Impostazione dell'autorizzazione
            $authorizator = new MyAuthorizator();
            $this->getUser()->setAuthorizator($authorizator);
            bdump("Autorizzazione impostata correttamente.");
    
            // Recupera i dettagli dell'utente
            $currentUser = $this->userModel->getUserById($this->getUser()->identity->id);
    
            // Log per monitorare l'utente attualmente autenticato
            bdump("Utente autenticato: {$currentUser->username} con ruolo: {$currentUser->ruolo}");
    
            // Gestione della richiesta precedente (se presente)
            $this->restoreRequest($this->requestedUrl);
            
            // Reindirizza l'utente alla pagina richiesta o alla home
            $this->redirect('Home:default');
            
        } catch (Nette\Security\AuthenticationException $e) {
            // Log in caso di errore durante il login
            bdump("Errore durante il login: " . $e->getMessage());
            
            // Aggiungi errori al form
            $form->addError($e->getMessage());
            $form['username']->addError('');
            $form['password']->addError('');
        }
    }
    

	//SIGN UP
    protected function createComponentSignUpForm(): Form
    {
        $form = new Form;

        $form->addText('username', 'Nome utente:')
            ->setRequired('Inserisci un nome utente.');

        $form->addEmail('email', 'Email:')
            ->setRequired('Inserisci una email valida.');

        $form->addPassword('password', 'Password:')
            ->setRequired('Inserisci una password.');

        $form->addPassword('passwordConfirm', 'Conferma Password:')
            ->setRequired('Conferma la password.');

        $form->addSubmit('send', 'Registrati');

        $form->onSuccess[] = [$this, 'signUpFormSucceeded'];

        return $form;
    }



    public function signUpFormSucceeded(Form $form, $data): void
    {
        bdump($data);
        // Verifica se l'utente o l'email sono già registrati
        $existingUser = $this->userModel->getUserByUsername($data->username) 
            || $this->userModel->getUserByEmail($data->email);

        if ($existingUser) {
            $form->addError('Nome utente o email già registrati.');
            return;
        }

        // Verifica che le password corrispondano
        if ($data->password !== $data->passwordConfirm) {
            $form->addError('Le password non corrispondono.');
            return;
        }

        $hashedPassword = $this->passwords->hash($data->password);

        // Crea il nuovo utente
        $this->userModel->createUser([
            'username' => $data->username,
            'email' => $data->email,
            'password' => $hashedPassword,
            'created_at' => new \DateTime()
        ]);

        $this->flashMessage('Registrazione completata con successo. Ora puoi accedere.', 'success');
        $this->redirect('Login:default');
    }


	#LOGOUT
    public function renderLogout(){
        $this->getUser()->logout(true);
        $this->redirect("Login:default");
    }

}