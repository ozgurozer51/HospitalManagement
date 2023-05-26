
<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

date_default_timezone_set('europe/istanbul');
$simdikitarih = date('y/m/d h:i:s');

$islem=$_GET['islem'];

if ($islem=="hastaodemelistesi"){
       $hastaid=$_GET['odeid'];?>

    <?php 
    include "../view/head.php";

    ?>
    <div class="card ">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="card-title">hizmet/malzeme listesi</h4>
                </div>
            </div>
        </div>

        <div class="card-body " id="veznelist"   >

            <!--html tables with student data-->
            <table id="tableid" class="table table-bordered border-primary mb-0" style="width:100%">
                <thead>
                <tr>
                    <th>işlem no</th>
                    <th>protok. no</th>
                    <th>hizmet</th>
                    <th>gelir kodu</th>
                    <th>gelir adı</th>
                    <th>miktar</th>
                    <th>fiyat</th>
                    <th>t. tutar</th>
                    <th>ödeme durum</th>
                    <th>m.det. turu</th>
                    <th>doktor</th>
                    <th>personel</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $hastalistgetir = "select * from hasta_istemleri where protokol_no='$hastaid' and durum!='2'";

                $stida = oci_parse($baglanti, $hastalistgetir);
                oci_execute($stida);
                while (($rowa = oci_fetch_array($stida, oci_assoc)) != false) {

                    $toplam1 = $rowa["adet"] * $rowa["ucret"];
                if ($rowa["odeme_yapildi"] == 0) {
                    $nettoplam = $nettoplam + $toplam1;
                }
                ?>
                    <tr  <?php if ($rowa["odeme_yapildi"] == 1) { ?>
                        style="pointer-events: none; ";
                    <?php } ?> >
                        <td><?php echo $rowa["id"]; ?></td>
                        <td><?php echo $rowa["protokol_no"]; ?></td>
                        <td><?php echo $rowa["adi"]; ?></td>
                        <td></td>
                        <td><?php echo $rowa["name_surname"] ?></td>
                        <td><?php echo $rowa["adet"] ?></td>
                        <td><?php echo $rowa["ucret"] ?> ₺</td>
                        <td><?php
                            echo $toplam1;
                            ?></td>
                        <td>
                            <?php if ($rowa["odeme_yapildi"] == 1) { ?>
                                <center><i class="fas fa-check-circle"
                                           style="color:green;">ödendi</i></center>
                            <?php } else { ?>

                                <center><i class="fas fa-times-circle"
                                           style="color:red;">ödenmedi</i></center>
                            <?php } ?>

                        </td>
                        <td><?php echo $rowa["bolum_adi"] ?>rht</td>
                        <td><?php echo $rowa["doktor_id"] ?></td>
                        <td><?php echo $rowa["istemi_yapan_kullanici_id"] ?></td>
                    </tr>

                <?php } ?>

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right"><b>ara tutar</b></td>
                    <td colspan="2" id=""><b id="resultid"></b> ₺</td>
                    <td colspan="2"  style="text-align: right"><b>toplam tutar</b></td>
                    <td colspan="2"><h4><? echo $nettoplam; ?> ₺</h4> </td>
                </tr>
                </tfoot>
            </table>
            <br />
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-success btn-sm "
                        data-toggle="modal" data-target="#odemeal" id="odemeler">
                    <i class="fas fa-book px-1 odemak"></i>ödeme al
                </button>
            </div>

            <script>
                // initialization of datatable
                $(document).ready(function () {
                    var studenttable = $('#tableid').datatable(  );

                    // activate the 'selected' class
                    // on clicking the rows
                    $('#tableid tbody').on(
                        'click', 'tr', function () {
                            $(this).toggleclass('selected');
                        });

                    $('#tableid tbody').click(function () {

                        // display the total row count
                        // on clicking the button
                        var totalcount
                            = studenttable.rows('.selected')
                            .data();
                        // $("#resultid").show().html(
                        //     "<br /><b>user clicked </b> "
                        //     + totalcount['0']['4'] + ' rows ');
                        const  say=studenttable.rows('.selected').data().length;

                        if(say>0){
                            console.log(totalcount['0']);
                            var bilgiler=0;
                            var id;
                            var araode;
                            var hizmetkodu=[];
                            var hztkod;
                            var hztkd;
                            var araid;
                            var idal=[];
                            for (let i = 0; i <= say; i++) {
                                bilgiler+=parseint(totalcount[i][6]);
                                id=parseint(totalcount[i][0]);
                                hztkd=totalcount[i][1];
                                hizmetkodu.push(hztkd);
                                idal.push(id);
                                console.log(bilgiler);
                                console.log(idal);

                                document.getelementbyid("resultid").innerhtml=bilgiler;
                                document.getelementbyid("araode").innerhtml=bilgiler;
                                hztkod=document.getelementbyid("hizmetkod");
                                hztkod.value=hizmetkodu;

                                araode=document.getelementbyid("araode1");
                                araode.value=bilgiler;
                                araid=document.getelementbyid("idal");
                                araid.value=idal;
                            }
                            document.getelementbyid("idal").value = idal;


                        }else{
                            document.getelementbyid("resultid").innerhtml=0;
                            document.getelementbyid("araode").innerhtml=0;
                            document.getelementbyid("araode1").innerhtml=0;
                        }



                    });
                });

                $(document).ready(function () {
                    $('#odemeler').on('click', function () {

                        //$('#kasaduzenle').modal('show');
                        var deg=document.getelementbyid("istemid").value;
                        $.ajax({
                            type: "post",
                            url: "ajax/araislemler.php?islem=veznemodall",
                            data: {"deg": deg},
                            success: function (e) {
                                $("#toplamtutar").html(e);
                            }
                        })
                    });
                    $('#odemeler').on('click', function () {
                        var deg=document.getelementbyid("istemid").value;

                        $.ajax({
                            type: "post",
                            url: "ajax/araislemler.php?islem=maknogetir",
                            data: {"deg": deg},
                            success: function (e) {
                                $("#makbuznogetir").html(e);
                            }
                        })
                    });



                });
            </script>
        </div>

    </div>

<?php } ?>





