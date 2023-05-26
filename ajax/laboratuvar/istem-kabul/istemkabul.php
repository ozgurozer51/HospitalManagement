<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];
if($islem=="listeyi-getir"){ ?>

<div class="col-12 row ">
    <div class="col-5">
        <div id="tablo_istem_kabul_hasta_liste"></div>

        <script>
            $.get( "/ajax/laboratuvar/istem-kabul/tablo-istemkabul.php?islem=tb_istem_kabul_hasta", function(getveri){
                $('#tablo_istem_kabul_hasta_liste').html(getveri);
            });
        </script>


    </div>

    <div class="col-7">
        <div class="row">
            <div class="row mt-2">
                <div class="col-8"></div>
                <div class="col-4" align="right">
                    <button  class="btn btn-success btn_istem_randevu" data-bs-toggle="modal"></i> Randevu</button>
                    <button class="btn btn-success text-white"><i class="fa-regular fa-vials"></i> Numune Alım</button>
                </div>

                <script>
                    $("body").off("click", ".btn_istem_randevu").on("click", ".btn_istem_randevu", function(e){
                        var id = $(this).attr('id');


                        $('.tanimlamalar_w95_h75').window('setTitle', 'Randevu');
                        $('.tanimlamalar_w95_h75').window('open');
                        $('.tanimlamalar_w95_h75').window('refresh', 'ajax/laboratuvar/istem-kabul/modal-istemkabul.php?islem=modal_istem_kabul_randevu&id='+id+'');
                    });
                </script>
            </div>



            <div id="tablo_istem_kabul_hasta_tup">
                <div class="warning-definitions mt-5">
                    <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                        <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                        <p>Hastanın İstem Bilgilerini Görmek İçin Seçim yapınız</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div id="tablo_istem_kabul_tup_tetkik"></div>
        </div>
    </div>
</div>
<?php } ?>


