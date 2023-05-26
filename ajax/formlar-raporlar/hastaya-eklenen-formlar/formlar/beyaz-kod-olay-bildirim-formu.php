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

            </div>

            <div style="display: flex;">
            <div class="easyui-panel" title="OLAY İLE İLGİLİ GENEL BİLGİLER" style="width:50%;max-width:50%;">
                <div class="ms-2">
                <input class="easyui-datetimebox" id="datetime_of_event"                  name="datetime_of_event"      labelWidth=250 data-options="showSeconds:false , label:'Olayın Olduğu Tarih Ve Saat:'" label-p="Olayın Olduğu Tarih Ve Saat:" style="width:100%">
                <input class="easyui-textbox"     id="location_of_Incident"               name="location_of_incident" labelWidth=250 data-options="label:'Olayın Olduğu Yer:'"                      label-p="Olayın Olduğu Yer:" style="width:100%">
                <input class="easyui-textbox"     id="work_performed_at_time_of_inciden"  name="work_performed_at_time_of_inciden"   data-options="showSeconds:false , label:'Olayda Yapılan İş:'"  label-p="Olayda Yapılan İş:"                   labelWidth=250 style="width:100%">
                <input class="easyui-textbox"     id="type_of_violence"                   name="type_of_violence"                    data-options="showSeconds:false , label:'Olayın Şiddeti:'"     label-p="Olayın Şiddeti:"                      labelWidth=250 style="width:100%">
                <input class="easyui-textbox"     id="cause_of_event"                     name="cause_of_event"                      data-options="label:'Şiddetin Türü:'"                          label-p="Şiddetin Türü:"                       labelWidth=250 style="width:100%">
                <input class="easyui-textbox"     id="how_event_occurred"                 name="how_event_occurred"                  data-options="label:'Olayın Başlama Nedeni:'"                  label-p="Olayın Başlama Nedeni:"               labelWidth=250 style="width:100%">
                <input class="easyui-textbox"     id="object_used_in_event"               name="object_used_in_event"                data-options="label:'Olayda Kullanılan Nesne:'"                label-p="Olayda Kullanılan Nesne:"             labelWidth=250 style="width:100%">
                <input class="easyui-textbox"     id="surroundings_in_incident_negatives" name="surroundings_in_incident_negatives"  data-options="label:'Olayda Çevredi Oluşan Olumsuzluklar:'"    label-p="Olayda Çevredi Oluşan Olumsuzluklar:" labelWidth=250 style="width:100%">
            </div>
            </div>
            <div class="easyui-panel" title="Şiddeti Gerçekliştirenin" style="width:50%; max-width:50%;">
                <div class="ms-2">
                 <input class="easyui-textbox" id="perpetrator_name" name="perpetrator_name" data-options="label:'Adı:'" label-p="Şiddeti Gerçekleştirenin Adı:" labelWidth=250 style="width:100%">
                 <input class="easyui-numberbox" id="age_of_perpetrator_of_violence" name="age_of_perpetrator_of_violence" data-options="label:'Yaşı:'" label-p="Şiddeti Gerçekleştirenin Yaşı:" labelWidth=250 style="width:100%">
                 <input class="easyui-textbox" id="perpetrators_gender" name="perpetrators_gender" data-options="label:'Cinsiyeti:'" label-p="Şiddeti Gerçekleştirenin Cinsiyeti:" labelWidth=250 style="width:100%">
                 <input class="easyui-textbox" id="perpetrators_contact" name="perpetrators_contact" data-options="label:'İletişim Bilgileri:'" label-p="Şiddeti Gerçekleştirenin İletişim Bilgileri:" labelWidth=250 style="width:100%">
                </div>
            </div>
            </div>

            <div style="display: flex; ">
            <div class="easyui-panel" title="Olayı Görenlerin Bilgileri: 1. Kişi" style="width:50%; max-width:50%;">
                <div class="ms-2">
                <input class="easyui-textbox" id="name_of_those_who_saw_event_1"                name="name_of_those_who_saw_event_1" data-options="label:'Adı:'" label-p="1-Olayı Görenin Adı:" labelWidth=250 style="width:100%">
                <input class="easyui-numberbox" id="age_of_those_who_saw_event_1"                 name="age_of_those_who_saw_event_1" data-options="label:'Yaşı:'" label-p="1-Olayı Görenin Yaşı:" labelWidth=250 style="width:100%">
                <input class="easyui-textbox" id="gender_of_those_who_saw_even_1"               name="gender_of_those_who_saw_even_1" data-options="label:'Cinsiyeti:'" label-p="1-Olayı Görenin Cinsiyeti:" labelWidth=250 style="width:100%">
                <input class="easyui-textbox" id="contact_information_of_those_who_saw_event_1" name="contact_information_of_those_who_saw_event_1" data-options="label:'İletişim Bilgileri:'" label-p="1-Olayı Görenin İletişim Bilgileri:" labelWidth=250 style="width:100%">
            </div>
            </div>
            <div class="easyui-panel" title="Olayı Görenlerin Bilgileri: 2. Kişi" style="width:50%; max-width:50%;">
                <div class="ms-2">
                <input class="easyui-textbox" id="name_of_those_who_saw_event_2"                name="name_of_those_who_saw_event_2" data-options="label:'Adı:'" label-p="2-Olayı Görenin Adı:" labelWidth=250 style="width:100%">
                <input class="easyui-numberbox" id="age_of_those_who_saw_event_2"                 name="age_of_those_who_saw_event_2" data-options="label:'Yaşı:'" label-p="2-Olayı Görenin  Yaşı:" labelWidth=250 style="width:100%">
                <input class="easyui-textbox" id="gender_of_those_who_saw_even_2"               name="gender_of_those_who_saw_even_2" data-options="label:'Cinsiyeti:'" label-p="2-Olayı Görenin  Cinsiyeti:" labelWidth=250 style="width:100%">
                <input class="easyui-textbox" id="contact_information_of_those_who_saw_event_2" name="contact_information_of_those_who_saw_event_2" data-options="label:'İletişim Bilgileri:'" label-p="2-Olayı Görenin  İletişim Bilgileri:" labelWidth=250 style="width:100%">
            </div>
            </div>
            </div>

        </form>
    </div>
</div>