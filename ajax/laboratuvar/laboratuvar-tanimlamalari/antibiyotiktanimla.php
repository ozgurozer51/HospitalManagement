<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_antibiyotik_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_antibiotic_definition",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Antibiyotik Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Antibiyotik Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_antibiyotik_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_antibiotic_definition", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Antibiyotik Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Antibiyotik Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_antibiyotik_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_antibiotic_definition", "id", $id, $detay, $kullanici, $tarih);
    if ($sql == 1){?>
        <script>
            alertify.success('Antibiyotik Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Antibiyotik Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_antibiyotik_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];

    $sql = backcancel('lab_antibiotic_definition','id',$id,$date,$user);
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


    <div id="antibiyotik-tanim">


        <div class="mt-2 mx-2">

            <script>
                $('#antibiyotik-tanimla-tab').DataTable( {
                    "responsive":false,
                    "scrollX": true,
                    "scrollY": true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'Antibiyotik Tanımla',
                            className: 'btn btn-success btn-sm btn-kaydet',

                            action: function ( e, dt, button, config ) {

                                $('.tanimlamalar_w40_h35').window('setTitle', 'Antibiyotik Tanımla');
                                $('.tanimlamalar_w40_h35').window('open');
                                $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=modal-antibiyotik-tanimla');


                            }
                        }
                    ],
                } );
            </script>

            <table id="antibiyotik-tanimla-tab" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Kodu</th>
                    <th>Antibiyotik Adı</th>
                    <th>Açıklama</th>
                    <th>Rapor Sırası</th>
                    <th>Antibiyotik Profili</th>
                    <th>Durumu</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select lab_antibiotic_definition.id as antibiotic_definitionid ,lab_antibiotic_definition.status as antibiotic_definition_status,lab_antibiotic_definition.*, lab_antibiotic_profile.* from lab_antibiotic_definition,lab_antibiotic_profile where lab_antibiotic_definition.antibiotic_profile=lab_antibiotic_profile.id");
                foreach ((array)$sql as $row){ ?>
                    <tr style="cursor: pointer;">
                        <td><?php echo $row["antibiotic_definitionid"]; ?> </td>
                        <td><?php echo mb_strtoupper($row["antibiotic_code"]); ?> </td >
                        <td><?php echo mb_strtoupper($row["antibiotic_name"]); ?> </td >
                        <td><?php echo mb_strtoupper($row["antibiotic_description"]); ?> </td >
                        <td><?php echo mb_strtoupper($row["order_of_report"]); ?> </td >
                        <td><?php echo mb_strtoupper($row["antibiotic_profile_name"]); ?> </td >
                        <td align="center"><?php if($row["antibiotic_definition_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square tanimli_antibiyotik_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["antibiotic_definitionid"];?>"></i>

                            <?php if($row['antibiotic_definition_status']=='0'){?>
                                <i class="fa-solid fa-recycle tanimli_antibiyotik_aktif" title="Aktif Et" id="<?php echo $row["antibiotic_definitionid"]; ?>" alt="icon" ></i>
                            <?php } else{ ?>
                                <i class="fa fa-trash tanimli_antibiyotik_sil" title="İptal" id="<?php echo $row["antibiotic_definitionid"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>


    </div>

    <script>
        $("body").off("click", ".tanimli_antibiyotik_duzenle").on("click", ".tanimli_antibiyotik_duzenle", function(e){
            var getir = $(this).attr('id');

            $('.tanimlamalar_w40_h35').window('setTitle', 'Antibiyotik Tanımı Düzenle');
            $('.tanimlamalar_w40_h35').window('open');
            $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=modal-antibiyotik-tanim-duzenle&getir=' + getir + '');

        });

        $("body").off("click", ".tanimli_antibiyotik_sil").on("click", ".tanimli_antibiyotik_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Antibiyotik Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=sql_antibiyotik_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=listeyi-getir",function(e){
                                $('#antibiyotik-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".tanimli_antibiyotik_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Antibiyotik Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".tanimli_antibiyotik_aktif").on("click",".tanimli_antibiyotik_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=sql_antibiyotik_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('#antibiyotik-tanim:first').load("ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=listeyi-getir");
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-antibiyotik-tanimla"){?>

    <div class=" mt-3 mx-1">

        <form id="antibiyotik_tanimla_form" action="javascript:void(0);">

            <div class="row">

                <div class="col-4">
                    <label>Antibiyotik Kodu</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="antibiotic_code"/>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Antibiyotik Adı</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="antibiotic_name"/>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Antibiyotik Açıklama</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="antibiotic_description"/>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Rapor Sırası</label>
                </div>

                <div class="col-8">
                    <input type="number" class="form-control" name="order_of_report"/>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Antibiyorik Profili</label>
                </div>

                <div class="col-8">
                    <select class="form-select" name="antibiotic_profile">
                        <option selected disabled class="text-white bg-danger">Antibiyotik Profili seçiniz..</option>
                        <?php $sql = verilericoklucek("select * from lab_antibiotic_profile where status='1'") ;
                        foreach ($sql as $item){ ?>
                            <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['antibiotic_profile_name']); ?></option>
                        <?php  } ?>
                    </select>
                </div>

            </div>

        </form>

        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-laboratuvar-tanim-ekle">Kaydet</a>
            </div>
        </div>

    </div>



    <script>

        $("body").off("click", "#btn-laboratuvar-tanim-ekle").on("click", "#btn-laboratuvar-tanim-ekle", function(e){
                var antibiyotik_tanimla_form = $("#antibiyotik_tanimla_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=sql_antibiyotik_tanimi_ekle',
                    data:antibiyotik_tanimla_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=listeyi-getir",function(e){
                            $('#antibiyotik-tanim').html(e);
                        });

                        $('.tanimlamalar_w40_h35').window('close');
                    }
                });
            });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });

    </script>

<?php }

else if($islem=="modal-antibiyotik-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_antibiyotik_tanim=singularactive("lab_antibiotic_definition","id",$gelen_veri);?>


    <div class=" mt-3 mx-1">

        <form id="antibiyotik_tanimi_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" value="<?php echo $lab_antibiyotik_tanim["id"];?>">

            <div class="row">

                <div class="col-4">
                    <label>Antibiyotik Kodu</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="antibiotic_code" value="<?php echo mb_strtoupper($lab_antibiyotik_tanim["antibiotic_code"]);?>">
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Antibiyotik Adı</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="antibiotic_name" value="<?php echo mb_strtoupper($lab_antibiyotik_tanim["antibiotic_name"]);?>">
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Antibiyotik Açıklama</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="antibiotic_description" value="<?php echo mb_strtoupper($lab_antibiyotik_tanim["antibiotic_description"]);?>">
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Rapor Sırası</label>
                </div>

                <div class="col-8">
                    <input type="number" class="form-control" name="order_of_report" value="<?php echo mb_strtoupper($lab_antibiyotik_tanim["order_of_report"]);?>">
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Antibiyorik Profili</label>
                </div>

                <div class="col-8">
                    <select class="form-select" name="antibiotic_profile">
                        <option selected disabled class="text-white bg-danger">Antibiyotik Profili seçiniz..</option>
                        <?php $sql = verilericoklucek("select * from lab_antibiotic_profile where status='1'") ;
                        foreach ($sql as $item){ ?>
                            <option  <?php if($lab_antibiyotik_tanim["antibiotic_profile"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['antibiotic_profile_name']); ?></option>
                        <?php  } ?>
                    </select>
                </div>

            </div>

        </form>

        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-antibiyotik-tanim-duzenle">Kaydet</a>
            </div>
        </div>

    </div>

    <script>

        $("body").off("click", "#btn-antibiyotik-tanim-duzenle").on("click", "#btn-antibiyotik-tanim-duzenle", function(e){
                var antibiyotik_tanimi_duzenle_form = $("#antibiyotik_tanimi_duzenle_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=sql_antibiyotik_tanimi_duzenle ',
                    data:antibiyotik_tanimi_duzenle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla.php?islem=listeyi-getir",function(e){
                            $('#antibiyotik-tanim').html(e);
                        });
                        $('.tanimlamalar_w40_h35').window('close');
                    }
                });
            });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });

    </script>
<?php } ?>