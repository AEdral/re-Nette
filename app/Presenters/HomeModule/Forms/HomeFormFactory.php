<?php

namespace App\Forms;

use App\Bootstrap;
use Nette;
use Nette\Application\UI\Form;
use Contributte\FormsBootstrap;
use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\BoostrapVersion;
use Contributte\FormsBootstrap\Enums\RenderMode;
use stdClass;

use Nette\Utils\Arrays;

class HomeFormFactory {

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
		return $form;		
	}







	public function Insert(): BootstrapForm {
		$form = $this->default();
		$form->setHtmlAttribute('id','kt_modal_add_user_form');
		
		//$form->addRow()->addCell(12)->addCheckbox('status', 'Utente Abilitato');
        $row = $form->addRow();
		$row->addCell(2)->addSubmit('insert','Insert')->setHtmlAttribute('class','btn btn-primary me-5');

		return $form;
	}



	public function Reset($name): BootstrapForm {
		$form = $this->default();
		$form->setHtmlAttribute('id','kt_modal_add_user_form');
		
		//$form->addRow()->addCell(12)->addCheckbox('status', 'Utente Abilitato');
        $row = $form->addRow();
		$row->addCell(2)->addSubmit('reset',$name)->setHtmlAttribute('class','btn btn-primary me-5');

		return $form;
	}






}