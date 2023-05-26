<style>
    .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .fa-plus{
        color: #dbe2ef;
    }


</style>


<div id="modul-ana-tab" class="esyui-tabs" data-options="tools:'#tab-tools'">
    <div>

<div id="header" class="header-yonetim" style="height: 100% !important;">
    <div class="d-flex align-items-center justify-content-between shadow-sm" style=" background: #dbe2ef; ">
        <div>
            <img src="assets/logo-original.png" alt="ihars logo" width="125px">
        </div>
        <div>
            <h1 class="pageTitle">YÖNETİM MODÜLÜ</h1>
        </div>

        <div class="d-flex align-items-center">
            <div class="language me-3">
                <img src="assets/img/language-flag.png" alt="flag" width="30px" height="30px" class="rounded-circle">
            </div>
            <div class="signOut">
                <img src="assets/icons/sign-out.png" alt="sign out" width="35px">
            </div>
        </div>
    </div>
</div>

<div id="yonetimModuluKullanicilarSearch" style="display:none;">

    <div class="row searchArea">
        <div class="col-xl-2 generalSearch pe-0">
            <div class="generalSearchArea text-center p-3 position-relative h-100 d-flex align-items-center">
                <input class="w-100 pe-5" type="text" placeholder="Arama Yap">
                <img class="position-absolute" src="assets/icons/search.png" alt="icon" width="35px">
            </div>
        </div>
        <div class="col-xl-10 idCodeSearch">
            <div class="idCodeSearchArea row p-3 align-items-center">
                <div class="col-xl-9 position-relative">
                    <input class="w-100 pe-5" type="text" placeholder="Id veya Kod Ara">
                    <img class="position-absolute" src="assets/icons/search.png" alt="icon" width="40px">
                </div>

                <div class="col-xl-3">
                    <div class="d-flex justify-content-end pe-3 headerBtns">
                        <div class="text-center d-flex flex-column me-3 btn-kaydet" title="Ekle">
                            <button class="btn btn-save p-0 " data-bs-target="#modal-getir" data-bs-toggle="modal">
                                <img src="assets/icons/dotted-check.png" alt="icon" width="40px">
                            </button>
                        </div>
                        <div class="text-center d-flex flex-column" title="Çıkış">
                            <button class="btn btn-exit p-0">
                                <img src="assets/icons/exit.png" alt="icon" width="40px">
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="yonetimModuluKullanicilarMainContent" style="overflow: scroll; overflow-x:hidden; font-size: 15px;">
    <div class="row">
        <div class="col-xxl-2 col-xl-3 col-md-4 col-lg-4 col-sm-5 leftMenu" id="leftMenuDomSuper" style="overflow: scroll; overflow-x:hidden;">

            <div class="card shadow leftMenuCard">
                <div class="d-flex align-items-center">
                    <i class="fa-regular fa-square-plus fa-2x"></i>
                    <span class="fw-bold leftMenuTitle">&nbsp;Tanımlamalar</span>
                </div>
            </div>

            <div class="px-1 leftMenuTextOrangeBtn">
                    <button data-bs-toggle="collapse" data-bs-target="#organizasyontanimlari" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x"></i></button>
                    <span>Organizasyon Tanımları</span>
                    <div class="collapse " id="organizasyontanimlari" style="transition: 0s;">
                            <ul class="mb-0">
                                <li><a class="btntanimla p-1" adi="Hastane Tanımları" id-2="hastanetanimla" id="tanimlar/organizasyon-islemleri/hastane-tanimla">Hastane Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Bina Tanımları"    id-2="binatanimla"    id="tanimlar/organizasyon-islemleri/bina-tanimla">Bina Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Kat Tanımları"     id-2="kattanimla"     id="tanimlar/organizasyon-islemleri/kat-tanimla">Kat Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Oda Tanımları"     id-2="odatanimla"     id="tanimlar/organizasyon-islemleri/oda-tanimla">Oda Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Yatak Tanımları"   id-2="yataktanimla"   id="tanimlar/yataktanimla">Yatak Tanımları</a></li>
                            </ul>
                    </div>
            </div>

            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                    <button data-bs-toggle="collapse" data-bs-target="#kullaniciislemleri" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x"></i></button>
                    <span>Kullanıcı İşlemleri</span>
                    <div class="collapse " id="kullaniciislemleri">
                            <ul class="mb-0">
                                <li><a class="btntanimla p-1" adi="Kullanıcı Tanımları" id-2="kullanicitanimla" id="tanimlar/kullanici-islemleri/kullanici-tanimla">Kullanıcı Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Kullanıcı Yetkilendirme" id-2="yetkitanimla" id="tanimlar/kullanici-islemleri/yetki-tanimla">Kullanıcı Yetkilendirme</a></li>
                                <li><a class="btntanimla p-1" adi="Kullanıcı Grup Tanım" id-2="kullanicigruptanim" id="tanimlar/kullanici-islemleri/yetki-grup-tanim">Grup Tanım</a></li>
                                <li><a class="btntanimla p-1" adi="Kullanıcı Grup Üyelik" id-2="kullanicigrupuyelik" id="tanimlar/kullanici-islemleri/kullanici-grup-uyelik">Kullanıcı Grup Üyelik</a></li>
                            </ul>
                    </div>
            </div>

            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                    <button><i class="fa fa-plus fa-2x"></i></button>
                    <span>Doktor İşlemleri</span>
            </div>

            <div class="px-1 mt-1 leftMenuTextOrangeBtn btntanimla" adi="Branşlar" id="tanimlar/branstanimla" id-2="brans-tanimla">
                <div>
                    <button><i class="fa fa-plus fa-2x"></i></button>
                    <span>Branş Tanımları</span>
                </div>
            </div>

            <div class="px-1 leftMenuTextOrangeBtn">
                    <button data-bs-toggle="collapse" data-bs-target="#branstanimlamalari" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x"></i></button>
                    <span>Birim Tanımları</span>
                    <div class="collapse " id="branstanimlamalari" style="transition: 0s;">
                        <div class="">
                            <ul class="mb-0">
                                <li><a class="btntanimla p-1" adi="Poliklinik Tanımları" id-2="polikliniktanimla" id="tanimlar/polikliniktanimla">Poliklinik Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Servis Tanımları" id-2="servistanimla" id="tanimlar/servistanimla">Servis Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Vezne Tanımları" id-2="vezne-tanimlari" id="tanimlar/vezne-tanimlari/vezne-tanimlari">Vezne Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Laboratuvar  Birim Tanımları" id-2="laboratuvarbirimtanimla" id="tanimlar/laboratuvarbirimtanimla">Laboratuvar Birim Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Radyoloji Birim Tanımları" id-2="radyolojibirimtanimla" id="tanimlar/radyolojibirimtanimla">Radyoloji Birim Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Ameliyathane Tanımları" id-2="ameliyathanetanimla" id="tanimlar/ameliyathanetanimla">Ameliyathane Tanımları</a></li>
                            </ul>
                        </div>
                    </div>
            </div>

            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                <div class="btntanimla" adi="Hasta Sınıf Tanımları" id-2="hastasiniftanimla" id="tanimlar/hastasiniftanimla">
                    <button><i class="fa fa-plus fa-2x"></i></button>
                    <span>Hasta Sınıf Tanımları</span>
                </div>
            </div>

            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                <div class="btntanimla" adi="Hasta Kurum Tanımları" id-2="hastakurumtanimla" id="tanimlar/hastakurumtanimla">
                    <button><i class="fa fa-plus fa-2x"></i></button>
                    <span>Hasta Kurum Tanımları</span>
                </div>
            </div>

            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                <div class="btntanimla" adi="İşlemler" id-2="islem-tanim" id="tanimlar/islemler/islemler">
                    <button><i class="fa fa-plus fa-2x"></i></button>
                    <span>İşlemler</span>
                </div>
            </div>

            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                <div class="btntanimla" adi="Depo" id-2="depotanim" id="tanimlar/depotanimla">
                    <button><i class="fa fa-plus fa-2x"></i></button>
                    <span>Depo</span>
                </div>
            </div>
            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                <div class="btntanimla" adi="Hizmetler" id-2="hizmetler" id="tanimlar/hizmet-tanimla">
                    <button><i class="fa fa-plus fa-2x"></i></button>
                    <span>Hizmetler</span>
                </div>
            </div>

            <div class="card shadow leftMenuCard ">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-gear fa-2x"></i>
                    <span class="fw-bold leftMenuTitle">&nbsp; Sistem İşlemleri	</span>
                </div>
            </div>

            <div class="px-1 leftMenuTextOrangeBtn">
                    <button data-bs-toggle="collapse" data-bs-target="#hizmetislemleri" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x" style="color:white"  ></i></button>
                    <span>Hizmet İşlemleri</span>
                    <div class="collapse " id="hizmetislemleri" style="transition: 0s;">
                        <div class="">
                            <ul class="mb-0">
                                <li><a class="btntanimla p-1" adi="Hizmet Tanımları" id-2="hizmettanimla" id="tanimlar/tetkikler/hizmettanimla">Hizmet Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Hizmet Ayarları" id-2="hizmetayarlar" id="tanimlar/hizmetayarlar">Hizmet Ayarları</a></li>
                                <li><a class="btntanimla p-1" adi="Hizmet Fiyat İşlemleri" id-2="hizmetfiyatislem" id="tanimlar/tetkikler/hizmetfiyatislem">Hizmet Fiyat İşlemleri</a></li>
                                <li><a class="btntanimla p-1" adi="Hizmet İşleme Kuralları" id-2="hizmetislemekurallari" id="tanimlar/hizmetislemekurallari">Hizmet İşleme Kuralları</a></li>
                                <li><a class="btntanimla p-1" adi="Paket Tanımları" id-2="pakettanimla" id="tanimlar/pakettanimla">Paket Tanımları</a></li>
                                <li><a class="btntanimla p-1" adi="Paket İçerik Tanımları" id-2="paketiceriktanimla" id="tanimlar/paketiceriktanimla">Paket İçerik Tanımları</a></li>
                            </ul>
                        </div>
                    </div>

                <br>

                    <button class="mt-1" data-bs-toggle="collapse" data-bs-target="#taniislemleri" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x"></i></button>
                    <span>Tanı İşlemleri</span>
                    <div class="collapse " id="taniislemleri" style="transition: 0s;">
                            <ul class="mb-0">
                                <li><a class="btntanimla p-1" adi="ICD Tanım ve Ayar İşlemleri" id-2="icdtanimayarislem" id="tanimlar/icdtanimayarislem">ICD Tanım ve Ayar İşlemleri</a></li>
                            </ul>
                    </div>
            </div>

        </div>

        <div class="col-xxl-10 col-xl-9 col-md-8 col-lg-8 col-sm-7">

            <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">

            </div>

            <div class="warning-definitions mt-5">
                <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                    <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                    <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                    <p>İşlem Yapmak İçin Seçim yapınız</p>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="modal fade" id="modal-getir" aria-hidden="true">
    <div class="modal-dialog modal-xl " id='modal-tanim-icerik'></div>
</div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".btn-kaydet").on("click", function () {
            var secilen = $(".btntanimla-dom").attr('id');
            $.get("ajax/tanimlar/" + secilen + ".php?islem=modal-icerik", function (get) {
                $('#modal-tanim-icerik').html(get);
            });
        });

        $(".btntanimla").on("click", function () {
            $(".warning-definitions").remove();
            $(".btntanimla").removeClass('btntanimla-dom');
            $(this).toggleClass('btntanimla-dom');
        });



    let box = document.querySelector('.header-yonetim');
    let content = box.offsetHeight;
    var html = document.documentElement;
    var screen = html.clientHeight;
    var sonuc_1 = screen / 100;
    var sonuc_2 = content / sonuc_1;
    var sonuc_3 = 90 - sonuc_2;
    $('#leftMenuDomSuper').css("height", sonuc_3 + "vh");

    });

</script>



