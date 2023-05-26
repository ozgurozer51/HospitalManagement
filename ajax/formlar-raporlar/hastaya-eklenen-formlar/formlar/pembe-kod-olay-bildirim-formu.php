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
                        <input class="easyui-datetimebox"         id="event_start_datetime"                 name="event_start_datetime"                data-options="label:'Olayın Başlama Tarihi:'"    label-p="Olayın Başlama Tarihi:"  labelWidth=250   style="width:100%">
                        <input class="easyui-datetimebox"         id="event_end_datetime"                 name="event_end_datetime"                data-options="label:'Olayın Bitiş Tarihi:'"    label-p="Olayın Bitiş Tarihi:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"         id="event_result"                 name="event_result"                data-options="label:'Olay Sonucu:'"    label-p="Olay Sonucu:"  labelWidth=250   style="width:100%">
                        <input class="easyui-numberbox"         id="patient_age"                 name="patient_age"                data-options="label:'Hastanın Yaşı:'"    label-p="Hastanın Yaşı:"  labelWidth=250   style="width:100%">
                        <select id="cc" class="easyui-combobox" name="patient_gender"  data-options="label:'Hastanın Cinsiyeti:'" labelWidth=250   label-p="Hastanın Cinsiyeti:" style="width:100%;">
                            <option>Erkek</option>
                            <option>Kadın</option>
                            <option>Diğer</option>
                        </select>

                        <input class="easyui-textbox" id="name_of_person_filling_out_form" name="name_of_person_filling_out_form" data-options="label:'Formu Dolduran Kişinin Adı:'" label-p="Formu Dolduran Kişinin Adı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="duties_of_person_filling_out_form" name="duties_of_person_filling_out_form" data-options="label:'Formu Dolduran Kişinin Görevi:'" label-p="Formu Dolduran Kişinin Görevi:"  labelWidth=250   style="width:100%">
                        <input class="easyui-numberbox" id="age_of_person_filling_out_form" name="age_of_person_filling_out_form" data-options="label:'Formu Dolduran Kişinin Yaşı:'" label-p="Formu Dolduran Kişinin Yaşı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="contact_of_person_filling_out_form" name="contact_of_person_filling_out_form" data-options="label:'Formu Dolduran Kişinin İletişim Bilgileri:'" label-p="Formu Dolduran Kişinin İletişim Bilgileri:"  labelWidth=250   style="width:100%">


                    </div>
                </div>



                <div class="easyui-panel" id="rapor-form-hasta-bilgi" title="Müdahale Edilen Hastanın" style="width:50%;max-width:50%;">
                    <?php include "../hasta-bilgi-template.php"; ?>
                </div>



            </div>





        </form>
    </div>
</div>