<?php

/* ######### Initialisating the file needed ########### */
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* ######### Initialisating the file needed ########### */

/* Exemples of use of classes
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');

// Set cell A4 with a formula
$spreadsheet->getActiveSheet()->setCellValue(
    'A4',
    '=IF(A3, CONCATENATE(A1, " ", A2), CONCATENATE(A2, " ", A1))'
);

// read a xlsx file 
$dl_spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("GFI-FINAL-20220812-14-10.xlsx");

*/

    // the first row in the final file will have always the same row header
$row_header = array(
    "A1" => 1,
    "B1" => 33153940,
    "C1" => "PROD",
    "D1" => "INVOICE",
);

$final_spreadsheet = new Spreadsheet();

// read a xls file
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
$dl_spreadsheet = $reader->load("GFI.xls");

// writing in a spreadsheet:
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($final_spreadsheet);

// set the first ligne with the $row_header array values
$final_spreadsheet->getActiveSheet()->setCellValue('A1', $row_header["A1"]);
$final_spreadsheet->getActiveSheet()->setCellValue('B1', $row_header["B1"]);
$final_spreadsheet->getActiveSheet()->setCellValue('C1',  $row_header["C1"]);
$final_spreadsheet->getActiveSheet()->setCellValue('D1',  $row_header["D1"]);

/* ###  Get the value from the open file to put it in the new file */
// initiate the variable $i to move between rows /!\ initiate at 2 (skip the first row)
$i=2;

// get the current date + 90 days and show them all attached (Year-Month-Day): 20221004
$day_date_90_plus = date("Ymd", strtotime("+90 days"));

// check if the first line of the GFI file is not empty 
$is_not_empty= ($dl_spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue()) !== Null;

while ($is_not_empty)
{
    // get value from column C and G at the row $i
    $cellValueC = $dl_spreadsheet->getActiveSheet()->getCell('C'.$i)->getValue();
    $cellValueG = $dl_spreadsheet->getActiveSheet()->getCell('G'.$i)->getValue();


    // remove either the comma or the point from the value got
    $cellValueG= intval(str_replace(array(".",","),"",strval($cellValueG)));

    // and write it on the other temp file
    $final_spreadsheet->getActiveSheet()->setCellValue('A'.$i, "PAYMENT");
    $final_spreadsheet->getActiveSheet()->setCellValue('B'.$i, $cellValueC);
    $final_spreadsheet->getActiveSheet()->setCellValue('C'.$i, $cellValueG);
    $final_spreadsheet->getActiveSheet()->setCellValue('D'.$i, 978);
    $final_spreadsheet->getActiveSheet()->setCellValue('F'.$i, 0);
    $final_spreadsheet->getActiveSheet()->setCellValue('H'.$i, $day_date_90_plus);
    $final_spreadsheet->getActiveSheet()->setCellValue('K'.$i, true);

    // increment of the value $i and check if the next line is not empty
    $i+=1;
    $is_not_empty= ($dl_spreadsheet->getActiveSheet()->getCell('B'.$i)->getValue()) !== Null;
}

// save the temp file in xlsx format
$writer->save("temp_GFI-final.xlsx");

// write the file in .csv and add some specifications about the separator wanted

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($final_spreadsheet);
$writer->setDelimiter(';');
$writer->setEnclosure('"');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);

// set the separator for decimal and thousands
\PhpOffice\PhpSpreadsheet\Shared\StringHelper::setDecimalSeparator('.');
\PhpOffice\PhpSpreadsheet\Shared\StringHelper::setThousandsSeparator(" ' ");

$writer->save("test.csv");

echo "finished on " . date("d-m-Y - H:s:m");