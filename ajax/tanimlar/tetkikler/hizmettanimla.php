<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];
?>



    <div class="row">
        <div class="col-5">
            <div class="card" style=" height: 100%; ">
                <div class="card-header px-2 text-white fw-bold">
                    <div class="row">
                        <div class="col-8">Hizmet Grupları</div>
                    </div>
                </div>
                <div class="card-body tetkik-grup-liste">

                </div>
            </div>
        </div>

        <div class="col-7">
            <div class="card" id='tetkik-detay-list'>
                <div class="warning-definitions mt-5">
                    <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                        <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                        <p>İşlem Yapmak İçin Seçim yapınız</p>
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>


    </div>

<div class="modal fade" id="tetkik-grup-modal" aria-hidden="true" style="margin-top: 90px;">
    <div class="modal-dialog" id='modal-tetkik-grup-icerik'></div>
</div>

<div class="modal fade" id="tetkik-detay-modal" aria-hidden="true" style="margin-top: 90px;">
    <div class="modal-dialog" id="modal-tetkik-detay-icerik" style=" width: 50%; max-width: 50%; ">></div>
</div>

<script>
    $('.tetkik-grup-liste').load('ajax/tanimlar/tetkikler/tetkik-liste.php?islem=tetkik-grup-liste');
</script>






