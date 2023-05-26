<?php

include "../../controller/fonksiyonlar.php";

$baglanti = veritabanibaglantisi();
session_start();
ob_start();

$sosyalguvence=$_GET["sosyalguvence"];

$sosyalguvencegetir= tek("select * from transaction_definitions where definition_code='$sosyalguvence' and status!='2'");

$sosyal_guvence_adi=$sosyalguvencegetir["tanim_adi"];
$tanim_kodu=$_POST["tanim_kodu"];
$tanim_adi=$_POST["tanim_adi"];
if($tanim_kodu!="")
{


    $sorguvarmi= tek("select * from transaction_definitions where definition_code='$tanim_kodu' and status!='2'");
if($sorguvarmi){
echo "0";
exit();
}else{
        $_POST["status"] = 1;
        direktekle("transaction_definitions", $_POST);
}
}

if($_GET["sil"]!="")
{
    $silmeislem= silme("transaction_definitions","id",$_GET["sil"]);  ?>

    <script>  alertify.message("silme işlemi başarılı");</script>
<?php } ?>

<div class="modal-dialog" style=" width: 90%; max-width: 95%; ">

    <!-- modal content-->
    <form class="modal-content"  action="doktortanimla" method="post" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>

            <h4 class="modal-title"><?php echo $sosyal_guvence_adi; ?> bağlı kurumlar</h4>

        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-12 row">
                    <div align="right">
                        <button  id="<?php echo $sosyalguvence; ?>"  type="button" class="btn btn-success waves-effect waves-light w-sm kurumekle">kurum ekle</button>
                    </div>
                    <br/>
                    <br/>
                    <div id='islemsonuc'>
                        <table   id="exampleses" class="table table-striped table-bordered" style=" background:white;width: 100%;">
                            <thead>
                            <tr>
                                <th>id   </th>
                                <th>kurum kodu</th>
                                <th>kurum adı</th>
                                <th>i̇şlem</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "select * from transaction_definitions where definition_type='SOSYALGUVENCE' and status!='2' and definition_supplement='$sosyalguvence'";
                            $hello=verilericoklucek($sql);
                            foreach ($hello as $row) {
                                $yetkibilgisi=singular("authority_definition","id",$row["yetki_id"]);
                                $yetkiveren=singular("users","id",$row["yetki_veren"]);  ?>

                                <tr>
                                    <td><?php echo $row["id"]; ?></td>
                                    <td><?php echo  $row["definition_code"] ?></td>
                                    <td><?php echo  $row["definition_name"] ?></td>
                                    <td><button  sosyalguvence="<?php echo $sosyalguvence; ?>"  id='<?php echo $row["id"]; ?>' style=" line-height: 1px;" type="button" class="btn  btn-danger waves-effect waves-light islemsonsil"><i class="bx bx-block font-size-16 align-middle me-2"></i></button></td>
                                </tr>

                            <?php } ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">kapat</button>
        </div>
    </form>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".islemsonsil").click(function(){
            var goster = $(this).attr('id');
            var sosyalguvence = $(this).attr('sosyalguvence');
            var confirmtext = "silmek istediğinize emin misiniz?";
            if(confirm(confirmtext)) {
                $.get("ajax/tanimlar/kurumdetaygetir.php", {sil: goster, sosyalguvence:sosyalguvence }, function (getveri) {
                    $('#kurumbilgiicerik').html(getveri);
                });
            }
        });

        $('#exampleses').DataTable({
            "scrolly": true,
            "scrollx": true
        });

        $(".kurumekle").click(function(){
            var goster = $(this).attr('id');
            $.get( "ajax/tanimlar/kurumekle.php", { tanim_kodu:goster },function(getveri){
                $('#islemsonuc').html(getveri);
            });
        });

    });
</script>
