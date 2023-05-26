<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_antibiyotik_profili_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_antibiotic_profile",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Antibiyotik Profili Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Antibiyotik Profili Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_antibiyotik_profili_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $sql=direktguncelle("lab_antibiotic_profile", "id", $id, $_POST);

    if ($sql == 1){?>
        <script>
            alertify.success('Antibiyotik Profili Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Antibiyotik Profili Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_antibiyotik_profili_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_antibiotic_profile", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Antibiyotik Profili Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Antibiyotik Profili Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_antibiyotik_profili_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];

    $sql = backcancel('lab_antibiotic_profile','id',$id,$date,$user);
    if ($sql == 1) { ?>
        <script>
            alertify.success('Aktifleştirme İşlemi Başarılı');
        </script>

    <?php } else { ?>
        <script>
            alertify.error('Aktifleştirme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="listeyi-getir"){ ?>
    <style>
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
    <div id="antibiyotik-profili-tanim">
        <div class="col-12 row">
            <div class="col-5">
                <div class="card">
                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Antibiyotik Profili Tanımları</b></div>
                    <div class="card-body " style="height: 84vh;">

                        <script>
                            $('#antibiyotik-profili-datatb').DataTable( {
                                scrollY: '69vh',
                                scrollX: true,
                                "info":true,
                                "paging":false,
                                "searching":true,
                                "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                                "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                                    "<'row'<'col-sm-12'tr>>" +
                                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                                buttons: [
                                    {
                                        text: 'Antibiyotik Profili Tanımla',
                                        className: 'btn btn-success btn-sm btn-kaydet',

                                        action: function ( e, dt, button, config ) {

                                            $('.tanimlamalar_w40_h20').window('setTitle', 'Antibiyotik Profili Tanımla');
                                            $('.tanimlamalar_w40_h20').window('open');
                                            $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=modal-antibiyotik-profili-tanimla');
                                        }
                                    }
                                ],
                            } );
                        </script>

                        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="antibiyotik-profili-datatb">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Antibiyotik Profili Adı</th>
                                <th>Durumu</th>
                                <th>İşlem</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sql = verilericoklucek("select * from lab_antibiotic_profile");
                            foreach ((array)$sql as $row){ ?>
                                <tr style="cursor: pointer;" class="antibiyotik-profili-sec" id="<?php echo $row["id"];?>">
                                    <td><?php echo $row["id"];?></td>
                                    <td><?php echo mb_strtoupper($row["antibiotic_profile_name"]); ?> </td >
                                    <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                                    <td align="center">
                                        <i class="fa fa-pen-to-square antibiyotik_profili_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["id"];?>" data-bs-target="##lab_modal_lg" data-bs-toggle="modal"></i>
                                        <?php if($row['status']=='0'){ ?>
                                            <i class="fa-solid fa-recycle antibiyotik_profili_aktif" title="Aktif Et" id="<?php echo $row["id"];?>" alt="icon" ></i>
                                        <?php }else{ ?>
                                            <i class="fa fa-trash antibiyotik_profili_sil" title="İptal" id="<?php echo $row["id"];?>" alt="icon"></i>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <script>
                    $(".antibiyotik-profili-sec").click(function () {
                        $(this).css('background-color') != 'rgb(147,203,198)' ;
                        $('.antibiyotik-profili-sec-kaldir').removeClass("text-white");
                        $('.antibiyotik-profili-sec-kaldir').removeClass("antibiyotik-profili-sec-kaldir");
                        $('.antibiyotik-profili-sec').css({"background-color": "rgb(255, 255, 255)"});
                        $(this).css({"background-color": "rgb(147,203,198)"});
                        $(this).addClass("text-white antibiyotik-profili-sec-kaldir");

                        var getir=$('.antibiyotik-profili-sec-kaldir').attr('id');
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=tanimli_antibiyotik_liste",{ "getir":getir },function(getveri){
                            $('#tanimli_antibiyotikler').html(getveri);
                        });
                    });

                    $("body").off("click", ".antibiyotik_profili_duzenle").on("click", ".antibiyotik_profili_duzenle", function(e){
                        var getir = $(this).attr('id');


                        $('.tanimlamalar_w40_h20').window('setTitle', 'Antibiyotik Profili Tanımı Düzenle');
                        $('.tanimlamalar_w40_h20').window('open');
                        $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=modal-antibiyotik-profili-tanim-duzenle&getir='+ getir +'');
                    });

                    $("body").off("click", ".antibiyotik_profili_sil").on("click", ".antibiyotik_profili_sil", function(e){
                        var id = $(this).attr("id");

                        alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Antibiyotik Profili Tanımı Silme Nedeni..'></textarea>", function(){

                            var delete_detail = $('#delete_detail').val();
                            var delete_datetime = $('#delete_datetime').val();
                            if (delete_detail != '') {
                                $.ajax({
                                    type: 'POST',
                                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=sql_antibiyotik_profili_tanimi_sil',
                                    data: {
                                        id,
                                        delete_detail,
                                        delete_datetime
                                    },
                                    success: function (e) {
                                        $("#sonucyaz").html(e);
                                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=listeyi-getir",function(e){
                                            $('#antibiyotik-profili-tanim').html(e);
                                        });
                                        $('.alertify').remove();
                                    }
                                });
                            } else if (delete_detail == '') {
                                alertify.warning("Silme Nedeni Giriniz.");
                                setTimeout(() => {
                                    $(".antibiyotik_profili_sil").trigger("click");
                                }, "10")
                            }
                        }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Antibiyotik Profili Tanımı Silme İşlemini Onayla"});
                    });

                    $("body").off("click",".antibiyotik_profili_aktif").on("click",".antibiyotik_profili_aktif", function (e) {
                        var getir = $(this).attr('id');

                        $.ajax({
                            type:'POST',
                            url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=sql_antibiyotik_profili_tanimi_aktiflestir',
                            data:{getir},
                            success:function(e){
                                $('#sonucyaz').html(e);
                                $('#antibiyotik-profili-tanim:first').load("ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=listeyi-getir");
                            }
                        });
                    });
                </script>

            </div>

            <div class="col-7">
                <div id="tanimli_antibiyotikler">

                    <div class="warning-definitions mt-5">
                        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                            <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                            <p>Profile Tanımlı Antibiyotikleri Görmek İçin Profil Seçiniz.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php }

else if($islem=="modal-antibiyotik-profili-tanimla"){?>

    <div class="mx-3">
        <form id="antibiyotik_profili_tanimla_form" action="javascript:void(0);">

            <div class="col-12  mt-4 ">

                <div class="row">
                    <div class="col-4">
                        <label>Antibiyotik Profili Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="antibiotic_profile_name" id="antibiotic_profile_name">
                    </div>
                </div>

            </div>

        </form>



        <div class="mt-4 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_antibiyotik_profili_tanimla">Kaydet</a>
            </div>
        </div>
    </div>

    <script>
        $("body").off("click", "#btn_antibiyotik_profili_tanimla").on("click", "#btn_antibiyotik_profili_tanimla", function(e){

                var antibiyotik_profili_tanimla = $("#antibiyotik_profili_tanimla_form").serialize();

                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=sql_antibiyotik_profili_tanimi_ekle',
                    data:antibiyotik_profili_tanimla,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=listeyi-getir",function(e){
                            $('#antibiyotik-profili-tanim').html(e);
                        });

                        $('.tanimlamalar_w40_h20').window('close');
                    }
                });
            });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h20').window('close');
        });

    </script>

<?php }

else if($islem=="modal-antibiyotik-profili-tanim-duzenle"){
    $bilesik_id = $_GET['getir'];
    $lab_antibiyotik_profili=singularactive("lab_antibiotic_profile","id",$bilesik_id);?>




    <div class="mx-3">
        <form id="antibiyotik_profili_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" value="<?php echo $lab_antibiyotik_profili["id"];?>">

            <div class="col-12  mt-4 ">

                <div class="row">
                    <div class="col-4">
                        <label>Antibiyotik Profili Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="antibiotic_profile_name" value="<?php echo mb_strtoupper($lab_antibiyotik_profili["antibiotic_profile_name"]);?>">
                    </div>
                </div>

            </div>

        </form>



        <div class="mt-4 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-antibiyotik-profili-duzenle">Kaydet</a>
            </div>
        </div>
    </div>



    <script>

        $("body").off("click", "#btn-antibiyotik-profili-duzenle").on("click", "#btn-antibiyotik-profili-duzenle", function(e){
            var antibiyotik_profili_duzenle_form = $("#antibiyotik_profili_duzenle_form").serialize();
                console.log(antibiyotik_profili_duzenle_form);

            $("body").off("click", "#btn-antibiyotik-profili-duzenle").on("click", "#btn-antibiyotik-profili-duzenle", function(e){
                var antibiyotik_profili_duzenle_form = $("#antibiyotik_profili_duzenle_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=sql_antibiyotik_profili_tanimi_duzenle',
                    data:antibiyotik_profili_duzenle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla.php?islem=listeyi-getir",function(e){
                            $('#antibiyotik-profili-tanim').html(e);
                        });
                        $('.tanimlamalar_w40_h20').window('close');
                    }
                });
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h20').window('close');
        });

    </script>
<?php }

else if($islem=="tanimli_antibiyotik_liste"){
    $gelen_veri = $_GET['getir'];?>

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tanımlı Antibiyotikler</b></div>
        <div class="card-body " style="height: 84vh;">

            <script>
                $(document).ready(function(){
                    $('#tanimli_antibiyotik_datatb').DataTable( {
                        scrollY: '69vh',
                        scrollX: true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });
                });
            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tanimli_antibiyotik_datatb">
                <thead>
                <tr>
                    <th>Kodu</th>
                    <th>Antibiyotik Adı</th>
                    <th>Açıklama</th>
                    <th>Rapor Sırası</th>
                    <th>Durumu</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_antibiotic_definition where lab_antibiotic_definition.antibiotic_profile=$gelen_veri");
                foreach ((array)$sql as $row){ ?>
                    <tr style="cursor: pointer;">
                        <td><?php echo mb_strtoupper($row["antibiotic_code"]); ?> </td >
                        <td><?php echo mb_strtoupper($row["antibiotic_name"]); ?> </td >
                        <td><?php echo mb_strtoupper($row["antibiotic_description"]); ?> </td >
                        <td><?php echo mb_strtoupper($row["order_of_report"]); ?> </td >
                        <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php }?>