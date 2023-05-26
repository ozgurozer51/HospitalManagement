<?php
include "../../controller/fonksiyonlar.php";
$islem= $_GET['islem'];

if($islem=="epikriz-ekle"){
    unset($_GET['islem']);
    $sql = direktekle("epicrisis",$_GET);

    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Epikriz Ekleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Epikriz Ekleme Başarısız');
        </script>
    <?php }

     exit();
}
if($islem=="epikriz-guncelle"){
    unset($_GET['islem']);
    $id = $_GET['id'];
    unset($_GET['id']);
    $sql = direktguncelle("epicrisis","id",$id,$_GET);

    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Epikriz Güncelleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Epikriz Güncelleme Başarısız');
        </script>
    <?php }

    exit();
}



session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$kullanicininidsi=$_SESSION["id"]; 
$poliklinikbilgisigetir = singular("patient_registration", "id", $_GET["deger"]);
$hastabilgisigetir = singular("patients", "id", $poliklinikbilgisigetir["patient_id"]);

$protokol_no =  $poliklinikbilgisigetir["protocol_number"];
?>

<!--<style>-->
<!--    .nav-tabs {-->
<!--        display: -webkit-box;-->
<!--        display: -ms-flexbox;-->
<!--        display: flex;-->
<!--        -ms-flex-flow: column nowrap;-->
<!--        flex-flow: column nowrap;-->
<!--        -webkit-box-align: start;-->
<!--        -ms-flex-align: start;-->
<!--        align-items: flex-start;-->
<!--        border: none;-->
<!--    }-->
<!---->
<!--    .nav-tabs>li {-->
<!--        -webkit-writing-mode: vertical-lr;-->
<!--        -ms-writing-mode: tb-lr;-->
<!--        writing-mode: vertical-lr;-->
<!--        -webkit-transform: rotate(360deg);-->
<!--        transform: rotate(360deg);-->
<!--        -webkit-transform-origin: center center;-->
<!--        transform-origin: center center;-->
<!--        list-style-type: none;-->
<!--        height: 7%;-->
<!--        text-align: center;-->
<!--    }-->
<!---->
<!--    .nav-tabs>li>a {-->
<!--        display: block;-->
<!--        padding: 0px;-->
<!--        background-color: #dbe2ef;-->
<!--        color: #000;-->
<!--        text-decoration: none;-->
<!--        font-weight: 500;-->
<!--        border: 1px solid #3f72af;-->
<!--        border-radius: 5px;-->
<!--        font-size: 18px;-->
<!--    }-->
<!--</style>-->

    <button class="btn btn-secondary w-100" id="hasta-detay-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#hasta-detay<?php echo $protokol_no; ?>" aria-expanded="false">Hasta Detay Gizle</button>

<script>
    $("body").off("click", "#hasta-detay-toggle").on("click", "#hasta-detay-toggle", function (e) {
        if($(this).hasClass('collapsed')){
            $(this).html("Hasta Detay Göster");
        }else {
            $(this).html("Hasta Detay Gizle");
        }
    });
</script>

<div class="collapse show" id="hasta-detay<?php echo $protokol_no; ?>">
            <div class="hastabilgishow" style="display: block;">
                    <div class="row">
                        <div class="col-xl-1 col-lg-1 col-xxl-1">
                            <div id="live_camera"><img id="sonfotograf" src="assets/img/dummy-user.jpeg" alt="dummy user" width="100%;"></div>
                            <span style="font-size: 12px;">   Pro No  :  <?php echo $poliklinikbilgisigetir["protocol_number"]; ?></span>
                            <input type="hidden" id="hasta-kabul-protokol" value="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>">
                        </div>

                        <div class="col-xl-6">

                            <div class="row mt-2 mb-2">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Adı Soyadı</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control form-control-sm" disabled value="<?php echo $hastabilgisigetir["patient_name"]; ?> <?php echo $hastabilgisigetir["patient_surname"]; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label" for="">TC No</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-sm" type="text" disabled value="<?php echo $hastabilgisigetir["tc_id"]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Kurum</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php $kurumgetir = tek("select * from transaction_definitions where definition_type='KURUMLAR' and definition_code='{$hastabilgisigetir["institution"]}'"); ?>
                                            <input class="form-control form-control-sm" type="text" disabled value="<?php echo $kurumgetir["definition_name"]; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label" for="">Takip No </label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-sm" type="text" disabled value="<?php echo $hastabilgisigetir["provision_number"]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label" for="">Anne Adı</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-sm" type="text" disabled value="<?php echo $hastabilgisigetir["mother"]; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Dos. Türü</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-sm" type="text" disabled value="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">

                                <div class="col-xl-6 col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label" for="">Baba Adı</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-sm" type="text" disabled value="<?php echo $hastabilgisigetir["father"]; ?>">
                                        </div>
                                    </div>
                                </div>

                                    <div class="col-xl-6 col-md-6">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label" for="">Cinsiyet</label>
                                            </div>
                                            <div class="col-md-8">
                                                <?php $cinsiyetgetir = tek("select * from transaction_definitions where definition_type='CINSIYET' and definition_supplement='{$hastabilgisigetir["gender"]}'"); ?>
                                                <input class="form-control form-control-sm" type="text" disabled value="<?php echo $cinsiyetgetir["definition_name"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                            </div>

                        </div>

                        <div class="col-xl-5 mt-3 h-100">
                                <div class="row">
                                    <?php $sql = singular("epicrisis","protocol_id",$protokol_no); ?>
                                    <div class="col-xl-6 mb-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label" for="complaint">Şikayet</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea style="height: 60px;" id="complaint<?php echo $protokol_no; ?>" rows="12"><?php echo $sql['complaint']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label" for="curriculum_vitae">Özgeçmiş</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea style="height: 60px;"  id="curriculum_vitae<?php echo $protokol_no; ?>" rows="12"><?php echo $sql['curriculum_vitae']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label" for="family_history">Soygeçmiş</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea style="height: 60px;"  id="family_history<?php echo $protokol_no; ?>" rows="12"><?php echo $sql['family_history']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label" for="story">Hikayesi</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea style="height: 60px;"  id="story<?php echo $protokol_no; ?>" rows="12"><?php echo $sql['story']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 d-flex align-items-center mb-2">
                                    </div>

                                    <div class="col-xl-6 d-flex justify-content-end mb-2">

                                        <?php if($sql){?>
                                            <button class="btn btn-sm btn-secondary" data-id="<?php echo $sql['id']; ?>" id="epkiriz-guncelle<?php echo $protokol_no; ?>"><i class="fa-solid fa-edit"></i> Güncelle</button>
                                        <?php }else{ ?>
                                            <button class="btn btn-sm btn-secondary" id="epkiriz-onay<?php echo $protokol_no; ?>"><i class="fa-solid fa-thumbs-up"></i> Onayla</button>
                                        <?php } ?>

                                    </div>

                                </div>
                        </div>

                    </div>
            </div>
</div>

 <script>
     $("body").off("click", "#epkiriz-onay<?php echo $protokol_no; ?>").on("click", "#epkiriz-onay<?php echo $protokol_no; ?>", function (e) {
         var complaint = $('#complaint<?php echo $protokol_no; ?>').val();
         var curriculum_vitae = $('#curriculum_vitae<?php echo $protokol_no; ?>').val();
         var family_history = $('#family_history<?php echo $protokol_no; ?>').val();
         var story = $('#story<?php echo $protokol_no; ?>').val();
         $.get("ajax/hastadetay/hastakabuldetay.php?islem=epikriz-ekle", {
             complaint: complaint,
             curriculum_vitae: curriculum_vitae,
             family_history: family_history,
             story: story,
             protocol_id:<?php echo $protokol_no; ?>}, function (getveri) {
             $('.sonucyaz').html(getveri);
         });
     });

     $("body").off("click", "#epkiriz-guncelle<?php echo $protokol_no; ?>").on("click", "#epkiriz-guncelle<?php echo $protokol_no; ?>", function (e) {
         var complaint = $('#complaint<?php echo $protokol_no; ?>').val();
         var curriculum_vitae = $('#curriculum_vitae<?php echo $protokol_no; ?>').val();
         var family_history = $('#family_history<?php echo $protokol_no; ?>').val();
         var story = $('#story<?php echo $protokol_no; ?>').val();
         var id = $(this).attr("data-id");
         $.get("ajax/hastadetay/hastakabuldetay.php?islem=epikriz-guncelle", {
             complaint: complaint,
             curriculum_vitae: curriculum_vitae,
             family_history: family_history,
             story: story,
             protocol_id:<?php echo $protokol_no; ?> ,
             id: id
         }, function (getveri) {
             $('.sonucyaz').html(getveri);
         });
     });
 </script>


<div class="yatis-icerik">
    <?php $islemtanimlari = singularactive("transaction_definitions", "definition_name",'LAB'); ?>

    <div class="card p-3 ">
        <ul class="nav nav-pills p-1 mb-3 row" id="pills-tab" role="tablist">
            <li class="nav-item p-1 col-xl-3" role="presentation">
                <button class="nav-link p-1 hasta-kabul-tab w-100 active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home<?php echo $protokol_no; ?>" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Geçmiş İşlemler</button>
            </li>

            <li class="nav-item p-1 col-xl-3" role="presentation">
                <button class="nav-link p-1 hasta-kabul-tab w-100 istemislem<?php echo $protokol_no; ?>" protokolno="<?php echo $protokol_no; ?>" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile<?php echo $protokol_no; ?>" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"> <i class="fas fa-code-pull-request"></i> Hizmetler</button>
            </li>

            <li class="nav-item p-1 col-xl-3" role="presentation">
                <button class="nav-link p-1 hasta-kabul-tab w-100 ilacsarfbtn" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact<?php echo $protokol_no; ?>" type="button" role="tab"
                        aria-controls="pills-contact" aria-selected="false">İlaç Sarf</button>
            </li>

            <li class="nav-item p-1 col-xl-3" role="presentation">
                <button class="nav-link p-1 hasta-kabul-tab w-100 orderislem<?php echo $protokol_no; ?>" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled<?php echo $protokol_no; ?>"
                        type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">E-Order</button>
            </li>
        </ul>

        <script>
            $("body").off("click", ".hasta-kabul-tab").on("click", ".hasta-kabul-tab", function (e) {
                $('.hasta-kabul-tab').removeClass("text-white");
                $('.hasta-kabul-tab').removeClass("up-btn");
                $(this).addClass("text-white");
                $(this).addClass("up-btn");
            });
        </script>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                geçmiş işlem Ekranı:Hastanın poliklinikte ilk ve önceki gelişine ait tanı/ön tanı, tetkik vb görüntüleme.
            </div>
            <div class="tab-pane fade" id="pills-profile<?php echo $protokol_no;?>" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                <div class="hizmetbtn">
                    <div class="row">
                        <div class="col-md-10">
                            <button type="button" protokolno="<?php echo $protokol_no;?>" class="btn kps-btn  btn-sm  labislem<?php echo $protokol_no;?>"><i class="fas fa-vial"></i> &nbsp;Laboratuvar</button>
                            <button type="button" ihsan="rad" protokolno="<?php echo $protokol_no;?>" class="btn up-btn btn-sm   rontgenislem<?php echo $protokol_no;?>"><i class="fas fa-radiation"></i> Röntgen</button>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                </div>

                <div id="hastaistemlericerik<?php echo $protokol_no;?>">

                </div>

            </div>

            <div class="tab-pane fade" id="pills-contact<?php echo $protokol_no;?>" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                <div class="ilacsarfbody">

                </div>
            </div>
            <div class="tab-pane fade" id="pills-disabled<?php echo $protokol_no;?>" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">

                <button type="button" data-bs-toggle="modal" data-bs-target="#yatis-modal-getir" protokolno="35" class="btn btn-primary   btn-sm tedavigiris "><i class="fas fa-file-signature"></i> Tedavi giriş</button>

                <div class="orderlistesibody<?php echo $protokol_no;?> mt-3">      </div>

                <script>
                    $(document).ready(function () {
                        $(".ilacsarfbtn").click(function () {
                            var protokolno="<?php echo $protokol_no ?>";
                            var hastalarid="<?php echo $poliklinikbilgisigetir['patient_id'] ?>";
                            $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hasta_ilacsarfi", {protokolno:protokolno,hastalarid:hastalarid}, function (getVeri) {
                                $('.ilacsarfbody').html(getVeri);
                            });
                        });
                        $("body").off("click", ".orderislem").on("click", ".orderislem", function (e) {
                            var protokolno="35";
                            $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {protokolno:protokolno}, function (getVeri) {
                                $('.orderlistesibody<?php echo $protokol_no;?>').html(getVeri);
                            });
                        });

                        $("body").off("click", ".tedavigiris").on("click", ".tedavigiris", function (e) {
                            var protokolno = $(this).attr('protokolno');
                            $.get("ajax/yatis/yatismodalbody.php?islem=tedavigirisbody", {protokolno: protokolno}, function (getVeri) {
                                $('#modal-tanim-icerik').html(getVeri);
                            });
                        });

                        $( "#order-tab" ).one( "click", function() {
                            setTimeout(function () {
                                $('#tableorder').DataTable({
                                    "responsive": true
                                });
                            }, 500);
                        });
                    });
                </script>
            </div>
        </div>

        <script>
            $("body").off("click", ".istemislem<?php echo $protokol_no;?>").on("click", ".istemislem<?php echo $protokol_no;?>", function (e) {
                var protokolno = $(this).attr('protokolno');
                $.get("ajax/hastadetay/hastaistemlerilistele.php", {protokolno: protokolno}, function (getVeri) {
                    $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
                });
            });
            $("body").off("click", ".labislem<?php echo $protokol_no;?>").on("click", ".labislem<?php echo $protokol_no;?>", function (e) {
                var protokolno = $(this).attr('protokolno');
                $.get("ajax/hastadetay/hastalaboratuvarlistele.php", {protokolno: protokolno}, function (getVeri) {
                    $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
                });
            });

            $("body").off("click", ".rontgenislem<?php echo $protokol_no;?>").on("click", ".rontgenislem<?php echo $protokol_no;?>", function (e) {
                var protokolno = $(this).attr('protokolno');
                $.get("ajax/hastadetay/hastaradyolojilistele.php", {protokolno: protokolno}, function (getVeri) {
                    $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
                });
            });
        </script>

    </div>

</div>


<div class="modal fade modal-xl" id="tani-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-body" id="tani-modal-icerik"></div>
    </div>
</div>

<div class="modal fade modal-lg" id="modal-tani" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="tani-icerik"></div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("body").off("click", ".orderislem").on("click", ".orderislem", function (e) {
            $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {protokolno:<?php echo $protokol_no ?>}, function (getVeri) {
                $('.orderlistesibody<?php echo $protokol_no;?>').html(getVeri);
            });
        });

        $("body").off("click", ".tedavigiris").on("click", ".tedavigiris", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            $.get("ajax/yatis/yatismodalbody.php?islem=tedavigirisbody", {protokolno: protokolno}, function (getVeri) {
                $('#modal-tanim-icerik').html(getVeri);
            });
        });

        $( "#order-tab" ).one( "click", function() {
            setTimeout(function () {
                $('#tableorder').DataTable({
                    "responsive": true
                });
            }, 500);
        });

        $("body").off("click", ".istemislem").on("click", ".istemislem", function (e) {
        var protokolno = <?php echo $protokol_no; ?>;
        $.get("ajax/hastadetay/hastaistemlerilistele.php", {protokolno: protokolno}, function (getVeri) {
            $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
        });
    });

    //    $("body").off("click", ".labislem").on("click", ".labislem", function (e) {
    //    var protokolno = <?php //echo $protokol_no; ?>//;
    //    $.get("ajax/hastadetay/hastalaboratuvarlistele.php", {protokolno: protokolno}, function (getVeri) {
    //        $('#hastaistemlericerik<?php //echo $protokol_no; ?>//').html(getVeri);
    //    });
    //});

        $("body").off("click", ".rontgenislem").on("click", ".rontgenislem", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
        $.get("ajax/hastadetay/hastaradyolojilistele.php", {protokolno: protokolno}, function (getVeri) {
            $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
        });
    });

        var count<?php echo $protokol_no ?> = 0;
        $("body").off("click", ".showhastabilgi<?php echo $protokol_no ?>").on("click", ".showhastabilgi<?php echo $protokol_no ?>", function (e) {
            if (count<?php echo $protokol_no ?> == 0) {
                $(".hastabilgishow").show();
              count<?php echo $protokol_no ?> = 1;
                $(this).css("background-color", "#E7F1FF");
            } else {
                $(".hastabilgishow").hide();
              count<?php echo $protokol_no ?> = 0;
                $(this).css("background-color", "white");
            }
        });

        $("body").off("click", ".paketekle").on("click", ".paketekle", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            var tcno = $(this).attr('tcno');
            var tip = $(this).attr('tip');
            $.get("ajax/tanilistesigetir.php?islem2=poliklinik", {protokolno: protokolno, tcno: tcno, tip: tip}, function (getveri) {
                $('#tanigeldi').html(getveri);
            });
        });

            $("body").off("click", ".istemekleistemler").on("click", ".istemekleistemler", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            var id = $(this).attr('id');
            var tip = $(this).attr('tip');
            var ihsan='istemler';
            var grup_id = $(this).attr('grup_id');
            $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getveri) {
                if(getveri) {
                    alertify.success("kullanıcılar i̇stem paketi eklendi");
                    $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=istemhizmetlistesi", {protokolno: protokolno,ihsan:ihsan}, function (getveri) {
                        $('.istemlistebody').html(getveri);
                    });
                }else{
                    alertify.error("eklerken hata oluştu! : " + getveri);
                }
            });
        });

            $("body").off("click", ".gruppaketekle").on("click", ".gruppaketekle", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            var id = $(this).attr('id');
            var tip = $(this).attr('tip');
            $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip}, function (getveri) {
                $("#hizlipaketlerigetiristemler .close").click();
                $('.modal-backdrop').remove();
                $.get( "ajax/hastadetay/hastaistemlerilistele.php", { protokolno:protokolno },function(getveri){
                    $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getveri);
                });
            });
        });

            $("body").off("click", ".sagaekle").on("click", ".sagaekle", function (e) {
            var kullanici_id = $(this).attr('kullanici_id');
            var islem_id = $(this).attr('islem_id');
            var islem = $(this).attr('islem');
            var tip=$(this).attr('tipi');
            $.get("ajax/kullanici_hazirliste_ekle.php", {kullanici_id: kullanici_id,islem_id:islem_id,islem:islem,tip:tip}, function (getveri) {
                $('#kullaniciisteklistesiistemler').html(getveri);
            });
        });

            $("body").off("click", "#paketiolustur").on("click", "#paketiolustur", function (e) {
            // buton idli elemana tıklandığında
            var gonderilenform = $("#gonderilenform").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
            $.ajax({
                url:'ajax/hastadetay/hastaistemlerilistele.php?protokolno=16', // serileştirilen değerleri ajax.php dosyasına
                type:'post', // post metodu ile
                data:gonderilenform, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(e);
                    $("#hastaistemlericerik<?php echo $protokol_no; ?> .close").click();
                    $('.modal-backdrop').remove();
                }
            });
        });

    $('#istemhizmet').DataTable({
        "scrollX": true,
        "scrollY": '45vh',
        "lengthChange": false,
        "pageLength":20,
        "dom": "<'row'<'col-sm-12 col-md-6 'B><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "buttons": [
            {
                text: '<i class="fas fa-code-pull-request"></i> Hızlı Paket Listele',
                className:'btn kapat-btn btn-sm text-white',
                titleAttr:'Hastaya İstem Paketi Eklemek İçin Tıklayınız...',

                action: function( e, dt, node, config ) {
                    var ihsan="30041";
                    $.get("ajax/hastahizlipaketlistele.php?islem=istemler", {protokolno:16,ihsan:ihsan}, function (getveri) {
                        $('#hastahizlipaketlisteleislemistemler').html(getveri);
                    });
                },
                attr:  {
                    'data-bs-toggle':"modal",
                    'data-bs-target':"#hizlipaketlerigetiristemler",
                }
            },
            {
                text: '<i class="fas fa-code-pull-request"></i> Hızlı Paket Oluştur',
                className:'btn yeni-btn btn-sm text-white mx-1',
                titleAttr:'Hızlı Paket Oluşturmak İçin Tıklayınız...',

                action: function( e, dt, node, config ) {  },
                attr:  {
                    'data-bs-toggle':"modal",
                    'data-bs-target':"#hizlipaketolusturistemler",
                }
            },
            {
                text: '<i class="fas fa-code-pull-request"></i> İstem Ekle',
                className:'btn up-btn btn-sm text-white mx-1',
                titleAttr:'Hastaya İstem Eklemek İçin Tıklayınız...',

                action: function( e, dt, node, config ) {
                },
                attr:  {
                    'data-bs-toggle':"modal",
                    'data-bs-target':"#yeniistemekleistemler",
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
        },
    });

    });
</script>









