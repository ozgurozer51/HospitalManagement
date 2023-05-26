<?php include "../../../controller/fonksiyonlar.php";
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
    $hastakayit=tekil("hasta_kayit","id","$hastaavansid");
    $tcno=$hastakayit['tc_kimlik'];
    $hastabilgileri=tekil("hastalar","tc_kimlik","$tcno");
    ?>

    <form action="javascript:void(0);" id="formhastaavans">

        <input class="form-control"
               type="hidden"  value="<?php echo $hastaavansid; ?>"
               id="example-text-input" name="protokol_id">
        <div class="row">
            <div class="col-md-12 row">
                <div class="col-md-1">
                    <?php
                    $cinsiyet=$hastabilgileri['cinsiyet'];
                    if(isset($hastabilgileri['fotograf'])) {?>
                        <img src="<?php echo $hastabilgileri['fotograf'] ?>" alt="" class="rounded avatar-md mt-2">
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
                                           type="text" disabled value="<?php echo $hastabilgileri['adi'].' '.$hastabilgileri['soyadi']; ?>"
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
                                       class="col-md-4 col-form-label">makbuz no</label>

                                <div class="col-md-8" id="makbuznummarasi1">
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

                                    <select class="form-select" name="hastaavans[vezne_birim]" id="birim4">
                                        <option selected>birim seç</option>
                                        <?php $uyrukgetir = verilericoklucek("select * from birimler where durum='1'");
                                       foreach ($uyrukgetir as $rowa){ ?>
                                         <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["bolum_adi"]; ?></option>
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
                                    <select class="form-select" name="hastaavans[veznedar]" id="personel4">

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
                                $("#birim4").change(function () {
                                    var birimid = $(this).val();
                                    $.ajax({
                                        type: "post",
                                        url: "model/ajax.php?islem=birimgetir",
                                        data: {"birimid": birimid},
                                        success: function (e) {
                                            $("#personel4").html(e);
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

                                    <select class="form-select" id="dovuzturu1" name="hastaavans[dovuz_turu]">
                                        <?php $uyrukgetir = verilericoklucek("select * from islem_tanimlari where tanim_tipi='dovuz_turu'");
                                        foreach ($uyrukgetir as $rowa){?>
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
                                <div class="col-md-8" id="dovuzkuru1">
                                    <input class="form-control" type="text" disabled
                                           id="example-text-input">
                                    <input class="form-control" type="hidden" disabled name="hastaavans[dovuz_kuru]"
                                           id="example-text-input">
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $("#dovuzturu1").change(function () {
                                    var dovuzekkod = $(this).val();
                                    $.ajax({
                                        type: "post",
                                        url: "model/ajax.php?islem=dovuztipi",
                                        data: {"dovuzekkod": dovuzekkod},
                                        success: function (e) {
                                            $("#dovuzkuru1").html(e);
                                        }
                                    })
                                })

                            });
                        </script>

                        <div class="col-md-4">
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">kasa
                                    türü</label>
                                <div class="col-md-8">

                                    <select class="form-select" id="kasadetay" name="kasadetay[kasa_id]" >
                                        <?php $sql = verilericoklucek("select * from kasa where durum=1");
                                       foreach ($sql as $item){ ?>
                                            <option value="<?php echo $item['id']; ?>" ><?php echo $item['kasaadi']; ?></option>
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
                    <button type="button" class="btn btn-success btn-sm btnhastaavans makbuznogetir" data-id="<?php echo $hastaavansid; ?>"  >
                        <i class="fas fa-book px-1"></i>nakit avans ödeme
                    </button>
                </div>

            </div>

        </div>

    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btnhastaavans").on("click", function(){

                var gonderilenform = $("#formhastaavans").serialize();
                var odemeturu ='1';
                var hastaid = $(this).data('id');
                document.getelementbyid("formhastaavans").reset();
                $.ajax({
                    type:'post',
                    url:'ajax/veznehastaavans.php?islem=hastaavansinsert&odemeturu='+odemeturu+'&hastaid='+hastaid,
                    data:gonderilenform,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        alertify.message( 'ödeme işlemi başarılı');
                        $.get( "ajax/veznehastaavans.php?islem=hastaavanslistesi", {"hastaid":hastaid  },function(e){
                            $('#hastaavanslist').html(e);

                        });
                    }
                });

            });

            $(".makbuznogetir").on("click", function(){
                alert("makbuz no yenilendi..")
                $.ajax({
                    type:'post',
                    url:'ajax/veznehastaavans.php?islem=makbuznogetir',
                    data:{ },
                    success:function(e){
                        $("#makbuznummarasi1").html(e);
                    }
                });

            });

        });

    </script>

<?php }

if ($islem=="hastaavansinsert") {


    $_POST["kasadetay"]["ekleyen_kodu"]=$_POST["hastaavans"]["veznedar"];
    $_POST["kasadetay"]["kayit_zamani"]=$simdikitarih;
    $_POST["kasadetay"]["islem_tipi"]=giren;
    $_POST["kasadetay"]["protokol_id"]=$_GET['hastaid'];

    $kayitolustur = groupdirektekle("kasa_detay", $_POST['kasadetay'], "", "kasadetay");

    if($kayitolustur==1){
        $_POST["hastaavans"]["kasa_detay_id"]=islemtanimsoneklenen("kasa_detay");
        $_POST["hastaavans"]["protokol_id"]=$_GET['hastaid'];
        $_POST["hastaavans"]["hasta_tipi"]=0;
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

elseif ($islem=="hastaavansilme"){
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

elseif ($islem=="hastaavanslistesi") {   ?>


    <table id="example" class="table  table-bordered table-sm table-hover "
           style=" background:white;width: 100%;">
        <thead>
        <tr>

            <th scope="col">makbuz no</th>
            <th scope="col">ödeme türü</th>
            <th scope="col">vezne birim</th>
            <th scope="col">veznedar</th>
            <th scope="col">tutarı</th>
            <th></th>

        </tr>
        </thead>
        <tbody id="hastaavanslist">

        <?php $hastaid = $_GET['hastaid'];
        $hastalistgetir = verilericoklucek("select * from hasta_avans where protokol_id='$hastaid' and durum='1'");
        foreach ($hastalistgetir as $rowa){ ?>
            <tr >

                <td><?php echo $rowa["makbuz_seri_no"] ?></td>
                <td><?php $odemeturu=islemtanimekgetir("odeme_turu",$rowa["odeme_turu"]);echo $odemeturu ?></td>
                <td><?php echo birimgetir($rowa["vezne_birim"]); ?></td>
                <td><?php echo kullanicigetir($rowa["veznedar"]); ?></td>
                <td><?php  $tutar=kasadeatygetirid($rowa["kasa_detay_id"]);echo $tutar['tutar'] ?></td>
                <td><button type="button" class="btn btn-danger btn-sm btnhastaavanssil"  data-toggle="modal" data-id="<?php echo $rowa["id"] ?>" data-target="#hastaavansdelete"><i class="fas fa-trash-alt px-1"></i></button></td>

            </tr>

        <?php } ?>


        </tbody>
    </table>

    <div class="modal fade" role="dialog" id="hastaavansdelete">
        <form  action="javascript:void(0);"  id="formhastavans" >
            <div class="modal-dialog">
                <!-- modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">hasta avans silme</h4>
                        <button type="button" class="close btn-danger" data-dismiss="modal">x</button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="alert alert-danger" role="alert">hasta avans silmek için emin misiniz ?</div>

                            <input class="form-control" name="id" type="hidden"  id="hastaavansid">

                        </div>
                        <div class="mb-3">
                            <label for="basicpill-firstname-input" class="form-label">neden silmek istediğinizi açıklar mısınız ?</label>
                            <textarea class="form-control" name="silme_detay" ></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">kapat</button>
                        <button class="btn btn-success w-md justify-content-end hastaavanssil1" data-id="<?php echo $hastaid; ?>" style="margin-bottom:4px"  type="submit" data-dismiss="modal" >i̇ptal</button>
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

            $(".hastaavanssil1").on("click", function(){
                hastaid=$(this).data('id');
                var gonderilenform = $("#formhastavans").serialize();
                document.getelementbyid("formhastavans").reset();
                $.ajax({
                    type:'post',
                    url:'ajax/veznehastaavans.php?islem=hastaavansilme',
                    data:gonderilenform,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        alertify.message( 'iptal işlemi başarılı');
                        $.get( "ajax/veznehastaavans.php?islem=hastaavanslistesi", {"hastaid":hastaid  },function(e){
                            $('#hastaavanslist').html(e);
                        });
                    }
                });
            });
        });

    </script>


<?php } elseif ($islem=="hastaavansmbody") {   ?>
    <?php if($_POST['hastaavansid']!="") {
        $hastaavansid = $_POST['hastaavansid'];
    }else {
        $hastaavansid = $_GET['hastaavansid'];
    }
    $hastaavansbilgi=tekil("hasta_avans","id","$hastaavansid");

    $hastakayitbilgi=tekil("hasta_kayit","id",$hastaavansbilgi['protokol_id']);

    $hastabilgi=tekil("hastalar","tc_kimlik",$hastakayitbilgi['tc_kimlik']); ?>

    <div class="col-md-12">
        <form action="javascript:void(0);" id="formhastaavansup">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2 row">
                        <label for="example-text-input"
                               class="col-md-4 col-form-label">adı soyadı</label>

                        <div class="col-md-8">
                            <input class="form-control" type="text" disabled value="<?php echo $hastabilgi['adi'].' '.$hastabilgi['soyadi']; ?>" id="example-text-input">
                            <input class="form-control" type="text"  value="<?php echo $hastaavansid; ?>" id="hastaavansid" name="hastaavansid">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2 row">
                        <label for="example-text-input" class="col-md-4 col-form-label">makbuz tarihi</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" disabled value="<?php echo $hastaavansbilgi['makbuz_tarihi']; ?>" id="example-text-input">
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2 row">
                        <label for="example-text-input" class="col-md-4 col-form-label">makbuz no</label>

                        <div class="col-md-8">
                            <input class="form-control" type="text" disabled value="<?php echo $hastaavansbilgi['makbuz_seri_no']; ?>" id="example-text-input">
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-md-4 col-form-label">avans tutar</label>

                        <div class="col-md-8" >
                            <input class="form-control" type="number"  name="avans_tutar" value="<?php echo $hastaavansbilgi['avans_tutar']; ?>" id="example-text-input">
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2 row">
                        <label for="example-text-input" class="col-md-4 col-form-label">vezne birim</label>
                        <div class="col-md-8">

                            <select class="form-select" name="vezne_birim" id="birim9">
                                <option selected>birim seç</option>
                                <?php $uyrukgetir = verilericoklucek("select * from birimler");
                                foreach ($uyrukgetir as $rowa){ ?>
                                    <option <?php if($hastaavansbilgi['vezne_birim'] == $rowa["id"]) echo"selected"; ?>
                                        value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["bolum_adi"]; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-2 row">
                        <label for="example-text-input"
                               class="col-md-4 col-form-label">veznedar</label>

                        <div class="col-md-8">

                            <select class="form-select" name="veznedar" id="personel9">
                                <?php $kulaniciid=$hastaavansbilgi['veznedar'];
                                $uyrukgetir = verilericoklucek("select * from kullanicilar where id='$kulaniciid'");
                                foreach($uyrukgetir as $rowa){ ?>
                                    <option <?php if($hastaavansbilgi['veznedar'] == $rowa["id"]) echo"selected"; ?>value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["name_surname"]; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function () {
                        $("#birim9").change(function () {
                            var birimid = $(this).val();
                            $.ajax({
                                type: "post",
                                url: "model/ajax.php?islem=birimgetir",
                                data: {"birimid": birimid},
                                success: function (e) {
                                    $("#personel9").html(e);
                                }
                            })
                        })


                    })

                </script>

            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-md-4 col-form-label">dövüz
                            türü</label>
                        <div class="col-md-8">

                            <select class="form-select" id="dovuzturu2" name="dovuz_turu">
                                <?php $uyrukgetir = verilericoklucek("select * from islem_tanimlari where tanim_tipi='dovuz_turu'");
                               foreach ($uyrukgetir as $rowa){ ?>
                                    <option <?php if($hastaavansbilgi['dovuz_turu'] == $rowa["tanim_eki"]) echo"selected"; ?>
                                        value="<?php echo $rowa["tanim_eki"]; ?>"><?php echo $rowa["tanim_adi"]; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-md-4 col-form-label">dövüz kuru</label>
                        <div class="col-md-8" id="dovuzkuru2">
                            <input class="form-control" type="text" disabled value="<?php echo $hastaavansbilgi['dovuz_kuru'] ?>" id="example-text-input">
                            <input class="form-control" type="hidden" disabled name="dovuz_kuru" value="<?php echo $hastaavansbilgi['dovuz_kuru'] ?>" id="example-text-input">
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

            </div>
            <div class="card-footer bg-transparent border-success">
                <div class="row">
                    <div class="col-md-12" align="right">
                        <!--                    <button type="button" class="btn btn-danger btn-sm btnhastaavanssil" data-toggle="modal" data-id="--><?php //echo $hastaavansbilgi['protokol_id'] ?><!--" data-target="#hastaavansdelete" >-->
                        <!--                        <i class="fas fa-book px-1"></i>hasta avans sil-->
                        <!--                    </button>-->
                        <button type="button" class="btn btn-success btn-sm btnhastaavansupdate" data-id="<?php echo $hastaavansbilgi['protokol_id'] ?>" data-dismiss="modal" ><i class="fas fa-book px-1"></i>nakit avans ödeme</button>

                    </div>

                </div>

            </div>
        </form>
    </div>
<?php } ?>
