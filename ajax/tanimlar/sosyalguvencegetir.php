<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
?>

<div class="modal-dialog" style=" width: 90%; max-width: 95%; ">
    <form class="modal-content"  action="sosyalguvencetanimla" method="post" >
        <div class="modal-header">
            <button type="button" class="close border-danger bg-danger text-white" data-dismiss="modal">X</button>
            <?php  if ($_GET["guvenceid"] != "") {
                echo '<h4 class="modal-title">kurum düzenle</h4>';
                $birimbilgisi = singular("transaction_definitions", "definition_code", $_GET["guvenceid"]);
                extract($birimbilgisi);
            } else { echo '<h4 class="modal-title">kurum ekle</h4>'; }  ?>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-12 row">
                    <div class="col-6">
                        <label for="basicpill-firstname-input" class="form-label">skrs kodu</label>
                        <input type="number" class="form-control" name="definition_code" value="<?php echo $definition_code; ?>" id="basicpill-firstname-input">
                        <?php if ($birimbilgisi["id"]){ ?>
                            <input type="hidden" class="form-control" name="id" value="<?php echo $birimbilgisi["id"]; ?>" id="basicpill-firstname-input">
                       <?php  } ?>
                    </div>
                    <div class="col-6">
                        <label for="basicpill-firstname-input" class="form-label">kurum adı</label>
                        <input type="text" class="form-control" name="definition_name" value="<?php echo $definition_name; ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <?php  if ($_GET["guvenceid"] != "") { ?>
                <a onclick="return confirm('kullancıyı silmek istediğinize emin misiniz ? ');"       href="sosyalguvencetanimla?islem=sil&islemid=<?php echo $id; ?>"><button type="button" class="btn btn-default"   style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">sil</button></a>
                <input class="btn btn-primary w-md justify-content-end" name="kurumguncelle" type="submit"   value="düzenle"/>
                <?php } else { ?>
                <input class="btn btn-primary w-md justify-content-end" name="kurumkaydet" type="submit"   value="kaydet"/>
                <?php } ?>
            <button type="button" class="btn btn-default" data-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">kapat</button>
        </div>

    </form>
</div>
</div>