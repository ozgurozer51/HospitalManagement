<div class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">

    <div data-options="region:'east', collapsed:true , split:true , hideCollapsedContent:false" title="Hastaya Eklenmiş Form Ve Raporlar" style="width:25%;">
        <?php include "../tablo-template.php"; ?>
    </div>

    <div data-options="region:'center',title:''">
        <form id="ff-<?php echo $uniqid; ?>" uniquid="<?php echo $uniqid; ?>" print-url="<?php echo $_GET['print_url']; ?>" table-name="<?php echo $_GET['name']; ?>" form-adi="<?php echo $_GET['form_adi']; ?>" style="height: 100%;">

            <input class="form-table-name" type="hidden" value-2="<?php echo $_GET['name']; ?>">

            <div class="input-align">

                <div class="easyui-panel" title="Tatbikat İle İlgili Genel Bilgiler" style="width:50%;max-width:50%;">
                    <div class="ms-2">
                        <input class="easyui-textbox" id="hospital_name"  name="hospital_name"  labelWidth=250 data-options="showSeconds:false , label:'Hastane Adı:'" label-p="Hastane Adı:" style="width:100%">
                        <input class="easyui-datetimebox" id="pratice_datetime"  name="pratice_datetime"  labelWidth=250 data-options="showSeconds:false , label:'Tatbiket Tarihi:'" label-p="Tatbiket Tarihi:" style="width:100%">
                        <input class="easyui-textbox" id="pratice_of_story"  name="pratice_of_story"  labelWidth=250 data-options="showSeconds:false , label:'Tatbikatın Hikayesi:'" label-p="Tatbikatın Hikayesi:" style="width:100%">
                        <input class="easyui-textbox" id="flow_of_pratice"  name="flow_of_pratice"  labelWidth=250 data-options="showSeconds:false , label:'Tatbikatın Akışı:'" label-p="Tatbikatın Akışı:" style="width:100%">
                        <input class="easyui-textbox" id="result_of_pratice"  name="result_of_pratice"  labelWidth=250 data-options="showSeconds:false , label:'Tatbikatın Sonucu:'" label-p="Tatbikatın Sonucu:" style="width:100%">
                        <input class="easyui-textbox" id="positives"  name="positives"  labelWidth=250 data-options="showSeconds:false , label:'Tatbikatın Olumlu Yönleri:'" label-p="Tatbikatın Olumlu Yönleri:" style="width:100%">
                        <input class="easyui-textbox" id="negatives"  name="negatives"  labelWidth=250 data-options="showSeconds:false , label:'Tatbikatın Olumsuz Yönleri:'" label-p="Tatbikatın Olumsuz Yönleri:" style="width:100%">
                        <input class="easyui-textbox" id="rahabilition_suggestions"  name="rahabilition_suggestions"  labelWidth=250 data-options="showSeconds:false , label:'İyileştirme Önerileri'" label-p="İyileştirme Önerileri:" style="width:100%">
                    </div>
                </div>

                <div class="easyui-panel" id="rapor-form-hasta-bilgi" title="Muayene Edilenin" style="width:50%;max-width:50%;">
                    <?php include "../hasta-bilgi-template.php"; ?>
                </div>

            </div>


            <div class="easyui-panel" fit="ture" title="Tatbikata Katılan Kişilerin Bilgileri" >
                <div class="ms-2">

                    <input class="easyui-textbox mt-1" id="driller_name_1" name="driller_name_1" data-options="label:'Tatbikata Katılan 1. Kişinin Adı:'"        label-p="Tatbikata Katılan 1. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_1" name="driller_duty_1" data-options="label:'Tatbikata Katılan 1. Kişinin Görevi:'"     label-p="Tatbikata Katılan 1. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_2" name="driller_name_2" data-options="label:'Tatbikata Katılan 2. Kişinin Adı:'"        label-p="Tatbikata Katılan 2. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_2" name="driller_duty_2" data-options="label:'Tatbikata Katılan 2. Kişinin Görevi:'"     label-p="Tatbikata Katılan 2. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_3" name="driller_name_3" data-options="label:'Tatbikata Katılan 3. Kişinin Adı:'"        label-p="Tatbikata Katılan 3. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_3" name="driller_duty_3" data-options="label:'Tatbikata Katılan 3. Kişinin Görevi:'"     label-p="Tatbikata Katılan 3. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_4" name="driller_name_4" data-options="label:'Tatbikata Katılan 4. Kişinin Adı:'"        label-p="Tatbikata Katılan 4. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_4" name="driller_duty_4" data-options="label:'Tatbikata Katılan 4. Kişinin Görevi:'"     label-p="Tatbikata Katılan 4. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_5" name="driller_name_5" data-options="label:'Tatbikata Katılan 5. Kişinin Adı:'"        label-p="Tatbikata Katılan 5. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_5" name="driller_duty_5" data-options="label:'Tatbikata Katılan 5. Kişinin Görevi:'"     label-p="Tatbikata Katılan 5. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_6" name="driller_name_6" data-options="label:'Tatbikata Katılan 6. Kişinin Adı:'"        label-p="Tatbikata Katılan 6. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_6" name="driller_duty_6" data-options="label:'Tatbikata Katılan 6. Kişinin Görevi:'"     label-p="Tatbikata Katılan 6. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_7" name="driller_name_7" data-options="label:'Tatbikata Katılan 7. Kişinin Adı:'"        label-p="Tatbikata Katılan 7. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox"      id="driller_duty_7" name="driller_duty_7" data-options="label:'Tatbikata Katılan 7. Kişinin Görevi:'"     label-p="Tatbikata Katılan 7. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_8" name="driller_name_8" data-options="label:'Tatbikata Katılan 8. Kişinin Adı:'"        label-p="Tatbikata Katılan 8. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_8" name="driller_duty_8" data-options="label:'Tatbikata Katılan 8. Kişinin Görevi:'"     label-p="Tatbikata Katılan 8. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_9" name="driller_name_9" data-options="label:'Tatbikata Katılan 9. Kişinin Adı:'"        label-p="Tatbikata Katılan 9. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_9" name="driller_duty_9" data-options="label:'Tatbikata Katılan 9. Kişinin Görevi:'"     label-p="Tatbikata Katılan 9. Kişinin Görevi:" labelWidth=250 style="width:100%">

                    <input class="easyui-textbox mt-1" id="driller_name_10" name="driller_name_10" data-options="label:'Tatbikata Katılan 10. Kişinin Adı:'"     label-p="Tatbikata Katılan 10. Kişinin Adı:" labelWidth=250 style="width:100%">
                    <input class="easyui-textbox" id="driller_duty_10" name="driller_duty_10" data-options="label:'Tatbikata Katılan 10. Kişinin Görevi:'"  label-p="Tatbikata Katılan 10. Kişinin Görevi:" labelWidth=250 style="width:100%">

                </div>
            </div>

            </div>

        </form>
    </div>
</div>