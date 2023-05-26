<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];

session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
?>

<div class="col-xl-12">
    <div class="row">
        <div class="col-md-12" id="tahsilatlist">
            <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabcontent">
                <div class="row">


                    <form action="" method="get">
                        <div class="row">
                            <div class="col-lg-9">
                                <h4 class="card-title"></h4>
                            </div><!-- end col -->
                            <div class="col-lg-2">
                                <input type="number" class="form-control w-100" placeholder="tc kimlik numarası giriniz." name="protokolnovezne" id="basicpill-firstname-input">
                            </div><!-- end col -->
                            <div class="col-lg-1 mb-2"><input class="btn btn-success btn-sm justify-content-end tikla" name="tchastagetir" type="submit" value="getir"/>
                            </div><!-- end col -->
                        </div>
                    </form>

                </div>

            </div>
        </div><!--  end col -->
    </div><!-- end row -->
</div>

                 <div class="card mt-3">
                     <div class="card-header bg-primary text-white p-2">vezne tahsilat listesi</div>
                     <div class="card-body">
                    <table id="vezne-tahsilatlar" class="table  table-bordered table-sm table-hover table-responsive mt-3"
                           style=" background:white;width: 100%;" >
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>işlem adı</th>
                            <th>miktar</th>
                            <th>fiyat</th>
                            <th>tutar</th>
                            <th>durum</th>
                            <th>doktor</th>
                            <th>kasa</th>
                            <th>veznedar</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php  $hastabilgitc=tekil("hasta_kayit","tc_kimlik",$_GET['protokolnovezne']);
                        if (isset($hastabilgitc['tc_kimlik'])){
                            $tckimlik =$hastabilgitc['id'];
                        }else{
                            $tckimlik =$_GET['protokolnovezne'];
                        }

                        $hastalistgetir = verilericoklucek("select vezne.id as vezne_id , vezne.* , kullanicilar.name_surname from vezne 
                                          inner join kullanicilar on kullanicilar.id = vezne.veznedar 
                                          where vezne.durum='1'");
                            foreach ($hastalistgetir as $rowa){

                            $idler=$rowa['hasta_hizmet_kodu'];
                            $dizi = explode (",",$idler);

                            for ($i =0; $i < count($dizi); $i++) {
                                $row=tekil("hasta_istemleri","id","$dizi[$i]");

                                ?>

                                <tr class="makbuzclick" id="<?php echo $dizi[$i]; ?>"  <?php if ($rowa['islem_tipi']=='cikan') { ?> style="background: aqua; pointer-events: none"; <?php  }elseif ($row['durum']=='2'){ ?> style="background:darkgrey; pointer-events: none";   <?php  } else{ ?>style="background:lightpink";<?php  } ?> data-toggle="modal" data-target="#makbuziptl">
                                    <td><?php echo $row["vezne_id"]; ?></td>
                                    <td><?php echo $row["adi"]; ?></td>
                                    <td><?php echo $row["adet"]; ?></td>
                                    <td><?php echo $row["ucret"]; ?></td>
                                    <td><?php echo $row['adet']*$row["ucret"]; ?></td>
                                    <td> <?php if ($rowa["durum"] == 1) { ?> <i class="fas fa-check-circle" style="color:green;"> </i> <?php } else { ?><i class="fas fa-times-circle" style="color:red;"></i><?php } ?></td>
                                    <td><?php echo $dr=kullanicigetir($row["doktor_id"]); echo $dr; ?></td>
                                    <td><?php  $kasagtr=kasagetirid($rowa["kasa_id"]); echo  $kasagtr ?></td>
                                    <td><?php echo $rowa["name_surname"]; ?></td>
                                </tr>

                            <?php } } ?>

                        </tbody>
                    </table>
                 </div>
                 </div>


                    <script>
                        $(document).ready(function () {

                            $('#vezne-tahsilatlar').datatable({
                                "responsive":true,
                                "pagelength": 50,
                                "paging":true,

                                dom: "<'row'<'col-sm-12 col-md-6'lb><'col-sm-12 col-md-6'f>>" +
                                     "<'row'<'col-sm-12'tr>>" +
                                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                                buttons: [
                                    { extend: "copy",  classname: "btn btn-primary" ,  text: 'kopyala' },
                                    { extend: "excel", classname: "btn btn-primary" ,  titleattr: 'exel fromatında i̇ndir...'},
                                    { extend: "print", classname: "btn btn-primary" ,  text:'yazdır'},
                                    { extend: "pdf",   classname: "btn btn-primary"},
                                    { extend: "colvis", text: "sütun görünürlüğü" },
                                         ],

                                initcomplete: function () {
                                    var btns = $('.dt-button');
                                    btns.addclass('btn');
                                    btns.removeclass('dt-button');
                                },
                                "language": {
                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                },

                            });

                            $('.makbuzclick').on('click', function () {
                                var makid =$(this).attr('id');

                                $.ajax({
                                    type: "post",
                                    url: "ajax/vezne/araislemler.php?islem=makbuzbilgi",
                                    data: {"makid": makid},
                                    success: function (e) {
                                        $("#makbuzbody").html(e);
                                    }
                                })
                            });

                            $('.makbuzclick').on('click', function () {
                                var deg=document.getelementbyid("istemid").value;

                                $.ajax({
                                    type: "post",
                                    url: "ajax/vezne/araislemler.php?islem=maknogetir",
                                    data: {"deg": deg},
                                    success: function (e) {
                                        $("#makbuznumarasi").html(e);
                                    }
                                })
                            });
                        });
                    </script>

