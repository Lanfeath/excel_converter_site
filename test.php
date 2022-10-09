<?php
include_once "./actions/functions.php";
/*
$int = 251.02;
$str=strval($int);
echo "str: $str <br>";

$str= str_replace(array(".",","),"",$str);
echo $str;
//echo date("Ymd:H-m-s");

echo realpath("./actions/function.php");


$row_header_GFI = array(
    "B1" => "Type",
    "C1" => "Référence",
    "D1" => "Objet",
    "E1" => "Code client",
    "F1" => "Libellé client",
    "G1" => "Montant TTC",
    "H1" => "Montant HT",
);

foreach($row_header_GFI as $row_name => $row_value)
{
    echo "row_name: $row_name & row_value: $row_value <br>";
}
*/

$list_files= get_file_list("./gfi_final_csv");
/*
echo array_keys($files["file_info"])[0];
*/

foreach($list_files["file_info"] as $file_name => $file_value)
{
    var_dump($file_name);
    echo "<br>";
    var_dump($file_value["created_year"]);
    echo "<br>";
}
