<?php

namespace App\Models;

use Nette;
use Nette\Database\Explorer;
use Nette\Database\Row;


class DefaultModel {
    use Nette\SmartObject;

    private $database;
    public function __construct(Explorer $database) {
        //la variabile in input dichiara che questa classe prende in input l'ORM del db
        $this->database = $database;
    }


    public function query($query) {
        $th = [];
        $td = [];
        $results = $this->database->query($query);
        $td = $results->fetchAll();
        $i = 0;
        foreach ($td[0] as $key => $value) {
            $th[$i] = $key;
            $i++;
        }
        $ret = [];
        $ret["th"] = $th;
        $ret["td"] = $td;
        return $ret;
    }




    public function getAllMusicAlbums(){
        return $this->query('select * from music_albums');
    }


    public function arrayToRow(array $array): Row {
        $row = new Row();
        foreach ($array as $key => $value) {
            $row->$key = $value;
        }
        return $row;
    }


    public function export($data){        
        $filename = "export_". date("d-m-Y_H-i-s"). ".csv";
        $file = dirname(__DIR__)."/csv/export/". $filename;
        $puntatore = fopen($file, 'w');
        $array = $data['td'];
        $arrayTH = (array)$data['th'];
        fputcsv($puntatore, $arrayTH, ';', '"', "\n");
        foreach ($array as $row) {
            $row_array = (array)$row;
            fputcsv($puntatore, $row_array, ';', '"', "\n");
        }
        fclose($puntatore);
        return $file;
    }


    public function csvToArray($file, $from=0, $to=10000): array|null {
        $array = [];
        $file = dirname(__DIR__)."/csv/".$file;
        $info = (object)pathinfo($file);
        //bdump($info);
        
        $puntatore = fopen($file, 'r',);
        $arrayTH = fgetcsv($puntatore, null, ';', '"',"\n");

        //bdump($arrayTH);
        $cont = $from;
        $max = $cont + $to;
        while (($campi = fgetcsv($puntatore, null, ';', '"', "\n")) !== FALSE && ($cont < $max)) {
            //bdump($campi);
            $cont++;
            //$dataset = $this->database->table('pratiche')->where(false)->fetchAll();
            $x = array_combine($arrayTH,$campi);
            $array[] = $this->arrayToRow($x);
        }
        fclose($puntatore);
        return ["th"=> $arrayTH, "td" => $array];
    }





}