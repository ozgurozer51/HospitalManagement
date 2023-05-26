<?php
include "../controller/fonksiyonlar.php";
?>

<script type="text/javascript" src="assets/jquery-easyui-1.9.15/jquery.desktop.js"></script>
<link rel="stylesheet" type="text/css" href="assets/jquery-easyui-1.9.15/jquery.desktop.css">

<script type="text/javascript">
    $(function(){
        $('body').desktop({
            apps: [{
                name: 'Poliklinik',
                icon: 'assets/jquery-easyui-1.9.15/images/pol-clinick.svg',
                fit:true,
                href: 'model/hasta-kabul.php'
            },{
                name: 'Diş Hekimliği',
                icon: 'assets/jquery-easyui-1.9.15/images/dental.svg',
                fit:true,
                href: 'model/tanimlamalar.php'
            },{
                name: 'Yönetim',
                icon: 'assets/jquery-easyui-1.9.15/images/manager.svg',
                fit:true,
                href: 'model/tanimlamalar.php'
            },{
                name: 'Ameliyathane',
                icon: 'assets/jquery-easyui-1.9.15/images/operating-room.svg',
                fit:true,
                href: 'model/hasta-kabul.php'
            },{
                 name: 'Görüntü Arşivleme İletişim',
                 icon: 'assets/jquery-easyui-1.9.15/images/image-archive.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Diyet',
                 icon: 'assets/jquery-easyui-1.9.15/images/diet.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Eczane',
                 icon: 'assets/jquery-easyui-1.9.15/images/pharmacy.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Faturalama',
                 icon: 'assets/jquery-easyui-1.9.15/images/invoice.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Vezne',
                 icon: 'assets/jquery-easyui-1.9.15/images/teller.svg',
                 fit:true,
                 href: 'model/vezne.php'
            },{
                 name: 'Personel',
                 icon: 'assets/jquery-easyui-1.9.15/images/person.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'İstatistik',
                 icon: 'assets/jquery-easyui-1.9.15/images/statistic.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Stok',
                 icon: 'assets/jquery-easyui-1.9.15/images/stock.svg',
                 fit:true,
                 href: 'model/stok.php'
            },{
                 name: 'Demirbaş Ve Varlık',
                 icon: 'assets/jquery-easyui-1.9.15/images/fixture.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Tıbbi Form',
                 icon: 'assets/jquery-easyui-1.9.15/images/computer.png',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'İstek Kabul',
                 icon: 'assets/jquery-easyui-1.9.15/images/request accepted.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Rayoloji',
                 icon: 'assets/jquery-easyui-1.9.15/images/radiologiy.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Laboratuvar',
                 icon: 'assets/jquery-easyui-1.9.15/images/lab.svg',
                 fit:true,
                 href: 'model/laboratuvar.php'
            },{
                 name: 'Klinik',
                 icon: 'assets/jquery-easyui-1.9.15/images/clinic.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Diyaliz',
                 icon: 'assets/jquery-easyui-1.9.15/images/dialig.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Hasta kayıt',
                 icon: 'assets/jquery-easyui-1.9.15/images/add-patient.svg',
                 fit:true,
                 href: 'model/hasta-kayit.php'
            },{
                 name: 'Ketem',
                 icon: 'assets/jquery-easyui-1.9.15/images/cancer.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'MHRS',
                 icon: 'assets/jquery-easyui-1.9.15/images/computer.png',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Muhasebe',
                 icon: 'assets/jquery-easyui-1.9.15/images/accounting.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Pacs',
                 icon: 'assets/jquery-easyui-1.9.15/images/image-arc.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Yatış',
                 icon: 'assets/jquery-easyui-1.9.15/images/hospitalization.svg',
                 fit:true,
                 href: 'model/yatis.php'
            },{
                 name: 'Kan Merkezi',
                 icon: 'assets/jquery-easyui-1.9.15/images/blood.svg',
                 fit:true,
                 href: 'model/kan-merkezi.php'
            },{
                 name: 'Reçete',
                 icon: 'assets/jquery-easyui-1.9.15/images/receipt.svg',
                 fit:true,
                 href: 'model/hasta-kabul.php'
            },{
                 name: 'Genel Formlar',
                 icon: 'assets/jquery-easyui-1.9.15/images/forms.svg',
                 fit:true,
                 href: 'model/genel-formlar.php'
                }

            ],

            buttons: '#buttons'
        })
    });
    settingsApp = null;
    function settings(){
        if (settingsApp){
            $('body').desktop('openApp', settingsApp);
            return;
        }
        settingsApp = {
            id: 'settings',
            name: 'Ayarlar',
            width: 700,
            height: 500,
            onBeforeClose: function(){
                settingsApp = null;
            }
        };
        $('body').desktop('openApp', settingsApp);
        var template = '<div>' +
            '<div region="north" style="padding:5px;height:45px;text-align:right"></div>' +
            '<div region="south" style="text-align:right;height:45px;padding:5px"></div>' +
            '<div region="west" title="Arka plan Resimleri" split="true" style="width:200px"><table id="settings-dl"></table></div>' +
            '<div region="center" title="Önizle Ekranı"><img id="settings-img" style="border:0;width:100%;height:100%;"></div>' +
            '</div>';
        var layout = $(template).appendTo('#settings');
        layout.layout({
            fit: true
        });
        var combo = $('<input>').appendTo(layout.layout('panel','north'));
        combo.combobox({
            data: [
                { value: 'default', text: 'Varsayılan', group: 'Base'},
                { value: 'gray', text: 'Gri', group: 'Base'},
                { value: 'metro', text: 'İhars Özel', group: 'Base'},
                { value: 'metro-green', text: 'İhars Yeşil', group: 'Base'},
                { value: 'metro-red', text: 'İhars Kırmızı', group: 'Base'},
                { value: 'metro-orange', text: 'İhars Turuncu', group: 'Base'},
                { value: 'metro-blue', text: 'İhars Mavi', group: 'Base'},
                { value: 'metro-gray', text: 'İhars Gri', group: 'Base'},
                { value: 'material', text: 'Material', group: 'Base'},
                { value: 'material-teal', text: 'İhars Deniz Mavisi', group: 'Base'},
                { value: 'material-blue', text: 'İhars Mavi', group: 'Base'},
                { value: 'bootstrap', text: 'Bootstrap', group: 'Base'},
                { value: 'black', text: 'Siyah', group: 'Base'},
                { value: 'ui-sunny', text: 'Güneş', group: 'Base'},
                { value: 'ui-dark-hive', text: 'Koyu Kovan', group: 'Base'},
                { value: 'ui-cupertino', text: 'Cupertino', group: 'Base'},
                { value: 'ui-pepper', text: 'Biber', group: 'Base'},

            ],
            width: 300,
            label: 'Temalar: ',
            value: 'material-teal',
            editable:false,
            panelHeight: 'auto',
            onChange: function(theme){
                var link = $('head').find('link:first');
                link.attr('href', 'assets/jquery-easyui-1.9.15/themes/'+theme+'/easyui.css');
                localStorage.setItem("hbys-theme", 'assets/jquery-easyui-1.9.15/themes/'+theme+'/easyui.css');
            }
        });

        $('#settings-dl').datalist({
            fit: true,
            data: [
                {"text":"Desktop", "img":"assets/jquery-easyui-1.9.15/images/bg.jpg"},
                {"text":"Desktop2","img":"assets/jquery-easyui-1.9.15/images/bg2.jpg"},
                {"text":"Desktop3","img":"assets/jquery-easyui-1.9.15/images/bg3.jpg"},
                {"text":"İhars-1","img":"assets/jquery-easyui-1.9.15/images/ihars-1.jpg"},
                {"text":"İhars-2","img":"assets/jquery-easyui-1.9.15/images/ihars-2.jpg"},
                {"text":"İhars-3","img":"assets/jquery-easyui-1.9.15/images/ihars-3.jpg"},
                {"text":"İhars-4","img":"assets/jquery-easyui-1.9.15/images/ihars-4.jpg"},
                {"text":"İhars-5","img":"assets/jquery-easyui-1.9.15/images/ihars-5.jpg"}
            ],
            onLoadSuccess:function(){
                $(this).datalist('selectRow', 1);
            },
            onSelect(index,row){
                $('#settings-img').attr('src', row.img);
                localStorage.setItem("hbys-image", row.img);
            }
        });

        $('<a style="margin-right:10px"></a>').appendTo(layout.layout('panel','south')).linkbutton({
            text: 'Onayla',
            width: 80,
            onClick: function(){
                $('body').desktop('setWallpaper', $('#settings-dl').datalist('getSelected').img);
                $('#settings').window('close');
            }
        });

        $('<a></a>').appendTo(layout.layout('panel','south')).linkbutton({
            text: 'Vazgeç',
            width: 80,
            onClick: function(){
                $('#settings').window('close');
            }
        })
    }

    function win_search(value){
        alert('ara: ' + value);
    }


</script>

<div id="buttons">
    <input class="easyui-searchbox" data-options="prompt:'Ara',searcher:win_search" style="width:150px;">
    <a href="javascript:;" class="easyui-linkbutton" iconCls="icon-remove" outline="true" plain="true" onclick="settings()">Ayarlar</a>
</div>


<style>
    .window-menu-content {
        padding: 7px;
        z-index: -1;
        position: fixed;
        bottom: 45px;
        left: 6px;
        width: 900px;
        height: 600px;
        background-color: #2a3f37;
        border: 2px solid black;
        position: absolute;
        transition: all 0.2s ease-in-out;
        display: flex;
    }
</style>

<div class="window-menu-content" id="window-menu-content" style="opacity: 0;"></div>







