<?php

if(isset($_POST['file_dl']))
{
    //Read the filename
    $filename = $_POST['file_dl'];

    //Check the file exists or not
    if(file_exists("../gfi_final_csv/".$filename)) 
    {
        $type = filetype($filename);

        // Send file headers
        header("Content-type: $type");
        //** If you think header("Content-type: $type"); is giving you some problems,
        //** try header('Content-Type: application/octet-stream');

        header("Content-Disposition: attachment;filename=$filename");
        header("Content-Transfer-Encoding: binary"); 
        header('Pragma: no-cache'); 
        header('Expires: 0');

        // Send the file contents.
        set_time_limit(0);
        ob_clean();
        flush();

        //Read the size of the file
        readfile("../gfi_final_csv/".$filename);

        //Terminate from the script
        exit;
    }
    else{
        $_SESSION["error"]["file_name"]=$filename;
        $_SESSION["error"]["msg"]= "Le fichier sélectionné n'existe pas";
    }
}else{
    $_SESSION["error"]["msg"]= "Aucun fichier sélectionné";
}

header("Location: ../excel_site.php");
