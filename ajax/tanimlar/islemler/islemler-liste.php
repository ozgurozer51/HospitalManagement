<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$islem=$_GET["islem"];
$KULLANICI_ID = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');

 if($islem="islem-grup-listesi"){ ?>
    <div class="card" style=" height: 100%; ">
        <div class="card-body">

                <table class="table table-bordered nowrap display w-100 mt-2" id="grup-listesi" style="cursor: pointer;" >
                    <thead>
                    <tr>
                        <th>grup kodu</th>
                        <th>i̇şlem grubu</th>
                        <th>sgk grubu</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php $sql ="select * from transaction_definitions where definition_type='ISLEM_GRUBU' and status='1' ";
                    $hello = verilericoklucek($sql);
                    foreach ($hello as $row) {
                        $tanimeki= $row["definition_supplement"];  ?>
                        <tr>
                            <th  scope="row" id='<?php echo $row["id"]; ?>'><?php echo $row["id"]; ?></th>
                            <td class='islemgetir' id='<?php echo $row["id"]; ?>'><?php echo $row["definition_name"]; ?></td>
                            <td class='islemgetir' id='<?php echo $row["id"]; ?>'><?php echo islemtanimgetir($row["definition_supplement"]); ?></td>
                            <th scope="row"><button id='<?php echo $row["id"]; ?>'  type="button" class="btn btn-danger btn-sm islemsil islem-grubu-sil"><i class="fa-solid fa-trash"></i></button>
                                <button  id='<?php echo $row["id"]; ?>' type="button" data-bs-target="#islem-crud" data-bs-toggle="modal" class="btn btn-success btn-sm islem-update">  <i class="fa-solid fa-edit"></i></button>
                            </th>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>

        </div>
    </div>

     <script>
         $('#grup-listesi').DataTable({
             "scrollY": false,
             "scrollX": false,
             "paging": true,
             'Visible': true,
             "responsive": true,
             "pageLength": 50,
             dom: 'Bfrtp',
             buttons: [
                 {
                     text: 'İşlem Grubu Ekle',
                     className: 'btn btn-success btn-sm',
                     attr:  {
                         'data-bs-target': 'Copy',
                         'data-bs-toggle':"modal",
                         'data-bs-target':"#islem-crud",
                     }

                 }
             ],
             "language": {
                 "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
             },

         });
     </script>

<?php } ?>