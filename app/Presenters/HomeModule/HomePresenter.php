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

use Nette\Application\Responses\FileResponse;
use App\Models\ExportModel;
use Nette;
use Shuchkin\SimpleXLSX;
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

        $this->template->data = $this->model->getMusicAlbumsByGenre();
        
    }

    public function renderTable(int $current = 1, int $limit = 10, ?string $search = null, ?string $sortBy = null, string $sortDir = 'asc'): void
    {
        $this->template->title = "Custom Datatable example";
    
        $table = $this->table($this->model->getAllMusicAlbums(), $current, $limit, $search, $sortBy, $sortDir);
    
        $this->template->records = $table['records'];
        $this->template->columns = $table['columns'];
        $this->template->totalRecords = $table['totalRecords'];
        $this->template->current = $table['current'];
        $this->template->limit = $table['limit'];
        $this->template->search = $table['search'];
        $this->template->sortBy = $table['sortBy'];
        $this->template->sortDir = $table['sortDir'];
    }
    
    public function table($dataset = [], int $current = 1, int $limit = 10, ?string $search = null, ?string $sortBy = null, string $sortDir = 'asc') {
        if ($search !== null && trim($search) !== '') {
            $searchLower = mb_strtolower(trim($search));
            $dataset = array_filter($dataset, function ($row) use ($searchLower) {
                foreach ($row as $value) {
                    if (stripos((string)$value, $searchLower) !== false) {
                        return true;
                    }
                }
                return false;
            });
    
            $dataset = array_values($dataset);
        }
    
        if ($sortBy !== null) {
            usort($dataset, function ($a, $b) use ($sortBy, $sortDir) {
                $valA = $a[$sortBy] ?? null;
                $valB = $b[$sortBy] ?? null;
    
                if ($valA == $valB) return 0;
    
                if ($sortDir === 'asc') {
                    return ($valA < $valB) ? -1 : 1;
                } else {
                    return ($valA > $valB) ? -1 : 1;
                }
            });
        }
    
        $totalRecords = count($dataset);
        $offset = ($current - 1) * $limit;
        $pageData = array_slice($dataset, $offset, $limit);
        $columns = ($totalRecords > 0) ? array_keys((array)$dataset[0] ?? []) : [];
    
        return [
            'records' => $pageData,
            'totalRecords' => $totalRecords,
            'columns' => $columns,
            'limit' => $limit,
            'search' => $search,
            'current' => $current,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
        ];
    }

    public function handleExportExcel(?string $search = null, ?string $sortBy = null, string $sortDir = 'asc'): void
    {
        // Recupera tutti i dati, non solo paginati
        $dataset = $this->model->getAllMusicAlbums();

        // Applica filtri e ordinamenti SENZA PAGINAZIONE
        $filteredTable = $this->table($dataset, 1, PHP_INT_MAX, $search, $sortBy, $sortDir);

        $this->exportExcel($filteredTable['records']);
    }


    public function exportExcel($dataset){

        $export_dir = dirname(dirname(__DIR__))."/xlsx/";
        
        //$dir = __DIR__ . "/exports/xlsx/";
        $file = "export.xlsx";
        $filePath = $export_dir . $file;
        // Assicurati che la cartella di destinazione esista
        if (!is_dir($export_dir)) {
            mkdir($export_dir, 0777, true);
        }
        
        // Se il file non esiste, crealo con permessi di lettura e scrittura
        if (!file_exists($filePath)) {
            touch($filePath);
        }
        chmod($filePath, 0666); // Permessi di lettura e scrittura per tutti
        
        $this->model->exportExcel($dataset, $file, $export_dir);
        $this->exportDownload($export_dir, $file);
    }

    public function exportDownload($dir,$nome_file){
        $file = $dir.$nome_file;
        $extension = mime_content_type($file);
        if(is_file($file)){
            $response = new FileResponse(
                $file, 
                $nome_file, 
                $extension);
                $this->sendResponse($response);
        } else {
            throw new Nette\FileNotFoundException($nome_file);
        }           
    }
    


    public function documentoUploadNew(Array &$data){

        $fase_dest = $data['fase'];
        $upload = $data['documento'];
        if(!$upload->isOk()) return false;
        $nomeFile = $upload->getSanitizedName();
        //$targetFile = __DIR__. "/imports/csv/" . $nomeFile;
        $import_dir = dirname(dirname(__DIR__))."/xlsx/";
        $file = "import.xlsx";
        $targetFile = $import_dir.$file;
        if (!is_dir($import_dir)) {
            mkdir($import_dir, 0777, true);
        }
        // Se il file non esiste, crealo con permessi di lettura e scrittura
        if (!file_exists($targetFile)) {
            touch($targetFile);
        }
        chmod($targetFile, 0666); // Permessi di lettura e scrittura per tutti

        
        if($upload->move($targetFile)){ 
            //$this->importModel->truncateAnagraficheDaAggiornare();
            //$array2 = $this->rendModel->getRendicontazioneImport();
            if($xlsx = SimpleXLSX::parse($targetFile)){
                $xlsx->setDateTimeFormat('Y-m-d');
                $array = array();
                $chiavi = array();
                $rows = $xlsx->rows();
                foreach($rows[0] as $chiave) {
                    $chiavi[] = $chiave;
                }
                //prendo i nomi della prima row e me li salvo come chiavi
                //bdump($chiavi);
                foreach(array_slice($rows, 1)  as $riga_excel) {
                    $row = [];
                    foreach($riga_excel as $key => $value) {
                        $row[$chiavi[$key]] = $value;
                    }
                    $array[] = $row;
                }
                //bdump($diff);
                return $this->updateModel->aggiornaFasiManuale($array,$fase_dest);
            }
            return false;
        }
        return false;
    }

}
