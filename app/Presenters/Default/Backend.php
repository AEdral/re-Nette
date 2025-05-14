<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Security\MyAuthorizator;
use Tracy\Debugger;
use App\Models\UserModel;
use App\Classes\MyHashids;

class Backend extends Nette\Application\UI\Presenter {

	public $authorizator;
	protected $hashids;


	public function __construct( /*UserModel $userModel1*/ ) { 
	}
	
	protected function startup(): void {
		parent::startup();
		if(!$this->getUser()->isLoggedIn()){
            //bdump($this->storeRequest());
            $this->redirect('Login:default',['requestedUrl' => $this->storeRequest()]);
        }

        $this->authorizator = new MyAuthorizator();
        $this->getUser()->setAuthorizator($this->authorizator);
        if(!$this->getUser()->isAllowed($this->name,$this->action))
            $this->error("forbidden",403);

		
		$this->template->logo = LOGO;
		$this->template->appName = APPNAME;
		$this->template->basePath = APPURL;




		/* MAPPA DEI LINK DEL SITO */
		/* tramite il metodo addNomeLink aggiungo ad un array visibile in tutti i presenter figli di Backend
		   contentente tutti i link della sezione del sito "Nome" in formato 'nome' 'link'.  
		   Il tag $titolo (di default settato a false) serve a evidenziare il pagina di default della sezione
		   del sito "Nome" (se è un presenter sarà ad esempio NomePresenter:default). Questo link può essere
		   evidenziato nei file latte con un if */


		$navbar_links = [
			['nome' => "Home", 'link' => "Home:default", 'title' => true],
			['nome' => "Default", 'link' => "Home:default", 'title' => false],
			['nome' => "Table Example", 'link' => "Home:table", 'title' => false],
			['nome' => "Dashboard Example", 'link' => "Home:dashboard", 'title' => false],
		];
		

		$option_links = [
			['nome' => "Logout", 'link' => "Login:logout", 'title' => false],
		];

		$this->template->navbar = $navbar_links;
		$this->template->options = $option_links;
		
		

		$this->template->section = strtolower($this->name);
		$this->template->page = ($this->action!="default")?$this->action:"";
		//$this->cookies = $cookies = $this->getHttpRequest()->getCookies();
		//$this->hashids = new MyHashids();
		$this->template->section = $this->getSession('comuneSession');
		//bdump($this->getHttpRequest()->getUrl());
		$httpRequest = $this->getHttpRequest();

		$query = "";
		foreach($httpRequest->getQuery() as $key => $value) {
			if(is_array($value)){
				foreach($value as $single_value){
					$query .= '?' . $key . '[]='.$single_value;
				}
			}else{
				$query .= "?" . $key . "=" . $value;
			}
		}
		$this->template->currentLink = $httpRequest->getUrl()->path . $query;
		$userSection = $this->getSession('userSession');
		$this->template->user_profile_image = $userSection->get('user_profile_image');
		$this->template->user_profile_name = $userSection->get('user_profile_name');
		$this->template->user_profile_lastname = $userSection->get('user_profile_lastname');
		$this->template->user_profile_email = $userSection->get('user_profile_email');
		//bdump($this->template->currentLink);
		//bdump($this->template->section->get('nome_comune'));
        
	}


	
	protected function beforeRender(): void {
		$this->setLayout(__DIR__.'/templates/@backend.latte');
	}

    
}
