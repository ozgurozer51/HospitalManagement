


<div id="Ameliyathane">
    <div class="row d-flex" id="Ameliyathane">
        <div class="col-xl-12 bgSoftBlue">
            <div class="row ">
                <div class="col-xl-3 mt-3 px-2">
                    <div class="row align-items-center">
                        <div class="col-xl-4 text-center">
                            <img src="../assets/img/dummy-avatar.png" class="avatarImgLaptop" alt="avatar" width="95px">
                        </div>
                        <div class="col-xl-8">
                            <div>
                                <div class="mb-2">
                                    <div class="bgWhiteLabel">Hasta Adı-Soyadı</div>
                                </div>
                                <div class="mb-2">
                                    <div class="bgWhiteLabel">Bölüm</div>
                                </div>
                                <div>
                                    <div class="bgWhiteLabel">Doktor</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 headerBorder">
                    <h5 class="pageSubtitle mb-0 text-center fw-light">Hasta Protokol İşlemleri</h5>
                    <div class="col-8  d-flex">
                        <div class="col-6">
                            <label  for="recipient-name" class="col-form-label">Yatış/Pol.</label>
                        </div>

                        <div class="col-8 ">
                            <input class="form-control" disabled type="text" >
                            <input class="form-control" hidden type="text" >
                            <input class="form-control" hidden type="text" >
                            <input class="form-control" hidden type="text" >
                        </div>
                    </div>
                    <div class="col-8  d-flex">
                        <div class="col-6">
                            <label  for="recipient-name" class="col-form-label">Protkol No</label>
                        </div>

                        <div class="col-8">
                            <input class="form-control" disabled type="text" >
                            <input class="form-control" hidden type="text" >
                            <input class="form-control" hidden type="text" >
                            <input class="form-control" hidden type="text" >
                        </div>
                    </div>
                    <div class="col-8  d-flex">
                        <div class="col-6">
                            <label  for="recipient-name" class="col-form-label">Yıl</label>
                        </div>

                        <div class="col-8">
                            <input class="form-control" disabled type="text" >
                            <input class="form-control" hidden type="text" >
                            <input class="form-control" hidden type="text" >
                            <input class="form-control" hidden type="text" >
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 headerBorder">
                    <h5 class="pageSubtitle mb-0 text-center fw-light">Hasta İşlemleri</h5>
                    <div class="row">
                        <div class="col-xl-4 text-center">
                            <button class="bg-transparent border-0">
                                <img src="../assets/icons/add-user.png" alt="yeni hasta" width="50px">
                                <div class="fw-500 fsLaptop">Yeni Hasta</div>
                            </button>
                        </div>
                        <div class="col-xl-4 text-center">
                            <button class="bg-transparent border-0">
                                <img src="../assets/icons/update.png" alt="yeni hasta" width="50px">
                                <div class="fw-500 fsLaptop">Güncelle</div>
                            </button>
                        </div>
                        <div class="col-xl-4 text-center">
                            <button class="bg-transparent border-0">
                                <img src="../assets/icons/find-user.png" alt="yeni hasta" width="50px">
                                <div class="fw-500 fsLaptop">Dosya Ara</div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 headerBorder">
                    <h5 class="pageSubtitle mb-0 text-center fw-light">Ameliyat İşlemleri</h5>
                    <div class="row">
                        <div class="col-xl-3 text-center">
                            <button class="bg-transparent border-0">
                                <img src="../assets/icons/Surgical-Scissors.png"  width="50px">
                                <div class="fw-500 fsLaptop">Ameliyat Bilgisi</div>
                            </button>
                        </div>
                        <div class="col-xl-3 text-center">
                            <button class="bg-transparent border-0">
                                <img src="../assets/icons/Person-Calendar.png"  width="50px">
                                <div class="fw-500 fsLaptop">Randevu İşlem</div>
                            </button>
                        </div>
                        <div class="col-xl-3 text-center">
                            <button class="bg-transparent border-0">
                                <img src="../assets/icons/project-setup.png" alt="yeni hasta" width="50px">
                                <div class="fw-500 fsLaptop">Dosya İşlemleri</div>
                            </button>
                        </div>
                        <div class="col-xl-3 text-center">
                            <button class="bg-transparent border-0">
                                <img src="../assets/icons/medkit.png" alt="yeni hasta" width="50px">
                                <div class="fw-500 fsLaptop">Med Kullanım</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="hastaKayitContent">

        <div class="col-xl-12">
            <div class="card p-3">

                <div class="col-xl-2">
                    <div class="card border-0 bg-transparent">
                        <button class="bgSoftPink KPSbtn d-flex ">
                            <span class="material-symbols-outlined">add_circle</span>
                            <span>Yeni Ameliyat Ekle</span>
                        </button>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card border-0 bg-transparent">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="card institutionOther p-3" style=" background: #dbe2ef; ">
                                    <h6 class="card-title fw-bold">Sorgu/Listeleme</h6>
                                    <div class="card h-100 contactInformation p-3">
                                        <div class="row">
                                            <div class="col-xl-12 d-flex align-items-center mb-2">
                                                <label for=""class="h6">Hasta Bilgisi</label>
                                                <input type="text">
                                            </div>
                                            <div class="col-xl-12">
                                                <label for=""class="h6">Yat./Pol.</label>
                                                <select class="text-danger" aria-label="Default select example">
                                                    <option selected>Seçim yapınız..</option>
                                                    <option value="1"></option>
                                                    <option value="2"></option>
                                                </select>
                                            </div>
                                            <div class="col-xl-12">
                                                <label for=""class="h6">Ameliyat Yeri</label>
                                                <select class="text-danger" aria-label="Default select example">
                                                    <option selected>Seçim yapınız..</option>
                                                    <option value="1"></option>
                                                    <option value="2"></option>
                                                </select>
                                            </div>
                                            <div class="col-xl-12 d-flex align-items-center mb-2">
                                                <label for=""class="h6">Ameliyat Durum</label>
                                                <input type="text">
                                            </div>
                                            <div class="col-xl-8"></div>
                                            <div class="col-xl-4">
                                                <div class="d-flex justify-content-end align-items-end ">
                                                    <div class="text-center col-xl-2 flex-column ">
                                                        <div class="d-flex justify-content-end align-items-end ">
                                                            <button class="btn btn-okey">
                                                                <span class="material-symbols-outlined">check_circle</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="card institutionOther p-3" style=" background: #dbe2ef; ">
                                    <h6 class="card-title fw-bold">Sorgu Listesi</h6>
                                    <div class="card institution p-8">
                                        <div class="d-flex">
                                            <div class="secilentab"></div>
                                            <table class="table table-bordered table-sm table-hover" style="background: white; width: 100%; height: 235px" >
                                                <thead class="table-light">
                                                <tr>
                                                    <th>Ameliyat Tarih</th>
                                                    <th>TC No</th>
                                                    <th>Hasta Ad Soyad</th>
                                                    <th>Doğum Tarihi</th>
                                                    <th>Kurumu</th>
                                                    <th>Pro. No</th>
                                                    <th>Erteleme Tarihi</th>
                                                    <th>Yaşı</th>
                                                    <th>Servisi</th>
                                                    <th>Doktoru</th>
                                                    <th>Yatış Tarihi</th>
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
                </div>

                <div class="col-xl-12 mt-2">
                    <h6 class="card-title fw-bold">Ameliyat Ayrıntı İşlemleri</h6>
                    <div class="card institutionOther p-2" style=" background: white; ">
                        <div class="d-flex">
                            <button class="btn btn-primary-3 active">
                                Ameliyat Tanımı
                            </button>
                            <button class="btn btn-primary-3 ms-2">
                                Müdahaleler
                            </button>
                            <button class="btn btn-primary-3 ms-2">
                                Önceki Ameliyatlar
                            </button>
                        </div>
                        <div class="d-flex">
                            <div class="card p-2 col-xl-11" style=" background:#dbe2ef" ;>
                                <div class="col-xl-12">
                                    <div class="secilentab"></div>
                                    <table class="table table-bordered table-sm table-hover" style="background: white; width: 100%; " >
                                        <thead class="table-light">
                                        <tr>
                                            <th>İşlem Tarihi</th>
                                            <th>İşlemi Yapan</th>
                                            <th>Hizmet Kodu</th>
                                            <th>Hizmet</th>
                                            <th>Doktor</th>
                                            <th>Türü</th>
                                        </tr>
                                        </thead>
                                        <tbody> </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-xl-1">
                                <div class=" d-flex">
                                    <div
                                    <button class="btn btn-pink">
                                        <span class="material-symbols-outlined">vaccines</span>
                                    </button>
                                </div>
                                <div
                                <button class="btn btn-orange">
                                    <span class="material-symbols-outlined ">folder_delete</span>
                                </button>
                            </div>

                            <div
                            <button class="btn btn-red">
                                <span class="material-symbols-outlined">healing</span>
                            </button>
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



