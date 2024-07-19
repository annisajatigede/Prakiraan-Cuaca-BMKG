Parameter
  
  0 Kelembapan Udara dalam % (key id: hu)<br>
  1 Kelembapan Udara Maksimum dalam % (key id: humax)<br>
  2 Suhu Udara Maksimum dalam °C dan °F (key id: tmax)<br>
  3 Kelembapan Udara Minimum dalam % (key id: humin)<br>
  4 Suhu Udara Minimum dalam °C dan °F (key id: tmin)<br>
  5 Suhu Udara dalam °C dan °F (key id: t)<br>
  6 Cuaca berupa kode cuaca (key id: weather)<br>
  7 Arah Angin dalam derajat, CARD, dan SEXA (key id: wd)<br>
  8 Kecepatan Angin dalam knot, mph, kph, dan ms (key id: ws)<br>
  <br>
Cara penggunaan untuk provinsi Jawa Barat (Default):<br>
  $bmkg = new somagBMKG('Sumedang');<br>
  $bmkg->path_icon = 'images/icon_cuaca/';<br>
  $bmkg->icon_sufix = '-white';<br>
  $data = $bmkg->get_prakiraan_hari_ini();<br>
  <br>
Untuk luar provinsi Jawa Barat tinggal tambahkan host<br>
  <br>
  $bmkg = new somagBMKG('Aceh Barat');<br>
  $bmkg->host = 'https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Aceh.xml';
  <br>
  atau
  <br>
  $bmkg = new somagBMKG('Aceh Barat','https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Aceh.xml');
  <br>
  $bmkg->path_icon = 'images/icon_cuaca/';<br>
  $bmkg->icon_sufix = '-white';<br>
  $data = $bmkg->get_prakiraan_hari_ini();<br>
<br>
jangan lupa tambahkan sumber rujukan BMKG  
  
