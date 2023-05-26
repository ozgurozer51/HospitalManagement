<style>
    div[class^="btn-small"]{
        float: left;
        margin: 0 10px 10px 0;
        height: 95px;
        position: relative;
        cursor: pointer;
        transition: all .9s ease;
        user-drag: element;
        border: solid 2px transparent;
        text-align:center;
        line-height:100px;
    }

    div[class^="btn-small"]:hover{
        opacity: 0.7;
    }

    div[class^="btn-small"]:active{
        transform: scale(.98,.98);
    }

    .btn-small{
        transition: all 0.2s ease-in-out;
        width: 95px;
        cursor: pointer;
    }

    .label{
        position: absolute;
        color: white;
        font: 500 12px sans-serif;
        left: 10px;
        user-select: none;
    }

    .bottom {
        bottom: 5px;
    }

    .red {
        background: #df0024;
    }

    .blue {
        background: #00a9ec;
    }

    .orange {
        background: #ff9000;
    }

    .green {
        background: #009000;
    }

    div[class^="icon"]{
        width: 45px;
        height: 45px;
        margin: 20px auto;
        background-size: 45px 45px;
    }

    ::selection{
        background: mintcream;
    }

    .photo img:hover{
        border: solid 2px mintcream;
    }

    .light-title {
        font-size: 0.8em;
        font-weight: 200;
        color: rgba(255, 255, 255, 0.83);
    }

    .stats {
        flex: 2;
    }

    .stats .profile {
        display: flex;
        align-items: center;
    }

    .profile img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .profile p {
        color: white;
    }

    nav {
        margin-right: 15px;
    }

    nav a {
        display: block;
        text-decoration: none;
        padding: 10px 2px;
        color: white;
        font-size: 0.7em;
    }

    nav a:hover {
        background: rgba(134, 128, 128, 0.32);
    }

    .icon {
        display: inline-block;
        width: 30px;
        height: 30px;
        background: white;
        margin-right: 15px;
        vertical-align: middle;
    }

    .tiles-con {
        flex: 6;
        display: flex;
    }

    header h3 {
        color: white;
        font-size: 0.7em;
        font-weight: 200em;
        margin: 0 5px 15px;
    }

    .con .tiles-con {
        transform: translateY(20%);
        transition: transform .3s ease-out;
    }

    .start-btn:hover > .win-logo {
        background-color: #4ac5e0;
    }

    #in:checked + .con + .start-btn {
        background: rgba(134, 128, 128, 0.32);
    }

    #in:checked ~ .con > .tiles-con {
        transform: translate(0);
    }

/*  Windows Tema Css Bitiş*********************************************************************************************/

</style>

<?php include "../fonksiyonlar.php";
session_start();
ob_start();

if($_GET['islem']=="favori-modul-ekle"){
    $sql = sql_select("select * from user_module_favorite where module_name='$_POST[module_name]' and userid = $_SESSION[id]");
    if($sql[0]['status'] == 0 && $sql[0]['id']){
        unset($_POST);
        $_POST['status'] = 1;
        $sql = direktguncelle("user_module_favorite" , "id" , $sql[0]['id'] , $_POST);
    }elseif ($sql[0]['status'] == 1 && $sql[0]['id'] ){   ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('ModÜL Daha Önce Eklenmiş.');
        </script>

        <?php exit();  }else{
        $_POST['userid'] = $_SESSION['id'];
        $sql = direktekle("user_module_favorite" , $_POST);
    } ?>

    <script>
        alertify.set('notifier','delay', 8);
        alertify.success('İşlem Başarılı');
    </script>

    <?php     exit();
}if($_GET['islem'] == "favori-moduller"){

    $sql = sql_select("select * from user_module_favorite where userid = $_SESSION[id] and status = 1");
    foreach ($sql as $item) {
        $sayi = rand(11,24);
        $r = rand(0,255);
        $g = rand(0,255);
        $b = rand(0,215);
        $renk = dechex($r) . dechex($g) . dechex($b);  ?>

        <div style="display: flex; cursor: pointer;">
            <i class="fa-thin fa-circle-xmark favori-modul-kaldir" data-id="<?php echo $item['id'];  ?>" title="Favori Listesinden Kaldır" style="position: relative; color:white; margin-top: 10px; right: 0px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;
            <a class="text-white modul-sec"  adi="<?php echo $item['module_name']; ?>" id="<?php echo $item['url']; ?>"  id-2="<?php echo $item['module_name']; ?>"><i class="fa-solid fa-objects-column" style="color:#<?php echo $renk; ?>;"></i> <?php echo $item['module_name']; ?></a>
        </div>

    <?php  } ?>

    <?php exit();  }if($_GET['islem'] == "favori-modul-kaldir"){

    $sql = canceldetail("user_module_favorite" , "id" , $_POST['id'] , "0");

    if($sql== 1){ ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Modul Favori Listesinden Çıkarıldı');
        </script>

    <?php   }  }

$user_info = sql_select("select * from users inner join transaction_definitions on transaction_definitions.id = users.title  where users.id=$_SESSION[id]");

$system_user = 0;

if ($user_info[0]['personnel_type'] != 1) {
    $user_authority = sql_select("select *
from authority_of_pool
         inner join authority_definition on authority_of_pool.authority_id = authority_definition.id
where authority_of_pool.status = 1
  and authority_of_pool.userid=$_SESSION[id]");

}else if($user_info[0]['personnel_type'] == 1){
    $system_user = 1;
}  ?>


<div class="main-menu-content" style="display: flex;">
    <section class="f-item stats" style="max-width:250px; min-width:250px; overflow-y: auto;">
        <div class="profile">
            <p><?php echo $user_info[0]['definition_name'] . " " . $user_info[0]['name_surname']; ?></p>
        </div>
        <h3 class="light-title">Favoriler</h3>
        <div id="target1" style="min-height: 100px; min-width: 100px;">
            <nav class="easyui-droppable targetarea" id="target1" style="height: 100%; min-height: 100%;">

                <?php $sql = sql_select("select * from user_module_favorite where userid = $_SESSION[id] and status = 1");

                foreach ($sql as $item) {
                    $r = rand(0, 255);
                    $g = rand(0, 255);
                    $b = rand(0, 215);
                    $renk = dechex($r) . dechex($g) . dechex($b); ?>

                    <div style="display: flex; cursor: pointer;">
                        <i class="fa-thin fa-circle-xmark favori-modul-kaldir" data-id="<?php echo $item['id']; ?>" title="Favori Listesinden Kaldır" style="position: relative; color:white; margin-top: 10px; right: 0px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="text-white modul-sec" adi="<?php echo $item['module_name']; ?>" id="<?php echo $item['url']; ?>" id-2="<?php echo $item['module_name']; ?>"><i class="fa-solid fa-objects-column" style="color:#<?php echo $renk; ?>;"></i> <?php echo $item['module_name']; ?></a>
                    </div>

                <?php } ?>

            </nav>
        </div>
    </section>

    <section class="f-item tiles-con" style="max-width: 650px; overflow-y: auto; min-width: 650px;">
   <i class="fa-solid fa-xmark fa-2x" id="win-menu-close" style="color:white; position:absolute; right:0px; top:0px; cursor:pointer;"></i>
        <div class="tiles-sec tiles-sec-1">
            <header>
                <h3>Modüller</h3>
            </header>
            <div class="tilsses-1">

                <div class="wrap" id="modul-list" style="color:white;">

                    <div class="btn-small orange modul-sec drag" adi="Yatış" id="yatis" id-2="Yatış"><i class="fa-solid fa-bed-pulse fa-2x"></i><span class="label bottom">Yatış</span></div>
                    <div class="btn-small red drag modul-sec fav-popup" adi="Hasta Kayıt" id="hastakayit" id-2="Hastakayıt"><i class="fa-solid fa-users-medical fa-2x"></i> <span class="label bottom">Hasta Kayıt</span></div>
                    <div class="btn-small blue drag  modul-sec fav-popup" adi="Poliklinik" id="hasta-kabul" id-2="Poliklinik"><i class="fa-sharp fa-solid fa-notes-medical fa-2x"></i><span class="label bottom">Poliklinik</span></div>
                    <div class="btn-small green drag modul-sec fav-popup " adi="Tanımlar" id="tanimlamalar" id-2="Yönetim"><i class="fa-solid fa-people-roof fa-2x"></i><span class="label bottom">Yönetim</span></div>

                </div>

            </div>
        </div>
    </section>

</div>





<script>
    $(function(){
        $('.drag').draggable({
            proxy:'clone',
            revert:true,
            cursor:'auto',

            onStartDrag:function(){
                $(this).draggable('options').cursor='not-allowed';
                $(this).draggable('proxy').addClass('dp');
            },

            onStopDrag:function(){
                $(this).draggable('options').cursor='auto';
            }

        });

        $('#target1').droppable({
            onDragEnter:function(e,source){
                $(source).draggable('options').cursor='auto';
                $(source).draggable('proxy').css('border','1px solid red');
                $(this).addClass('over');
            },
            onDragLeave:function(e,source){
                $(source).draggable('options').cursor='not-allowed';
                $(source).draggable('proxy').css('border','1px solid #ccc');
                $(this).removeClass('over');
            },
            onDrop:function(e,source){
                $(this).append(source);

                $(this).removeClass('over');
                var module_name =  $(source).attr("adi");
                var url =  $(source).attr("id");
                $.ajax({
                    type: "post",
                    url: "controller/windows/index.php?islem=favori-modul-ekle",
                    data: {module_name:module_name,url:url},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("controller/windows/index.php?islem=favori-moduller", {}, function (getveri) {
                            $(".targetarea").html(getveri);
                        });
                    }
                });

                $(source).hide();

            }
        });
    });

    $("body").off("click", "#win-menu-close").on("click", "#win-menu-close", function (e) {
        $('.desktop-start').trigger("click");
    });

    $("body").off("click", ".favori-modul-kaldir").on("click", ".favori-modul-kaldir", function (e) {
        var id = $(this).attr("data-id");
        $.ajax({
            type: "post",
            url: "controller/windows/index.php?islem=favori-modul-kaldir",
            data: {id:id},
            success: function (e) {
                $("#sonucyaz").html(e);
                $.get("controller/windows/index.php?islem=favori-moduller", {}, function (getveri) {
                    $(".targetarea").html(getveri);
                });
            }
        });
    });

    $("#modul-ara").on("keyup", function () {
        var filter = $(this).val();
        var count_filter = filter.length;
        var path = window.location.pathname
        if(path=="/" || path=="/index.php" ){

            $(".dom-701").each(function () {
                if (count_filter > 1) {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).parent().parent().removeClass("bg-yesil text-white");
                    } else {
                        $(this).parent().parent().addClass("bg-yesil text-white");
                    }
                }else{
                    $(".dom-701").parent().parent().removeClass("bg-yesil text-white");
                }
            });
//**********************************************************************************************************************
        } else {
            if (count_filter > 1) {
                $(".btn-small").each(function () {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show();
                        var sonuc = $(this).parent().html();
                        $('#modul_arama_sonucunu_goster').html(sonuc);
                    }
                });
            } else {
                $('#modul_arama_sonucunu_goster').html("");
            }
        }

    });

</script>
