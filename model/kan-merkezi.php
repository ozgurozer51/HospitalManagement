<div id="kanMerkeziModulu">
<div class="col-xl-12 bgSoftBlue">
    <div class="row">
        <div class="col-xl-12">
            <div class="row" style=" background: #dbe2ef; ">
                <div class="col-md-2 text-center tab_sec stok_hareket" >
                    <button class="bg-transparent border-0 ">
                        <img src="assets/icons/Stocks.png"  width="50px">
                        <div class="fw-500 fsLaptop">Stok</div>
                    </button>
                </div>

                <div class="col-md-2 text-center tab_sec donor_islemleri">
                    <button class="bg-transparent border-0 ">
                        <img src="assets/icons/Blood-Donation.png"  width="50px">
                        <div class="fw-500 fsLaptop">Donör İşlemleri</div>
                    </button>
                </div>

                <div class="col-md-2 text-center tab_sec kizilay_kan_merkezi">
                    <button class="bg-transparent border-0 ">
                        <img src="assets/icons/Crescent-Moon.png"  width="50px">
                        <div class="fw-500 fsLaptop">Kızılay Kan Merkezi</div>
                    </button>
                </div>

                <div class="col-md-2 text-center tab_sec hasta_kan_cikis">
                    <button  class="bg-transparent border-0 ">
                        <img src="assets/icons/Hospital-Bed.png"  width="50px">
                        <div class="fw-500 fsLaptop">Hasta Kan Çıkışı</div>
                    </button>
                </div>

                <div class="col-md-2 text-center tab_sec kurumlara_kan_cikis">
                    <button  class="bg-transparent border-0 ">
                        <img src="assets/icons/Logout.png"  width="50px">
                        <div class="fw-500 fsLaptop">Kurumlara Kan Çıkışı</div>
                    </button>
                </div>

                <div class="col-md-2 text-center tab_sec tanimlamalar">
                    <button class="bg-transparent border-0 ">
                        <img src="assets/icons/Treatment-List.png"  width="50px">
                        <div class="fw-500 fsLaptop">Tanımlamalar</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
<div id="kan_merkezi_tasarim"></div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".hasta_kan_cikis").on("click", function () {
            $.get( "ajax/kan-merkezi/kan_merkezi_tasarimlar.php?islem=hasta_kan_cikis_ekrani", function(getveri){
                $('#kan_merkezi_tasarim').html(getveri);
            });
        });
        $(".donor_islemleri").on("click", function () {
            $.get( "ajax/kan-merkezi/kan_merkezi_tasarimlar.php?islem=donor_islemleri_ekrani", function(getveri){
                $('#kan_merkezi_tasarim').html(getveri);
            });
        });
        $(".stok_hareket").on("click", function () {
            $.get( "ajax/kan-merkezi/kan_merkezi_tasarimlar.php?islem=stok_hereket_ekrani", function(getveri){
                $('#kan_merkezi_tasarim').html(getveri);
            });
        });
        $(".kizilay_kan_merkezi").on("click", function () {
            $.get( "ajax/kan-merkezi/kan_merkezi_tasarimlar.php?islem=kizilay_kan_merkezi_ekrani", function(getveri){
                $('#kan_merkezi_tasarim').html(getveri);
            });
        });
        $(".kurumlara_kan_cikis").on("click", function () {
            $.get( "ajax/kan-merkezi/kan_merkezi_tasarimlar.php?islem=kurumlara_kan_cikis_ekrani", function(getveri){
                $('#kan_merkezi_tasarim').html(getveri);
            });
        });
        $(".tanimlamalar").on("click", function () {
            $.get( "ajax/kan-merkezi/kan_merkezi_tasarimlar.php?islem=tanimlamalar_ekrani", function(getveri){
                $('#kan_merkezi_tasarim').html(getveri);
            });
        });


    });
</script>
<script>
    $(".tab_sec").click(function () {
        let tiklananSatir = $(this);
        if (!tiklananSatir.hasClass("aktif-satir")) {
            $('.tab_sec').each(function () {
                $(this).removeClass('aktif-satir');
            });
            tiklananSatir.addClass('aktif-satir');
        } else {
            tiklananSatir.removeClass('aktif-satir');
        }
    });
</script>


