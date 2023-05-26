<?php include "../controller/fonksiyonlar.php";  ?>

<div class="easyui-tabs" data-options="fit:true" id="hasta-kayit-tab">
<div title="Hasta Kayıt" data-options="iconCls:'fa-solid fa-clipboard-medical'">

<div class="easyui-layout" id="hasta-kayit-ana-layout" fit="true" style="width:100%;height:100%;">

<div data-options="region:'east',split:true , hideCollapsedContent:false" title="İletişim Bilgileri" style="width:35%; overflow-y: hidden;">

<form id="hasta-kayit-ana-form">

<input class="easyui-numberbox" label="Telefon 1:" labelWidth="75" autocomplete="off"  id="phone_number" name="phone_number" style="width: 100%;">
<input class="easyui-numberbox" label="Telefon 2:" labelWidth="75" autocomplete="off"  name="phone_number_2" style="width: 100%;">

<select class="easyui-combobox" label="İl:" labelWidth="75" name="county" id="il-selector" style="width: 100%;">
    <option>İl Seçiniz...</option>
    <?php $sql = sql_select("select * from province");
    foreach ($sql as $item) { ?>
        <option value="<?php echo $item["id"]; ?>"><?php echo $item["province_name"];?></option>
    <?php } ?>
</select>

<select class="easyui-combobox" label="İlçe" labelWidth="75" name="district" id="ilce" style="width: 100%;">
    <option>Önce İl Seçiniz...</option>
</select>

<input class="easyui-textbox" label="Adres:" labelWidth="75" data-options="multiline:'true'" id="acikadres" autocomplete="off" name="address" style="width: 100%; height: 50px;">

</form>

<footer>
    <button disabled class="easyui-linkbutton poliklinik-butonu" id="poliklinik-btn"><i class="fa-solid fa-vector-square"></i> Poliklinik</button>
    <button disabled class="easyui-linkbutton randevubutonu" id="randevu-btn"><i class="fa-solid fa-calendar-days"></i> Randevu</button>
    <button disabled class="easyui-linkbutton" id="hasta-bilgisi-guncelle-btn"><i class="fa-solid fa-pen-to-square"></i> Güncelle</button>
    <button disabled class="easyui-linkbutton" id="yeni-hasta-kaydet-btn" ><i class="fa-solid fa-floppy-disk"></i> Kaydet</button>
    <button class="easyui-linkbutton" id="formu-reset-btn" onclick="form_reset()"><i class="fa-solid fa-rectangle-history-circle-plus"></i> Yeni</button>
    <button class="easyui-linkbutton" data-bs-toggle="modal" data-bs-target="#hastaara"><i class="fa-solid fa-folder-magnifying-glass"></i> Ara</button>
</footer>

    </div>

    <div data-options="region:'west',split:true , hideCollapsedContent:false" title="Hasta Bilgileri" style="width:40%; overflow-y: hidden;">

        <div class="easyui-layout" data-options="fit:true">
<!--------------------------------------------------------------------------------------------------------------------->
            <div data-options="region:'west', split:false" style="width:50%; overflow-y: hidden;">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'west' , border:true , split:true , hideCollapsedContent:false , collapsed:true" title="Fotoğraf" style="overflow-y: hidden; width: 50%; height: 50%;">
                        <div id="live_camera"><img id="sonfotograf" src="assets/img/dummy-user.jpeg" alt="dummy user" width="100%"></div>
                        <div style="display: flex;">
                            <i class="fa-sharp fa-solid fa-camera fa-2x" onclick="cift_tikla()" style="cursor: pointer;"></i>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <i class="fa-solid fa-circle-check fa-2x" onclick="capture_web_snapshot()" style="cursor: pointer; color: green;"></i>
                        </div>
                    </div>

                    <div data-options="region:'center',split:true , border:false " style="width: 100%; overflow-y: hidden;">

                <form id="hasta-kayit-ana-form-2">
                        <input label="TC No:" labelWidth="75" id="tc_kimlik_al" class="easyui-numberbox"  data-options=" icons: [{
                        iconCls:'icon-cancel pop',
                        class:'pop',
                        id:'pop',
                        handler: function(e){
                            $(e.data.target).textbox('clear');
                            form_reset_2();
                            input_disable();
                    }}]" name="tc_id" style="width: 100%;">
                        <input class="easyui-textbox" label="Adı:" labelWidth="75"        id="patient_name" name="patient_name"                   style="width:100%; ">
                        <input class="easyui-textbox" label="Soyadı:" labelWidth="75"     id="patient_surname" name="patient_surname"              style="width:100%; ">
                        <input class="easyui-textbox" data-options="label:'Anne Adı' , labelWidth:75"  id="anneadidegeri" name="mother"       style="width:100%; ">
                        <input class="easyui-textbox" label="Baba Adı:" labelWidth="75"   id="babaadidegeri" name="father"                style="width:100%; ">
                        <select class="easyui-combobox" label="Cinsiyet:" labelWidth="75" id="cinsiyet" name="gender"            style="width:100%;">
                            <option>Seçiniz</option>
                            <?php $sql = sql_select("select * from transaction_definitions where definition_type='CINSIYET'");
                            foreach ($sql as $rowa) { ?>
                                <option id="<?php echo $rowa["definition_supplement"]; ?>" value="<?php echo $rowa["definition_supplement"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                            <?php } ?>
                        </select>
                </form>

                    </div>

                </div>
            </div>
<!--------------------------------------------------------------------------------------------------------------------->
            <div data-options="region:'center' , border:false" style="width:50%; overflow:hidden;">

                <form id="hasta-kayit-ana-form-4">

                <input  class="easyui-datebox" label="Doğum Tarihi:" labelWidth="100" name="birth_date" id="DOGUMTARIHIDEGER" id="date" style="width: 100%; ">
                <select class="easyui-combobox" label="Doğum Yeri:"  labelWidth="100" name="birth_place" id="dogumyeri" style="width: 100%; ">
                    <option>Seçiniz</option>
                    <?php $sql = sql_select("select * from province");
                    foreach ($sql as $rowa) { ?>
                        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["province_name"]; ?></option>
                    <?php } ?>
                </select>
                <select class="easyui-combobox" label="Medeni Durum:" labelWidth="100" id="medenihali" name="marital_status" style="width:100%;  ">
                    <option>Seçiniz</option>
                    <?php
                    $sql = sql_select("select * from transaction_definitions where definition_type='MEDENI_HALI'");
                    foreach ($sql as $rowa) { ?>
                        <option value="<?php echo $rowa["definition_supplement"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                    <?php } ?>
                </select>
                <select class="easyui-combobox" label="Kan Grubu:" labelWidth="100" name="blood_group" id="kangrubu" style="width: 100%; ">
                    <option>Seçiniz</option>
                    <?php $sql = "select * from transaction_definitions where definition_type='KAN_GRUBU'";
                    $sql_2=sql_select($sql);
                    foreach ($sql_2 as $rowa) { ?>
                        <option  value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                    <?php } ?>
                </select>
                <select class="easyui-combobox" label="Öğrenim Durumu:" labelWidth="100" name="education_status" id="ogrenim_durumu" style="width: 100%; ">
                    <option value="">Seçiniz</option>
                    <?php $sql = sql_select("select * from skrs_definitions where system_code='3cdc2ba0-03de-46f4-8ace-684c94712349'  order by definitions_explain ASC");
                    foreach ($sql as $rowa) { ?>
                        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definitions_explain"]; ?></option>
                    <?php } ?>
                </select>
                <select class="easyui-combobox" label="Mesleği:" name="job" labelWidth="100"   style="width:100%; ">
                    <option>Seçiniz</option>
                    <?php $sql = sql_select("select * from skrs_definitions where system_code='c3eaf407-b302-5fdd-e043-14031b0a2484' order by definitions_explain ASC limit 10");
                    foreach ($sql as $rowa) { ?>
                        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definitions_explain"]; ?></option>
                    <?php } ?>
                </select>

                </form>

            </div>
        </div>
    </div>

<div data-options="region:'center'" title="Kurum/Diğer" style="width:25%; overflow-y: hidden;">
    <form id="hasta-kayit-ana-form-3">
<input class="easyui-textbox" placeholder="Anne Kimlik" label="Anne TC no:" labelWidth="80" name="mother_tc_id" id="mother_tc_id" style="width: 100%;">
<select class="easyui-combobox" label="Uyruk" labelWidth="80" id="uyruk" name="nationality" style="width: 100%;">
    <option value="">seçiniz</option>
    <?php $kurumsql = sql_select("select * from transaction_definitions where definition_type='UYRUK'");
    foreach ($kurumsql as $rowa) { ?>
        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
    <?php } ?>
</select>

 <select class="easyui-combobox" label="Kurum:" labelWidth="80" id="kurum_seciniz" name="institution" style="width: 100%;">
     <option>seçiniz</option>
     <?php $kurumsql = sql_select("select * from transaction_definitions where definition_type='KURUMLAR'");
     foreach ($kurumsql as $rowa) { ?>
         <option value="<?php echo $rowa["definition_code"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
     <?php } ?>
 </select>

 <select class="easyui-combobox" label="Alt Kurum:" labelWidth="80" id="sosyal_guvence" name="social_assurance" style="width: 100%;">
     <option value="">Kurum seçiniz</option>
 </select>

<select class="easyui-combobox" label="Öncelik:" labelWidth="80" id="oncelik" name="priority" style="width: 100%;">
    <option value="">Seçiniz</option>
    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='ONCELIK'");
    foreach ($sql as $rowa) { ?>
        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
    <?php } ?>
</select>

<input class="easyui-textbox" label="Pas. No:" labelWidth="80" name="passport_number" id="pasaportnumarasi" style="width:100%;">

    </form>

</div>

    <div data-options="region:'south',split:true , hideCollapsedContent:false" style="height:65%;">

        <div class="easyui-layout" data-options="fit:true"   style="width: 100%; height: 100%;">
            <div data-options="region:'west' ,split:true , hideCollapsedContent:false"  title="Kayıtlı Hasta Listesi"   style="width: 50%;height: 100%; padding:5px;">

                <div class="easyui-datagrid" id="kayitli-hastalar-listesi" style="width:100%;height:100%" data-options="fit:true , singleSelect:true,collapsible:true , pagination:true " idField="id">
                </div>

                <script>
                    $(document).ready(function (){
                        $('#kayitli-hastalar-listesi').datagrid({
                            url: 'ajax/hastaislemleri/hasta-kayit-detay.php?islem=kayitli-hasta-listesi',
                            columns: [[
                                {
                                    field: 'patient_name', title: 'Hasta Adı', formatter: function (value, row, index) {
                                        return row.patient_name + " " + row.patient_surname;
                                    }
                                },
                                {field:'kimlik_no',title: 'Kimlik No'},
                                {field:'muracat_tarihi',title: 'Müracat Tarihi'},
                                {field:'polikilinik',title: 'Bölüm'},
                                {field:'protokol_no',title: 'Protokol'},

                            ]],

                            onClickRow: function(rowIndex, row) {

                                var adi = row.patient_name +" "+ row.patient_surname;
                                var tab_class = row.protokol_no;
                                var tabs_count = $('.' + tab_class).length;
                                if (tabs_count == 0) {
                                    $.get("ajax/poliklinik/hasta-detay-islem.php?deger="+row.deger, function (e) {
                                        var tabs = $('#hasta-kayit-tab').tabs('add', {
                                            iconCls: "fa-solid fa-hospital-user " + tab_class,
                                            title: adi,
                                            content: '<div class="sayfa-icerik" style="height:100%; width: 100%;" >' + e + '</div>',
                                            closable: true,

                                        });
                                    });

                                } else {
                                    $("." + tab_class).trigger("click");
                                }
                            },

                            queryParams:{},
                        });

                        $("body").off("click", ".tanigetir").on("click", ".tanigetir", function (e) {
                            $(".tanigetir").removeClass("tanibtnsec");
                            $(this).addClass("tanibtnsec");
                            var protokol_no = $(this).attr("protokol-no");
                            var diagnosis_modul = $(this).attr("diagnosis-modul");
                            if (!protokol_no) {
                                alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
                            } else {
                                var tip = 0;
                                var modul = "ayaktan";
                                var protokol_no = $(this).attr("protokol-no");
                                $.get("ajax/tani/tanilistesi.php?islem=tanilistesigetir", {
                                    protokolno: protokol_no,
                                    diagnosis_modul: diagnosis_modul,
                                    tip: tip,
                                    modul: modul
                                }, function (e) {
                                    $('#tani-modal1').modal('show');
                                    $('#tani-icerik').html(e);
                                });
                            }
                        });

                        $("body").off("click", ".yatistalep").on("click", ".yatistalep", function (e) {
                            var protokol_no = $(this).attr("protokol-no");
                            if (!protokol_no) {
                                alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
                            } else {
                                $.get("ajax/yatis/yatismodalbody.php?islem=yatistalepbody", {getir: protokol_no}, function (getVeri) {
                                    $('#yatis-modal').modal('show');
                                    $('#yatis-icerik').html(getVeri);
                                });
                            }
                        });

                        $("body").off("click", "#recete").on("click", "#recete", function (e) {
                            var protokol_no = $(this).attr("protokol-no");
                            if (protokol_no) {
                                $.get("ajax/recete/recetemodal.php?islem=modal_recete", {getir: protokol_no}, function (e) {
                                    if (e == 500) {
                                        alertify.error('Önce Tanı Giriniz!');
                                    } else {
                                        $('#ana-icerik').html(e);
                                        $('#ana-modal').modal('show');
                                    }
                                });
                            } else {
                                alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
                            }
                        });


                    });
                </script>



            </div>

            <div data-options="region:'center' , split:true" style="width: 50%;height: 100%;">

                <div class="easyui-tabs" fit="true" id="hasta-kayit-tab">

                    <div title="Geçmiş Muayeneleri" data-options="iconCls:'fa-solid fa-history' , selected:'true'">
                        <div class="easyui-datagrid" id="gecmis-muayeneler-datagrid"  style="width:100%;height:100%" data-options="singleSelect:true,collapsible:true" idField="id">
                        </div>
                    </div>

                    <div title="Hastaya Ait Randevular" data-options="iconCls:'fa-solid fa-calendar-days'">
                        <div id="hastaya-ait-randevular-datagrid-toolbar">
                            <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="randevu_iptali()">Randevu İptali</a>
                            <a class="easyui-linkbutton" iconCls="icon-save" plain="true"   onclick="gelmedi()">Hasta Gelmedi</a>
                            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true"   onclick="randevu_iptali_geri_al()">Randevu İptali Geri Al</a>
                        </div>
                        <div class="easyui-datagrid" id="hastaya-ait-randevular-datagrid" fitColumns="true" toolbar="hastaya-ait-randevular-datagrid-toolbar" style="width:100%;height:100%" data-options="singleSelect:true,collapsible:true">
                        </div>
                    </div>

                    <div title="Yeni Kayıtlar" data-options="iconCls:'fa-solid fa-user-plus'">
                        <div id="yeni-kayitlar-datagrid-toolbar">
                            <a  class="easyui-linkbutton" iconCls="icon-add"    plain="true"    onclick="">....</a>
                            <a  class="easyui-linkbutton" iconCls="icon-remove" plain="true"    onclick="">....</a>
                            <a  class="easyui-linkbutton" iconCls="icon-save"   plain="true"    onclick="">....</a>
                            <a  class="easyui-linkbutton" iconCls="icon-undo"   plain="true"    onclick="">....</a>
                        </div>
                        <div class="easyui-datagrid" toolbar="yeni-kayitlar-datagrid-toolbar" fitColumns="true" id="yeni-kayitlar-datagrid" style="width:100%;height:100%" data-options="singleSelect:true,collapsible:true">
                        </div>
                    </div>

                </div>

            </div>

        </div>

            </div>


    </div>


</div>
</div>
</div>



<div id="window-alert-randevu-iptal" class="easyui-dialog" closed="true" style="width:400px;" > </div>
<div class="window-poliklinik"></div>
<div class="window-randevu"></div>

<script id="myScript">
        $(document).ready(function() {

            $("body").off("click", ".pop").on("click", ".pop", function (e) {
                input_disable();
                $('#hasta-kayit-ana-form').form('clear');
                $('#hasta-kayit-ana-form-2').form('clear');
                $('#hasta-kayit-ana-form-3').form('clear');
                $('#hasta-kayit-ana-form-4').form('clear');
                input_disable();
                $('#yeni-hasta-kaydet-btn').prop('disabled' , true);
                $('#yeni-hasta-kaydet-btn').removeClass('l-btn-disabled');
            });

            $('#il-selector').combobox({
                onChange: function(newValue, oldValue){
                    $.ajax({
                        url: 'ajax/hastaislemleri/hasta-kayit-detay.php?islem=ilceleri-getir&province_id='+newValue+'',
                        dataType: 'json',
                        success: function(data){
                            $('#ilce').combobox({
                                valueField: 'id',
                                textField: 'district_name',
                                data: data,
                                filter: function(q, row){
                                    // Filtreleme işlemleri burada yer alır
                                }
                            });
                        }
                    });
                }
            });

            $("#formu-reset-btn").on("click", function(){
                $('#hasta-kayit-ana-form').form('clear');
                $('#hasta-kayit-ana-form-2').form('clear');
                $('#hasta-kayit-ana-form-3').form('clear');
                $('#hasta-kayit-ana-form-4').form('clear');
                input_disable();
                $('#yeni-hasta-kaydet-btn').prop('disabled' , true);
                $('#yeni-hasta-kaydet-btn').removeClass('l-btn-disabled');
            });

            function input_enable() {
                $('#poliklinik-btn').prop('disabled', false);
                $('#poliklinik-btn').removeClass('l-btn-disabled');
                $('#randevu-btn').prop('disabled', false);
                $('#randevu-btn').removeClass('l-btn-disabled');
                $('#hasta-bilgisi-guncelle-btn').prop('disabled', false);
                $('#hasta-bilgisi-guncelle-btn').removeClass('l-btn-disabled');
            }

            function input_disable(){
                $('#poliklinik-btn').prop('disabled', true);
                $('#poliklinik-btn').addClass('l-btn-disabled');
                $('#randevu-btn').prop('disabled', true);
                $('#randevu-btn').addClass('l-btn-disabled');
                $('#hasta-bilgisi-guncelle-btn').prop('disabled', true);
                $('#hasta-bilgisi-guncelle-btn').addClass('l-btn-disabled');
            }

            $('.window-randevu').dialog({
               closed:true,
               title:'randevu',
               fit:true,

            });

            $('.window-poliklinik').dialog({
           modal:true,
           title:'Poliklinik Bilgileri',
           height: 500,
           width: 1000,
           closed:true,
           cache:false
        });

            $('#window-alert-randevu-iptal').dialog({
                title:'Randevu İptali',
                closed:true,
                modal:true,
                height: 350,
                content:'İptal Nedeni Belirtiniz... <input class="easyui-textbox" id="randevu-iptal-nedeni" multiline="true"  style="width: 100%; height: 200px;"/>',
                buttons: [{
                    text:'Onayla',
                    iconCls:'icon-ok',
                    handler:function(){
                        var id = randevu_id_selector();
                        var randevu_iptal_nedeni = $("#randevu-iptal-nedeni").val();
                        $.ajax({
                            type: "post",
                            url: "ajax/hastaislemleri/hasta-kayit-detay.php?islem=randevu-iptali",
                            data: {id: id, reason_for_appointment_cancellation: randevu_iptal_nedeni},
                            success: function (e) {
                                $("#sonuc-yaz").html(e);
                                $('#hastaya-ait-randevular-datagrid').datagrid('reload');
                                $('#window-alert-randevu-iptal').dialog('close');
                            }
                        });
                    }
                }]
            });

        function randevu_id_selector(){
            var selectedRow = $('#hastaya-ait-randevular-datagrid').datagrid('getSelected');
            if (selectedRow != null ) {
                var selectedRowId = selectedRow.randevu_id;
                return selectedRowId;
            }else{
                alertify.error("İşlem Yapmak İçin Tabloda Seçim Yapınız...");
            }
        }

        function gelmedi(){
            var id = randevu_id_selector();
            $.ajax({
                type: "post",
                url: "ajax/hastaislemleri/hasta-kayit-detay.php?islem=gelmedi",
                data: {id: id},
                success: function (e) {
                    $("#sonuc-yaz").html(e);
                    $('#hastaya-ait-randevular-datagrid').datagrid('reload');
                    $('#window-alert-randevu-iptal').dialog('close');
                }
            });
        }

        function randevu_iptali(){
            var id = randevu_id_selector();
            $('#window-alert-randevu-iptal').dialog('open');
        }

        function randevu_iptali_geri_al(){
            var id = randevu_id_selector();
            $.ajax({
                type: "post",
                url: "ajax/hastaislemleri/hasta-kayit-detay.php?islem=randevu-iptalini-geri-al",
                data: {id: id},
                success: function (e) {
                    $("#sonuc-yaz").html(e);
                    $('#hastaya-ait-randevular-datagrid').datagrid('reload');
                    $('#window-alert-randevu-iptal').dialog('close');
                }
            });
        }

        function yeni_randevu(){
            var id = randevu_id_selector();
        }

        $(function(){
            $('#hasta-kayit-tab').tabs({
                cache:true,
                onSelect: function(title, index){
                    var tab = $('#hasta-kayit-tab').tabs('getTab', index);
                    var tc_no = $('#tc_kimlik_al').val();
                    if(index == 1) {
                        $('#hastaya-ait-randevular-datagrid').datagrid({
                            url: 'ajax/hastaislemleri/hasta-kayit-detay.php?islem=hastaya-ait-randevular',
                            columns: [[
                                {field:'department_name',title: 'Bölüm Adı'},
                                {field:'name_surname', title: 'Doktor Adı'},
                                {field:'appointment_time',title: 'Randevu Zamanı'},
                                {field:'randevu_durumu', title: 'Durum', formatter: function(value, row, index) {

                                        if (row.randevu_durumu == 1){
                                            return '<span style="color:green;">Aktif</span>';
                                        }else if (row.randevu_durumu == 2){
                                            return '<span style="color:red;">Randevusu İptal Edilmiş</span>';
                                        }else if(row.randevu_durumu == 3){
                                            return '<span style="color:red;">Gelmedi</span>';
                                        }
                                    }}
                            ]],
                            queryParams: {tc_no: tc_no},
                        });
                    }
                }
            });
        });

        function gecmis_muayeneler() {
            var tc_no = $('#tc_kimlik_al').val();
            $('#gecmis-muayeneler-datagrid').datagrid({
                url: 'ajax/hastaislemleri/hasta-kayit-detay.php?islem=hasta-gecmis-muayeneleri',
                columns: [[
                    {field: 'protokol_no', title: 'Protokol No'},
                    {field: 'hasta_kayit_tarihi', title: 'Kayıt Tarihi'},
                    {field: 'bolum_adi', title: 'Birim'},
                    {field: 'doktor_adi', title: 'Doktor',}
                ]],
                queryParams: {tc_no: tc_no},
            });
        }

                $('#tc_kimlik_al').numberbox({
                    disabled: false,
                    buttonText:'<i class="kpssorgula fa-regular fa-shield-check"></i>',
                    buttonIcon:'',
                    inputEvents: $.extend({}, $.fn.textbox.defaults.inputEvents, {
                        keypress: function (e) {
                            if(e.which === 13){

                             var tc_no =    $('#tc_kimlik_al').val();
                             var data;
                                $.ajax({
                                    url: 'ajax/hastaislemleri/hasta-kayit-detay.php?islem=hastayi-sistemde-sorgula&tc_no=' + tc_no + '',
                                    type: 'POST',
                                    dataType: 'json',
                                    success: function(response) {
                                        data = response;
                                        $('#hasta-kayit-ana-form').form('load', data);
                                        $('#hasta-kayit-ana-form-2').form('load', data);
                                        $('#hasta-kayit-ana-form-3').form('load', data);
                                        $('#hasta-kayit-ana-form-4').form('load', data);
                                        if(data){
                                            input_enable();
                                            $('#yeni-hasta-kaydet-btn').prop('disabled' , false);
                                            $('#yeni-hasta-kaydet-btn').addClass('l-btn-disabled');
                                            gecmis_muayeneler();
                                        }else{
                                            $('#yeni-hasta-kaydet-btn').prop('disabled' , true);
                                            $('#yeni-hasta-kaydet-btn').removeClass('l-btn-disabled');
                                            alertify.alert("Uyarı", "Hasta İlk Defa Geliyor");
                                        }
                                    }
                                });

                               var tc_no_len = tc_no.length;
                                if (tc_no_len > 11 ){
                                    alertify.alert("Uyarı", "Tc Kimlik Numarası 11 Haneden Büyük Olamaz : ");
                                }else if(tc_no_len < 11){
                                    alertify.alert("Uyarı", "Tc Kimlik Numarası 11 Haneden Küçük Olamaz : ");
                                }else if((tc_no%2) == 1){
                                }
                                else {

                                }
                            }
                        }
                    }),

                    onClickButton: function(){
                        var hastalarkaydetbutonu = document.getElementById("hastalarkaydetbutonu");
                        var TC_KIMLIK= document.getElementById("tc_kimlik_al").value;
                        alertify.success(TC_KIMLIK + " Kimlik Numarasını Sorguluyorum");
                        $.get( "ajax/webservisleri/kpssorgula.php", { tc_id:TC_KIMLIK },function(getVeri){
                            if(getVeri!=0){

                                alertify.success(TC_KIMLIK + "Sorgulamam tamamlandı");

                            //    AcikAdres = arasinial_js(getVeri, "<AcikAdres>", "</AcikAdres>");
                            //     if(AcikAdres){
                            //         document.getElementById("acikadres").value = AcikAdres;
                            //     }

                                $('.gecmismuayenegetir').trigger('click');

                            }else{
                                alertify.alert("Hata","Sorgulama yaptığınız TC Kimlik numarası hatalı olabilir veya sistem yanıt vermiyor.");
                            }

                        });
                    }
                });
        });
    </script>


<script type="text/javascript">

    // function cift_tikla() {
    //     var x = document.getElementById("gizle");
    //     x.style.display = "block";
    //     Webcam.set({
    //         width: 150,
    //         height: 150,
    //         image_format: 'jpeg',
    //         jpeg_quality: 90
    //     });
    //
    //     Webcam.attach('#live_camera');
    // }

    function capture_web_snapshot() {
        Webcam.snap(function (site_url) {
            $(".image-tag").val(site_url);
            document.getElementById('live_camera').innerHTML =
                '<img id="sonfotograf" width="95%;" src="'+site_url+'"/>';
        });
    }
    // $("#tc_kimlik_al").focus();

    function showResult(str) {
        if (str.length==0) {
            document.getElementById("livesearch").innerHTML="";
            document.getElementById("livesearch").style.border="0px";
            return;
        }
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
                document.getElementById("livesearch").innerHTML=this.responseText;
                document.getElementById("livesearch").style.border="1px solid #A5ACB2";
            }
        }
        xmlhttp.open("GET","ajax/hastaara.php?q="+str,true);
        xmlhttp.send();
    }

    $(document).ready(function() {
        $("form").bind("keypress", function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
    });

    $(".poliklinik-butonu").click(function () {
        var tc_id = $('#tc_kimlik_al').val();
        $('.window-poliklinik').dialog('open');
        $('.window-poliklinik').dialog('refresh' , 'ajax/hastaislemleri/poliklinikkayit.php?tc_id='+tc_id+'');
    });

    $("#randevu-btn").click(function () {
        var tc_id = $('#tc_kimlik_al').val();
        var adi=    $("#patient_name").val();
        var soyadi= $("#patient_surname").val();

        $('.window-randevu').dialog({
            title:'Hasta Randevu İşlemleri-' + adi + " " + soyadi,
            closed:false
        });

        $('.window-randevu').dialog('refresh' , 'ajax/hastaislemleri/poliklinikrandevuislem.php?islem=anaekran&tc_id='+tc_id+'');

    });

    // $.get( "ajax/hastaislemleri/hastakayitdetay.php", function(ihsanson){
    //     $('#hastakayitdetay').css("display" , "block");
    //     $('.hasta-gecmisi').html(ihsanson);
    // });

    function inputafocusla(str){
        var textbox = document.getElementById(str);
        textbox.focus();
        textbox.scrollIntoView();
    }

    $(document).ready(function () {

        $("#yeni-hasta-kaydet-btn").on("click", function(){ // buton idli elemana tıklandığında
            var gonderilenform = $("#gonderilenform").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
            var tc_idaaa = document.forms["gonderilenform"];  // form seçimi
            var tc_isd = tc_idaaa.tc_id;   // eposta alanı seçimi

            $.ajax({
                url: 'ajax/hastaislemleri/hastakayit.php?islem=yenikayit', // serileştirilen değerleri ajax.php dosyasına
                type: 'POST', // post metodu ile
                data: gonderilenform, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                success: function (gonderVeri) { // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    if (gonderVeri == 1) {
                        alertify.success("Kayıt işlemi başarılı");
                    } else {
                        alertify.alert("Uyarı", "Kayıt işleminde hata oluştu. Hata detayı : " + gonderVeri);
                    }
                }
            });
        });

        $("#hasta-bilgisi-guncelle-btn").on("click", function(){ // buton idli elemana tıklandığında

              var data_1 = $('#hasta-kayit-ana-form').serialize();
              var data_2 = $('#hasta-kayit-ana-form-2').serialize();
              var data_3 = $('#hasta-kayit-ana-form-3').serialize();
              var data_4 = $('#hasta-kayit-ana-form-4').serialize();
              all_data = data_1+"&"+data_2+"&"+data_3+"&"+data_4
            $.ajax({
                url:'ajax/hastaislemleri/hastakayit.php?islem=kayitguncelle', // serileştirilen değerleri ajax.php dosyasına
                type:'POST',
                data:all_data, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                success:function(gonderVeri){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    if(gonderVeri==1){
                        alertify.success("Güncelleme işlemi başarılı");
                    }else{
                        alertify.alert("Uyarı","Kayıt işleminde hata oluştu. Hata detayı : " + gonderVeri);
                    }
                }
            });

        });

        inputafocusla("tc_kimlik_al");

        $("#kurum_seciniz").change(function () {
            var kurumid = $(this).val();
            $.ajax({
                type: "post",
                url: "ajax/sosyalguvencekurumsec.php",
                data: {"kurumid": kurumid},
                success: function (e) {
                    $("#sosyal_guvence").html(e);
                }
            })
        })
    });

    // function arasinial_js(str,birinci,ikinci) {
    //     let boluma= str.split(birinci);
    //     let ihsan= boluma[1];
    //     if(ihsan) {
    //         let bolum = ihsan.split(ikinci);
    //         return bolum[0];
    //     }
    // }

  //  $("#hasta-kayit-liste").load("ajax/hasta-kayit-kabul/hasta-kayit-kabul-liste.php?islem=kayit-olan-hastalar");

</script>


