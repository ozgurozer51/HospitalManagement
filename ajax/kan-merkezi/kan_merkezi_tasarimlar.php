<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$islem=$_GET["islem"];

//HASTA KAN ÇIKIŞ EKRANI
if ($islem=="hasta_kan_cikis_ekrani"){ ?>
    <div class="card p-2">
        <div class="row">
            <div class="col-xl-12">
                <div class="card border-0 bg-transparent">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card institutionOther p-1" style="height: 90vh; background: #dbe2ef;">
                                <div class="row">
                                    <div id="kantalepistemlerilistesi"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card institutionOther p-1" style="height: 90vh; background: #dbe2ef; ">
                                <div class="row">
                                    <div id="kantalepkarsilamalistesi">
                                        <div class="warning-definitions mt-5">
                                        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                            <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                                            <p>İşlem Yapmak İçin Seçim yapınız</p>
                                        </div>
                                        </div>
                                    </div>

                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }

//DONÖR İŞLEMLERİ EKRANI
else if($islem=="donor_islemleri_ekrani"){ ?>
<div class="card p-2">
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 bg-transparent">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card institutionOther p-3" style="height: 90vh; background: #dbe2ef; ">
                            <div id="bagisci_kabul_islemleri"></div>
                        </div>
                    </div>
                    <div class="col-md-4" id="kanMerkeziContent">
                        <div class="card institutionOther p-3" style="height: 90vh; background: #dbe2ef; ">
                            <div id="bagisci_rezerve_listesi"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

//STOK HAREKET İŞLEMLERİ EKRANI
else if($islem=="stok_hereket_ekrani"){ ?>
<div class="card p-2">
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 bg-transparent">
                <div class="row">
                    <div class="col-md-4" id="kanMerkeziContent">
                        <div class="card institutionOther p-3" style="height: 90vh; background: #dbe2ef; ">
                            <div class="card institutionOther p-2 fw-bold" style="background: #FFB3B3; width: 100%;">Kan Ekleme Form</div>
                            <div  id="stok_ekle_form"></div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card institutionOther p-3" style="height: 90vh; background: #dbe2ef; ">
                            <div class="row">
                                <div id="kan_stok_tablosu"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

//KURUMLARA KAN ÇIKIŞ EKRANI
else if($islem=="kurumlara_kan_cikis_ekrani"){?>
<div class="card p-2">
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 bg-transparent">
                <div class="row">
                        <div class="card institutionOther p-2" style="height: 90vh; background: #dbe2ef; ">
                            <div class="row" id="kan_cikis_kurum_tablo"></div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

//TANIMLAMALAR EKRANI
else if($islem=="tanimlamalar_ekrani"){?>
<div class="card p-2">
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 bg-transparent">
                <div class="row">
                    <div class="col-md-12" id="kanMerkeziContent">
                        <div class="card institutionOther p-2" style="height: 90vh; background: #DBE2EF; ">
                            <div class="col-xl-1"></div>
                            <div class="card p-3">
                                <div class="d-flex">
                                    <button class="btn  col-4  kan_turu_tablo tanimlamabutton" style=" background: #DBE2EF; ">
                                        Kan Türü
                                    </button>
                                    <button class="btn  col-4 ms-1 ret_nedenleri_tablo tanimlamabutton" style=" background: #DBE2EF; ">
                                        Ret Nedeni
                                    </button>
                                    <button class="btn  col-4 ms-1 dolap_raf_tablo tanimlamabutton" style=" background: #DBE2EF; ">
                                        Dolap/Raf
                                    </button>
                                </div>
                            </div>
                                <div class="d-flex row">
                                    <div class="card institutionOther mt-1 "  style=" background: #DBE2EF; ">
                                        <div id="tanimlamalar_tablosu">
                                            <div class="warning-definitions mt-5">
                                                <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                                    <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                                    <h5 class="text-warning">ÜST TARAFTAN SEÇİM YAPINIZ</h5>
                                                    <p>İşlem Yapmak İstediğiniz Tanımlama Butonlaırndan Birini Seçiniz</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

//KIZILAY KAN MERKEZİ EKRANI
else if($islem=="kizilay_kan_merkezi_ekrani"){?>
    <div class="card p-2">
        <div class="row">
            <div class="col-xl-12">
                <div class="card border-0 bg-transparent">
                    <div class="row">
                        <div class="card institutionOther p-3" style="height: 90vh; background: #dbe2ef; ">
                            <div class="warning-definitions mt-5">
                                <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                    <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                    <h6 class="text-warning">KIZILAY KAN MERKEZİ GELECEK</h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    $(".tanimlamabutton").click(function () {
        let tiklananSatir = $(this);
        if (!tiklananSatir.hasClass("aktif-satir")) {
            $('.tanimlamabutton').each(function () {
                $(this).removeClass('aktif-satir');
            });
            tiklananSatir.addClass('aktif-satir');
        } else {
            tiklananSatir.removeClass('aktif-satir');
        }
    });
    
    $(document).ready(function(){

        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", function(getveri){
            $('#kantalepistemlerilistesi').html(getveri);
        });

        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem", function(getveri){
            $('#bagisci_kabul_islemleri').html(getveri);
        });

        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_stok_eklem_islemi", function(getveri){
            $('#stok_ekle_form').html(getveri);
        });

        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_stok_tablo", function(getveri){
            $('#kan_stok_tablosu').html(getveri);
        });

        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_cikis_kurum_tablosu", function(getveri){
            $('#kan_cikis_kurum_tablo').html(getveri);
        });

        $(".kan_turu_tablo").click(function(){
            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_turu_tablosu", function(getveri){
                $('#tanimlamalar_tablosu').html(getveri);
            });
        });

        $(".ret_nedenleri_tablo").click(function(){
            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=ret_nedenleri_liste", function(getveri){
                $('#tanimlamalar_tablosu').html(getveri);
            });
        });

        $(".dolap_raf_tablo").click(function(){
            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=dolap_raf_tablosu", function(getveri){
                $('#tanimlamalar_tablosu').html(getveri);
            });
        });
    });
</script>



