<?php
   // include_once "./assets/functions.php";
   // include_once "./assets/variables.php";
?>

<!DOCTYPE html>
<html lang="fr">    

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./excel_site_style.css" rel="stylesheet">
     </head>

    <body>
        
        <header>
            <img src="./ministere_sport.png" alt="Logo ministère des sports" class="align-left">
            <img src="./creps_toulouse.png" alt="CREPS Toulouse">
            <img src="./region_occitanie.png" alt="Logo région occitanie" class="align-right">
        </header>

        <content>

            <div>
                <h1> Bienvenue sur le site pour convertir les fichiers GFI </h1>
            </div>

            <div class="item">
                <h2>Sélectionner votre fichier</h2>
                <form method="post">
                    <div>
                        
                        <input type="file"
                            id="fichier_GFI" name="fichier_GFI"
                            accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div>
                        <button>Envoyer</button>
                    </div>
                </form>
            </div>

            <div class="item">
                <h2>Télécharger votre fichier converti</h2>
                <a href="./test.txt" download>Fichier GFI Final .csv</a>
            </div>

            <div class="item">
                <h2>Téléchargez un fichier précédent</h2>
                <form method="post">
                    <div>
                        <select name="file_dl">
                        <optgroup label="Récent">
                            <option value ="file_1">Fichier 1</option>
                            <option value ="file_2">Fichier 2</option>
                            <option value ="file_3">Fichier 3</option>
                            <option value ="file_4">Fichier 4</option>
                            </optgroup>
                            <optgroup label="Année 2022">
                                <option>Fichier 2022-1</option>
                                <option>Fichier 2022-2</option>
                                <option>Fichier 2022-3</option>
                                <option>Fichier 2022-4</option>
                                <option>Fichier 2022-1</option>
                                <option>Fichier 2022-2</option>
                                <option>Fichier 2022-3</option>
                                <option>Fichier 2022-4</option>
                                <option>Fichier 2022-1</option>
                                <option>Fichier 2022-2</option>
                                <option>Fichier 2022-3</option>
                                <option>Fichier 2022-4</option>
                            </optgroup>
                            <optgroup label="Année 2020">
                                <option>Fichier 2020-1</option>
                                <option>Fichier 2020-2</option>
                                <option>Fichier 2020-3</option>
                                <option>Fichier 2020-4</option>
                                <option>Fichier 2020-1</option>
                                <option>Fichier 2020-2</option>
                                <option>Fichier 2020-3</option>
                                <option>Fichier 2020-4</option>
                                <option>Fichier 2020-1</option>
                                <option>Fichier 2020-2</option>
                                <option>Fichier 2020-3</option>
                                <option>Fichier 2020-4</option>
                            </optgroup>
                            <optgroup label="Année 2020">
                                <option>Fichier 2020-1</option>
                                <option>Fichier 2020-2</option>
                                <option>Fichier 2020-3</option>
                                <option>Fichier 2020-4</option>
                                <option>Fichier 2020-1</option>
                                <option>Fichier 2020-2</option>
                                <option>Fichier 2020-3</option>
                                <option>Fichier 2020-4</option>
                                <option>Fichier 2020-1</option>
                                <option>Fichier 2020-2</option>
                                <option>Fichier 2020-3</option>
                                <option>Fichier 2020-4</option>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <button>Envoyer</button>
                    </div>
                </form>
            </div>

        </content>

    </body>

</html>