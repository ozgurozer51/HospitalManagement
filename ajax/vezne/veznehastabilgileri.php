<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

date_default_timezone_set('europe/istanbul');
$simdikitarih = date('y/m/d h:i:s');

$islem=$_GET['islem'];

if ($islem=="hastabilgi"){

    if($_POST['hastaid']!="") {
        $hasta = $_POST['hastaid'];
    }else {
        $hasta = $_GET['hastaid'];
    }

    ?>


    <form action="" method="get">
        <div class="row">
            <div class="col-lg-9">
                <h4 class="card-title">hastalar</h4>
            </div><!-- end col -->
            <div class="col-lg-2">
                <input type="number" class="form-control w-100"
                       placeholder="tc kimlik numarası giriniz."
                       name="protokolnovezne" id="basicpill-firstname-input">
            </div><!-- end col -->

            <div class="col-lg-1 mb-1">
                <input class="btn btn-success btn-sm justify-content-end tikla"
                       name="tchastagetir" type="submit"
                       value="getir"/>
            </div><!-- end col -->
        </div>
    </form>
    <table id="example"
           class="table  table-bordered table-sm table-hover "
           style=" background:white;width: 100%;">
        <thead>
        <tr>

            <th scope="col">tc kimlik no</th>
            <th scope="col">i̇şlem numarası</th>
            <th scope="col">müracaat tarihi</th>
            <th scope="col">adı soyadı</th>
            <th scope="col">birim</th>
            <th scope="col">sosyal güvence</th>
            <th scope="col">toplam borç tutar</th>

        </tr>
        </thead>
        <tbody>

        <?php $hastalistgetir = verilericoklucek("select hasta_kayit.*,hastalar.*,birimler.*,hasta_kayit.id as protokolid,hastalar.adi as adim from hasta_kayit  
                                                        inner join hastalar on hasta_kayit.tc_kimlik=hastalar.tc_kimlik 
                                                        inner join birimler on birimler.id=hasta_kayit.poliklinik_id where hasta_kayit.id=$hasta");

             foreach ($hastalistgetir as $rowa){ ?>
            <tr class="hastacard" data-id="<?php echo $rowa["protokolid"] ?>" >
                <td><?php echo $rowa["tc_kimlik"]?></td>
                <td><?php echo $rowa["protokolid"] ?></td>
                <td><?php echo $rowa["kayit_tarihi"] ?></td>
                <td><?php echo $rowa["adim"]." ".$rowa["soyadi"] ?></td>
                <td><?php echo $rowa["bolum_adi"] ?></td>
                <td><?php echo islemtanimgetirkod($rowa["sosyal_guvence"]) ?></td>
                <td><?php echo hastaborcsorgula($rowa["protokolid"] )?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
    <script type="text/javascript">
        $(document).ready(function(){

            $('.hastacard').click(function(){

                var odeid = $(this).data('id');
                var geri=document.queryselector(".geribtn")
                geri.style.visibility = 'visible';
                $.get( "ajax/vezneodemelistesi.php?islem=hastaodemelistesi", { odeid:odeid },function(getveri){
                    $('#hastabilgiclass').html(getveri);
                });
            });

            $(".btnhastabilgilendirme").on("click", function(){
                var hastaid=$(this).data('id');

                $.ajax({
                    type:'post',
                    url:'ajax/veznehastabilgileri.php?islem=hastabilgi',
                    data:{"hastaid":hastaid},
                    success:function(e){
                        $("#hastabilgiclass").html(e);
                    }
                });

            });

        });

    </script>

<?php }


?>