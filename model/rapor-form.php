

<div class="easyui-layout" data-options="fit:true">

<!--    <div data-options="region:'north',split:true" style="height:5%"></div>-->
<!---->
<!--    <div data-options="region:'south',split:true" style="height:5%;"></div>-->

    <div data-options="region:'west',split:true" title="Menu" style="width:20%;">

        <input id="ss" class="easyui-searchbox" style="width:100%;" data-options="searcher:qq,prompt:'Ara'">

        <script>
            function qq(aaa) {
            alert(aaa);
            }
        </script>

        <ul class="easyui-tree"  data-options="animate:true,lines:true">



            <li>

                <span>Formlar</span>

                <ul>

                    <li id="Cinsel Olaylar" class="ilac-rapor-btn">
                        <div onclick="rapor_form_getir('formlar/yenidogan-muayene-ve-degerlendirme-formu.php' , 'yenidogan-muayene-ve-degerlendirme-formu' , 'Yeni Doğan Muayene Ve Değerlendirme Formu' , 'newborn_evaluation_and_examination_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')" class="ilac-rapor-btn">Yeni Doğan Muayene Ve Değerlendirme Formu
                        </div>
                    </li>


                    <li data-options="state:'closed'">
                        <span>Acil Servis</span>
                        <ul>
                            <li id="Cinsel Olaylar" class="ilac-rapor-btn">
                                <div onclick="rapor_form_getir('formlar/acil_servis_hasta_esyalari_teslim_ve_iade_formu.php' , 'acil-servis-hasta-esyalari-teslim-ve-emanet-formu' , 'Acil Servis Hasta Eşyaları Teslim Ve Emanet Formu' , 'emergency_room_patient_goods_delivery_and_return_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')" class="ilac-rapor-btn">Acil Servis Hasta Eşyaları Teslim Ve Emanet Formu</div>
                            </li>
                        </ul>
                    </li>

                    <li data-options="state:'closed'">
                        <span>Cinsel Olaylar</span>
                        <ul>
                            <li id="Cinsel Olaylar" class="ilac-rapor-btn">
                                <div onclick="rapor_form_getir('formlar/cinsel-saldiri-muayene-form.php' , 'cinsel-saldiri-muayene-form' , 'Cinsel saldırı Muayene Formu' , 'carnal_assault_examination_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')" class="ilac-rapor-btn">Cinsel Saldırı Muayene Formu</div>
                            </li>
                        </ul>
                    </li>

                    <li data-options="state:'closed'">
                        <span>Acil Durum Ve Afet Yönetimi</span>
                        <ul>

                            <li>
                                <div onclick="rapor_form_getir('formlar/beyaz-kod-olay-bildirim-formu.php' , 'beyaz-kod-olay-bildirim-formu' , 'Beyaz Kod Olay Bildirim Formu' , 'white_code_event_notification_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')">AD.FR.01-BEYAZ KOD OLAY BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>
                                <div onclick="rapor_form_getir('formlar/mavi-kod-olay-bildirim-formu.php' , 'mavi-kod-olay-bildirim-formu' , 'Mavi Kod Olay Bildirim Formu' , 'blue_code_event_notification_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')">AD.FR.02-MAVİ KOD OLAY BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>
                                <div onclick="rapor_form_getir('formlar/pembe-kod-olay-bildirim-formu.php' , 'pembe-kod-olay-bildirim-formu' , 'Pembe Kod Olay Bildirim Formu' , 'pink_code_event_notification_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')">AD.FR.03-PEMBE KOD OLAY BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>
                                <div onclick="rapor_form_getir('formlar/kirmizi-kod-olay-bildirim-formu.php' , 'kirmizi-kod-olay-bildirim-formu' , 'Kirmizi Kod Olay Bildirim Formu' , 'red_code_event_notification_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')">AD.FR.04 KIRMIZI KOD OLAY BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>
                                <div onclick="rapor_form_getir('formlar/kirmizi-kod-tatbikat-bildirim-formu.php' , 'kirmizi-kod-tatbikat-bildirim-formu' , 'Kırmızı Kod Tatbikat Bildirim Formu' , 'red_code_pratice_notification_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')">AD.FR.05 KIRMIZI KOD TATBİKAT BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>
                                <div onclick="rapor_form_getir('formlar/mavi-kod-tatbikat-bildirim-formu.php' , 'mavi-kod-tatbikat-bildirim-formu' , 'Mavi Kod Tatbikat Bildirim Formu' , 'blue_code_exercise_notification_form' , 'ajax/formlar-raporlar/hastaya-eklenen-formlar/formlar-print/print-basic.php')">AD.FR.06 MAVİ KOD TATBİKAT BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>AD.FR.07 PEMBE KOD TATBİKAT BİLDİRİM FORMU</li>
                            <li>AD.FR.08 BEYAZ KOD TATBİKAT FORMU</li>
                            <li>AD.FR.09 ÇATI TEMİZLİĞİ TAKİP FORMU</li>
                            <li>AD.FR.10 YANGIN SÖNDÜRME EKİPMANLARI TAKİP FORMU</li>
                        </ul>
                    </li>

                </ul>
            </li>
        </ul>

    </div>

    <div data-options="region:'center', split:true , fit:true" style="width:80%;">

        <div id="rapor-form" class="easyui-tabs" style="width:80%;" data-options="tools:'#tab-tools',fit:true">

        </div>

    </div>

</div>

<script>

    function rapor_form_getir(url , id_2 , adi , table_name , print_url){
        var protocol_no =  "<?php echo $_GET['protocol_no']; ?>";
        var patient_name = "<?php echo $_GET['patient_name']; ?>";
        var patient_tc =   "<?php echo $_GET['patient_tc']; ?>";
        var department =   "<?php echo $_GET['department']; ?>";
        var doctor =       "<?php echo $_GET['doctor']; ?>";
        var menu_id =      "<?php echo $_GET['menu_id']; ?>";
        var patient_id =   "<?php echo $_GET['patient_id']; ?>";
        var referance_table_name = table_name;

        var tab_class = id_2;
        var tabs_count = $('.' + tab_class).length;
        if(tabs_count==0) {
            $.ajax({
                url: "ajax/formlar-raporlar/hastaya-eklenen-formlar/" + url + "",
                data: { name:referance_table_name , protocol_no:protocol_no , patient_name:patient_name , patient_tc:patient_tc , department:department , doctor:doctor , menu_id:menu_id , patient_id:patient_id , print_url:print_url , form_adi:adi},
                dataType: 'html',
                type: 'GET',
                success: function (e) {
                    var tabs_form = $('#rapor-form').tabs('add', {
                        iconCls: "fa-solid fa-file-pen " + tab_class,
                        title: adi,
                        content:'<div class="sayfa-icerik" style="height:100%; width: 80%;">'+e+'</div>',
                        closable: true,
                    });
                }
            });
        }else{
            $("." + tab_class).trigger("click");
        }

    }

</script>
