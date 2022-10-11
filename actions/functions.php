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

function get_file_list($directory)
{
    if (!is_dir($directory)) return false;

    $scan_rep= scandir($directory);
    $result["file_info"]=array();
    $result["years"]=array();

    foreach($scan_rep as $file)
    {

        if (!is_file($directory."/".$file)) continue;
            // the year of the file is contained in the name
        $file_year= substr($file,4,4);
        $created_year = date("Y", filectime($directory."/".$file));
        $created_month = date("m", filectime($directory."/".$file));

        $result["file_info"][$file]= array(
            "file_year" => $file_year,
            "created_year" =>$created_year,
            "created_month" =>$created_month,
        );

        // create a list of years 
        if(!in_array($file_year, $result["years"])) array_push($result["years"],$file_year);
    }

    return $result;
}

function search_expression_in_all_files(string $expression, $directory )
{
        // prepare pattern for preg_match search
    $pattern="~".$expression."~";

    if (!is_dir($directory)) return false;

    $scan_rep= scandir($directory);

    foreach($scan_rep as $file)
    {
        
        if (!is_file($directory."/".$file)) continue;



            // open file in read only mode
        $ressource = fopen($directory."/".$file,"r");


            // read the content of the file lines by lines
        $content=fgets($ressource);
        while($content)
        {
                // if the expression is in the line -> we return true and stop the function
            if (preg_match($pattern,$content)) return true;

            // read next line
            $content=fgets($ressource);
        }
            // once finished close the ressource / file
        fclose($ressource);
    }

    return false;
}

