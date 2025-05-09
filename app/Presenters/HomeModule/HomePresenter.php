<?php

declare(strict_types=1);

namespace App\Presenters;

use Contributte\FormsBootstrap\BootstrapForm;
use App\Models\HomeModel;
use Nette\Application\UI\Form;
use stdClass;
use Nette\Utils\DateTime;
use App\Classes\AiAssistant;
use App\Models\DefaultModel;
use App\Models\ExportModel;
use Nette;
use Nette\Forms\Form as FormsForm;
use Nette\Utils\Type;

final class HomePresenter extends Backend {

    

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

        
    public function renderDefault(){    
        $this->template->title = "Home";
        bdump(dirname(dirname(__DIR__)));
    }

    public function renderDashboard(){
        $this->template->title = "Dashboard";
    }

    public function renderTable(int $current = 1, int $limit = 10, ?string $search = null): void
    {

        //bdump($this->model->getAllMusicAlbums());

        $this->template->title = "Custom Datatable example";
        $dataset_example = $this->dataset_example;

        if ($search !== null && trim($search) !== '') {
            $searchLower = mb_strtolower(trim($search));

            $dataset_example = array_filter($dataset_example, function ($row) use ($searchLower) {
                foreach ($row as $value) {
                    if (stripos((string)$value, $searchLower) !== false) {
                        return true;
                    }
                }
                return false;
            });

            $dataset_example = array_values($dataset_example);
        }

        $totalRecords = count($dataset_example);
        $offset = ($current - 1) * $limit;
        $pageData = array_slice($dataset_example, $offset, $limit);
        $columns = ($totalRecords>0) ? array_keys((array)$dataset_example[0] ?? []) : [];

        $this->template->records = $pageData;
        $this->template->columns = $columns;
        $this->template->totalRecords = $totalRecords;
        $this->template->current = $current;
        $this->template->limit = $limit;
        $this->template->search = $search;
    }


}
