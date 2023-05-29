<?php
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y-m-d H:i:s');

$today_start_time = date('Y-m-d') . "00:00:00";
$today_end_time = date('Y-m-d') . "23:59:00";

session_start();
ob_start();


function veritabanibaglantisi()
{
    $ayarlar = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $baglanti_cumlesi = sprintf(
     
    );

    try {
        return  $baglanti = new pdo($baglanti_cumlesi, null, null, $ayarlar);

    } catch (pdoexception $ex) {
        die($ex->getmessage());
    }

//$baglanti = null;



}
$db=veritabanibaglantisi();
function hekimeaitolayankayitsorgula($hekim,$kullanicid){

    if ($hekim != $kullanicid) {
        $baskahastamuayeneduzenleme = yetkisorgula($kullanicid, "baskakayitmudahele");
        if ($baskahastamuayeneduzenleme == "") {
            echo 'disabled';
        }
    }
}
function hekimeaitolmayankayitsorgula($hekim,$kullanicid){

    if ($hekim != $kullanicid) {
        $baskahastamuayeneduzenleme = yetkisorgula($kullanicid, "baskakayitmudahele");
        if ($baskahastamuayeneduzenleme == "") {
            echo 'disabled';
        }
    }
}
function turkcetarih( $zt = 'now'){
    $f='j f y ';
    $z = date("$f", strtotime($zt));
    $donustur = array(
        'monday'    => 'pazartesi',
        'tuesday'   => 'salı',
        'wednesday' => 'çarşamba',
        'thursday'  => 'perşembe',
        'friday'    => 'cuma',
        'saturday'  => 'cumartesi',
        'sunday'    => 'pazar',
        'january'   => 'ocak',
        'february'  => 'şubat',
        'march'     => 'mart',
        'april'     => 'nisan',
        'may'       => 'mayıs',
        'june'      => 'haziran',
        'july'      => 'temmuz',
        'august'    => 'ağustos',
        'september' => 'eylül',
        'october'   => 'ekim',
        'november'  => 'kasım',
        'december'  => 'aralık',
        'mon'       => 'pts',
        'tue'       => 'sal',
        'wed'       => 'çar',
        'thu'       => 'per',
        'fri'       => 'cum',
        'sat'       => 'cts',
        'sun'       => 'paz',
        'jan'       => 'oca',
        'feb'       => 'şub',
        'mar'       => 'mar',
        'apr'       => 'nis',
        'jun'       => 'haz',
        'jul'       => 'tem',
        'aug'       => 'ağu',
        'sep'       => 'eyl',
        'oct'       => 'eki',
        'nov'       => 'kas',
        'dec'       => 'ara',
    );
    foreach($donustur as $en => $tr){
        $z = str_replace($en, $tr, $z);
    }
    if(strpos($z, 'mayıs') !== false && strpos($f, 'f') === false) $z = str_replace('mayıs', 'may', $z);


    return  $z;
}

function nettarih($tarihdegeri){
    if($tarihdegeri!=""){
        return date('d.m.Y H:i', strtotime($tarihdegeri));
    }
}

function duztarih($tarihdegeri){
    if($tarihdegeri!=""){
        return date('d.m.Y', strtotime($tarihdegeri));
    }
}

function searchtarih($tarihdegeri){
    if($tarihdegeri!=""){
        return date('d/m/Y', strtotime($tarihdegeri));
    }
}
function tredate($tarihdegeri){
    if($tarihdegeri!=""){
        return date('Y-m-d', strtotime($tarihdegeri));
    }
}


function tarihduzenle($tarih){
    $tarih=array_reverse(explode('-',$tarih));
    $tarih=implode('.',$tarih);
    return $tarih;
}
function islemtanimgetireki($deger)
{
    $baglanti = veritabanibaglantisi();


    $sorgu = $baglanti->query("select * from transaction_definitions  where description_supplement='$deger' ");

    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["description_name"];
}
function islemtanimgetirname($deger)
{
    $baglanti = veritabanibaglantisi();


    $sorgu = $baglanti->query("select * from transaction_definitions  where definition_name='$deger'");

    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["definition_supplement"];
}


function hastalarsoyadi($deger)
{
    $baglanti = veritabanibaglantisi();


    $sorgu = $baglanti->query("select * from patients where id='$deger'  ");

    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["patient_surname"];

}

function hastalaradi($deger)
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from patients where id='$deger' ");

    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["patient_name"];
}



function kullanicigetirid($deger)
{
    $baglanti = veritabanibaglantisi();

    if($deger){
        $sorgu = $baglanti->query("select * from users where id='$deger' ");

        $row = $sorgu->fetch(pdo::FETCH_ASSOC);
        if($row['id']!=""){
            return $row['name_surname'];

        }else{
            return false;
        }
    }else{
        return false;
    }
}


function islemtanimekgetir($tanimtipi,$deger)
{
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->query("select * from transaction_definitions where definition_supplement='$deger' and definition_type='$tanimtipi'");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["definition_name"];
}

function skortanimgetir($id)
{
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->query("select * from saps_definition where id='$id'");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["definition_key"];

}

function islemtanimgetirkod($deger)
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from transaction_definitions where definition_code='$deger' ");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["definition_name"];

}

function islemtanimgetirid($deger)
{
    $row=singular("transaction_definitions","id",$deger);
    return $row['definition_name'];

}

function labtanimgetirid($deger)
{
    $row=singular("lab_definitions","id",$deger);
    return $row['definition_name'];

}


function arasinial($str,$birinci,$ikinci,$i) {
    $bolum = explode ($birinci,$str);
    $bolum = explode ($ikinci,$bolum[$i]);
    return $bolum[0];
}


function get_guid() {
    $data = php_major_version < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function dahaoncekikayitsorgula($birim,$tc_kimlik){
//    $kayit_tarihi="";
    $nettarih = date('Y-m-d');
    $kayitbilgilerinigetir=tek("select * from patient_registration where tc_id='$tc_kimlik' and outpatient_id='$birim' order by id desc");
    $kayit_tarihi=$kayitbilgilerinigetir["insert_datetime"];
$sonkayit=explode(" ",$kayit_tarihi);
    $fark = ikitariharasindakigunfark($nettarih , $sonkayit[0]);
//    var_dump("$nettarih - $kayit_tarihi");
//    var_dump($nettarih , $kayit_tarihi);
//    var_dump($kayit_tarihi);
//    var_dump($nettarih);
//    var_dump($fark);
//    var_dump($birim);
    return $fark;
}

function medulawebservis($protokolno){
    $kayitbilgilerinigetir=tekil("patient_registration","id",$protokolno);
    extract($kayitbilgilerinigetir);
    $hekimbilgisigetir=tekil("users","id",$doctor);
    $gelissebebigetir=tekil("transaction_definitions","id",$reason_arrival);
    $tanimadi=$gelissebebigetir["definition_supplement"];
    $branskodu=$hekimbilgisigetir["dr_bras_code"];

    $hastabilgilerinigetir=tekil("patients","tc_id",$tc_id);
    $adres=$hastabilgilerinigetir["address"];
    $telefon=$hastabilgilerinigetir["phone_number"];
    $guiddegeri=get_guid();

    $tokenenvelope="";
    $tokenenvelope.='<soapenv:envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://servisler.ws.gss.sgk.gov.tr">';
    $tokenenvelope.='<soapenv:header>';
    $tokenenvelope.='<wsse:security soapenv:mustunderstand="0" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">';
    $tokenenvelope.='<wsse:usernametoken wsu:id="usernametoken-'.$guiddegeri.'" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">';
    $tokenenvelope.='<wsse:username>'.medula_kullanici_adi.'</wsse:username>';
    $tokenenvelope.='<wsse:password type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#passwordtext">'.medula_kullanici_sifresi.'</wsse:password>';
    $tokenenvelope.='</wsse:usernametoken>';
    $tokenenvelope.='</wsse:security>';
    $tokenenvelope.='</soapenv:header>';
    $tokenenvelope.='<soapenv:body>';
    $tokenenvelope.='<ser:hastakabul>';
    $tokenenvelope.='<provizyongiris>';
    $tokenenvelope.='<hastatckimlikno>'.$tc_id.'</hastatckimlikno>';
    $tokenenvelope.='<provizyontarihi>'.tarihduzenle(insert_datetime).'</provizyontarihi>';
    $tokenenvelope.='<sagliktesiskodu>'.medula_saglik_tesis_kodu.'</sagliktesiskodu>';
    $tokenenvelope.='<branskodu>'.$branskodu.'</branskodu>';
    $tokenenvelope.='<provizyontipi>'.$tanimadi.'</provizyontipi>';
    $tokenenvelope.='<tedavituru>a</tedavituru>';
    $tokenenvelope.='<tedavitipi>0</tedavitipi>';
    $tokenenvelope.='<takiptipi>n</takiptipi>';
    $tokenenvelope.='<takipno/>';
    $tokenenvelope.='<istisnaihal/>';
    $tokenenvelope.='<donortckimlikno></donortckimlikno>';
    $tokenenvelope.='<yesilkartsevkedentesiskodu>0</yesilkartsevkedentesiskodu>';
    $tokenenvelope.='<yesilkartsevkedentedavitipi>0</yesilkartsevkedentedavitipi>';
    $tokenenvelope.='<yesilkartsevkedentakiptipi/>';
    $tokenenvelope.='<yardimhakkiid>0</yardimhakkiid>';
    $tokenenvelope.='<plakano/>';
    $tokenenvelope.='<devredilenkurum>1</devredilenkurum>';
    $tokenenvelope.='<sigortalituru>1</sigortalituru>';
    $tokenenvelope.='<yakinlikkodu/>';
    $tokenenvelope.='<hastatelefon>'.$telefon.'</hastatelefon>';
    $tokenenvelope.='<hastaadres>'.kisalt($adres,90).'</hastaadres>';
    $tokenenvelope.='</provizyongiris>';
    $tokenenvelope.='</ser:hastakabul>';
    $tokenenvelope.='</soapenv:body>';
    $tokenenvelope.='</soapenv:envelope>';
    $url = "https://sgkt.sgk.gov.tr/medula/hastane/hastakabulislemleriws";

    $CURL = CURL_INIT($URL);
    CURL_SETOPT($CURL, CURLOPT_URL, $URL);
    CURL_SETOPT($CURL, CURLOPT_POST, TRUE);
    CURL_SETOPT($CURL, CURLOPT_RETURNTRANSFER, TRUE);

    $HEADERS = ARRAY(
        "CONTENT-TYPE: APPLICATION/SOAP+XML; CHARSET=UTF-8",
    );

    CURL_SETOPT($CURL, CURLOPT_HTTPHEADER, $HEADERS);


    CURL_SETOPT($CURL, CURLOPT_POSTFIELDS, $TOKENENVELOPE);

//FOR DEBUG ONLY!
    CURL_SETOPT($CURL, CURLOPT_SSL_VERIFYHOST, FALSE);
    CURL_SETOPT($CURL, CURLOPT_SSL_VERIFYPEER, FALSE);

    $resp = curl_exec($curl);
    curl_close($curl);
//    $ihsan= arasinial($resp,'<takipno>','</takipno>',1);
    return $resp;
}
function kisalt($kelime, $str = 10)
{
    if (strlen($kelime) > $str)
    {
        if (function_exists("mb_substr")) $kelime = mb_substr($kelime, 0, $str, "utf-8").'..';
        else $kelime = substr($kelime, 0, $str);
    }
    return $kelime;
}

function sistemdentcsorgula($TC_KIMLIK){

    $baglanti = veritabanibaglantisi();
    $hastabilgilerinigetir=tek("select * from patients where tc_id='$TC_KIMLIK'");
    $hastaresimgetir=tek("select * from patients_photo where patients_id='{$hastabilgilerinigetir["id"]}' order by id desc");
    
    echo "<hastaadi>".$hastabilgilerinigetir["patient_name"]."</hastaadi>";
    echo "<hastasoyadi>".$hastabilgilerinigetir["patient_surname"]."</hastasoyadi>";
    echo "<adres>".$hastabilgilerinigetir["address"]."</adres>";
    echo "<telefonnumarasi>".$hastabilgilerinigetir["phone_number"]."</telefonnumarasi>";
    echo "<kangrubu>".$hastabilgilerinigetir["blood_group"]."</kangrubu>";
    echo "<sosyalguvencesi>".$hastabilgilerinigetir["social_assurance"]."</sosyalguvencesi>";
    echo "<oncelik>".$hastabilgilerinigetir["priority"]."</oncelik>";
    echo "<dogumtarihi>".$hastabilgilerinigetir["birth_date"]."</dogumtarihi>";
    echo "<dogumyeri>".$hastabilgilerinigetir["birth_place"]."</dogumyeri>";
    echo "<kurum>".$hastabilgilerinigetir["institution"]."</kurum>";
    echo "<cinsiyet>".$hastabilgilerinigetir["gender"]."</cinsiyet>";
    echo "<fotograf>".$hastabilgilerinigetir["photo"]."</fotograf>";
    echo "<yas>".$hastabilgilerinigetir["age"]."</yas>";
    echo "<anneadi>".$hastabilgilerinigetir["mother"]."</anneadi>";
    echo "<babaadi>".$hastabilgilerinigetir["father"]."</babaadi>";
    echo "<il>".$hastabilgilerinigetir["county"]."</il>";
    echo "<ilce>".$hastabilgilerinigetir["district"]."</ilce>";
    echo "<uyruk>".$hastabilgilerinigetir["nationality"]."</uyruk>";
    echo "<medenihali>".$hastabilgilerinigetir["marital_status"]."</medenihali>";
    echo "<pasaportnumarasi>".$hastabilgilerinigetir["passport_number"]."</pasaportnumarasi>";
    echo "<meslegi>".$hastabilgilerinigetir["job"]."</meslegi>";
    echo "<ogrenim_durumu>".$hastabilgilerinigetir["education_status"]."</ogrenim_durumu>";
    if($hastaresimgetir["id"]){
    echo "<sonfotograf>".$hastaresimgetir["photo"]."</sonfotograf>";
} }

function kpssorgula($TC_KIMLIK){
    $guiddegeri=get_guid();

    $tokenEnvelope="";
    $tokenEnvelope .= "<s:Envelope xmlns:s=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:a=\"http://www.w3.org/2005/08/addressing\" xmlns:u=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">";
    $tokenEnvelope .= "<s:Header>";
    $tokenEnvelope .= "<a:Action s:mustUnderstand=\"1\">http://docs.oasis-open.org/ws-sx/ws-trust/200512/RST/Issue</a:Action>";
    $tokenEnvelope .= "<a:MessageID>urn:uuid:".$guiddegeri."</a:MessageID>";
    $tokenEnvelope .= "<a:ReplyTo>";
    $tokenEnvelope .= "<a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address>";
    $tokenEnvelope .= "</a:ReplyTo>";
    $tokenEnvelope .= "<a:To s:mustUnderstand=\"1\">https://kpsv2test.saglik.gov.tr/STS/STSService.svc</a:To>";

    $tokenEnvelope .= "<KurumKodu a:IsReferenceParameter=\"true\" xmlns=\"\">123456</KurumKodu>";
    $tokenEnvelope .= "<UygulamaKodu a:IsReferenceParameter=\"true\" xmlns=\"\">8353df93-453c-4e23-8be8-2f913dd35313</UygulamaKodu>";

    $tokenEnvelope .= "<o:Security s:mustUnderstand=\"1\" xmlns:o=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\">";
    $tokenEnvelope .= "<o:UsernameToken u:Id=\"uuid-".$guiddegeri."-1\">";
    $tokenEnvelope .= "<o:Username>test_user</o:Username>";
    $tokenEnvelope .= "<o:Password>f6)@6U:l</o:Password>";
    $tokenEnvelope .= "</o:UsernameToken>";
    $tokenEnvelope .= "</o:Security>";
    $tokenEnvelope .= "</s:Header>";
    $tokenEnvelope .= "<s:Body>";
    $tokenEnvelope .= "<trust:RequestSecurityToken xmlns:trust=\"http://docs.oasis-open.org/ws-sx/ws-trust/200512\">";
    $tokenEnvelope .= "<wsp:AppliesTo xmlns:wsp=\"http://schemas.xmlsoap.org/ws/2004/09/policy\">";
    $tokenEnvelope .= "<a:EndpointReference>";
    $tokenEnvelope .= "<a:Address>https://kpsv2test.saglik.gov.tr/Router/RoutingService.svc</a:Address>";
    $tokenEnvelope .= "</a:EndpointReference>";
    $tokenEnvelope .= "</wsp:AppliesTo>";
    $tokenEnvelope .= "<trust:KeyType>http://docs.oasis-open.org/ws-sx/ws-trust/200512/Bearer</trust:KeyType>";
    $tokenEnvelope .= "<trust:RequestType>http://docs.oasis-open.org/ws-sx/ws-trust/200512/Issue</trust:RequestType>";
    $tokenEnvelope .= "</trust:RequestSecurityToken>";
    $tokenEnvelope .= "</s:Body>";
    $tokenEnvelope .= "</s:Envelope>";
    $url = "https://kpsv2test.saglik.gov.tr/STS/STSService.svc";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Content-Type: application/soap+xml; charset=UTF-8",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


    curl_setopt($curl, CURLOPT_POSTFIELDS, $tokenEnvelope);

//for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);

//var_dump($resp);
    $ihsan= arasinial($resp,'<trust:RequestedSecurityToken>','</trust:RequestedSecurityToken>',1);
    $kpsRequestEnvelope= "";
    $kpsRequestEnvelope .= "<s:Envelope xmlns:s=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:a=\"http://www.w3.org/2005/08/addressing\" xmlns:u=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">";
    $kpsRequestEnvelope .= "<s:Header>";
    $kpsRequestEnvelope .= "<a:Action s:mustUnderstand=\"1\">https://www.saglik.gov.tr/KPS/01/01/2017/IKpsServices/BilesikKisiveAdresSorgula</a:Action>";
    $kpsRequestEnvelope .= "<a:MessageID>urn:uuid:".$guiddegeri."</a:MessageID>";
    $kpsRequestEnvelope .= "<a:ReplyTo>";
    $kpsRequestEnvelope .= "<a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address>";
    $kpsRequestEnvelope .= "</a:ReplyTo>";
    $kpsRequestEnvelope .= "<a:To s:mustUnderstand=\"1\">https://kpsv2test.saglik.gov.tr/Router/RoutingService.svc</a:To>";
    $kpsRequestEnvelope .= "<o:Security s:mustUnderstand=\"1\" xmlns:o=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\">";
// $kpsRequestEnvelope .= "<u:Timestamp u:Id=\"_0\">";
// $kpsRequestEnvelope .= "<u:Created>2022-03-18T12:40:29.506Z</u:Created>";
// $kpsRequestEnvelope .= "<u:Expires>2022-03-20T12:40:29.506Z</u:Expires>";
// $kpsRequestEnvelope .= "</u:Timestamp>";

    $kpsRequestEnvelope .= $ihsan;

    $kpsRequestEnvelope .= "</o:Security>";
    $kpsRequestEnvelope .= "</s:Header>";
    $kpsRequestEnvelope .= "<s:Body>";
    $kpsRequestEnvelope .= "<BilesikKisiveAdresSorgula xmlns=\"https://www.saglik.gov.tr/KPS/01/01/2017\">";
    $kpsRequestEnvelope .= "<kimlikNo>".$TC_KIMLIK."</kimlikNo>";
    $kpsRequestEnvelope .= "</BilesikKisiveAdresSorgula>";
    $kpsRequestEnvelope .= "</s:Body>";
    $kpsRequestEnvelope .= "</s:Envelope>";


    $curlaa = curl_init("https://kpsv2test.saglik.gov.tr/Router/RoutingService.svc");
    curl_setopt($curlaa, CURLOPT_URL, "https://kpsv2test.saglik.gov.tr/Router/RoutingService.svc");
    curl_setopt($curlaa, CURLOPT_POST, true);
    curl_setopt($curlaa, CURLOPT_RETURNTRANSFER, true);

    $headersA = array(
        "Content-Type: application/soap+xml; charset=UTF-8",
    );
    curl_setopt($curlaa, CURLOPT_HTTPHEADER, $headersA);


    curl_setopt($curlaa, CURLOPT_POSTFIELDS, $kpsRequestEnvelope);


    $soncikti = curl_exec($curlaa);
    curl_close($curlaa);
    return ($soncikti);
}

function turkcetarihsaatli( $zt = 'now'){
    $f='j f y g:i a';
    $z = date("$f", strtotime($zt));
    $donustur = array(
        'monday'    => 'pazartesi',
        'tuesday'   => 'salı',
        'wednesday' => 'çarşamba',
        'thursday'  => 'perşembe',
        'friday'    => 'cuma',
        'saturday'  => 'cumartesi',
        'sunday'    => 'pazar',
        'january'   => 'ocak',
        'february'  => 'şubat',
        'march'     => 'mart',
        'april'     => 'nisan',
        'may'       => 'mayıs',
        'june'      => 'haziran',
        'july'      => 'temmuz',
        'august'    => 'ağustos',
        'september' => 'eylül',
        'october'   => 'ekim',
        'november'  => 'kasım',
        'december'  => 'aralık',
        'mon'       => 'pts',
        'tue'       => 'sal',
        'wed'       => 'çar',
        'thu'       => 'per',
        'fri'       => 'cum',
        'sat'       => 'cts',
        'sun'       => 'paz',
        'jan'       => 'oca',
        'feb'       => 'şub',
        'mar'       => 'mar',
        'apr'       => 'nis',
        'jun'       => 'haz',
        'jul'       => 'tem',
        'aug'       => 'ağu',
        'sep'       => 'eyl',
        'oct'       => 'eki',
        'nov'       => 'kas',
        'dec'       => 'ara',
    );

    foreach($donustur as $en => $tr){
        $z = str_replace($en, $tr, $z);
    }

    if(strpos($z, 'mayıs') !== false && strpos($f, 'f') === false) $z = str_replace('mayıs', 'may', $z);
    return  $z;
}

//		$this->logdbbaglanti=$logdbbaglanti;
function arrayislemi($deger)
{
    $sonuc = implode(",", array_map(function ($deg)
    {
        return $deg;
    }
        , array_keys($deger)));

    return $sonuc;
}

function birimgetirid($deger)
{
    $yetkiidsi=singular("units","id",$deger);
    return $yetkiidsi['department_name'];
}

function denemedongu($deger)
{
    $sonuc = implode(",", array_map(function ($deg)
    {
        return ":" . $deg;
    }
        , array_keys($deger)));

    return $sonuc;
}

function arrayislemipost($deger)
{
    $sonuc = implode(",", array_map(function ($deg)
    {
        return "" . $deg;
    }
        , array_keys($deger)));

    return $sonuc;
}

function sonbukucu($deger)
{
    $sonuc = implode(",", array_map(function ($deg)
    {
        return $deg . "=:" . $deg;
    }
        , array_keys($deger)));

    return $sonuc;
}


function silme($tablo, $sutun, $id)
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("update $tablo set  status ='2' where $sutun=$id");
    $sil = $sorgu->fetch(pdo::FETCH_ASSOC);
    if ($sil)
    {
        return true;
    }
    else
    {
        return print_r($baglanti->errorinfo());
    }
}
function silmedetay($tablo,$sutun,$id,$detay,$silme,$tarih)
{
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->query("update $tablo set  status=0,silme_detay='$detay',silme_kullanici='$silme',silme_tarih='$tarih' where $sutun=$id");
    $sil = $sorgu->fetch(pdo::FETCH_ASSOC);
    if ($sil)
    {
        return 1;
    }
    else
    {
        return print_r($baglanti->errorinfo());
    }
}

function canceldetail($tablo,$sutun,$id,$detay,$silme,$tarih)
{
    $baglanti = veritabanibaglantisi();

     $delete_datetime = date('Y-m-d H:i:s');
     $delete_userid =   $_SESSION["id"];

    $sorgu = $baglanti->query("update $tablo set  status ='0',delete_detail='$detay',delete_userid='$delete_userid' , delete_datetime='$delete_datetime' where $sutun=$id");
    $sil = $sorgu->fetch(pdo::FETCH_ASSOC);
    if ($sil)
    {
        return true;
    }
    else
    {
        return print_r($baglanti->errorinfo());
    }
}

function sql_delete($tablo,$sutun,$id,$detay,$silme,$tarih)
{
    $baglanti = veritabanibaglantisi();

    $delete_datetime = date('Y-m-d H:i:s');
    $delete_userid =   $_SESSION["id"];

    $sorgu = $baglanti->query("update $tablo set  status=false,delete_detail='$detay',delete_userid='$delete_userid' , delete_datetime='$delete_datetime' where $sutun=$id");
    $sil = $sorgu->fetch(pdo::FETCH_ASSOC);
    if (isset($sil))
    {
        return 1;
    } else
    {
        return 0;
    }
}

function backcancel($tablo,$sutun,$id,$date,$user)
{
    $baglanti = veritabanibaglantisi();

    $update_userid = $_SESSION['id'];
    $update_datetime = date('Y-m-d H:i:s');

    $sorgu = $baglanti->query("update $tablo set  status ='1',update_userid='$update_userid',update_datetime='$update_datetime' where $sutun='$id'");
    $sil = $sorgu->fetch(pdo::FETCH_ASSOC);
    if ($sil)
    {
        return true;
    }
    else
    {
        return print_r($baglanti->errorinfo());
    }
}




function groupdirektekle($tablo, $deger, $silinecek = "",$grupdegeri)
{
    $baglanti = veritabanibaglantisi();
    try {
        $deger['insert_userid'] = $_SESSION['id'];
        $deger['insert_datetime'] = date('Y-m-d H:i:s');
        if (strlen($silinecek) != 0) {
            unset($deger["$silinecek"]);
        }
        $ihsan = arrayislemi($deger);
        $bolunmus = explode(",", $ihsan);
        $baglanti = veritabanibaglantisi();
        foreach ($bolunmus as $ulkeker)
        {
            if($deger[$ulkeker]) {
                $sutunadlari.=$ulkeker.",";
                $icerikler.="'".$deger[$ulkeker]."',";
            }
        }
        $iceriklerW= rtrim($icerikler,",");
        $sutunadlariA= rtrim($sutunadlari,",");

//        var_dump("$sutunadlari");
        // var_dump("INSERT INTO $tablo  ($sutunadlariA) VALUES ($iceriklerW) ");
        $sorgu = $baglanti->prepare("INSERT INTO $tablo  ($sutunadlariA) VALUES ($iceriklerW) ");
        $ekle = $sorgu->execute();
        if ($ekle) {
            return 1;
        } else {
            return "HATA".$ekle->errorInfo();
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return  $e->getMessage();
    }
}

function hastaborcsorgula($hastaid)
{
    $baglanti = veritabanibaglantisi();
    $sql = "select * from patient_prompts where (protocol_number='$hastaid') and status='1' and payment_completed='0'";
    $sorgu = $baglanti->query($sql);
    while ($rowa = $sorgu->fetch(pdo::FETCH_ASSOC)) {
        $adet = $rowa['piece'];
        $ucret = $rowa['fee'] + $rowa['service_fee'];
        $toplam = $adet * $ucret;
        $nettoplam = $nettoplam + $toplam;
    }
    return $nettoplam;
}



function miktargetir($tablo,$sutun,$deger,$sutunmiktar,$islem_durum)
{
    $miktararti=0;
    $miktareksi=0;
    $baglanti = veritabanibaglantisi();

    $uyrukgetir = "select * from $tablo where $sutun=$deger ";
    $sorgu = $baglanti->query($uyrukgetir);
    while ($rowa = $sorgu->fetch(pdo::FETCH_ASSOC)) {

        if ($rowa["$islem_durum"]==1){
            $miktararti+= $rowa["$sutunmiktar"];
        }elseif($rowa["$islem_durum"]==2){
            $miktareksi+= $rowa["$sutunmiktar"];
        }


    }

    $sonucla=$miktararti-$miktareksi;
    return $sonucla;

}

function protokoliletanilarigetir($protokolno)
{
    $baglanti = veritabanibaglantisi();
    $tumtanilar = "";
    $uyrukgetir = "select * from hasta_kayit_tanilar_ontanilar where protokol_no='$protokolno' and status='1'";
    $sorgu = $baglanti->query($uyrukgetir);
    while ($rowa = $sorgu->fetch(pdo::FETCH_ASSOC)) {
        $tani_adi = $rowa['tani_kodu'];
        $tumtanilar = $tani_adi.",".$tumtanilar;

    }

    return $tumtanilar;

}
function yeniarrayislemi($deger)
{
    $sonuc = implode(",", array_map(function ($deg) {
        return $deg . "=?";
    }, array_keys($deger)));

    return $sonuc;
}

function direktekle($tablo, $deger, $silinecek = "", $silinecekiki = "", $silinecekuc = "")
{
    try {
        if (strlen($silinecek) != 0) {
            unset($deger["$silinecek"]);
        }
        if (strlen($silinecekiki) != 0) {
            unset($deger["$silinecekiki"]);
        }

        if (strlen($silinecekuc) != 0) {
            unset($deger["$silinecekuc"]);
        }

        $deger['insert_datetime']  = date('Y-m-d H:i:s');
        $deger['insert_userid']    = $_SESSION["id"];

        $ihsan = arrayislemi($deger);
        $bolunmus = explode(",", $ihsan);
        $baglanti = veritabanibaglantisi();
        foreach ($bolunmus as $ulkeker)
        {
            if($deger["$ulkeker"]) {
                $sutunadlari.=$ulkeker.",";
                $icerikler.="'".$deger[$ulkeker]."',";
            }
        }
        $iceriklerW= rtrim($icerikler,",");
        $sutunadlariA= rtrim($sutunadlari,",");
        
        $sorgu = $baglanti->prepare("INSERT INTO $tablo  ($sutunadlariA) VALUES ($iceriklerW) ");
//        var_dump("INSERT INTO $tablo  ($sutunadlariA) VALUES ($iceriklerW) ");
        $ekle = $sorgu->execute();
        if ($ekle) { 
            return 1;
        } else {
            return "HATA".$ekle->errorInfo();
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return  $e->getMessage();
    }
}

function sql_insert($tablo, $deger, $silinecek = "", $silinecekiki = "", $silinecekuc = "")
{
    try {
        if (strlen($silinecek) != 0) {
            unset($deger["$silinecek"]);
        }
        if (strlen($silinecekiki) != 0) {
            unset($deger["$silinecekiki"]);
        }

        if (strlen($silinecekuc) != 0) {
            unset($deger["$silinecekuc"]);
        }

        $deger['insert_datetime']  = date('Y-m-d H:i:s');
        $deger['insert_userid']    = $_SESSION["id"];

        $ihsan = arrayislemi($deger);
        $bolunmus = explode(",", $ihsan);
        $baglanti = veritabanibaglantisi();
        foreach ($bolunmus as $ulkeker)
        {
            if($deger["$ulkeker"]) {
                $sutunadlari.=$ulkeker.",";
                $icerikler.="'".$deger[$ulkeker]."',";
            }
        }
        $iceriklerW= rtrim($icerikler,",");
        $sutunadlariA= rtrim($sutunadlari,",");

        $sorgu = $baglanti->prepare("INSERT INTO $tablo  ($sutunadlariA) VALUES ($iceriklerW) ");
//        var_dump("INSERT INTO $tablo  ($sutunadlariA) VALUES ($iceriklerW) ");
        $ekle = $sorgu->execute();
        if ($ekle) {
            return 1;
        } else {
            return 0;
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return  $e->getMessage();
    }
}


function guncelle($sqlifade){
    $baglanti = veritabanibaglantisi();

    try {
        $stmt = $baglanti->prepare($sqlifade);
        $result=$stmt->execute();
        if($result)
        {
            return 1;
        }
        else{
            return $result->errorInfo();
        }

    } catch (Exception $e) {

        return  $e->getMessage();

    }


}

function odayatakdolumu($tablo,$id,$durum){
    $baglanti = veritabanibaglantisi();

    $gungun=guncelle("update $tablo set doluluk='$durum'  where id='$id'");
    if($gungun){

        echo 'true';
    }else{
        echo 'false';
    }
}

function direktguncelle($tablo, $sutun, $id, $deger, $silinecek1 = "", $silinecek2 = "", $ekkosul = "")
{
    $baglanti = veritabanibaglantisi();

    unset($deger["$silinecek1"]);
    unset($deger["$silinecek2"]);

    $deger['update_datetime']  = date('Y-m-d H:i:s');
    $deger['update_userid']    = $_SESSION["id"];

    $ihsan = arrayislemi($deger);
    $bolunmus = explode(",", $ihsan);

    $hepsi="";
    $ihsasn = sonbukucu($deger);
    foreach ($bolunmus as $ulkeker)
    {
        if($deger["$ulkeker"]) {
            $hepsi.= $ulkeker."='".$_POST[$ulkeker]."',";
        }

    }
    $hepsi= rtrim($hepsi,",");
    unset($deger["$silinecek1"]);
    unset($deger["$silinecek2"]);
    $sorgu = $baglanti->prepare("UPDATE $tablo SET ".yeniarrayislemi($deger)." where $ekkosul $sutun='" . $id . "'");
    $ekle = $sorgu->execute(array_values($deger));
    if ($ekle) {
        return 1;
    } else {
        return "HATA".$ekle->errorInfo();
    }
}


function direktgroupguncelle($tablo, $sutun, $id, $deger, $grupdegeri, $ekkosul = "")
{


    $baglanti = veritabanibaglantisi();
    try {

        $deger['update_userid'] = $_SESSION['id'];
        $deger['update_datetime'] = date('Y-m-d H:i:s');

        $ihsan = arrayislemi($deger);
        $bolunmus = explode(",", $ihsan);
        $baglanti = veritabanibaglantisi();
        foreach ($bolunmus as $ulkeker)
        {
            if($deger[$ulkeker]) {
                $sutunadlari.=$ulkeker.",";
                $icerikler.="'".$deger[$ulkeker]."',";
            }
        }
        $iceriklerW= rtrim($icerikler,",");
        $sutunadlariA= rtrim($sutunadlari,",");

//        var_dump("$sutunadlari");
        $sorgu = $baglanti->prepare("UPDATE $tablo SET ".yeniarrayislemi($deger)." where $ekkosul $sutun='" . $id . "'");
//        var_dump("UPDATE $tablo SET ".yeniarrayislemi($deger)." WHERE $ekkosul $sutun='" . $id . "'");
        $ekle = $sorgu->execute(array_values($deger));
        if ($ekle) {
            return $ekle;
        } else {
            return "HATA".$ekle->errorInfo();
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return  $e->getMessage();
    }
}

function tekil($tablo, $sutun, $deger, $cekilecek = "*")
{
    $baglanti = veritabanibaglantisi();

    try {
        $sorgu = $baglanti->prepare("SELECT $cekilecek FROM $tablo where $sutun=?  and status='1'");
        $sorgu->execute([$deger]);
        $say = $sorgu->rowcount();
        //print_r($sorgu->errorInfo());
        //print_r($sorgu->debugDumpParams());
        if ($say == 0) {
            throw new Exception(implode($sorgu->errorInfo()), 1);
            //return ["sonuc"=> FALSE, "hata" => $sorgu->errorInfo()];
        } else {
            return $sorgu->fetch(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return FALSE;
    }

}

function singular($tablo, $sutun, $deger, $cekilecek = "*")
{
    $baglanti = veritabanibaglantisi();

    try {
        $sorgu = $baglanti->prepare("SELECT $cekilecek FROM $tablo WHERE $sutun=? ");
        $sorgu->execute([$deger]);
        $say = $sorgu->rowcount();
        //print_r($sorgu->errorInfo());
        //print_r($sorgu->debugDumpParams());
        if ($say == 0) {
            throw new Exception(implode($sorgu->errorInfo()), 1);
            //return ["sonuc"=> FALSE, "hata" => $sorgu->errorInfo()];
        } else {
            return $sorgu->fetch(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return FALSE;
    }


}
function singularactive($tablo, $sutun, $deger, $cekilecek = "*")
{
    $baglanti = veritabanibaglantisi();

    try {
        $sorgu = $baglanti->prepare("SELECT $cekilecek FROM $tablo WHERE $sutun=? and status=1");
        $sorgu->execute([$deger]);
        $say = $sorgu->rowcount();
        //print_r($sorgu->errorInfo());
        //print_r($sorgu->debugDumpParams());
        if ($say == 0) {
            throw new Exception(implode($sorgu->errorInfo()), 1);
//            return ["sonuc"=> FALSE, "hata" => $sorgu->errorInfo()];
        } else {
            return $sorgu->fetch(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return FALSE;
    }


}
function poliklinikpaketsorgula($poliklinik_id, $sutunadi, $hasta_tc_kimlik,$detaysutunadi)
{
    //kontrol edilecek veya silinecek //
    $hastabilgilerinigetir=tek("select * from hastalar where tc_kimlik='$hasta_tc_kimlik'");


    $birimdetaylari=tek("select * from birimler where id='$poliklinik_id'");
    $veriid=$birimdetaylari[$sutunadi];
    $islemdetaylari=tek("select * from islem_detaylari where id='$veriid'");
    $kurumicinislemdetaylari=tek("select * from islem_detaylari_ucretleri where islem_detay_id='$veriid' and kurum_id='{$hastabilgilerinigetir["kurum"]}'and alt_kurum='{$hastabilgilerinigetir["sosyal_guvence"]}'");

    $ucret=$birimdetaylari[$detaysutunadi];
    return $ucret;

    //kontrol edilecek veya silinecek //
}

function kurumagoreistemucreti($kurum,$altkurum,$istemid,$tip=''){
    $islembilgisi=tek("select * from transaction_detail where id='$istemid' and status='1'");
    $islemdetaybilgisi=tek("select * from transaction_details_costs where process_detail_id='$istemid' and (institution_id='$kurum' and sub_institution='$altkurum') and status='1'");
    if($tip=="yatan"){
        $ucret=$islemdetaybilgisi["lying_urgent_cost"]+$islemdetaybilgisi["diffrence_standing_cost"];

    }else{

        $ucret=$islemdetaybilgisi["standing_cost"]+$islemdetaybilgisi["diffrence_standing_cost"];
    }
    if($ucret==""){
        $ucret=$islembilgisi["transaction_cost"];
    }
    return $ucret."-".$islemdetaybilgisi["special_service_cost"];
}
function islemtanimgetir($deger,$eksql )
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from transaction_definitions where id='$deger' $eksql  and status='1'  ");
    $cikti = $sorgu->fetch(pdo::FETCH_ASSOC);

    return $cikti["definition_name"];

}

function labtanimgetir($deger,$eksql )
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from lab_definitions where id='$deger' $eksql  and status='1'  ");
    $cikti = $sorgu->fetch(pdo::FETCH_ASSOC);

    return $cikti["definition_name"];

}

function binakatoda($tablo,$deger,$eksql)
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from $tablo where id='$deger' $eksql  and status='1'");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);

    return $row;

}

function tablogetir($tablo,$deger,$eksql)
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from $tablo where id='$deger' $eksql  and status='1'");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);

    return $row;

}

function islemdetaygetir($deger,$eksql )
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from islem_detaylari where id='$deger' $eksql and status='1' ");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["islem_adi"];

}

function birimgetir($deger,$eksql )
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from units where id='$deger' $eksql   and status='1' ");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["department_name"];

}

function birimyetkisisorgula($kullanici_id,$tip="")
{
    $yetkiidsi=singular("users","id",$kullanici_id);
    $kullanici_yetki_id=$yetkiidsi["authority_id"];
    if($kullanici_yetki_id==1 && $tip=="") {
        return " where patient_registration.id is not null ";
    }else{
        if($tip==""){
            $yetkiler=" where  patient_registration.id is not null  and( ";
            $i=0;
            $onkayitverilericek=verilericoklucek("select users_outhorized_units.unit_id from users,users_outhorized_units where users.id='$kullanici_id' and users_outhorized_units.userid='$kullanici_id' and users_outhorized_units.status='1'");
//            var_dump($tip);
            foreach ($onkayitverilericek as $rowa) {
                $birimid=$rowa["unit_id"];
                if($i!=0){ $yetkiler.=' or ';}
                $yetkiler.="  patient_registration.outpatient_id='$birimid'  ";
                $i++;
            }
            $soncikanlar=$yetkiler." and doctor='$kullanici_id' )";
            return $soncikanlar;
        }else{
            if($kullanici_yetki_id==1 ) {
                return "   where  id is not null";
            }else{
                $yetkiler=" where id is not null  and( ";
                $ece=0;
                $onkayitverileraaicek=verilericoklucek("select users_outhorized_units.unit_id from users,users_outhorized_units where users.id='$kullanici_id' and users_outhorized_units.userid='$kullanici_id' and users_outhorized_units.status='1'");

                foreach ($onkayitverileraaicek as $rowaaa) {
                    $birimid=$rowaaa["unit_id"];
                    if($ece!=0){ $yetkiler.=' or ';}
                    $yetkiler.="  unit_code='$birimid'  ";
                    $ece++;
                }
                $soncikanlar=$yetkiler." and doctor_id='$kullanici_id' )";
                return $soncikanlar;
            }
        }
    }
}

function birimyetkiselect($kullanici_id)
{
    $yetkiidsi=singular("users","id",$kullanici_id);
    $kullanici_yetki_id=$yetkiidsi["authority_id"];
//    var_dump($kullanici_id);
    $baglanti = veritabanibaglantisi();
    if($kullanici_yetki_id==1) {
        return " ";
    }else{
//        $yetkivarmi  = tek("select users_outhorize_units.unit_id from users,users_outhorize_units where users.id='$kullanici_id' and users_outhorize_units.userid='$kullanici_id'");
        $yetkivarmi  = verilericoklucek("select users_outhorized_units.unit_id from users,users_outhorized_units where users.id='$kullanici_id' and users_outhorized_units.userid='$kullanici_id' and users_outhorized_units.status='1'");


        $yetkiler=" and (";
        $i=0;
        foreach ($yetkivarmi as $rowa){
            $birimid=$rowa["unit_id"];
            if($i!=0){ $yetkiler.=' or ';}
            $yetkiler.="  id='$birimid'  ";
            $i++;
        }
        $soncikanlar=$yetkiler." and status='1' )";
        return $soncikanlar;

    }

}


function polhastasayisigetir($tablo, $sutun, $deger)
{
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->prepare("select count(*)  from $tablo where $sutun=$deger and status=1 ");
    $sorgu->execute();
    return $say = $sorgu->fetchColumn();

}

function drhastasayisigetir($tablo, $sutun, $deger,$eksutun,$ekdeger)
{
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->prepare("select count(*) c from $tablo where $sutun='$deger' and $eksutun >= '$ekdeger'");
    $sorgu->execute();
    return $say = $sorgu->fetchColumn();
}

function tckimlik_dogrulama($tckimlik){
    $olmaz=array('11111111110','22222222220','33333333330','44444444440','55555555550','66666666660','7777777770','88888888880','99999999990');
    if($tckimlik[0]==0 or !ctype_digit($tckimlik) or strlen($tckimlik)!=11){ return false;  }
    else{
        for($a=0;$a<9;$a=$a+2){ $ilkt=$ilkt+$tckimlik[$a]; }
        for($a=1;$a<9;$a=$a+2){ $sont=$sont+$tckimlik[$a]; }
        for($a=0;$a<10;$a=$a+1){ $tumt=$tumt+$tckimlik[$a]; }
        if(($ilkt*7-$sont)%10!=$tckimlik[9] or $tumt%10!=$tckimlik[10]){ return false; }
        else{
            foreach($olmaz as $olurmu){ if($tckimlik==$olurmu){ return false; } }
            return true;
        }
    }
}

function yetkisorgula($kullanici_id,$moduladi){
    date_default_timezone_set('Europe/Istanbul');
    $gecerliliksuresi=date('y-m-d');
    $baglanti = veritabanibaglantisi();
    $yetkiidsi=singular("users","id",$kullanici_id);
    $kullanici_yetki_id=$yetkiidsi["authority_id"];
    if($kullanici_yetki_id==1) {
        return 1;
    }else{
        $yetkivarmi= tek("select authority_group.group_name as yetkigrupadi,
                                       authority_definition.id,
                                       authority_of_pool.authority_id,
                                       authority_of_pool.userid,
                                       authority_definition.authority,
                                       authority_of_pool.period_of_validity
                                from authority_of_pool,
                                     authority_definition,
                                     authority_group
                                where authority_definition.id = authority_of_pool.authority_id
                                    and authority_of_pool.userid = $kullanici_id and authority_definition.authority = '$moduladi'
                                   or authority_of_pool.period_of_validity > '$gecerliliksuresi%'
                                order by yetkigrupadi fetch first 1 rows only");
//  var_dump($yetkivarmi);
        if($yetkivarmi["yetkigrupadi"]!=""){
            return true;
        }
    }
}


function sql_authority($kullanici_id,$moduladi){
    date_default_timezone_set('Europe/Istanbul');
    $gecerliliksuresi=date('y-m-d');
    $baglanti = veritabanibaglantisi();
    $yetkiidsi=singular("users","id",$kullanici_id);
    $kullanici_yetki_id=$yetkiidsi["personnel_type"];
    if($kullanici_yetki_id==1) {
        return 1;
    }else{
               $yetkivarmi= tek("select authority_group.group_name as yetkigrupadi,
                      authority_definition.id,
                      authority_of_pool.authority_id,
                      authority_of_pool.userid,
                      authority_definition.authority,
                      authority_of_pool.period_of_validity
               from authority_of_pool,
                    authority_definition,
                    authority_group
               where authority_definition.id = authority_of_pool.authority_id
                 and authority_of_pool.userid = $kullanici_id
                 and authority_definition.authority = '$moduladi'
                 and authority_of_pool.status = 1
                  or authority_of_pool.period_of_validity
                   > '$gecerliliksuresi%'
               order by yetkigrupadi
               fetch first 1 rows only");

        if($yetkivarmi["id"] > 0 ){
            return 1;
        }
    }
}

function go($yonlendirilecekurl){ ?>
    <script language="javascript">
        window.location="<?php echo $yonlendirilecekurl; ?>";
    </script>
    <? }

//bunu silebilir başlangıç
function bebeksorgula($annetc,$dogum_sirasi,$dogum_tipi){
    $dogumsorgula=tek("select * from hastalar where anne_tc_kimlik_numarasi='$annetc' and dogum_sirasi='$dogum_sirasi' and dogum_tipi='$dogum_tipi'");
    if($dogumsorgula){
        return 1;
    }else{
        return 0;
    }
}

//bunu silebilir bitiş


function tek($sorgu)
{
    $baglanti = veritabanibaglantisi();

    try {
        $sorgu = $baglanti->prepare($sorgu);
        $sorgu->execute();
        $say = $sorgu->rowcount();

        //pre($sorgu->debugDumpParams());
        if ($say == 0) {
            throw new Exception(implode($sorgu->errorInfo()), 1);
            //return ["sonuc"=> FALSE, "hata" => $sorgu->errorInfo()];
        } else {
            return $sorgu->fetch(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        $_SESSION['hata'] = $e->getMessage();
        return FALSE;
    }
}
function kayitsayisigetir($tablo, $sutun, $deger,$eksorgu)
{
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->prepare("SELECT COUNT(*) FROM $tablo where $sutun='$deger' $eksorgu");
    $sorgu->execute();
    $ihsan=   $sorgu->fetchColumn();
    return $ihsan;
}

function butontanimla($butonadi,$butontipi){
    if($butontipi==1){
        return '<button type="button" class="btn btn-success btn-sm p-1">
                                               <i class="fa-solid fa-check"></i>'.$butonadi.'</button>';
    }else{
        return '<button type="button" class="btn btn-danger btn-sm p-1">
                                                <i class="fa fa-ban"></i>'.$butonadi.'</button>';
    }
}
function islemtanimsoneklenen($tablo,$sutun, $deger)
{
    $baglanti = veritabanibaglantisi();
    $row =tek("select  MAX(id) as id  from $tablo ");
//    $row = $sorgu->fetch(PDO::FETCH_ASSOC); 
    return $row["id"];

}
function guvelikdegistir ($par)
{
    return str_replace(
        array("'", "\""),
        array("'", ""),
        $par
    );
}

function verilericoklucek($sql){
    $sql=guvelikdegistir($sql);
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->query($sql);
    $url_array = array();
    while ($cikti = $sorgu->fetch(PDO::FETCH_ASSOC)) {
        $url_array[] = $cikti;

    }
    if($url_array){

        return $url_array;
    }else{
        echo false;
    }
}

function sql_select($sql){
    $sql=guvelikdegistir($sql);
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->query($sql);
    $url_array = array();
    while ($cikti = $sorgu->fetch(PDO::FETCH_ASSOC)) {
        $url_array[] = $cikti;

    }
    if($url_array){

        return $url_array;
    }else{
        echo false;
    }
}

function multi_select($sql){
    $sql=guvelikdegistir($sql);
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->query($sql);
    $url_array = array();
    while ($cikti = $sorgu->fetch(PDO::FETCH_ASSOC)) {
        $url_array[] = $cikti;
    }
    if($url_array){

        return $url_array;
    }else{
        echo false;
    }
}


function karaktertemizle($text) {
    $find = array('ç', 'ş', 'ğ', 'ü', 'i̇', 'ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#',' ');
    $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp','');
    $text = strtolower(str_replace($find, $replace, $text));
    $text = preg_replace("@[^a-za-z0-9\-_\.\+]@i", ' ', $text);
    $text = trim(preg_replace('/\s+/', ' ', $text));
    $text = str_replace(' ', '-', $text);

    return $text;
}

function ikitariharasindakiyilfark($tarih1, $tarih2 ) {

    date_default_timezone_set('Europe/Istanbul');
    $tarih1 = new datetime($tarih1);
    $tarih2 = $tarih1->diff(new datetime($tarih2));

    return $tarih2->y;
}
function ikitariharasindakigunfark($tarih1, $tarih2 ) {

    date_default_timezone_set('Europe/Istanbul');
    $tarih1 = new datetime($tarih1);
    $tarih2 = $tarih1->diff(new datetime($tarih2));

    return $tarih2->days;
}
function ikitariharasindakiayfark($tarih1, $tarih2 ) {

    date_default_timezone_set('Europe/Istanbul');
    $tarih1 = new datetime($tarih1);
    $tarih2 = $tarih1->diff(new datetime($tarih2));

    return $tarih2->m;
}

function uyari($uyarininicerigi,$uyaridurumu){
    if($uyaridurumu==1){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="mdi mdi-check-all me-2"></i>
                                           '.$uyarininicerigi.'
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                                        </div>';
        unset($_POST);
    }else{
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="mdi mdi-block-helper me-2"></i>
                                                 '.$uyarininicerigi.'
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                                        </div>';
    }
}


function sistem_durum_sorgula(){
    $baglanti = veritabanibaglantisi();

    $sistemayarlari=tek("select * from system_settings where id='1'");
    define("sistem_para_birimi",$sistemayarlari["currency"]);
    define("oturum_sonlandirma_suresi",$sistemayarlari["sign_out"]);
    define("lab_grup_id",$sistemayarlari["lab_group_id"]);
    define("rad_grup_id",$sistemayarlari["rad_group_id"]);
    define("dolar_fiyat",$sistemayarlari["dollar_price"]);
    define("medula_kullanici_adi",$sistemayarlari["medula_user_name"]);
    define("medula_kullanici_sifresi",$sistemayarlari["medula_user_password"]);
    define("medula_saglik_tesis_kodu",$sistemayarlari["medula_health_facility_id"]);
    define("euro_fiyat",$sistemayarlari["euro_price"]);
    define("hastane_adi",$sistemayarlari["hospital_name"]);
    define("kontrol_gecerlilik_suresi",$sistemayarlari["control_validity"]); //hasta muayene geldikten sonra kaç gün içinde tekrar gelirse kontrol olarak geçecek sisteme
    if($sistemayarlari["system_status"]!=1){
        go("bakimda");

    }
}

function parabirimi(){
    $baglanti = veritabanibaglantisi();
    $sistemayarlari=tek("select * from sistem_ayarlari where id='1'");
    echo " ".$sistemayarlari["para_birimi"];
}

function oturumkaydikontrol() {
    $sorgu=tek("select * from users where tc_id='{$_SESSION['tc_id']}' and user_password='{$_SESSION['user_password']}'");
    $kullanici_yetki_id=$sorgu["authority_id"];
    if($sorgu['tc_id']==""){
//    var_dump($_SESSION);
        go("giris.php");
    }else{
        $oturumsorgu=tek("select * from users_session_logs where session_hash='{$_SESSION["session_hash"]}' and userid='{$_SESSION['id']}'");
        if($oturumsorgu["desistence_datetime"]!="")
        {
            unset($_SESSION);
            go("giris.php");
        }else{
            $oturum_tarihi = date('y-m-d h:i:s');
            $yenitarih = date('y-m-d h:i:s',strtotime('+'.oturum_sonlandirma_suresi.' minutes',strtotime($oturumsorgu["session_datetime"])));
            if($yenitarih<$oturum_tarihi){
                guncelle("update  users_session_logs set  desistence_datetime='$oturum_tarihi' where session_hash='{$_SESSION["session_hash"]}' and desistence_datetime is null");
                unset($_SESSION);
                go("giris.php");
            }else{
                guncelle("update  users_session_logs set  session_datetime='$oturum_tarihi' where session_hash='{$_SESSION["session_hash"]}' and desistence_datetime is null");
            }}
    }
    tek("update  users_session_logs set  desistence_datetime='$yenitarih'  where desistence_datetime is null and session_datetime>'$yenitarih'");

}

function oturumsayisisorgula($userid){
    session_start();
    $oturumsorgu=tek("select * from users where id='$userid' and status='1'");
    $ayni_anda_oturum_sayisi=$oturumsorgu["session_num"];
    $aktifoturumsayisi= kayitsayisigetir("users_session_logs", "userid", $userid," and desistence_datetime is null and is_alert='0' ");
    if($aktifoturumsayisi>$ayni_anda_oturum_sayisi){

        $uyariyapildimi=tek("select * from users_session_logs where userid='$userid' and desistence_datetime is null  order by id asc");
        if($uyariyapildimi["is_alert"]=="0"){
            if($_SESSION["session_hash"]==$uyariyapildimi["session_hash"]){
                ?>
                <script>
                    alertify.alert("dikkat!!","size izin verilen birden fazla bilgisayarda aynı anda oturum açma sayısını açtığınızdan dolayı bu oturumunuzu sonlandırıyorum!");
                </script>
                <?php
                $oturum_tarihi = date('y-m-d h:i:s');
                tek("update  users_session_logs set  desistence_datetime='$oturum_tarihi',is_alert='1'  where desistence_datetime is null and is_alert='0' and id='{$uyariyapildimi["id"]}'");
            }
        }
    }
}

function kesinsil($tablo, $sutun, $id, $eksorgu = "")
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("delete from $tablo where $eksorgu $sutun='$id'");
    $sil = $sorgu->fetch(pdo::FETCH_ASSOC);
    if ($sil)
    {
        return true;
    }
    else
    {
         return "HATA".$sorgu->errorInfo();
    }
}

function kullanicigetir($deger,$eksql )
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from users where id='$deger' $eksql   and status='1' ");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["name_surname"];
    oci_free_statement($result);
    oci_close($baglanti);

}

function rasgelesifreolustur($deger)
{
    $harf = 'abcdefghijklmnoprstuvyz';
    $harf_sayisi = mb_strlen($harf);
    for ($i = 0; $i < 10; $i++){
        $secilen_harf_konumu = mt_rand(0,$harf_sayisi - 1);
        $kod .= mb_substr($harf, $secilen_harf_konumu, 1).rand(0,9);
    }
    $sonuc=mb_substr($kod, 0, $deger); //j6z1b2

    return $sonuc;
}
function randomNumber($length)
{
    $output = '';
    for($i = 0; $i < $length; $i++) {
        $output .= mt_rand(0, 9);
    }

    return $output;
}

function kasagetirid($deger)
{
    $baglanti = veritabanibaglantisi();
    $sorgu = $baglanti->query("select * from kasa where id='$deger' ");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row["kasaadi"];
    oci_free_statement($result);
    oci_close($baglanti);

}

function kasadeatygetirid($deger)
{
    $baglanti = veritabanibaglantisi();

    $sorgu = $baglanti->query("select * from kasa_detay where id='$deger' ");
    $row = $sorgu->fetch(pdo::FETCH_ASSOC);
    return $row;
    oci_free_statement($result);
    oci_close($baglanti);
}

define("sistem_url",$_SERVER['DOCUMENT_ROOT']);

date_default_timezone_set('Europe/Istanbul');
//error_reporting(0);

sistem_durum_sorgula();

$simdikitarih = date('d/m/Y H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$simdiyil = substr($simditarih, -4);
$gunceltarih=date('Y-m-d H:i:s');
$gunceldate=date('Y-m-d');
$guncelsaat=date('H:i:s');
$nettarih = date('Y-m-d');
$netsaat = date('H:i:s');
session_start();
$dil	=$_GET["dil"];
if($_SESSION["dil"]==""){
    $_SESSION["dil"] = "tr";
//    go("index.php");
}
if($dil!=""){
    $_SESSION["dil"] = $dil;
    go("index.php");
}

if ($_SESSION["dil"]==""){
    require(sistem_url."/diller/tr.php");
}else {
    require(sistem_url."/diller/".$_SESSION["dil"].".php");
}
?>


