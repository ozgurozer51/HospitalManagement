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

            <div class="easyui-panel" title="MUAYENEYE ESAS OLAYLA İLGİLİ BİLGİLER" style="width:100%;max-width:33.33%;">
                <input class="easyui-textbox"     id="story_of_the_event"     name="story_of_the_event"    labelWidth=150 data-options="label:'Olayın Öyküsü:'" label-p="Olayın Öyküsü:" style="width:100%">
                <input class="easyui-textbox"     id="complaint_of_examinee"  name="complaint_of_examinee" labelWidth=150 data-options="label:'Muayene Edilenin Şikayetleri:'" label-p="Muayene Edilenin Şikayetleri:" style="width:100%">
                <input class="easyui-datetimebox" id="inspection_datetime"    name="inspection_datetime"   data-options="showSeconds:false , label:'Muayene Tarihi:'" label-p="Muayene Tarihi:" labelWidth=150 style="width:100%">
            </div>

        </div>

        <div class="easyui-panel ms-2" title="OLAYLA BAĞLANTILI BİLGİ ve BULGULAR" style="width:100%; max-width:100%;">

            <div class="ms-2">

                <div class="input-align">
                    <label class="anal_vaginal_penetration" style="width:500px;">Saldırı sırasında anal/vajinal penetrasyon: </label>
                    <input class="easyui-radiobutton" id="anal_vaginal_penetration" name="anal_vaginal_penetration" value="1" labelWidth=40 label-p="Var" label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="anal_vaginal_penetration" name="anal_vaginal_penetration" value="2" labelWidth=40 label-p="Yok"  label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="anal_vaginal_penetration" name="anal_vaginal_penetration" value="3" labelWidth=175 label-p="Cevap Vermiyor / Bilmiyor" label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="pre_event_alcohol_or_drug_use" style="width:500px;">Olay öncesi alkol / ilaç kullanımı : </label>
                    <input class="easyui-radiobutton" id="pre_event_alcohol_or_drug_use" name="pre_event_alcohol_or_drug_use" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="pre_event_alcohol_or_drug_use" name="pre_event_alcohol_or_drug_use" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="pre_event_alcohol_or_drug_use" name="pre_event_alcohol_or_drug_use" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="ejaculation_of_attacker" style="width:500px;">Saldırı sırasında saldırganın ejakülasyonu : </label>
                    <input class="easyui-radiobutton" id="ejaculation_of_attacker" name="ejaculation_of_attacker" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="ejaculation_of_attacker" name="ejaculation_of_attacker" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="ejaculation_of_attacker" name="ejaculation_of_attacker" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="use_of_lubricants_during_the_attack" style="width:500px;">Saldırı sırasında kaydırıcı madde kullanımı: </label>
                    <input class="easyui-radiobutton" id="use_of_lubricants_during_the_attack" name="use_of_lubricants_during_the_attack" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="use_of_lubricants_during_the_attack" name="use_of_lubricants_during_the_attack" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="use_of_lubricants_during_the_attack" name="use_of_lubricants_during_the_attack" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="condom_use_during_assault" style="width:500px;">Saldırı sırasında kondom kullanımı: </label>
                    <input class="easyui-radiobutton" id="condom_use_during_assault" name="condom_use_during_assault" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="condom_use_during_assault" name="condom_use_during_assault" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="condom_use_during_assault" name="condom_use_during_assault" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="use_of_physical_verbal_threats_during_attack" style="width:500px;">Saldırı sırasında fiziksel/sözel şiddet/tehdit kullanımı: </label>
                    <input class="easyui-radiobutton" id="use_of_physical_verbal_threats_during_attack" name="use_of_physical_verbal_threats_during_attack" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="use_of_physical_verbal_threats_during_attack" name="use_of_physical_verbal_threats_during_attack" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="use_of_physical_verbal_threats_during_attack" name="use_of_physical_verbal_threats_during_attack" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="use_of_weapon_or_similar_device_during_attack" style="width:500px;">Saldırı sırasında silah ve/veya benzeri alet kullanımı: </label>
                    <input class="easyui-radiobutton" id="use_of_weapon_or_similar_device_during_attack" name="use_of_weapon_or_similar_device_during_attack" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="use_of_weapon_or_similar_device_during_attack" name="use_of_weapon_or_similar_device_during_attack" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="use_of_weapon_or_similar_device_during_attack" name="use_of_weapon_or_similar_device_during_attack" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="previous_similar_attack_history" style="width:500px;">Daha önceden benzer saldırı öyküsü: </label>
                    <input class="easyui-radiobutton" id="previous_similar_attack_history" name="previous_similar_attack_history" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="previous_similar_attack_history" name="previous_similar_attack_history" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="previous_similar_attack_history" name="previous_similar_attack_history" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="vaginal_lavage" style="width:500px;">Olay sonrası yıkanma / vajinal lavaj: </label>
                    <input class="easyui-radiobutton" id="vaginal_lavage" name="vaginal_lavage" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="vaginal_lavage" name="vaginal_lavage" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="vaginal_lavage" name="vaginal_lavage" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="vaginal_bleeding" style="width:500px;">Saldırıya bağlı himen perforasyonu / vajinal kanama: </label>
                    <input class="easyui-radiobutton" id="vaginal_bleeding" name="vaginal_bleeding" value="1" label-p="Var" labelWidth=40 label="Var">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="vaginal_bleeding" name="vaginal_bleeding" value="2" label-p="Yok" labelWidth=40 label="Yok">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="vaginal_bleeding" name="vaginal_bleeding" value="3" label-p="Cevap Vermiyor / Bilmiyor" labelWidth=175 label="Cevap Vermiyor / Bilmiyor">
                </div>

                <div class="input-align">
                    <label class="was_condom_used_relationship" style="width:500px;">Bu ilişkide kondom kullanıldı mı ?</label>
                    <input class="easyui-radiobutton" id="was_condom_used_relationship" name="was_condom_used_relationship" value="true"  label-p="Evet" labelWidth=40 label="Evet">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="was_condom_used_relationship" name="was_condom_used_relationship" value="false" label-p="Hayır" labelWidth=40 label="Hayır">
                </div>

                <div class="input-align">
                    <label class="does_she_use_birth_control" style="width:500px;">Herhangi bir doğum kontrol yöntemi kullanıyor mu ?: </label>
                    <input class="easyui-radiobutton" id="does_she_use_birth_control" name="does_she_use_birth_control" value="true"  label-p="Evet" labelWidth=40 label="Evet">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="does_she_use_birth_control" name="does_she_use_birth_control" value="false" label-p="Hayır" labelWidth=40 label="Hayır">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-textbox" id="does_she_use_birth_control_explain" name="does_she_use_birth_control_explain"   label-p="Açıklama" label="Açıklama" style="width: 200px;" >
                </div>

                <div class="input-align">
                    <label class="pregnancy_history" style="width:500px;">Geçirilmiş veya halen mevcut hamilelik öyküsü ?: </label>
                    <input class="easyui-radiobutton" id="pregnancy_history" name="pregnancy_history" value="true"  label-p="Evet" labelWidth=40 label="Evet">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="pregnancy_history" name="pregnancy_history" value="false" label-p="Hayır" labelWidth=40 label="Hayır">
                </div>

                <div class="input-align">
                    <label class="history_of_venereal_disease" style="width:500px;">Geçirilmiş veya halen mevcut veneryal hastalık öyküsü ? : </label>
                    <input class="easyui-radiobutton" id="history_of_venereal_disease" name="history_of_venereal_disease" value="true"  label-p="Evet"  labelWidth=40 label="Evet">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="history_of_venereal_disease" name="history_of_venereal_disease" value="false" label-p="Hayır" labelWidth=40 label="Hayır">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-textbox"     id="history_of_venereal_disease_explain" name="history_of_venereal_disease_explain"       label-p="Açıklama" label="Açıklama" style="width: 200px;">
                </div>

                <div class="input-align">
                    <label class="history_of_emotional_illness" style="width:500px;">Geçirilmiş veya halen mevcut emosyonel hastalık öyküsü ?: </label>
                    <input class="easyui-radiobutton" id="history_of_emotional_illness" name="history_of_emotional_illness" value="true"    label-p="Evet" labelWidth=40 label="Evet">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="history_of_emotional_illness" name="history_of_emotional_illness" value="false"   label-p="Hayır" labelWidth=40 label="Hayır">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-textbox"     id="history_of_emotional_illness_explain" name="history_of_emotional_illness_explain" label-p="Açıklama" label="Açıklama" style="width: 200px;">
                </div>

                <div class="input-align">
                    <label class="the_examine_clothes" style="width:500px;">Muayene edilenin giysileri ?: </label>
                    <input class="easyui-radiobutton" id="the_examine_clothes" value="1" name="the_examine_clothes"  label-p="İncelendi" labelWidth=80 label="İncelendi">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="the_examine_clothes" value="2" name="the_examine_clothes"  label-p="İncelenmedi" labelWidth=100 label="İncelenmedi">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="the_examine_clothes"  value="3" name="the_examine_clothes" label-p="Muhafaza Altına Alındı/Aldırıldı" labelWidth=200 label="Muhafaza Altına Alındı/Aldırıldı">
                </div>

                <div class="input-align">
                    <input class="easyui-datebox" id="last_of_sexual_intercourse_datetime" label="En son cinsel ilişki tarihi :" label-p="En son cinsel ilişki tarihi :" labelWidth=500 name="last_of_sexual_intercourse_datetime" style="width:700px">
                </div>

                <div class="input-align">
                    <input class="easyui-datetimebox" id="first_menstruation_datetime" name="first_menstruation_datetime" label="İlk menstrüasyon tarihi:" label-p="İlk menstrüasyon tarihi:" labelWidth=500 data-options="showSeconds:false" style="width:700px">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-datetimebox" id="last_menstruation_datetime" name="last_menstruation_datetime"   label="Son menstrüasyon tarihi:" label-p="Son menstrüasyon tarihi:"  labelWidth=200 data-options="showSeconds:false" style="width:400px">
                </div>

            </div>

        </div>

        </form>
    </div>
</div>