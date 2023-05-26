
<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

date_default_timezone_set('europe/istanbul');
$simdikitarih = date('y/m/d h:i:s');

$islem=$_GET['islem'];


if ($islem=="hastaavansin"){
    if($_POST['hastaavansid']!="") {
        $hastaavansid = $_POST['hastaavansid'];
    }else {
        $hastaavansid = $_GET['hastaavansid'];
    }
    $personelbilgileri=tekil("kullanicilar","id","$hastaavansid");

    $personelozluk=tekil("personel_ozluk","kullanici_id","$hastaavansid");
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btnpersonelavans").on("click", function(){

                var gonderilenform = $("#formpersonelavans").serialize();
                var odemeturu ='1';
                var hastaid = $(this).data('id');
                document.getelementbyid("formpersonelavans").value = "";
                document.getelementbyid("formpersonelavans").reset();
                $.ajax({
                    type:'post',
                    url:'ajax/veznepersonelavans.php?islem=personelavansinsert&odemeturu='+odemeturu+'&hastaid='+hastaid,
                    data:gonderilenform,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        alertify.message( 'ödeme işlemi başarılı');

                        $.get( "ajax/veznepersonelavans.php?islem=personelavanslistesi", {"hastaid":hastaid  },function(e){
                            $('#personelavanslist').html(e);

                        });
                    }
                });

            });
        });

    </script>


    <form action="javascript:void(0);" id="formpersonelavans">

        <input class="form-control"
               type="hidden"  value="<?php echo $hastaavansid; ?>"
               id="example-text-input" name="protokol_id">
        <div class="row">
            <div class="col-md-12 row">
                <div class="col-md-1">
                    <?php
                    $cinsiyet=$personelozluk['cinsiyet'];
                    if(isset($personelbilgileri['fotograf'])) {?>
                        <img src="<?php echo $personelbilgileri['fotograf'] ?>" alt="" class="rounded avatar-md mt-2">
                    <?php }else{ ?>
                        <?php if($cinsiyet=='e'){  ?>
                            <img src="assets/images/erkek.jpeg " alt="" class="rounded avatar-md mt-2">
                        <?php } elseif($cinsiyet=='k'){ ?>
                            <img src="assets/images/kadin.jpeg" alt="" class="rounded avatar-md mt-2">

                        <?php } }?>
                </div>
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input"
                                       class="col-md-4 col-form-label">adı soyadı</label>

                                <div class="col-md-8">
                                    <input class="form-control"
                                           type="text" disabled value="<?php echo $personelbilgileri['name_surname']; ?>"
                                           id="example-text-input">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input"
                                       class="col-md-4 col-form-label">makbuz
                                    tarihi</label>
                                <div class="col-md-8">
                                    <input class="form-control"
                                           type="text" disabled value="<?php echo $simdikitarih; ?>"
                                           id="example-text-input">
                                    <input class="form-control"
                                           type="hidden"  value="<?php echo $simdikitarih; ?>"
                                           id="example-text-input" name="hastaavans[makbuz_tarihi]">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input"
                                       class="col-md-4 col-form-label">makbuz numarası</label>

                                <div class="col-md-8" id="makbuznummarasi">
                                    <input class="form-control"
                                           type="text" disabled value="<?php echo rasgelesifreolustur(8); ?>"
                                           id="example-text-input">
                                    <input class="form-control"
                                           type="hidden"  value="<?php echo rasgelesifreolustur(8); ?>"
                                           id="example-text-input" name="hastaavans[makbuz_seri_no]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">vezne birim</label>
                                <div class="col-md-8">
                                    <select class="form-select" name="hastaavans[vezne_birim]" id="birim5">
                                        <option selected>birim seç</option>
                                        <?php
                                        $uyrukgetir = "select * from birimler";
                                        $hello = verilericoklucek($uyrukgetir);
                                        foreach ($hello as $rowa) {

                                            ?>
                                            <option
                                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["bolum_adi"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input"
                                       class="col-md-4 col-form-label">veznedar</label>

                                <div class="col-md-8">
                                    <select class="form-select" name="hastaavans[veznedar]" id="personel5">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">avans tutar</label>

                                <div class="col-md-8" >
                                    <input class="form-control" type="number"  name="kasadetay[tutar]"
                                           id="example-text-input">
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#birim5").change(function () {
                                    var birimid = $(this).val();
                                    $.ajax({
                                        type: "post",
                                        url: "model/ajax.php?islem=birimgetir",
                                        data: {"birimid": birimid},
                                        success: function (e) {
                                            $("#personel5").html(e);
                                        }
                                    })
                                })


                            })

                        </script>

                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">dövüz
                                    türü</label>
                                <div class="col-md-8">
                                    <select class="form-select" id="dovuzturu2" name="hastaavans[dovuz_turu]">
                                        <?php
                                        $uyrukgetir = "select * from islem_tanimlari where tanim_tipi='dovuz_turu'";
                                        $hello = verilericoklucek($uyrukgetir);
                                        foreach ($hello as $rowa) {

                                            ?>
                                            <option value="<?php echo $rowa["tanim_eki"]; ?>"><?php echo $rowa["tanim_adi"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">dövüz
                                    kuru</label>
                                <div class="col-md-8" id="dovuzkuru2">
                                    <input class="form-control" type="text" disabled
                                           id="example-text-input">
                                    <input class="form-control" type="hidden" disabled name="hastaavans[dovuz_kuru]"
                                           id="example-text-input">
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#dovuzturu2").change(function () {
                                    var dovuzekkod = $(this).val();
                                    $.ajax({
                                        type: "post",
                                        url: "model/ajax.php?islem=dovuztipi",
                                        data: {"dovuzekkod": dovuzekkod},
                                        success: function (e) {
                                            $("#dovuzkuru2").html(e);
                                        }
                                    })
                                })

                            });
                        </script>
                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">kasa</label>
                                <div class="col-md-8">
                                    <select class="form-select" name="kasadetay[kasa_id]" >
                                        <?php

                                        $sql = "select * from kasa where durum=1";
                                        $stid = oci_parse($baglanti, $sql);
                                        oci_execute($stid);
                                        while (($row = oci_fetch_array($stid, oci_assoc)) != false) {
                                            extract($row);
                                            ?>
                                            <option value="<?php echo $id ?>" ><?php echo $kasaadi ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer bg-transparent border-success">
            <div class="row">
                <div class="col-md-12" align="right">
                    <button type="button" class="btn btn-success btn-sm btnpersonelavans btnlist1 makbuznogetir" data-id="<?php echo $hastaavansid; ?>"  >
                        <i class="fas fa-book px-1"></i>nakit avans ödeme
                    </button>
                </div>

            </div>

        </div>

    </form>


<?php }

if ($islem=="personelavansinsert") {
    $_POST["kasadetay"]["ekleyen_kodu"]=$_POST["hastaavans"]["veznedar"];
    $_POST["kasadetay"]["kayit_zamani"]=$simdikitarih;
    $_POST["kasadetay"]["islem_tipi"]=giren;
    $_POST["kasadetay"]["protokol_id"]=$_GET['hastaid'];

    $kayitolustur = groupdirektekle("kasa_detay", $_POST['kasadetay'], "", "kasadetay");

    if($kayitolustur==1){
        $_POST["hastaavans"]["kasa_detay_id"]=islemtanimsoneklenen("kasa_detay");
        $_POST["hastaavans"]["protokol_id"]=$_GET['hastaid'];
        $_POST["hastaavans"]["hasta_tipi"]=1;
        $_POST["hastaavans"]["odeme_turu"]=$_GET['odemeturu'];

        $kayitolustur1 = groupdirektekle("hasta_avans", $_POST['hastaavans'], "", "hastaavans");

        if($kayitolustur1==1){

            echo "veri eklendi";
        }else{

            var_dump($kayitolustur);
            echo "veri eklenmedi..";
        }

    }else{

        var_dump($kayitolustur);
        echo "veri eklenmedi..";
    }
}
elseif ($islem=="personelavansilme"){
    if ($_POST) {

        $avansid=$_POST['id'];
        $avansdetay=$_POST['silme_detay'];
        $hastaavans =silmedetay("hasta_avans","id","$avansid","$avansdetay");

        if ($hastaavans==1){

            $kasadetayid=$babakalim=tekil("hasta_avans","id","$avansid");
            $kasaid=$kasadetayid['kasa_detay_id'];
            $hastaavans =silme("kasa_detay","id",$kasaid,$_POST);
            if ($hastaavans==1){
                echo "silme başarılı";
            }else{
                echo  "kasaekleme hatası";
            }

        }else{
            echo  print_r($hastaavans);
        }


    }
}

elseif ($islem=="makbuznogetir"){ ?>
    <input class="form-control" type="text" disabled value="<?php echo rasgelesifreolustur(8); ?>" id="example-text-input">
    <input class="form-control" type="hidden"  value="<?php echo rasgelesifreolustur(8); ?>" id="example-text-input" name="hastaavans[makbuz_seri_no]">
<?php }


elseif ($islem=="personelavanslistesi") {   ?>
    <?php  echo $hastaid=$_GET['hastaid']; ?>
    <table id="example" class="table  table-bordered table-sm table-hover "
           style=" background:white;width: 100%;">
        <thead>
        <tr>

            <th scope="col">makbuz no</th>
            <th scope="col">ödeme türü</th>
            <th scope="col">vezne birim</th>
            <th scope="col">veznedar</th>
            <th scope="col">tutarı</th>

        </tr>
        </thead>
        <tbody id="hastaavanslist">

        <?php

        $hastalistgetir = "select * from hasta_avans where protokol_id='$hastaid' and durum='1'";

        $stida = oci_parse($baglanti, $hastalistgetir);
        oci_execute($stida);
        while (($rowa = oci_fetch_array($stida, oci_assoc)) != false) {

            ?>
            <!--            <tr id="hastaavansuptade" class="personelavanslist" data-toggle="modal" data-id="--><?php //echo $rowa["id"] ?><!--" data-target="#avanspersonelmodal">-->
            <tr>

                <td><?php echo $rowa["makbuz_seri_no"]; ?></td>
                <td><?php $odemeturu=islemtanimekgetir("odeme_turu",$rowa["odeme_turu"]);
                    echo $odemeturu; ?> </td>

                <td><?php echo birimgetir($rowa["vezne_birim"]); ?></td>
                <td><?php echo kullanicigetir($rowa["veznedar"]); ?></td>
                <td><?php  $tutar=kasadeatygetirid($rowa["kasa_detay_id"]);
                    echo $tutar['tutar']; ?></td>
                <td>
                    <center><button type="button" class="btn btn-danger btn-sm btnhastaavanssil " data-toggle="modal"  data-target="#personelavansdelete" data-id="<?php echo $rowa["id"] ?>" >
                            <i class="fas fa-trash-alt  px-1"></i>
                        </button></center>
                </td>

            </tr>

        <?php } ?>


        </tbody>
    </table>

    <div class="modal fade" role="dialog" id="personelavansdelete">
        <form  action="javascript:void(0);"  id="formhastavans1" >
            <div class="modal-dialog">
                <!-- modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">hasta avans silme</h4>
                        <button type="button" class="close btn-danger" data-dismiss="modal">x</button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="alert alert-danger" role="alert">
                                hasta avans silmek için emin misiniz ?
                            </div>
                            <input class="form-control" name="id" type="text"  id="hastaavansid">
                        </div>
                        <div class="mb-3">
                            <label for="basicpill-firstname-input" class="form-label">neden silmek istediğinizi açıklar mısınız ?</label>
                            <textarea class="form-control" name="silme_detay" ></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">kapat</button>
                        <input class="btn btn-success w-md justify-content-end personelavanssil " style="margin-bottom:4px" data-id="<?php echo $hastaid; ?>" type="submit" data-dismiss="modal" value="kaydet"/>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".btnhastaavanssil").on("click", function(){

                document.getelementbyid("hastaavansid").value = $(this).data('id');
            });


            $(".personelavanssil").on("click", function(){
                var hastaid=$(this).data('id');
                var gonderilenform = $("#formhastavans1").serialize();
                document.getelementbyid("formhastavans1").reset();
                $.ajax({
                    type:'post',
                    url:'ajax/veznepersonelavans.php?islem=personelavansilme',
                    data:gonderilenform,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        alertify.message( 'iptal işlemi başarılı');

                        $.get( "ajax/veznepersonelavans.php?islem=personelavanslistesi", {"hastaid":hastaid  },function(e){
                            $('#personelavanslist').html(e);

                        });
                    }
                });

            });

        });

    </script>


<?php } ?>

<script>
    $(document).ready(function(){
        $(".btnlist").on("click", function(){
            alert("liste yenilendi.:");
            var hastaid =$(this).data('id');
            $.ajax({
                type:'post',
                url:'ajax/veznepersonelavans.php?islem=personelavanslistesi',
                data:{"hastaid":hastaid },
                success:function(e){
                    $("#personelavanslist").html(e);
                }
            });

        });
        $(".makbuznogetir").on("click", function(){
            $.ajax({
                type:'post',
                url:'ajax/veznepersonelavans.php?islem=makbuznogetir',
                data:{ },
                success:function(e){
                    $("#makbuznummarasi").html(e);
                }
            });

        });

    });

</script>