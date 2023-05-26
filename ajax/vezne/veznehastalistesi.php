<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];

session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
?>

          <div class="row">
              <div class="col-md-5 col-xs-5 col-md-5 col-lg-5 col-xl-5">
                 <div class="card">
                      <div class="card-header bg-info text-white p-1" id="goster-dom" style="cursor:pointer;">borçlu hasta listesini tarihe göre filtrele (i̇stemlerin tarihi baz alınır)</div>
                     <div class="card-body" id="toggle-dom-super">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4 col-xs-4 col-lg-4 col-sm-4">
                                      <label for="baslangic_tarihi" class="form-labhel">başlangıç tarihi;</label>
                                      <input id="BASLANGIC_TARIHI" type="date" class="form-control"/>
                                  </div>
                                  <div class="col-md-4 col-xs-4 col-lg-4 col-sm-4">
                                      <label for="bitis_tarihi" class="form-labhel">bitiş tarihi;</label>
                                      <input id="BITIS_TARIHI" type="date" class="form-control"/>
                                  </div>
                                  <div class="col-md-4 col-xs-4 col-lg-4 col-sm-4">
                                      <label class="form-label text-white">ara:</label>
                                      <button id="BORCLU_FILTRE" class="btn btn-info form-control" disabled><i class="fas fa-search"></i> ara</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
         <div class="card">
          <div class="card-header bg-primary text-white p-2">borçlu hasta listesi</div>
       <div class="card-body">
       <table id="borclu-hastalar" class="table table-bordered table-sm table-hover nowrap display w-100" >
           <thead>
           <tr>
               <th>tc kimlik numarası</th>
               <th>protokol no</th>
               <th>adı</th>
               <th>toplam borç</th>
               <th>sosyal güvence</th>
               <th>bölüm adı</th>
           </tr>
           </thead>
           <tbody style="cursor:pointer;"> </tbody>
       </table>
      </div>
      </div>
      </div>

          <div class="col-md-7 col-xs-7 col-md-7 col-lg-7 col-xl-7">
              <div class="card">
                  <div class="card-header bg-primary text-white p-2">hizmet/malzeme listesi</div>
                  <div class="card-body detayli_islem">
                          <div style=" padding: 15%; " class="alert alert-warning alert-dismissible fade show  mb-0 text-center" role="alert">
                              <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                              <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                              <p>sol taraftan borçlu hasta listesine çift tıklayınız</p>
                          </div>
                  </div>
              </div>
          </div>
  </div>


<script type="text/javascript">
    $(document).ready(function(){
        $( "#BASLANGIC_TARIHI" ).change(function() { if ($( "#BITIS_TARIHI").val()){ $('#BORCLU_FILTRE').prop("disabled", false); } });
        $( "#BITIS_TARIHI" ).change(function() { if ($( "#BASLANGIC_TARIHI").val()){ $('#BORCLU_FILTRE').prop("disabled", false); }});

        var table =  $('#borclu-hastalar').DataTable({
            "processing": true,
            "serverSide": true,
            "scrollY": 450,
            "scrollX": false,
            "paging":true,
            'Visible': true,
            "responsive":true,
            "pageLength": 50,

            ajax:{
                url: 'ajax/vezne/vezne-server-side.php?islem=borclu-hastalar-listesi',
                type: 'POST',
                data: function(data){
                    data.baslangic_tarihi = $("#BASLANGIC_TARIHI").val();
                    data.bitis_tarihi = $("#BITIS_TARIHI").val();
                }
            },

            columns:[
                {data:'hasta_tc_numarasi'},
                {data:'hasta_protokol_no'},
                {data:'hasta_adi'},
                {data:'toplam_borc'},
                {data:'hasta_sosyal_guvence_adi'},
                {data:'birim_adi'},
            ],

            "aLengthMenu": [[10, 25, 50, 75,100,500, 1000 ,2000],[10, 25, 50, 75,100,500, 1000 ,2000]],

            "columnDefs": [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": [4]
                },
                {
                    "searchable": false,
                    "targets": [1]
                },
                {
                    "searchable": false,
                    "targets": [2]
                },
                {
                    "searchable": false,
                    "targets": [3]
                },
                {
                    "searchable": false,
                    "targets": [5]
                },
            ],

            "oLanguage": {
                "sSearch": "TC No Ara:"
            },

            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },

            "fnCreatedRow": function( nRow, aData, iDataIndex ) {  //Tr attr ekleme İşlemleri
                $(nRow)
                    .attr('tc-kimlik',aData['hasta_tc_numarasi'])
                    .attr('protokol-no',aData['hasta_protokol_no'])
                    .attr('title',"Seçmek İçin Çift Tıklayınız")
            },

            "rowCallback": function( row, data ) {
                $('td:eq(3)', row).attr('hasta-toplam-borc', data['hasta_protokol_no']);
            }


        });

        $('#borclu-hastalar tbody').on('click', 'tr', function () {

            var TC_KIMLIK = $(this).attr('tc-kimlik');
            var PROTOKOL_NO = $(this).attr('protokol-no');

            $('.SECIM_YAP').css("background-color", "rgb(255, 255, 255)");
            $('.SECIM_YAP').addClass("text-dark");
            $('.SECIM_YAP').removeClass("text-white");
            $(this).addClass("text-white");
            $(this).css("background-color", "rgb(57, 180, 150)");

            $.ajax({
                type: 'POST',
                url: 'ajax/vezne/vezneborcdetaysorgula.php',
                data: { tc_kimlik:TC_KIMLIK , protokol_no:PROTOKOL_NO },
                success: function (e) {
                    $('.detayli_islem').html(e);
                }
            });
        });

        $('#goster-dom').on('click', function () {
            $('#toggle-dom-super').toggle();
        });

        $('#toggle-dom-super').toggle();

        $('#BORCLU_FILTRE').on('click', function () {
            table.draw();
        });

    });
</script>

