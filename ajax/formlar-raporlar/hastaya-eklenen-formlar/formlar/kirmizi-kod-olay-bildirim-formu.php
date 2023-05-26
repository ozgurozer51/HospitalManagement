<div class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">

    <div data-options="region:'east', collapsed:true , split:true , hideCollapsedContent:false" title="Hastaya Eklenmiş Form Ve Raporlar" style="width:25%;">
        <?php include "../tablo-template.php"; ?>
    </div>

    <div data-options="region:'center',title:''">
        <form id="ff-<?php echo $uniqid; ?>" uniquid="<?php echo $uniqid; ?>" print-url="<?php echo $_GET['print_url']; ?>" table-name="<?php echo $_GET['name']; ?>" form-adi="<?php echo $_GET['form_adi']; ?>">

            <input class="form-table-name" type="hidden" value-2="<?php echo $_GET['name']; ?>">

            <div class="input-align">

                <div class="easyui-panel" id="rapor-form-hasta-bilgi" title="Muayene Edilenin" style="width:50%;max-width:50%;">
                    <?php include "../hasta-bilgi-template.php"; ?>
                </div>

                <div class="easyui-panel" title="OLAY İLE İLGİLİ GENEL BİLGİLER" style="width:50%;max-width:50%;">
                    <div class="ms-2">
                        <input class="easyui-textbox" id="area_of_fire"  name="area_of_fire"  labelWidth=250 data-options="showSeconds:false , label:'Yangının Oluştuğu Bölge:'" label-p="Yangının Oluştuğu Bölge:" style="width:100%">
                        <input class="easyui-textbox" id="cause_of_fire"  name="cause_of_fire"  labelWidth=250 data-options="showSeconds:false , label:'Yangının Başlama Nedeni:'" label-p="Yangının Başlama Nedeni:" style="width:100%">
                        <input class="easyui-textbox" id="negatives_in_environment"  name="negatives_in_environment"  labelWidth=250 data-options="showSeconds:false , label:'Çevrede Oluşan Olumsuzluklar:'" label-p="Çevrede Oluşan Olumsuzluklar:" style="width:100%">
                        <input class="easyui-textbox" id="event_resul"  name="event_resul"  labelWidth=250 data-options="showSeconds:false , label:'Olayın Sonucu:'" label-p="Olayın Sonucu:" style="width:100%">
                    </div>
                </div>

            </div>

                <div class="easyui-panel" title="Müdahale Eden Kişiler İle İlgili Genel Bilgiler" style="width:100%; max-width:100%;">
                    <div class="ms-2">

                        <input class="easyui-textbox" id="response_team_name_1" name="response_team_name_1" data-options="label:'Müdahale Ekibi 1. Kişi Adı:'" label-p="Müdahale Ekibi 1. Kişi Adı:" labelWidth=250 style="width:100%">
                        <input class="easyui-numberbox" id="response_team_phone_number_1" name="response_team_phone_number_1" data-options="label:'Müdahale Ekibi 1. Kişi Telefon No:'" label-p="Müdahale Ekibi 1. Kişi Telefon No:" labelWidth=250 style="width:100%">

                        <input class="easyui-textbox" id="response_team_name_2" name="response_team_name_2" data-options="label:'Müdahale Ekibi 2. Kişi Adı:'" label-p="Müdahale Ekibi 2. Kişi Adı:" labelWidth=250 style="width:100%">
                        <input class="easyui-numberbox" id="response_team_phone_number_2" name="response_team_phone_number_2" data-options="label:'Müdahale Ekibi 2. Kişi Telefon No:'" label-p="Müdahale Ekibi 2. Kişi Telefon No:" labelWidth=250 style="width:100%">

                        <input class="easyui-textbox" id="response_team_name_3" name="response_team_name_3" data-options="label:'Müdahale Ekibi 3. Kişi Adı:'" label-p="Müdahale Ekibi 3. Kişi Adı:" labelWidth=250 style="width:100%">
                        <input class="easyui-numberbox" id="response_team_phone_number_3" name="response_team_phone_number_3" data-options="label:'Müdahale Ekibi 3. Kişi Telefon No:'" label-p="Müdahale Ekibi 3. Kişi Telefon No:" labelWidth=250 style="width:100%">

                        <input class="easyui-textbox" id="response_team_name_4" name="response_team_name_4" data-options="label:'Müdahale Ekibi 4. Kişi Adı:'" label-p="Müdahale Ekibi 4. Kişi Adı:" labelWidth=250 style="width:100%">
                        <input class="easyui-numberbox" id="response_team_phone_number_4" name="response_team_phone_number_4" data-options="label:'Müdahale Ekibi 4. Kişi Telefon No:'" label-p="Müdahale Ekibi 4. Kişi Telefon No:" labelWidth=250 style="width:100%">

                    </div>
                </div>

        </form>
    </div>
</div>