<div class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">

    <div data-options="region:'east', collapsed:true , split:true , hideCollapsedContent:false" title="Hastaya Eklenmiş Form Ve Raporlar" style="width:25%;">
        <?php include "../tablo-template.php"; ?>
    </div>

    <div data-options="region:'center',title:''">
        <div id="ff-<?php echo $uniqid; ?>" uniquid="<?php echo $uniqid; ?>" print-url="<?php echo $_GET['print_url']; ?>" table-name="<?php echo $_GET['name']; ?>" form-adi="<?php echo $_GET['form_adi']; ?>">

            <input class="form-table-name" type="hidden" value-2="<?php echo $_GET['name']; ?>">

            <div class="input-align">

                <div class="easyui-panel" id="rapor-form-hasta-bilgi" title="Müdahale Edilen Hastanın" style="width:50%;max-width:50%;">
                    <?php include "../hasta-bilgi-template.php"; ?>
                </div>

                <div class="easyui-panel" title="Ek Bilgiler" style="width:50%;max-width:50%;">
                    <div class="ms-2">
                        <input class="easyui-textbox"   id="nursing_services_manager_name"     name="nursing_services_manager_name"          data-options="label:'Hazırlayan Hemşire Hizmetleri Müdürü:'"    label-p="Hazırlayan Hemşire Hizmetleri Müdürü:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"   id="quality_management_director_name"  name="quality_management_director_name"                data-options="label:'Hazırlayan Kalite Yönetim Direktörü:'"    label-p="Hazırlayan Kalite Yönetim Direktörü:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"   id="surgeon_general_name"              name="surgeon_general_name" data-options="label:'Başhekim:'"    label-p="Başhekim:"  labelWidth=250   style="width:100%">
                    </div>
                </div>

            </div>

            <div class="input-align">

            <div class="easyui-panel" title="Yenidoğan Bilgileri" style="width:50%;max-width:50%;">
                <div class="ms-2">
                    <input class="easyui-textbox"   id="baby_name"          name="baby_name"          data-options="label:'Bebek Adı:'"    label-p="Bebek Adı:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox"   id="baby_protocol_no"   name="baby_protocol_no"                data-options="label:'Protokol No:'"    label-p="Protokol No:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox"   id="baby_gender"        name="baby_gender" data-options="label:'Cinsiyeti:'"    label-p="Cinsiyeti:"  labelWidth=250   style="width:100%">
                    <input class="easyui-datetimebox"   id="baby_born_datetime" name="baby_born_datetime" data-options="label:'Cinsiyeti:'"    label-p="Cinsiyeti:"  labelWidth=250   style="width:100%">
                </div>


                <div class="easyui-panel" title="Baba Bilgileri" style="width:100%;max-width:100%;">
                    <div class="ms-2">

                        <input class="easyui-textbox"   id="father_name_surname"    name="father_name_surname"          data-options="label:'Adı Soyadı:'"    label-p="Adı Soyadı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-numberbox" id="father_age"    name="father_age" data-options="label:'Yaş:'"    label-p="Yaş:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"   id="father_job"    name="father_job" data-options="label:'Mesleği:'"    label-p="Mesleği:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"   id="father_chronic_disease"    name="father_chronic_disease" data-options="label:'Kronik Hastalığı:'"    label-p="Kronik Hastalığı:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"   id="father_drug_used"    name="father_drug_used" data-options="label:'Kullanılan İlaç:'"    label-p="Kullanılan İlaç:"  labelWidth=250   style="width:100%">
                        <input class="easyui-textbox"   id="father_hereditary_disease_in_family"    name="father_hereditary_disease_in_family" data-options="label:'Ailede Kalıtsal Hastalık:'"    label-p="Ailede Kalıtsal Hastalık:"  labelWidth=250   style="width:100%">

                    </div>
                </div>

            </div>



            <div class="easyui-panel" title="Anne Bilgileri" style="width:50%;max-width:50%;">
                  <div class="ms-2">
                      <input class="easyui-textbox"   id="mother_name_surname"    name="mother_name_surname"          data-options="label:'Adı Soyadı:'"    label-p="Adı Soyadı:"  labelWidth=250   style="width:100%">
                      <input class="easyui-numberbox" id="mother_age"    name="mother_age" data-options="label:'Yaş:'"    label-p="Yaş:"  labelWidth=250   style="width:100%">
                      <input class="easyui-textbox"   id="mother_blood_group"    name="mother_blood_group" data-options="label:'Kan Grubu:'"    label-p="Kan Grubu:"  labelWidth=250   style="width:100%">
                      <input class="easyui-textbox"   id="mother_job"    name="mother_job" data-options="label:'Mesleği:'"    label-p="Mesleği:"  labelWidth=250   style="width:100%">
                      <input class="easyui-textbox"   id="chronic_disease"    name="chronic_disease" data-options="label:'Kronik Hastalığı:'"    label-p="Kronik Hastalığı:"  labelWidth=250   style="width:100%">
                      <input class="easyui-textbox"   id="drug_used"    name="drug_used" data-options="label:'Kullanılan İlaç:'"    label-p="Kullanılan İlaç:"  labelWidth=250   style="width:100%">
                      <input class="easyui-textbox"   id="illness_in_newborn_period_in_siblings"    name="illness_in_newborn_period_in_siblings" data-options="label:'Ailede Kalıtsal Hastalık:'"    label-p="Ailede Kalıtsal Hastalık:"  labelWidth=250   style="width:100%">
                      <input class="easyui-textbox"   id="consanguineous_marriage" name="consanguineous_marriage" data-options="label:'Akraba Evliliği:'"    label-p="Akraba Evliliği:"  labelWidth=250   style="width:100%">
                      <input class="easyui-datebox"   id="last_menstruation_date"  name="last_menstruation_date" data-options="label:'Son Adet Tarihi:'"    label-p="Son Adet Tarihi:"  labelWidth=250   style="width:100%">
                      <input class="easyui-numberbox" id="number_of_pregnancy"  name="number_of_pregnancy" data-options="label:'Gebelik Sayısı:'"    label-p="Gebelik Sayısı:"  labelWidth=250   style="width:100%">
                      <input class="easyui-numberbox" id="low_curettage_count"  name="low_curettage_count" data-options="label:'Düşük Ve Küretaj Sayısı:'"    label-p="Düşük Ve Küretaj Sayısı:"  labelWidth=250   style="width:100%">
                  </div>
            </div>

            </div>

            <div class="input-align">

            <div class="easyui-panel" title="Diğer" style="width:50%;max-width:50%;">
                <div class="ms-2">


                    <div style="display: flex">
                    <label style="width: 250px;">Anne Baba Birlikte mi</label>
                    <input class="easyui-radiobutton" id=mom_and_dad_together?"" name="mom_and_dad_together?" value="true"  label-p="Evet"  labelWidth=50 label="Evet">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="mom_and_dad_together?" name="mom_and_dad_together?" value="false" label-p="Hayır" labelWidth=50 label="Hayır">
                    </div>

                    <div style="display: flex">
                    <label style="width: 250px;">İletişim İçin Tercüman Gereksinimi</label>
                    <input class="easyui-radiobutton" id="interpreter_requirement_for_communication" name="interpreter_requirement_for_communication" value="true"  label-p="Var"  labelWidth=50 label="Var * (*Hasta Hakları ve Uluslar Arası Hasta Birimi’ni arayınız)">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="interpreter_requirement_for_communication" name="interpreter_requirement_for_communication" value="false" label-p="Yok" labelWidth=50 label="Yok">
                    </div>

                    <input class="easyui-checkbox" id="patient_rights_and_responsibilities_explained" name="patient_rights_and_responsibilities_explained" value="true" label-p="Hasta Hakları Ve Sorumlulukları Anlatıldı:" labelWidth=350 label="Hasta Hakları Ve Sorumlulukları Anlatıldı:">
                    <br>
                    <input class="easyui-numberbox"   id="mother_mobile_number" name="mother_mobile_number" data-options="label:'Anne Telefon No:'"    label-p="Anne Telefon No:"  labelWidth=250   style="width:100%">
                    <input class="easyui-numberbox"   id="father_mobile_number" name="father_mobile_number" data-options="label:'Baba Telefon No:'"    label-p="Baba Telefon No:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox"     id="mother_address" name="mother_address" data-options="label:'Anne Adresi:'"    label-p="Anne Adresi:"  labelWidth=250   style="width:100%">
                    <input class="easyui-textbox"     id="father_address" name="father_address" data-options="label:'Baba Adresi:'"    label-p="Baba Adresi:"  labelWidth=250   style="width:100%">

                    <div style="display: flex">
                        <label style="width: 250px;">Başvurulan Bölüm:</label>
                        <input class="easyui-radiobutton" id="referenced_section" name="referenced_section" value="Poliklinik"  label-p="Poliklinik:"  labelWidth=100 label="Poliklinik:">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="easyui-radiobutton" id="referenced_section" name="referenced_section" value="Acil Servis" label-p="Acil Servis:" labelWidth=100 label="Acil Servis:">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="easyui-radiobutton" id="referenced_section" name="referenced_section" value="Doğumhane/Ameliyathane" label-p="Doğumhane/Ameliyathane:" labelWidth=100 label="Doğumhane/Ameliyathane:">
                    </div>


                    <input class="easyui-radiobutton" id="referenced_section" name="referenced_section" value="Kurum Dışı Doktor / Ev" label-p="Kurum Dışı Doktor/Ev:" labelWidth=100 label="Kurum Dışı Doktor/Ev:">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="referenced_section" name="referenced_section" value="Başka Sağlık Kuruluşu" label-p="Başka Sağlık Kuruluşu:" labelWidth=100 label="Başka Sağlık Kuruluşu:">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-textbox" id="referenced_section" name="referenced_section"  label-p="Başka Sağlık Kuruluşu:" labelWidth=150 style="width: 270px;" label="Başka Sağlık Kuruluşu:">



                </div>
            </div>

                <div class="easyui-panel" title="Alışkanlıklar" style="width:50%;max-width:50%;">
                    <div class="ms-2">

                        <label style="width: 250px;">Anne Sigara Kullanımı:</label>
                        <input class="easyui-radiobutton" id="does_mother_smoke" name="does_mother_smoke" value="true"  label-p="Evet:"  labelWidth=45 label="Evet:">
                        <input class="easyui-radiobutton" id="does_mother_smoke" name="does_mother_smoke" value="false"  label-p="Hayır:"  labelWidth=45 label="Hayır:">
                        <input class="easyui-checkbox" id="does_mother_smoke" name="does_mother_smoke" value="true"  label-p="Bırakmış:"  labelWidth=75 label="Bırakmış:">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="easyui-textbox" id="mother_substance_habit" name="mother_substance_habit"  label-p="Anne Ne Kadar Sigara Kullanır:" labelWidth=250 style="width: 100%;" label="Anne Ne Kadar Sigara Kullanır:">

                        <label style="width: 250px;">Baba Sigara Kullanımı:</label>
                        <input class="easyui-radiobutton" id="does_father_smoke" name="does_father_smoke" value="true"  label-p="Evet:"  labelWidth=45 label="Evet:">
                        <input class="easyui-radiobutton" id="does_father_smoke" name="does_father_smoke" value="false"  label-p="Hayır:"  labelWidth=45 label="Hayır:">
                        <input class="easyui-checkbox" id="does_father_smoke" name="does_father_smoke" value="true"  label-p="Bırakmış:"  labelWidth=75 label="Bırakmış:">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="easyui-textbox" id="father_substance_habit" name="father_substance_habit"  label-p="Baba Ne Kadar Sigara Kullanır:" labelWidth=250 style="width: 100%;" label="Baba Ne Kadar Sigara Kullanır:">

                    </div>
                </div>


            </div>

            <div class="input-align">

            <div class="easyui-panel" title="Hamileye Ait Bilgiler" style="width:50%;max-width:50%;">
                <div class="ms-2">

                    <label style="width: 200px;">Gebelik Şekli:</label>
                    <input class="easyui-radiobutton" id="pregnancy_type" name="pregnancy_type" value="spontan"  label-p="Spontan:"  labelWidth=95 label="Spontan:">
                    <input class="easyui-radiobutton" id="pregnancy_type" name="pregnancy_type" value="Çoğul Gebelik"  label-p="Çoğul Gebelik:"  labelWidth=95 label="Çoğul Gebelik:">
                    <label style="width: 200px;">Gebelik Şekli:</label>

                    <input class="easyui-radiobutton" id="pregnancy_type" name="pregnancy_type" value="İnseminasyon"  label-p="İnseminasyon:"  labelWidth=95 label="İnseminasyon:">
                    <input class="easyui-radiobutton" id="pregnancy_type" name="pregnancy_type" value="mikroenjelsiyon(ICSI)"  label-p="Mikroenjelsiyon(ICSI):"  labelWidth=95 label="Mikroenjelsiyon(ICSI):">
                    <input class="easyui-radiobutton" id="pregnancy_type" name="pregnancy_type" value="Redüksiyon"  label-p="Redüksiyon:"  labelWidth=95 label="Redüksiyon:">
                    <br>
                    <input class="easyui-textbox"     id="pregnancy_type" name="pregnancy_type" label-p="Diğer:"  labelWidth=200 label="Diğer:" style="width: 100%;">

                   <label style="width: 200px;">Serolojik Testler...</label>
                    <br>

                    <label style="width: 200px;">HIV</label>
                    <input class="easyui-radiobutton" id="hiv" name="hiv" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="hiv" name="hiv" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <label style="width: 200px;">Sifilis</label>
                    <input class="easyui-radiobutton" id="sifilis" name="sifilis" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="sifilis" name="sifilis" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <label style="width: 200px;">Hepatit A</label>
                    <input class="easyui-radiobutton" id="hepatit_a" name="hepatit_a" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="hepatit_a" name="hepatit_a" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <label style="width: 200px;">Hepatit B</label>
                    <input class="easyui-radiobutton" id="hepatit_b" name="hepatit_b" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="hepatit_b" name="hepatit_b" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">
                    <br>
                    <input class="easyui-textbox"     id="other_serological_test" name="other_serological_test" label-p="Diğer:"  labelWidth=200 label="Diğer:" style="width: 100%;">

                    <label>Gebelikteki Problemler...</label>
                    <br>
                    <label style="width: 200px;">Preeklemsi</label>
                    <input class="easyui-radiobutton" id="preeclampsia" name="preeclampsia" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="preeclampsia" name="preeclampsia" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <label style="width: 200px;">Eklemsi</label>
                    <input class="easyui-radiobutton" id="articulate" name="articulate" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="articulate" name="articulate" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <label style="width: 200px;">Diyabet</label>
                    <input class="easyui-radiobutton" id="diabetes" name="diabetes" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="diabetes" name="diabetes" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <label style="width: 200px;">Enfeksiyon</label>
                    <input class="easyui-radiobutton" id="infection" name="infection" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="infection" name="infection" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <label style="width: 200px;">Amniosentez</label>
                    <input class="easyui-radiobutton" id="amniosentez" name="amniosentez" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton" id="amniosentez" name="amniosentez" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">


                </div>
            </div>

            <div class="easyui-panel easyui-tooltip" title="Doğuma Ait Bilgiler" style="width:50%;max-width:50%;">
                <div class="ms-2">

                    <i class="fas fa-circle-info easyui-tooltip" title="Mor veya Soluk:0 Puan , Gövde Pembe veya ekstemiteler mor 1 Puan , Tamamen Pembe 2 Puan..."></i>
                    <br>
                    <input class="easyui-numberbox"  id="appearance_1_min pp1" name="appearance_1_min" label-p="Bulgu Cilt Rengi(Appearance) 1. dk:" labelWidth=300 label="Bulgu Cilt Rengi(Appearance) 1. dk:" title="Mor veya Soluk:0 Puan , Gövde Pembe veya ekstemiteler mor 1 Puan , Tamamen Pembe 2 Puan" style="width: 100%;">
                    <input class="easyui-numberbox"  id="appearance_5_min" name="appearance_5_min"     label-p="Bulgu Cilt Rengi(Appearance) 5. dk:" labelWidth=300 label="Bulgu Cilt Rengi(Appearance) 5. dk:" title="Mor veya Soluk:0 Puan , Gövde Pembe veya ekstemiteler mor 1 Puan , Tamamen Pembe 2 Puan" style="width: 100%;">
                    <input class="easyui-numberbox"  id="appearance_10_min" name="appearance_10_min"   label-p="Bulgu Cilt Rengi(Appearance) 10. dk:" labelWidth=300 label="Bulgu Cilt Rengi(Appearance) 10. dk:" title="Mor veya Soluk:0 Puan , Gövde Pembe veya ekstemiteler mor 1 Puan , Tamamen Pembe 2 Puan" style="width: 100%;">
                    <input class="easyui-numberbox"  id="appearance_15_min" name="appearance_15_min"   label-p="Bulgu Cilt Rengi(Appearance) 15. dk:" labelWidth=300 label="Bulgu Cilt Rengi(Appearance) 15. dk:" title="Mor veya Soluk:0 Puan , Gövde Pembe veya ekstemiteler mor 1 Puan , Tamamen Pembe 2 Puan" style="width: 100%;">
                    <input class="easyui-numberbox"  id="appearance_20_min" name="appearance_20_min"   label-p="Bulgu Cilt Rengi(Appearance) 20. dk:" labelWidth=300 label="Bulgu Cilt Rengi(Appearance) 20. dk:" title="Mor veya Soluk:0 Puan , Gövde Pembe veya ekstemiteler mor 1 Puan , Tamamen Pembe 2 Puan" style="width: 100%;">

                    <i class="fas fa-circle-info easyui-tooltip" title="Yok: 0 Puan , < 100/dk: 1 Puan , >100/dk 2 Puan..."></i>
                    <br>
                    <input class="easyui-numberbox"  id="pulse_1_min"  name="pulse_1_min"     label-p="Bulgu Kalp Hızı (Pulse) 1. dk:"  labelWidth=300 label="Bulgu Kalp Hızı (Pulse) 1. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="pulse_5_min"  name="pulse_5_min"     label-p="Bulgu Kalp Hızı (Pulse) 5. dk:"  labelWidth=300 label="Bulgu Kalp Hızı (Pulse) 5. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="pulse_10_min" name="pulse_10_min"    label-p="Bulgu Kalp Hızı (Pulse) 10. dk:" labelWidth=300 label="Bulgu Kalp Hızı (Pulse) 10. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="pulse_15_min" name="pulse_15_min"    label-p="Bulgu Kalp Hızı (Pulse) 15. dk:" labelWidth=300 label="Bulgu Kalp Hızı (Pulse) 15. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="pulse_20_min" name="pulse_20_min"    label-p="Bulgu Kalp Hızı (Pulse) 20. dk:" labelWidth=300 label="Bulgu Kalp Hızı (Pulse) 20. dk:" style="width: 100%;">

                    <i class="fas fa-circle-info easyui-tooltip" title="Yok: 0 Puan , Yüz Buruşturma: 1 Puan , Öksürük,Hapşırık,Yüksek Sesli Ağlama: 2 Puan..."></i>
                    <br>
                    <input class="easyui-numberbox"  id="grimace_1_min"  name="grimace_1_min"    label-p="Bulgu Refleks Cevap (Grimace) 1. dk:"  labelWidth=300  label="Bulgu Refleks Cevap (Grimace) 1. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="grimace_5_min"  name="grimace_5_min"    label-p="Bulgu Refleks Cevap (Grimace) 5. dk:"  labelWidth=300  label="Bulgu Refleks Cevap (Grimace) 5. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="grimace_10_min" name="grimace_10_min"   label-p="Bulgu Refleks Cevap (Grimace) 10. dk:" labelWidth=300  label="Bulgu Refleks Cevap (Grimace) 10. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="grimace_15_min" name="grimace_15_min"   label-p="Bulgu Refleks Cevap (Grimace) 15. dk:" labelWidth=300  label="Bulgu Refleks Cevap (Grimace) 15. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="grimace_20_min" name="grimace_20_min"   label-p="Bulgu Refleks Cevap (Grimace) 20. dk:" labelWidth=300  label="Bulgu Refleks Cevap (Grimace) 20. dk:" style="width: 100%;">

                    <i class="fas fa-circle-info easyui-tooltip" title="Genel Hipotoni: 0 Puan , Alt Ekstremitelerde Fleksiyon: 1 Puan , Aktif Hareketli: 2 Puan..."></i>
                    <br>
                    <input class="easyui-numberbox"  id="activity_1_min"  name="activity_1_min"    label-p="Bulgu Kas Tonusu (Activity) 1. dk:"  labelWidth=300  label="Bulgu Kas Tonusu (Activity) 1. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="activity_5_min"  name="activity_5_min"    label-p="Bulgu Kas Tonusu (Activity) 5. dk:"  labelWidth=300  label="Bulgu Kas Tonusu (Activity) 5. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="activity_10_min" name="activity_10_min"   label-p="Bulgu Kas Tonusu (Activity) 10. dk:" labelWidth=300  label="Bulgu Kas Tonusu (Activity) 10. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="activity_15_min" name="activity_15_min"   label-p="Bulgu Kas Tonusu (Activity) 15. dk:" labelWidth=300  label="Bulgu Kas Tonusu (Activity) 15. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="activity_20_min" name="activity_20_min"   label-p="Bulgu Kas Tonusu (Activity) 20. dk:" labelWidth=300  label="Bulgu Kas Tonusu (Activity) 20. dk:" style="width: 100%;">

                    <i class="fas fa-circle-info easyui-tooltip" title="Yok: 0 Puan , Düzensiz,Çene Atma: 1 Puan , Rahat Solunum Güçlü Ağlama: 2 Puan..."></i>
                    <br>
                    <input class="easyui-numberbox"  id="recpiratium_1_min"  name="recpiratium_1_min"    label-p="Bulgu Solunum (Recpiratium) 1. dk:"  labelWidth=300  label="Bulgu Solunum (Recpiratium) 1. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="recpiratium_5_min"  name="recpiratium_5_min"    label-p="Bulgu Solunum (Recpiratium) 5. dk:"  labelWidth=300  label="Bulgu Solunum (Recpiratium) 5. dk:"  style="width: 100%;">
                    <input class="easyui-numberbox"  id="recpiratium_10_min" name="recpiratium_10_min"   label-p="Bulgu Solunum (Recpiratium) 10. dk:" labelWidth=300  label="Bulgu Solunum (Recpiratium) 10. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="recpiratium_15_min" name="recpiratium_15_min"   label-p="Bulgu Solunum (Recpiratium) 15. dk:" labelWidth=300  label="Bulgu Solunum (Recpiratium) 15. dk:" style="width: 100%;">
                    <input class="easyui-numberbox"  id="recpiratium_20_min" name="recpiratium_20_min"   label-p="Bulgu Solunum (Recpiratium) 20. dk:" labelWidth=300  label="Bulgu Solunum (Recpiratium) 20. dk:" style="width: 100%;">


                    <label style="width: 300px;">Müdehale Yapıldı mı?</label>
                    <input class="easyui-radiobutton" id="has_there_been_any_intervention?" name="has_there_been_any_intervention?" value="true"  label-p="Evet:"  labelWidth=50 label="Evet:">
                    <input class="easyui-radiobutton" id="has_there_been_any_intervention?" name="has_there_been_any_intervention?" value="false"  label-p="Hayır:"  labelWidth=50 label="Hayır:">

                    <select class="easyui-combobox" name="state"  labelWidth=50 multiple="true" multiline="true" label="Select States:" labelPosition="top" style="width:100%;height:100px;">
                        <option value="Oksijen">Oksijen</option>
                        <option value="Pozitif Basınçlı Ventilasyon">Pozitif Basınçlı Ventilasyon</option>
                        <option value="Entübasyon">Entübasyon</option>
                        <option value="Göğüs Masajı">Göğüs Masajı</option>
                    </select>

                    <input class="easyui-textbox" id="medication_used_in_intervention"     name="medication_used_in_intervention"     label-p="Müdahalede Kullanılan İlaç:"         labelWidth=300 label="Müdahalede Kullanılan İlaç:"        style="width: 100%;">
                    <input class="easyui-textbox" id="other_actions_taken_in_intervention" name="other_actions_taken_in_intervention" label-p="Müdahalede Yapılan Diğer İşlemler:"  labelWidth=300 label="Müdahalede Yapılan Diğer İşlemler:" style="width: 100%;">


                </div>
            </div>
        </div>



        </form>
    </div>
</div>