<?php
    //Parameter $toRead musi byt konkretny file, parameter $dlm je znak delimiteru.
    //Vracia kompletny parsovany array kde $csvArray[0] obsahuje nazvy stlpcov zahlavia CSV suboru
    //a dalej idu jednotlive zaznamy.
    function readCSVFile($toRead,$dlm)
    {
        $csvArray = array_map(function($f) use ($dlm) {return str_getcsv($f,$dlm);}, file($toRead));
        return $csvArray;
    }

    function arrayToCSVDownload($toSave,$filename,$dlm)
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'. $filename .'";');

        $f = fopen('php://output', 'w');

        foreach($toSave as $line) 
        {
            fputcsv($f, $line, $dlm);
        }

        fclose($f);
    }    
?>