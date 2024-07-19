<?php
require_once('somag_bmkg_class.php');
function debugData($str){
    echo '<pre>';
    print_r($str);
    echo '</pre>';
  }

$bmkg = new somagBMKG('Aceh Barat');
$bmkg->host = 'https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Aceh.xml';
$bmkg->path_icon = 'images/icon_cuaca/';
$bmkg->icon_sufix = '-white';
$data = $bmkg->get_prakiraan_hari_ini();

  debugData($data);