<?php
session_start();

include_once "./actions/functions.php";

if (isset($_SESSION["error"])) $error=$_SESSION["error"];

(isset($_SESSION["dl_file"])) ? $download_file = true : $download_file = false;
(isset($_SESSION["file_name"])) ? $file_name = $_SESSION["file_name"] : $file_name = false;

session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">    

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./assets/excel_site_style.css" rel="stylesheet">
     </head>

    <body>
        
        <header>
            <img src="./assets/ministere_sport.png" alt="Logo ministère des sports" class="align-left">
            <img src="./assets/creps_toulouse.png" alt="CREPS Toulouse">
            <img src="./assets/region_occitanie.png" alt="Logo région occitanie" class="align-right">
        </header>

        <content>
            <div>
                <div>
                    <h1> Bienvenue sur le site pour convertir les fichiers GFI </h1>
                </div>

                <?php

                    // si une erreur s'est présentée on informe l'utilisateur:
                if (isset($error))
                {
                    echo ' <div>';
                    echo'<p class="erreur"> Le fichier sélectionné';
                    if (isset($error["file_name"])) 
                    {
                        echo ' <strong> '.$error["file_name"] .'</strong>';
                    }
                    echo ' a provoqué une erreur <p>';

                    echo '<p class="erreur_msg"> Message d\'erreur: <br><br>"'.$error["msg"].'" <p> </div>';
                }
                    // On affiche cette div que si un fichier est a selectionner
                if(! $download_file)
                {
                    echo '
                        
                        <div class="item">
                        <h2>Sélectionner votre fichier</h2>
                            <form enctype="multipart/form-data" action="./actions/xl_converter.php" method="POST">
                                <div>
                                    <!-- MAX_FILE_SIZE must precede the file input field -->
                                    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                                    
                                    <!-- Name of input element determines name in $_FILES array -->
                                    <input name="fichier_gfi" id="fichier_gfi" type="file"
                                        accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                                </div>
                                <br>
                                <div>
                                        <input type="submit" value="Envoyer" />
                                </div>
                            </form>
                        </div>
                    ';
                }
                
                    // On affiche cette div que si un fichier est a tétécharger
                if($download_file && $file_name)
                {
                    echo '
                        <div class="item">
                            <h2>Télécharger votre fichier converti</h2>
                            <a href="./gfi_final_csv/' . $_SESSION["file_name"] . '" download>Fichier GFI Final .csv</a>
                        </div>
                    ';
                }
                echo '
                    <div class="item">
                        <h2>Téléchargez un fichier précédent</h2>
                    ';

                // On récupère la liste des fichiers du dossier GFI Final CSV:
                $list_files= get_file_list("./gfi_final_csv");

                $list_of_files=array_keys($list_files["file_info"]);
                $list_year= array_values($list_files["years"]);

                    // On trie les 2 listes par ordre décroissant pour afficher les derniers créés en derniers
                rsort($list_year);
                rsort($list_of_files);

                $count_recent=(count($list_files["file_info"])>=4)?4:count($list_files["file_info"]);
                
                    if($count_recent!==0)
                    {
                        echo '
                        <form method="post" action="./actions/download.php">
                            <div>
                                <select name="file_dl">
                                <optgroup label="Récent">
                                    <option value=null>Selectionner un fichier</option>
                        ';
                                
                                for ($i=0; $i<$count_recent; $i++)
                                {
                                    echo '
                                    <option value ="'.$list_of_files[$i].'">'.$list_of_files[$i].'</option>';
                                }
                                
                                echo '</optgroup>';
                                
                                for ($i=1; $i<=count($list_files["years"]); $i++)
                                {
                                    echo '
                                    <optgroup label="Année '.$list_year[$i-1].'">';
                                    
                                    foreach($list_files["file_info"] as $file_name => $file_value )
                                    {
                                        if($file_value["file_year"] === $list_year[$i-1] )
                                        {
                                            echo '
                                                <option value="'.$file_name.'">'.$file_name.'</option>
                                            ';
                                        }
                                        
                                    }
                                    echo '</optgroup>';
                                }

                                echo '</select>
                            </div>
                            <br>
                            <div>
                                <button>Envoyer</button>
                            </div>
                        </form>
                        ';
                    }
                    else
                    {
                        echo ' <p>Aucun fichier récent disponibles sur le serveur </p>';
                    }

                echo'
                    </div>
                ';

                ?>

            </div>
            <?php
                    // On affiche cette div que si un fichier a été selectionné
                if($download_file)
                {
                    // au clic sur le bouton un script JS recharge la page
                    echo"
                        <div>                
                            <button onclick='window.location.reload();'>
                                Convertir un nouveau fichier
                            </button>
                        </div>
                    ";
                }
            ?>
        </content>

    </body>

</html>