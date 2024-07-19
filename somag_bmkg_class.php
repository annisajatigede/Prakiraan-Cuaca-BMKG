<?php
/**
 * author   : Igun Gunawan
 * email    : aagun2006@gmail.com 
 * web      : https://sumedangonline.com
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * Parameter
 * 0 Kelembapan Udara dalam % (key id: hu)
 * 1 Kelembapan Udara Maksimum dalam % (key id: humax)
 * 2 Suhu Udara Maksimum dalam °C dan °F (key id: tmax)
 * 3 Kelembapan Udara Minimum dalam % (key id: humin)
 * 4 Suhu Udara Minimum dalam °C dan °F (key id: tmin)
 * 5 Suhu Udara dalam °C dan °F (key id: t)
 * 6 Cuaca berupa kode cuaca (key id: weather)
 * 7 Arah Angin dalam derajat, CARD, dan SEXA (key id: wd)
 * 8 Kecepatan Angin dalam knot, mph, kph, dan ms (key id: ws)
 * 
 * Cara penggunaan untuk provinsi Jawa Barat:
 * $bmkg = new somagBMKG('Sumedang');
 * $bmkg->path_icon = 'images/icon_cuaca/';
 * $bmkg->icon_sufix = '-white';
 * $data = $bmkg->get_prakiraan_hari_ini();
 * 
 * Untuk luar provinsi Jawa Barat tinggal tambahkan host
 * 
 * $bmkg = new somagBMKG('Aceh Barat');
 * $bmkg->host = 'https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Aceh.xml';
 * 
 * atau
 * 
 * $bmkg = new somagBMKG('Aceh Barat','https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Aceh.xml');
 * 
 * $bmkg->path_icon = 'images/icon_cuaca/';
 * $bmkg->icon_sufix = '-white';
 * $data = $bmkg->get_prakiraan_hari_ini();
 * 
 * */ 
 
class somagBMKG {
	var $url = 'https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-JawaBarat.xml';
    public $host;
    public $kota;
    public $path_icon;
    public $icon_sufix;

    var $cuaca = array(
                    0 => "Cerah",
                    1 => "Cerah Berawan",
                    2 => "Cerah Berawan",
                    3 => "Berawan",
                    4 => "Berawan Tebal",
                    5 => "Udara Kabur",
                    100 => "Cerah",
                    101 => "Cerah Berawan",
                    102 => "Cerah Berawan",
                    103 => "Berawan",
                    104 => "Berawan Tebal",
                    10 => "Asap",
                    45 => "Berkabut",
                    60 => "Hujan Ringan",
                    61 => "Hujan Sedang",
                    63 => "Hujan Lebat",
                    80 => "Hujan Lokal",
                    95 => "Hujan Petir",
                    97 => "Hujan Petir"
                );

    var $arahAngin = array(
                    'N'     =>  "Utara",
                    'NNE'   =>  "Utara Timur Laut",
                    'NE'    =>  "Timur Laut",
                    'ENE'   =>  "Timur - Timur Laut",
                    'E'     =>  "Timur",
                    'ESE'   =>  "Timur-Tenggara",
                    'SE'    =>  "Tenggara",
                    'SSE'   =>  "Selatan - Tenggara",
                    'S'     =>  "Selatan",
                    'SSW'   =>  "Selatan - Barat Daya",
                    'SW'    =>  "Barat Daya",
                    'WSW'   =>  "Barat - Barat Daya",
                    'W'     =>  "Barat",
                    'WNW'   =>  "Barat - Barat Laut",
                    'NW'    =>  "Barat Laut",
                    'NNW'   => "Utara - Barat Laut",
                    'VARIABLE'=> 'Berubah-ubah'
                );     

    function __construct($kota=false,$urlData=false) {
        $this->kota = $kota;
        if($urlData){
            $this->host = $urlData;
        }else{
            $this->host = $this->url;
        }
	}

    function render_url(){
        $ch = curl_init($this->host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if (!$html = curl_exec($ch)){
            return 'Tidak Terhubung Dengan Server';
        } else {
            curl_close($ch);
            $xml = simplexml_load_string($html);
            $json = json_encode($xml);
            $json = str_replace('"@attributes"','"attributes"',$json);
            $array = json_decode($json,TRUE);
            $areas = $array['forecast']['area'];
            return $areas;
        }
    }

    function get_data_bmkg(){
        return $this->render_url();
    }

    function get_kota(){
        $kota = $this->get_data_bmkg();
        foreach($kota as $namakota){
            if( strtolower($namakota['name'][0]) == strtolower($this->kota) ){
                return $namakota;
            }
        }
    }

    function get_param($str){
        $city = $this->get_kota();
        foreach($city['parameter'] as $param){
            if($param['attributes']['id']==$str){
                return $param['timerange'];
            }
        }
    }

    function get_param_val_by_time($str){
        date_default_timezone_set("Asia/Jakarta");
        $param = $this->get_param($str);
        $day    = date("d M Y",time());
        $saatIni =  date("H",time()) ;
        $text = [];
        $data = [];
        foreach($param as $params){
            if($str=='tmin' || $str=='tmax' || $str =='humin' || $str =='humax'){
                $timeParams = strtotime($params['attributes']['day']); 
                switch($str){
                    case'tmin';
                        if( strtotime($day)==$timeParams){
                            $data = array('id'=>$str,'celcius'=>$params['value'][0],'fahrenheit'=>$params['value'][1],'datetime'=>$timeParams,'desc'=>'Suhu Udara Minimum dalam °C dan °F');
                        }   
                    break;
                    case'tmax';
                        if( strtotime($day)==$timeParams){
                            $data = array('id'=>$str,'celcius'=>$params['value'][0],'fahrenheit'=>$params['value'][1],'datetime'=>$timeParams,'desc'=>'Suhu Udara Maksimum dalam °C dan °F');
                        }   
                    break;
                    case'humin';
                        if( strtotime($day)==$timeParams){
                            $data = array('id'=>$str,'celcius'=>$params['value'],'fahrenheit'=>$params['value'][1],'datetime'=>$timeParams,'desc'=>'Kelembapan Udara Minimum dalam %');
                        }   
                    break;
                    case'humax';
                        if( strtotime($day)==$timeParams){
                            $data = array('id'=>$str,'celcius'=>$params['value'],'fahrenheit'=>$params['value'][1],'datetime'=>$timeParams,'desc'=>'Kelembapan Udara Maksimum dalam %');
                        }   
                    break;
                }
                
            }else{
                $timeParams = $params['attributes']['h']; 
                $next       = $timeParams+6;
                if($saatIni >= $timeParams && ($saatIni)<=$next){
                    switch($str){
                        case'weather':
                            $text = array('text'=>$this->cuaca[$params['value']],'icon'=>$this->path_icon.$params['value'].$this->icon_sufix.'.png','desc'=>'Cuaca berupa kode cuaca');
                        break;
                        case'hu':
                            $text = array('desc'=>'Kelembapan Udara dalam %');
                        break;
                        case't':
                            $text = array('celcius'=>$params['value'][0],'fahrenheit'=>$params['value'][1],'desc'=>'Suhu Udara dalam °C dan °F');
                        break;
                        case'ws':
                            $text = array('knot'=>$params['value'][0],'mph'=>round($params['value'][1],2),'kph'=>round($params['value'][2],2),'ms'=>round($params['value'][3],2),'desc'=>'Kecepatan Angin dalam knot, mph, kph, dan ms');
                        break;
                        case'wd':
                            $text = array('derajat'=>$params['value'][0],'card'=>$this->arahAngin[$params['value'][1]],'sexa'=>$params['value'][2],'desc'=>'Arah Angin dalam derajat, CARD, dan SEXA');                            
                        break;
                    }
                    $data = array(
                        'id'            => $str,
                        'kode'          =>$params['value'],
                        'date_time'     => time(),
                        'time_start'    =>$timeParams,
                        'time_end'      =>$next,
                    );
                }
            }
        }
        return array_merge($data,$text);
    }
    function get_prakiraan_hari_ini(){
        $data       = [];
        if( $this->get_kota() ){
            $weather    = $this->get_param_val_by_time('weather');
            $t          = $this->get_param_val_by_time('t');
            $tmin       = $this->get_param_val_by_time('tmin');
            $tmax       = $this->get_param_val_by_time('tmax');
            $hu         = $this->get_param_val_by_time('hu');
            $humin      = $this->get_param_val_by_time('humin');
            $humax      = $this->get_param_val_by_time('humax');
            $ws         = $this->get_param_val_by_time('ws');
            $wd         = $this->get_param_val_by_time('wd');
            $data = array(
                'date_time'         =>$weather['date_time'],
                'kota'              =>$this->kota,
                'time_start'        =>$weather['time_start'].':00',
                'time_end'          =>$weather['time_end'].':00',
                'range_time'        =>$weather['time_start'].':00'.'-'.$weather['time_end'].':00',
                'text'              =>$weather['text'],
                'icon'              =>$weather['icon'],
                't_celcius'         =>$t['celcius'],
                't_fahrenheit'      =>$t['fahrenheit'],
                'tmin_celcius'      =>$tmin['celcius'],
                'tmin_fahrenheit'   =>$tmin['fahrenheit'],
                'tmax_celcius'      =>$tmax['celcius'],
                'tmax_fahrenheit'   =>$tmax['fahrenheit'],
                'hu'                =>$hu['kode'],
                'humin_celcius'     =>$humin['celcius'],
                'humin_fahrenheit'  =>$humin['fahrenheit'],
                'humax_celcius'     =>$humax['celcius'],
                'humax_fahrenheit'  =>$humax['fahrenheit'],
                'ws_knot'           =>$ws['knot'],
                'ws_mph'            =>$ws['mph'],
                'ws_kph'            =>$ws['kph'],
                'ws_ms'             =>$ws['ms'],
                'ws_knot'           =>$ws['knot'],
                'wd_derajat'        =>$wd['derajat'],
                'wd_card'           =>$wd['card'],
                'wd_sexa'           =>$wd['sexa'],
            );
        }else{
            $data = array('info'=>'Data Kota Tidak Terdaftar dalam provinsi ini, anda menggunakan data '.$this->host.'. Kemungkinan kesalahan penulisan atau url host belum ditambahkan. Silakan cek url data di https://data.bmkg.go.id/prakiraan-cuaca/');
        }
        return $data;
    }
}