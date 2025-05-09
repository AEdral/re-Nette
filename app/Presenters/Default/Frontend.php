<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Classes\MyHashids;

class Frontend extends Nette\Application\UI\Presenter {

    protected $hashids;

    public function __construct( ) { }
    
    protected function startup(): void {
        parent::startup();
        $this->template->appName = APPNAME;
        $this->template->logo = LOGO;
        //$this->hashids = new MyHashids();
    }

    protected function beforeRender(): void {
        $this->setLayout(__DIR__.'/templates/@frontend.latte');
    }

}
