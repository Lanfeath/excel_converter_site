<?php

$int = 251.02;
$str=strval($int);
echo "str: $str <br>";

$str= str_replace(array(".",","),"",$str);
echo $str;
//echo date("Ymd:H-m-s");
