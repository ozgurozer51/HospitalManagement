<div class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">

    <div data-options="region:'east', collapsed:true , split:true , hideCollapsedContent:false" title="Hastaya Eklenmiş Form Ve Raporlar" style="width:25%;">
        <?php include "../tablo-template.php"; ?>
    </div>

    <div data-options="region:'center',title:''">
        <form id="ff-<?php echo $uniqid; ?>" uniquid="<?php echo $uniqid; ?>" print-url="<?php echo $_GET['print_url']; ?>" table-name="<?php echo $_GET['name']; ?>" form-adi="<?php echo $_GET['form_adi']; ?>">

            <input class="form-table-name" type="hidden" value-2="<?php echo $_GET['name']; ?>">

            <div class="input-align">

                <div class="easyui-panel" title="TATBİKAT İLE İLGİLİ GENEL BİLGİLER" style="width:50%;max-width:50%;">
                    <div class="ms-2">

                        <input class="easyui-textbox" id="place_of_exercise" name="place_of_exercise" data-options="label:'Tatbikatın Yapıldığı Yer:'"    label-p="Tatbikatın Yapıldığı Yer:"  labelWidth=250   style="width:100%">
                        <input class="easyui-datetimebox" id="exercise_was_carried_out_datetime" name="exercise_was_carried_out_datetime" data-options="label:'Tatbikatın Yapıldığı Tarih:'"    label-p="Tatbikatın Yapıldığı Tarih:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="purpose_of_exercise" name="purpose_of_exercise" data-options="label:'Tatbikatın Amacı:'" label-p="Tatbikatın Amacı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="story_of_drill" name="story_of_drill" data-options="label:'Tatbikatın Hikayesi:'" label-p="Tatbikatın Hikayesi:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="flow_of_exercise" name="flow_of_exercise" data-options="label:'Tatbikatın Akışı:'" label-p="Tatbikatın Akışı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="flow_of_exercise" name="flow_of_exercise" data-options="label:'Tatbikatın Sonucu:'" label-p="Tatbikatın Sonucu:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="possitives" name="possitives" data-options="label:'Olumlu Yönleri:'" label-p="Olumlu Yönleri:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="negatives" name="negatives" data-options="label:'Negatif Yönleri:'" label-p="Negatif Yönleri:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox" id="improvement_suggestions" name="improvement_suggestions" data-options="label:'İyileştirme Önerileri:'" label-p="İyileştirme Önerileri:"  labelWidth=250   style="width:100%">

                    </div>
                </div>

            </div>

            <div class="easyui-panel" title="Tatbikata Katılan Kişiler" style="width:100%;max-width:100%;">
                <div class="ms-2">

                    <input class="easyui-textbox" id="name_of_exercise_participant_1" name="name_of_exercise_participant_1" data-options="label:'Tatbikata Katılan 1. Kişi Adı:'"         label-p="Tatbikata Katılan 1. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_1" name="duty_of_exercise_participant_1" data-options="label:'Tatbikata Katılan 1. Kişi Görevi:'"      label-p="Tatbikata Katılan 1. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_2" name="name_of_exercise_participant_2" data-options="label:'Tatbikata Katılan 2. Kişi Adı:'"         label-p="Tatbikata Katılan 2. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_2" name="duty_of_exercise_participant_2" data-options="label:'Tatbikata Katılan 2. Kişi Görevi:'"      label-p="Tatbikata Katılan 2. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_3" name="name_of_exercise_participant_3" data-options="label:'Tatbikata Katılan 3. Kişi Adı:'"         label-p="Tatbikata Katılan 3. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_3" name="duty_of_exercise_participant_3" data-options="label:'Tatbikata Katılan 3. Kişi Görevi:'"      label-p="Tatbikata Katılan 3. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_4" name="name_of_exercise_participant_4" data-options="label:'Tatbikata Katılan 4. Kişi Adı:'"         label-p="Tatbikata Katılan 4. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_4" name="duty_of_exercise_participant_4" data-options="label:'Tatbikata Katılan 4. Kişi Görevi:'"      label-p="Tatbikata Katılan 4. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_5" name="name_of_exercise_participant_5" data-options="label:'Tatbikata Katılan 5. Kişi Adı:'"         label-p="Tatbikata Katılan 5. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_5" name="duty_of_exercise_participant_5" data-options="label:'Tatbikata Katılan 5. Kişi Görevi:'"      label-p="Tatbikata Katılan 5. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_6" name="name_of_exercise_participant_6" data-options="label:'Tatbikata Katılan 6. Kişi Adı:'"         label-p="Tatbikata Katılan 6. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_6" name="duty_of_exercise_participant_6" data-options="label:'Tatbikata Katılan 6. Kişi Görevi:'"      label-p="Tatbikata Katılan 6. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_7" name="name_of_exercise_participant_7" data-options="label:'Tatbikata Katılan 7. Kişi Adı:'"         label-p="Tatbikata Katılan 7. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_7" name="duty_of_exercise_participant_7" data-options="label:'Tatbikata Katılan 7. Kişi Görevi:'"      label-p="Tatbikata Katılan 7. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_8" name="name_of_exercise_participant_8" data-options="label:'Tatbikata Katılan 8. Kişi Adı:'"         label-p="Tatbikata Katılan 8. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_8" name="duty_of_exercise_participant_8" data-options="label:'Tatbikata Katılan 8. Kişi Görevi:'"      label-p="Tatbikata Katılan 8. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_9" name="name_of_exercise_participant_9" data-options="label:'Tatbikata Katılan 9. Kişi Adı:'"         label-p="Tatbikata Katılan 9. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_9" name="duty_of_exercise_participant_9" data-options="label:'Tatbikata Katılan 9. Kişi Görevi:'"      label-p="Tatbikata Katılan 9. Kişi Görevi:"  labelWidth=250   style="width:100%">

                    <input class="easyui-textbox" id="name_of_exercise_participant_10" name="name_of_exercise_participant_10" data-options="label:'Tatbikata Katılan 10. Kişi Adı:'"       label-p="Tatbikata Katılan 10. Kişi Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox" id="duty_of_exercise_participant_10" name="duty_of_exercise_participant_10" data-options="label:'Tatbikata Katılan 10. Kişi Görevi:'"    label-p="Tatbikata Katılan 10. Kişi Görevi:"  labelWidth=250   style="width:100%">

                </div>
            </div>





        </form>
    </div>
</div>