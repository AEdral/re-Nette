<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Contributte\FormsBootstrap;
use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\BoostrapVersion;
use Contributte\FormsBootstrap\Enums\RenderMode;
use stdClass;

class LoginFormFactory {

	//Form di default da usare come base
	public function default(){
		//Sintassi corretta genera errore
		//BootstrapForm::switchBootstrapVersion(BoostrapVersion::V5);
		BootstrapForm::switchBootstrapVersion(5);
		$form = new BootstrapForm;
		$form->renderMode = RenderMode::VERTICAL_MODE;
		$form->addProtection("Sessione scaduta");
		$form->onValidate[] = function (BootstrapForm $form, array $values): void {};
		$form->onSuccess[] = function (BootstrapForm $form, array $values): void {}; 
		//$form->set
		return $form;		
	}

	//Form di login
	public function login(): BootstrapForm {
		$form = $this->default();
		$form->setHtmlAttribute('id','kt_sign_in_form');
		$row = $form->addRow();
		$row->addCell(12)->addText('utente', 'Utente')
						->setHtmlAttribute('class','form-control form-control-lg form-control-solid mb-10')
						->setRequired("Campo obbligatorio");
		$row = $form->addRow();
		$row->addCell(12); 
		$row = $form->addRow();
		$row->addCell(12)->addPassword('password', 'Password')
						->setHtmlAttribute('class','form-control form-control-lg form-control-solid mb-10')
						->setRequired("Campo obbligatorio");
		$form->addSubmit('submit','Esegui login')->setHtmlAttribute('class','mt-2');
		return $form;
	}

}