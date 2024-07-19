Parameter
  0 Kelembapan Udara dalam % (key id: hu)
  1 Kelembapan Udara Maksimum dalam % (key id: humax)
  2 Suhu Udara Maksimum dalam °C dan °F (key id: tmax)
  3 Kelembapan Udara Minimum dalam % (key id: humin)
  4 Suhu Udara Minimum dalam °C dan °F (key id: tmin)
  5 Suhu Udara dalam °C dan °F (key id: t)
  6 Cuaca berupa kode cuaca (key id: weather)
  7 Arah Angin dalam derajat, CARD, dan SEXA (key id: wd)
  8 Kecepatan Angin dalam knot, mph, kph, dan ms (key id: ws)
  
Cara penggunaan untuk provinsi Jawa Barat (Default):
  $bmkg = new somagBMKG('Sumedang');
  $bmkg->path_icon = 'images/icon_cuaca/';
  $bmkg->icon_sufix = '-white';
  $data = $bmkg->get_prakiraan_hari_ini();
  
Untuk luar provinsi Jawa Barat tinggal tambahkan host
  
  $bmkg = new somagBMKG('Aceh Barat');
  $bmkg->host = 'https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Aceh.xml';
  
  atau
  
  $bmkg = new somagBMKG('Aceh Barat','https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Aceh.xml');
  
  $bmkg->path_icon = 'images/icon_cuaca/';
  $bmkg->icon_sufix = '-white';
  $data = $bmkg->get_prakiraan_hari_ini();

jangan lupa tambahkan sumber rujukan BMKG  
  