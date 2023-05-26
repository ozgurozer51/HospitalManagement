<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$islemid=$_POST["islem_detay_id"];
if($islemid=="") {
    $islemid = $_GET["islemid"];
}
if($_GET["silinecekid"]!=""  ) {
    silme("transaction_details_costs","id",$_GET["silinecekid"]);

}
if($_POST["kurum_id"]!=""  ) {

    $kurumidsikayitlimi = singular("transaction_details_costs", "institution_id", $_POST["kurum_id"]);
if($kurumidsikayitlimi=="" or $kurumidsikayitlimi["durum"]!=1){
    direktekle("islem_detaylari_ucretleri", $_POST);
}else{
    echo '<div class="alert alert-danger" role="alert">
                                            eklemek istediğiniz kurum daha önce eklenmiş olduğundan tekrar işlem yapamazsınız!
                                        </div>';
}
}
?>
<script>

    $(document).ready(function(){


        $(".islemkaydisil").click(function(){
            var goster = $(this).attr('id');
            $.ajax({
                url:'ajax/islemdetayfiyatekle.php?silinecekid=' + goster, // serileştirilen değerleri ajax.php dosyasına
                type:'post', // post metodu ile
                // data:{ getir:goster }, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    $('#sonuc').html(e);// div elemanını her gönderme işleminde boşalttı ve gelen verileri içine attı
                }
            });
        });
    });

</script>

<div class="card-body" id='sonuc'>
    <div class="table-responsive">
        <table  id="example" class="table table-striped table-bordered" style=" background:white">

            <thead>
            <tr>
                <th>durum</th>
                <th>kurum adı</th>
                <th>özel kodu</th>
                <th>ücret(ayaktan)</th>
                <th>ücret(yatan acil)</th>
                <th>fark (ayak)</th>
                <th>fark (yatan acil)</th>
                <th>ttb birimi</th>
                <th>i̇şlem</th>

            </tr>
            </thead>
            <tbody>
            <?php

            $sql = "select * from transaction_details_costs where status!='2'  and process_detail_id='$islemid' order by id";
            $hello=verilericoklucek($sql);
            foreach ($hello as $row) {

                ?>
                <tr>
                    <th id="<?php echo $row["id"]; ?>"    scope="row"><?php if($row["durum"]==1){ echo butontanimla("","1");}else{ echo  butontanimla("","0");} ?></th>
                    <td ><?php echo islemtanimgetir($row["kurum_id"]); ?></td>
                    <td ><?php echo $row["special_code"]; ?></td>
                    <td ><?php echo $row["ttb_of_unit"]; ?></td>
                    <td ><?php echo $row["standing_cost"]; parabirimi(); ?>  </td>
                    <td ><?php echo $row["lying_urgent_cost"]; parabirimi(); ?> </td>
                    <td ><?php echo $row["diffrence_standing_cost"]; parabirimi(); ?>  </td>
                    <td ><?php echo $row["diffrence_standing_urgent_cost"]; parabirimi();?> </td>
                    <td ><button id="<?php echo $row["id"]; ?>" type="button" class="islemkaydisil btn btn-danger waves-effect waves-light">
                            <i class="bx bx-block font-size-16 align-middle me-2"></i> sil
                        </button></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>