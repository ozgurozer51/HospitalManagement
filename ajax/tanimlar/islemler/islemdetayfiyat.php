<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$islemid=$_GET["islemid"];
$KULLANICI_ID = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');

if(isset($_POST["institution_id"])) {
    if(!isset($kurumidsikayitlimi["id"])){
        $sql = direktekle("transaction_details_costs", $_POST);
        if ($sql == 1) { ?>
            <script>
                alertify.set('notifier','delay', 8);
                alertify.success('Ekleme İşlemi Başarılı');
            </script>
        <?php   }
        else{
        echo '<div class="alert alert-danger" role="alert">eklemek istediğiniz kurum daha önce eklenmiş olduğundan tekrar işlem yapamazsınız! </div>';
    }
} }

$islemid=$_POST["process_detail_id"];
if($islemid=="") {
    $islemid = $_GET["islemid"];
}
if($_GET["silinecekid"]!=""  ) {
    $id = $_GET["silinecekid"];
    $detay = $_GET["silme_detay"];
   $sql =  canceldetail("transaction_details_costs","id",$id,$detay,$KULLANICI_ID,$simdikitarih);
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Silme İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Silme İşlemi Başarısız');
        </script>

    <?php } }  ?>

    <form class="modal-content"  action="birimtanimla"  id="gonderilenform" method="post" autocomplete="off" >
        <div class="modal-header">
            <h4 class="modal-title">i̇şlemin kurumlara göre ücretleri</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-12 row">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label">kurum</label>

                        <select name="institution_id" id="kurum_seciniz"    class="form-control" >
                            <option value="">seçim yapınız</option>
                            <?php $bolumgetir = "select * from transaction_definitions where definition_type='KURUMLAR'";
                            $hello=verilericoklucek($bolumgetir);
                            foreach ($hello as $rowa) { ?>
                                <option   value="<?php echo $rowa["definition_code"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                                <?php } ?>
                        </select>

                    </div>

                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label">alt kurum</label>

                        <select name="sub_institution" id="alt_kurum"    class="form-control" >
                            <option value="">seçim yapınız</option>
                        </select>

                    </div>

                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label">özel kodu</label>
                        <input type="text" class="form-control" name="special_code" value="" id="basicpill-firstname-input"  id="basicpill-firstname-input">
                        <input type="hidden" class="form-control" name="process_detail_id"   id="basicpill-firstname-input"  value="<?php echo $islemid; ?>" id="basicpill-firstname-input">
                    </div>

                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label">ttb birimi</label>
                        <input type="text" class="form-control" name="ttb_of_unit" value="" id="basicpill-firstname-input"  id="basicpill-firstname-input">
                    </div>

                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">ücret(ayak)</label>
                        <input type="text" class="form-control" name="standing_cost" value="0" id="basicpill-firstname-input" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">ücret(yatan acil)</label>
                        <input type="text" class="form-control" name="lying_urgent_cost" value="0" id="basicpill-firstname-input"  id="basicpill-firstname-input">
                    </div>

                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">fark(ayak)</label>
                        <input type="text" class="form-control" name="diffrence_standing_cost" value="0" id="basicpill-firstname-input" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">fark(yatan acil)</label>
                        <input type="text" class="form-control" name="diffrence_standing_urgent_cost" value="0" id="basicpill-firstname-input"  id="basicpill-firstname-input">
                    </div>
                   <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">özel hizmet bedeli</label>
                        <input type="text" class="form-control" name="special_service_cost" value="0" id="basicpill-firstname-input"  id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <br/> 
                        <button  name="gonder" id="gonder"  type="button" class="btn btn-success waves-effect waves-light">
                            <i class="bx bx-check-double font-size-16 align-middle me-2"></i> ekle
                        </button> </div>
                    <div class="card-body mt-2" id='sonuc' style="width: 100%; height: 400px; overflow: scroll;">
                        <div class="table-responsive">
                            <table  id="example" class="table table-striped table-bordered" style=" background:white">

                                <thead>
                                <tr>
                                    <th>durum</th>
                                    <th>kurum adı</th>
                                    <th>alt kurum adı</th>
                                    <th>özel kodu</th>
                                    <th>ücret(ayaktan)</th>
                                    <th>ücret(yatan acil)</th>
                                    <th>fark (ayak)</th>
                                    <th>fark (yatan acil)</th>
                                    <th>özel hizmet bedeli</th>
                                    <th>ttb birimi</th>
                                    <th>i̇şlem</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $sql = "select * from transaction_details_costs  where status='1' and process_detail_id='$islemid'  order by id";
                                $hello=verilericoklucek($sql);
                                foreach ($hello as $row) { ?>
                                    <tr>
                                        <th id="<?php echo $row["id"]; ?>"    scope="row"><?php if($row["status"]==1){ echo butontanimla("","1");}else{ echo  butontanimla("","0");} ?></th>
                                        <td ><?php $tanimlar= singular("transaction_definitions","definition_code",$row["institution_id"]); echo $tanimlar["definition_name"]; ?></td>
                                        <td ><?php $tanimlar= singular("transaction_definitions","definition_code",$row["sub_institution"]); echo $tanimlar["definition_name"]; ?></td>
                                        <td ><?php echo $row["special_code"]; ?></td>
                                        <td ><?php echo $row["standing_cost"];  parabirimi(); ?> </td>
                                        <td ><?php echo $row["lying_urgent_cost"]; parabirimi(); ?></td>
                                        <td ><?php echo $row["diffrence_standing_cost"]; parabirimi(); ?></td>
                                        <td ><?php echo $row["diffrence_standing_urgent_cost"]; parabirimi(); ?></td>
                                        <td ><?php echo $row["special_service_cost"]; parabirimi(); ?></td>
                                        <td ><?php echo $row["ttb_of_unit"]; ?></td>
                                        <td ><button id="<?php echo $row["id"]; ?>" islemid="<?php echo $islemid; ?>" type="button" class="islemkaydisil btn btn-danger waves-effect waves-light"><i class="bx bx-block font-size-16 align-middle me-2"></i> sil</button></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">kapat</button>
        </div>
    </form>


</div>


<script>
    $("#kurum_seciniz").change(function () {
        var kurumid = $(this).val();
        $.ajax({
            type: "post",
            url: "ajax/sosyalguvencekurumsec.php",
            data: {"kurumid": kurumid},
            success: function (e) {
                $("#alt_kurum").html(e);
            }
        })
    })

    $(document).ready(function(){
        $('#example2').DataTable( {
            "scrollY": true,
            "scrollX": true
        });

        $("#gonder").click(function(){
            var gonderilenform = $("#gonderilenform").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
            $.ajax({
                url:'ajax/tanimlar/islemler/islemdetayfiyat.php?islemid=<?php echo $_GET["islemid"]; ?>', // serileştirilen değerleri ajax.php dosyasına
                type:'post', // post metodu ile
                data:gonderilenform, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    // console.log(e);
                    $('#islem-icerik').html(e);// div elemanını her gönderme işleminde boşalttı ve gelen verileri içine attı
                }
            });
        });

        $(".islemkaydisil").click(function(){
            var goster = $(this).attr('id');
            var islemid = $(this).attr('islemid');
            $.ajax({
                url:'ajax/tanimlar/islemler/islemdetayfiyat.php?silinecekid=' + goster + '&islemid=' + islemid, // serileştirilen değerleri ajax.php dosyasına
                type:'post', // post metodu ile
                // data:{ getir:goster }, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    $('#islem-icerik').html(e);// div elemanını her gönderme işleminde boşalttı ve gelen verileri içine attı
                }
            });
        });
    });

</script>