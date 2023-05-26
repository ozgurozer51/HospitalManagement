<?php include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$simdisaat = $simdi['1'];
$islem = $_GET["islem"];

$protokol = $_GET['getir'];
$hasta_kayit = singular("patient_registration", "protocol_number", $_GET["getir"]);
$hasta_protokol = $hasta_kayit["protocol_number"];
$hasta_kayit_tc = $hasta_kayit["tc_id"];
$hastalar = singular("patients", "tc_id", $hasta_kayit_tc);

$dogum = $hastalar['birth_date'];
$YAS = ikitariharasindakiyilfark($simdikitarih, $dogum);
?>
<?php $sql = tek("select * from patient_record_diagnoses where protocol_number=$_GET[getir] and status = 1");
if (!isset($sql['id'])) { ?>

    <div class="alert alert-warning">Reçete İşleminden Önce Hastaya Tanı Giriniz!</div>

    <?php exit();
} ?>

<?php $rowkuruk = $hastalar["social_assurance"];
$SOSYALGUVENCE = tek("SELECT * FROM transaction_definitions WHERE definition_code='{$hastalar["social_assurance"]}'");
$KURUMGETiR = tek("SELECT * FROM transaction_definitions WHERE definition_code='{$hastalar["institution"]}'"); ?>

<script src="assets/printer.js"></script>

<input type="hidden" id="protocol-701" value="<?php echo $_GET['getir']; ?>">

<div class="easyui-layout" fit="true" style="width: 100%; height: 100%;">
    <div title="İlaç Listesi" data-options="region:'west' , hideCollapsedContent:false , split:true" style="width: 30%; height: 100%;">
        <form id="form_birim" action="javascript:void(0);">
        </form>

        <div class="easyui-tabs" id="recete-ilac-listesi"  style="width: 100%; height: 50%;">

            <div title="İlaç Listesi">

                <input id="recete-ilac-ara" class="easyui-textbox" data-options="prompt:'İlaç Adı İle Arayınız' , icons: [{
                        iconCls:'icon-cancel',
                        class:'pop',
                        id:'pop',
                        handler: function(e){
                            $(e.data.target).textbox('clear');
                            $('#recete-ilac-listesi-2').datagrid('reload');
                    }}]" style="width: 100%;">

                <div class="easyui-datagrid" fit="true" id="recete-ilac-listesi-2" style="width: 100%; height: 50%;"></div>

                <script>
                    $(document).ready(function(){
                     $('#recete-ilac-listesi-2').datagrid({
                         singleSelect:true,
                         url:'ajax/recete/recetetablo.php?islem=ilac-listesi-json',
                         height:'50%',
                         width:'100%',

                         onDblClickRow: function(rowIndex, rowData) {
                             ilac_bilgisi_gir();
                         },

                         columns: [[
                             {field: 'id', title: 'Fav.' , formatter: function(value, row, index) {

                                        if (row.fav_id>0){
                                            return "<i class='mdi mdi-star-plus' id='favoriyi-kaldir' data-id='"+row.fav_id+"' style='color: deeppink;'></i>";
                                        }else {
                                            return "<i class='mdi mdi-star-plus' id='favoriye-ekle' data-id='"+row.ilac_id+"'></i>";
                                        }
                                 }},

                             {field: 'drug_name', title: 'İlaç Adı'},
                         ]],
                     });

                        $("body").off("click", "#favoriyi-kaldir").on("click", "#favoriyi-kaldir", function (e) {
                           var fav_id =  $(this).attr('data-id');
                            $.ajax({
                                url: 'ajax/recete/recetesql.php?islem=favoriyi-kaldir',
                                type: 'POST',
                                data: {fav_id: fav_id},
                                success: function (response) {
                                    $('.sonucyaz').html(response);
                                    $('#recete-ilac-listesi-2').datagrid('reload');
                                    // $('#favori-listesi').datagrid('reload');
                                },
                            });
                        });

                        $("body").off("click", "#favoriye-ekle").on("click", "#favoriye-ekle", function (e) {
                            var drug_id =  $(this).attr('data-id');
                            $.ajax({
                                url: 'ajax/recete/recetesql.php?islem=favoriyi-ekle',
                                type: 'POST',
                                data: {drug_id:drug_id},
                                success: function (response) {
                                    $('.sonucyaz').html(response);
                                    // $('#favori-listesi').datagrid('reload');
                                    $('#recete-ilac-listesi-2').datagrid('reload');
                                },
                            });
                        });

                        $('#recete-ilac-ara').textbox('textbox').keyup(function(e){
                            var value = $(this).val();
                            $('#recete-ilac-listesi-2').datagrid('load', {
                                drug_name:value,
                            });
                        });

                    });
                </script>

            </div>

            <div title="Sık Kullanılan İlaçlar">

                <div class="easyui-datagrid" id="favori-listesi"></div>

                <script>
                    $('#favori-listesi').datagrid({
                       url:'ajax/recete/recetetablo.php?islem=favori-listesi',
                        singleSelect:true,
                        onDblClickRow: function(rowIndex, rowData) {
                            ilac_bilgisi_gir();
                            $('#drug-id-register').val(rowData.ilac_id);
                        },

                        onLoadSuccess: function(data) {
                            if(data=='505'){

                                   alert("dsds");
                            }

                        },

                        columns: [[
                            {field: 'id', title: 'Fav.' , formatter: function(value, row, index) {
                                        return "<i class='mdi mdi-star-plus' id='favori-listesinden-favoriyi-kaldir' data-id='"+row.fav_id+"' style='color: deeppink;'></i>";
                                }},
                            {field: 'drug_name', title: 'İlaç Adı'},
                        ]],
                    });

                    $("body").off("click", "#favori-listesinden-favoriyi-kaldir").on("click", "#favori-listesinden-favoriyi-kaldir", function (e) {
                        var fav_id =  $(this).attr('data-id');
                        $.ajax({
                            url: 'ajax/recete/recetesql.php?islem=favoriyi-kaldir',
                            type: 'POST',
                            data: {fav_id:fav_id},
                            success: function (response) {
                                $('.sonucyaz').html(response);
                                $('#favori-listesi').datagrid('reload');
                                // $('#recete-ilac-listesi-2').datagrid('reload');
                            },
                        });

                    });


                </script>



            </div>


            <div title="Paket Listesi">

                <button class="easyui-linkbutton sablon-sil" id="template-delete" disabled ><i class="fa-solid fa-trash"></i> Seçilen Şablonu Kaldır</button>
                <button class="easyui-linkbutton" id="add-template"  disabled><i class="fa-solid fa-octagon-plus"></i> Seçilen Şablonu Ekle</button>
                <div class="paket_body"></div>
            </div>

        </div>

        <script>
            $(document).ready(function(){
                $('#recete-ilac-listesi').tabs({
                    cache:false,
                    onSelect: function(title, index){
                        if (index == 2) {
                            $.get("ajax/recete/recetetablo.php?islem=sablonlar", {}, function (e) {
                                $('.paket_body').html(e);
                            });
                        }
                        if (index == 1) {
                            $('#favori-listesi').datagrid('reload');
                        }
                        if (index == 0) {
                            $('#recete-ilac-listesi-2').datagrid('reload');
                        }
                    }
                });
            });
        </script>

        <div class="tani_listesi_get">
            <div class="easyui-panel" title="Eklenen Tanılar" fit="true" style="height: 50%; width: 100%;">
                <table id="recete-tani-listesi" class="table table-sm table-bordered display nowrap" style="font-size: 13px !important;">
                    <thead>
                    <tr>
                        <th id="clk-dom-701" style="width: 100px;">Tani Tipi</th>
                        <th>İşlem</th>
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
    </div>

    <div data-options="region:'center',split:true" style="width: 70%; height: 100%;">

        <div class="easyui-panel" style="width: 100%;">
            <button class="easyui-linkbutton gecmis_receteler_dom border-0 "><i class="fa-solid fa-rectangle-history-circle-plus"></i> Geçmiş Reçeteler</button>
            <button class="easyui-linkbutton border-0"><i class="fa-solid fa-send-backward"></i> Medula Gönder</button>
            <button class="easyui-linkbutton"><i class="fa-solid fa-file-magnifying-glass"></i> Medula Sorgula</button>
            <button class="easyui-linkbutton"><i class="fa-solid fa-file-xmark"></i> Medula Sil</button>
            <button class="easyui-linkbutton medula_giris"><i class="fa-solid fa-right-to-bracket"></i> Medula Giriş</button>
            <button class="easyui-linkbutton"><i class="fa-solid fa-right-left"></i> Ehu Sevk Onayla</button>
            <button class="easyui-linkbutton" onclick="window.open('https://recetem.enabiz.gov.tr/');"><i class="fa-solid fa-receipt"></i> Reçetem</button>
            <button class="easyui-linkbutton"><i class="fa-solid fa-signature-slash"></i> İmzasız İşlemler</button>
        </div>

        <div class="easyui-panel" title="Hasta Bilgileri" border="false" style="display: flex; width: 100%;">

            <div class="easyui-panel" border="false" style="width:33%;">
                <input class="easyui-textbox hasta-bilgi-input" label="Hasta Adı:"   labelWidth="100" readonly value="<?php echo $hastalar['patient_name'] . ' ' . $hastalar['patient_surname'] . '(' . $YAS . ')'; ?>" style="width: 100%;">
                <input class="easyui-textbox hasta-bilgi-input" label="Kan Grubu:"   labelWidth="100" readonly value="<?php $kan = $hastalar["blood_group"];echo islemtanimgetirid($kan); ?>" style="width: 100%;">
                <input class="easyui-textbox hasta-bilgi-input" label="Protokol No:" labelWidth="100" readonly value=" <?php echo $hasta_kayit["protocol_number"]; ?>" style="width: 100%;">
            </div>

            <div class="easyui-panel" border="false" style="width: 33%;">
                <input class="easyui-textbox hasta-bilgi-input" label="Kur Kodu:" labelWidth="100" readonly value="<?php echo $KURUMGETiR["definition_name"] . " / " . $SOSYALGUVENCE["definition_name"] ?>" style="width: 100%;">
                <input class="easyui-textbox hasta-bilgi-input" label="TC Kimlik No:" labelWidth="100" readonly value="<?php echo $hastalar["tc_id"]; ?>" style="width: 100%;">
                <input class="easyui-textbox hasta-bilgi-input" label="Anne Adı:" labelWidth="100" readonly value="<?php echo $hastalar["mother"] ?>" style="width: 100%;">
            </div>

            <div class="easyui-panel" border="false" style="width: 34%;">
                <input class="easyui-textbox hasta-bilgi-input" label="Takip No:" labelWidth="100" readonly value="<?php echo $hasta_kayit["provision_number"] ?>" style="width: 100%;">
                <input class="easyui-textbox hasta-bilgi-input" label="Doğum Tarihi" labelWidth="100" readonly value="<?php echo $hastalar["birth_date"] ?>" style="width: 100%;">
                <input class="easyui-textbox" label="Hasta Notu:" labelWidth="100" style="width: 100%;"><?php echo $hastalar["reminders"] ?>
            </div>

        </div>

        <div class="easyui-panel" style="width: 100%;">
            <div class="easyui-tabs" style="width: 100%;">

                <div title="Reçete Bilgileri">
                    <div class="easyui-panel" style="display: flex; width: 100%;">

                        <div class="easyui-panel" style="width:33%;">
                            <input class="easyui-datetimebox recipe_time" label="Reçete Tarihi:" labelWidth="100" value="<?php echo date('Y-m-d\TH:i'); ?>" style="width: 100%;">
                            <input class="easyui-textbox serial_number" label="Reçete Kodu:" labelWidth="100" readonly value="<?php echo randomNumber(10); ?>" style="width: 100%;">
                            <input class="easyui-textbox" label="Medula Kodu:" labelWidth="100" readonly value="" style="width: 100%;">
                        </div>

                        <div class="easyui-panel" style="width:33%;">
                            <select class="easyui-combobox recipe_tour" label="Reçete Türü:" labelWidth="100" style="width: 100%;">
                                <option value="">Reçete türü seçiniz..</option>
                                <?php $sql = sql_select("select * from transaction_definitions WHERE status='1' AND definition_type='RECETE_TURU'");
                                foreach ((array)$sql as $value) { ?>
                                    <option value="<?php echo $value["id"]; ?>"<?php if ($value["definition_name"] == 'NORMAL') {echo "selected";} ?>><?php echo $value['definition_name']; ?></option><?php } ?>
                            </select>
                            <select class="easyui-combobox" label="Ehu Onay Sevk:" labelWidth="100" style="width: 100%;">
                                <option value="1">Evet</option>
                                <option selected value="2">Hayır</option>
                            </select>
                            <input class="easyui-textbox" label="Red Nedeni:" labelWidth="100" style="width: 100%;">
                        </div>

                        <div class="easyui-panel" style="width:34%;">
                            <select name="" class="easyui-combobox recipe_sub_tour" label="Alt Türü:" labelWidth="100" style="width: 100%;">
                                <option selected disabled>Seçim yapınız..</option>
                                <?php $sql = sql_select("select * from transaction_definitions where definition_type='RECETE_ALT_TURU' and transaction_definitions.status='1'");
                                foreach ($sql as $item) { ?>
                                    <option value="<?php echo $item["id"]; ?>"<?php if ($hasta_kayit['hospitalization_info'] == 'Y') {
                                        if ($item["definition_name"] == 'Taburcu Reçetesi') {
                                            echo "selected";
                                        }
                                    } else {
                                        if ($item["definition_name"] == 'Ayaktan Reçetesi') {
                                            echo "selected";
                                        }
                                    } ?>> <?php echo $item["definition_name"]; ?></option>
                                <?php } ?>
                            </select>
                            <input class="easyui-textbox" label="Doktor:" labelWidth="100" readonly value="<?php echo kullanicigetir($hasta_kayit["doctor"]); ?>" style="width: 100%;">
                            <input class="easyui-textbox" label="Açıklama:" labelWidth="100" class="recipe_detail" tyle="width: 100%;" multiline="true" style="width: 100%;">
                        </div>
                    </div>


                </div>


                <div title="Reçete Güncelleme">Reçete Güncelleme</div>
                <div title="Reçemiş İlaç Raporları">Geçmiş İlaç Raporları</div>

            </div>

            <div class="easyui-panel" id="recete_eklenen_ilac_tablo" style="width: 100%;">
                <!--REÇETEYE EKLENEN İLAÇLARIN TABLOSUNUN GELDİĞİ DİV-->
            </div>
        </div>
    </div>


    <div class="gecmis_receteler_alert">
                <!-- GEÇMİŞ REÇETENİN TABLOSUNUN GELDİĞİ DİV-->
                <div id="gecmis_recete_tablosu"></div>
                <!--GEÇMİŞ REÇETELERİN İÇİNDEKİ İLAÇLARIN TABLOSUNUN GELDİĞİ DİV-->
                <div id="gecmis_recete_eklenen_ilac_tablo"></div>
    </div>

    <div class="row">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-6">

                                </div>
                                <!--İLAÇ EKLEME FORMU BAŞLANGIÇ-->
                                <div class="col-6">

                                </div>
                            </div>
                        </div>
                        <!--İLAÇ EKLEME FORMU BİTİŞ-->
                        <div class="col-xl-1"></div>
                        <div class="col-xl-12"></div>
    </div>
</div>

<script>
        $('.w-simple').dialog('setTitle', 'Reçete İşlemleri-- <?php echo $hastalar['patient_name'] . ' ' . $hastalar['patient_surname'] . '(' . $YAS . ')'; ?>');

    $(".recetebilgi").click(function () {
        $('.recetebilgi').removeClass("text-white up-btn");
        $(this).addClass("text-white up-btn");
    });

    $("body").off("click", ".medula_giris").on("click", ".medula_giris", function (e) {
        alertify.confirm("" +
            "<label class='form-label'>Kullanıcı Adı</label>" +
            "<input class='form-control form-control-sm col-md-6' type='text' id='medula-username' >" +
            "<label class='form-label'>Şifre</label>" +
            "<input class='form-control form-control-sm' type='text' id='medula-password' >" +
            "<label class='form-label mt-2 mx-2'>Beni Hatırla</label>" +
            "<input  type='checkbox' class='mt-2' id='medula-hatirla' >" +
            "", function () {

        }, function () {
            alertify.warning("Giriş işlemiden vazgeçtiniz..")
        }).set({
            labels: {
                ok: "Giriş",
                cancel: "Vazgeç"
            }
        }).set('resizable', true).set({title: "Medula Giriş Ekranı"}).resizeTo('40%', '50%');
    });

    $(document).ready(function () {

// İLAÇLAR TABLOSU İLAÇ ARAMA
        $("#ilac-ara").keyup(function () {
            var drug_name = $(this).val();
            $.get("ajax/recete/recetesql.php?islem=sql_ilac_ara", {drug_name: drug_name}, function (e) {
                $('#livesearcasdah').html(e);
            });
        });

// İLAÇLAR TABLOSU
        var protokolno = "<?php echo $protokol; ?>";
        var hastalarid = "<?php echo $hasta_kayit['patient_id']; ?>";
        $.get("ajax/recete/recetesql.php?islem=sql_ilac_ara", {
            protokolno: protokolno,
            hastalarid: hastalarid
        }, function (getVeri) {
            $('#ilac_tablo').html(getVeri);
        });


        $.get("ajax/recete/recetetablo.php?islem=gecmis_recete_tablo", {getir:<?php echo $protokol; ?> }, function (getVeri) {
            $('#gecmis_recete_tablosu').html(getVeri);
        });


        $.get("ajax/recete/recetetablo.php?islem=recete_eklenen_ilaclar", {getir:<?php echo $protokol; ?> }, function (getVeri) {
            $('#recete_eklenen_ilac_tablo').html(getVeri);
        });


        $("body").off("click", "#add-template").on("click", "#add-template", function (e) {
            var eili = $('.eklenen-ilac-listesi-recete-id').val();
            $(this).prop("disabled", true);
            var recete_id = $('.sablon_sec:checked').attr('recete-id');
            if (recete_id == eili) {
                alertify.alert("Aynı Hastadan Eklenen Şablon Eklenemez", function () {
                    alertify.message('OK');
                });

            } else {

                var protocol = $('#protocol-701').val();
                $.get("ajax/recete/recetesql.php?islem=seçilen-sablonu-hastaya-ekle", {
                    recete_id: recete_id,
                    protocol: protocol
                }, function (e) {
                    $('.sonucyaz').html(e);
                    $.get("ajax/recete/recetetablo.php?islem=recete_eklenen_ilaclar", {getir: protocol}, function (getVeri) {
                        $('#recete_eklenen_ilac_tablo').html(getVeri);
                    });

                });
            }
        });

        $("body").off("click", "#receipt-templates-clk").on("click", "#receipt-templates-clk", function (e) {
            $.get("ajax/recete/recetetablo.php?islem=sablonlar", {}, function (e1) {
                alertify.confirm(e1, function () {
                    var recete_id = $('.sablon_sec:checked').attr('recete-id');
                    var protocol = $('#protocol-701').val();
                    $.get("ajax/recete/recetesql.php?islem=seçilen-sablonu-hastaya-ekle", {
                        recete_id: recete_id,
                        protocol: protocol
                    }, function (e) {
                        $('.sonucyaz').html(e);
                        $.get("ajax/recete/recetetablo.php?islem=recete_eklenen_ilaclar", {getir: protocol}, function (getVeri) {
                            $('#recete_eklenen_ilac_tablo').html(getVeri);
                        });

                    });

                }, function () {
                    alertify.warning('Şablon Seçimi Yapmadınız')
                }).set({
                    labels: {
                        ok: "Onayla",
                        cancel: "Vazgeç"
                    }
                }).set('resizable', true).set({title: "Şablon Seç"}).resizeTo('95%', '85%');
            });
        });

        $("body").off("click", ".template_select").on("click", ".template_select", function (e) {

            $(".sablon-sil").linkbutton("enable");
            $("#add-template").linkbutton("enable");

            $(".template_select").removeClass("bg-yesil text-white");
            $(".sablon_sec").prop("checked", false);

            $(this).addClass("bg-yesil text-white");
            $(this).find(".sablon_sec").prop("checked", true);
            $('.sablon-sil').linkbutton('enable');
        });

        $("body").off("click", ".sablon-sil").on("click", ".sablon-sil", function (e) {
            $(this).prop("disabled", true);
            var id = $("input[name=radioname]:checked").attr('data-id');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeni Belirtiniz</div> <textarea class='form-control form-control-sm' id='delete-detail' rows='2'>", function () {
                var delete_detail = $("#delete-detail").val();
                $.ajax({
                    url: 'ajax/recete/recetesql.php?islem=recete-sablonu-sil',
                    type: 'POST',
                    data: {delete_detail, id},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/recete/recetetablo.php?islem=sablonlar", {}, function (e) {
                            $('.paket_body').html(e);
                        });
                    }
                });

            }, function () {
                alertify.warning('Silme İsleminden Vazgeçtiniz')
            }).set({
                labels: {
                    ok: "Onayla",
                    cancel: "Vazgeç"
                }
            }).set('resizable', true).set({title: "Şablon Sil"}).resizeTo('50%', '40%');
        });

        $("body").off("change", "#drug_use_period").on("change", "#drug_use_period", function (e) {
            if ($("#drug_use_period").val() != "007") {
                $("#drug_use_period_gun, #drug_use_period_adet").attr("disabled", true);
            } else {
                $("#drug_use_period_gun, #drug_use_period_adet").attr("disabled", false);
            }
        });

    });

    $(".gecmis_receteler_alert").hide();
    $('.gecmis_receteler_dom').click(function (event) {
        $(".gecmis_receteler_alert").show();
        alertify.genericDialog || alertify.dialog('genericDialog', function () {
            return {
                main: function (content) {
                    this.setContent(content);
                },
                setup: function () {
                    return {
                        focus: {
                            element: function () {
                                return this.elements.body.querySelector(this.get('selector'));
                            },
                            select: false
                        },
                        options: {
                            title: 'GEÇMİŞ REÇETELER',
                            basic: true,
                            maximizable: true,
                            resizable: true,
                            padding: false,
                            movable: true,
                            closableByDimmer: false,
                            startMaximized: true,
                        }
                    };
                },
                settings: {
                    selector: undefined,
                    invokeOnCloseOff: false,
                }
            };
        });
        alertify.genericDialog($('.gecmis_receteler_alert')[0]).set('selector');
    });

    $("body").off("click", ".tanigetir").on("click", ".tanigetir", function (e) {
        var tip = $(this).attr("tip");
        var protokol_no = $(this).attr("protokol-no");
        var modul = 'ayaktan';

        $(".tanigetir").removeClass("tanibtnsec");
        $(this).addClass("tanibtnsec");
        if (!protokol_no) {
            alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
        } else {

            var protokol_no = $(this).attr("protokol-no");

            $('.w-simple-2').dialog({
                modal:true,
                cache:false,
                fit:true,
                title:'Tanı Ekleme İşlemi',
                closed:false
            });

            $('.w-simple-2').dialog('refresh' , 'ajax/tani/tanilistesi.php?islem=tanilistesigetir&protokolno='+protokol_no+'&tip='+tip+'&modul='+modul+'');

        }
    });

    var protokolno = "<?php echo $protokol ?>";
    var tip = 2;
    var modul = 1;

    var table_recete = $('#recete-tani-listesi').DataTable({
        deferRender: true,
        scrollY: '30vh',
        "info": false,
        "paging": false,
        "searching": false,
        "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",

        ajax: {
            url: '/ajax/tani/tanilistesi.php?islem=recete-tani-listesi',
            data: {"protokolno": protokolno, "tip": tip, "modul": modul},
            processing: true,
            method: 'GET',
            dataSrc: ''
        },
        "buttons": [{

            text: 'Tanı Ekle',
            className: 'btn btn-secondary  btn-p btn-sm tanigetir',
            titleAttr: 'Tanı Eklemek İçin Tıklayınız',
            attr: {
                id: "tanigetir",
                "tip": 2,
                "protokol-no":<?php echo $hasta_protokol ?>
            }
        }],


        "createdRow": function (row, data, dataIndex) {
            $(row).addClass('tr-id');
            $(row).addClass('tr' + data['special_id']);
        },

        "fnRowCallback": function (nRow, aData) {
            var $nRow = $(nRow);
            $title = `${aData['special_id']}`;
            $nRow.attr("special-id", $title);

            setTimeout(function () {
                $('#recete' + aData['special_id']).find("option[value=" + aData['ana_tani'] + "]").prop("selected", true);
                var tip = 2;
                if (aData['tipi'] != tip) {
                    $('#recete' + aData["special_id"]).prop("disabled", true);
                    $("button[data-id='" + aData["special_id"] + "']").prop("disabled", true);
                }
            }, 500);

            return nRow;
        },

        "initComplete": function (settings, json) {

        },

        columns: [
            {
                data: null,
                <?php $sql = sql_select("select * from transaction_definitions WHERE status='1' AND definition_type='TANI_TURU'"); ?>
                render: function (data) {
                    return '<select insert_id="' + data.tani_ekleyen + '" class="form-select form-select-sm tani-adi-change tani-adi" id="recete' + data.special_id + '" style="width:100px;">' +
                        <?php foreach ($sql as $item){   ?>
                        '<option tani-kodu="<?php echo $item['definition_supplement']; ?>" value="<?php echo $item['definition_supplement']; ?>" ><?php echo $item['definition_name']; ?></option>' +
                        <?php } ?>
                        '</select>';
                }
            },
            {
                data: null,
                render: function (data) {
                    return '<button id="tani-sil-recete" data-id="' + data.special_id + '" class="btn btn-danger tani-iptal btn-sm"><i class="fas fa-trash"></i></button>';
                }
            },
            {
                data: null,
                render: function (data) {
                    var ktarihi = data.ktarihi;
                    var islem_tarihi = moment(ktarihi).format('DD/MM/YY H:mm:ss');
                    if (islem_tarihi == "Invalid date") {
                        islem_tarihi = "";
                    }
                    return islem_tarihi;
                }
            },
            {data: 'tanikodu'},
            {data: 'diagnoses_name'},
            {data: 'department_name'},
            {data: 'definition_name'},
        ],
    });

    $("body").off("click", "#tani-sil-recete").on("click", "#tani-sil-recete", function (e) {
        var special_id = $(this).closest('tr').attr('special-id');
        $.ajax({
            url: 'ajax/tani/taniislem.php?islem=tani-sil',
            type: 'GET',
            data: {special_id: special_id},
            success: function (response) {
                alertify.success("İşlem Başarılı");
                table_recete.ajax.url(table_recete.ajax.url()).load();
            },
        });
    });

    $("body").off("click", ".tani-kapat").on("click", ".tani-kapat", function (e) {
        table_recete.ajax.url(table_recete.ajax.url()).load();
    });

    $("body").off("change", ".tani-adi-change").on("change", ".tani-adi-change", function (e) {
        var tani_id = $(this).closest('tr').attr('special-id');
        var diagnosis_type = $(this).val();
        $.ajax({
            url: 'ajax/tani/taniislem.php?islem=tani-adi-guncelle',
            type: 'POST',
            data: {tani_id: tani_id, diagnosis_type: diagnosis_type},
            success: function (response) {
                alertify.success("İşlem Başarılı");
            },
            error: function (response) {
                alertify.error("İşlem Başarısız");
            },
        });
    });

    $("body").off("click", ".ilacfaveklese-2").on("click", ".ilacfaveklese-2", function (e) {
        var ilac_id = $(this).attr('ilac_id');
        var islem = $(this).attr('islem');
        var islem_id = $(this).attr('islem_id');
        var protokolno = $(this).attr('protokolno');
        var hastalarid = $(this).attr('hastalarid');
        $.get("ajax/recete/recete_fav.php", {
            "ilac_id": ilac_id,
            "islem": islem,
            "islem_id": islem_id
        }, function (getveri) {
            if (islem == "ekle") {
                console.log(getveri);
                alertify.message("ilaç favorilere eklendi");
            } else {
                alertify.error("ilaç favorilerden çıkartıldı");
            }

            var islemturu = 'favori_listem';
            $.get("ajax/recete/recete_fav.php", {
                islem: islemturu,
                protokolno: protokolno,
                hastalarid: hastalarid
            }, function (e) {
                $('.ilac_fav_body').html(e);
            });
            $.get("ajax/recete/recetesql.php?islem=sql_ilac_ara", {
                protokolno: protokolno,
                hastalarid: hastalarid
            }, function (getVeri) {
                $('#ilac_tablo').html(getVeri);
            });

        });
    });

    $("body").off("click", ".ilac_sec").on("click", ".ilac_sec", function (e) {
        $('.ilac_sec').removeClass("text-white bg-yesil ana-ilac-listesi");
        $(this).addClass("text-white bg-yesil ana-ilac-listesi");
    });

    $("body").off("click", ".ana-ilaclar-listesi").on("click", ".ana-ilaclar-listesi", function (e) {
        $.get("ajax/recete/recetesql.php?islem=sql_ilac_ara", {}, function (getVeri) {
            $('#ilac_tablo').html(getVeri);
        });
    });

    $("body").off("click", ".ilacfaveklese").on("click", ".ilacfaveklese", function (e) {

        var ilac_id = $(this).attr('ilac_id');
        var islem = $(this).attr('islem');
        var islem_id = $(this).attr('islem_id');
        $.get("ajax/recete/recete_fav.php", {
            ilac_id: ilac_id,
            islem: islem,
            islem_id: islem_id
        }, function (getveri) {
            if (islem == "ekle") {
                alertify.message("ilaç favorilere eklendi");
            } else {
                alertify.error("ilaç favorilerden çıkartıldı");
            }
        });
        $(this).closest('tr').remove();
    });

    function ilac_bilgisi_gir() {

        $('#talimat-icerik-form').form('clear');

        $('#talimat-icerik-window').dialog({
            buttons: [{
                text: 'Kaydet',
                iconCls: 'icon-save',
                handler: function() {
                    $('#talimat-icerik-window').form('submit', {

                        onSubmit: function() {
                            var drug_use_form = $('#drug_use_form').val();
                            var drug_use_type = $('#drug_use_type').val();
                            var box_pieces = $('#box_pieces').val();
                            var drug_id = $('.ana-ilac-listesi').attr("ilac_id");
                            var patient_referenceid = $('#patient_referenceid').val();
                            var drug_description = $('#drug_description').val();
                            var drug_use_period = $('#drug_use_period').val();

                            var count_701 = $(".eklenen_ilac_sec").find("[ilac_id='" + drug_id + "']").attr("ilac_id");

                            if (count_701 > 0) {
                                alertify.error('İlaç Zaten Ekli');
                            } else {

                                if (drug_use_form != null && drug_use_type != null && box_pieces != '' && drug_id != '' && drug_use_period != null) {
                                    var serial_number = $('.serial_number').val();
                                    var recipe_time = $('.recipe_time').val();
                                    var recipe_detail = $('.recipe_detail').val();
                                    var recipe_tour = $('.recipe_tour').val();
                                    var recipe_sub_tour = $('.recipe_sub_tour').val();
                                    var patient_id = "<?php echo $hasta_kayit['patient_id']; ?>";


                                    $.ajax({
                                        url: 'ajax/recete/recetesql.php?islem=sql_recete_ekle',
                                        type: 'POST',
                                        data: {
                                            serial_number,
                                            patient_id,
                                            recipe_time,
                                            recipe_detail,
                                            recipe_tour,
                                            recipe_sub_tour,
                                            drug_use_form,
                                            drug_use_type,
                                            box_pieces,
                                            drug_id,
                                            patient_referenceid,
                                            drug_description,
                                            drug_use_period
                                        },
                                        success: function (e) {
                                            $("#sonucyaz").html(e);
                                            $.get("ajax/recete/recetetablo.php?islem=recete_eklenen_ilaclar", {getir:<?php echo $protokol; ?> }, function (getVeri) {
                                                $('#recete_eklenen_ilac_tablo').html(getVeri);
                                            });
                                        }
                                    });
                                } else if (drug_id == '') {
                                    alertify.warning("İlaç seçiniz.");
                                } else if (drug_use_form == null) {
                                    alertify.warning("Kullanım şekli seçiniz.");
                                } else if (drug_use_type == null) {
                                    alertify.warning("Kullanım tipi seçiniz.");
                                } else if (drug_use_period == null) {
                                    alertify.warning("Kullanım periyodu seçiniz.");
                                } else if (box_pieces == '') {
                                    alertify.warning("Doz bilgisi giriniz.");
                                } else if (drug_description == '') {
                                    alertify.warning("Açıklama bilgisi giriniz.");
                                }
                            }
                        },
                        success: function(result) {
                            // Kaydetme işlemi başarılı olduğunda yapılacak işlemler
                            $('#talimat-icerik-window').dialog('close');
                        }
                    });
                }
            },{
                text: 'İptal',
                iconCls: 'icon-cancel',
                handler: function() {
                    $('#talimat-icerik-window').dialog('close');
                }
            }]
        });


        $('#talimat-icerik-window').dialog('open');



        }




</script>
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.bootstrap5.css"/>-->
<div style="display: none;">
    <div class="recete-yazdir">
        <div class="text-center borer-701">
            <p class="fw-bold fs-5">T.C. **** Valiliği İl Sağlık Müdürlüğü <?php echo hastane_adi; ?> Devlet
                Hastanesi</p>
        </div>

        <table class="table table-border">
            <thead>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            </thead>
            <tbody>
            <tr>
                <td>
                    Adı Soyadı:
                </td>
                <td>
                    <?php echo $hastalar['patient_name'] . ' ' . $hastalar['patient_surname'] . '(' . $YAS . ')'; ?>
                </td>
                <td>
                    Reçete Tarihi:
                </td>
                <td>
                    <?php echo $simdikitarih; ?>
                </td>
            </tr>
            <tr>
                <td>
                    T.C. Kimlik No:
                </td>
                <td>
                    <?php echo $hastalar["tc_id"]; ?>
                </td>
                <td>
                    Protocol No:
                </td>
                <td>
                    <?php echo $protokol; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Kurum:
                </td>
                <td>
                </td>
                <td>
                    Takip No:
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    Uzmanlık:
                </td>
                <td>
                </td>
                <td>
                    E-Reçete No:
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    Açıklama:
                </td>
                <td>
                </td>
                <td>
                    Reçete Türü/Alt Türü:
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    Tanı:
                </td>
                <td>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>

    <div id="talimat-icerik-window" class="easyui-dialog" closed="true" modal="true" title="İlaç Talimat Bilgisi" style="width:50%; height:50%;">

            <form class="talimat-icerik-form">

                    <select class="easyui-combobox" labelPosition="left" name="drug_use_form" id="drug_use_form" label="Kullanım Şekli:" labelWidth="120" style="width: 100%;">
                        <option>Seçim yapınız..</option>
                        <?php $sql = sql_select("select * from transaction_definitions WHERE definition_type='ILAC_KULLANIM_SEKLI' and transaction_definitions.status='1'");
                        foreach ($sql as $item) { ?>
                            <option value="<?php echo $item["id"]; ?>"><?php echo $item["definition_name"]; ?></option>
                        <?php } ?>
                    </select>

                    <select class="easyui-combobox" labelPosition="left" name="drug_use_type" id="drug_use_type" label="Kullanım Şekli:" labelWidth="120" style="width: 100%;">
                        <option>Seçim yapınız..</option>
                        <?php $sql = sql_select("select * from transaction_definitions where definition_type='ILAC_KULLANIM_TIPI' and transaction_definitions.status='1'");
                        foreach ($sql as $item) { ?>
                            <option value="<?php echo $item["id"]; ?>"> <?php echo $item["definition_name"]; ?></option>
                        <?php } ?>
                    </select>

                <div style="display: flex;">
                    <select class="easyui-combobox" labelPosition="left" name="drug_use_period" id="drug_use_period" label="Kullanım Periyodu:" labelWidth="120" style="width: 70%;">
                        <option>Seçiniz</option>
                        <?php $sql = sql_select("select * from transaction_definitions where definition_type='ILAC_KULLANIM_PERIYODU' and transaction_definitions.status='1'");
                        foreach ($sql as $item) { ?>
                            <option value="<?php echo $item["definition_name"]; ?>"> <?php echo $item["definition_name"]; ?></option>
                        <?php } ?>
                        <option class="bg-primary text-white" value="007">Manuel Giriş Yap</option>
                    </select>

                    <input name="drug_use_period_gun" id="drug_use_period_gun" class="easyui-numberbox" style="width: 10%;"/>
                    <label>X</label>
                    <input name="drug_use_period_adet" id="drug_use_period_adet" class="easyui-numberbox" style="width: 10%;"/>

                </div>

             <input id="drug_description" labelPosition="left" class="easyui-textbox" name="drug_description" label="Açıklama:" multiline="true" labelWidth="120" style="width: 100%; height: 100px;">

                <input type="text" hidden id="drug_id" name="drug_id"/>
                <input type="text" hidden id="patient_referenceid" name="patient_referenceid" value="<?php echo $protokol ?>"/>

            </form>


    </div>



