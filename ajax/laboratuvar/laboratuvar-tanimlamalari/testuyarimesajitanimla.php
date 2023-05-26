<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_test_uyari_mesaji_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_test_warning_message",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Test Uyarı Mesajı Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_test_uyari_mesaji_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_test_warning_message", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Test Uyarı Mesajı Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_test_uyari_mesaji_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_test_warning_message", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Test Uyarı Mesajı Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_test_uyari_mesaji_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_test_warning_message','id',$id,$date,$user);

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

else if ($islem=="sql_test_uyari_mesaji_tetkik_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_test_warning_message_analysis",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Test Uyarı Mesajı Tetkik Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Tetkik Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_test_uyari_mesaji_tetkik_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_test_warning_message_analysis", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Test Uyarı Mesajı Tetkik Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Tetkik Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_test_uyari_mesaji_tetkik_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_test_warning_message_analysis", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Test Uyarı Mesajı Tetkik Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Tetkik Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_test_uyari_mesaji_tetkik_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_test_warning_message_analysis','id',$id,$date,$user);

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

else if ($islem=="sql_test_uyari_mesaji_kullanici_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_test_warning_message_user",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Test Uyarı Mesajına Kullanıcı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajına Kullanıcı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_test_uyari_mesaji_kullanici_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_test_warning_message_user", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Test Uyarı Mesajı Kullanıcı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Kullanıcı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_test_uyari_mesaji_kullanici_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_test_warning_message_user", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Test Uyarı Mesajı Kullanıcı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Uyarı Mesajı Kullanıcı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_test_uyari_mesaji_kullanici_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_test_warning_message_user','id',$id,$date,$user);

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

        
    <div class="row">

        <div class="col-6">
           <div id="test-uyari-mesaji-liste"></div>
            <script>
                $(document).ready(function(){
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test-uyari-mesaji-listesi",function(getveri){
                        $('#test-uyari-mesaji-liste').html(getveri);
                    });
                });
            </script>

        </div>

        <div class="col-6">
            <div class="row" id="test-uyarı-kullanici-tetkik-tanim">

                <div class="warning-definitions mt-5">
                    <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                        <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                        <p>İhlal Tanımı Yapmak İçin Tetkik Seçiniz.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

<?php } 

else if($islem=="test-uyari-mesaji-listesi"){?>

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Test Uyarı Mesajarı</b></div>
        <div class="card-body " style="height: 84vh;">

            <script>
                $('#uyari-mesaji-datatb').DataTable( {
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
                            text: 'U. Mesajı Tanımla',
                            className: 'btn btn-success btn-sm btn-kaydet mx-1',

                            action: function ( e, dt, button, config ) {

                                $('.tanimlamalar_w40_h35').window('setTitle', 'Uyarı Mesajı Tanımla');
                                $('.tanimlamalar_w40_h35').window('open');
                                $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=modal-uyari-mesaji-tanimla');
                            }
                        },

                        {
                            text: 'Tetkik Tanımla',
                            className: 'btn btn-success btn-sm btn-uyari-mesaji-tetkik-tanimla mx-1',
                            attr:  {
                                'disabled':true,
                                'title':"Uyarı Mesajına Tetkik Tanımla",
                            },
                            action: function ( e, dt, button, config ) {
                                var getir=$('.uyari-mesaji-sec-kaldir').attr('id');

                                $('.tanimlamalar_w40_h45').window('setTitle', 'Test Uyarı Mesajı Tetkik Tanımla');
                                $('.tanimlamalar_w40_h45').window('open');
                                $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=modal_test_uyari_mesaji_tetkik_tanimla&getir=' + getir + '');
                            }
                        },

                        {
                            text: 'Kullanıcı Tanımla',
                            className: 'btn btn-success btn-sm btn-uyari-mesaji-kullanici-tanimla mx-1',
                            attr:  {
                                'disabled':true,
                                'title':"Uyarı Mesajına Kullanıcı Tanımla",
                            },
                            action: function ( e, dt, button, config ) {
                                var getir=$('.uyari-mesaji-sec-kaldir').attr('id');

                                $('.tanimlamalar_w40_h45').window('setTitle', 'Uyarı Mesajına Kullanıcı Tanımla');
                                $('.tanimlamalar_w40_h45').window('open');
                                $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=modal_test_uyari_mesaji_kullanici_tanimla&getir=' + getir + '');
                            }
                        },
                    ],
                });
            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="uyari-mesaji-datatb">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Uyarı Adı</th>
                    <th>Uyarı Mesajı</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_test_warning_message");
                foreach ((array)$sql as $rowa ) {?>
                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="uyari-mesaji-sec">
                        <td><?php echo $rowa["id"]; ?></td>
                        <td><?php echo $rowa["warning_name"]; ?></td>
                        <td><?php echo $rowa["warning_message"]; ?></td>
                        <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-circle-info uyari_mesaji_tanim_detay" title="Detay" alt="icon" id="<?php echo $rowa["id"]; ?>"></i>

                            <i class="fa fa-pen-to-square uyari_mesaji_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $rowa["id"]; ?>"></i>

                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle uyari_mesaji_tanim_aktif" title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash uyari_mesaji_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

    <script>
        $(".uyari-mesaji-sec").click(function () {
            $('.btn-uyari-mesaji-kullanici-tanimla').attr('disabled', false);
            $('.btn-uyari-mesaji-tetkik-tanimla').attr('disabled', false);

            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.uyari-mesaji-sec-kaldir').removeClass("text-white");
            $('.uyari-mesaji-sec-kaldir').removeClass("uyari-mesaji-sec-kaldir");
            $('.uyari-mesaji-sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white uyari-mesaji-sec-kaldir");

            var getir=$('.uyari-mesaji-sec-kaldir').attr('id');
            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":getir },function(getveri){
                $('#test-uyarı-kullanici-tetkik-tanim').html(getveri);
            });

        });
    </script>


    <script>
        $("body").off("click", ".uyari_mesaji_tanim_detay").on("click", ".uyari_mesaji_tanim_detay", function(e){
            var getir = $(this).attr('id');

            $('.tanimlamalar_w40_h35').window('setTitle', 'Test Uyarı Mesajı');
            $('.tanimlamalar_w40_h35').window('open');
            $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=modal-test-uyari-mesaji-detay&getir='+ getir +'');
        });

        $("body").off("click", ".uyari_mesaji_tanim_duzenle").on("click", ".uyari_mesaji_tanim_duzenle", function(e){
            var getir = $(this).attr('id');
            $('.tanimlamalar_w40_h35').window('setTitle', 'Uyarı Mesajı Düzenle');
            $('.tanimlamalar_w40_h35').window('open');
            $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=modal-test-uyari-mesaji-duzenle&getir='+ getir +'');
        });

        $("body").off("click", ".uyari_mesaji_tanim_sil").on("click", ".uyari_mesaji_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Test Uyarı Mesajı Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test-uyari-mesaji-listesi",function(e){
                                $('#test-uyari-mesaji-liste').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".uyari_mesaji_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Test Uyarı Mesajı Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".uyari_mesaji_tanim_aktif").on("click",".uyari_mesaji_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('#test-uyari-mesaji-liste:first').load("ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test-uyari-mesaji-listesi");
                }
            });
        });
    </script>
<?php }

else if($islem=="modal-uyari-mesaji-tanimla"){?>

        <div class="mt-3 mx-2">

            <form id="test_uyari_mesaji_form" action="javascript:void(0);">

                <div class="row">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Uyarı Adı</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-sm" name="warning_name" id="warning_name">
                        </div>
                        <label class="col-sm-2 col-form-label">Uyarı Aktif</label>
                        <select name="status" id="status" class="col-sm-2 col-form-select ">
                            <option selected disabled class="text-white bg-danger">Uyarı durumu seçiniz.</option>
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3 ">
                    <div class="col-4">
                        <input type="checkbox" class="col-md-3" name="show_accept_transaction" value="1">İstem Kabul
                    </div>
                    <div class="col-4">
                        <input type="checkbox" class="col-md-3" name="show_result_confirm" value="1">Sonuç Onay
                    </div>
                    <div class="col-4">
                        <input type="checkbox" class="col-md-3" name="show_microbiology" value="1">Mikro Biyoloji
                    </div>

                </div>

                <div class="row mt-2 ">
                    <div class="col-4">
                        <input type="checkbox" class="col-md-3" name="show_sampling" value="1">Numune Alma
                    </div>

                    <div class="col-4">
                        <input type="checkbox" class="col-md-3" name="show_sample_acceptance" value="1">Numune Kabul
                    </div>

                    <div class="col-4">
                        <input type="checkbox" class="col-md-3" name="show_results" value="1">Sonuç Verme
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Mesaj</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="warning_message"></textarea>
                        </div>
                    </div>
                </div>

            </form>

            <div class="row mt-5" align="right">
                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-uyari-mesaji-ekle">Kaydet</a>
                </div>
            </div>

        </div>


   

    <script>

        $("body").off("click", "#btn-test-uyari-mesaji-ekle").on("click", "#btn-test-uyari-mesaji-ekle", function(e){
            var test_uyari_mesaji_form = $("#test_uyari_mesaji_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_ekle',
                data:test_uyari_mesaji_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h35').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test-uyari-mesaji-listesi",function(e){
                        $('#test-uyari-mesaji-liste').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });

    </script>
<?php }

else if($islem=="modal-test-uyari-mesaji-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_test_uyari_mesaj=singularactive("lab_test_warning_message","id",$gelen_veri);?>

    <div class="mt-3 mx-2">

        <form id="test_uyari_mesaji_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_test_uyari_mesaj["id"];?>">

            <div class="row">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Uyarı Adı</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" name="warning_name" id="warning_name" value="<?php echo $lab_test_uyari_mesaj["warning_name"];?>">
                    </div>
                    <label class="col-sm-2 col-form-label">Uyarı Aktif</label>
                    <select name="status" id="status" class="col-sm-2 col-form-select form-select-sm ">
                        <option selected disabled class="text-white bg-danger">Uyarı durumu seçiniz.</option>
                        <option value="1"  <?php if ($lab_test_uyari_mesaj["status"] == 1) {echo "selected";} ?>>Aktif</option>
                        <option value="0"  <?php if ($lab_test_uyari_mesaj["status"] == 0) {echo "selected";} ?>>Pasif</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3 ">
                <div class="col-4">
                    <input type="checkbox" class="col-md-3" name="show_accept_transaction" value="1" <?php if($lab_test_uyari_mesaj['show_accept_transaction']==1){ ?> checked <?php } ?>>İstem Kabul
                </div>
                <div class="col-4">
                    <input type="checkbox" class="col-md-3" name="show_result_confirm" value="1" <?php if($lab_test_uyari_mesaj['show_result_confirm']==1){ ?> checked <?php } ?>>Sonuç Onay
                </div>
                <div class="col-4">
                    <input type="checkbox" class="col-md-3" name="show_microbiology" value="1" <?php if($lab_test_uyari_mesaj['show_microbiology']==1){ ?> checked <?php } ?>>Mikro Biyoloji
                </div>

            </div>

            <div class="row mt-2 ">
                <div class="col-4">
                    <input type="checkbox" class="col-md-3" name="show_sampling" value="1" <?php if($lab_test_uyari_mesaj['show_sampling']==1){ ?> checked <?php } ?>>Numune Alma
                </div>

                <div class="col-4">
                    <input type="checkbox" class="col-md-3" name="show_sample_acceptance" value="1" <?php if($lab_test_uyari_mesaj['show_sample_acceptance']==1){ ?> checked <?php } ?>>Numune Kabul
                </div>

                <div class="col-4">
                    <input type="checkbox" class="col-md-3" name="show_results" value="1" <?php if($lab_test_uyari_mesaj['show_results']==1){ ?> checked <?php } ?>>Sonuç Verme
                </div>
            </div>

            <div class="row mt-2">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Mesaj</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="warning_message"><?php echo $lab_test_uyari_mesaj["warning_message"];?></textarea>
                    </div>
                </div>
            </div>

        </form>

        <div class="row mt-5" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-uyari-mesaji-duzenle">Kaydet</a>
            </div>
        </div>

    </div>

    <script>

        $("body").off("click", "#btn-test-uyari-mesaji-duzenle").on("click", "#btn-test-uyari-mesaji-duzenle", function(e){
            var test_uyari_mesaji_duzenle_form = $("#test_uyari_mesaji_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_duzenle',
                data:test_uyari_mesaji_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h35').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test-uyari-mesaji-listesi",function(e){
                        $('#test-uyari-mesaji-liste').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });

    </script>
<?php }

else if($islem=="modal-test-uyari-mesaji-detay"){
    $gelen_veri = $_GET['getir'];
    $lab_test_uyari_mesaj=singularactive("lab_test_warning_message","id",$gelen_veri);?>

    <div class="mt-4 mx-2">

        <div class="row">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Uyarı Adı</label>
                <div class="col-sm-6">
                    <input type="text" disabled class="form-control" name="warning_name" id="warning_name" value="<?php echo $lab_test_uyari_mesaj["warning_name"];?>">
                </div>
                <label class="col-sm-2 col-form-label">Uyarı Aktif</label>
                <select disabled name="status" id="status" class="col-sm-2 col-form-select form-select-ms ">
                    <option selected disabled class="text-white bg-danger">Uyarı durumu seçiniz.</option>
                    <option value="1"  <?php if ($lab_test_uyari_mesaj["status"] == 1) {echo "selected";} ?>>Aktif</option>
                    <option value="0"  <?php if ($lab_test_uyari_mesaj["status"] == 0) {echo "selected";} ?>>Pasif</option>
                </select>
            </div>
        </div>

        <div class="row mt-3 ">
            <div class="col-4">
                <input disabled type="checkbox" class="col-md-3" name="show_accept_transaction" value="1" <?php if($lab_test_uyari_mesaj['show_accept_transaction']==1){ ?> checked <?php } ?>>İstem Kabul
            </div>
            <div class="col-4">
                <input disabled type="checkbox" class="col-md-3" name="show_result_confirm" value="1" <?php if($lab_test_uyari_mesaj['show_result_confirm']==1){ ?> checked <?php } ?>>Sonuç Onay
            </div>
            <div class="col-4">
                <input disabled type="checkbox" class="col-md-3" name="show_microbiology" value="1" <?php if($lab_test_uyari_mesaj['show_microbiology']==1){ ?> checked <?php } ?>>Mikro Biyoloji
            </div>

        </div>

        <div class="row mt-2 ">
            <div class="col-4">
                <input disabled type="checkbox" class="col-md-3" name="show_sampling" value="1" <?php if($lab_test_uyari_mesaj['show_sampling']==1){ ?> checked <?php } ?>>Numune Alma
            </div>

            <div class="col-4">
                <input disabled type="checkbox" class="col-md-3" name="show_sample_acceptance" value="1" <?php if($lab_test_uyari_mesaj['show_sample_acceptance']==1){ ?> checked <?php } ?>>Numune Kabul
            </div>

            <div class="col-4">
                <input disabled type="checkbox" class="col-md-3" name="show_results" value="1" <?php if($lab_test_uyari_mesaj['show_results']==1){ ?> checked <?php } ?>>Sonuç Verme
            </div>
        </div>

        <div class="row mt-4">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Mesaj</label>
                <div class="col-sm-9">
                    <textarea disabled class="form-control" name="warning_message"><?php echo $lab_test_uyari_mesaj["warning_message"];?></textarea>
                </div>
            </div>
        </div>

    </div>

<?php }

else if($islem=="test_uyari_mesaji_tetkik_liste"){
    $gelen_veri = $_GET['getir'];?>

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Test Uyarı Mesajına Tanımlı Tetkikler</b></div>
        <div class="card-body " style="height: 40vh;">


                <script>
                    $(document).ready(function(){
                        $('#test-uyari-tetkik-liste-datatb').DataTable( {
                            scrollY: '25vh',
                            scrollX: true,
                            "info":true,
                            "paging":false,
                            "searching":true,
                            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                        });
                    });
                </script>

                <table id="test-uyari-tetkik-liste-datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                    <thead>
                    <tr>
                        <th>Test Grubu</th>
                        <th>SUT Kodu</th>
                        <th>Tetkik Adı</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql = verilericoklucek("select lab_analysis.id as analysis_id, lab_analysis.status as analysis_status, lab_analysis.*,lab_test_warning_message_analysis.* from lab_test_warning_message_analysis,lab_analysis where lab_test_warning_message_analysis.analysis_id=lab_analysis.id  and warning_messageid=$gelen_veri");
                    foreach ((array)$sql as $rowa){
                        $test_grup=singular("lab_test_groups","id",$rowa["test_group"])?>
                        <tr  style="cursor: pointer;" id="<?php echo $rowa["id"] ?>">
                            <td><?php echo $test_grup["group_name"]; ?></td >
                            <td><?php echo $rowa["sut_code"]; ?></td >
                            <td><?php echo $rowa["analysis_name"]; ?></td >
                            <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            <td align="center">
                                <i class="fa fa-pen-to-square uyari_mesaj_tetkik_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $rowa["id"]; ?>"></i>

                                <?php if($rowa['status']=='0'){ ?>
                                    <i class="fa-solid fa-recycle uyari_mesaj_tetkik_tanim_aktif" title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>
                                <?php }else{ ?>
                                    <i class="fa fa-trash uyari_mesaj_tetkik_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header p-1 mt-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Test Uyarı Mesajına Tanımlı Kullanıcılar</b></div>
        <div class="card-body" style="height: 40vh;">
            <script>
                $(document).ready(function(){
                    $('#test-uyari-kullanici-liste-datatb').DataTable( {
                        scrollY: '25vh',
                        scrollX: true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });
                });
            </script>

            <table id="test-uyari-kullanici-liste-datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                <thead>
                <tr>
                    <th>Kullanıcı Adı</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select users.id as userid, users.status as userstatus,users.*,lab_test_warning_message_user.* from lab_test_warning_message_user,users where lab_test_warning_message_user.user_id = users.id and warning_messageid=$gelen_veri");
                foreach ((array)$sql as $rowa){?>
                    <tr  style="cursor: pointer;" id="<?php echo $rowa["id"] ?>">
                        <td><?php echo $rowa["name_surname"]; ?></td >
                        <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle uyari_mesaj_kullanici_tanim_aktif" title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash uyari_mesaj_kullanici_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>

        $("body").off("click", ".uyari_mesaj_tetkik_tanim_duzenle").on("click", ".uyari_mesaj_tetkik_tanim_duzenle", function(e){
            var getir = $(this).attr('id');


            $('.tanimlamalar_w40_h45').window('setTitle', 'Test Uyarı Mesajı Tanımlı Tetkik Düzenle');
            $('.tanimlamalar_w40_h45').window('open');
            $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=modal_test_uyari_mesaji_tetkik_duzenle&getir=' + getir + '');

        });

        $("body").off("click", ".uyari_mesaj_tetkik_tanim_sil").on("click", ".uyari_mesaj_tetkik_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Test Uyarı Mesajı Tetkik Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_tetkik_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $gelen_veri;?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":getir },function(e){
                                $('#test-uyarı-kullanici-tetkik-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".uyari_mesaj_tetkik_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Test Uyarı Mesajı Tetkik Silme İşlemini Onayla"});
        });

        $("body").off("click",".uyari_mesaj_tetkik_tanim_aktif").on("click",".uyari_mesaj_tetkik_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_tetkik_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $gelen_veri;?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":getir },function(e){
                        $('#test-uyarı-kullanici-tetkik-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".uyari_mesaj_kullanici_tanim_sil").on("click", ".uyari_mesaj_kullanici_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Test Uyarı Mesajı Kullanıcı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_kullanici_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $gelen_veri;?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":getir },function(e){
                                $('#test-uyarı-kullanici-tetkik-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".uyari_mesaj_kullanici_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Test Uyarı Mesajı Kullanıcı Silme İşlemini Onayla"});
        });

        $("body").off("click",".uyari_mesaj_kullanici_tanim_aktif").on("click",".uyari_mesaj_kullanici_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_kullanici_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $gelen_veri;?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":getir },function(e){
                        $('#test-uyarı-kullanici-tetkik-tanim').html(e);
                    });
                }
            });
        });
    </script>
<?php }

else if($islem=="modal_test_uyari_mesaji_tetkik_tanimla"){
    $gelen_veri = $_GET['getir'];?>

    <div class="mt-2">
        <table id="cihaz-tetkik-tanimlama-liste-datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
            <thead>
            <tr>
                <th title="Test Grubu">Grubu</th>
                <th>SUT Kodu</th>
                <th>Tetkik Adı</th>
                <th>Durum</th>
            </tr>
            </thead>
            <tbody>
            <?php $sql = verilericoklucek("select lab_analysis.id as lab_analysisid ,lab_analysis.status as analysis_status ,lab_analysis.*,lab_test_groups.* from lab_analysis,lab_test_groups where lab_analysis.test_group=lab_test_groups.id and lab_analysis.id NOT IN (select analysis_id from lab_test_warning_message_analysis where warning_messageid=$gelen_veri)");
            foreach ((array)$sql as $rowa ) {?>
                <tr style="cursor: pointer;" id="<?php echo $rowa["lab_analysisid"]; ?>" class="uyari_mesaj_tetkik_sec">
                    <td><?php echo $rowa["group_name"]; ?></td>
                    <td><?php echo $rowa["sut_code"]; ?></td>
                    <td><?php echo $rowa["analysis_name"]; ?></td >
                    <td align="center"><?php if($rowa["analysis_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <script>
            $(document).ready(function(){
                setTimeout(function(){
                    $('#cihaz-tetkik-tanimlama-liste-datatb').DataTable( {
                        "scrollY": '25vh',
                        "scrollX": true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });
                }, 100);
            });
            $(".uyari_mesaj_tetkik_sec").click(function () {
                $(this).css('background-color') != 'rgb(147,203,198)' ;
                $('.uyari_mesaj_tetkik_sec-kaldir').removeClass("text-white");
                $('.uyari_mesaj_tetkik_sec-kaldir').removeClass("uyari_mesaj_tetkik_sec-kaldir");
                $('.uyari_mesaj_tetkik_sec').css({"background-color": "rgb(255, 255, 255)"});
                $(this).css({"background-color": "rgb(147,203,198)"});
                $(this).addClass("text-white uyari_mesaj_tetkik_sec-kaldir");

                var getir=$('.uyari_mesaj_tetkik_sec-kaldir').attr('id');
                $('#analysis_id').val(getir);
            });
        </script>

        <form id="test_uyari_tetkik_tanimla_form" action="javascript:void(0);">
            <input class="form-control" hidden type="text" name="warning_messageid" id="warning_messageid" value="<?php echo $gelen_veri;?>">
            <input class="form-control" hidden type="text" name="analysis_id" id="analysis_id">
        </form>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-uyari-mesaji-tetkik-ekle">Kaydet</a>
            </div>
        </div>
    </div>

    <script>

        $("body").off("click", "#btn-test-uyari-mesaji-tetkik-ekle").on("click", "#btn-test-uyari-mesaji-tetkik-ekle", function(e){
            var test_uyari_tetkik_tanimla_form = $("#test_uyari_tetkik_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_tetkik_ekle',
                data:test_uyari_tetkik_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h45').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":<?php echo $gelen_veri;?> },function(e){
                        $('#test-uyarı-kullanici-tetkik-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });

    </script>
<?php }

else if($islem=="modal_test_uyari_mesaji_tetkik_duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_test_uyari_tetkik=singularactive("lab_test_warning_message_analysis","id",$gelen_veri);
    $test_uyari_mesaj_id = $lab_test_uyari_tetkik["warning_messageid"];?>

    <div class="mt-2">

        <table id="cihaz-tetkik-tanimlama-liste-datatb1" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
            <thead>
            <tr>
                <th title="Test Grubu">Grubu</th>
                <th>SUT Kodu</th>
                <th>Tetkik Adı</th>
                <th>Durum</th>
            </tr>
            </thead>
            <tbody>
            <?php $sql = verilericoklucek("select lab_analysis.id as lab_analysisid ,lab_analysis.status as analysis_status ,lab_analysis.*,lab_test_groups.* from lab_analysis,lab_test_groups where lab_analysis.test_group=lab_test_groups.id and lab_analysis.id NOT IN (select analysis_id from lab_test_warning_message_analysis where warning_messageid=$test_uyari_mesaj_id)");
            foreach ((array)$sql as $rowa ) {?>
                <tr style="cursor: pointer;" id="<?php echo $rowa["lab_analysisid"]; ?>" class="uyari_mesaj_tetkik_duzenle_sec">
                    <td><?php echo $rowa["group_name"]; ?></td>
                    <td><?php echo $rowa["sut_code"]; ?></td>
                    <td><?php echo $rowa["analysis_name"]; ?></td >
                    <td align="center"><?php if($rowa["analysis_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <script>
            $(document).ready(function(){
                setTimeout(function(){
                    $('#cihaz-tetkik-tanimlama-liste-datatb1').DataTable( {
                        scrollY: '25vh',
                        scrollX: true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });
                }, 100);
            });
            $(".uyari_mesaj_tetkik_duzenle_sec").click(function () {
                $(".uyari_mesaj_tetkik_duzenle_sec").removeClass("bg-yesil text-white")
                $(this).addClass("bg-yesil text-white");
                $('#analysis_id123').val($(this).attr('id'));
            });
        </script>

        <form id="test_uyari_tetkik_tanim_duzenle_form" action="javascript:void(0);">
            <input class="form-control" hidden type="text" name="id" id="id" value="<?php echo $gelen_veri; ?>">
            <input class="form-control" hidden  type="text" name="analysis_id" id="analysis_id123">
        </form>


        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-uyari-mesaji-tetkik-duzenle">Kaydet</a>
            </div>
        </div>

    </div>

    <script>

        $("body").off("click", "#btn-test-uyari-mesaji-tetkik-duzenle").on("click", "#btn-test-uyari-mesaji-tetkik-duzenle", function(e){
            var test_uyari_tetkik_tanim_duzenle_form = $("#test_uyari_tetkik_tanim_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_tetkik_duzenle',
                data:test_uyari_tetkik_tanim_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h45').window('close');
                    var getir = "<?php echo $lab_test_uyari_tetkik["warning_messageid"]?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":getir },function(e){
                        $('#test-uyarı-kullanici-tetkik-tanim').html(e);
                    });
                }
            });
        });


        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });

    </script>
<?php }

else if($islem=="modal_test_uyari_mesaji_kullanici_tanimla"){
    $gelen_veri = $_GET['getir'];?>

        <div class="mt-2">
            <table id="kullanici-tanimla-listesi-datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                <thead>
                <tr>
                    <th>Adı Soyadı</th>
                    <th>Ünvanı</th>
                    <th>Bölümü</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from users where users.id NOT IN (select user_id from lab_test_warning_message_user where warning_messageid=$gelen_veri) and status='1'");
                foreach ((array)$sql as $rowa ) {?>
                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="uyari_mesaj_kullanici_sec">
                        <td><?php echo $rowa["name_surname"]; ?></td>
                        <td><?php echo islemtanimgetirid($rowa["title"]); ?></td>
                        <td><?php echo birimgetirid($rowa["department"]); ?></td >
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <script>
                $(document).ready(function(){
                    setTimeout(function(){
                        $('#kullanici-tanimla-listesi-datatb').DataTable( {
                            scrollY: '25vh',
                            scrollX: true,
                            "info":true,
                            "paging":false,
                            "searching":true,
                            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                        });
                    }, 100);
                });

                $(".uyari_mesaj_kullanici_sec").click(function () {
                    $(this).css('background-color') != 'rgb(147,203,198)' ;
                    $('.uyari_mesaj_kullanici_sec-kaldir').removeClass("text-white");
                    $('.uyari_mesaj_kullanici_sec-kaldir').removeClass("uyari_mesaj_kullanici_sec-kaldir");
                    $('.uyari_mesaj_kullanici_sec').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(147,203,198)"});
                    $(this).addClass("text-white uyari_mesaj_kullanici_sec-kaldir");

                    var user_id=$('.uyari_mesaj_kullanici_sec-kaldir').attr('id');
                    $('#user_id').val(user_id);
                });
            </script>

            <form id="test_uyari_kullanici_tanimla_form" action="javascript:void(0);">
                <input class="form-control" hidden type="text" name="warning_messageid" id="warning_messageid" value="<?php echo $gelen_veri;?>">
                <input class="form-control" hidden type="text" name="user_id" id="user_id">
            </form>

            <div class="row" align="right">
                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-uyari-mesaji-kullanici-ekle">Kaydet</a>
                </div>
            </div>


    </div>

    <script>

            $("body").off("click", "#btn-test-uyari-mesaji-kullanici-ekle").on("click", "#btn-test-uyari-mesaji-kullanici-ekle", function(e){
                var test_uyari_kullanici_tanimla_form = $("#test_uyari_kullanici_tanimla_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=sql_test_uyari_mesaji_kullanici_ekle',
                    data:test_uyari_kullanici_tanimla_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $('.tanimlamalar_w40_h45').window('close');
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla.php?islem=test_uyari_mesaji_tetkik_liste",{ "getir":<?php echo $gelen_veri;?> },function(e){
                            $('#test-uyarı-kullanici-tetkik-tanim').html(e);
                        });
                    }
                });
            });

            $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
                $('.tanimlamalar_w40_h45').window('close');
            });
    </script>
<?php }?>


