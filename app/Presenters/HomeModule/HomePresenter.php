<?php

declare(strict_types=1);

namespace App\Presenters;

use Contributte\FormsBootstrap\BootstrapForm;
use App\Models\OracondModel;
use Nette\Application\UI\Form;
use stdClass;
use Nette\Utils\DateTime;
use App\Classes\AiAssistant;
use App\Models\ExportModel;
use Nette;
use Nette\Forms\Form as FormsForm;
use Nette\Utils\Type;

final class HomePresenter extends Backend {


    public function __construct() {  
    }

    protected function startup(): void {
        parent::startup();
    
    }

        
    public function renderDefault(){    
        $this->template->title = "Home";
        bdump(dirname(dirname(__DIR__)));
    }

    public function renderDashboard(){
        $this->template->title = "Dashboard";
    }

}
