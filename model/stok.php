<?php

include '../../controller/fonksiyonlar.php';
$islem  = $_GET["id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stok Ürün Kayıt</title>
</head>

<body id="stokModulu">

<div class="col-xl-12 bgSoftBlue">
    <div class="row gx-0">
        <div class="col-xl-6 headerBorder">
            <h5 class="pageSubtitle mb-0 text-center">Depo Seçim</h5>
            <div class="hstack gap-3">
                <div class="text-center">
                    <button class="bg-transparent border-0">
                        <img src="assets/icons/warehouse.png"  width="30px">
                    </button>
                </div>
                <div id="header">
                    <div class="row gx-0 mt-2">
                        <div class="d-flex align-items-center">
                            <div class="d-flex">
                                <div class="col-xl-">
                                    <div id="liste-yenile"></div>

                                    <select class="col-sm-6 form-select secilen-depo">
                                        <option value="Seçilmedi" selected>Depo Seçimi Yapınız..</option>
                                        <?php
                                        $depo_cek = verilericoklucek(" SELECT * FROM warehouses WHERE status=1");
                                        foreach ($depo_cek as $item) {
                                            ?>
                                            <option class="secilen-depo-bilgisi" data-name="<?php echo $item["warehouse_name"]?>" value="<?php echo $item["id"];?>"><?php echo $item["warehouse_name"]; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="flex-row">
                                <div class="d-flex justify-content-evenly">
                                    <div class="col-xl-3 text-center">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <div class="col-xl-6 headerBorder">
            <h5 class="pageSubtitle mb-0 text-center">Servis İstekleri</h5>
            <div class="d-flex justify-content-evenly">
                <div class="text-center">
                    <button class="bg-transparent border-0" id="new-stock-card">
                        <img src="assets/icons/Report-Card.png" width="30px">
                        <div class="fw-500 fsLaptop">Stok Kartları</div>
                    </button>
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0" id="new-firm-add">
                        <img src="assets/icons/companion.png" width="30px">
                        <div class="fw-500 fsLaptop">Firmalar</div>
                    </button>
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0" id="warehouse-process">
                        <img src="assets/icons/Stacking.png" width="30px">
                        <div class="fw-500 fsLaptop">Depo Tanımları</div>
                    </button>
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0" id="stock-invoice">
                        <img src="assets/icons/Lira.png" width="30px">
                        <div class="fw-500 fsLaptop">Fatura</div>
                    </button>
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0">
                        <img src="assets/icons/sign-out.png" width="30px">
                        <div class="fw-500 fsLaptop">Çıkış</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="get-tables"></div>
<div id="get-modals"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<script>
    // STOK KARTLARI SCRİPTİ
    $("#new-stock-card").click(function (){
        $.get("ajax/stok/stok-tablo.php?islem=stok-kartlari-getir",function (e){
            $("#get-tables").html(e);
        });
    });

    // DEPOYA GÖRE VERİGETİR SCRİPTİ
    $(".secilen-depo").on("change",function (){
        var warehouse_id = $(".secilen-depo").val();
        $.get("ajax/stok/stok-tablo.php?islem=depoya-gore-getir",{warehouse_id:warehouse_id},function (getVeri){
            $("#get-tables").html(getVeri);
        });
    });

    // YENİ FİRMA EKLEME SCRİPTİ
    $("#new-firm-add").on("click",function (){
        $.get("ajax/stok/stok-tablo.php?islem=firmalari-getir",function (getVeri){
            $("#get-tables").html(getVeri);
        });
    });
    $("#warehouse-process").click(function (){
        $.get("ajax/stok/stok-depo.php?islem=warehouses",function (getVeri){
           $("#get-tables").html(getVeri);
        });
    });

    $("#stock-invoice").click(function (){
        $.get("ajax/stok/stok-tablo.php?islem=get-invoices",function (getVeri){
           $("#get-tables").html(getVeri);
        });
    })

    $(document).ready(function (){
        $('#dock-container').remove();
    });
</script>

