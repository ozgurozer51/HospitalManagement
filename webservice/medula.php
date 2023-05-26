<?php

// Medula web servisine bağlanmak için gerekli olan bilgiler
$username = "99999999990";
$password = "99999999990";
$wsdl = "https://medula.sgk.gov.tr/medula/hastane/hastaKabulIslemleriWS?wsdl";

// Bağlantı oluşturma
$client = new SoapClient($wsdl, array('trace' => 1, 'exceptions' => 0));

// Hasta kabulü bilgilerini tanımlama
$hastaKabulBilgisi = array(
    "hasta" => array(
        "tcKimlikNo" => "99999999990",
        "ad" => "Ahmet",
        "soyad" => "Yılmaz",
        "dogumTarihi" => "01.01.1980",
        "cinsiyet" => "E",
        "medeniHal" => "E",
        "babaAd" => "Mehmet",
        "anaAd" => "Fatma",
        "adres" => "İstanbul",
        "il" => "İstanbul",
        "ilce" => "Kadıköy",
        "postaKodu" => "34700",
        "evTelefonu" => "02161234567",
        "cepTelefonu" => "05001234567",
        "email" => "ahmet.yilmaz@example.com",
        "faturaTuru" => "0",
        "saglikTesisKodu" => "123456",
        "protokolNo" => "123456",
        "aciklama" => "Hasta kabulü oluşturuldu"
    ),
    "hastaKabul" => array(
        "tarih" => "2023-03-15",
        "saat" => "10:00",
        "poliklinikKodu" => "101",
        "hekimKodu" => "123456",
        "kurumKodu" => "123456",
        "hastaGelisTuru" => "1",
        "durum" => "1"
    )
);

// Hasta kabulü oluşturma
$result = $client->__soapCall("HastaKabul", array($hastaKabulBilgisi), array("username" => $username, "password" => $password));

// Sonucu görüntüleme
print_r($result);

?>
