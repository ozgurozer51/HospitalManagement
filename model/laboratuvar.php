<style>
    .fa-plus{
        color: #dbe2ef;
    }

</style>

<div class="lab_modal_lg_icerik"></div>

<div class="modal fade" id="lab_modal" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog"  id='lab_modal_icerik'> </div>
</div>

<div class="modal fade" id="lab_modal_lg" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg"  id='lab_modal_lg_icerik'> </div>
</div>

<div class="modal fade" id="lab_modal_yuzde_yetmisbes" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="width: 75%; max-width:75%;" id='lab_modal_yuzde_yetmisbes_icerik'> </div>
</div>

<div class="modal fade" id="lab_modal_yuzde_seksen" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="width: 80%; max-width:80%;" id='lab_modal_yuzde_seksen_icerik'> </div>
</div>

<div class="modal fade" id="lab_modal_yuzde_doksan" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" style="width: 90%; max-width:90%;" id='lab_modal_yuzde_doksan_icerik'> </div>
</div>

<div class="modal fade" id="lab_modal_yuzde_doksanbes"  aria-hidden="true">
    <div class="modal-dialog" style="width: 95%; max-width:95%;" id='lab_modal_yuzde_doksanbes_icerik'> </div>
</div>

<div style="display: none;">
    <div class="w1" style="width: 60%; height: 50%;"></div>
</div>

<div style="display: none;">
    <div class="w2" style="width: 90%; height: 75%;"></div>
</div>

<div style="display: none;">
    <div class="w3" style="width: 60%; height: 50%;"></div>
</div>

<div style="display: none;">
    <div class="kriter_paneli_window" style="width: 60%; height: 50%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w40_h20" style="width: 40%; height: 20%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w40_h25" style="width: 40%; height: 25%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w40_h30" style="width: 40%; height: 30%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w40_h35" style="width: 40%; height: 35%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w40_h45" style="width: 40%; height: 45%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w40_h50" style="width: 40%; height: 50%;"></div>
</div>
<div style="display: none;">
    <div class="tanimlamalar_w50_h35" style="width: 50%; height: 35%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w70_h45" style="width: 70%; height: 45%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w80_h45" style="width: 80%; height: 45%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w90_h55" style="width: 90%; height: 55%;"></div>
</div>

<div style="display: none;">
    <div class="tanimlamalar_w95_h75" style="width: 95%; height: 75%;"></div>
</div>


<div  class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">

    <div data-options="region:'west',title:'Laboratuvar Menü',split:true,hideCollapsedContent:false" style="width:15%; background:#dbe2ef;" >

        <div class=" row" id="yonetimModuluKullanicilarMainContent">

            <div class="laboratuvar" id="leftMenuDomSuper" style="overflow: scroll; overflow-x:hidden;">

                <div data-bs-toggle="collapse" data-bs-target="#tanimlamalar" aria-expanded="false" aria-controls="service" class="card shadow leftMenuCard">
                    <div class="d-flex align-items-center">
                        <i  class="fa-regular fa-square-plus fa-2x"></i>
                        <span  class="fw-bold leftMenuTitle">&nbsp;Tanımlamalar</span>
                    </div>
                </div>
                <div class="collapse " id="tanimlamalar">
                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Laboratuvar Tanımları" id-2="laboratuvartanimla" id="laboratuvar/laboratuvar-tanimlamalari/laboratuvartanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Laboratuvar Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Tüp Tanımları" id-2="tuptanimla" id="laboratuvar/laboratuvar-tanimlamalari/tuptanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Tüp Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Kap Tanımları" id-2="kaptanimla" id="laboratuvar/laboratuvar-tanimlamalari/kaptanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Kap Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Cihaz Tanımları" id-2="cihaztanimla" id="laboratuvar/laboratuvar-tanimlamalari/cihaztanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Cihaz Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Test Grup Tanımları" id-2="testgruptanimla" id="laboratuvar/laboratuvar-tanimlamalari/testgruptanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Test Grup Tanımları</span>
                        </div>
                    </div>


                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Tetkik Tanımları" id-2="tetkiktanimla" id="laboratuvar/laboratuvar-tanimlamalari/tetkiktanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Tetkik Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Parametre Tanımları" id-2="parametretanimla" id="laboratuvar/laboratuvar-tanimlamalari/parametretanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Parametre Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Bileşik Tanımları" id-2="bilesiktanimla" id="laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Bileşik Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="İhlal Tanımları" id-2="ihlaltanimla" id="laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>İhlal Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Test Uyarı Mesajı Tanımları" id-2="testuyarimesajitanimla" id="laboratuvar/laboratuvar-tanimlamalari/testuyarimesajitanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Test Uyarı Mesajı Tanımları</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Cihaz Tetkik Tanımları" id-2="cihaztetkiktanimla" id="laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Cihaz Tetkik Tanımları</span>
                        </div>
                    </div>

                    <!--            <div class="px-1 mt-1 leftMenuTextOrangeBtn">-->
                    <!--                <div class="lab-btn" adi="Cihaz Tüp Tanımları" id-2="cihaztuptanimla" id="laboratuvar/laboratuvar-tanimlamalari/cihaztuptanimla">-->
                    <!--                    <button><i class="fa fa-plus fa-2x"></i></button>-->
                    <!--                    <span>Cihaz Tüp Tanımları</span>-->
                    <!--                </div>-->
                    <!--            </div>-->

                    <!--            <div class="px-1 mt-1 leftMenuTextOrangeBtn">-->
                    <!--                <div class="lab-btn" adi="Tüp Grup Tanımları" id-2="tupgruptanimla" id="laboratuvar/laboratuvar-tanimlamalari/tupgruptanimla">-->
                    <!--                    <button><i class="fa fa-plus fa-2x"></i></button>-->
                    <!--                    <span>Tüp Grup Tanımları</span>-->
                    <!--                </div>-->
                    <!--            </div>-->

                    <!--            <div class="px-1 mt-1 leftMenuTextOrangeBtn">-->
                    <!--                <div class="lab-btn" adi="Sonuç Grup Tanımları" id-2="sonucgruptanimla" id="laboratuvar/laboratuvar-tanimlamalari/sonucgruptanimla">-->
                    <!--                    <button><i class="fa fa-plus fa-2x"></i></button>-->
                    <!--                    <span>Sonuç Grup Tanımları</span>-->
                    <!--                </div>-->
                    <!--            </div>-->

                    <!--            <div class="px-1 mt-1 leftMenuTextOrangeBtn">-->
                    <!--                <div class="lab-btn" adi="Mikro Organizma Tanımları" id-2="mikroorganizmatanimla" id="laboratuvar/laboratuvar-tanimlamalari/mikroorganizmatanimla">-->
                    <!--                    <button><i class="fa fa-plus fa-2x"></i></button>-->
                    <!--                    <span>Mikro Organizma Tanımları</span>-->
                    <!--                </div>-->
                    <!--            </div>-->

                    <!--            <div class="px-1 mt-1 leftMenuTextOrangeBtn">-->
                    <!--                <div class="lab-btn" adi="Koloni Tanımları" id-2="kolonitanimla" id="laboratuvar/laboratuvar-tanimlamalari/kolonitanimla">-->
                    <!--                    <button><i class="fa fa-plus fa-2x"></i></button>-->
                    <!--                    <span>Koloni Tanımları</span>-->
                    <!--                </div>-->
                    <!--            </div>-->

                    <!--            <div class="px-1 mt-1 leftMenuTextOrangeBtn">-->
                    <!--                <div class="lab-btn" adi="Kalite Serumları Tanımları" id-2="kaliteserumtanimla" id="laboratuvar/laboratuvar-tanimlamalari/kaliteserumtanimla">-->
                    <!--                    <button><i class="fa fa-plus fa-2x"></i></button>-->
                    <!--                    <span>Kalite Serumları Tanımları</span>-->
                    <!--                </div>-->
                    <!--            </div>-->

                    <div class="px-1 leftMenuTextOrangeBtn">
                        <button data-bs-toggle="collapse" data-bs-target="#geneltanimlamalar" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x"></i></button>
                        <span>Genel Tanımlamalar</span>
                        <div class="collapse " id="geneltanimlamalar" >
                            <ul class="mb-0">
                                <li>
                                    <a class="lab-btn p-1" adi="Örnek Tipi Tanımları" id-2="ornektipitanimla" id="laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla"> Örnek Tipi Tanımları</a>
                                </li>

                                <li>
                                    <a class="lab-btn p-1" adi="Randevu Profili Tanımları" id-2="randevuprofilitanimla" id="laboratuvar/laboratuvar-tanimlamalari/randevuprofilitanimla">Randevu Profili Tanımları</a>
                                </li>

                                <li>
                                    <a class="lab-btn p-1" adi="Kural İşlevi Tanımları" id-2="kuralislevitanimla" id="laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla">Kural İşlevi Tanımları</a>
                                </li>

                                <li>
                                    <a class="lab-btn p-1" adi="Hazır Değer Tanımları" id-2="hazirdegertanimla" id="laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla">Hazır Değer Tanımları</a>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <div class="px-1 leftMenuTextOrangeBtn mt-1">
                        <button data-bs-toggle="collapse" data-bs-target="#mikrobiyolojitanimlamalar" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x"></i></button>
                        <span>Mikrobiyoloji Tanımlamaları</span>
                        <div class="collapse " id="mikrobiyolojitanimlamalar" >
                            <ul class="mb-0">
                                <li>
                                    <a class="lab-btn p-1" adi="Bakteri Tanımları" id-2="bakteritanimla" id="laboratuvar/laboratuvar-tanimlamalari/bakteritanimla"> Bakteri Tanımları</a>
                                </li>

                                <li>
                                    <a class="lab-btn p-1" adi="Antibiyotik Profili Tanımları" id-2="antibiyotikprofilitanimla" id="laboratuvar/laboratuvar-tanimlamalari/antibiyotikprofilitanimla">Antibiyotik Profili Tanımları</a>
                                </li>

                                <li>
                                    <a class="lab-btn p-1" adi="Antibiyotik Tanımları" id-2="antibiyotiktanimla" id="laboratuvar/laboratuvar-tanimlamalari/antibiyotiktanimla">Antibiyotik Tanımları</a>
                                </li>

                                <li>
                                    <a class="lab-btn p-1" adi="Kısıtlı Bildirim Tanımları" id-2="kisitlibildirimtanimlari" id="laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari">Kısıtlı Bildirim Tanımları</a>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <div class="px-1 leftMenuTextOrangeBtn mt-1">
                        <button data-bs-toggle="collapse" data-bs-target="#kalitekonroltanimlari" aria-expanded="false" aria-controls="service"><i class="fa fa-plus fa-2x"></i></button>
                        <span>Kalite Kontrol Tanımlamaları</span>
                        <div class="collapse " id="kalitekonroltanimlari" >
                            <ul class="mb-0">
                                <li>
                                    <a class="lab-btn p-1" adi="Kalite Kontrol ve Lot Tanıtma" id-2="kalitekontrolvelottanitma" id="laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma"> Kalite Kontrol ve Lot Tanıtma</a>
                                </li>

                                <li>
                                    <a class="lab-btn p-1" adi="Kalite Kontrol Test Tanıtma" id-2="kalitekontroltesttanitma" id="laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma">Kalite Kontrol Test Tanıtma</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="mt-1"></div>
                <div  data-bs-toggle="collapse" data-bs-target="#laboratuvar" aria-expanded="false" aria-controls="service" class="card shadow leftMenuCard ">
                    <div class="d-flex align-items-center">
                        <i class="fa-regular fa-flask-vial fa-2x"></i>
                        <span class="fw-bold leftMenuTitle">&nbsp; İşlemler</span>
                    </div>
                </div>
                <div class="collapse " id="laboratuvar">

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="İstem Kabul" id-2="istemkabul" id="laboratuvar/istem-kabul/istemkabul">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>İstem Kabul</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Numune Alma" id-2="numunealma" id="laboratuvar/numune-alma/numunealma">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Numune Alma</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Numune Kabul" id-2="numunekabul" id="laboratuvar/numune-kabul/numunekabul">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Numune Kabul</span>
                        </div>
                    </div>

                    <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                        <div class="lab-btn" adi="Sonuç Onaylama" id-2="sonuconaylama" id="laboratuvar/numune-sonuc-onaylama/sonuconaylama">
                            <button><i class="fa fa-plus fa-2x"></i></button>
                            <span>Sonuç Onaylama</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div data-options="region:'center' ,  fit:'true'" style="width: 85%; height: 100%;" >

        <div id="lab-tab" class="easyui-tabs" data-options="fit:'ture'">

        </div>

    </div>

</div>

<script>
    setTimeout(function (){

        $('.w1').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.w1').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'Sonuç Gir',

        });

        $('.w2').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.w2').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'Geçmiş Sonuçlar',

        });

        $('.w3').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.w3').html("");
                },

            cache: true,
            modal: true,

            closed: true,
            title: 'Tekrar Sonuç Gir',

        });

        $('.kriter_paneli_window').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.kriter_paneli_window').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'test',

        });

        $('.tanimlamalar_w40_h20').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w40_h20').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w40_h20',

        });

        $('.tanimlamalar_w40_h25').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w40_h25').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w40_h25',

        });

        $('.tanimlamalar_w40_h30').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w40_h30').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w40_h30',

        });

        $('.tanimlamalar_w40_h35').dialog({


            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w40_h35').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w40_h35',

        });

        $('.tanimlamalar_w40_h45').dialog({


            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w40_h45').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w40_h45',

        });

        $('.tanimlamalar_w40_h50').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w40_h50').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w40_h50',

        });

        $('.tanimlamalar_w50_h35').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w50_h35').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w50_h35',

        });

        $('.tanimlamalar_w70_h45').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w70_h45').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w70_h45',

        });

        $('.tanimlamalar_w80_h45').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w80_h45').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w80_h45',

        });

        $('.tanimlamalar_w90_h55').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w90_h55').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w90_h55',

        });

        $('.tanimlamalar_w95_h75').dialog({

            onClose: function () {
                $(this).find('FORM').form('clear');
                $('.tanimlamalar_w95_h75').html("");
            },

            cache: true,
            modal: true,

            closed: true,
            title: 'tanimlamalar_w95_h75',

        });

    },1000);

</script>

<script type="text/javascript">

    $('.lab-btn').click(function (){
        var tab_class = $(this).attr("id-2");
        var url = $(this).attr("id");
        var adi = $(this).attr("adi");

        var tabs_count = $('.' + tab_class).length;
        if(tabs_count==0) {
            $.ajax({
                url: "ajax/" + url + ".php?islem=listeyi-getir",
                data: {},
                dataType: 'html',
                type: 'get',
                success: function (e) {

                    var tabs = $('#lab-tab').tabs('add', {
                        iconCls: "fa-solid fa-report " + tab_class,
                        title: adi,
                        content:'<div style="height:100%; width: 85%; " class="sayfa-icerik">'+e+'</div>',
                        closable: true,
                    });

                }
            });
        }else{
            $("." + tab_class).trigger("click");
        }

    });

    $(document).ready(function () {

        let box = document.querySelector('.laboratuvar');
        let content = box.offsetHeight;
        var html = document.documentElement;
        var screen = html.clientHeight;
        var sonuc_1 = screen / 136;
        var sonuc_2 = content / sonuc_1;
        var sonuc_3 = 100 - sonuc_2;

        $('#leftMenuDomSuper').css("height", sonuc_3 + "vh");

    });

</script>