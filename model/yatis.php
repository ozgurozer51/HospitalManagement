<?php

include '../controller/fonksiyonlar.php';

?>

<style>
    @media(min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width:1500px;
        }
    }
    @media(min-width: 768px) {
        .modal-xxl {
            width:99%;
            max-width:99%;
        }
    }
</style>



<style>

    #refakatcitab_filter > label  {
        width: 100%;
    }
    #refakatcitab_filter > label > input{
        width: 91%;
    }
    .nav-link{
        background-color:#F0F0F0!important;
        color: black;
    }
    .card-title, .kapat-btn{
        background-color:#3F72AF !important;
        color: white !important;
    }

    .modal-header{
        background-color:#112D4E !important;
        color: white !important;
    }
    .sil-btn{
        background-color:#E64848 !important;
        color: white !important;
    }
    .yeni-btn{
        background-color:#FF9551 !important;
        color: white !important;
    }
    .kps-btn{
        background-color: #775656 !important;
        color: white !important;
    }
    .up-btn{
        background-color:#60b3abad !important;
        color: white !important;
    }
    .yatis-btn{
        background-color:#39B496FF !important;
    }
    .tabs-inner {
        margin-right:3px !important;
    }

    body {
        overflow: hidden;
        height:100%;
    }
    .fa-light:hover{
        color: #93cbc6
    }
    .form-control{
        font-size:90%;
    }
    .fa-color{
        color: red;
    }
   

</style>
<?php

$hastaya_uniqid=uniqid();


?>

<script src="assets/webcam.min.js"></script>
<script>
    var ekran_yukseklik=screen.height-500;
    var ekran_genişlik=screen.width-500;

    var windows_height=screen.height-800;
    var windows_width=screen.width-100;

</script>

<div class="modal fade " id="modal-yatis">
    <div class="modal-dialog modal-xxl"  id="modal-yatis-icerik">Ana Modal</div>
</div>

<div id="dialogmodal-<?php echo $hastaya_uniqid; ?>"></div>

<div class="windows-<?php echo $hastaya_uniqid; ?>" style="width:80%;height:30%" id="windows-<?php echo $hastaya_uniqid; ?>"></div>
<div class="windows40-<?php echo $hastaya_uniqid; ?>" style="width:80%;height:40%" id="windows40-<?php echo $hastaya_uniqid; ?>"></div>
<div class="windows50-<?php echo $hastaya_uniqid; ?>" style="width:80%;height:50%" id="windows50-<?php echo $hastaya_uniqid; ?>"></div>
<div class="windowsfull-<?php echo $hastaya_uniqid; ?>" style="width:100%;height:90%" id="windowsfull-<?php echo $hastaya_uniqid; ?>"></div>

<script>
    $('.windows-<?php echo $hastaya_uniqid; ?>').window({
        onClose: function () {
            $('.windows-<?php echo $hastaya_uniqid; ?>').html("");
        },
        cache: true,
        modal: true,
        closed: true,
        iconCls: 'icon-save',
    });

    $('.windowsfull-<?php echo $hastaya_uniqid; ?>').window({
        onClose: function () {
            $('.windowsfull-<?php echo $hastaya_uniqid; ?>').html("");
        },
        cache: true,
        modal: true,
        closed: true,
        iconCls: 'icon-save',
    });

    $('.windows50-<?php echo $hastaya_uniqid; ?>').window({
        onClose: function () {
            $('.windows50-<?php echo $hastaya_uniqid; ?>').html("");
        },
        cache: true,
        modal: true,
        closed: true,
        iconCls: 'icon-save',
    });

    $('.windows40-<?php echo $hastaya_uniqid; ?>').window({
        onClose: function () {
            $('.windows40-<?php echo $hastaya_uniqid; ?>').html("");
        },
        cache: true,
        modal: true,
        closed: true,
        iconCls: 'icon-save',
    });
</script>


<div id="modul-ana-tab" class="easyui-tabs" data-options="tols:'#tab-tools'" >
    <div title="Yatış" iconCls="fa-thin fa-bed-front fa-color" style="overflow-x: hidden !important;">
        <div class="row" style="margin-left:10px">
            <div class="col-xl-12">
                <div class="row ">
                    <div class="col-xl-6 border border-dark rounded" style="background-color:#DBE2EF">
                        <div class="d-flex justify-content-evenly my-1">
                            <div class="col-md-6 mt-1">
                                <form  action="javascript:void(0);" id="formreset">
                                    <p class="mb-0 text-center fw-bold mb-1">Kriter Paneli</p>
                                    <div class="row">
                                        <div class="col-xl-6" >
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-xs" id="birimyatis_name" placeholder="Birim Adı" disabled>
                                                <div class="input-group-append input-group-sm">
                                                    <span id="birim-clk" onclick="$('.birimgetireasui').dialog('open')" class="input-group-text birim-one-clk">...</span>
                                                </div>
                                                <input type="hidden" class="form-control" id="birimyatis_id" >
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-xs" id="doktoryatis_name" placeholder="Doktor Adı" disabled>
                                                <div class="input-group-append input-group-sm">
                                                    <span id="doktor-listesi-btn" onclick="$('.doktorgetireasui').dialog('open')" class="input-group-text">...</span>
                                                </div>
                                                <input type="hidden" class="form-control" id="doktoryatis_id" >
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-xl-3 d-flex flex-column ">
                                            <input placeholder="Başlangıç Tarihi" class="textbox-n w-100  px-2 py-1"  type="date"
                                                   value='<?php echo date('Y-m-d'); ?>' id="baslangictarihi">
                                        </div>
                                        <div class="col-xl-3 d-flex flex-column">
                                            <input placeholder="Bitiş Tarihi" class="textbox-n w-100 px-2 py-1" type="date"
                                                   value='<?php echo date('Y-m-d'); ?>' id="bitistarihi">
                                        </div>
                                        <div class="col-xl-4 d-flex flex-column">

                                            <input placeholder="TC Numarası veya Protokol Numarası" class="textbox-n w-100 px-2 py-1" type="number"
                                                   id="tcnumarasi">
                                        </div>
                                        <div class="col-xl-2 d-flex flex-column ">
                                            <button type="reset" class="kapat-btn resetyap mx-1 py-1"><i class="fa-solid fa-arrows-rotate fa-sm"></i></button>
                                        </div>
                                    </div>
                                    <div class="row my-1">
                                        <div class="col-xl-6 d-flex flex-column">

                                            <input placeholder="İsim" class="textbox-n w-100 px-2 py-1" type="text"
                                                   id="hastaisim">
                                        </div>
                                        <div class="col-xl-6 d-flex flex-column">

                                            <input placeholder="soyisim" class="textbox-n w-100 px-2 py-1" type="text"
                                                   id="hastasoyisim">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-0 text-center fw-bold">Yatış İşlemleri</p>
                                <div class="row mx-1">
                                    <nav class="navbar ">
                                        <form class="form-inline" action="javascript:void(0);">
                                            <button style="width:30%; " class="btn btnyatis secilenal  btnyatisclick btn-sm" yatis_tur="1"; id="btnyatisbekle"><i class="fa-duotone fa-bed"></i> Yatış Bekleyen</button>
                                            <button style="width:30%" class="btn btnyatis secilenal btnyatisclick  btn-sm" yatis_tur="2"; id="btnyatan"><i class="fa fa-bed-pulse" aria-hidden="true"></i> Yatan Hasta</button>
                                            <button  style="width:30%" class="btn btnyatis secilenal btnyatisclick  btn-sm" yatis_tur="3"; id="btntaburcuolan"><i class="fa fa-male" aria-hidden="true"></i> Taburcu Olan</button>
                                            <button  style="width:30%" class="btn btnyatis secilenal btnyatisclick mt-1  btn-sm" yatis_tur="4"; id="btntaburcuolacaklar"><i class="fa-duotone fa-bed-pulse"></i>Taburcu Olacak</button>
                                            <button  style="width:30%" class="btn btnyatis secilenal btnyatisclick mt-1 btn-sm" yatis_tur="5"; id="btnizinli"><i class="fa fa-blind" aria-hidden="true"></i> İzinli Olan</button>
                                            <button  style="width:30%" class="btn btnyatis secilenal btnyatisclick mt-1 btn-sm" yatis_tur="6"; id="btnbaskaservis"><i class="fa fa-handshake" aria-hidden="true"></i> B. Servise G.G.</button>
                                        </form>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 border border-dark rounded" style="background-color:#DBE2EF">
                        <p class="fw-bold mb-0 text-center mt-1">Hasta İşlemleri</p>
                        <div class=" justify-content-evenly ">
                            <div class="row mx-1">
                                <nav class="navbar ">
                                    <form class="form-inline" action="javascript:void(0);">
                                        <button class="btn btnyatis secilenal  btn-sm hastayapilanhizmet" > <i class="fa-regular fa-book-open-reader "></i> Hasta Hizmet</button>
                                        <button class="btn btnyatis secilenal  btn-sm yatisiptalolan" ><i class="fa-thin fa-bed-front "></i> Yatiş İptal</button>
                                        <button class="btn btnyatis secilenal kosulalma btn-sm diyet_ekli_liste" ><i class="fa-solid fa-utensils"></i>Hasta Diyet</button>
                                    </form>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card institutionOther p-2 mt-1 mx-2" >
            <div class="row w-100 h-100">
                <div class="col-md-6" align="right">

                </div>
                <style>
                    .secilenal:hover{
                        background-color:rgb(57, 180, 150) !important;
                        color: white !important;
                    }
                </style>
                <div  class="doktorgetireasui" style="width:50%;height:50%;padding:5px">
                    <div id="dlg-toolbar" >
                        <script>
                            var birim_al=$('#birimyatis_id').val();
                        </script>
                        <table id="doktoruaes" class="easyui-datagrid"
                               toolbar="#tbdoktor" pagination="true"
                               rownumbers="true" fitColumns="true" singleSelect="true"
                               data-options="singleSelect:true,collapsible:true,url:'/ajax/yatis/yatislistesi.php?islem=doktor-listesi',method:'post'">
                            <thead>
                            <tr>
                                <th data-options="field:'doktor_id'" width="15%">Doktor Kodu</th>
                                <th data-options="field:'name_surname'" width="35%">Doktor Adı</th>
                                <th data-options="field:'birim_id'" width="15%">Birimi Kodu</th>
                                <th data-options="field:'department_name'" width="35%">Birimi Adı</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div id="tbdoktor" style="padding:3px">
                        <!--        tablo üzerinde input açma-->
                        <input id="doktor" placeholder="Doktor Adı" style="line-height:26px;border:1px solid #ccc">
                        <input id="doktor_kodu" placeholder="Doktor Kodu" style="line-height:26px;border:1px solid #ccc">
                        <button class="btn btn-light" onclick="doktorSearch()">Search</button>
<!--                        <a href="#" class="easyui-linkbutton" style="text-decoration: none" plain="true" onclick="doktorSearch()">Search</a>-->
                    </div>
                </div>

                    <script>
                        $('.doktorgetireasui').dialog({
                            onClose: function () {
                                $(this).find('FORM').form('clear');
                                // $('.doktorgetireasui').html("");
                            },
                            cache: true,
                            modal: true,
                            fit: false,
                            closed: true,
                            iconCls: 'icon-save',
                            title: 'Doktorlar',

                        });
                        function doktorSearch(){
                            $('#doktoruaes').datagrid('load',{
                                doktor: $('#doktor').val(),
                                doktor_kodu: $('#doktor_kodu').val()
                            });
                        }

                        setTimeout(function() {
                            $('#doktoruaes').datagrid({
                                onClickRow: function(index,field,value){
                                    var row = $('#doktoruaes').datagrid('getSelected');
                                    if (row!=null){
                                        $('#doktoryatis_name').val(row.name_surname);
                                        $('#doktoryatis_id').val(row.doktor_id);
                                        $('.doktorgetireasui').dialog('close');
                                    }else{
                                        alertify.warning('<b>!</b> Doktor Seçiniz..');
                                    }
                                }
                            });
                        }, 1000);
                    </script>




                <div class="birimgetireasui"  style="width:50%;height:50%;padding:5px">
                    <table id="birimuaes" class="easyui-datagrid"
                           pagination="true" toolbar="#tbbirim"
                           rownumbers="true" fitColumns="false" singleSelect="true"
                           data-options="singleSelect:true,collapsible:true,url:'/ajax/yatis/yatislistesi.php?islem=klinik-listesi',method:'post'">
                        <thead>
                        <tr>
                            <th data-options="field:'klinik'" width="75%">Bölüm Adı</th>
                            <th data-options="field:'klinik_kodu'" width="25%">Kodu</th>
                        </tr>
                        </thead>
                    </table>


                    <div id="tbbirim" style="padding:3px">
                        <!--        tablo üzerinde input açma-->
                        <input id="klinik" placeholder="Birim Adı" style="line-height:26px;border:1px solid #ccc">
                        <input id="klinik_kodu" placeholder="Birim Kodu" style="line-height:26px;border:1px solid #ccc">
                        <button class="btn btn-light" onclick="birimSearch()">Search</button>
<!--                        <a href="#" class="easyui-linkbutton" plain="true" onclick="birimSearch()">Search</a>-->
                    </div>

                </div>


                <script>

                    function birimSearch(){
                        $('#birimuaes').datagrid('load',{
                            klinik: $('#klinik').val(),
                            klinik_kodu: $('#klinik_kodu').val()
                        });
                    }

                    setTimeout(function() {
                        $('#birimuaes').datagrid({
                            onClickRow: function(index,field,value){
                                var row = $('#birimuaes').datagrid('getSelected');
                                if (row!=null){
                                    $('#birimyatis_name').val(row.klinik);
                                    $('#birimyatis_id').val(row.klinik_kodu);
                                    $('.birimgetireasui').dialog('close');
                                    $('#doktoruaes').datagrid('load', {
                                        birim_id:row.klinik_kodu
                                    });
                                }else{
                                    alertify.warning('<b>!</b> Birim Seçiniz..');
                                }
                            }
                        });
                    }, 1000);

                </script>

                <script type="text/javascript">
                    $(document).ready(function () {


                        $('.birimgetireasui').dialog({
                            onClose: function () {
                                $(this).find('FORM').form('clear');
                               // $('.birimgetireasui').html("");
                            },
                            cache: true,
                            modal: true,
                            fit: false,
                            closed: true,
                            iconCls: 'icon-save',
                            title: 'Servisler',

                        });

                        $("body").off("click",".hastayapilanhizmet").on("click",".hastayapilanhizmet", function (e) {
                            var birimid=$("#birimyatis_id").val();
                            if (birimid!='') {

                                $(".secilenal").css({"background-color":"#ffb3b3"});
                                $("#btnyatan").css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').removeClass("text-white");
                                $('.sevk-dom').removeClass("yatis-btn");
                                $(this).css({"background-color":"rgb(57, 180, 150)"});
                                $(this).addClass("text-white");
                                $(this).addClass("yatis-btn");
                                $(this).addClass("sevk-dom");

                                $('#panel_servis').panel('refresh', 'ajax/yatis/yatislistesi.php?islem=hastayapilanhizmet&birimid='+birimid+'');
                                $('#panel_servis').panel('setTitle', 'Serviste Olan Hastalara Eklenmiş Hizmetler');
                            }else{
                                alertify.warning('<b>!</b> Birim Seçiniz..');
                            }
                        });

                        $("body").off("click",".diyet_ekli_liste").on("click",".diyet_ekli_liste", function (e) {
                            var birimid=$("#birimyatis_id").val();
                            // $.get("ajax/diyet/diyet_listesi.php?islem=diyet_ekli_liste", {"birimid": birimid}, function (get) {
                            //     $('.secilentab').html(get);
                            //
                            // });
                            if (birimid!=''){

                                $(".secilenal").css({"background-color":"#ffb3b3"});
                                $("#btnyatan").css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').removeClass("text-white");
                                $('.sevk-dom').removeClass("yatis-btn");
                                $(this).css({"background-color":"rgb(57, 180, 150)"});
                                $(this).addClass("text-white");
                                $(this).addClass("yatis-btn");
                                $(this).addClass("sevk-dom");

                                $('#panel_servis').panel('refresh', 'ajax/diyet/diyet_listesi.php?islem=diyet_ekli_liste&birimid='+birimid+'');
                                $('#panel_servis').panel('setTitle', 'Serviste Olan Hastalara Eklenmiş Diyetler');
                            }else{
                                alertify.warning('<b>!</b> Birim Seçiniz..');
                            }

                        });



                        $("body").off("click",".yatisiptalolan").on("click",".yatisiptalolan", function (e) {
                            var birimid=$("#birimyatis_id").val();
                            if (birimid!='') {

                                $(".secilenal").css({"background-color":"#ffb3b3"});
                                $("#btnyatan").css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').removeClass("text-white");
                                $('.sevk-dom').removeClass("yatis-btn");
                                $(this).css({"background-color":"rgb(57, 180, 150)"});
                                $(this).addClass("text-white");
                                $(this).addClass("yatis-btn");
                                $(this).addClass("sevk-dom");

                                $('#panel_servis').panel('refresh', 'ajax/yatis/yatislistesi.php?islem=yatisiptalolan&birimid='+birimid+'');
                                $('#panel_servis').panel('setTitle', 'Yatışı İptal Olan Hastalar');
                            }else{
                                alertify.warning('<b>!</b> Birim Seçiniz..');
                            }
                        });

                        var hastaya_uniqid="<?php echo $hastaya_uniqid; ?>"
                        var yatis_tur='1';
                        $('#panel_servis').panel('refresh', 'ajax/yatis/yatislistesi.php?islem=yatissorgula&yatis_tur='+yatis_tur+'&hastaya_uniqid='+hastaya_uniqid+'');

                        $("body").off("click",".btnyatisclick").on("click",".btnyatisclick", function (e) {
                            var birimid=$("#birimyatis_id").val();
                            var doktorid=$("#doktoryatis_id").val();
                            var hastaisim=$("#hastaisim").val();
                            var hastasoyisim=$("#hastasoyisim").val();
                            var tcnumarasi=$("#tcnumarasi").val();
                            var baslangic=$('#baslangictarihi').val();
                            var bitis=$('#bitistarihi').val();
                            // var secilen = $(this).val();

                            if (birimid=='' && doktorid=='' && hastaisim=='' && hastasoyisim=='' && tcnumarasi=='' ){
                                $(".secilenal").css({"background-color":"#ffb3b3"});
                                $(".secilenal").css({"border-color":"#3f72af"});
                                var  hasClass = $(this).hasClass('kosulalma');
                                if (!hasClass){
                                    alertify.warning('<b>!</b> Koşul Seçiniz..');
                                }
                            }else{
                                var secilen = $(this).attr('id');
                                var yatis_tur=$(this).attr('yatis_tur');

                                $(".secilenal").css({"background-color":"#ffb3b3"});
                                $("#btnyatan").css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').css({"background-color":"#ffb3b3"});
                                $('.sevk-dom').removeClass("text-white");
                                $('.sevk-dom').removeClass("yatis-btn");
                                $(this).css({"background-color":"rgb(57, 180, 150)"});
                                $(this).addClass("text-white");
                                $(this).addClass("yatis-btn");
                                $(this).addClass("sevk-dom");

                                $('#panel_servis').panel('refresh', 'ajax/yatis/yatislistesi.php?islem=yatissorgula' +
                                    '&birimid='+birimid+'&baslangic='+baslangic+'&bitis='+bitis+'&doktorid='+doktorid+'&hastaisim='+hastaisim+
                                    '&hastasoyisim='+hastasoyisim+'&tcnumarasi='+tcnumarasi+'&yatis_tur='+yatis_tur+'&hastaya_uniqid='+hastaya_uniqid+'');

                                if (yatis_tur==1){
                                    $('#panel_servis').panel('setTitle', 'Yatış Bekleyen Hastalar');
                                }
                                else if (yatis_tur==2){
                                    $('#panel_servis').panel('setTitle', 'Yatışta Olan Hastalar');
                                }
                                else if (yatis_tur==3){
                                    $('#panel_servis').panel('setTitle', 'Taburcu Olan Hastalar');
                                }
                                else if (yatis_tur==4){
                                    $('#panel_servis').panel('setTitle', 'Taburcu Olacak Hastalar');
                                }
                                else if (yatis_tur==5){
                                    $('#panel_servis').panel('setTitle', 'İzinli Olan Hastalar');
                                }
                                else if (yatis_tur==6){
                                    $('#panel_servis').panel('setTitle', 'Başka Servise Giden/Gelen Olan Hastalar');
                                }
                            }
                        });
                    });
                </script>
                <div class="secilentab">

                </div>
                <?php $ekran_yukseklik="<script>document.write(ekran_yukseklik)</script>"; ?>
                <div id="panel_servis" class="easyui-panel" title="Yatış Bekleyen Hastalar" style="padding-left:30px;padding-top:10px;width:100%;height:<?php echo $ekran_yukseklik; ?>">

                </div>

                    <div id="yatisMenu" class="easyui-menu">
                        <div data-options="iconCls:'icon-save'" onclick="$('#figurlistegetir').dialog('open')" class="btnfigur">Hasta Figurleri</div>
                        <div >#</div>
                        <div class="menu-sep"></div>
                        <div>#</div>
                    </div>

                    <script>

                        // sag click işlemi iptali için
                        // $("body").on("contextmenu", function(e) {
                        //     return false;
                        // });

                    </script>

            </div>
        </div>

    </div></div>


<div id="figurlistegetir" class="easyui-dialog" title="Figur Seçim"
     data-options="iconCls:'icon-save',resizable:true,modal:true,closed: true"
     style="width:22%;height:22%;padding:5px">
    <div id="dlg-toolbar" >
        <ul class="list-group" id="figursec">

            <style>
                .alertify .ajs-body .ajs-content {
                    padding:unset;
                }
            </style>
            <?php
            $bolumgetir = "select * from transaction_definitions where definition_type='figurler' ORDER BY definition_name desc";

            $hello=verilericoklucek($bolumgetir);
            $sayi=0;
            foreach ((array) $hello as $value){
                $sayi++;?>
                <li class="list-group-item secilifigur  btnfi-<?php echo $sayi; ?>" style="cursor: pointer" figur_file="<?php echo $value['definition_supplement']; ?>" figur_name="<?php echo $value['definition_name']; ?>" >
                    <img title="<?php echo $value['definition_name'] ?>" src="<?php echo $value['definition_supplement'] ?>" style="margin-right:5px" alt="yeni hasta" width="15px">
                    <?php echo $value['definition_name'] ?>
                </li>
            <?php  }  ?>
        </ul>
        <input type="hidden" class="protokol_numarasi_alma">
    </div>

</div>

<script>
    $("body").off("click", ".secilifigur").on("click", ".secilifigur", function (e) {

        $('.figurbtn-dom').css({"background-color":"rgb(255, 255, 255)"});
        $('.figurbtn-dom').removeClass("text-white");
        $('.figurbtn-dom').removeClass("active");
        $(this).css({"background-color":"rgb(57, 180, 150"});
        $(this).addClass("text-white");
        $(this).addClass("figurbtn-dom");
        $(this).addClass("active");
    });
</script>

<script >
    $(document).ready(function(){

        $("body").off("click", ".btnfi-1").on("click", ".btnfi-1", function (e) {
            var protokolno = $(".protokol_numarasi_alma").val();
            var figur_name = $(this).attr('figur_name');
            //sarı yaprak
            $.get("ajax/yatis/yatislistesi.php?islem=figursorgula", {"protokolno": protokolno,"figur_name":figur_name}, function (get) {
                if (get==1){
                    alertify.warning("Hastaya ekli zaten figur");
                }else{
                    var figurtur=1;
                    $.get("ajax/yatis/yatisislem.php?islem=figur_ekle",{protocol_number: protokolno,figurtur:figurtur}, function (e) {
                        $('#sonucyaz').html(e);
                    });
                    yatisdatatable.ajax.url(yatisdatatable.ajax.url()).load();
                }

            });
        });
        $("body").off("click", ".btnfi-2").on("click", ".btnfi-2", function (e) {
            var protokolno = $(".protokol_numarasi_alma").val();
            var figur_name = $.trim($(this).attr('figur_name'));
            //mavi yaprak
            $.get("ajax/yatis/yatislistesi.php?islem=figursorgula", {"protokolno": protokolno,"figur_name":figur_name}, function (get) {
                if (get==1){
                    alertify.warning("Hastaya ekli zaten figur");
                }else{
                    var figurtur=2;
                    $.get("ajax/yatis/yatisislem.php?islem=figur_ekle",{protocol_number: protokolno,figurtur:figurtur}, function (e) {
                        $('#sonucyaz').html(e);
                    });
                    yatisdatatable.ajax.url(yatisdatatable.ajax.url()).load();
                }

            });
        });
        $("body").off("click", ".btnfi-3").on("click", ".btnfi-3", function (e) {
            var protokolno = $(".protokol_numarasi_alma").val();
            var figur_name = $.trim($(this).attr('figur_name'));
            // kırmızı yaprak
            $.get("ajax/yatis/yatislistesi.php?islem=figursorgula", {"protokolno": protokolno,"figur_name":figur_name}, function (get) {
                if (get==1){
                    alertify.warning("Hastaya ekli zaten figur");
                }else{
                    var figurtur=3;
                    $.get("ajax/yatis/yatisislem.php?islem=figur_ekle",{protocol_number: protokolno,figurtur:figurtur}, function (e) {
                        $('#sonucyaz').html(e);
                    });
                    yatisdatatable.ajax.url(yatisdatatable.ajax.url()).load();
                }

            });
        });
        $("body").off("click", ".btnfi-4").on("click", ".btnfi-4", function (e) {
            // dört yapraklı
            var protokolno = $(".protokol_numarasi_alma").val();
            var figur_name = $.trim($(this).attr('figur_name'));

            $.get("ajax/yatis/yatislistesi.php?islem=figursorgula", {"protokolno": protokolno,"figur_name":figur_name}, function (get) {
                if (get==1){
                    alertify.warning("Hastaya ekli zaten figur");
                }else{
                    $('#figurlistegetir').dialog('close');

                    var win=$('.windowsfull-<?php echo $hastaya_uniqid; ?>')
                    win.window('setTitle', 'Harizmi Düşme Risk Formu');

                    $('#windowsfull-<?php echo $hastaya_uniqid; ?>').window('open');
                    $('#windowsfull-<?php echo $hastaya_uniqid; ?>').window('refresh', 'ajax/harizmi_2_dusme_riski_olcegi/harizmi_2_dusme_riski_olcegi.php?islem=harizmiolcek2body&getir='+protokolno+'');

                    yatisdatatable.ajax.url(yatisdatatable.ajax.url()).load();
                }

            });
        });

    });
</script>












