<?php
include "../../controller/fonksiyonlar.php";
$islem= $_GET['islem'];
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$kullanicininidsi=$_SESSION["id"];
$poliklinikbilgisigetir = singular("patient_registration", "id", $_GET["deger"]);
$hastabilgisigetir = singular("patients", "id", $poliklinikbilgisigetir["patient_id"]);
$protokol_no =  $poliklinikbilgisigetir["protocol_number"];

$uniqid = uniqid();
$uniqid_2 = uniqid();
?>
<?php $kurumgetir = tek("select * from transaction_definitions where definition_type='KURUMLAR' and definition_code='{$hastabilgisigetir["institution"]}'"); ?>
<?php $cinsiyetgetir = tek("select * from transaction_definitions where definition_type='CINSIYET' and definition_supplement='{$hastabilgisigetir["gender"]}'"); ?>

<script type="text/javascript" src="assets/jquery-easyui-1.9.15/jquery.easyui.min.js"></script>


<div  id="w" class="w"></div>
<div  id="w2" class="w2"></div>

<div class="easyui-panel"  style="width:100%;">
    <a class="easyui-linkbutton" onclick="get_forms()"><i class="fa-solid fa-file-pen"></i> Formlar</a>
    <a class="easyui-linkbutton" onclick="get_epicrisis('<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>')"><i class="fa-solid fa-file-invoice"></i> Epkriz</a>
    <a class="easyui-linkbutton" onclick="get_consultation('<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>' , '<?php echo $hastabilgisigetir['id']; ?>')" ><i class="fa-solid fa-people-arrows"></i> Konsültasyon</a>
    <a class="easyui-linkbutton" onclick="get_anamnesis('<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>')"><i class="fa-solid fa-file-plus"></i> Annamez</a>
    <a class="easyui-linkbutton" id="dosya-islemleri" protokol-no="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>"><i class="fa-solid fa-file-export"></i> Dosya İşlemleri </a>
    <a class="easyui-linkbutton" id="barcode" protokol-no="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>"><i class="fa-solid fa-barcode"></i> Barkod</a>
    <a class="easyui-linkbutton yatistalep" protokol-no="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>"><i class="fa-solid fa-bed-pulse"></i> Yatış</a>
    <a class="easyui-linkbutton" id="recete" protokol-no="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>"><i class="fa-solid fa-receipt"></i> Reçete</a>
    <a class="easyui-linkbutton tanigetir" tip="0" diagnosis-modul="6" protokol-no="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>"><i class="fa-solid fa-person-dots-from-line"></i> Tanı</a>
</div>

<script>
    $('.w2').dialog({
        onClose: function () {
            $('.w2').html("");
        },
        cache: true,
        modal: true,
        fit: true,
        closed: true,
        iconCls: 'icon-save',
    });
    // $("body").off("click", "#anamnez-clk").on("click", "#anamnez-clk", function (e) {
    //     var protokol_no = $(this).attr("protokol-no");
    //     var servis =  $('.bg-yesil').attr("poliklinik-id");
    //     var hekim =  $('.bg-yesil').attr("doktor-id");
    //     if (protokol_no){
    //         $.get("ajax/anamnezislem.php?islem=anamnezbody", {protokol:protokol_no , servis:servis , hekim:hekim}, function (e) {
    //             $('#ana-icerik').html(e);
    //             $('#ana-modal').modal('show');
    //         });
    //     }else{
    //         alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
    //     }
    // });

    function get_anamnesis(protokol) {
        $('.w2').dialog({
            title: 'Anamnez İşlemleri - <?php echo $hastabilgisigetir["patient_name"]; ?> <?php echo $hastabilgisigetir["patient_surname"]; ?>',
            iconCls: ' fa-solid fa-clipboard-medical ',
        });
        $('#w2').dialog('open');
        $('#w2').dialog('refresh', 'ajax/anamnezislem.php?islem=anamnezbody&protokolno='+protokol+'');
    }

    function get_epicrisis(protokol) {
        $('.w2').dialog({
            title: 'Epikıriz İşlemleri - <?php echo $hastabilgisigetir["patient_name"]; ?> <?php echo $hastabilgisigetir["patient_surname"]; ?>',
            iconCls: ' fa-solid fa-clipboard-medical ',
        });
        $('#w2').dialog('open');
        $('#w2').dialog('refresh', 'ajax/yatis/yatismodalbody.php?islem=epikrizbody&protokolno='+protokol+'');
    }

    function get_consultation(protokol , patient_id) {
        $('#w2').dialog({
            title: 'Konsultasyon İşlemleri - <?php echo $hastabilgisigetir["patient_name"]; ?> <?php echo $hastabilgisigetir["patient_surname"]; ?>',
            iconCls: ' fa-solid fa-clipboard-medical ',
        });
        $('#w2').dialog('open');
        $('#w2').dialog('refresh', 'ajax/konsultasyon/konsultasyon.php?islem=konsultasyonbody&protocol_no='+protokol+'&patient_id='+patient_id+'');
    }

</script>


<div class="easyui-accordion"  style="width:100%;">
    <div title="Hasta Detay" data-options="iconCls:'fa-sharp fa-regular fa-hospital-user'" style="overflow:auto;padding:10px;">

        <div id="hasta-detay<?php echo $protokol_no; ?>">

            <div class="row">

                <div class="col-1">
                    <div id="live_camera"><img id="sonfotograf" src="assets/img/dummy-user.jpeg" alt="dummy user" style="height: 80%; width: 90%;"></div>
                    <span style="font-size: 12px;">   Pro. No  :  <?php echo $poliklinikbilgisigetir["protocol_number"]; ?></span>
                    <input type="hidden" id="hasta-kabul-protokol" value="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>">
                </div>

                <div class="col-xl-6">

                    <div class="row">
                        <div class="col-md-6">
                            <input class="easyui-textbox" label="Adı Soyadı:" labelWidth="90"  style="width: 100%;" readonly value="<?php echo $hastabilgisigetir["patient_name"]; ?> <?php echo $hastabilgisigetir["patient_surname"]; ?>">
                        </div>
                        <div class="col-md-6">
                            <input class="easyui-textbox" label="TC No:" labelWidth="90" style="width: 100%;" readonly value="<?php echo $hastabilgisigetir["tc_id"]; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <input class="easyui-textbox" label="Kurum:" labelWidth="90" style="width: 100%;" readonly value="<?php echo $kurumgetir["definition_name"]; ?>">
                        </div>
                        <div class="col-md-6">
                            <input class="easyui-textbox" label="Takip No:" labelWidth="90" style="width: 100%;" readonly value="<?php echo $hastabilgisigetir["provision_number"]; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <input class="easyui-textbox" label="Anne Adı:" labelWidth="90" style="width: 100%;"  readonly value="<?php echo $hastabilgisigetir["mother"]; ?>">
                        </div>
                        <div class="col-md-6">
                            <input class="easyui-textbox" label="Dosya Türü:" labelWidth="90" style="width: 100%;" readonly value="<?php echo $poliklinikbilgisigetir["protocol_number"]; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <input class="easyui-textbox" label="Baba Adı:" labelWidth="90" style="width: 100%;" readonly value="<?php echo $hastabilgisigetir["father"]; ?>">

                        </div>
                        <div class="col-xl-6 col-md-6">
                            <input class="easyui-textbox" label="Cinsiyet" labelWidth="90" style="width: 100%;" readonly value="<?php echo $cinsiyetgetir["definition_name"]; ?>">
                        </div>

                    </div>

                </div>

                <div class="col-xl-5">

                    <?php $sql = singular("epicrisis","protocol_id",$protokol_no); ?>

                    <input class="easyui-textbox" label="Şikayet:" labelWidth="90" style="width: 100%;"   id="complaint<?php echo $protokol_no; ?>" value="<?php echo $sql['complaint']; ?>">
                    <input class="easyui-textbox" label="Özgeçmiş:" labelWidth="90" style="width: 100%;"  id="curriculum_vitae<?php echo $protokol_no; ?>" value="<?php echo $sql['curriculum_vitae']; ?>">
                    <input class="easyui-textbox" label="Soygeçmiş:" labelWidth="90" style="width: 100%;" id="family_history<?php echo $protokol_no; ?>"  value="<?php echo $sql['family_history']; ?>">

                        <?php if($sql){ ?>
                            <input class="easyui-textbox story<?php echo $protokol_no; ?>"  label="Hikeyesi:" labelWidth="90" value="<?php echo $sql['story']; ?>"  id="epkiriz-guncelle<?php echo $protokol_no; ?>" data-id="<?php echo $sql['id']; ?>" style="width: 100%;">
                        <?php }else{ ?>
                            <input class="easyui-textbox story<?php echo $protokol_no; ?>" label="Hikeyesi:" labelWidth="90"  value="<?php echo $sql['story']; ?>"  id="epkiriz-onay<?php echo $protokol_no; ?>" style="width: 100%;">
                        <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $islemtanimlari = singularactive("transaction_definitions", "definition_name",'LAB'); ?>

<div class="easyui-tabs" data-options="fit:true" style="width:100%; height:100%;">
    <div title="Geçmiş İşlemler" data-options="iconCls:'fa-solid fa-rectangle-history-circle-user'" style="padding:3px">geçmiş işlem Ekranı:Hastanın poliklinikte ilk ve önceki gelişine ait tanı/ön tanı, tetkik vb görüntüleme.</div>
    <div title="Hizmetler" data-options="iconCls:'fa-brands fa-servicestack'  , cache:false , href:'ajax/istem/istemmodal.php?islem=hastayaeklenenhizmetler&protokolno=<?php echo $protokol_no; ?>&hizmet_tur=hizmet'" style="padding:3px"></div>
    <div title="İlaç Sarf" data-options="iconCls:'fa-solid fa-pills' ,  cache:false , href:'ajax/ilac_sarf/ilacsarflistesi.php?islem=hasta_ilacsarfi&protokolno=<?php echo $protokol_no; ?>&hastalarid=<?php  echo $poliklinikbilgisigetir['patient_id']; ?>' " style="padding:3px"></div>
    <div title="E-order" data-options="iconCls:'fa-brands fa-jedi-order' ,  cache:false , href:'ajax/yatis/yatislistesi.php?islem=orderlist&protokolno=<?php echo $protokol_no; ?>' " style="padding:3px"></div>
</div>




<script>
    setTimeout(function (){
        $('#epkiriz-guncelle<?php echo $protokol_no; ?>').textbox({
            disabled: false,
            buttonText:'Güncelle',
            buttonIcon:'icon-edit',
            onClickButton: function(){
                var complaint = $('#complaint<?php echo $protokol_no; ?>').val();
                var curriculum_vitae = $('#curriculum_vitae<?php echo $protokol_no; ?>').val();
                var family_history = $('#family_history<?php echo $protokol_no; ?>').val();
                var story = $('.story<?php echo $protokol_no; ?>').val();
                var id = $(this).attr("data-id");
                $.get("ajax/hastadetay/hastakabuldetay.php?islem=epikriz-guncelle", {
                    complaint: complaint,
                    curriculum_vitae: curriculum_vitae,
                    family_history: family_history,
                    story: story,
                    protocol_id:<?php echo $protokol_no; ?> ,
                    id: id
                }, function (getveri) {
                    $('.sonucyaz').html(getveri);
                });
            }
        });

        $('#epkiriz-onay<?php echo $protokol_no; ?>').textbox({
            disabled: false,
            buttonText:'Onayla',
            buttonIcon:'icon-save',
            buttonWidth:'200',
            onClickButton: function(){
                var complaint = $('#complaint<?php echo $protokol_no; ?>').val();
                var curriculum_vitae = $('#curriculum_vitae<?php echo $protokol_no; ?>').val();
                var family_history = $('#family_history<?php echo $protokol_no; ?>').val();
                var story = $('.story<?php echo $protokol_no; ?>').val();
                $.get("ajax/hastadetay/hastakabuldetay.php?islem=epikriz-ekle", {
                    complaint: complaint,
                    curriculum_vitae: curriculum_vitae,
                    family_history: family_history,
                    story: story,
                    protocol_id:<?php echo $protokol_no; ?>}, function (getveri) {
                    $('.sonucyaz').html(getveri);
                });
            }
        });
    },100);

    $("body").off("click", ".hasta-kabul-tab").on("click", ".hasta-kabul-tab", function (e) {
        $('.hasta-kabul-tab').removeClass("text-white");
        $('.hasta-kabul-tab').removeClass("up-btn");
        $(this).addClass("text-white");
        $(this).addClass("up-btn");
    });

        $("body").off("click", ".tedavigiris").on("click", ".tedavigiris", function (e) {
            var protokolno = $(this).attr('protokolno');
            $.get("ajax/yatis/yatismodalbody.php?islem=tedavigirisbody", {protokolno: protokolno}, function (getVeri) {
                $('#modal-tanim-icerik').html(getVeri);
            });
        });

        $( "#order-tab" ).one( "click", function() {
            setTimeout(function () {
                $('#tableorder').DataTable({
                    "responsive": true
                });
            }, 500);
        });

    $("body").off("click", ".labislem<?php echo $protokol_no;?>").on("click", ".labislem<?php echo $protokol_no;?>", function (e) {
        var protokolno = $(this).attr('protokolno');
        $.get("ajax/hastadetay/hastalaboratuvarlistele.php", {protokolno: protokolno}, function (getVeri) {
            $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
        });
    });

    $("body").off("click", ".rontgenislem<?php echo $protokol_no;?>").on("click", ".rontgenislem<?php echo $protokol_no;?>", function (e) {
        var protokolno = $(this).attr('protokolno');
        $.get("ajax/hastadetay/hastaradyolojilistele.php", {protokolno: protokolno}, function (getVeri) {
            $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
        });
    });

    $(document).ready(function () {
        $("body").off("click", ".orderislem").on("click", ".orderislem", function (e) {
            $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {protokolno:<?php echo $protokol_no ?>}, function (getVeri) {
                $('.orderlistesibody<?php echo $protokol_no;?>').html(getVeri);
            });
        });

        $("body").off("click", ".tedavigiris").on("click", ".tedavigiris", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            $.get("ajax/yatis/yatismodalbody.php?islem=tedavigirisbody", {protokolno: protokolno}, function (getVeri) {
                $('#modal-tanim-icerik').html(getVeri);
            });
        });

        $( "#order-tab" ).one( "click", function() {
            setTimeout(function () {
                $('#tableorder').DataTable({
                    "responsive": true
                });
            }, 500);
        });


        $("body").off("click", ".rontgenislem").on("click", ".rontgenislem", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            $.get("ajax/hastadetay/hastaradyolojilistele.php", {protokolno: protokolno}, function (getVeri) {
                $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getVeri);
            });
        });

        var count<?php echo $protokol_no ?> = 0;
        $("body").off("click", ".showhastabilgi<?php echo $protokol_no ?>").on("click", ".showhastabilgi<?php echo $protokol_no ?>", function (e) {
            if (count<?php echo $protokol_no ?> == 0) {
                $(".hastabilgishow").show();
                count<?php echo $protokol_no ?> = 1;
                $(this).css("background-color", "#E7F1FF");
            } else {
                $(".hastabilgishow").hide();
                count<?php echo $protokol_no ?> = 0;
                $(this).css("background-color", "white");
            }
        });

        $("body").off("click", ".paketekle").on("click", ".paketekle", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            var tcno = $(this).attr('tcno');
            var tip = $(this).attr('tip');
            $.get("ajax/tanilistesigetir.php?islem2=poliklinik", {protokolno: protokolno, tcno: tcno, tip: tip}, function (getveri) {
                $('#tanigeldi').html(getveri);
            });
        });

        $("body").off("click", ".istemekleistemler").on("click", ".istemekleistemler", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            var id = $(this).attr('id');
            var tip = $(this).attr('tip');
            var ihsan='istemler';
            var grup_id = $(this).attr('grup_id');
            $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getveri) {
                if(getveri) {
                    alertify.success("kullanıcılar i̇stem paketi eklendi");
                    $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=istemhizmetlistesi", {protokolno: protokolno,ihsan:ihsan}, function (getveri) {
                        $('.istemlistebody').html(getveri);
                    });
                }else{
                    alertify.error("eklerken hata oluştu! : " + getveri);
                }
            });
        });

        $("body").off("click", ".gruppaketekle").on("click", ".gruppaketekle", function (e) {
            var protokolno = <?php echo $protokol_no; ?>;
            var id = $(this).attr('id');
            var tip = $(this).attr('tip');
            $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip}, function (getveri) {
                $("#hizlipaketlerigetiristemler .close").click();
                $('.modal-backdrop').remove();
                $.get( "ajax/hastadetay/hastaistemlerilistele.php", { protokolno:protokolno },function(getveri){
                    $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(getveri);
                });
            });
        });

        $("body").off("click", ".sagaekle").on("click", ".sagaekle", function (e) {
            var kullanici_id = $(this).attr('kullanici_id');
            var islem_id = $(this).attr('islem_id');
            var islem = $(this).attr('islem');
            var tip=$(this).attr('tipi');
            $.get("ajax/kullanici_hazirliste_ekle.php", {kullanici_id: kullanici_id,islem_id:islem_id,islem:islem,tip:tip}, function (getveri) {
                $('#kullaniciisteklistesiistemler').html(getveri);
            });
        });

        $("body").off("click", "#paketiolustur").on("click", "#paketiolustur", function (e) {
            // buton idli elemana tıklandığında
            var gonderilenform = $("#gonderilenform").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
            $.ajax({
                url:'ajax/hastadetay/hastaistemlerilistele.php?protokolno=16', // serileştirilen değerleri ajax.php dosyasına
                type:'post', // post metodu ile
                data:gonderilenform, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    $('#hastaistemlericerik<?php echo $protokol_no; ?>').html(e);
                    $("#hastaistemlericerik<?php echo $protokol_no; ?> .close").click();
                    $('.modal-backdrop').remove();
                }
            });
        });

        $('#istemhizmet').DataTable({
            "scrollX": true,
            "scrollY": '45vh',
            "lengthChange": false,
            "pageLength":20,
            "dom": "<'row'<'col-sm-12 col-md-6 'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [
                {
                    text: '<i class="fas fa-code-pull-request"></i> Hızlı Paket Listele',
                    className:'btn kapat-btn btn-sm text-white',
                    titleAttr:'Hastaya İstem Paketi Eklemek İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {
                        var ihsan="30041";
                        $.get("ajax/hastahizlipaketlistele.php?islem=istemler", {protokolno:16,ihsan:ihsan}, function (getveri) {
                            $('#hastahizlipaketlisteleislemistemler').html(getveri);
                        });
                    },
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketlerigetiristemler",
                    }
                },
                {
                    text: '<i class="fas fa-code-pull-request"></i> Hızlı Paket Oluştur',
                    className:'btn yeni-btn btn-sm text-white mx-1',
                    titleAttr:'Hızlı Paket Oluşturmak İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {  },
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketolusturistemler",
                    }
                },
                {
                    text: '<i class="fas fa-code-pull-request"></i> İstem Ekle',
                    className:'btn up-btn btn-sm text-white mx-1',
                    titleAttr:'Hastaya İstem Eklemek İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {
                    },
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#yeniistemekleistemler",
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

    });


    $('.w').dialog({
        onClose: function () {
            $(this).find('FORM').form('clear');
            $('.w').html("");
        },
        cache: true,
        modal: true,
        fit: true,
        closed: true,
        iconCls: 'icon-save',
        title: 'HBYS',

        toolbar: [{
            id: 'save-tool',
            text: 'Kaydet',
            iconCls: 'icon-save',
            handler: function () {
                //alert('ok');
            }
        },
            {
                id: 'update-tool',
                text: 'Güncelle',
                iconCls: 'icon-edit',
                handler: function () {
                    //alert('ok');
                }
            },
            {
                id: 'delete-tool',
                text: 'Seçilen Formu Sil',
                iconCls: 'icon-cancel',
                handler: function () {
                    //alert('ok');
                }
            },
            {
                id: 'print-tool',
                text: 'Yazdır',
                iconCls: 'icon-print',
                handler: function () {
                    //alert('ok');
                }
            },
            {
                id: 'pdf-tool',
                text: 'PDF',
                iconCls: 'icon-add',
                handler: function () {
                    //alert('ok');
                }
            }
        ]
    });

    function get_forms() {
        $('#w').dialog({
            title: 'Form İşlemleri - <?php echo $hastabilgisigetir["patient_name"]; ?> <?php echo $hastabilgisigetir["patient_surname"]; ?>',
            iconCls: ' fa-solid fa-square-poll-horizontal',
        });

        $('#w').window('open');
        $('#w').window('refresh', 'model/rapor-form.php?protocol_no=<?php echo $protokol_no; ?>&patient_name=<?php echo $hastabilgisigetir['patient_name'] . " " . $hastabilgisigetir['patient_surname'];  ?>&patient_tc=<?php echo $hastabilgisigetir['tc_id']; ?>&department=<?php echo $poliklinikbilgisigetir['outpatient_id']; ?>&doctor=<?php echo $poliklinikbilgisigetir['doctor']; ?>&menu_id=<?php echo $uniqid; ?>&patient_id=<?php echo $poliklinikbilgisigetir["patient_id"]; ?>');
    }

    $("body").off("click", "#v-<?php echo $uniqid_2; ?>").on("click", "#v-<?php echo $uniqid_2; ?>", function (e) {
        var url = $(this).attr("url");
        $('#w').window('open');
        $('#w').window('refresh', 'ajax/formlar-raporlar/formlar/cinsel-saldiri-muayene-form.php?protocol_no=<?php echo $protokol_no; ?>&patient_name=<?php echo $hastabilgisigetir['patient_name'] . " " . $hastabilgisigetir['patient_surname'];  ?>&patient_tc=<?php echo $hastabilgisigetir['tc_id']; ?>&department=<?php echo $poliklinikbilgisigetir['outpatient_id']; ?>&doctor=<?php echo $poliklinikbilgisigetir['doctor']; ?>&menu_id=<?php echo $uniqid; ?>&patient_id=<?php echo $poliklinikbilgisigetir["patient_id"]; ?>&name=carnal_assault_examination_form&url=' + url + '');
    });


</script>









