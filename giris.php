<?php
include "controller/fonksiyonlar.php";
session_start();
ob_start();
$dil=$_GET["dil"];

if($_SESSION["dil"]==""){
    $_SESSION["dil"] = "tr";
    go("giris.php");
}

if($dil!=""){
    $_SESSION["dil"] = $dil;
    go("giris.php");
}

if ($_SESSION["dil"]==""){
    require("diller/tr.php");
}else {
    require("diller/".$_SESSION["dil"].".php");
}
if($_GET["islem"]=="girisyap"){
    if ($_POST["kullanici_adi"]) {
        $kullanici_adi = $_POST["kullanici_adi"];
        $sifresi = $_POST["sifresi"];
        $sorgu = tek("SELECT * FROM users WHERE tc_id='$kullanici_adi' and user_password='$sifresi'");

        if ($sorgu['name_surname'] != "") {
// $_SESSION["kullanici_adi"]=$sonuc['kullanici_adi'];
// $_SESSION["kullanici_sifresi"]=$sonuc['kullanici_sifresi'];
// $_SESSION["kullanici_id"]=$sonuc['id'];
            foreach ($sorgu as $key => $value) {
                $_SESSION["$key"] = $sorgu["$key"];
            }

            $songiris = date('Y-m-d H:i:s');
            $OTURUM_HASH=get_guid();
            $kullaniciid = $sorgu['id'];
            unset($_POST);
            $_POST["userid"]=$kullaniciid;
            $_POST["session_datetime"]=$songiris;
            $_POST["first_session_datetime"]=$songiris;
            $_POST["session_hash"]=$OTURUM_HASH;
            direktekle("users_session_logs",$_POST);

            $_SESSION["session_hash"] = $_POST["session_hash"];

            // var_dump($_SESSION);
            echo '1';

        } else { echo '0'; }
    }
}else{ ?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <link href="assets/css/alertify.min.css" rel="stylesheet" type="text/css" />

    <script src="assets/alertify.min.js"></script>
    <title>Giriş Yap</title>
    
</head>

<body>

    <div id="girisYapContent" class="vh-100 d-flex position-relative">
        <div class="leftBox">

        </div>
        <div class="rightBox">

        </div>
        <div class="cardBox">
            <form class="d-flex" method="post" action="#" id="sistemegirisformu">

                <div id='icerik'></div>
                <div class="d-flex align-items-center justify-content-center cardLeftBox">
                    <img class="logoImg" src="assets/logo-disi.png" alt="logo" width="300px">
                </div> 
                <div class="p-5 cardRightBox">
                    <h3 class="text-center"><?php  echo $dil["lutfen_giris_yapiniz"];?>!</h3>
                    <div> 
                        <div class="d-flex flex-column position-relative mt-4">
                            <label class="ps-3" for=""><?php  echo $dil["tc_kimlik"];?></label>
                            <input type="number" name="kullanici_adi" class="shadow" placeholder="<?php  echo $dil["tc_kimlik"];?>">
                            <img class="inputIcon" src="assets/icons/user.png" alt="kullanıcı" width="32px">
                        </div>
                        <div class="d-flex flex-column position-relative mt-3">
                            <label class="ps-3" for=""><?php  echo $dil["sifreniz"];?></label>
                            <input type="password" name="sifresi" class="shadow" placeholder="<?php  echo $dil["sifreniz"];?>">
                            <img class="inputIcon" src="assets/icons/password.png" alt="kullanıcı" width="32px">
                        </div>
                    </div>
                    <div class="d-flex mt-2 p-3">
                        <button class="bg-transparent border-0">
                            <img class="languageFlag shadow me-2" src="assets/img/language-flag.png" alt="bayrak" width="27px" height="27px">
                        </button>
                        <button class="bg-transparent border-0">
                            <img class="languageFlag shadow" src="assets/img/usa-flag.png" alt="bayrak" width="27px" height="27px">
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button id="girisyapinbutton" type="button" class="btn btn-login shadow">
                            <?php  echo $dil["giris_yap"];?>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

    <script src="//code.jquery.com/jquery-3.0.0.min.js"></script>
<!-- end authentication section -->
<script>
    $(document).ready(function(){
        $("#girisyapinbutton").on("click", function(){
            
            var gonderilenform = $("#sistemegirisformu").serialize();
            $.ajax({
                url:'giris.php?islem=girisyap',
                type:'POST',
                data:gonderilenform,
                success:function(getVeri){
                    if(getVeri=="0"){
                        alertify.alert("Hatalı Bilgiler!!","Girdiğiniz kullanıcı adı ve şifre hatalıdır. Lütfen kontrol edip tekrar deneyiniz");
                    }else{
                        alertify.message("Girişiniz başarıyla yapılmıştır. Yönlendiriliyorsunuz lütfen bekleyiniz");
                        window.location="index.php";
                    }
                }
            });

        });
    });
</script>


<?php } ?>