

<div class="easyui-layout" data-options="fit:true">

        <div data-options="region:'north',split:true" style="height:5%">

            <div  id="w" class="w easyui-panel" > </div>
            <script>
                $('.w').panel({
                    onClose: function () {
                        $(this).find('FORM').form('clear');
                        $('.w').html("");
                    },
                    cache: true,
                    modal: true,
                    fit: true,
                    closed: true,
                    iconCls: 'icon-save',
                    title: 'HBYS',

                    toolbar: [{
                        id: 'save-tool',
                        text: 'Kaydet',
                        iconCls: 'icon-save',
                        handler: function () {
                            //alert('ok');

                        }
                    },
                        {
                            id: 'update-tool',
                            text: 'Güncelle',
                            iconCls: 'icon-edit',
                            handler: function () {
                                //alert('ok');
                            }
                        },
                        {
                            id: 'delete-tool',
                            text: 'Seçilen Formu Sil',
                            iconCls: 'icon-cancel',
                            handler: function () {
                                //alert('ok');
                            }
                        },
                        {
                            id: 'print-tool',
                            text: 'Yazdır',
                            iconCls: 'icon-print',
                            handler: function () {
                                //alert('ok');
                            }
                        },
                        {
                            id: 'pdf-tool',
                            text: 'PDF',
                            iconCls: 'icon-add',
                            handler: function () {
                                //alert('ok');
                            }
                        }
                    ]
                });
            </script>



        </div>


        <div data-options="region:'south',split:true" style="height:5%;"></div>

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

                    <li data-options="state:'closed'">
                        <span>Acil Durum Ve Afet Yönetimi</span>
                        <ul>

                            <li>
                                <div onclick="rapor_form_getir('genel-formlar/formlar/kirmizi-kod-olay-bildirim-formu.php' , 'kirmizi-kod-olay-bildirim-formu' , 'Kirmizi Kod Olay Bildirim Formu' , 'red_code_event_notification_form' , 'ajax/formlar-raporlar/genel-formlar/formlar-print/print-basic.php')">AD.FR.04 KIRMIZI KOD OLAY BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>
                                <div onclick="rapor_form_getir('genel-formlar/formlar/kirmizi-kod-tatbikat-bildirim-formu.php' , 'kirmizi-kod-tatbikat-bildirim-formu' , 'Kırmızı Kod Tatbikat Bildirim Formu' , 'red_code_pratice_notification_form' , 'ajax/formlar-raporlar/genel-formlar/formlar-print/print-basic.php')">AD.FR.05 KIRMIZI KOD TATBİKAT BİLDİRİM FORMU
                                </div>
                            </li>

                            <li>
                                <div onclick="rapor_form_getir('genel-formlar/formlar/mavi-kod-tatbikat-bildirim-formu.php' , 'mavi-kod-tatbikat-bildirim-formu' , 'Mavi Kod Tatbikat Bildirim Formu' , 'blue_code_exercise_notification_form' , 'ajax/formlar-raporlar/genel-formlar/formlar-print/print-basic.php')">AD.FR.06 MAVİ KOD TATBİKAT BİLDİRİM FORMU
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
                url: "ajax/formlar-raporlar/" + url + "",
                data: {
                    name: referance_table_name,
                    protocol_no: protocol_no,
                    patient_name: patient_name,
                    patient_tc: patient_tc,
                    department: department,
                    doctor: doctor,
                    menu_id: menu_id,
                    patient_id: patient_id,
                    print_url: print_url,
                    form_adi: adi
                },
                dataType: 'html',
                type: 'GET',
                success: function (e) {
                    var tabs_form = $('#rapor-form').tabs('add', {
                        iconCls: "fa-solid fa-file-pen " + tab_class,
                        title: adi,
                        content:'<div class="sayfa-icerik" style="height:100%; width: 80%; ">'+e+'</div>',
                        closable: true,
                    });
                }
            });
        }else{
            $("." + tab_class).trigger("click");
        }

    }

</script>
