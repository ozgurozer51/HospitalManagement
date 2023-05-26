<?php

include '../controller/fonksiyonlar.php';

//if (!is_numeric($_GET['HASTA_PROTOKOL_NO'])) { ?>
<!--         <script type="text/javascript">-->
<!--             alertify.alert("UYARI", "seçim yapmadınız!!");-->
<!--             $('.modal-backdrop').remove();-->
<!--         </script>-->
<!--     --><?php //exit(); } ?>

     <form action="javascript:void(0);" id="hasta_randevu_islemi_form">
         <div class="modal-dialog" id="personel_ameliyat_takvimi_dom" style="width:99%; max-width: 99%;">

             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title"><?php echo $_GET['HASTA_ADI']; ?>  Kişisine Malzeme Talebinde Bulun</h4>
                     <button type="button" class="close btn-danger" data-dismiss="modal">X</button>
                 </div>

                 <div class="modal-body">

                     <form action="javascript:void(0);" id="ameliyathane_malzeme_talebi">

                         <div class="row">
                             <div class="col-md-4 col-xs-4">

                                 <form action="javascript:void(0);" class="MALZEME_SECME_FORMU">
                                     <label for="MALZEME_TIPI" class="form-label">Malzeme Tipi:</label>
                                     <select class="form-select" id="MALZEME_TIPI" title="Malzeme Tipini Belirtiniz...">
                                         <option value="null" selected disabled class="bg-danger text-white">Seçiniz</option>

                                         <?php $mtipi = verilericoklucek("SELECT * FROM transaction_definitions where definition_type='MALZEME_TIPI'");
                                         foreach ($mtipi as $malzemetipi) { ?>
                                             <option value="<?php echo $malzemetipi["ID"]; ?>"><?php echo $malzemetipi["TANIM_ADI"]; ?></option>
                                         <?php } ?>
                                     </select>

                                     <div class="row">

                                         <div class="col-md-6 col-xs-6">
                                             <label for="MIKTAR_TIPI" class="form-label">Miktar Tipi:</label>

                                             <select class="form-select" id="MIKTAR_TIPI">
                                                 <option class="bg-danger text-white" disabled selected>Seçiniz</option>

                                                 <?php $olcukodu = verilericoklucek("SELECT * FROM transaction_definitions where definition_type='STOK_OLCU_KODU'");
                                                 foreach ($olcukodu as $olcuk) { ?>
                                                     <option value="<?php echo $olcuk["ID"]; ?>"> <?php echo $olcuk["TANIM_ADI"]; ?> </option>
                                                 <?php } ?>

                                             </select>
                                         </div>

                                         <div class="col-md-6 col-xs-6">
                                             <label class="form-label">Miktar</label>
                                             <input class="form-control" type="text" id="MIKTAR" name="MIKTAR" placeholder="Miktar" title="Miktar Bilgisi Giriniz...">
                                         </div>
                                     </div>

                                     <label for="MALZEME_BILGISI" class="form-label">Malzeme Bilgisi:</label>
                                     <textarea class="form-control" id="MALZEME_BILGISI" name="MALZEME_BILGISI" rows="3" placeholder="Malzeme Bilgisi" title="Malzeme Bilgisini Belirtiniz..."></textarea>

                                     <label for="STOK_ISTEK_HEKIM_ACIKLAMA" class="form-label">Talep Notu:</label>
                                     <textarea class="form-control" id="STOK_ISTEK_HEKIM_ACIKLAMA" name="STOK_ISTEK_HEKIM_ACIKLAMA" rows="3" placeholder="Stok İstek Açıklama" title="İstekle ilgili hekimin açıklama bilgisidir..."></textarea>

                                     <label for="MALZEME_TALEP_ACILIYET_SEVIYESI" class="form-label">Malzeme Talep Aciliyet Seviyesi:</label>
                                     <select class="form-select" id="MALZEME_TALEP_ACILIYET_SEVIYESI" name="MALZEME_TALEP_ACILIYET_SEVIYESI" title="Malzeme Talep Aciliyet Seviyesi Belirtiniz...">
                                         <option value="Normal">Normal</option>
                                         <option value="Acil">Acil</option>
                                         <option value="Çok Acil">Çok Acil</option>
                                     </select>

                                    <button type="button" id="MALZEME_KAYIT" class="btn btn-primary mt-3" disabled> Kaydet</button>

                             </div>
                             <div style="height: 100% !important;" class="col-md-8 col-xs-8">
                                 <div class="card mt-2">
                                     <div class="card-header bg-primary p-2 text-white"><strong>Malzeme Seç</strong></div>

                                     <div class="malzeme_getir" id="malzeme_getir">

                                     <?php $MALZEME_TIPI = $_GET['MALZEME_TIPI'];
   if (!is_numeric($MALZEME_TIPI)) { exit(); } ?>

   <table id="AMELİYAT_MALZEME_LİSTESİ" class="table  table-bordered table-hover " style="background:white;width: 100%;">

       <thead>
       <tr>
           <th scope="col">Malzeme Adı</th>
           <th scope="col">Atc Adı</th>
           <th scope="col">Atc Kodu</th>
           <th scope="col">Reçete Türü</th>
           <th scope="col">Üretici Marka</th>
           <th scope="col">Mkys Malzeme Kodu</th>
       </tr>
       </thead>

       <tbody>
       <?php $sql= verilericoklucek("SELECT * FROM stock_card WHERE status='1'");
       foreach ($sql as $rowa2) { ?>
           <tr class="MALZEME_SEC" data-id="<?php echo $rowa2["id"];?>" style="cursor: pointer;">
               <td class="MALZEME_ADI">      <?php echo $rowa2["stock_name"]; ?></td>
               <td class="ATC_ADI">          <?php echo $rowa2["atc_name"]; ?></td>
               <td class="ATC_KODU">         <?php echo $rowa2["atc_code"]; ?></td>
               <td class="RECETE_TURU">      <?php echo $rowa2["prescription_type"]; ?></td>
               <td class="URETICI_MARKA">    </td>
               <td class="MKYS_MALZEME_KODU"></td>
           </tr>
       <?php } ?>
       </tbody>

       <tfoot>
       <tr>
           <th scope="col">Malzeme Adı</th>
           <th scope="col">Atc Adı</th>
           <th scope="col">Atc Kodu</th>
           <th scope="col">Reçete Kodu</th>
           <th scope="col">Üretici Marka</th>
           <th scope="col">Mkys Malzeme Kodu</th>
       </tr>
       </tfoot>

   </table>

   <script>
       $(document).ready(function () {

           $('#AMELİYAT_MALZEME_LİSTESİ').DataTable({
               "responsive": true,
               "paging": false,
               "scrollY": 300,
               "scrollX": false,
               "autoWidth": true,
               "language": {
                   "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
               },
               initComplete: function () {
                   this.api().columns().every(function () {
                       var column = this;
                       var select = $('<select><option value=""></option></select>')
                           .appendTo($(column.footer()).empty())
                           .on('change', function () {
                               var val = $.fn.dataTable.util.escapeRegex(
                                   $(this).val()
                               );

                               column
                                   .search(val ? '^' + val + '$' : '', true, false)
                                   .draw();
                           });

                       column.data().unique().sort().each(function (d, j) {
                           select.append('<option value="' + d + '">' + d + '</option>')
                       });
                   });
               }
           });

//Seçilen Malzemeyi Ekle------------------------------------------------------------------------------------------------
           $(".MALZEME_SEC").off().on("click", function () {

               if ($(this).css('background-color') != 'rgb(57, 180, 150)') {
                   $('.malzeme-sec-kaldir').removeClass("text-white");
                   $('.malzeme-sec-kaldir').removeClass("malzeme-sec-kaldir");
                   $('.MALZEME_SEC').css({"background-color": "rgb(255, 255, 255)"});
                   $(this).css({"background-color": "rgb(57, 180, 150)"});
                   $(this).addClass("text-white malzeme-sec-kaldir");
                   $('#MALZEME_KAYIT').removeAttr('disabled');
               } else {
                   $(this).css({"background-color": "rgb(255, 255, 255)"});
                   $(this).removeClass("text-white");
                   $(this).removeClass("malzeme-sec-kaldir");
                   $('#MALZEME_KAYIT').attr('disabled', 'disabled');
               }

           });

//Secilem Malzeme + Girilin Bilgi clk button----------------------------------------------------------------------------
           $("#MALZEME_KAYIT").off().on("click", function () {

               $(this).prop("disabled" , true);

               $('#select_item_send').prop("disabled" , false);

               var MALZEME_BILGILERI = $('.malzeme-sec-kaldir').attr("data-id");
               var STOK_ISTEK_HEKIM_ACIKLAMA = $('#STOK_ISTEK_HEKIM_ACIKLAMA').val();
               var OLCU_KODU = $('#OLCU_KODU').val();
               var MIKTAR = $('#MIKTAR').val();
               var MALZEME_BILGISI = $('#MALZEME_BILGISI').val();
               var MALZEME_TALEP_ACILIYET_SEVIYESI = $('#MALZEME_TALEP_ACILIYET_SEVIYESI').val();
               var MIKTAR_TIPI = $('#MIKTAR_TIPI').val();
               var HASTA_PROTOKOL_NO = $(".HASTA_SEC:checked").attr('data-id-4');

               $('textarea[name=STOK_ISTEK_HEKIM_ACIKLAMA').val('');
               $('select[name=OLCU_KODU').val('');
               $('input[name=MIKTAR').val('');
               $('textarea[name=MALZEME_BILGISI').val('');
               $('select[name=MALZEME_TALEP_ACILIYET_SEVIYESI').val('');

               var MALZEME_ADI = $(".malzeme-sec-kaldir").find(".MALZEME_ADI").html();
               var URETICI_MARKA = $(".malzeme-sec-kaldir").find(".URETICI_MARKA").html();
               var ATC_ADI = $(".malzeme-sec-kaldir").find(".ATC_ADI").html();

               $(".malzeme_kaydet_listele").append("<tr class='IDD' name='ID' id='" + MALZEME_BILGILERI + "'> <input type='hidden' " +
                   "name='malzemeler[]' stok-istek-hekim-aciklama='" + STOK_ISTEK_HEKIM_ACIKLAMA + "' hasta-protokol-no='" + HASTA_PROTOKOL_NO + "' malzeme-bilgisi='" + MALZEME_BILGISI + "' " +
                   "miktar='" + MIKTAR + "' malzeme-aciliyet-seviyesi='" + MALZEME_TALEP_ACILIYET_SEVIYESI + "' miktar-tipi='" + MIKTAR_TIPI + "' olcu-kodu='" + OLCU_KODU + "'" +
                   "value='" + MALZEME_BILGILERI + "' /> <td> " + MALZEME_ADI + "</td>   <td> " + URETICI_MARKA + "</td>  <td> " + ATC_ADI + "</td>  <td> <button id='delete_malzeme' class='btn btn-danger delete' data-id='" + MALZEME_BILGILERI + "'  type='button'>Sil</button> </td> <td> " + MIKTAR + " </td>  <td>" + MALZEME_TALEP_ACILIYET_SEVIYESI + "</td> </tr>");

           });

           $(document).on('click', '#delete_malzeme', function () {
               $(this).closest("tr").remove();
               var MALZEME_ID = [];

               $("input[name='malzemeler[]']").off().each(function () {
                   MALZEME_ID.push($(this).val());
               });

             if(MALZEME_ID == ''){
                 $("#select_item_send").prop("disabled" , true );
             }

           });

       });
   </script>

                                     </div>

                                 </div>
                             </div>
                         </div> <!--Row Bitiş--------------->
                     </form>

                     <div class="card mt-2">
                         <div class="card-header bg-success p-2 text-white"><strong>Kayıt Edilecek Malzeme Listesi</strong></div>

                         <table class="table table-bordered table-hover bg-white w-auto">
                             <thead>
                             <tr>
                                 <th>Malzeme Adı</th>
                                 <th>Atc Adı</th>
                                 <th>Üretici Marka</th>
                                 <th>İşlem</th>
                                 <th>Miktar</th>
                                 <th>Malzeme Talep Aciliyet Seviyesi</th>
                             </tr>
                             </thead>
                             <tbody class="malzeme_kaydet_listele" style="cursor: pointer;">

                             </tbody>
                         </table>

                     </div>

                     <input type="hidden" id="AMELIYATHANE_ID_MALZEME_TALEBI" name="AMELIYATHANE_ID" value="<?php echo $AMELIYATHANE_ID; ?>">

               </form>
                 </div>

                 <div class="modal-footer">
                     <button class="btn btn-outline-danger w-md justify-content-end" style="margin-bottom:4px" data-dismiss="modal" type="button">Kapat</button>
                     <button id="select_item_send" class="btn btn-success w-md justify-content-end" style="margin-bottom:4px" data-dismiss="modal" type="button">Gönder</button>
                    <input type="hidden" id="birim-id" value="<?php echo $_GET['BIRIM_ID']; ?>"/>
                 </div>

             </div>
         </div>
     </form>


         <script>
             $(document).ready(function() {
//Ameliyat Malzeme Talebi İçin Modal------------------------------------------------------------------------------------
                 $("#MALZEME_TIPI").change(function () {
                     var MALZEME_TIPI = $(this).val();
                     var BIRIM_ID = $('#birim-id').val();

                     $.get("ajax/ameliyathane-modul/ameliyathanelistesi.php?islem=ameliyat_malzeme_listesi_filtre", { MALZEME_TIPI: MALZEME_TIPI , BIRIM_ID:BIRIM_ID }, function (e) {
                         $('.malzeme_getir').html(e);
                     });
                 });

//ameliyat listesi malzeme bilgileri için post gönder--------------------------------------------------------------------
                 $("#select_item_send").off().on("click", function () {
                     $(this).prop("disabled" , true);
                     var MALZEME_ID = [];
                     var MIKTAR_TIPI = [];
                     var MIKTAR = [];
                     var MALZEME_BILGISI = [];
                     var STOK_ISTEK_HEKIM_ACIKLAMA = [];
                     var MALZEME_TALEP_ACILIYET_SEVIYESI = [];
                     var HASTA_PROTOKOL_NO = [];

                     $("input[name='malzemeler[]']").off().each(function () {
                         MALZEME_ID.push($(this).val());
                         MIKTAR_TIPI.push($(this).attr('miktar-tipi'));
                         MIKTAR.push($(this).attr('miktar'));
                         MALZEME_BILGISI.push($(this).attr('malzeme-bilgisi'));
                         STOK_ISTEK_HEKIM_ACIKLAMA.push($(this).attr('stok-istek-hekim-aciklama'));
                         MALZEME_TALEP_ACILIYET_SEVIYESI.push($(this).attr('malzeme-aciliyet-seviyesi'));
                         HASTA_PROTOKOL_NO.push($(this).attr('hasta-protokol-no'));
                     });

                     var AMELIYAT_ID = $("#OPERASYON_KODU").val();
                     var AMELIYATHANE_ID = $(".HASTA_SEC:checked").attr('ameliyat-masa-id');

                     if (MALZEME_ID != '') {
                         $.ajax({
                             type: 'POST',
                             url: 'ajax/ameliyathane-modul/ameliyathanefunction.php?islem=ameliyat_malzeme_talebi',
                             data: {
                                 AMELIYATHANE_ID: AMELIYATHANE_ID,
                                 AMELIYAT_ID: AMELIYAT_ID,
                                 MALZEME_ID: MALZEME_ID,
                                 MIKTAR_TIPI: MIKTAR_TIPI,
                                 MIKTAR: MIKTAR,
                                 MALZEME_BILGISI: MALZEME_BILGISI,
                                 STOK_ISTEK_HEKIM_ACIKLAMA: STOK_ISTEK_HEKIM_ACIKLAMA,
                                 MALZEME_TALEP_ACILIYET_SEVIYESI: MALZEME_TALEP_ACILIYET_SEVIYESI,
                                 HASTA_PROTOKOL_NO: HASTA_PROTOKOL_NO
                             },
                             success: function (e) {
                                 $("#sonucyaz").html(e);
                                 $('.malzeme_kaydet_listele').html('');
                             }
                         });
                     }
                 });

             });
         </script>
