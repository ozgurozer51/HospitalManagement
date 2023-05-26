<?php
include "../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem = $_GET['islem'];
session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
?>

        <table id="hasta-borc-tablosu" class="table table-bordered table-sm table-hover nowrap display w-100 mt-2">
            <thead>
            <tr>
                <th>işlem no</th>
                <th>protok. no</th>
                <th>hizmet</th>
                <th>ödeme durum</th>
                <th>gelir adı</th>
                <th>miktar</th>
                <th>fiyat</th>
                <th>hizmet bedeli</th>
                <th>t. tutar</th>
                <th>gelir kodu</th>
                <th>m.det. turu</th>
            </tr>
            </thead>

            <tbody>
            <?php $hastaid = $_POST['tc_kimlik'];
            $toplam = 0;
            $toplam=   12; //hastaborcsorgula($hastaid);
            $sql = verilericoklucek("select * from patient_prompts where patient_tc='$_POST[tc_kimlik]' and status='1'");
            foreach ($sql as $rowa){
                $toplam1 = ($rowa["piece"] * $rowa["fee"]) + $rowa["service_fee"];
                
                if ($rowa["payment_completed"] == 0) { $nettoplam = $nettoplam + $toplam1; } ?>

                <tr <?php if ($rowa["payment_completed"] != 1) { $hizmet_toplam += $rowa["service_fee"]; echo 'istem-id="'.$rowa["id"].'"';  echo "class='HIZMET_MALZEME_SECIM_YAP'"; echo 'hizmet-malzeme-net-tutar="'.(($rowa["piece"]*$rowa["fee"] + $rowa["service_fee"]*$rowa["piece"])).'"';  echo 'hizmet-net-tutar="'.$rowa["service_fee"].'"'; echo 'fiyat-net-tutar="'.$rowa["piece"]*$rowa["fee"].'"'; echo 'adet-sayi="'.$rowa["piece"].'"'; }   ?> >
                    <td><?php echo $rowa["id"]; ?></td>
                    <td><?php echo $rowa["protocol_number"]; ?></td>
                    <td><?php echo $rowa["request_name"]; ?></td>
                    <td><?php if ($rowa["payment_completed"] == 1) { ?><i class="fas fa-check-circle" style="color:green;">ödendi</i><?php } else { ?><i class="fas fa-times-circle" style="color:red;">ödenmedi</i><?php } ?></td>
                    <td><?php echo $rowa["name_surname"]; ?></td>
                    <td><?php echo $rowa["piece"]; ?></td>
                    <td><?php echo $rowa["fee"]; ?> ₺</td>
                    <td><?php echo $rowa["service_fee"]; ?> ₺</td>
                    <td><?php echo $toplam1; ?></td>
                    <td></td>
                    <td><?php echo $rowa["department_name"]; ?>rht</td>
                </tr>

            <?php } ?>

            </tbody>

        </table>


<input type="hidden" id="hitmet_toplam_al" value="<?php echo $hizmet_toplam; ?>">
<input type="hidden" id="HASTA_PROTOKOL_NO" value="<?php echo $_POST['protokol_no']; ?>">

<div id="istemleri_biriktir"></div>


<div class="modal fade" id="odemeal" data-bs-backdrop="static" role="dialog" aria-labelledby="odemeal" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">tahsilat bilgisi</h4>
                <button type="button" class="close btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

          <form action="javascript:void(0);" id="formvezne">
          <input  type="hidden" id="HASTA_TC_KIMLIK" name="patient_id" value="<?php echo $_POST['tc_kimlik']; ?>" id="istemid">
          <input  type="hidden" name="receipt_number" value="<?php echo $toplam; ?>">
          <input  type="text" name="tutari"  id="araode1">
          <input  type="hidden" name="id"    id="idal"/>
          <input  type="hidden" id="PROTOKOL_NO_GET_VAL"   value="<?php echo $_POST['protokol_no']; ?>" />
          <input type="hidden" name="treasurerid" value="<?php echo $kullanici_id; ?>">

             <div class="row mt-1">
                 <div class="col-md-3">
                     <label for="example-text-input" class=" col-form-label">makbuz no:</label>
                 </div>
                 <div class="col-md-9">
                     <div id="makbuznogetir"></div>
                 </div>
             </div>


              <div class="row mt-1">
                  <div class="col-md-3">
                      <label for="example-text-input" class="col-form-label">makbuz tarihi:</label>
                  </div>
                  <div class="col-md-9">
                      <?php // $tarih = explode(' ', $istemler['request_date']); ?>
                      <input class="form-control px-2" type="text" disabled value="<?php echo $simdikitarih; ?>" id="example-text-input">
                      <input class="form-control px-2" type="hidden" name="receipt_datetime" value="<?php echo $simdikitarih; ?>" id="example-text-input">
                  </div>
              </div>

              <div class="row mt-1">
                  <div class="col-md-3">
                      <label for="KASA_SEC" class="form-label">kasa tipi:</label>
                  </div>
                  <div class="col-md-9">
                          <select id="KASA_SEC" name="safe_id" class="form-select">
                              <option class="bg-danger text-white" selected disabled>kasa seçiniz</option>
                              <?php $sql = verilericoklucek("select *
                                    from safe
                                             inner join users_outhorized_safes on users_outhorized_safes.safe_id = safe.id
                                    where users_outhorized_safes.userid = $kullanici_id and safe.status='1'");

                              if(!isset($sql)){
                                 ?> <option class="bg-danger text-white" disabled>Henüz Kasa Yetkiniz Bulunmamaktadır</option> <?php
                              }

                              foreach ($sql as $rowa) { ?>
                                  <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["safe_name"]; ?></option>
                              <?php } ?>
                          </select>
                  </div>
              </div>


              <div class="row mt-1">
                  <div class="col-md-3">
                      <label for="example-text-input" class="col-form-label">açıklama:</label>
                  </div>
                  <div class="col-md-9">
                      <textarea class="form-control" rows="3" type="text" name="explanation" id="example-text-input"></textarea>
                  </div>
              </div>

              <div class="row mt-1">
                  <div class="col-md-3">
                      <label for="DOVUZ_TURU" class="col-form-label">dövüz türü:</label>
                  </div>
                  <div class="col-md-9">
                      <select class="form-select" id="DOVUZ_TURU" required>
                          <option value="2" selected>varsayılan (tl)</option>
                          <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='DOVUZ_TURU'");
                          foreach ($sql as $rowa) { ?>
                              <option value="<?php echo $rowa["definition_supplement"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                          <?php } ?>
                      </select>
                  </div>

              </div>

              <div class="row mt-1">
                  <div class="col-md-3">
                      <label for="example-text-input" class="col-form-label">döviz kuru:</label>
                  </div>
                  <div class="col-md-9">
                    <div  id="dovuzkuru">
                        <input class="form-control" type="text" disabled>
                    </div>
                      <input class="form-control" type="hidden" disabled id="dovuz_kuru" name="dovuz_kuru">
                  </div>
              </div>

              <div class="row mt-1">
                  <div class="col-md-3">
                      <label class="col-form-label">ödeme türü seçiniz:</label>
                  </div>
                  <div class="col-md-9">
                      <select id="ODEME_TURU" class="form-select" required>
                          <option class="bg-danger text-white" disabled selected>seçiniz</option>
                          <option value="1">nakit ödeme</option>
                          <option value="2">kredi kartı i̇le ödeme</option>
                          <option value="3">senetle ödeme</option>
                      </select>
                  </div>

              </div>

              <div class="row mt-1">
                      <div class="col-md-6">
                          <div id="toplamtutar"></div>
                      </div>
                      <div class="col-md-6">
                          <h4>ara tutar: <b id="araode"></b> ₺</h4>
                      </div>
              </div>

          <div class="col-md-12" id="ftrtutar"></div>


         </form>


          <div class="modal-footer">
              <button type="button" class="btn btn-success btn-lg btn-vezne" id="vezne-odeme-onay" disabled><i class="fas fa-book"></i><b>ödeme i̇şlemini onayla</b></button>
              <button type="button" data-bs-dismiss="modal" class="btn btn-lg btn-danger odeme-islemi-kapat"><b>kapat</b></button>
          </div>

            </form>

    </div>
</div>
    </div>
</div>


<script>
    $('#buton-evreni').html('');

    $(document).ready(function () {
        var L=0;
        var PROTOKOL_NO =  $('#HASTA_PROTOKOL_NO').val();
        var TUM_BORC    =  $("[hasta-toplam-borc='"+PROTOKOL_NO+"']").html();


        $("#info-tum-toplam").html(TUM_BORC);

       var Table_dom = $('#hasta-borc-tablosu').DataTable({
            "responsive":true,
            "scrollY":'55vh',
            "scrollX":false,
            "paging":false,
           "info": false,

            dom: 'Bf<"clear">rtip',

            buttons: [{
                    text: '<img src="assets/icons/Select-All.png" width="50px"> <br> Tümünü Seç',
                    className:'btn btn btn-light',
                    titleAttr:'Tüm Borc Ödemesi İçin Hepsini Seçiniz...',

                    action: function ( e, dt, node, config ) {
                        var PROTOKOL_NO = $('#HASTA_PROTOKOL_NO').val();
                        if (L==0) {
                            $('[name="istemleri-al[]"]').remove();
                            $('.HIZMET_MALZEME_SECIM_YAP').addClass('text-white');
                            $('.HIZMET_MALZEME_SECIM_YAP').css("background-color", "rgb(57, 180, 150)");
                            $('.HIZMET_MALZEME_SECIM_YAP').removeClass('text-dark bg-white');
                            $('#odemeler').prop("disabled" , false);
                            $('#info-ara-toplam').html($('#info-tum-toplam').html());
                            $('#info-hizmet-toplam').html($('#hitmet_toplam_al').val());
                            var ISTEM_ID = [];

                            $('.HIZMET_MALZEME_SECIM_YAP').each(function(i){
                                ISTEM_ID.push($(this).attr('istem-id'));
                            });

                            for(let i=0;i<ISTEM_ID.length;i++){
                                $('#istemleri_biriktir').append("<input type='hidden' id='" + ISTEM_ID[i] + "' name='istemleri-al[]' protokol-no='" + PROTOKOL_NO + "' value='" + ISTEM_ID[i] + "' />");
                            }
                            L=1;
                            
                        }else{
                            $('.HIZMET_MALZEME_SECIM_YAP').removeClass('text-white');
                            $('.HIZMET_MALZEME_SECIM_YAP').css("background-color", "rgb(255, 255, 255)");
                            $('.HIZMET_MALZEME_SECIM_YAP').addClass('text-dark');
                            $('#odemeler').prop("disabled" , true);
                            $('#info-ara-toplam').html('0');
                            $('#info-hizmet-toplam').html('0')
                            $('[name="istemleri-al[]"]').remove();
                            L=0;
                        }
                    }
                },

                { extend: "copy",  className:"btn btn-light" ,   text:'<img src="assets/icons/Copy.png" width="50px"> <br> Kopyala' },
                { extend: "excel", className:"btn btn-light" ,   text:'<img src="assets/icons/Excel.png" width="50px"> <br> Exel'},
                { extend: "print", className:"btn btn-light" ,   text:'<img src="assets/icons/Print.png" width="50px"> <br> Yazdır'},
                { extend: "pdf",   className:"btn btn-light" ,   text:'<img src="assets/icons/PDF.png" width="50px"> <br> PDF'},
                { extend: "colvis",className:"Sütun btn-light" , text:'<img src="assets/icons/Select-Column.png" width="50px"> <br> Sütun Görünürlüğü'},
            ],

            initComplete: function () {
                var btns = $('.dt-button');
                btns.addClass('btn');
                btns.removeClass('dt-button');
                Table_dom.button().container().appendTo($('#buton-evreni'));
            },

            "language": { "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json" },

        });

        var sum = 0;

        $('.area.on').each(function(index, elem) {
            sum += parseInt($(elem).attr('hizmet-malzeme-net-tutar'));
        });


            $("body").off("click", ".HIZMET_MALZEME_SECIM_YAP").on("click", ".HIZMET_MALZEME_SECIM_YAP", function(e){
            var ISTEM_ID = $(this).attr('istem-id');
            var PROTOKOL_NO = $('#HASTA_PROTOKOL_NO').val();

            if ($(this).css('background-color') != 'rgb(57, 180, 150)') {

                $(this).addClass("text-white");
                $(this).css("background-color", "rgb(57, 180, 150)");
                $('#odemeler').prop("disabled" , false);

                var ARA_TOPLAM = Number($('#info-ara-toplam').html());
                var HIZMET_MALZEME_NET_TUTAR = Number($(this).attr('hizmet-malzeme-net-tutar'));

                var sonuc = ARA_TOPLAM+HIZMET_MALZEME_NET_TUTAR;
                $('#info-ara-toplam').html(sonuc);
                $('#araode').html(sonuc)

                var ARA_TOPLAM_HIZMET = Number($('#info-hizmet-toplam').html());
                var HIZMET_NET_TUTAR = Number($(this).attr('hizmet-net-tutar'));
                var ADET_SAYI = Number($(this).attr('adet-sayi'));

                var sonuc2 = ARA_TOPLAM_HIZMET+HIZMET_NET_TUTAR;
                $('#info-hizmet-toplam').html(sonuc2);

                $('#istemleri_biriktir').append("<input type='hidden' id='" + ISTEM_ID + "' name='istemleri-al[]' protokol-no='" + PROTOKOL_NO + "' value='" + ISTEM_ID + "' />");

            }else{

                $(this).css("background-color", "rgb(255, 255, 255)");
                $(this).addClass("text-dark");
                $(this).removeClass("text-white");

                var ARA_TOPLAM = Number($('#info-ara-toplam').html());
                var HIZMET_MALZEME_NET_TUTAR = Number($(this).attr('hizmet-malzeme-net-tutar'));
                var sonuc = ARA_TOPLAM-HIZMET_MALZEME_NET_TUTAR;
                $('#info-ara-toplam').html(sonuc);
                $('#araode').html(sonuc);

                var ARA_TOPLAM_HIZMET = Number($('#info-hizmet-toplam').html());
                var HIZMET_NET_TUTAR = Number($(this).attr('hizmet-net-tutar'));
                var ADET_SAYI = Number($(this).attr('adet-sayi'));

                var sonuc2 = ARA_TOPLAM_HIZMET-HIZMET_NET_TUTAR;

                $('#info-hizmet-toplam').html(sonuc2);

                $('#' + ISTEM_ID).remove();

            }
        });


            $("body").off("click", "#odemeler").on("click", "#odemeler", function(e){

                var deg=$("#HASTA_TC_KIMLIK").val();
            $.ajax({
                type: "POST",
                url: "ajax/vezne/araislemler.php?islem=maknogetir",
                data: {"deg": deg},
                success: function (e) {
                    $("#makbuznogetir").html(e);
                }
            });
            $.ajax({
                type: "POST",
                url: "ajax/vezne/araislemler.php?islem=veznemodall",
                data: {"deg": deg},
                success: function (e) {
                    $("#toplamtutar").html(e);
                }
            });
        });

            $( "#KASA_SEC" ).change(function() {
            if($("#ODEME_TURU").val() > 0 ) {
                $('#vezne-odeme-onay').prop("disabled", false);
            }
        });



                $( "#ODEME_TURU" ).change(function() {
                if($("#KASA_SEC").val() > 0 ) {
                $('#vezne-odeme-onay').prop("disabled", false);
            }
        });



                $( "#DOVUZ_TURU" ).change(function() {
            var dovuzekkod = $(this).val();
            $.ajax({
                type: "POST",
                url: "ajax/vezne/araislemler.php?islem=dovuztipi",
                data: {"dovuzekkod": dovuzekkod},
                success: function (e) {
                    $("#dovuzkuru").html(e);
                }
            })
        });


           $( "#DOVUZ_TURU" ).change(function() {
           var dovuzekkod = $(this).val();
            var ttutar=$("#toplamdeger").val();
            var araode=document.getElementById("resultID").innerHTML;

            $.ajax({
                type: "POST",
                url: "ajax/vezne/araislemler.php?islem=faturatutar",
                data: {"dovuzekkod": dovuzekkod,"ttutar": ttutar,"araode":araode},
                success: function (e) {
                    $("#ftrtutar").html(e);
                }
            })
        });


            $("body").off("click", ".btn-vezne").on("click", ".btn-vezne", function(e){
            var gonderilenform = $("#formvezne").serialize();
            var ODEME_TURU = $('#ODEME_TURU').val();
            var KASA_ID  = $('#KASA_SEC').val();
            var DOVUZ_TURU = $('#DOVUZ_TURU').val();
            var TC_KIMLIK = $('#HASTA_TC_KIMLIK').val();
            var PROTOKOL_NO = $('#PROTOKOL_NO_GET_VAL').val();

            var ISTEM_ID = [];
            $("input[name='istemleri-al[]']").off().each(function () {
                ISTEM_ID.push($(this).val());
            });

            var ARA_TOPLAM_2 = Number($('#info-ara-toplam').html());
            var TUM_TOPLAM_2 = Number($('#info-tum-toplam').html());

            if(ARA_TOPLAM_2==TUM_TOPLAM_2){
                var ISLEM_TURU = 1;
            }else{
                var ISLEM_TURU = 0;
            }

            gonderilenform += "&patient_serviceid=" + ISTEM_ID;
            gonderilenform += "&safe_id=" + KASA_ID;
            gonderilenform += "&currency_type=" + DOVUZ_TURU;
            gonderilenform += "&request_type=" + ISLEM_TURU;

            document.getElementById("araode1").value="";

            var odemeturu = $('#ODEME_TURU').val();

            document.getElementById("formvezne").reset();
            $('#vezne-odeme-onay').prop("disabled", true);

            $.ajax({
                type:'POST',
                url:'ajax/vezne/araislemler.php?islem=vezneekle&odemeturu='+odemeturu,
                data:gonderilenform,
                success:function(e){
                    $("#sonucyaz").html(e);
                    $('.odeme-islemi-kapat').trigger("click");


                    setTimeout(() => {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax/vezne/vezne-borc-detay-sorgula.php',
                            data: { tc_kimlik:TC_KIMLIK , protokol_no:PROTOKOL_NO },
                            success: function (e2) {
                                $('.detayli_islem').html(e2);
                            }
                        });
                    }, "1000");

                    $('#borclu-hastalar').DataTable().ajax.reload();
                }
            });

        });


    });

</script>

<div id="deneme123"></div>