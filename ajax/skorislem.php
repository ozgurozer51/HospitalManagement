<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];


$islem = $_GET['islem'];

if ($islem == "apache") {

    $yatissekle = direktekle("score_apache2", $_POST);
    var_dump($yatissekle);
    if ($yatissekle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
    <?php }
    else { ?>
        <script>
            alertify.danger('işlemi Başarisiz');
        </script>
    <?php }

}
else if ($islem == "glaskow") {

    $yatissekle = direktekle("score_glaskow", $_POST);
    var_dump($yatissekle);
    if ($yatissekle==1) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php }
    else { ?>
        <script>
            alertify.danger('işlemi Başarisiz');
        </script>
    <?php }

}

else if ($islem == "euroscore2") {

    $yatissekle = direktekle("score_euroscore2",$_POST);
    var_dump($yatissekle);
    if ($yatissekle==1) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php }
    else { ?>
        <script>
            alertify.danger('işlemi Başarisiz');
        </script>
    <?php }

}
else if ($islem == "spas") {

    $yatissekle = direktekle("score_spas", $_POST);
    var_dump($yatissekle);
    if ($yatissekle==1) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php }
    else { ?>
        <script>
            alertify.danger('işlemi Başarisiz');
        </script>
    <?php }

}
else if ($islem == "crib") {

    $yatissekle = direktekle("score_crib", $_POST);
    var_dump($yatissekle);
    if ($yatissekle==1) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php }
    else { ?>
        <script>
            alertify.danger('işlemi Başarisiz');
        </script>
    <?php }

}

else if ($islem == "prism") {
    unset($_POST['Bouton']);
    unset($_POST['conclusion_prism_death2']);
    unset($_POST['y']);
    $yatissekle = direktekle("score_prism", $_POST);
    var_dump($yatissekle);
    if ($yatissekle==1) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php }
    else { ?>
        <script>
            alertify.danger('işlemi Başarisiz');
        </script>
    <?php }

}

else if ($islem == "snaps") {
    $yatissekle = direktekle("score_snaps2", $_POST);
    var_dump($yatissekle);
    if ($yatissekle==1) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php }
    else { ?>
        <script>
            alertify.danger('işlemi Başarisiz');
        </script>
    <?php }

}
elseif ($islem == "apachedelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("score_apache2", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}
elseif ($islem == "glaskowdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("score_glaskow", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "euroscore2cancel") {

    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("score_euroscore2", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "spasdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("score_spas", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "prismedelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("score_prism", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "snapsdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("score_snaps2", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "cribdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("score_crib", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}