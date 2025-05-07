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
	private $cookies;
	protected $hashids;

	private $home_links = [];
	private $export_links = [];
	private $report_links = [];
	private $admin_links = [];
	
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

		
		
		$this->template->appName = APPNAME;
		$this->template->basePath = APPURL;




		/* MAPPA DEI LINK DEL SITO */
		/* tramite il metodo addNomeLink aggiungo ad un array visibile in tutti i presenter figli di Backend
		   contentente tutti i link della sezione del sito "Nome" in formato 'nome' 'link'.  
		   Il tag $titolo (di default settato a false) serve a evidenziare il pagina di default della sezione
		   del sito "Nome" (se è un presenter sarà ad esempio NomePresenter:default). Questo link può essere
		   evidenziato nei file latte con un if */

		array_push($this->admin_links, ['nome' => "Indice", 'link' => "Indice:default", 'title' => true]);
		array_push($this->admin_links, ['nome' => "Indice Database", 'link' => "Indice:default", 'title' => false]);
		
	
		$this->addExportLink("Export","",true);
		$this->addExportLink("Pratiche","ExportManager:pratiche");
		$this->addExportLink("Notifiche","ExportManager:notifiche");
		$this->addExportLink("Versamenti","ExportManager:versamenti");
		$this->addExportLink("Fasi","Diagnostic:stati");
		$this->addExportLink("Attività","ExportManager:log");


		$this->addExportLink("Import","",true);
		$this->addExportLink("Aggiornamento Fasi","Diagnostic:aggiornafasi");
		$this->addExportLink("Aggiornamento Anagrafiche","ImportManager:anagrafiche");


		$this->addExportLink("Gestione","",true);
		$this->addExportLink("Rendicontazione","ExportManager:rendicontazione");

		/*
		$this->addReportLink("Reports","Report:default", true);
		$this->addReportLink("Pratiche","Report:pratiche");
		*/
		//inserimento manuale senza funzione apposita
		#array_push($this->admin_links, ['nome' => "Admin", 'link' => "Admin:default", 'title' => true]);
		#array_push($this->admin_links, ['nome' => "Dati statistici", 'link' => "Admin:statistics", 'title' => false]);
		#array_push($this->admin_links, ['nome' => "Dati economici", 'link' => "Admin:economics", 'title' => false]);
		/*
		array_push($this->admin_links, ['nome' => "Programmazione settimanale", 'link' => "Admin:programmazione", 'title' => false]);
		*/


		#array_push($this->admin_links, ['nome' => "Diagnostica fasi", 'link' => "Diagnostic:fasi", 'title' => false]);
		/*
		array_push($this->admin_links, ['nome' => "Comuni", 'link' => "Indice:comuni", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Pratiche", 'link' => "Indice:pratiche", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Sot", 'link' => "Indice:sot", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Anagrafiche", 'link' => "Indice:anagrafiche", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Transazioni", 'link' => "Indice:transazioni", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Versamenti", 'link' => "Indice:versamenti", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Documenti Abuso", 'link' => "Indice:documenti", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Fabbricati", 'link' => "Indice:fabbricati", 'title' => false]);
		array_push($this->admin_links, ['nome' => "Terreni", 'link' => "Indice:terreni", 'title' => false]);
		*/
		//array_push($this->admin_links, ['nome' => "Alloggi", 'link' => "Indice:alloggi", 'title' => false]);


		
        $this->template->navbar = array_merge($this->admin_links, $this->home_links, $this->export_links, $this->report_links);
		$this->template->home_links = $this->home_links;
		$this->template->export_links = $this->export_links;
		$this->template->report_links = $this->report_links;
		$this->template->admin_links = $this->admin_links;

		/*
		if(!$this->getUser()->isLoggedIn()){
			$this->redirect('Home:default',['backlink' => $this->storeRequest()]);
		
        }
		*/
        
		/*
		$this->authorizator = new MyAuthorizator();
		$this->getUser()->setAuthorizator($this->authorizator);
		if(!$this->getUser()->isAllowed($this->name,$this->action)){
			$this->error("forbidden",403);
			
		}
		*/
		
		$this->template->section = strtolower($this->name);
		$this->template->page = ($this->action!="default")?$this->action:"";
		$this->cookies = $cookies = $this->getHttpRequest()->getCookies();
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

	private function addLink($nome, $link, $title=false){
        array_push($this->export_links, ['nome' => $nome, 'link' => $link, 'title' => $title]);
    } 
	private function addHomeLink($nome, $link, $title=false){
        array_push($this->home_links, ['nome' => $nome, 'link' => $link, 'title' => $title]);
    } 
	private function addExportLink($nome, $link, $title=false){
        array_push($this->export_links, ['nome' => $nome, 'link' => $link, 'title' => $title]);
    } 
	private function addReportLink($nome, $link, $title=false){
        array_push($this->report_links, ['nome' => $nome, 'link' => $link, 'title' => $title]);
    } 

        

	
	protected function beforeRender(): void {
		$this->setLayout(__DIR__.'/templates/@backend.latte');
	}

    
}
