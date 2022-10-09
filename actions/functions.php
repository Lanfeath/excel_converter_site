<?php
function open_excel_file_by_ext(string $xl_extension, $file_path)
{
    // $xl_extension is either xls or xlsx
    if($xl_extension==="xls")
    {
        // read a xls file
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($file_path);
    }
    else
    {
        // read a xlsx file 
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
    }
    
    return $spreadsheet;
}

function get_file_list($repertory)
{
    if (!is_dir($repertory)) return false;

    $scan_rep= scandir($repertory);
    $result["file_info"]=array();
    $result["years"]=array();


    foreach($scan_rep as $file)
    {
        if (!is_file($repertory."/".$file)) continue;

        $created_year = date("Y", filectime($repertory."/".$file));
        $created_month = date("m", filectime($repertory."/".$file));
        $result["file_info"][$file]= array(
            "created_year" =>$created_year,
            "created_month" =>$created_month,
        );

        if(!in_array($created_year, $result["years"])) array_push($result["years"],$created_year);
    }

    return $result;
}

