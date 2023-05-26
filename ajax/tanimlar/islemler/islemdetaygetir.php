<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
yetkisorgula($_SESSION["username"], "islemgetir");
$verigetir=tekil("islem_tanimlari","id",$_GET["getir"]);
$islem = $_GET["islem"];
$gelen_veri = $_GET['getir'];
?>

<div class="card">
    <div class="card-header bg-primary text-white p-2">İşlem Detay Listesi</div>
    <div class="card-body">
        <table class="table table-bordered display nowrap w-100" id="islemdetay-table">
            <thead>
            <tr>
                <th>Durum</th>
                <th>İşlem Adı</th>
                <th>Ücreti</th>
                <th>Birimi</th>
                <th>İşlem</th>
                <th>İşlem Detay Getir</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


    <script type="text/javascript">
        $(document).ready(function(){

     $('#islemdetay-table').DataTable({
        "serverSide": true,
        "scrollY": 450,
        "scrollX": false,
        "paging":true,
        'Visible': true,
        "responsive":true,
        "pageLength": 50,

        ajax:{
        url: 'ajax/tanimlar/islemler/islem-server-side.php',
        type: 'POST',
         data: {id:<?php echo $_GET['getir']; ?>}
    },

        columns:[{data:'status'} , {data:'transaction_name'} , {data:'cost'} , {data:'unit_id'} , {data:'islem'}, {data:'islem2'}],

        "aLengthMenu": [[10, 25, 50, 75,100,500, 1000 ,2000],[10, 25, 50, 75,100,500, 1000 ,2000]],

        "columnDefs": [
    {
        "searchable": false,
        "targets": [1]
    },
    {
        "searchable": false,
        "targets": [2]
    }
        ],

        "oLanguage": {
        "sSearch": "İşlem Adı Ara:"
    },

        "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
    },

    });

    });
</script>



