

<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

date_default_timezone_set('europe/istanbul');
$simdikitarih = date('y/m/d h:i:s');

$islem=$_GET['islem'];

if ($islem=="firmateminatinsert"){

    $_POST["kasadetay"]["ekleyen_kodu"]=$_POST["firmateminat"]["veznedar"];
    $_POST["kasadetay"]["kayit_zamani"]=$simdikitarih;
    $_POST["kasadetay"]["islem_tipi"]=giren;

    $kayitolustur = groupdirektekle("kasa_detay", $_POST['kasadetay'], "", "kasadetay");

    if($kayitolustur==1){
        $_POST["firmateminat"]["kasa_detay_id"]=islemtanimsoneklenen("kasa_detay");
        $kayitolustur1 = groupdirektekle("firma_teminat", $_POST['firmateminat'], "", "firmateminat");

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
}elseif ($islem=="firmateminattablo"){ ?>
    <table id="example"
           class="table  table-bordered table-sm table-hover "
           style=" background:white;width: 100%;">
        <thead>
        <tr>
            <th scope="col">seri no</th>
            <th scope="col">makbuz no</th>
            <th scope="col">firma</th>
        </tr>
        </thead>
        <tbody>


        <?php

        $hastalistgetir = "select * from firma_teminat where durum='1' order by id asc ";

       $hello = verilericoklucek($hastalistgetir);
        foreach ($hello as $rowa) {

            ?>

            <tr class="firmasilmetr" data-toggle="modal" data-target="#firmaiptal" data-id="<?php echo $rowa["id"] ?>">

                <td><?php echo $rowa["id"] ?></td>
                <td><?php echo $rowa["makbuz_numarasi"] ?></td>
                <td><?php $firma=tekil("firmalar","id","{$rowa["firma_kodu"]}");
                    echo $firma["firma_adi"]; ?></td>

            </tr>

        <?php } ?>

        </tbody>
    </table>
    <script>
        $(document).ready(function(){
            $(".firmasilmetr").on("click", function(){
                var hastaid=$(this).data('id');

                $.ajax({
                    type:'post',
                    url:'ajax/veznefirmateminat.php?islem=firmateminatbody',
                    data:{"hastaid":hastaid},
                    success:function(e){
                        $("#firmabody").html(e);
                    }
                });

            });
        });

    </script>

    <div class="modal fade" id="firmaiptal" tabindex="-1" role="dialog" aria-labelledby="odemeal" aria-hidden="true">
        <div class="modal-dialog " id="firmabody">
            <!-- modal content-->

        </div>
    </div>
<?php }elseif ($islem=="firmateminatbody"){ ?>

    <form  action="javascript:void(0);"  id="formteminatsil" >
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
                    <input class="form-control" name="id" type="text" value="<?php echo $_POST['hastaid']; ?>">
                </div>
                <div class="mb-3">
                    <label for="basicpill-firstname-input" class="form-label">neden silmek istediğinizi açıklar mısınız ?</label>
                    <textarea class="form-control" name="silme_detay" ></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">kapat</button>
                <input class="btn btn-success w-md justify-content-end firmatemsil " style="margin-bottom:4px" data-id="<?php echo $hastaid; ?>" type="submit" data-dismiss="modal" value="kaydet"/>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function(){
            $(".firmatemsil").on("click", function(){
                var gonderilenform = $("#formteminatsil").serialize();
                document.getelementbyid("formteminatsil").reset();

                $.ajax({
                    type:'post',
                    url:'ajax/veznefirmateminat.php?islem=firmadel',
                    data:gonderilenform,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        alertify.message( 'iptal işlemi başarılı');

                        $.get( "ajax/veznefirmateminat.php?islem=firmateminattablo", { },function(e){
                            $('#firmnatablo').html(e);

                        });
                    }
                });
            });
        });
    </script>
<?php }

elseif ($islem=="firmadel"){
    if ($_POST) {
        $id=$_POST['id'];
        $avansdetay=$_POST['silme_detay'];
        var_dump($_POST);
        $hastaavans =silmedetay("firma_teminat","id","$id","$avansdetay");

        if ($hastaavans==1){

            $kasadetayid=$babakalim=tekil("firma_teminat","id","$id");
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

?>




