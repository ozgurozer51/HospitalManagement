<div class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">

    <div data-options="region:'east', collapsed:true , split:true , hideCollapsedContent:false" title="Hastaya Eklenmiş Form Ve Raporlar" style="width:25%;">
        <?php include "../tablo-template.php"; ?>
    </div>

    <div data-options="region:'center',title:''">
        <form id="ff-<?php echo $uniqid; ?>" uniquid="<?php echo $uniqid; ?>" print-url="<?php echo $_GET['print_url']; ?>" table-name="<?php echo $_GET['name']; ?>" form-adi="<?php echo $_GET['form_adi']; ?>">

            <input class="form-table-name" type="hidden" value-2="<?php echo $_GET['name']; ?>">

            <div class="input-align">

                <div class="easyui-panel" title="OLAY İLE İLGİLİ GENEL BİLGİLER" style="width:50%;max-width:50%;">
                    <div class="ms-2">
                        <input class="easyui-textbox"         id="unit"                 name="unit"                data-options="label:'Mavi Kod Çağrısının Yapıldığı Birim:'"    label-p="Mavi Kod Çağrısının Yapıldığı Birim:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="name_of_caller"       name="name_of_caller"      data-options="label:'Çağrı Yapan Kişinin Adı Soy Adı:'"        label-p="Çağrı Yapan Kişinin Adı Soy Adı:"      labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="code_blue_complaint"  name="code_blue_complaint" data-options="showSeconds:false , label:'Mavi Kod Şikayeti:'"  label-p="Mavi Kod Şikayeti:"                    labelWidth=250   style="width:100%">
                        <input class="easyui-datetimebox"     id="call_datetime"        name="call_datetime"       data-options="showSeconds:false , label:'Çağrı Zamanı:'"       label-p="Çağrı Zamanı:"                         labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="diagnosis"            name="diagnosis"           data-options="label:'Tanı:'"                                   label-p="Tanı:"                                 labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="code_blue_team_intervention"            name="code_blue_team_intervention"           data-options="label:'Mavi Kod Ekibinin Müdahalesi:'"                                   label-p="Mavi Kod Ekibinin Müdahalesi:"                                 labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="intervention_result"            name="intervention_result"           data-options="label:'Müdahale Sonucu:'"   label-p="Müdahale Sonucu:"                                 labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="related_to_intervention_notes"            name="related_to_intervention_notes"           data-options="label:'Müdahale İle İlgili Notlar:'"   label-p="Müdahale İle İlgili Notlar:"            labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="name_of_medical_personnel_involved"            name="name_of_medical_personnel_involved"           data-options="label:'Müdahale Eden Doktorun Adı:'"   label-p="Müdahale Eden Doktorun Adı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="if_code_blue_team_arrived_in_less_than_3_minutes_reason"            name="if_code_blue_team_arrived_in_less_than_3_minutes_reason"           data-options="label:'3 Dakikadan Geç Sürede Ulaşılma Nedeni:'"   label-p="3 Dakikadan Geç Sürede Ulaşılma Nedeni:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="negativities_in_process"            name="negativities_in_process"           data-options="label:'Süreçde Oluşan Olumsuzluklar:'"   label-p="Süreçde Oluşan Olumsuzluklar:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="anesthesia_technician_name"            name="anesthesia_technician_name"           data-options="label:'Anestezi Teknisyen Adı:'"   label-p="Anestezi Teknisyen Adı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="healthcare_personnel_involved"            name="healthcare_personnel_involved"           data-options="label:'Sağlık Personeli Adı:'"   label-p="Sağlık Personeli Adı:"  labelWidth=250   style="width:100%">
                    </div>
                </div>



                <div class="easyui-panel" id="rapor-form-hasta-bilgi" title="Müdahale Edilen Hastanın" style="width:50%;max-width:50%;">
                    <?php include "../hasta-bilgi-template.php"; ?>
                </div>



            </div>





        </form>
    </div>
</div>