
<style>
    ::-webkit-scrollbar {
        width: 1px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 1px;
    }

    ::-webkit-scrollbar-thumb {
        border-radius: 1px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
    }
</style>

<body id="vezneModulu" style="height:100% !important;">

<div class="col-xl-12 bgSoftBlue" style="height:20% !important;">
    <div class="row gx-0">
        <div class="col-xl-6 headerBorder">
            <h6 class="pageSubtitle fw-light text-center">Aktif Hasta Vezne Bilgileri</h6>
            <div class="row align-items-center">
                <div class="col-xl-4 text-center">
                    <img src="assets/img/dummy-avatar.png" class="avatarImgLaptop" alt="avatar" width="90px">
                </div>

                <div class="col-xl-4">
                        <div class="mb-2">
                            <div class="bgWhiteLabel"> Adı-Soyadı</div>
                        </div>
                        <div class="mb-2">
                            <div class="bgWhiteLabel">Toplam Miktar</div>
                        </div>
                </div>

                <div class="col-xl-4">
                    <div>
                        <div class="mb-2">
                            <div class="bgWhiteLabel">İptal Edilen Miktar</div>
                        </div>
                        <div class="mb-2">
                            <div class="bgWhiteLabel">Ödeme Türü</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xl-6 headerBorder">
            <h6 class="pageSubtitle fw-light text-center">Hizmet/Malzeme Seçimleri</h6>
            <div class="d-flex justify-content-evenly" id="buton-evreni"><!------------Data Tables Button İçerik--------------------------></div>
        </div>
    </div>
</div>


<div class="row" style="height: 70% !important;">

<!--    <div class="col-xl-1">-->
<!--        <ul class="nav nav-tabs">-->
<!--            <li role="presentation"><a href="#" >Seçim Filtre</a></li>-->
<!--            <li role="presentation"></li>-->
<!--            <li role="presentation">-->
<!--                <a href="#"><img src="assets/logo-icon.png" alt="logo" width="54px"></a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </div>-->

    <div class="col-xl-12">
        <div class="card p-3">
            <div class="row">
                <div class="col-xl-12 ">
                    <div class="card institutionOther p-2" style=" background: #dbe2ef; ">
                        <div class="col-xl-12">
                            <div class="card border-0 bg-transparent">

                                <div class="row">

                                    <div class="col-xl-4">

                                        <div class="row">

                                            <div class="col-xl-12" >
                                                <div class="card institutionOther p-2 text-white" id="goster-dom" style=" background: #3F72AF; cursor: pointer; ">Borçlu Hasta Listesine Göre Filtrele</div>
                                            </div>

                                            <div id="toggle-dom-super">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <label for="">Başlangıç Tarihi</label>
                                                        <input placeholder="" id="BASLANGIC_TARIHI" class="textbox-n w-100 form-control" type="text" onfocus="(this.type='date')" id="date">
                                                    </div>

                                                    <div class="col-xl-6  ">
                                                        <label for="">Bitiş Tarihi</label>
                                                        <input placeholder="" id="BITIS_TARIHI" class="textbox-n w-100 form-control" type="text" onfocus="(this.type='date')" id="date">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-xl-12" style=" background: #dbe2ef; ">
                                                <div class="card p-3">

                                                <input type="text" id="tc-no-ara" class="form-control" placeholder="TC No Ara" >

                                                    <div class="col-m-11" >
                                                        <div class="card institution p-8">
                                                            <div class="table-responsive">

                                                                    <table class="table table-bordered table-sm table-hover display nowrap mt-1" id="borclu-hastalar" style="background: white; width: 100%;" >
                                                                        <thead class="table-light">
                                                                        <tr>
                                                                            <th>TC No</th>
                                                                            <th>Adı</th>
                                                                            <th>Protokol No</th>
                                                                            <th>Toplam Borç</th>
                                                                            <th>Sosyal Güvence</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody> </tbody>
                                                                    </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        </div>
                                    </div>

                                    <div class="col-xl-8">
                                        <div class="row w-100 ">
                                            <div class="col-xl-12">
                                                <div class="cardp-1 w-100 bg-white">
                                                <div class="card-header">Hizmet Malzeme Listesi </div>
                                                <div class="card-body col-m-11"
                                                <div class="row justify-content-between w-80 mx-auto mt-3">
                                                    <div class="card institution p-8">

                                                        <div class="detayli_islem">

                                                            <div class="alert-dismissible fade show  mb-0 text-center"
                                                                 role="alert">
                                                                <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                                                <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                                                                <p>İşlem Yapmak İçin Seçim yapınız</p>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

   <div class="card institutionOther" style="background:#FFB3B3; height: 10% !important;">
       <div class="row">
           <div class="col-3 d-flex bd-highlight">
               <button  class="btn btn-red w-50 h-100 p-2 bd-highlight">Tüm Borç:<span class="p-2 bd-highlight" id="info-tum-toplam">0</span></button>
           </div>
           <div class="col-3">
               <button class="btn btn-red w-50 h-100">Ara Toplam:<span id="info-ara-toplam">0</span></button>
           </div>
           <div class="col-3">
               <button class="btn btn-red w-50 h-100">Hizmet Toplam:<span id="info-hizmet-toplam">0</span></button>
           </div>
           <div class="col-3">
               <button type="button" class="btn btn-trash" data-bs-toggle="modal" data-bs-target="#odemeal" id="odemeler" style="padding: 17px;"  disabled><img src="assets/icons/Cash-Hand.png" alt="icon" height="20px" width="20px">Ödeme Al</button>
           </div>
       </div>
   </div>


</body>


<script type="text/javascript">

    $(document).ready(function(){
        $("#BASLANGIC_TARIHI").change(function() {
            if ($( "#BITIS_TARIHI").val()){
                table.draw(); }
        });

        $("#BITIS_TARIHI").change(function() {
            if ($( "#BASLANGIC_TARIHI").val()){
                table.draw(); }
        });

        $("#tc-no-ara").keyup(function() {
            table.draw();
        });

        var table =  $('#borclu-hastalar').DataTable({
            "dom": "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'p>>",
            "processing": true,
            "serverSide": true,
            "scrollY": '50vh',
            "scrollX": false,
            "paging": false,
            "Visible": true,
            "responsive": true,
            "pageLength": 50,

            ajax:{
                url: 'ajax/vezne/vezne-server-side.php?islem=borclu-hastalar-listesi',
                type: 'POST',
                data: function(data){
                    data.baslangic_tarihi = $("#BASLANGIC_TARIHI").val();
                    data.bitis_tarihi = $("#BITIS_TARIHI").val();
                    data.tc_no_ara  = $('#tc-no-ara').val();
                }
            },

            columns:[
                {data:'hasta_tc_numarasi'},
                {data:'hasta_adi'},
                {data:'hasta_protokol_no'},
                {data:'toplam_borc'},
                {data:'hasta_sosyal_guvence_adi'},
                    ],

            "aLengthMenu": [[10, 25, 50, 75,100,500, 1000 ,2000],[10, 25, 50, 75,100,500, 1000 ,2000]],

            "columnDefs": [{
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
                },],

            "oLanguage": {
                "sSearch": "TC No Ara:"
            },

            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },

            "fnCreatedRow": function(nRow,aData,iDataIndex) {  //Tr attr ekleme İşlemleri
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
            $(this).css("background-color", "#60b3abad");

            $.ajax({
                type: 'POST',
                url: 'ajax/vezne/vezne-borc-detay-sorgula.php',
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

        $("#dock-container").remove();


    });
</script>


