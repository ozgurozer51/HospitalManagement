<?php include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$islem=$_GET["islem"];
$random_isim = uniqid();

//REÇETE EKLENEN İLAÇLAR TABLOSU BAŞLANGIÇ
 if($islem=="recete_eklenen_ilaclar") {
    $protokol = $_GET['getir'];

     $recete_tablo = singular("prescriptions", "patient_referenceid", $protokol);
     $receteid = $recete_tablo["id"];

     try{
     $recete_icerik_tablo = verilericoklucek("select prescription_medicine.id as prescriptionmedicineid,
                                    prescription_medicine.box_pieces as kullanilmasigerekendoz,
                                    prescription_medicine.*,
                                    prescription_drugs.*,
                                    prescription_drugs.id as ilac_id
                             from prescription_medicine
                                      inner join prescription_drugs on prescription_medicine.drug_id = prescription_drugs.id
                             where prescription_medicine.recipe_id =$receteid
                               and prescription_medicine.status='1'
                               and prescription_drugs.status='1'");

     }catch(Exception $e){ ?>
         <div class="alert alert-warning">Hastaya Henüz İlaç Girişi Yapılmadı</div>
    <?php exit(); } ?>

    <input type="hidden" class="eklenen-ilac-listesi-recete-id" value="<?php echo $receteid; ?>">

<!--REÇETE EKLENEN İLAÇLAR TABLOSU BAŞLANGIÇ-->
    <table class="table table-striped table-bordered display table-sm w-100" id="<?php echo $random_isim; ?>" style="font-size:13px !important; " >
        <thead>
        <tr>
            <th class="border border-dark">#</th>
            <th class="border border-dark">İLAÇ ADI</th>
            <th class="border border-dark">REÇETE TÜRÜ</th>
            <th class="border border-dark">KULLANIM ŞEKLİ</th>
            <th class="border border-dark">KULLANIM TİPİ</th>
            <th class="border border-dark">KULLANIM PERİYODU</th>
            <th class="border border-dark">AÇIKLAMA</th>
        </tr>
        </thead>
        <tbody class="eklenen_ilac_sec">
      <?php foreach ((array)$recete_icerik_tablo as $item ) {
            $drug_use_form=$item["drug_use_form"];
            $drug_use_type=$item["drug_use_type"];
            $drug_use_period=$item["drug_use_period"]; ?>
            <tr id="<?php echo $item["prescriptionmedicineid"]; ?>"  ilac_id="<?php echo $item['ilac_id']; ?>" class="recete_ilac_sec" >
                <td class="border border-dark"><input class="form-check-input recete_ilac_sec_btn" type="checkbox"  id="<?php echo $item["prescriptionmedicineid"]; ?>" data-drug-name="<?php echo $item["drug_name"]; ?>" data-drug-use-form="<?php echo $drug_use_form; ?>" drug-use-type-id="<?php echo $item["drug_use_type"]; ?>" drug-use-period-id="<?php echo $item["drug_use_period"]; ?>" box-pieces-id="<?php echo $item["kullanilmasigerekendoz"]; ?>" drug-description-id="<?php echo $item["drug_description"]; ?>" ></td>
                <td class="border border-dark"><?php echo $item["drug_name"]; ?></td>
                <td class="border border-dark" <?php if($item["recipe_type"]=="Yeşil"){?>style="color: green; font-weight: bold"<?php }else if ($item["recipe_type"]=="Mor"){?>style="color: purple; font-weight: bold"<?php }else if ($item["recipe_type"]=="Kırmızı"){?>style="color: red; font-weight: bold"<?php }else if ($item["recipe_type"]=="Turuncu"){?>style="color: orangered; font-weight: bold"<?php }?>><?php echo $item["recipe_type"];?></td>
                <td class="border border-dark"><?php if ($drug_use_form!=''){echo islemtanimgetir($drug_use_form); }  ?></td>
                <td class="border border-dark"><?php if ($drug_use_type!=''){echo islemtanimgetir($drug_use_type); } ?></td>
                <td class="border border-dark"><?php if ($drug_use_period!=''){echo $item['drug_use_period']; } ?></td>
                <td class="border border-dark"><?php echo $item["drug_description"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<!--REÇETE EKLENEN İLAÇLAR TABLOSU BİTİŞ-->

<!--REÇETE EKLİ İLAÇ SEÇ DÜZENLE VE SİL SCRIPTLERİ BAŞLANGIÇ-->

     <div style="display: none" id="recete-sablon-form">
         <div class="row" >
             <div class="col-12 mt-3 floating-label">
                 <textarea id="template_name" class="form-control form-control-sm" name="template_name" placeholder="Şablon Adı" rows="2"></textarea>
                 <label for="drug_description" class="fw-bold">Şablon Adı</label>
             </div>
         </div>
     </div>

     <script>
         $(document).ready(function(){

             var clicked_control = 0;

             setTimeout(function() {
             $('#<?php echo $random_isim; ?>').DataTable({
                dom: "<'row'<'col-sm-12 col-md-12'B>>" +
                         "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                 //"responsive":true,
                 "pageLength":false,
                 "scrollX": true,
                 "scrollY": "25vh",
                 "searching":false,
                 "paging":false,
                 "info":false,
                 "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                 buttons: [
                     {
                         text: '<i class="fas fa-check"></i> Tümünü Seç',
                         className:'btn btn-primary text-white all-select',
                         action: function ( e, dt, button, config ) {

                             if (clicked_control==0){
                                 $(".all-select").addClass("clicked");
                                 clicked_control = 1;
                                 $('.recete_ilac_sec_btn').prop("checked" , true);
                                 $('.recete_ilac_sil_open_pop').prop("disabled" , false);
                                 $('.recete_ilac_duzenle_open_pop').prop("disabled" , true);
                                 $(".all-select").html("<i class='fas fa-check'></i> Tümünü Seç Aktif");
                             }else{
                                 $(".all-select").removeClass("clicked");
                                 clicked_control = 0;
                                 $('.recete_ilac_sec_btn').prop("checked" , false);
                                 $('.recete_ilac_duzenle_open_pop').prop("disabled" , false);
                                 $(".all-select").html("<i class='fas fa-check'></i> Tümünü Seç");
                             }

                         }

                     },


                     {
                         text: '<i class="fas fa-edit"></i> Düzenle',
                         className:'btn btn-warning text-white recete_ilac_duzenle_open_pop',
                     },

                     {
                         text: '<i class="fas fa-trash"></i> Seçilen İlacı Sil',
                         className:'btn btn-danger recete_ilac_sil_open_pop',
                         attr:{"disabled":"disabled"},

                         action: function ( e, dt, button, config ) {

                             alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='İlaç Silme Nedeni..'></textarea><input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function () {

                                 if (clicked_control == 1) {

                                     var ilac_id = [];
                                     $(".recete_ilac_sec_btn:checked").off().each(function () {
                                         ilac_id.push($(this).attr('id'));
                                     });

                                     var delete_detail = $('#delete_detail').val();
                                     $.ajax({
                                         type: 'POST',
                                         url: 'ajax/recete/recetesql.php?islem=grup-ilac-sil',
                                         data: {
                                             ilac_id,
                                             delete_detail,
                                         },
                                         success: function (e) {
                                             $("#sonucyaz").html(e);
                                             $.get("ajax/recete/recetetablo.php?islem=recete_eklenen_ilaclar", {getir:<?php echo $protokol; ?> }, function (getVeri) {
                                                 $('#recete_eklenen_ilac_tablo').html(getVeri);
                                             });
                                             $('.alertify').remove();
                                         }
                                     });

                                 } else {
                                     var drug_id = $('.recete_ilac_sec_btn:checked').attr('id');
                                     var delete_detail = $('#delete_detail').val();
                                     $.ajax({
                                         type: 'POST',
                                         url: 'ajax/recete/recetesql.php?islem=sql_recete_ilac_sil',
                                         data: {
                                             drug_id,
                                             delete_detail,
                                         },
                                         success: function (e) {
                                             $("#sonucyaz").html(e);
                                             $.get("ajax/recete/recetetablo.php?islem=recete_eklenen_ilaclar", {getir:<?php echo $protokol; ?> }, function (getVeri) {
                                                 $('#recete_eklenen_ilac_tablo').html(getVeri);
                                             });
                                             $('.alertify').remove();
                                         }
                                     });
                                 }

                             }, function () {
                                 alertify.warning('Silme İşleminden Vazgeçtiniz')
                             }).set({
                                 labels: {
                                     ok: "Onayla",
                                     cancel: "Vazgeç"
                                 }
                             }).set({title: "İlaç Silme İşlemini Onayla"});

                         }


                     },

                     {
                         text: 'Şablona Ekle ',
                         className:'btn btn-secondary',
                         action: function ( e, dt, node, config ) {
                             alertify.confirm( $('#recete-sablon-form').html() , function () {
                                 var templates_name = $('.ajs-modal').find("#template_name").val();
                                 var receipt_id = <?php echo $receteid ?>;
                                 $.ajax({
                                     type: 'POST',
                                     url: 'ajax/recete/recetesql.php?islem=recete-sablonu-ekle',
                                     data: { receipt_id , templates_name },
                                     success: function (e) {
                                         $(".sonucyaz").html(e);
                                     }
                                 });
                             }, function () {
                             }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Şablon Ekleme İşlemi"});
                         }
                     },
                     {
                         extend: 'print',
                         text: '<i class="fas fa-print"></i> Yazdır',
                         className:'btn btn-dark',
                         autoPrint: true,
                         title: '',
                         exportOptions: {
                             columns: [1, 2 , 3 , 4 , 5]
                         },
                         orientation: 'landscape',
                         pageSize: 'LEGAL',
                         customize: function(win) {
                             var print_body  = $('.recete-yazdir').html();
                             $(win.document.body).prepend(print_body);
                             $(win.document.body).css("color" , "dark");
                             $(win.document.body).find( 'table' ).addClass( 'compact' ).css( 'font-size', 'inherit' )
                         },
                         init: function(api, node, config) {
                           //  $(node).removeClass('btn-secondary')
                         }
                     }

                     ],

             });
             }, 100);

            $(".recete_ilac_sec").click(function () {
                $(".recete_ilac_sil_open_pop").attr("disabled" , false);
                if (clicked_control==0){
                    $(".recete_ilac_sec_btn").prop("checked", false);
                    $(this).find(".recete_ilac_sec_btn").prop("checked", true);
                    $('.recete_ilac_duzenle_open_pop').prop("disabled" , false);
                    $('.recete_ilac_sil_open_pop').prop("disabled" , false);
                }else{

                    if($(this).find(".recete_ilac_sec_btn").is(":checked")){
                        $(this).find(".recete_ilac_sec_btn").prop("checked", false);
                    }else {
                        $(this).find(".recete_ilac_sec_btn").prop("checked", true);
                    }
                }


            });

            var ilac_duzenle = $('#recete_ilac_duzenle_form').html();
            $('#recete_ilac_duzenle_form').remove();

            $(document).on('click', '.recete_ilac_duzenle_open_pop', function () {
                setTimeout(function() {
                    var doz = $('.recete_ilac_sec_btn:checked').attr('box-pieces-id');
                    $('.ilacduzenle').find('#box_pieces').val(doz);

                    var aciklama  = $('.recete_ilac_sec_btn:checked').attr('drug-description-id');
                    $('.ilacduzenle').find('#drug_description').val(aciklama);

                    var period =  $('.recete_ilac_sec_btn:checked').attr('drug-use-period-id');
                    $('.ilacduzenle').find('#drug_use_period').val(period);

                    var kullanim_tipi  = $('.recete_ilac_sec_btn:checked').attr('drug-use-type-id');
                    $('.ilacduzenle').find('#drug_use_type').val(kullanim_tipi);

                    var kullanim_sekli  = $('.recete_ilac_sec_btn:checked').attr('data-drug-use-form');
                    $('.ilacduzenle').find('#drug_use_form').val(kullanim_sekli);

                    var ilac_adi  = $('.recete_ilac_sec_btn:checked').attr('data-drug-name');
                    $('.ilacduzenle').find('#drug_name').val(ilac_adi);

                }, 500);

                alertify.confirm(ilac_duzenle, function(){
                    var drug_use_form = $('.ilacduzenle').find('#drug_use_form').val();
                    var drug_use_type = $('.ilacduzenle').find('#drug_use_type').val();
                    var box_pieces = $('.ilacduzenle').find('#box_pieces').val();
                    var drug_description = $('.ilacduzenle').find('#drug_description').val();
                    var drug_use_period = $('.ilacduzenle').find('#drug_use_period').val();
                    var update_datetime = $('.ilacduzenle').find('#update_datetime').val();
                    var drug_id = $('.recete_ilac_sec_btn:checked').attr('id');
                    $.ajax({
                        url:'ajax/recete/recetesql.php?islem=sql_recete_ilac_duzenle',
                        type:'POST',
                        data:{
                            drug_use_form,
                            drug_use_type,
                            box_pieces,
                            update_datetime,
                            drug_description,
                            drug_use_period,
                            drug_id
                        },
                        success:function(e){
                            $("#sonucyaz").html(e);
                            $.get( "ajax/recete/recetetablo.php?islem=recete_eklenen_ilaclar", { getir:<?php echo $protokol; ?> },function(getVeri){
                                $('#recete_eklenen_ilac_tablo').html(getVeri);
                            });
                        }
                    });

                }, function(){ alertify.warning('İlaç Düzenleme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Kaydet", cancel: "Vazgeç"}}).set({title:"İlaç Düzenle"});

            });
        });


    </script>

    <!--REÇETE EKLİ İLAÇ SEÇ DÜZENLE VE SİL SCRIPTLERİ BİTİŞ-->


    <!--REÇETE İLAÇ DÜZENLE FORM BAŞLANGIÇ-->

    <form id="recete_ilac_duzenle_form">
        <div class="ilacduzenle">

            <div class="col-12 floating-label mt-3">
                    <input class="form-control" disabled type="text" id="drug_name" name="drug_name" placeholder=" ">
                    <label for="box_pieces" class="fw-bold">İlaç Adı</label>
            </div>

            <div class="col-12 floating-label mt-3">
                            <select class="form-select" name="drug_use_form" id="drug_use_form" >
                                <option selected disabled class="text-white bg-danger">Kullanım Şekli Seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='ILAC_KULLANIM_SEKLI' and transaction_definitions.status='1'");
                                foreach ($sql as $item ) { ?>
                                    <option value="<?php echo $item["id"]; ?>" ><?php echo $item["definition_name"]; ?></option>
                                <?php }?>
                            </select>
                            <label  for="drug_use_form-name"  class="fw-bold">Kullanım Şekli</label>
                </div>

            <div class="col-12 floating-label mt-3">
                            <select class="form-select" name="drug_use_type" id="drug_use_type" >
                                <option selected disabled class="text-white bg-danger">Kullanım Tipi Seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='ILAC_KULLANIM_TIPI' and transaction_definitions.status='1'");
                                foreach ($sql as $item ) { ?>
                                    <option value="<?php echo $item["id"]; ?>"> <?php echo $item["definition_name"]; ?></option>
                                <?php }?>
                            </select>
                            <label  for="drug_use_type-name"  class="fw-bold">Kullanım Tipi</label>
                </div>

            <div class="col-12 floating-label mt-3">
                            <select class="form-select" name="drug_use_period" id="drug_use_period" >
                                <option selected disabled class="text-white bg-danger">Kullanım Periyodu Seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from transaction_definitions WHERE definition_type = 'ILAC_KULLANIM_PERIYODU' and transaction_definitions.status='1'");
                                foreach ($sql as $item ) { ?>
                                    <option  value="<?php echo $item["id"]; ?>"> <?php echo $item["definition_name"]; ?></option>
                                <?php }?>
                            </select>
                            <label  for="drug_use_period-name"  class="fw-bold">Kullanım Periyodu</label>
                        </div>

            <div class="col-12 floating-label mt-3">
                        <input class="form-control"  type="number" id="box_pieces" name="box_pieces" placeholder=" ">
                        <label  for="box_pieces-name" class="fw-bold">Doz</label>
                        <input class="form-control"  type="text" hidden id="update_datetime" name="update_datetime" value="<?php echo $simdikitarih ?>">
            </div>

            <div class="col-12 floating-label mt-3">
                        <textarea class="form-control" id="drug_description" name="drug_description" placeholder="Açıklama Bilgisi" ></textarea>
                        <label for="drug_description" class="fw-bold">Açıklama</label>
            </div>

        </div>
    </form><!-- form bitiş -->

    <!--REÇETE İLAÇ DÜZENLE FORM BİTİŞ-->


<?php }//REÇETE EKLENEN İLAÇLAR BİTİŞ


//GEÇMİŞ REÇETE TABLOSU BAŞLANGIÇ

else if($islem=="gecmis_recete_tablo"){
    $protokol = $_GET['getir']; ?>


    <table class="table mt-2 table-bordered table-hover display nowrap table-sm w-100" id="datatable_gecmis_recete">
        <thead>
        <tr>
            <th class="border border-dark">#</th>
            <th class="border border-dark">REÇETE TARİHİ</th>
            <th class="border border-dark">HEKİM</th>
            <th class="border border-dark">Birim</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $say=0;
        $hastakayit=singular("patient_registration","protocol_number",$protokol);

        $TC_KIMLIK=$hastakayit["tc_id"];
        $sql=verilericoklucek("select * from patient_registration left join units on patient_registration.outpatient_id = units.id where patient_registration.tc_id='$TC_KIMLIK'");
        foreach ($sql as $row){
            $protokol_no=$row["protocol_number"];
            $sql=verilericoklucek("select * from prescriptions where prescriptions.patient_referenceid='$protokol_no'and prescriptions.status='1'");
            foreach ((array)$sql as $item ) {
                $say++; ?>
                <tr id="<?php echo $item["id"]; ?>" class="eski_recete_sec">
                    <td class="border border-dark"><input class="form-check-input eski_recete_sec_btn" type="radio"  id="<?php echo $item["id"]; ?>"></td>
                    <td class="border border-dark"><?php echo nettarih($item["insert_datetime"]);?></td>
                    <td class="border border-dark"><?php echo kullanicigetirid($item["insert_userid"]);?></td>
                    <td class="border border-dark"><?php echo $row["department_name"];?></td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function(){
            $('#datatable_gecmis_recete').DataTable({
                "responsive": true,
                "language": { "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json" },
            });
            $('[id="' + <?php echo $protokol ?> + '"]').prop("checked", true);

        $(".eski_recete_sec").click(function () {
            $(".eski_recete_sec_btn").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            $.get("ajax/recete/recetetablo.php?islem=gecmis_recete_ilac_icerik", {getir: getir}, function (getVeri) {
                $('#gecmis_recete_eklenen_ilac_tablo').html(getVeri);
            });
        });
        });
    </script>
<?php } //GEÇMİŞ REÇETE TABLOSU BİTİŞ


//GEÇMİŞ REÇETE İLAÇ İÇERİK TABLOSU BAŞLANGIÇ

else if($islem=="gecmis_recete_ilac_icerik"){
    $receteid = $_GET['getir'];?>

    <script>
        $(document).ready(function(){
            $('#datatable_recete_eklenen_ilaclar').DataTable( {
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        } );
    </script>

    <div class="mt-2" "></div>
    <table class="table table-bordered table-sm table-hover display nowrap w-100" id="datatable_recete_eklenen_ilaclar">
        <thead>
        <tr>
            <th class="border border-dark">İLAÇ ADI</th>
            <th class="border border-dark">REÇETE TÜRÜ</th>
            <th class="border border-dark">KULLANIM ŞEKLİ</th>
            <th class="border border-dark">KULLANIM TİPİ</th>
            <th class="border border-dark">KULLANIM PERİYODU</th>
            <th class="border border-dark">DOZ</th>
            <th class="border border-dark">AÇIKLAMA</th>
        </tr>
        </thead>
        <tbody class="eklenen_ilac_sec" style="cursor: pointer;" >
        <?php $sql =verilericoklucek("select prescription_medicine.id  as prescriptionmedicineid,
                                    prescription_medicine.box_pieces as kullanilmasigerekendoz,
                                    prescription_medicine.*,
                                    prescription_drugs.*
                             from prescription_medicine
                                      inner join prescription_drugs on prescription_medicine.drug_id = prescription_drugs.id
                             where prescription_medicine.recipe_id = '$receteid'
                               and prescription_medicine.status = '1'
                               and prescription_drugs.status = '1'");

        foreach ($sql as $item ) {
            $drug_use_form=$item["drug_use_form"];
            $drug_use_type=$item["drug_use_type"];
            $drug_use_period=$item["drug_use_period"];  ?>

            <tr id="<?php echo $item["prescriptionmedicineid"]; ?>" class="eski_recete_sec">
                <td class="border border-dark"><?php echo $item["drug_name"]; ?></td>
                <td class="border border-dark" <?php if($item["recipe_type"]=="Yeşil"){?>style="color: green; font-weight: bold"<?php }else if ($item["recipe_type"]=="Mor"){?>style="color: purple; font-weight: bold"<?php }else if ($item["recipe_type"]=="Kırmızı"){?>style="color: red; font-weight: bold"<?php }else if ($item["recipe_type"]=="Turuncu"){?>style="color: orangered; font-weight: bold"<?php }?>><?php echo $item["recipe_type"];?></td>
                <td class="border border-dark"><?php if ($drug_use_form!=''){echo islemtanimgetir($drug_use_form); }  ?></td>
                <td class="border border-dark"><?php if ($drug_use_type!=''){echo islemtanimgetir($drug_use_type); }  ?></td>
                <td class="border border-dark"><?php if ($drug_use_period!=''){echo $drug_use_period; } ?></td>
                <td class="border border-dark"><?php echo $item["kullanilmasigerekendoz"]; ?></td>
                <td class="border border-dark"><?php echo $item["drug_description"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php }
//GEÇMİŞ REÇETE İLAÇ İÇERİK TABLOSU BİTİŞ

//İLAÇLAR TABLOSU BAŞLANGIÇ
else if($islem=="sql_ilac_ara"){
    $drug_name=$_GET['drug_name'];

    if(!isset($drug_name)){ ?>
        <script>
            $(document).ready(function(){
                $('#ilaclarin_listesi').DataTable( {
                    "responsive":true,
                    "searching":false,
                    "lengthChange": false,
                    "paging": false,
                    "scrollX":true,
                    "scrollY":"50vh",
                    "info": false,
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                    },
                } );
            });
        </script>

    <?php } ?>

    <table class="table table-bordered table-hover table-sm display nowrap w-100" id="ilaclarin_listesi">
        <thead>
        <tr>
            <th class="border border-dark">İLAÇ ADI</th>
            <th class="border border-dark">REÇETE TÜRÜ</th>
        </tr>
        </thead>
        <tbody id="livesearcasdah">

        <?php $ilaclar=verilericoklucek("select * from prescription_drugs where (lower(drug_name) like '%$drug_name%' or upper(drug_name) like '%$drug_name%') and status='1' fetch first 10 rows only");
        foreach ($ilaclar as $item){ ?>
            <tr id="<?php echo $item["id"]; ?>" class="ilac_sec">
                <td class="border border-dark"><?php echo $item["drug_name"];?></td>
                <td class="border border-dark" <?php if($item["recipe_type"]=="Yeşil"){?>style="color: green; font-weight: bold" <?php }else if ($item["recipe_type"]=="Mor"){ ?>style="color: purple; font-weight: bold"<?php } else if ($item["recipe_type"]=="Kırmızı"){?>style="color: red; font-weight: bold"<?php }else if ($item["recipe_type"]=="Turuncu"){?>style="color: orangered; font-weight: bold"<?php }?>><?php echo $item["recipe_type"];?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>



<!--İLAÇLAR TABLOSU BİTİŞ---------------------------------------------------------------------------------------------->
<!--Şablon Tablosu----------------------------------------------------------------------------------------------------->

<?php } else if($islem=="sablonlar"){ ?>

    <table class="table table-bordered table-sm table-hover display nowrap w-100" id="<?php echo "table-1-".$random_isim ?>">
        <thead>
        <tr>
            <th>Seç</th>
            <th>Şablon Adı</th>
            <th>Reçete İd</th>
        </tr>
        </thead>
        <tbody>
        <?php  $sql=verilericoklucek("select receipt_templates.*,prescriptions.*,
               receipt_templates.id as sablon_id,
               prescriptions.id as recete_id
               from receipt_templates
                   inner join prescriptions on prescriptions.id = receipt_templates.receipt_id
               where receipt_templates.insert_userid = $_SESSION[id] and receipt_templates.status = 1");
        foreach ($sql as $item){ ?>
            <tr class="template_select">
                <td><input type="radio" class="sablon_sec" id="sablon_sec_701" name="radioname" recete-id="<?php echo $item['recete_id']; ?>" data-id="<?php echo $item['sablon_id']; ?>"></td>
                <td><?php echo $item["templates_name"]; ?></td>
                <td><?php echo $item["receipt_id"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>

        setTimeout(function() {
        $('#<?php echo "table-1-".$random_isim ?>').DataTable({
            "pageLenght":false,
            "paging":false,
            "info":false,
            "searching":false,
            "scrollX":true,
            "scrollY":"30vh"
        });
        }, 100);

    </script>

<?php }if($islem == "ilac-listesi-json"){

    $drug_name =  $_POST['drug_name'];

    if($drug_name) {
        $ek_sorgu = "(lower(prescription_drugs.drug_name) like '%$drug_name%' or upper(prescription_drugs.drug_name) like '%$drug_name%') and";
    }

    $sql = sql_select("
    select user_drug_favorite.id as fav_id, 
    prescription_drugs.id as ilac_id , 
           *
from prescription_drugs
         left join user_drug_favorite on prescription_drugs.id = user_drug_favorite.drug_id
         and user_drug_favorite.status = 1
         and user_drug_favorite.user_id = $_SESSION[id]
where $ek_sorgu prescription_drugs.status='1'
   fetch first 10 rows only");

    echo json_encode($sql);

}if($islem=='favori-listesi'){

     $sql = json_encode(sql_select("
 select *, user_drug_favorite.id as fav_id
from user_drug_favorite
         inner join prescription_drugs on prescription_drugs.id = user_drug_favorite.drug_id
where user_drug_favorite.status = 1
  and user_drug_favorite.user_id =$kullanici_id
 "));


     if($sql){
         echo $sql;
     }else{
       echo 505;
     }

}






