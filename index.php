<?php
include "controller/fonksiyonlar.php";
$sayfa = $_GET["sayfa"];
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
if ($sayfa == "cikisyap") {
    guncelle("UPDATE  kullanicilar_oturum_kayitlari SET  sonlandirilma_tarihi='$gunceltarih' WHERE oturum_hash='{$_SESSION["OTURUM_HASH"]}' AND sonlandirilma_tarihi IS NULL");
    session_destroy(); go("index.php");
}

oturumkaydikontrol();

$kullanicininidsi=$_SESSION["id"];

$uniqid = uniqid();
$uniqid_2 = uniqid();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/jquery-easyui-1.9.15/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="assets/jquery-easyui-1.9.15/themes/icon.css">
    <!--    <link rel="stylesheet" type="text/css" href="assets/fontawesome-6.2/css/all.min.css"/> -->
    <link rel="icon" href="data:;base64,=">
    <link href="assets/alertifyjs-(v1.13.1)/css/alertify.css" rel="stylesheet" type="text/css"/>
    <link href="assets/alertifyjs-(v1.13.1)/css/themes/default.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="assets/grid-kesirli.css">
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-5.0.2/css/bootstrap.css"/>
    <!--    <script type="text/javascript" src="assets/fontawesome-6.2/js/all.min.js"></script> -->
    <link rel="stylesheet" type="text/css" href="assets/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href='assets/fullcalendar5/lib/main.css' rel='stylesheet'/>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>


    <script type="text/javascript" src="assets/bootstrap-5.0.2/js/bootstrap.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.3.122/pdf.min.js"></script>
    <script type="text/javascript" src="assets/jquery-easyui-1.9.15/jquery.min.js"></script>
    <script src="assets/alertifyjs-(v1.13.1)/alertify.js"></script>

    <script type="text/javascript" src="assets/fontawesome-6.2/hile.js"></script>
    <script src='assets/fullcalendar5/lib/main.js'></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.1/html2pdf.bundle.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/print-js@1.6.0/dist/print.js"></script>

    <script type="text/javascript" src="assets/jquery/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="assets/jquery-easyui-1.9.15/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="assets/jquery-easyui-1.9.15/edatagrid.js"></script>
    <script type="text/javascript" src="assets/jquery-easyui-1.9.15/detailview.js"></script>
    <script type="text/javascript" src="assets/jquery-easyui-1.9.15/easyloader.js"></script>
    <script type="text/javascript" src="assets/jquery-easyui-1.9.15/jquery.easyui.mobile.js"></script>
    <script type="text/javascript" src="assets/jquery-easyui-1.9.15/locale/easyui-lang-tr.js"></script>

    <script type="text/javascript" src="assets/DataTables/datatables.min.js"></script>

    <title>Sağlık Bilgi Sistemi</title>

</head>

<body class="" id="saglikBilgiSistemi">

<?php  $hersayfasorgu = yetkisorgula($kullanicininidsi, $sayfa);

if ($hersayfasorgu) {
    if ($sayfa) {
        include "model/" . $sayfa . ".php";
       // require __DIR__.'/controller/windows/index.php';
    } else {
        include "model/anasayfa-2.php";
    }
} else { ?>
    <div class="alert alert-warning alert-dismissible fade show px-4 mb-0 text-center" role="alert">
        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
        <h5 class="text-warning">Yetksiz İşlem</h5>
        <p>Yetkisiz işlem yapmaya çalıştığınız tespit edilmiştir!!!. </p>
    </div>
<?php } ?>

<div  class="dataTables_processing card" id="processing-dom" style="z-index:99999 !important; display: block;">Yükleniyor...<div><div></div><div></div><div></div><div></div></div></div>
<div class="sonucyaz" id="sonucyaz"></div>

<script>
var hbys_theme = localStorage.getItem("hbys-theme");
    if (hbys_theme) {
        var link = $('head').find('link:first');
        if (hbys_theme.length > 0) {
            link.attr('href', hbys_theme);
        }
    }

    var hbys_image = localStorage.getItem("hbys-image");
    if (hbys_image) {
        $('#saglikBilgiSistemi').css('background-image', 'url("' + hbys_image + '")');
    }
</script>

<script>
    $(document).ready(function () {
        $('#processing-dom').hide();
        $(document).ajaxStart(function () {
            $('#processing-dom').show();

            setTimeout(function () {
                $('#processing-dom').hide();
            }, 5000);

        }).ajaxStop(function () {
            $('#processing-dom').hide();
        });

        $(".btn-kaydet").on("click", function () {
            var secilen = $(".btntanimla-dom").attr('id');
            $.get("ajax/tanimlar/" + secilen + ".php?islem=modal-icerik", function (get) {
                $('#modal-tanim-icerik').html(get);
            });
        });

        var index = 0;

        function removePanel() {
            var tab = $('#tt').tabs('getSelected');
            if (tab) {
                var index = $('#tt').tabs('getTabIndex', tab);
                $('#tt').tabs('close', index);
            }
        }

    });

    $(document).ready(function (){
        $.extend($.fn.datebox.defaults,{
            formatter:function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return (d<10?('0'+d):d)+'.'+(m<10?('0'+m):m)+'.'+y;
            },
            parser:function(s){
                if (!s) return new Date();
                var ss = s.split('\.');
                var d = parseInt(ss[0],10);
                var m = parseInt(ss[1],10);
                var y = parseInt(ss[2],10);
                if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                    return new Date(y,m-1,d);
                } else {
                    return new Date();
                }
            }
        });
    });
</script>

</body>

</html>




<style>


    button{
        padding: 0px;
        margin: 0px;
    }

    input[type=text]{
        border:1px solid #aaa !important;
        border-radius:1px !important;
        padding:0px !important;
        transition:.3s !important;
        margin: 0px !important;
    }

    select{
        border:1px solid #aaa !important;
        border-radius:1px !important;
        padding:0px !important;
        transition:.3s !important;
        margin: 0px !important;
    }

    select{
        border:1px solid #aaa !important;
        border-radius:1px !important;
        padding:0px !important;
        transition:.3s !important;
        margin: 0px !important;
    }

    input[type="number"]{
        border:1px solid #aaa !important;
        border-radius:1px !important;
        padding:0px !important;
        transition:.3s !important;
        margin: 0px !important;
    }

    select:focus{
        border-color:dodgerBlue !important;
        box-shadow:0 0 8px 0 dodgerBlue !important;
    }

    textarea:focus{
        border-color:dodgerBlue !important;
        box-shadow:0 0 8px 0 dodgerBlue !important;
    }

    input[type=text]:focus{
        border-color:dodgerBlue !important;
        box-shadow:0 0 8px 0 dodgerBlue !important;
    }

    input[type="datetime-local"]:focus{
        border-color:dodgerBlue !important;
        box-shadow:0 0 8px 0 dodgerBlue !important;
    }

    input[type="date"]:focus{
        border-color:dodgerBlue !important;
        box-shadow:0 0 8px 0 dodgerBlue !important;
    }

    input[type="datetime-local"] {
        margin: 0px !important;
        padding: 0px !important;
        border-radius: 1px !important;
    }

    input[type="date"] {
        margin: 0px !important;
        padding: 0px !important;
        border-radius: 1px !important;
    }

</style>

