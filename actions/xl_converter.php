<?php
session_start();

include_once "functions.php";

/* ######### Initialisating the file needed ########### */

    require 'E:\xampp\htdocs\php_learning\vendor\autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* ######### END OF Initialisating the file needed ########### */

if(($_FILES)===array()) 
{
    $_SESSION["error"]["msg"]="Un problème est survenu lors du chargement du fichier (trop lourd ou autre motif).";
    header("Location: ../excel_site.php");
    exit;
}


/* ######### Check file uploaded for errors  ########### */

    // Check $_FILES['fichier_gfi']['error'] value.
    switch ($_FILES['fichier_gfi']['error']) {
        case 0:
            // the upload was successful
            break;
        case 2:
            $_SESSION["error"]["msg"]= 'Fichier trop volumineux (max 1 000 ko).';
            break;
        case 3:
            $_SESSION["error"]["msg"]= 'Le fichier a été partiellement envoyé.';
            break;
        case 4:
            $_SESSION["error"]["msg"]= "Aucun fichier n'a été envoyé";
            break;
        default:
            $_SESSION["error"]["msg"]='Erreur inconnue.';
    }

    // Check filesize here.
    if ($_FILES['fichier_gfi']['size'] > 1000000) {
        $_SESSION["error"]["msg"]= 'Fichier trop volumineux (max 30 000o).';
    }

    // Check MIME Type must be either xls or xlsx.
    if (!isset($_SESSION["error"]))
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        if (false === $ext = array_search(
            $finfo->file($_FILES['fichier_gfi']['tmp_name']),
            array(
                "xls" => "application/vnd.ms-excel", 
                "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            ),
            true
        )) {
            //throw new RuntimeException('Invalid file format.');
            $_SESSION["error"]["msg"]="L'extension du fichier n'est pas correcte. Ce doit être un .xls ou un .xlsx";
        } 
    }

     // If error go to main page and get file name
    if (isset($_SESSION["error"])) 
    {
        $_SESSION["error"]["file_name"]=$_FILES['fichier_gfi']['name'];
        header("Location: ../excel_site.php");
        exit;
    }

/* ****************** End of Check file uploaded for errors  ****************** */


// get the type of extension of the file:
($finfo->file($_FILES['fichier_gfi']['tmp_name']) === "application/vnd.ms-excel") ? $xl_extension="xls": $xl_extension="xlsx";


/* ######### SAVE file on server ########### */

    $uploads_dir = '../excel_files/';
    $tmp_name = "";
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
    $name = "GFI.". $xl_extension;
    move_uploaded_file($_FILES["fichier_gfi"]["tmp_name"], "$uploads_dir/$name");

    $file_path=realpath($uploads_dir."/".$name);

/* ****************** END OF SAVE file on server ****************** */


/* ######### Open EXCEL file and check if it mach usual GFI format ########### */

    $dl_spreadsheet= open_excel_file_by_ext($xl_extension,$file_path);
    $row_header_GFI = array(
        "B1" => "Type",
        "C1" => "Référence",
        "D1" => "Objet",
        "E1" => "Code client",
        "F1" => "Libellé client",
        "G1" => "Montant TTC",
        "H1" => "Montant HT",
    );

    foreach($row_header_GFI as $col_name => $col_value)
    {
        $value= $dl_spreadsheet->getActiveSheet()->getCell($col_name)->getValue();

        if( $value !== $col_value) 
        {
            $_SESSION["error"]["msg"]= "Le fichier Excel n'a pas la structure d'un fichier GFI. <br> <br> La colonne $col_name a pour valeur: '$value' au lieu de '$col_value'.";
            $_SESSION["error"]["file_name"]=$_FILES['fichier_gfi']['name'];
            header("Location: ../excel_site.php");
            exit;
        }
    }

/* ****************** END OF Open EXCEL file and check if it mach usual GFI format ****************** */


/* ######### Convert file excel to GIF Final.csv ########### */

    // the first row in the final file will have always the same row header
    $row_header = array(
        "A1" => 1,
        "B1" => 33153940,
        "C1" => "PROD",
        "D1" => "INVOICE",
    );

    // writing in a new spreadsheet:
    $final_spreadsheet = new Spreadsheet();
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($final_spreadsheet);

    // set the first ligne with the $row_header array values
    $final_spreadsheet->getActiveSheet()->setCellValue('A1', $row_header["A1"]);
    $final_spreadsheet->getActiveSheet()->setCellValue('B1', $row_header["B1"]);
    $final_spreadsheet->getActiveSheet()->setCellValue('C1',  $row_header["C1"]);
    $final_spreadsheet->getActiveSheet()->setCellValue('D1',  $row_header["D1"]);

        /* ###  Get the value from the open file to put it in the new file */
    // initiate the variable $i to move between rows /!\ initiate at 2 (skip the first row)
    $i=2;
    // separate variable $j for the GFI_final_temp file to always write in last line
    $j=2;

    // get the current date + 90 days and show them all attached (Year-Month-Day): 20221004
    $day_date_90_plus = date("Ymd", strtotime("+90 days"));

    // check if the first line of the GFI file is not empty 
    $is_not_empty= ($dl_spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue()) !== Null;

    while ($is_not_empty)
    {
        // get value from column C and G at the row $i
        $cellValueC = $dl_spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue();
        $cellValueG = $dl_spreadsheet->getActiveSheet()->getCell('G'.$i)->getValue();

        // if the line is already registered in one of the file -> we don't copy it and pass to the next      
        if(search_expression_in_all_files($cellValueC, "../gfi_final_csv")) 
        {
            $i+=1;
            $is_not_empty= ($dl_spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue()) !== Null;
            continue;
        }
        // remove either the comma or the point from the value got
        $cellValueG= intval(str_replace(array(".",","),"",strval($cellValueG)));

        // and write it on the other temp file
        $final_spreadsheet->getActiveSheet()->setCellValue('A'.$j, "PAYMENT");
        $final_spreadsheet->getActiveSheet()->setCellValue('B'.$j, $cellValueC);
        $final_spreadsheet->getActiveSheet()->setCellValue('C'.$j, $cellValueG);
        $final_spreadsheet->getActiveSheet()->setCellValue('D'.$j, 978);
        $final_spreadsheet->getActiveSheet()->setCellValue('F'.$j, 0);
        $final_spreadsheet->getActiveSheet()->setCellValue('H'.$j, $day_date_90_plus);
        $final_spreadsheet->getActiveSheet()->setCellValue('K'.$j, true);

        // increment of the value $i and check if the next line is not empty
        $i+=1;
        $j+=1;

        $is_not_empty= ($dl_spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue()) !== Null;
    }

        // If no line has been written as all the lines were already inserted in a previous GFI: error
    if($final_spreadsheet->getActiveSheet()->getCell('C2')->getValue() === null) 
    {
        $_SESSION["error"]["msg"]= "Toutes les commandes ont déjà été traitées.";
        $_SESSION["error"]["file_name"]=$_FILES['fichier_gfi']['name'];
        header("Location: ../excel_site.php");
        exit;
    }

    // know the year of the file: based on the reference number
    $file_year= substr($final_spreadsheet->getActiveSheet()->getCell('B2')->getValue(),1,4);
    
    // save the temp file in xlsx format
    $writer->save('../gfi_final_csv/temp_GFI-final.xlsx');

    // write the file in .csv and add some specifications about the separator wanted

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($final_spreadsheet);
    $writer->setDelimiter(';');
    $writer->setEnclosure('"');
    $writer->setLineEnding("\r\n");
    $writer->setSheetIndex(0);

    // set the separator for decimal and thousands
    \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setDecimalSeparator('.');
    \PhpOffice\PhpSpreadsheet\Shared\StringHelper::setThousandsSeparator(" ' ");
    
    $file_name="GFI_".$file_year."_cree_".date("ymdHis").".csv";
    $dir_name= ('../gfi_final_csv/');

    $writer->save($dir_name. $file_name);

        // Delete unnecessary files from server
    unlink("../gfi_final_csv/temp_GFI-final.xlsx");

/* ****************** END OF Convert file excel to GIF Final.csv ****************** */

    // shows to page excel_site.php that we have 1 file ready to be downloaded
$_SESSION["dl_file"]=true;
$_SESSION["file_name"]=$file_name;

header("Location: ../excel_site.php");

