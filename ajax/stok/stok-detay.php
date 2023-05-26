<?php

include '../../controller/fonksiyonlar.php';
$islem = $_GET["islem"];

if ($islem == "stok-hareketleri-tablo"){
?>
    <script>
        $(document).ready( function () {
            $('#stock-cards').DataTable( {
                "responsive":true,
                paging:false,
                length:10,
                scrollX:true,
                scrollY:"35vh",
                searching: false,
                info:false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });
    </script>

<div class="row">
    <div class="col-12">
        <font size="2">
        <table class="table table-bordered table-sm table-hover px-2" id="stock-cards" style="background: white; width: 100%;" >
            <thead class="table-light">
            <tr>
                <th>Barkod</th>
                <th>Ürün Adı</th>
                <th>Üretici Firma</th>
                <th>Ürün Tipi</th>
                <th>Kutu Miktarı</th>
                <th>Uyarılar</th>
                <th>Gebe Kullanım</th>
                <th>Araç Kullanım</th>
                <th>Açıklama</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $istekler = verilericoklucek(" SELECT * FROM stock_card WHERE status=1");
            foreach ($istekler as $item) {
                ?>
                <tr id="<?php echo $item["id"]?>" class="stock-id">
                    <td><?php echo $item["barcode"]?></td>
                    <td><?php echo $item["stock_name"]?></td>
                    <td><?php echo $item["producting_brand"]?></td>
                    <td><?php echo islemtanimgetirid($item["stock_type"])?></td>
                    <td><?php echo $item["stock_number_of_boxes"]?></td>
                    <td><?php echo $item["stock_warnings"]?></td>
                    <td><?php echo $item["stock_pregnant_use"]?></td>
                    <td><?php echo $item["stock_vehicle_machine_use"]?></td>
                    <td><?php echo $item["drug_description"]?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php }

if ($islem == "stok-hareket-sorgula-getir"){
?>
<div class="row">
    <div class="col-4 mt-4">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label-sm">Malzeme Adı</label>
            <div class="col-sm-6">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm malzeme-adi" disabled>
                    <div class="input-group-append">
                        <button class="btn btn-outline-warning btn-sm" id="ilac-listesi-getir" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row mt-1">
            <label class="col-sm-4 col-form-label-sm">Ehu Onay Durum</label>
            <div class="col-sm-6">
                <select id="" class="form-select ehu-onay-durum">
                    <option value="Seçiniz...">Seçiniz...</option>
                    <option value="1">Onaylı</option>
                    <option value="2">Onaysız</option>
                </select>
            </div>
        </div>
        <div class="form-group row mt-1">
            <label class="col-sm-4 col-form-label-sm">MKYS Kodu</label>
            <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm mkys-kodu">
            </div>
        </div>
    </div>
    <div class="col-4 mt-4">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label-sm">İstek Durum</label>
            <div class="col-sm-6">
                <select class="form-select istek-durum">
                    <option value="Seçiniz...">Seçiniz...</option>
                    <option value="1">Karşılandı</option>
                    <option value="2">Karşılanmadı</option>
                    <option value="0">Bekliyor</option>
                </select>
            </div>
        </div>
        <div class="form-group row mt-1">
            <label class="col-sm-4 col-form-label-sm">Taşınır No</label>
            <div class="col-sm-6">
                <input type="text"  class="form-control form-control-sm tasinir-no">
            </div>
        </div>
        <div class="form-group row mt-1">
            <label class="col-sm-4 col-form-label-sm">Malzeme Tipi</label>
            <div class="col-sm-6">
                <select class="form-select malzeme-tipi">
                    <option value="Seçiniz...">Seçiniz...</option>
                    <option value="28464">İlaç</option>
                    <option value="28462">Sarf</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-4 mt-4">

        <div class="form-group row">
            <label class="col-sm-4 col-form-label-sm">Reçete Türü</label>
            <div class="col-sm-6">
                <select id="" class="form-select malzeme-turu">
                    <option value="Seçiniz...">Seçiniz...</option>
                    <option value="Normal">Normal</option>
                    <option value="Kırmızı">Kırmızı</option>
                    <option value="Turuncu">Turuncu</option>
                    <option value="Mor">Mor</option>
                    <option value="Yeşil">Yeşil</option>
                </select>
            </div>
        </div>
        <div class="form-group row mt-1">
            <label class="col-sm-4 col-form-label-sm">Baş. Tarih</label>
            <div class="col-sm-6">
                <input type="date"  class="form-control form-control-sm baslangic-tarih">
            </div>
        </div>
        <div class="form-group row mt-1">
            <label class="col-sm-4 col-form-label-sm">Bit. Tarih</label>
            <div class="col-sm-6">
                <input type="date"  class="form-control form-control-sm bitis-tarih">
            </div>
        </div>
        <div class="modal-footer mx-5">
            <button class="btn btn-sm" style="color: white; background-color: #FF9551" id="filtre-kriter-getir">Filtrele</button>
        </div>
    </div>
    <br>
    <div class="col-12 gelecek-tablo">
        <br>
        <font size="2">
        <table class="table table-bordered table-sm table-hover px-2 fiter-detail" style="background: white; width: 100%; style='font-family:" >
            <thead class="table-light">
            <tr>
                <th>Ürün Adı</th>
                <th>Tür</th>
                <th>Barkod</th>
                <th>MKYS Kod</th>
                <th>T. Kod</th>
                <th>Reçete Türü</th>
                <th>D.G. Zaman</th>
                <th>D.Adı</th>
                <th>İstek Zamanı</th>
                <th>İstek</th>
                <th>D.Ç Zaman</th>
                <th>İade Miktarı</th>
                <th>Ehu Durum</th>
                <th>Firma</th>
                <th>Adet</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

    <script>
        $("#filtre-kriter-getir").click(function (){
            var malzeme_adi = $(".malzeme-adi").val();
            var ehu_onay_durum = $(".ehu-onay-durum").val();
            var mkys_kodu = $(".mkys-kodu").val();
            var istek_durum = $(".istek-durum").val();
            var tasinir_no = $(".tasinir-no").val();
            var malzeme_tipi = $(".malzeme-tipi").val();
            var malzeme_turu = $(".malzeme-turu").val();
            var baslangic_tarih = $(".baslangic-tarih").val();
            var bitis_tarih = $(".bitis-tarih").val();

            $.ajax({
                url:"ajax/stok/stok-sql.php?islem=detayli-filtrele",
                type:"GET",
                data:{
                    malzeme_adi:malzeme_adi,
                    ehu_onay_durum:ehu_onay_durum,
                    mkys_kodu:mkys_kodu,
                    istek_durum:istek_durum,
                    tasinir_no:tasinir_no,
                    malzeme_tipi:malzeme_tipi,
                    malzeme_turu:malzeme_turu,
                    baslangic_tarih:baslangic_tarih,
                    bitis_tarih:bitis_tarih
                },
                success:function (getVeri){
                    $(".malzeme-adi").val();
                    $(".ehu-onay-durum").val();
                    $(".mkys-kodu").val();
                    $(".istek-durum").val();
                    $(".tasinir-no").val();
                    $(".malzeme-tipi").val();
                    $(".malzeme-turu").val();
                    $(".baslangic-tarih").val();
                    $(".bitis-tarih").val();


                    $(".gelecek-tablo").html("");
                    $(".gelecek-tablo").html(getVeri);
                }
            });
        });

        $("#ilac-listesi-getir").click(function (){
            $.get("ajax/stok/stok-modal.php?islem=ilac-adi-getir",function (getModal){
                $("#get-modals").html(getModal);
            });
        });

    </script>
<?php }
if ($islem == "depo-stoklari"){
?>
    <br>
    <script>
        $(document).ready( function () {
            $('.depodaki-urunler-detayli-arama').DataTable( {
                "responsive":true,
                searching:false,
                scrollX:true,
                scrollY:"35vh",
                info:false,
                paging:false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });
    </script>
        <div class="row">
            <div class="col-6 px-2">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label-sm">Malzeme Adı</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm drug-name" aria-describedby="basic-addon2" disabled>
                            <div class="input-group-append">
                                <button class="btn btn-outline-warning btn-sm ilac-adi-getir" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-1">
                    <label class="col-sm-4 col-form-label-sm">Barkod</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm barkod-no">
                    </div>
                </div>
                <div class="form-group row mt-1">
                    <label class="col-sm-4 col-form-label-sm">Depo Adı</label>
                    <div class="col-sm-4">
                        <select class="form-select form-select-sm warehouse-name">
                            <option value="Seçiniz...">Seçiniz...</option>
                            <?php
                            $depo_adi_getir = verilericoklucek("SELECT warehouse_name,id FROM warehouses WHERE status=1");
                            foreach ($depo_adi_getir as $depo_adi) {
                            ?>
                            <option value="<?php echo $depo_adi["id"];?>"><?php echo $depo_adi["warehouse_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-6 px-2">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label-sm">Firma Adı</label>
                    <div class="col-sm-4">
                        <select class="form-select form-select-sm company-name">
                            <option value="Seçiniz...">Seçiniz...</option>
                            <?php
                            $firma_getir = verilericoklucek("SELECT company_name,id FROM companies WHERE status=1");
                            foreach ($firma_getir as $firma) {
                                ?>
                                <option value="<?php echo $firma["id"];?>"><?php echo $firma["company_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row mt-1">
                    <label class="col-sm-4 col-form-label-sm">Stok Türü</label>
                    <div class="col-sm-4">
                        <select class="form-select form-select-sm stok-tur">
                            <option value="Seçiniz...">Seçiniz...</option>
                            <option value="28464">İLAÇ</option>
                            <option value="28462">SARF</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer mx-5 px-4">
                    <button type="button" class="btn btn-sm filtrelermisin"  data-dismiss="modal" style="background-color: #112D4E; color: white;">Filtrele</button>
                </div>
            </div>
        </div>
    <br>
    <div class="row">
        <div class="col-12 depo-filtrelenmis-hali">
            <font size="2">
            <table class="table table-bordered table-sm table-hover px-2 depodaki-urunler-detayli-arama"  style="background: white; width: 100%;" >
                <thead class="table-light">
                <tr>
                    <th>Ürün Adı</th>
                    <th>Barkod</th>
                    <th>Depo Adı</th>
                    <th>Depoya Giriş Tarihi</th>
                    <th>Getiren Firma</th>
                    <th>Getiren Bayi</th>
                    <th>Anlık Ürün Miktarı</th>
                    <th>Lot No</th>
                    <th>Stok Türü</th>
                    <th>Son Kullanma Tarihi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $istekler = verilericoklucek("SELECT
    srm.stock_name as urun_adi,
    srm.barcode as barkod,
    srm.lot_number as lot_no,
    srm.expiration_date as son_kullanma_tarih,
    srm.dealership_number as bayi_no,
    srm.insert_datetime as giris_tarihi,
    srm.stock_amount as urun_miktari,
    srm.stock_type as stok_turu,
    depo.warehouse_name as depo_adi,
    c.company_name as firma_ad
FROM stock_receipt_move as srm
         INNER JOIN warehouses as depo ON srm.warehouse_id = depo.id
         INNER JOIN companies c on srm.company_id = c.id
WHERE srm.status=1");

                foreach ($istekler as $item) {
                    ?>
                    <tr id="<?php echo $item["id"]?>" class="stock-id">
                        <td><?php echo $item["urun_adi"]?></td>
                        <td><?php echo $item["barkod"]?></td>
                        <td><?php echo $item["depo_adi"]?></td>
                        <td><?php echo $item["giris_tarihi"]?></td>
                        <td><?php echo $item["firma_ad"]?></td>
                        <td><?php echo $item["bayi_no"]?></td>
                        <td><?php echo $item["urun_miktari"]?></td>
                        <td><?php echo $item["lot_no"]?></td>
                        <td><?php echo islemtanimgetirid($item["stok_turu"])?></td>
                        <td><?php echo $item["son_kullanma_tarih"]?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>

        $(".filtrelermisin").click(function (){
            var drug_name = $(".drug-name").val();
            var barkod_no = $(".barkod-no").val();
            var warehouse_name = $(".warehouse-name").val();
            var company_name = $(".company-name").val();
            var stok_tur = $(".stok-tur").val();
            $.ajax({
                url:"ajax/stok/stok-sql.php?islem=depo-filtre",
                type:"GET",
                data: {
                    ilac_adi:drug_name,
                    barkod:barkod_no,
                    depo_id:warehouse_name,
                    firma_id:company_name,
                    stok_tur:stok_tur
                },
                success:function (getVeri){
                    $(".depo-filtrelenmis-hali").html("");
                    $(".depo-filtrelenmis-hali").html(getVeri);
                }
            });
        });

        $(".ilac-adi-getir").click(function (){
            $.get("ajax/stok/stok-modal.php?islem=ilac-listesi-getir-modal",function (getModal){
               $("#get-modals").html(getModal);
            });
        });

    </script>
<?php }

if ($islem == "iade-hareketleri"){
?>
    <script>
        $(document).ready( function () {
            $('.iade-detay-filtre').DataTable( {
                "responsive":true,
                searching:false,
                scrollX:true,
                scrollY:"35vh",
                info:false,
                paging:false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });
    </script>
    <br><br>
    <div class="row">
        <div class="col-3 px-2">
            <div class="form-group row">
                <label class="col-sm-5 col-form-label-sm">Malzeme Adı</label>
                <div class="col-sm-7">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm ilac-yazilacak-input" aria-describedby="basic-addon2" disabled>
                        <div class="input-group-append">
                            <button class="btn btn-outline-warning btn-sm ilac-adi-getir" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 px-2">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label-sm">&nbsp;&nbsp;Birim Adı</label>
                <div class="col-sm-8">
                    <select class="form-select form-select-sm birim-adi">
                        <option value="Seçiniz...">Seçiniz...</option>
                        <?php
                        $birimleri_getir = verilericoklucek("select id,department_name from units WHERE status=1");
                        foreach ($birimleri_getir as $birim){
                        ?>
                        <option value="<?php echo $birim["id"];?>"><?php echo $birim["department_name"];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-3 px-2">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label-sm">Baş. Tarih</label>
                <div class="col-sm-8">
                    <input type="date"  class="form-control form-control-sm bas-tarih">
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label-sm">Bit. Tarih</label>
                <div class="col-sm-8">
                    <input type="date"  class="form-control form-control-sm bit-tarih">
                </div>
            </div>
        </div>
        <div class="modal-footer px-2">
            <button type="button" class="btn btn-sm filter-iade-button"  data-dismiss="modal" style="background-color: #112D4E; color: white;">Filtrele</button>
        </div>
        <div class="col-12 again-table mt-1">
            <font size="2">
            <table class="table table-bordered  table-hover px-2 nowrap display w-100 iade-detay-filtre">
                <thead class="table-light">
                <tr>
                    <th>Mazleme Adı</th>
                    <th>Barkod</th>
                    <th>Depo Adi</th>
                    <th>Firma Ad</th>
                    <th>Bayi No</th>
                    <th>İade Edilen Miktar</th>
                    <th>İade Sebepleri</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $istekler = verilericoklucek("
                SELECT warehouses.warehouse_name as warehouse_name
                ,companies.company_name as company_name
                ,stock_card.stock_name as stock_name
                ,stock_returns.* FROM stock_returns
                INNER JOIN warehouses on stock_returns.warehouse_id = warehouses.id
                INNER JOIN companies on stock_returns.company_id = companies.id
                INNER JOIN stock_card on stock_returns.stock_cardid=stock_card.id
                WHERE  stock_returns.status=1");
                foreach ($istekler as $item) {
                    ?>
                    <tr id="<?php echo $item["id"]?>">
                        <td><?php echo $item["stock_name"]?></td>
                        <td><?php echo $item["stock_barcode"]?></td>
                        <td><?php echo $item["warehouse_name"]?></td>
                        <td><?php echo $item["company_name"]?></td>
                        <td><?php echo $item["dealership_number"]?></td>
                        <td><?php echo $item["number_of_returns"]?></td>
                        <td><?php echo $item["reason_of_returns"]?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>

        $(".ilac-adi-getir").click(function (){
            $.get("ajax/stok/stok-modal.php?islem=get-drugs",function (getModal){
                $("#get-modals").html(getModal);
            });
        });

        $(".filter-iade-button").click(function (){
            var malzeme_adi = $(".ilac-yazilacak-input").val();
            var birim_adi = $(".birim-adi").val();
            var bas_tarih = $(".bas-tarih").val();
            var bit_tarih = $(".bit-tarih").val();

            $.ajax({
                url:"ajax/stok/stok-sql.php?islem=iade-detay-getir",
                type:"GET",
                data:{
                    malzeme_adi:malzeme_adi,
                    birim_adi:birim_adi,
                    bas_tarih:bas_tarih,
                    bit_tarih:bit_tarih
                },
                success:function (getVeri){
                    $(".again-table").html(getVeri);
                }
            });
        });

    </script>
<?php } ?>