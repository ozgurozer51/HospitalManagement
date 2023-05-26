<!-- DATATABLE - TREE TABLE -->

<style>
    .acik{
        color: darkgreen;
        margin-left: 25%;
        margin-right: 25%;

    }
    .kapali{
        color: red;
        margin-left: 25%;
        margin-right: 25%;

    }
</style>


<table id="test_table" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">
    <thead>
    <tr>
        <th></th>
        <th>Drum</th>
        <th>Tetkik Adı</th>
        <th>İşlem Tarihi</th>
        <th>Laborant Onay Tarihi</th>
        <th>Onaylayan Laborant</th>
        <th>Uzman Onay Tarihi</th>
        <th>Onaylayan Uzman</th>
        <th>Açıklama</th>
        <th>Cihaz Adı</th>
    </tr>
    </thead>
</table>


<script>
    function format(d) {
        // console.log(d[0].split('/')[1]);
        return (
            '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
            '<thead>'+
            '<tr>' +
            '<th>Alt Parametre</th>' +
            '<th>Tekrar Çalışma Sayısı</th>' +
            '<th>Sonuç</th>' +
            '<th>Birim</th>' +
            '<th>Referans Aralığı</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody class="eklenecek'+ d[0].split('/')[1] +'"></tbody>'+
            '</table>'
        );
    }

    $(document).ready(function () {
        var test_dt = $('#test_table').DataTable({
            // deferRender: true,
            scrollY: true,
            scrollX: true,
            // "serverSide": true,
            "info": false,
            "paging": false,
            // "deferLoading": 0,
            "searching": false,
            "fnRowCallback": function (nRow, aData) {
               $(nRow).addClass("treeTable_icerik");
               $(nRow).addClass("avratsiz");
            },
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"}
        });
        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': 375}, function (result) {
            if (result != 2) {
                var json = JSON.parse(result);
                json.forEach(function (item) {
                    var basilacak = "";
                    if (item.tetkik_sonuc_durum == 0){
                        basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 40%; margin-right: 40%; ' ></i>";
                    }else if (item.tetkik_sonuc_durum == 1){
                        basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 40%; margin-right: 40%;'></i>";
                    }else if (item.tetkik_sonuc_durum == 2){
                        basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 40%; margin-right: 40%;'></i>";
                    }

                     let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, item.sonuc_tarihi, item.laborant_onay_tarihi, item.onaylayan_laborant, item.lab_uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                    $(row).attr("data-id",item.idsi);
                });
            }

        });

        $("body").off("click",".treeTable_icerik").on("click",".treeTable_icerik",function (){
            var id = $(this).attr("data-id");
            var tr = $(this).closest('tr');
            var row = test_dt.row(tr);
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }

            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                if (result != 2){
                    var json = JSON.parse(result);
                    json.forEach(function (item){

                        var tektrar_calisma = item.tektrar_calisma_sayisi;
                        if (tektrar_calisma == null){
                            tektrar_calisma = "";
                        }


                        $(".eklenecek" + id).append(
                            "<tr>"+
                            "<td>"+item.parametre_adi+"</td>" +
                            "<td>"+tektrar_calisma+"</td>" +
                            "<td>"+item.tetkik_sonucu+"</td>" +
                            "<td>"+item.birimadi+"</td>" +
                            "<td>"+item.alt_limit+' - '+item.ust_limit+"</td>" +
                            "</tr>");
                    });
                }
            });
        });

    });

    $("body").off("click",".iconudegistir").on("click",".iconudegistir",function (){
        var classvarmi=$(this).hasClass('ekle');
        if (classvarmi) {
            $(this).removeClass('fa-circle-minus');
            $(this).addClass('fa-circle-plus');
            $(this).removeClass('ekle');
            $(this).removeClass('kapali');
            $(this).addClass('acik');


        } else {
            $(this).addClass('fa-circle-minus');
            $(this).removeClass('fa-circle-plus');
            $(this).addClass('ekle');
            // $(this).removeAttr("style")
            $(this).removeClass('acik');
            $(this).addClass('kapali');
        }

    });
</script>

