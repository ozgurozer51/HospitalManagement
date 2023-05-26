<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$islem = $_GET['islem'];
?>

<div class="modal fade bd-example-modal" id="islem-modal" role="dialog">
    <div class="modal-dialog" style="width: 95%; max-width: 95%;" >
    <div id='islem-icerik'></div>
    </div>
</div>

<div id='islemsonuc'></div>

<div class="row">
    <div class="col-lg-4" id="islem-grup-listesi"> </div>

    <div class="col-lg-8">
        <div class="card" id='islem-icerik-side'>
            <span style="font-size: 20px;padding: 30px;">sol taraftan i̇şlem seçiniz</span>
        </div>
    </div>
</div>

<div class="modal fade" id="islem-crud" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" style=" width: 50%; max-width: 95%; ">
    <form class="modal-content">

        <div class="modal-header">
             <h4 class="modal-title">İşlemler</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-12 row">

                    <div class="col-6">
                        <label for="basicpill-firstname-input" class="form-label">grubun adı</label>
                        <input type="text" class="form-control" id="definition_name" name="definition_name" value="" required id="basicpill-firstname-input">
                        <input type="hidden" class="form-control" name="id" id="transaction_definitions_id" value="" >
                    </div>

                    <div class="col-6">
                        <label for="basicpill-firstname-input" class="form-label">grubu</label>

                        <select name="definition_supplement"  id="definition_supplement"     class="form-control" >
                            <option value="">seçim yapınız</option>
                            <?php $bolumgetir = "select * from transaction_definitions where definition_type='SGK_GRUBU'";
                            $hello = verilericoklucek($bolumgetir);
                            foreach ($hello as $rowa) { ?>
                                <option  value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <input class="btn btn-primary" id="islem-ekle-sql" type="button" data-bs-dismiss="modal"  value="onayla"/>
          <input class="btn btn-primary " id="islem-guncelle-sql" type="button" data-bs-dismiss="modal"  value="onayla"/>
          <button type="button" class="btn btn-default" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">kapat</button>
        </div>
    </form>
</div>
</div>



<script>


    $(document).ready(function(){

        $.get("ajax/tanimlar/islemler/islemler-liste.php?islem=islem-grup-listesi", { },function(e){
            $('#islem-grup-listesi').html(e);
        });



        $(".islemtanimlagetir").click(function(){
            var isleminidsi = $(this).attr('id');
            $.get( "ajax/tanimlar/islemler/islemekle.php", { islemid:isleminidsi },function(getveri){
                $('#islem-icerik-side').html(getveri);
            });
        });

        $("#islem-ekle-sql").click(function(){
           var definition_name = $('#definition_name').val();
           var definition_supplement = $('#definition_supplement').val();
            $.ajax({
                type: "post",
                url: "ajax/tanimlar/islemler/islemler-sql.php?islem=islem-grubu-ekle",
                data: {definition_name:definition_name,definition_supplement:definition_supplement },
                success: function (e) {
                    $("#islem-grup-listesi").load("ajax/tanimlar/islemler/islemler-liste.php?islem=islem-grup-listesi");
                }
            });
        });

        $(document).on("click",".islem-update",function() {
           $('#islem-ekle-sql').prop("type" , "hidden");
           $('#islem-guncelle-sql').prop("type" , "button");

           var id =  $(this).attr('id');
            $('#transaction_definitions_id').val(id);
            $.get("ajax/tanimlar/islemler/islemler-sql.php?islem=islem-grubu-json-getir", {id:id },function(e){
               var sonuc = JSON.parse(e);
            $('#definition_name').val(sonuc[0].definition_name);
            $('#definition_supplement').val(sonuc[0].definition_supplement)
            });

        });

        $(document).on("click",".islemekle",function() {
            $('#islem-ekle-sql').prop("type" , "button");
            $('#islem-guncelle-sql').prop("type" , "hidden");
        });

        $(document).on("click","#islem-guncelle-sql",function() {
            var definition_name = $('#definition_name').val();
            var definition_supplement = $('#definition_supplement').val();
            var id = $('#transaction_definitions_id').val();

            $.ajax({
                type: "post",
                url: "ajax/tanimlar/islemler/islemler-sql.php?islem=islem-grubu-guncelle",
                data: {definition_name:definition_name,definition_supplement:definition_supplement , id:id },
                success: function (e) {
                    $.get("ajax/tanimlar/islemler/islemler-liste.php?islem=islem-grup-listesi", { },function(e){
                        $('#islem-grup-listesi').html(e);
                    });
                }
            });
        });

            $("body").off("click", ".islemgetir").on("click", ".islemgetir", function(e){


                var goster = $(this).attr('id');
            alertify.success("Veriyi getiriyorum lütfen bekleyiniz");
            $.get( "ajax/tanimlar/islemler/islemdetaygetir.php", { getir:goster },function(getveri){
                $('#islem-icerik-side').html(getveri);
            });
        });

            $("body").off("click", ".islemsil").on("click", ".islemsil", function(e){
            var goster = $(this).attr('id');
            var confirmtext = "silmek istediğinize emin misiniz?";
            if(confirm(confirmtext)) {
                $.get("ajax/tanimlar/islemler/islemsil.php", {getir: goster}, function (getveri) {
                    $('#modal-tanim-icerik').html(getveri);
                });
            }
        });

        $(".islemtanimlagetir").click(function(){
            var isleminidsi = $(this).attr('islem-id');
            $.get( "ajax/tanimlar/islemler/islemekle.php", { islemid:isleminidsi },function(getveri){
                $('#islemtanimlaicerik').html(getveri);
            });
        });

        $(".islemdetayiekle").click(function(){
            var isleminidsi = $(this).attr('islem-id');
            var eklenecekislemid = $(this).attr('eklenecekislemid');
            $.get( "ajax/tanimlar/islemler/islemdetayekle.php", { islemid:isleminidsi,eklenecekislemid:eklenecekislemid },function(getveri){
                $('#islemtanimlaicerik').html(getveri);
            });
        });

        $(".islemdetayiguncelle").click(function(){
            var isleminidsi = $(this).attr('islem-id');
            $.get( "ajax/tanimlar/islemler/islemdetayekle.php", { islemid:isleminidsi },function(getveri){
                $('#islemtanimlaicerik').html(getveri);
            });
        });

        $(document).on("click","#islemdetayidetaylifiyat",function() {
            var isleminidsi = $(this).attr('islem-id');
            $.get( "ajax/tanimlar/islemler/islemdetayfiyat.php", { islemid:isleminidsi },function(getveri){
                $('#islem-icerik').html(getveri);
            });
        });


    $(document).on('click', '.islem-grubu-sil', function () {
        var id = $(this).attr('id');
        alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
            "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kat Silme Nedeni..'></textarea>" +
            "<input class='form-control' hidden type='text' id='delete_datetime'>", function () {
            var delete_detail = $('#delete_detail').val();
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/islemler/islemler-sql.php?islem=islem-grup-sil',
                data: { id, delete_detail },
                success: function (e) {
                    $(".sonucyaz").html(e);

                    $.get("ajax/tanimlar/islemler/islemler-liste.php?islem=islem-grup-listesi", { },function(e){
                        $('#islem-grup-listesi').html(e);
                    });

                    $('.alertify').remove();
                }
            });
        }, function () {
            alertify.message('Silme İşleminden Vazgeçtiniz')
        }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme İşlemini Onayla"});
    });

    });
</script>