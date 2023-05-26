<body id="receteModulu">

</div>
<div class="col-xl-12 bgSoftBlue">
    <div class="row gx-0">
        <div class="col-xl-6 headerBorder">
            <h6 class="pageSubtitle fw-light text-center">Seçili Hasta</h6>
            <div class="row align-items-center">
                <div class="col-xl-4 text-center">
                    <img src="assets/img/doctor-avatar.png" class="avatarImgLaptop" alt="avatar" width="95px">
                </div>
                <div class="col-xl-4">
                    <div>
                        <div class="mb-2">
                            <input class="bgWhiteLabel" name="pation_name" placeholder="Hasta Adı-Soyadı">
                        </div>
                        <div class="mb-2">
                            <input class="bgWhiteLabel" name="policlinic_name" placeholder="Poliklinik">
                        </div>
                        <div>
                            <input class="bgWhiteLabel" name="doctor_name" placeholder="Doktor">
                        </div>
                    </div>

                </div>
                <div class="col-xl-4">
                    <div>
                        <div class="mb-2">
                            <input class="bgWhiteLabel" name="operation_type" placeholder="İşlem Türü">
                        </div>
                        <div class="mb-2">
                            <input class="bgWhiteLabel" name="inspection_start" placeholder="Muayene Başlama">
                        </div>
                        <div>
                            <input class="bgWhiteLabel" name="inspection_finish" placeholder="Muayene Bitiş">
                        </input>
                    </div>

                </div>
            </div>
        </div>

    </div>
        <div class="col-xl-6 headerBorder">
            <h5 class="pageSubtitle fw-light text-center">Reçete İşlemleri</h5>
            <div class="d-flex justify-content-evenly">
                <div class="d-flex justify-content-evenly">
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0">
                        <img src="assets/icons/Add-Receipt.png"  width="50px">
                        <div class="fw-500 fsLaptop" id="new_recipe">Reçete Oluştur</div>
                    </button>
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0">
                        <img src="assets/icons/Test-Passed.png"  width="50px">
                        <div class="fw-500 fsLaptop">E-Reçete Kaydet</div>
                    </button>
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0">
                        <img src="assets/icons/Paste.png"  width="50px">
                        <div class="fw-500 fsLaptop">Geçmiş Reçeteler</div>
                    </button>
                </div>
                <div class="text-center">
                    <button class="bg-transparent border-0">
                        <img src="assets/icons/Print.png"  width="50px">
                        <div class="fw-500 fsLaptop">Yazdır</div>
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
<div class="row" >
    <div class="col-xl-1">
        <ul class="nav nav-tabs">
            <li role="presentation" </li>
            <li role="presentation"></li>
            <li role="presentation">
                <a href=""><img src="assets/logo-icon.png" alt="logo" width="54px"></a>
            </li>
        </ul>
    </div>
    <div class="col-xl-11">
        <div class="card p-3">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card border-0 bg-transparent">
                        <div class="row">

                            <div class="col-md-8">
                                <div class="card institutionOther p-3" style=" background: #dbe2ef;  ">
                                    <h6 class="card-title ">İlaç Listesi </h6>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="drug_name" placeholder="İlaç Adı İle Arayınız" >
                                        </div>
                                        <div class="col-m-11"
                                        <div class="row justify-content-between w-80 mx-auto mt-3">
                                            <div class="card institution p-8">
                                                <div class="d-flex">
                                                    <div class="secilentab"></div>
                                                    <table class="table table-striped table-bordered w-100 display nawrap ilaclarin_listesi dataTable no-footer dtr-inline" id="table" style="background: white; width: 100%;" >
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th>İlaç Adı</th>
                                                            <th>Reçete Türü</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="drugs_name">
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function (){
                                    $("#ilac_button").hide();
                                    $('#drugs_name').on('click', 'tr', function () {
                                        $("#ilac_kaydet").show();
                                        var drug_id = $(this).attr('id');
                                        $('.SECIM_YAP').css("background-color", "rgb(255, 255, 255)");
                                        $(this).css("background-color", "#60b3abad");

                                        $('.SECIM_YAP').removeClass('secildi');
                                        $(this).addClass("secildi");

                                        $("#ilac_button").show();
                                    });
                                });
                            </script>

                            <script>
                                $(document).ready(function (){
                                    $("#ilac_kaydet").click(function (){
                                        var id = $(".secildi").attr("id");
                                        var kullanim_sekli = $("#kullanim_sekli").val();
                                        var doz = $("#doz").val();

                                        var kullanim_tipi = $("#kullanim_tipi").val();
                                        var kullanim_periyodu = $("#kullanim_periyodu").val();
                                        var aciklama = $("#aciklama").val();

                                        $.ajax({
                                            type: "POST",
                                           url:"ajax/recete/recetemodel.php?islem=sql_recete_ekle",
                                            data: {
                                                id:id,
                                                recipe_id:kullanim_sekli,
                                                drug_use_type:kullanim_tipi,
                                                drug_use_period:kullanim_periyodu,
                                                drug_description:aciklama,
                                                drug_use_dose:doz,
                                            },
                                            success:function (e){
                                                alert(e);
                                                $.get("ajax/recete/recete-liste.php?islem=liste-getir",function (result){
                                                    $("#new-div").html(result);
                                                });
                                            }
                                        });

                                    });
                                });

                                $(document).ready(function (){
                                    var ara;
                                  var table =  $('#table').DataTable({
                                        proccessing: true,
                                        serverSide:true,
                                       "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'>>" +
                                                "<'row'<'col-sm-12'tr>>" +
                                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                                        language:{
                                            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Turkish.json"
                                        },
                                        ajax:{
                                            url: 'ajax/recete/recete-sql.php?islem=recete-ara',
                                            type: 'POST',
                                            data: function(data){
                                                data.ara = $("#drug_name").val(); // gönderilen data

                                            }
                                        },
                                        columns:[
                                            {data:'drug_name'},
                                            {data:'recipe_type'},
                                        ],
                                      fnCreatedRow: function( nRow, mData, iDataIndex ) {  //Tr attr ekleme İşlemleri
                                          $(nRow)
                                              .attr('id',mData['id'])

                                      },


                                  });

                                    $('#drug_name').keydown(function (){
                                        table.draw();
                                    })

                                });
                            </script>

                            <div class="col-md-4">
                                <div class="card institutionOther p-3" style=" background: #dbe2ef; ">
                                    <h6 class="card-title ">İlaç Kullanım Talimat</h6>
                                    <div class="card h-100 contactInformation p-3">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <label class="h6">Kullanım Şekli</label>
                                                <select class="form-select" aria-label="Default select example" id="kullanim_sekli">
                                                    <option selected>Seçim yapınız..</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-12">
                                                <label class="h6">Kullanım tipi</label>
                                                <select class="form-select" aria-label="Default select example" id="kullanim_tipi">
                                                    <option selected>Seçim yapınız..</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-12">
                                                <label class="h6">Kullanım Periyodu</label>
                                                <select class="form-select" aria-label="Default select example" id="kullanim_periyodu">
                                                    <option selected>Seçim yapınız..</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="h6">Doz</label>
                                                <input type="number" class="form-control" id="doz">
                                            </div>
                                            <div class="form-group">
                                                <label class="h6">Açıklama</label>
                                                <textarea class="form-control" id="aciklama" cols="50" rows="2"></textarea>
                                            </div>
                                            <div class="col-xl-8"></div>
                                            <div class="col-xl-4" id="ilac_button">
                                                <div class="text-center d-flex flex-column me-4">
                                                    <button class="button-85" id="ilac_kaydet">
                                                        <!-- <img src="assets/icons/Ok.png" alt="icon" width="40px">
                                                          <label>İlaç Kaydet</label>-->
                                                        İLAÇ KAYDET
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-1"></div>

                        <div id="new-div"></div>

                        <style>

                                                                                   /* CSS */
                                                                               .button-85 {
                                                                                   padding: 0.6em 2em;
                                                                                   border: none;
                                                                                   outline: none;
                                                                                   color: rgb(255, 255, 255);
                                                                                   background: #111;
                                                                                   cursor: pointer;
                                                                                   position: relative;
                                                                                   z-index: 0;
                                                                                   border-radius: 10px;
                                                                                   user-select: none;
                                                                                   -webkit-user-select: none;
                                                                                   touch-action: manipulation;
                                                                               }

                            .button-85:before {
                                content: "";
                                background: linear-gradient(
                                        45deg,
                                        #ff0000,
                                        #ff7300,
                                        #fffb00,
                                        #48ff00,
                                        #00ffd5,
                                        #002bff,
                                        #7a00ff,
                                        #ff00c8,
                                        #ff0000
                                );
                                position: absolute;
                                top: -2px;
                                left: -2px;
                                background-size: 400%;
                                z-index: -1;
                                filter: blur(5px);
                                -webkit-filter: blur(5px);
                                width: calc(100% + 4px);
                                height: calc(100% + 4px);
                                animation: glowing-button-85 20s linear infinite;
                                transition: opacity 0.3s ease-in-out;
                                border-radius: 10px;
                            }

                            @keyframes glowing-button-85 {
                                0% {
                                    background-position: 0 0;
                                }
                                50% {
                                    background-position: 400% 0;
                                }
                                100% {
                                    background-position: 0 0;
                                }
                            }

                            .button-85:after {
                                z-index: -1;
                                content: "";
                                position: absolute;
                                width: 100%;
                                height: 100%;
                                background: #222;
                                left: 0;
                                top: 0;
                                border-radius: 10px;
                            }
                        </style>
</body>
</html>

