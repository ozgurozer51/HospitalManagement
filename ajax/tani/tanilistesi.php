<?php  include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$islem = $_GET['islem'];
$kullanicininidsi=$_SESSION["id"];
$random_name = uniqid();

if ($islem=="taniara") {
    $q=$_GET["q"];
    $protokolno=$_GET["protokolno"];
    $hasya_kayit=singularactive("patient_registration","protocol_number",$protokolno);
    if ($hasya_kayit['outpatient_id']==''){
        $birimadi=$hasya_kayit['service_id'];
    }else{
        $birimadi=$hasya_kayit['outpatient_id'];
    }
    $modul=$_GET["modul"];
    $tip=$_GET["tip"];
    $hastalarid=$_GET["hastalarid"];
    if($kullanicininidsi){
        if(strlen($q)<2){
            echo "En az 2 karakter veya rakam girmeniz gerekmektedir"; ?>
            <script>
                var islemturu='favori_listem';
                var userid="<?php echo $_SESSION['id']; ?>";
                var protokolno = "<?php echo $protokolno; ?>";
                var hastalarid = "<?php echo $hastalarid; ?>";
                var modul="<?php echo $modul; ?>";
                var tip="<?php echo $tip; ?>";
                $.get("ajax/tani/tanifavekle.php", { "userid":userid,"islem":islemturu,"protokolno":protokolno,"hastalarid":hastalarid,"modul":modul,"tip":tip},function(e){
                    $('#livesearcasdah').html(e);
                });
            </script>

      <?php   }else{  ?>

<!--            <input type="hidden" class="diagnosis-modul-main" value="--><?php //echo $_GET['diagnosis_modul']; ?><!--">-->

            <table  id="id-<?php echo $random_name;?>" class="table table-striped table-bordered w-100" style="font-size: 13px;">
                <thead>
                <tr>
                    <th></th>
                    <th>Tanı Kodu</th>
                    <th>Tanı Adı</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                <?php $kullanicid=$_SESSION['id'];
                    $refakatcicrud=yetkisorgula($kullanicid, "taniinsert");

                    if ($q =="null"){
                        $sql=verilericoklucek("SELECT * FROM diagnoses WHERE status='1'  FETCH FIRST 10 ROWS ONLY");
                    }else{
                       $sql=verilericoklucek("SELECT * FROM diagnoses WHERE   ( lower(diagnoses_name) LIKE '%$q%' or  upper(diagnoses_name) LIKE '%$q%' ) or ( lower(diagnoses_id) LIKE '%$q%' or upper(diagnoses_id) LIKE '%$q%') and status='1'  FETCH FIRST 7 ROWS ONLY");
                    }

                foreach ((array) $sql as $rowa) {

                    $cssekle="";
                    $sorbakalim=tek("SELECT * FROM users_diagnosis_favorites WHERE insert_userid='$kullanicininidsi' and diagnosis_id='$rowa[id]' AND status='1'");
                    if($sorbakalim["diagnosis_id"] == $rowa['id']) {
                        $cssekle="color: #e83e8c;";
                        $islem="cikar";
                    }else {
                        $islem="ekle";
                    } ?>

                    <tr    class="tanimlaekle tani-main">
                        <td id="favsonuc<?php echo $rowa["id"]; ?>" ><i islem="<?php echo $islem; ?>" islem_id="<?php  echo $rowa["id"]; ?>" protokolno="<?PHP ECHO $protokolno; ?>"  hastalarid="<?PHP ECHO $hastalarid; ?>"  taniekleyendoktor="<?php echo $kullanicininidsi; ?>"  taniid="<?php echo $rowa["id"]; ?>" style=" font-size: 18px;<?php echo $cssekle; ?>" class="mdi mdi-star-plus tanifaveklese"></i></td>
                        <td><?php echo $rowa["diagnoses_id"]; ?></td>
                        <td><?php echo $rowa["diagnoses_name"]; ?></td>
                        <td><?php if ($refakatcicrud==1){ ?>
                            <button tanikodu="<?PHP ECHO $rowa["diagnoses_id"]; ?>"  tip="<?PHP ECHO $_GET["tip"]; ?>" tani_id="<?PHP ECHO $rowa["id"]; ?>" taniekleyendoktor="<?PHP ECHO $kullanicininidsi; ?>" protokolno="<?PHP ECHO $protokolno; ?>"  hastalarid="<?PHP ECHO $hastalarid; ?>"   type="button" id="taniekle" class="taniekle btn btn-danger btn-sm">Ekle</button>
                            <?php } ?>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
            <script>

                $(document).ready(function() {
                    var tip="<?php echo tip; ?>";
                  $('#id-<?php echo $random_name; ?>').DataTable({
                        "paging":false,
                        "searching":false,
                        "info":false,
                        "pageLenght":false,
                        "scrollX":true,
                        "scrollY":"55vh"
                    });

                    $("body").off("click", ".tanifaveklese").on("click", ".tanifaveklese", function (e) {
                        var taniid = $(this).attr('taniid');
                        var islem = $(this).attr('islem');
                        var islem_id = $(this).attr('islem_id');
                        var taniekleyendoktor = $(this).attr('taniekleyendoktor');

                        if(islem =="cikar"){
                             $(this).css("color" ,  "#000000");
                            $(this).attr("islem" ,  "ekle");
                        }else{
                            $(this).css("color" ,  "#e83e8c");
                            $(this).attr("islem" ,  "cikar");
                        }

                        $.get( "ajax/tani/tanifavekle.php", {  taniid:taniid,taniekleyendoktor:taniekleyendoktor,islem:islem,islem_id:islem_id  },function(getveri) {
                            if(islem=="ekle"){
                                alertify.success("tanı favorilere eklendi");
                            }else{
                                alertify.error("tanı favorilerden çıkartıldı");
                            }

                        });
                    });

                    $("body").off("click", "#tani-sil").on("click", "#tani-sil", function (e) {
                        var special_id = $(this).closest('tr').attr('special-id');
                        $.ajax({
                            url : 'ajax/tani/taniislem.php?islem=tani-sil',
                            type: 'GET',
                            data:{special_id:special_id},
                            success: function (response) {
                                alertify.success("İşlem Başarılı");
                            },
                        });

                        table_poliklinik.ajax.url(table_poliklinik.ajax.url()).load();
                      //  $(this).closest('tr').remove();
                    });

                    $("body").off("click", "#taniekle-fav").on("click", "#taniekle-fav", function (e) {
                        setTimeout(function(){
                            table_poliklinik.ajax.url(table_poliklinik.ajax.url()).load();
                            },1000);
                    });

                    $("body").off("click", ".taniekle").on("click", ".taniekle", function (e) {
                        // var diagnosis_modul = $(".diagnosis-modul-main").val();
                        var protokolno = $(this).attr('protokolno');
                        var tanikodu = $(this).attr('tanikodu');
                        var taniekleyendoktor = $(this).attr('taniekleyendoktor');
                        var hastalarid = $(this).attr('hastalarid');
                        var tani_id = $(this).attr('tani_id');
                        var tip = $(this).attr('tip');
                        var modul="<?php echo $modul; ?>";
                        var birim="<?php echo $birimadi; ?>";
                        if (modul=='ayaktan'){
                            var diagnosis_modul =1;
                        }else{
                            var diagnosis_modul =2;
                        }
                        var islemturu='favori_listem';
                        $.get( "ajax/tani/taniislem.php?islem=taniekle", {tip:tip,protokolno:protokolno,tanikodu:tanikodu,taniekleyendoktor:taniekleyendoktor,hastalarid:hastalarid,tani_id:tani_id,diagnosis_modul:diagnosis_modul,birim:birim },function(e){
                         $('.sonucyaz').html(e);

                        <?php if($modul!='ayaktan'){ ?>
                        $.get("ajax/tani/tanilistesi.php?islem=tanilistesi", {tip:tip,protokolno:protokolno,"modul":modul },function(e){
                            $('#ontaniicerik').html(e);
                            $('.inputreset').val('');
                            $.get("ajax/tani/tanifavekle.php", { userid:taniekleyendoktor,islem:islemturu,protokolno:protokolno,hastalarid:hastalarid,"modul":modul,tip:tip},function(e){
                                $('#livesearcasdah').html(e);
                            });

                        });

                            <?php }else{ ?>
                            var tip="<?php echo islemtanimgetirname("Poliklinik");?>";

                        <?php } ?>

                        });

                        table_poliklinik.ajax.url(table_poliklinik.ajax.url()).load();
                    });

                } );
            </script>

        <?php } }

//**********************************************************************************************************************

 }  elseif ($islem=="tanilistesigetir"){

    $protokolno = $_GET['protokolno'];
    $modul = $_GET['modul'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokolno);
    $hastalarid=$hastakayit['patient_id'];
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);
    $hastalar = singularactive("patients", "id",$hastalarid);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);  ?>


                <?php if ($hastakayit['hospitalization_protocol']==''){ ?>
                    <script>
                        var poliklinik_protokol="<?php echo $protokolno; ?>";
                        var poliklinik_status=1;
                    </script>
               <?php }else{ ?>
                    <script>
                        var klinik_protokol="<?php echo $protokolno; ?>";
                        var poliklinik_protokol="<?php echo $hastakayit['hospitalization_protocol']; ?>";
                        var poliklinik_status=2;
                    </script>
               <?php } ?>

                <div class="easyui-layout" data-options="fit:true,split:true" style="width: 100%; height: 100%;">
                    <div data-options="region:'west' , split:'true'" title="Eklenen Tanılar" style="width: 50%;">

                            <div class="easyui-panel" fit="true">

                                    <table id="eklenen-tani-listesi-<?php echo $random_name; ?>" class="table table-sm table-bordered display nowrap" style="font-size: 13px !important;">
                                        <thead>
                                        <tr>
                                            <th id="clk-dom-701">Tani Tipi</th>
                                            <th>Birimi</th>
                                            <th>İşlem Tarihi</th>
                                            <th>Tanı Kodu</th>
                                            <th>Tanı Adı</th>
                                            <th>Birim</th>
                                            <th>Konum</th>
                                        </tr>
                                        </thead>
                                    </table>

                            </div>
                    </div>

                    <div title="Eklenecek Tanı Listesi" data-options="region:'center' , split:true" style="width: 50%;">

                         <div class="easyui-tabs"  id="tani-listesi-tab" fit="true" style="width: 100%; height: 100%;">
                            <input type="text" class="form-control" style="width: 100%;" onkeyup="showResultAAA(this.value)" placeholder="Tanı Adı İle arayınız.">
                             <div title="Tanı Listesi" id="tani-listesi-icerik">
                             </div>
                             <div title="Sık Kullanılan Tanılar">
                                 <div id="livesearcasdah"></div>
                             </div>
                         </div>

                        <script>
                            $(document).ready(function(){
                                $('#tani-listesi-tab').tabs({
                                    cache:true,
                                    onSelect: function(title, index){

                                        if(index == 1){
                                            var islemturu = 'favori_listem';
                                            var userid = "<?php echo $_SESSION['id']; ?>";
                                            var protokolno = "<?php echo $protokolno; ?>";
                                            var hastalarid = "<?php echo $hastalarid; ?>";
                                            var tip = "<?php echo $_GET['tip']; ?>";
                                            var modul = "<?php echo $modul; ?>";
                                            $.get("ajax/tani/tanifavekle.php", {
                                                "userid": userid,
                                                "islem": islemturu,
                                                "protokolno": protokolno,
                                                "hastalarid": hastalarid,
                                                "modul": modul,
                                                "tip": tip
                                            }, function (e) {
                                                $('#livesearcasdah').html(e);
                                            });
                                        }
                                    }
                                });
                            });
                        </script>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="recete_bilgi" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                <?php $hastakayit1 = singularactive("patient_registration", "hospitalization_protocol", $protokolno); ?>
                                <input <?php if ($taburcu['discharge_status'] == "1" || $izin['id'] != '' || $hastakayit1['hospitalization_protocol'] != '') {echo 'disabled';} ?> autocomplete="off" onkeyup="showResultAAA(this.value)" style=" height: 30px;" required type="text" class="form-control form-control-sm inputreset mb-2" placeholder="Tani kodu veya tani adiyla arayiniz" name="tani_adi" id="basicpill-firstname-input">
                            </div>
                            <div class="tab-pane fade" id="recete_up" role="tabpanel" aria-labelledby="recete-update" tabindex="0">
                                <div id="livesearcasdah" class="mb-2 mx-2">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


    <script>
        function afterTableInitialization(settings, json) {
            table1 = settings.oInstance.api();
            table1.columns().every( function () {

                var tanibtntip=$(".tanibtnsec").attr("tip");
                // var tipal=aData['tipi'];

                if(json[0]) {
                    json.forEach(function (data, index) {
                        // if (data['tipi'] != tip) {
                        if (data['tipi'] != tanibtntip) {
                            $('#main' + data["special_id"]).prop("disabled", true);
                            $("button[data-id='" + data["special_id"] + "']").prop("disabled", true);
                            // alert('geldi');
                        }
                    });
                }
            });
        }

        $('#eklenen-tani-listesi-<?php echo $random_name; ?>').on('xhr.dt', function ( e, settings, json, xhr ) {
            new $.fn.dataTable.Api( settings ).one( 'draw', function () {
                afterTableInitialization(settings, json);
            });
        });
        var modul_number="<?php  if ($modul=='ayaktan'){ echo 1; }else{ echo 2; } ?>";
        var tip="<?php echo $tip; ?>";
        var table_poliklinik = $('#eklenen-tani-listesi-<?php echo $random_name; ?>').DataTable({
            deferRender: true,
            scrollY: '60vh',
            "info":false,
            "paging":false,
            "searching":false,
            "dom": "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",

            fixedColumns: false,
            fixedHeader: false,
            scrollX: true,

            colReorder: true,

            ajax: {
                url: '/ajax/tani/tanilistesi.php?islem=recete-tani-listesi',
                data:{"protokolno":<?php echo $_GET['protokolno']; ?>,"tip":<?php echo $_GET['tip']; ?>,"modul":"<?php echo $_GET['modul']; ?>" },
                processing: true,
                method: 'GET',
                dataSrc: ''
            },

            "createdRow": function (row, data, dataIndex) {
                $(row).addClass('tr'+data['special_id']);
            },

            "fnRowCallback": function (nRow, aData) {
                var $nRow = $(nRow);
                var tip="<?php echo $tip; ?>"
                $title = `${aData['special_id']}`;
                $nRow.attr("special-id", $title);


                var tanibtntip=$(".tanibtnsec").attr("tip");
                var tipal=aData['tipi'];



                setTimeout(function (){
                    $('#main' + aData['special_id']).find("option[value=" + aData['ana_tani'] + "]").prop("selected", true);
                    if (tip!=2 && aData['definition_name']=='Recete' && tanibtntip!=tipal){
                        $('.tr' + aData['special_id']).closest("tr").remove();
                    }
                 },500);

                return nRow;
            },

            "initComplete": function (settings, json) {
            },

            columns:[
                {
                    data: null,
                    <?php $sql = verilericoklucek("select * from transaction_definitions WHERE status='1' AND definition_type='TANI_TURU'"); ?>
                    render: function (data) { return '<select insert_id="' + data.tani_ekleyen + '" class="form-select selectboxtanitipi-'+data.tipi+' form-select-sm tani-adi-change tani-adi" id="main'+ data.special_id +'" style="width:100px;">' +
                        <?php foreach ($sql as $item){   ?>
                        '<option tani-kodu="<?php echo $item['definition_supplement']; ?>"   value="<?php echo $item['definition_supplement']; ?>" ><?php echo $item['definition_name']; ?></option>' +
                        <?php } ?>
                        '</select>'; }
                },
                {
                    data: null,
                    render: function (data) { return '<button id="tani-sil" data-id="'+ data.special_id +'" class="btn btn-danger buttontanitipi-'+data.tipi+' tani-iptal btn-sm"><i class="fas fa-trash"></i></button>'; }
                },
                {
                    data:null,
                    render:function (data){
                        var ktarihi = data.ktarihi;
                        var islem_tarihi = moment(ktarihi).format('DD/MM/YY H:mm:ss');
                        if (islem_tarihi == "Invalid date") {
                            islem_tarihi = "";
                        }
                        return islem_tarihi;
                    }
                },
                {data:'tanikodu'},
                {data:'diagnoses_name'},
                {data:'department_name'},
                {data:'definition_name'},
            ],

        });

        $("body").off("change", ".tani-adi-change").on("change", ".tani-adi-change", function (e) {
            var tani_id = $(this).closest('tr').attr('special-id');
            var main_diagnosis = $(this).val();

            if (poliklinik_status==1){
                $.ajax({
                    url : 'ajax/tani/taniislem.php?islem=tani-adi-guncelle',
                    type: 'POST',
                    data:{tani_id:tani_id,main_diagnosis:main_diagnosis,poliklinik_protokol:poliklinik_protokol},
                    success: function (response) {
                        alertify.success("İşlem Başarılı");
                    },
                    error:function (response) {
                        alertify.error("İşlem Başarısız");
                    },
                });
            }else if (poliklinik_status==2){
                $.ajax({
                    url : 'ajax/tani/taniislem.php?islem=tani-adi-guncelle',
                    type: 'POST',
                    data:{tani_id:tani_id,main_diagnosis:main_diagnosis,poliklinik_protokol:poliklinik_protokol,klinik_protokol:klinik_protokol},
                    success: function (response) {
                        alertify.success("İşlem Başarılı");
                    },
                    error:function (response) {
                        alertify.error("İşlem Başarısız");
                    },
                });
            }
            setTimeout(function(){
                table_poliklinik.ajax.url(table_poliklinik.ajax.url()).load();
            },500);

        });


        $("body").off("click", "#sik-kullanilan-tanilar").on("click", "#sik-kullanilan-tanilar", function (e) {

            var islemturu = 'favori_listem';
            var userid = "<?php echo $_SESSION['id']; ?>";
            var protokolno = "<?php echo $protokolno; ?>";
            var hastalarid = "<?php echo $hastalarid; ?>";
            var tip = "<?php echo $_GET['tip']; ?>";
            var modul = "<?php echo $modul; ?>";
            $.get("ajax/tani/tanifavekle.php", {
                "userid": userid,
                "islem": islemturu,
                "protokolno": protokolno,
                "hastalarid": hastalarid,
                "modul": modul,
                "tip": tip
            }, function (e) {
                $('#livesearcasdah').html(e);
            });

            setTimeout(function() {
              $('.sik-kullanilan-trigger').trigger("click");
            }, 1000);

        });

        <?php //} ?>

        var protokolno ="<?php echo $_GET['protokolno']; ?>";
        var modul ="<?php echo $_GET['modul']; ?>";
        $.get( "ajax/tani/tanilistesi.php?islem=taniara&q=null&hastalarid=<?php echo $hastalarid; ?>&tip=<?php echo $_GET["tip"]; ?>", { protokolno:protokolno,modul:modul },function(getVeri){
            $('#tani-listesi-icerik').html(getVeri);
        });

        function showResultAAA(str) {
            var protokolno ="<?php echo $_GET['protokolno']; ?>";
            var modul ="<?php echo $_GET['modul']; ?>";
            $.get( "ajax/tani/tanilistesi.php?islem=taniara&q="+str+"&hastalarid=<?php echo $hastalarid; ?>&tip=<?php echo $_GET["tip"]; ?>", { protokolno:protokolno,modul:modul },function(getVeri){
                $('#tani-listesi-icerik').html(getVeri);
            });
        }

        var protokolno = "<?php echo $_GET['protokolno']; ?>";
        var tip = "<?php echo $_GET['tip']; ?>";
        var modul = "<?php echo $_GET['modul']; ?>";
        <?php if ($modul!='ayaktan') { ?>

        $.get("ajax/tani/tanilistesi.php?islem=tanilistesi", {"protokolno":protokolno,"tip":tip,"modul":modul },function(e){
            $('#ontaniicerik').html(e);
        });
        <?php } else{  ?>
        var tip="<?php echo islemtanimgetirname("Poliklinik");?>";
        <?php } ?>

    </script>

<?php } else if($islem=="tanilistesi"){

    $protokolno = $_GET['protokolno'];
    $modul = $_GET['modul'];

    $hastakayit = singularactive("patient_registration", "protocol_number", $protokolno);

    $hastalarid=$hastakayit['patient_id'];
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);  ?>

<div class="easyui-panel" title="Eklenen Tanılar">

    <table  id="<?php echo $random_name; ?>"   class="table table-resposive table-sm table-bordered display nowrap w-100"  style="font-size: 13px !important;">
        <thead>
        <tr>
            <th></th>
            <?php if ($modul!='ayaktan'){ ?>
            <th></th>
            <?php } ?>
            <th>işlem Tarihi</th>
            <th>icd Kodu</th>
            <th>Tani Adi</th>
            <th>Birimi</th>
        </tr>
        </thead>
        <tbody>
        <?php $kullanicid=$_SESSION['id'];
        $taniupdate=yetkisorgula($kullanicid, "taniupdate");
        $tanidelete=yetkisorgula($kullanicid, "tanidelete");

        $service_control = singular("patient_registration","protocol_number", $protokolno);

          if(!isset($service_control["hospitalization_info"])){

            $ONTANIGETIR=verilericoklucek("select patient_record_diagnoses.id as taniid,patient_record_diagnoses.insert_datetime as ktarihi,patient_record_diagnoses.*,units.*,patient_registration.* from
                patient_record_diagnoses INNER JOIN patient_registration on patient_record_diagnoses.protocol_number=patient_registration.protocol_number
                                         INNER JOIN units on units.id=patient_registration.outpatient_id
            where patient_record_diagnoses.protocol_number='$protokolno'
                        and patient_record_diagnoses.diagnosis_type='{$_GET["tip"]}'
                        and patient_record_diagnoses.tc_id=patient_registration.patient_id
                        and patient_record_diagnoses.status='1'");
          }else {
              if ($_GET["tip"]==2){
                  $ONTANIGETIR = verilericoklucek("select patient_record_diagnoses.id as taniid,patient_record_diagnoses.insert_datetime as ktarihi,patient_record_diagnoses.*,units.*,patient_registration.* from
                                    patient_record_diagnoses INNER JOIN patient_registration on patient_record_diagnoses.protocol_number=patient_registration.protocol_number
                                                             INNER JOIN units on units.id=patient_registration.service_id
                                where patient_record_diagnoses.protocol_number='{$_GET["protokolno"] }'
                                  and patient_record_diagnoses.diagnosis_type='{$_GET["tip"]}'
                                  and patient_record_diagnoses.tc_id=patient_registration.patient_id
                                  and patient_record_diagnoses.status='1'");
              }
              else if ($_GET["tip"]==0){
                  $ONTANIGETIR=verilericoklucek("select patient_record_diagnoses.id as taniid,patient_record_diagnoses.insert_datetime as ktarihi,patient_record_diagnoses.*,units.*,patient_registration.* from
                patient_record_diagnoses INNER JOIN patient_registration on patient_record_diagnoses.protocol_number=patient_registration.protocol_number
                                         INNER JOIN units on units.id=patient_registration.outpatient_id
            where patient_record_diagnoses.protocol_number='$protokolno'
                        and patient_record_diagnoses.diagnosis_type='{$_GET["tip"]}'
                        and patient_record_diagnoses.tc_id=patient_registration.patient_id
                        and patient_record_diagnoses.status='1'");
              }
              else{
                  $ONTANIGETIR = verilericoklucek("select patient_record_diagnoses.id as taniid,patient_record_diagnoses.insert_datetime as ktarihi,patient_record_diagnoses.*,units.*,patient_registration.* from
                                    patient_record_diagnoses INNER JOIN patient_registration on patient_record_diagnoses.protocol_number=patient_registration.protocol_number
                                                             INNER JOIN units on units.id=patient_registration.service_id
                                where patient_record_diagnoses.protocol_number='{$_GET["protokolno"] }'
                                  and patient_record_diagnoses.diagnosis_type NOT IN (2)
                                  and patient_record_diagnoses.tc_id=patient_registration.patient_id
                                  and patient_record_diagnoses.status='1'");
              }

          }

        foreach ((array)  $ONTANIGETIR as $ONTANIG) {
            $ONTANi_ADi = singular("diagnoses", "id", $ONTANIG["diagnosis_id"]); ?>

            <tr >
                <td style="width:25%"><?php //if($ONTANIG["main_diagnosis"]!=1  && $taniupdate==1){?>
                    <select class="form-select form-select-sm" <?php if ($modul=='ayaktan'){ echo 'disabled'; }?> <?php hekimeaitolayankayitsorgula($hastakayit["doctor"],$_SESSION["id"]); ?>id="tanituru" tani_id="<?php echo $ONTANIG["taniid"]; ?>" hastalarid="<?php echo $hastalarid; ?>" tip="<?php echo $_GET["tip"]; ?>" protokolno="<?php echo $_GET["protokolno"]; ?>" style="width: 100px !important;">
                        <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='TANI_TURU'" ;

                        $sql=verilericoklucek($bolumgetir);
                        foreach ((array) $sql as $value){ ?>
                            <option  <?php if($ONTANIG["main_diagnosis"] == $value["definition_supplement"]) echo"selected"; ?>value="<?php echo $value["definition_supplement"]; ?>" ><?php echo $value['definition_name']; ?></option>
                        <?php  } ?>
                    </select>

                </td>
                <?php if ($modul!='ayaktan'){ ?>
                <td>
                    <?php if($ONTANIG["main_diagnosis"]!=1 && $tanidelete==1){ ?>
                        <button type="button"   <?php hekimeaitolayankayitsorgula($hastakayit["doctor"],$_SESSION["id"]); ?>tani_id="<?php echo $ONTANIG["taniid"]; ?>"  tip="<?PHP ECHO $_GET["tip"]; ?>" hastalarid="<?php echo $hastalarid; ?>"  protokolno="<?php echo $_GET["protokolno"]; ?>" class="tanisil btn kapat-btn btn-sm px-1 py-1"><i class="mdi mdi-trash-can"></i></button>&nbsp;
                    <?php } ?>
                </td>
             <?php } ?>
                <td><?php echo $ONTANIG["ktarihi"] ?></td>
                <td><?php echo $ONTANIG["diagnosis_code"] ?></td>
                <td><?php echo $ONTANi_ADi['diagnoses_name'] ?></td>
                <td><?php echo $ONTANIG["department_name"] ?></td>

            </tr>

        <?php } ?>

        </tbody>
    </table>

</div>

    <script>
        $(document).ready(function() {

                $("body").off("change", "#tanituru").on("change", "#tanituru", function (e) {
                var tanituru = $(this).val();
                var tani_id = $(this).attr('tani_id');
                var protokolno = $(this).attr('protokolno');
                var hastalarid = $(this).attr('hastalarid');
                var tip = $(this).attr('tip');
                $.get("ajax/tani/taniislem.php?islem=taniekle&islecm=anatanidegistir&islems=sil", {
                    tani_id: tani_id,
                    protokolno: protokolno,
                    tip: tip,
                    tanituru: tanituru,
                    hastalarid: hastalarid
                }, function (getVeri) {

                    alertify.success("Ana tani değiştirildi");

                    $.get("ajax/tani/tanilistesi.php?islem=tanilistesi", {protokolno:protokolno,tip:tip },function(e){
                        $('#ontaniicerik').html(e);
                    });

                });
            });

            $("body").off("click", ".tanisil").on("click", ".tanisil", function (e) {
                var tani_id = $(this).attr('tani_id');
                var protokolno = $(this).attr('protokolno');
                var hastalarid = $(this).attr('hastalarid');
                var tip = $(this).attr('tip');
                $.get( "ajax/tani/taniislem.php?islem=tani-sil-2", { tani_id:tani_id,protokolno:protokolno,tip:tip,hastalarid:hastalarid },function(getVeri){
                    $('.sonucyaz').html(getVeri);
                        $.get("ajax/tani/tanilistesi.php?islem=tanilistesi", {hastalarid:hastalarid,protokolno:protokolno,tip:tip },function(e){
                            $('#ontaniicerik').html(e);
                        });
                });
            });
        });

     var tani_table =   $('#<?php echo $random_name; ?>').DataTable({
            "scrollX": true,
            "scrollY": '60vh',
            "paging":false,
            "info":false,
            "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [{
                    text: 'Poliklik',
                    className:'btn btn-tani  btn-p btn-sm',
                    titleAttr:'Poliklik Tanılarını Listelemek İçin Tıklayınız',

                    action: function( e, dt, node, config ) {
                        var protokol_var_mi="<?php echo $hastakayit['hospitalization_protocol']; ?>";
                        if (protokol_var_mi==''){
                            var protokolno = "<?php echo $hastakayit['protocol_number']; ?>";
                        }else{
                            var protokolno = "<?php echo $hastakayit['hospitalization_protocol']; ?>";
                        }

                        var tip = "<?php echo islemtanimgetirname("Poliklinik");?>";
                        var modul = "<?php echo $_GET['modul']; ?>";
                        // $.get("ajax/tani/tanilistesi.php?islem=plktanilistesi", { protokolno:protokolno,tip:tip,modul:modul},function(e){
                        //     $('#ontaniicerik').html(e);
                        // });
                    }
                } <?php $hastakayit1 = singularactive("patient_registration", "hospitalization_protocol", $protokolno);
                if ($hastakayit1['hospitalization_protocol']!='') { ?>
                ,
                {
                    text: 'Yatış',
                    className:'btn  btn-sm btn-tani up-btn mx-2',
                    titleAttr:'Yatış Tanılarını Listelemek İçin Tıklayınız',
                    action: function( e, dt, node, config ) {
                        var protokolno = "<?php echo $_GET['protokolno']; ?>";
                        var tip = "<?php echo islemtanimgetirname("Klinik");?>";
                        var modul = "<?php echo $_GET['modul']; ?>";
                        $.get("ajax/tani/tanilistesi.php?islem=tanilistesi", {protokolno:protokolno,tip:tip,modul:modul },function(e){
                            $('#ontaniicerik').html(e);
                        });
                    }
                }
                <?php }  if ($hastakayit['hospitalization_protocol']!='') { ?>
                ,
                {
                    text: 'Yatış',
                    className:'btn  btn-sm btn-tani up-btn mx-2',
                    titleAttr:'Yatış Tanılarını Listelemek İçin Tıklayınız',

                    action: function( e, dt, node, config ) {
                        var protokolno = "<?php echo $_GET['protokolno']; ?>";
                        var tip = "<?php echo islemtanimgetirname("Klinik");?>";
                        var modul = "<?php echo $_GET['modul']; ?>";
                        $.get("ajax/tani/tanilistesi.php?islem=tanilistesi", {protokolno:protokolno,tip:tip,modul:modul },function(e){
                            $('#ontaniicerik').html(e);
                        });
                    }
                }
                <?php } ?>
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });
    </script>
<?php }

if($islem == "recete-tani-listesi"){
    $ek_sql='';
    $birim_tipi=$_GET['tip'];
    if ($birim_tipi!='' && $birim_tipi!=2){
        $ek_sql.="and patient_record_diagnoses.diagnosis_type={$birim_tipi}";
    }

    $sql_metin="select patient_record_diagnoses.id as taniid,
       patient_record_diagnoses.insert_datetime as ktarihi,units.department_name,
       patient_record_diagnoses.diagnosis_type as tipi,
       patient_record_diagnoses.id as special_id,
       patient_record_diagnoses.diagnosis_code as tanikodu,
       patient_record_diagnoses.main_diagnosis as ana_tani,
       patient_record_diagnoses.main_diagnosis as tani_kodu,
       patient_record_diagnoses.insert_userid as tani_ekleyen,
       patient_record_diagnoses.diagnosis_modul,
       patient_record_diagnoses.protocol_number,
       transaction_definitions.*,
       diagnoses.diagnoses_name from patient_record_diagnoses
           LEFT JOIN patient_registration on patient_record_diagnoses.protocol_number=patient_registration.protocol_number
             LEFT JOIN units on units.id=patient_record_diagnoses.diagnosis_unit
             LEFT JOIN diagnoses on diagnoses.id=patient_record_diagnoses.diagnosis_id
             LEFT JOIN transaction_definitions on patient_record_diagnoses.diagnosis_type=transaction_definitions.defination_last_code
where patient_record_diagnoses.status='1' and (patient_record_diagnoses.protocol_number in (select protocol_number from patient_registration where protocol_number='{$_GET["protokolno"]}') or
      patient_record_diagnoses.protocol_number in (select hospitalization_protocol from patient_registration where protocol_number='{$_GET["protokolno"]}'))
  and patient_record_diagnoses.tc_id=patient_registration.patient_id $ek_sql";

 $sql = verilericoklucek($sql_metin);

 echo json_encode($sql);

} ?>