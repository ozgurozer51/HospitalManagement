<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_tetkik_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_analysis",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Tetkik Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Tetkik Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="processes_sql"){
    $sql = verilericoklucek("select distinct processes.id as hizmet_id,processes.process_name as hizmet_adi,processes_price.price as hizmet_ucret, official_code as hizmet_kodu,processes.process_group_id as hizmet_grup_id from processes inner join process_group on processes.process_group_id = process_group.id inner join processes_price on processes.id = processes_price.processes_id where process_group.id in (select id from process_group where  process_group.group_type=1 ) ");
//    fetch first 100 rows only
    ECHO json_encode($sql);
}

else if ($islem=="sql_tetkik_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);



    if($_POST["dilution_rate"]==null){
        $_POST["dilution_rate"] = null;
    }

    if($_POST["contact_no"]==""){
        $_POST["contact_no"] = "";
    }

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_analysis", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Tetkik Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Tetkik Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_tetkik_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_analysis", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Tetkik Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Tetkik Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_tetkik_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_analysis','id',$id,$date,$user);

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

    <div class="mx-2 mt-2" id="tetkik-tanim">

        <script>



            $('#tetkik-tanimla-tab thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#tetkik-tanimla-tab thead');

                var table = $('#tetkik-tanimla-tab').DataTable({
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        "scrollY": true,
                        "scrollX": true,
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                        },
                        "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

                        buttons: [
                            {
                                text: '<i class="fa-solid fa-plus"></i> Tetkik Tanımla',
                                className: 'btn btn-success btn-sm btn_test_tanimla mx-1',

                                action: function ( e, dt, button, config ) {
                                    $('.tanimlamalar_w80_h45').window('setTitle', 'Tetkik Tanımla');
                                    $('.tanimlamalar_w80_h45').window('open');
                                    $('.tanimlamalar_w80_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=modal-test-tanimla');
                                }
                            },

                            {
                                extend: 'pdf',
                                name: 'pdfButton',
                                className:'btn btn-sm btn-dark mx-1'
                            },

                            {
                                extend: 'excel',
                                name: 'excelButton',
                                className:'btn btn-sm btn-dark mx-1'
                            },


                            {
                                extend: 'print',
                                name: 'printButton',
                                className:'btn btn-sm btn-dark mx-1'
                            },
                        ],



                        initComplete: function () {
                            var api = this.api();
                            // For each column
                            api
                                .columns()
                                .eq(0)
                                .each(function (colIdx) {
                                    // Set the header cell to contain the input element
                                    var cell = $('.filters th').eq(
                                        $(api.column(colIdx).header()).index()
                                    );
                                    var title = $(cell).text();
                                    $(cell).html(colIdx != 0 ? '<input type="text" placeholder="' + title + '" />' : '');
                                    // On every keypress in this input
                                    $(
                                        'input',
                                        $('.filters th').eq($(api.column(colIdx).header()).index())
                                    )
                                        .off('keyup change')
                                        .on('change', function (e) {
                                            // Get the search value
                                            $(this).attr('title', $(this).val());
                                            var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                            var cursorPosition = this.selectionStart;
                                            // Search the column for that value
                                            api
                                                .column(colIdx)
                                                .search(
                                                    this.value != ''
                                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                        : '',
                                                    this.value != '',
                                                    this.value == ''
                                                )
                                                .draw();
                                        })
                                        .on('keyup', function (e) {
                                            e.stopPropagation();
                                            $(this).trigger('change');
                                            $(this)
                                                .focus()[0]
                                                .setSelectionRange(cursorPosition, cursorPosition);
                                        });
                                });
                        },
                    });


        </script>

        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tetkik-tanimla-tab">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Test Grubu</th>
                        <th>SUT Kodu</th>
                        <th>Tetkik Adı</th>
                        <th>İletişim No</th>
                        <th>Barkod Metni</th>
                        <th>Açıklama</th>
                        <th>Kısa Adı</th>
                        <th>Seyrelti Oranı</th>
                        <th>Kontrol Aktif</th>
                        <th>Çalışma Şekli</th>
                        <th>Cinsiyet</th>
                        <th>Test Aktif</th>
                        <th>Analizde Göster</th>
                        <th>Randevu Profili</th>
                        <th>Tüp/Kap Tipi</th>
                        <th>Örnek Tipi</th>
                        <th>LONIC Kodu</th>
                        <th>Tahlil Durumu</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql = verilericoklucek("select lab_analysis.id as lab_analysisid ,lab_analysis.status as durum ,lab_analysis.*,lab_test_groups.* from lab_analysis,lab_test_groups where lab_analysis.test_group=lab_test_groups.id");

                    foreach ((array)$sql as $row){ ?>
                        <tr style="cursor: pointer;">
                            <td><?php echo $row["lab_analysisid"];?> </td>
                            <td><?php echo $row["group_name"];?> </td>
                            <td><?php echo $row["sut_code"];?> </td>
                            <td><?php echo $row["analysis_name"];?> </td>
                            <td><?php echo $row["contact_no"];?> </td>
                            <td><?php echo $row["barcod_text"];?> </td>
                            <td><?php echo $row["analysis_explanation"];?> </td>
                            <td><?php echo $row["short_name"];?> </td>
                            <td><?php echo $row["dilution_rate"];?> </td>
                            <td><?php echo labtanimgetirid($row["control_active"]);?> </td>
                            <td><?php echo labtanimgetirid($row["way_of_working"]);?> </td>
                            <td><?php echo labtanimgetirid($row["gender"]);?> </td>
                            <td><?php echo labtanimgetirid($row["test_active_status"]);?> </td>
                            <td><?php echo labtanimgetirid($row["show_analysis"]);?> </td>
                            <td><?php echo labtanimgetirid($row["appointment_profile_code"]);?> </td>
                            <td><?php echo labtanimgetirid($row["tube_container_type"]);?> </td>

                            <td><?php echo labtanimgetirid( $row["example_type"]);?> </td>
                            <td><?php echo $row["loinc_code"];?> </td>

                            <td align="center"><?php if($row["durum"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            <td align="center">
                                <i class="fa fa-pen-to-square tetkik_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["lab_analysisid"];?>" sut_code="<?php echo $row["sut_code"]; ?>"></i>

                                <?php if($row['durum']=='0'){ ?>
                                    <i class="fa-solid fa-recycle tetkik_tanim_aktif"
                                       title="Aktif Et" id="<?php echo $row["lab_analysisid"]; ?>" alt="icon" ></i>

                                <?php }else{ ?>

                                    <i class="fa fa-trash tetkik_tanim_sil" title="İptal" id="<?php echo $row["lab_analysisid"];?>" alt="icon"></i>

                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

    </div>


    <script>
        $("body").off("click", ".tetkik_tanim_duzenle").on("click", ".tetkik_tanim_duzenle", function(e){
            var getir = $(this).attr('id');
            var sut_code = $(this).attr('sut_code');

            $('.tanimlamalar_w80_h45').window('setTitle', 'Tetkik Tanımı Düzenle');
            $('.tanimlamalar_w80_h45').window('open');
            $('.tanimlamalar_w80_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=modal-tetkik-tanim-duzenle&getir=' + getir + '&sut_code='+ sut_code +'');
        });

        $("body").off("click", ".tetkik_tanim_sil").on("click", ".tetkik_tanim_sil", function(e){
            var id = $(this).attr("id");
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Tetkik Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=sql_tetkik_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=listeyi-getir",function(e){
                                $('#tetkik-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".tetkik_tanim_sil:first").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Tetkik Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".tetkik_tanim_aktif").on("click",".tetkik_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=sql_tetkik_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('#tetkik-tanim:first').load("ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=listeyi-getir");
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-test-tanimla"){?>

    <div class="row mt-3">

        <div class="col-4">

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tanimli_tetkik_ekle_datatable">
                <thead>
                <tr>
                    <th>SUT Kodu</th>
                    <th>Tetkik Adı</th>
                </tr>
                </thead>

            </table>

            <script>
                var tanimli_tetkik_ekle = $('#tanimli_tetkik_ekle_datatable').DataTable({
                    deferRender: true,
                    scrollY: '30vh',
                    "info":false,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

                    ajax: {
                        url: '/ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=processes_sql',
                        processing: true,
                        method: 'GET',
                        dataSrc: ''
                    },



                    "createdRow": function (row, data, dataIndex) {

                    },

                    "fnRowCallback": function (nRow, aData) {
                        $(nRow)
                            .attr('official_code',aData['hizmet_kodu'])
                            .attr('process_name',aData['hizmet_adi'])
                            .attr('processes_id',aData['hizmet_id'])
                            .attr('process_groupid',aData['hizmet_grup_id'])
                            .attr('class','tiklayinca')

                    },

                    "initComplete": function (settings, json) {

                    },

                    columns:[

                        // {
                        //     data: null,
                        //     render: function (data) { return '<button id="tani-sil" data-id="'+ data.special_id +'" class="btn btn-danger tani-iptal btn-sm"><i class="fas fa-trash"></i></button>'; }
                        // },


                        {data:'hizmet_kodu'},
                        {data:'hizmet_adi'}
                    ],

                });

                $("body").off("click", ".tiklayinca").on("click", ".tiklayinca", function (e) {

                    $(this).css('background-color') != 'rgb(147,203,198)' ;
                    $('.tiklayinca-kaldir').removeClass("text-white");
                    $('.tiklayinca').removeClass("tiklayinca-kaldir");
                    $('.tiklayinca').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(147,203,198)"});
                    $(this).addClass("text-white tiklayinca-kaldir");

                    var processes_id=$('.tiklayinca-kaldir').attr('processes_id');
                    $('#processes_id').val(processes_id);

                    var process_groupid=$('.tiklayinca-kaldir').attr('process_groupid');
                    $('#process_groupid').val(process_groupid);

                    var official_code=$('.tiklayinca-kaldir').attr('official_code');
                    $('#sut_code').val(official_code);

                    var process_name=$('.tiklayinca-kaldir').attr('process_name');
                    $('#analysis_name').val(process_name);
                });

                // function afterTableInitialization(settings, json) {
                //     table = settings.oInstance.api();
                //     table.columns().every( function () {
                //
                //
                //         json.forEach(function (data, index) {
                //
                //
                //         });
                //     });
                // }


            </script>

        </div>

        <div class="col-8">

            <form id="test_tanimla_form" action="javascript:void(0);">

                <div class="col-12 row">
                    <input class="form-control " hidden type="text"  name="processes_id" id="processes_id">
                    <input class="form-control " hidden type="text"  name="process_groupid" id="process_groupid">
                    <div class="col-4">
                        <div class="row ">
                            <div class="col-5">
                                <label >Test Durum</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select " name="test_active_status" title="Test kullanımdaysa 'Evet', Kullanımda olmadığı durumda 'Hayır' seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Test kullanımda mı?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TEST_AKTIF'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($item["id"]=='40'){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Test Grubu</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select " id="test_group" name="test_group" title="Testin dahil olduğu grup seçilir. Grup seçilmesi sonrası tüm onaylamalar ve işlemler bu gruba göre yapılmaktadır.">
                                    <option selected disabled class="text-white bg-danger">Test grubu seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_test_groups where status='1'") ;
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['group_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">SUT Kodu</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control " type="text" name="sut_code" id="sut_code" title="SUT Kodu bilgisi">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Tetkik Adı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control " type="text" name="analysis_name" id="analysis_name" title="Tanımlanmak istenen tahlilin adı">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Kısa Adı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control " type="text" name="short_name" id="example-text-input" title="Uzun isimli olan tahlili kısaltma kiçin kısa ad">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input" >LOINC Kodu</label>
                            </div>
                            <div class="col-7">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control gelecek_doktor_adi" aria-describedby="basic-addon2" disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm ilac-adi-getir" id="get-doctors" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="row">
                            <div class="col-5">
                                <label for="example-text-input" >İletişim No</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="contact_no" id="example-text-input" title="İletişim bilgisi kodu">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Barkod Metni</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="barcod_text" id="example-text-input" title="Barkod üzerine çıkacak olan tetkik adı">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Açıklama</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="analysis_explanation" id="example-text-input" title="Rapor ve randevu bölümünde çıkması istenen tahlil ile ilgili açıklama">

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Tüp Tipi</label>
                            </div>
                            <div class="col-7" id="tup_tipi_dbclick">
                                <select class="form-select" id="tup_tipi" name="tube_container_type" title="Numune tüpleri seçenekleri. Uygun olan tüpü seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Tüp tipi seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TUP_TANIM'");
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Kap Tipi</label>
                            </div>
                            <div class="col-7" id="kap_tipi_dbclick">
                                <select class="form-select" id="kap_tipi" name="tube_container_type" title="Numune kapları seçenekleri. Uygun olan kabı seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Kap tipi seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='KAP_TANIM'");
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <script>

                            $("#tup_tipi").change(function () {
                                $('#kap_tipi').attr('disabled', true);
                            });

                            $( "#tup_tipi_dbclick" ).dblclick(function() {
                                $('#tup_tipi').attr('disabled', false);
                            });

                            $("#kap_tipi").change(function () {
                                $('#tup_tipi').attr('disabled', true);
                            });

                            $( "#kap_tipi_dbclick" ).dblclick(function() {
                                $('#kap_tipi').attr('disabled', false);
                            });

                        </script>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Örnek Tipi</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="example_type" title="Hangi örnek tipi alınacaksa uygun olan tanımlama seçilmelidir.">
                                    <option selected disabled class="text-white bg-danger">Örnek tipi seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='ORNEK_TIPI'");
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" > <?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="row">
                            <div class="col-5">
                                <label >Çalışma Şekli</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="way_of_working" title="Tahlil 'Cihazda' mı, yoksa 'Manuel' mi yapılacağını seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Tahlil nasıl yapılacak?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TETKIK_CALISMA_SEKLI'");
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Seyrelti Oranı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="number" name="dilution_rate" id="example-text-input" title="Seyrelti yapılacaksa seyrelti oranı yazılır">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Cinsiyet</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="gender" title="Tahlil için cinsiyet kriteri varsa uygun olan cinsiyeti seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Cinsiyet kriteri seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TEKTIK_CINSIYET'") ;
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Kontrol Aktif</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="control_active" title="Tahlil ile ilgili kontrol çalışması yapılıyor mu?">
                                    <option selected disabled class="text-white bg-danger">Çalışma yapılıyor mu ?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='KONTROL_AKTIF'");
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Randevu Profili</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="appointment_profile_code" title="Testin sonucunun ne zaman alınabileceği kriterleri belirlenmektedir.">
                                    <option selected disabled class="text-white bg-danger">Sonucun ne zaman alınablieceğini seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TETKIK_RANDEVU_PROFILI' ");
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>

                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Analizde Göster</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="show_analysis" title="Analizlerde testlerin görünmesini sağlar.">
                                    <option selected disabled class="text-white bg-danger">Analizde gösterilsin mi?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='ANALIZDE_GOSTER'");
                                    foreach ($sql as $item){ ?>
                                        <option  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

            </form>

            <div class="row mt-5" align="right">

                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-tanimi-ekle">Kaydet</a>
                </div>

            </div>

        </div>

    </div>


    <script>

        $("body").off("click", "#btn-test-tanimi-ekle").on("click", "#btn-test-tanimi-ekle", function(e){
            var test_tanimla_form = $("#test_tanimla_form").serialize();
            var test_group = $("#test_group").val();
            var processes_id = $("#processes_id").val();
            if (test_group != null && processes_id != '') {
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=sql_tetkik_tanimi_ekle',
                data:test_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w80_h45').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=listeyi-getir",function(e){
                        $('#tetkik-tanim').html(e);
                    });
                }
            });
            } else if (processes_id == '') {

                alertify.warning("Tetkik Seçiniz.");

            } else if (test_group == null) {

                alertify.warning("Test Grubu Seçiniz.");

            }
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w80_h45').window('close');
        });

    </script>

<?php }

else if($islem=="modal-tetkik-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $sut_code = $_GET['sut_code'];
    $lab_tahlil=singularactive("lab_analysis","id",$gelen_veri); ?>

    <div class="row mt-3">

        <div class="col-4">

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tanimli_tetkik_duzenle_datatable">
                <thead>
                <tr>
                    <th>SUT Kodu</th>
                    <th>Tetkik Adı</th>
                </tr>
                </thead>

            </table>

            <script>
                var tanimli_tetkik_duzenle = $('#tanimli_tetkik_duzenle_datatable').DataTable({
                    deferRender: true,
                    scrollY: '30vh',
                    "info":false,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    ajax: {
                        url: '/ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=processes_sql',
                        processing: true,
                        method: 'GET',
                        dataSrc: ''
                    },

                    "createdRow": function (row, data, dataIndex) {

                    },

                    "fnRowCallback": function (nRow, aData) {

                        var sut_code="<?php echo $sut_code;?>";

                        if(aData['hizmet_kodu'] == sut_code){

                            setTimeout(function() {
                                $("#"+aData['hizmet_kodu']).addClass("text-white bg-yesil");
                            }, 10);
                        }




                        $(nRow)
                            .attr('official_code1',aData['hizmet_kodu'])
                            .attr('id',aData['hizmet_kodu'])
                            .attr('process_name1',aData['hizmet_adi'])
                            .attr('processes_id1',aData['hizmet_id'])
                            .attr('process_groupid1',aData['hizmet_grup_id'])
                            .attr('class','tiklayinca1')

                    },



                    "initComplete": function (settings, json) { //fonksiyon içine jquery yazmak için tablo yüklendikten sonra bu çalışır

                    },

                    columns:[

                        {data:'hizmet_kodu'},
                        {data:'hizmet_adi'}
                    ],
                });



                $("body").off("click", ".tiklayinca1").on("click", ".tiklayinca1", function (e) {

                    $('.tiklayinca1').removeClass("text-white bg-yesil");
                    $(this).addClass("text-white bg-yesil");



                    var processes_id1=$('.bg-yesil').attr('processes_id1');
                    $('#processes_id1').val(processes_id1);

                    var process_groupid1=$('.bg-yesil').attr('process_groupid1');
                    $('#process_groupid1').val(process_groupid1);

                    var official_code1=$('.bg-yesil').attr('official_code1');
                    $('#sut_code1').val(official_code1);

                    var process_name1=$('.bg-yesil').attr('process_name1');
                    $('#analysis_name1').val(process_name1);
                });

                // function afterTableInitialization(settings, json) {
                //     table = settings.oInstance.api();
                //     table.columns().every( function () {
                //
                //
                //         json.forEach(function (data, index) {
                //
                //
                //         });
                //     });
                // }
            </script>

        </div>

        <div class="col-8">

            <form id="tanimli_tetkik_duzenle_form" action="javascript:void(0);">

                <div class="col-12 row">

                    <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_tahlil["id"];?>">
                    <input class="form-control" type="text" hidden name="processes_id" id="processes_id1" value="<?php echo $lab_tahlil["processes_id"];?>">
                    <input class="form-control" type="text" hidden name="process_groupid" id="process_groupid1" value="<?php echo $lab_tahlil["process_groupid"];?>">

                    <div class="col-4">

                        <div class="row ">
                            <div class="col-5">
                                <label >Test Durum</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="test_active_status" title="Test kullanımdaysa 'Evet', Kullanımda olmadığı durumda 'Hayır' seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Test kullanımda mı?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TEST_AKTIF'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["test_active_status"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo $item['definition_name']; ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row  mt-3">
                            <div class="col-5">
                                <label >Test Grubu</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="test_group" title="Testin dahil olduğu grup seçilir. Grup seçilmesi sonrası tüm onaylamalar ve işlemler bu gruba göre yapılmaktadır.">
                                    <option selected disabled class="text-white bg-danger">Test grubu seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_test_groups where status='1'") ;
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["test_group"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo $item['group_name']; ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">SUT Kodu</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="sut_code" id="sut_code1" title="SUT Kodu bilgisi" value="<?php echo $lab_tahlil["sut_code"];?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Tetkik Adı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="analysis_name" id="analysis_name1" title="Tanımlanmak istenen tahlilin adı" value="<?php echo $lab_tahlil["analysis_name"];?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Kısa Adı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="short_name" id="example-text-input" title="Uzun isimli olan tahlili kısaltma kiçin kısa ad" value="<?php echo $lab_tahlil["short_name"];?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input" >LOINC Kodu</label>
                            </div>
                            <div class="col-7">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control loinc_kodu" aria-describedby="basic-addon2" name="loinc_code" id="loinc_code" disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm loinc_kodu_getir" id="get-loinc-code" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="row">
                            <div class="col-5">
                                <label for="example-text-input" >İletişim No</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="contact_no" id="example-text-input" title="İletişim bilgisi kodu" value="<?php echo $lab_tahlil["contact_no"];?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Barkod Metni</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="barcod_text" id="example-text-input" title="Barkod üzerine çıkacak olan tetkik adı" value="<?php echo $lab_tahlil["barcod_text"];?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Açıklama</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="analysis_explanation" id="example-text-input" title="Rapor ve randevu bölümünde çıkması istenen tahlil ile ilgili açıklama" value="<?php echo $lab_tahlil["analysis_explanation"];?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Tüp Tipi</label>
                            </div>
                            <div class="col-7" id="tup_tipi_dbclick1">
                                <select class="form-select"  id="tup_tipi1" name="tube_container_type" title="Numune tüpleri seçenekleri. Uygun olan tüpü seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Tüp tipi seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TUP_TANIM'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["tube_container_type"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Kap Tipi</label>
                            </div>
                            <div class="col-7" id="kap_tipi_dbclick1">
                                <select class="form-select " id="kap_tipi1" name="tube_container_type" title="Numune kapları seçenekleri. Uygun olan kabı seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Kap tipi seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='KAP_TANIM'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["tube_container_type"]==$item["id"]){ echo "selected"; } ?>  value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <script>

                            $("#tup_tipi1").change(function () {
                                $('#kap_tipi1').attr('disabled', true);
                            });

                            $( "#tup_tipi_dbclick1" ).dblclick(function() {
                                $('#tup_tipi1').attr('disabled', false);
                            });

                            $("#kap_tipi1").change(function () {
                                $('#tup_tipi1').attr('disabled', true);
                            });

                            $( "#kap_tipi_dbclick1" ).dblclick(function() {
                                $('#kap_tipi1').attr('disabled', false);
                            });

                        </script>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Örnek Tipi</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="example_type" title="Hangi örnek tipi alınacaksa uygun olan tanımlama seçilmelidir.">
                                    <option selected disabled class="text-white bg-danger">Örnek tipi seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='ORNEK_TIPI'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["example_type"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" > <?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="row">
                            <div class="col-5">
                                <label >Çalışma Şekli</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="way_of_working" title="Tahlil 'Cihazda' mı, yoksa 'Manuel' mi yapılacağını seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Tahlil nasık yapılacak?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TETKIK_CALISMA_SEKLI'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["way_of_working"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label for="example-text-input">Seyrelti Oranı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="number" name="dilution_rate" id="example-text-input" title="Seyrelti yapılacaksa seyrelti oranı yazılır" value="<?php echo mb_strtoupper($lab_tahlil["dilution_rate"]);?>">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Cinsiyet</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="gender" title="Tahlil için cinsiyet kriteri varsa uygun olan cinsiyeti seçiniz.">
                                    <option selected disabled class="text-white bg-danger">Cinsiyet kriteri seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TEKTIK_CINSIYET'") ;
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["gender"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Kontrol Aktif</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="control_active" title="Tahlil ile ilgili kontrol çalışması yapılıyor mu?">
                                    <option selected disabled class="text-white bg-danger">Çalışma yapılıyor mu ?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='KONTROL_AKTIF'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["control_active"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Randevu Profili</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="appointment_profile_code" title="Testin sonucunun ne zaman alınabileceği kriterleri belirlenmektedir.">
                                    <option selected disabled class="text-white bg-danger">Sonucun ne zaman alınablieceğini seçiniz..</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='TETKIK_RANDEVU_PROFILI' ");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["appointment_profile_code"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>

                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label >Analizde Göster</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="show_analysis" title="Analizlerde testlerin görünmesini sağlar.">
                                    <option selected disabled class="text-white bg-danger">Analizde gösterilsin mi?</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='ANALIZDE_GOSTER'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($lab_tahlil["show_analysis"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

            </form>

            <div class="row mt-5" align="right">

                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-tetkik-tanim-duzenle">Kaydet</a>
                </div>

            </div>
        </div>

    </div>



    <script>

        $("body").off("click", "#btn-tetkik-tanim-duzenle").on("click", "#btn-tetkik-tanim-duzenle", function(e){
            var tanimli_tetkik_duzenle_form = $("#tanimli_tetkik_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=sql_tetkik_tanimi_duzenle',
                data:tanimli_tetkik_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w80_h45').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla.php?islem=listeyi-getir",function(e){
                        $('#tetkik-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w80_h45').window('close');
        });

    </script>
<?php } ?>


