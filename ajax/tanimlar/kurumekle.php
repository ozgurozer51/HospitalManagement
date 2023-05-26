

<div class="card-body ">
    <div class="row">
        <form class="col-md-12"  id="gonderilenform">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2 row">
                        <label for="example-text-input" class="col-md-4 col-form-label">kurum kodu</label>
                        <div class="col-md-12">
                            <input class="form-control" type="number" name="tanim_kodu" id="example-text-input">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2 row">
                        <label for="example-text-input" class="col-md-3 col-form-label">kurum adı</label>
                        <div class="col-md-12">
                            <input class="form-control" type="text"  name="tanim_adi" id="example-text-input">
                            <input class="form-control" type="hidden" value="sosyalguvence"  name="tanim_tipi" id="example-text-input">
                            <input class="form-control" type="hidden" value="<?php echo $_GET["tanim_kodu"]; ?>"  name="tanim_eki" id="example-text-input">
                        </div>
                    </div>
                </div>
                <input id="buton" type="button" class="btn btn-success waves-effect waves-light w-sm" value="gönder"/>
            </div>
            </form>
    </div>
</div>


    <script type="text/javascript">
        $(document).ready(function(){
            $("#buton").on("click", function(){ // buton idli elemana tıklandığında
                var gonderilenform = $("#gonderilenform").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
                $.ajax({
                    url:'ajax/tanimlar/kurumdetaygetir.php?sosyalguvence=<?php echo $_GET["tanim_kodu"]; ?>', // serileştirilen değerleri ajax.php dosyasına
                    type:'post', // post metodu ile
                    data:gonderilenform, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                    success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                        if(e!="0"){
                        $('#kurumbilgiicerik').html(e);

                    }else{
                            alertify.error("eklemek istediğiniz kurum kodu sistem tanımlı. başka bir kurum kodu giriniz");
                        }
                    }
                });

            });
        });
    </script>