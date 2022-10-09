# excel_converter_site
Create an excel converter site for GFI for the CREPS

#Update 09.10.2022:
Set up upload of a file and various inspection of the file uploaded:
 - if a file is selected 
 - MIME type= xl or xlsx
 - size below 1 000ko
 - if the first row of the excel file match the GFI structure :
        "B1" => "Type",
        "C1" => "Référence",
        "D1" => "Objet",
        "E1" => "Code client",
        "F1" => "Libellé client",
        "G1" => "Montant TTC",
        "H1" => "Montant HT",

Set the list of file to be download (historic) by a scan of the file GFI_FINAL_CSV where are saved all the converted files

to be done:
 - download the historic file on button click
 - sort files on historic file list by date (from new to old)
 - general improvement of style by css
 - historic of file called GFI_emise and sweep of this file before conversion to not convert lines already done in the past.
