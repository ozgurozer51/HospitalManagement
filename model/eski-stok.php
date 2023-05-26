<?php

include '../../controller/fonksiyonlar.php';
?>
<div id="stokModulu">
<div class="row gx-0 mt-2 " id="istekKarsilaProcess">
    <div class="col-xl-12 bgSoftBlue">
        <div class="row gx-0 ">
            <div class="col-xl-6 headerBorder">
                <h5 class="pageSubtitle mb-0 text-center">Depo Seçim</h5>
                <div class="hstack gap-3">

                    <div id="header">
                        <div class="row gx-0 mt-2">
                            <div class="d-flex align-items-center shadow-sm" style=" background: #dbe2ef;">
                                <div class="row">
                                    <div class="col-xl-" id="select-warehouse">
                                        <select class="col-xs-6 form-select secilen">
                                            <option selected>Depo Seçimi Yapınız..</option>
                                            <?php
                                            $depo_cek = verilericoklucek(" SELECT * FROM warehouses");
                                            foreach ($depo_cek as $item) {
                                            ?>
                                                <option id="<?php echo $item["id"];?>"><?php echo $item["warehouse_name"]; ?></option>
                                            <?php } ?>
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

                    <div class="d-flex justify-content-evenly">
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0" id="view-firm">
                            <img src="assets/icons/companion.png"  width="50px">
                            <div class="fw-500 fsLaptop">Firmalar</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0" id="new-firm">
                            <img src="assets/icons/users-dotted.png"  width="50px">
                            <div class="fw-500 fsLaptop">Yeni Firma Girişi</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0" id="new-stok">
                            <img src="assets/icons/open-box.png"  width="50px">
                            <div class="fw-500 fsLaptop">Yeni Stok Girişi</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0" id="add-warehouse-stok">
                            <img src="assets/icons/depot.png"  width="50px">
                            <div class="fw-500 fsLaptop">Depo Ürün Girişi</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0" id="create-request">
                            <img src="assets/icons/plus.png"  width="50px">
                            <div class="fw-500 fsLaptop">İstek Oluştur</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0">
                            <img src="assets/icons/Reseller.png"  width="50px">
                            <div class="fw-500 fsLaptop">İstek Karşıla</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0">
                            <img src="assets/icons/Stocks.png"  width="50px">
                            <div class="fw-500 fsLaptop">Servis Stok İşlemleri</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0">
                            <img src="assets/icons/Pill.png"  width="50px">
                            <div class="fw-500 fsLaptop">İlaç&Sarf İstem</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0">
                            <img src="assets/icons/sign-out.png"  width="50px">
                            <div class="fw-500 fsLaptop">Çıkış</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="col-xl-12">
            <div class="card p-3" >
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card border-0 bg-transparent">
                            <div class="row">
                                <div class="col-12 ">
                                    <div class="card institutionOther p-3" style=" background: #dbe2ef; ">
                                        <div class="row">

                                                                <div class="secilentab"></div>
                                                                <div id="get-stock-cards"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <div class="card">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                                        <div class="istenen-kalemler"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
<div id="upgrade-stok"></div>
        <div id="new-stok-modal"></div>
<div id="new-firm-add"></div>
    </div>
    <script>
        $("#select-warehouse select").change(function (){
            var warehouse_id = $(this).children(":selected").attr("id");
            $.get("ajax/stok/stok-tablo.php?islem=stoklari-getir",{warehouse_id:warehouse_id},function (getList){
                $("#get-stock-cards").html(getList);
            });
        });

        $("#get-stock-cards").on("click","tr",function (){
            var id = $(this).attr("id");
           /* $.get("ajax/stok/stok-tablo.php?islem=istenen-kalemler",function (getPencils){
                $(".istenen-kalemler").html(getPencils);
            })*/
        });

        $("#new-stok").click(function (){
            $.get("ajax/stok/stok-modal.php?islem=yeni-stok-getir",function (getStock){
                $("#new-stok-modal").html(getStock);
            });
        });

        $("#new-firm").click(function (){
           $.get("ajax/stok/stok-modal.php?islem=new-firm-add",function (e){
               $("#new-firm-add").html(e);
           })
        });
        $("#view-firm").click(function (){
            $.get("ajax/stok/stok-tablo.php?islem=get-companies",function (e){
               $("#get-stock-cards").html(e);
            });
        });
        $("#add-warehouse-stok").click(function (){
           $.get("ajax/stok/stok-tablo.php?islem=depoya-urun-ekle",function (e){
               $("#get-stock-cards").html(e);

           });
        });

        $("#create-request").click(function (){
           $.get("ajax/stok/stok-tablo.php?islem=add-request",function (getVeri){
                $("#get-stock-cards").html(getVeri);
           })
        });
    </script>

