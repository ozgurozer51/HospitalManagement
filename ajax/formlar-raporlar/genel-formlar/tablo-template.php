<?php
$uniqid = uniqid();
$uniqid_2 = uniqid();
?>

<div style="height:50%; max-height:50%;">
    <table id="table-<?php echo $uniqid; ?>" class="easyui-datagrid"  data-options="singleSelect:true , showRefresh:true, fitColumns:true , pagination:false" style="width:100%; height:100%; font-size: 13px;" url="ajax/formlar-raporlar/genel-formlar/gecmis-rapor-form.php?islem=hasta-gecmis-islemleri&referance_table_name=<?php echo $_GET['name']; ?>&patient_id=<?php echo $_GET['patient_id']; ?>" iconCls="icon-save" rownumbers="true">
        <thead field="id">
        <tr>
            <th field="form_id">ID</th>
            <th field="insert_datetime">Eklenme Tarihi</th>
            <th field="name_surname">Ekleyen Kullanıcı</th>
        </tr>
        </thead>
    </table>
</div>

<input type="hidden" class="form-id-2-<?php echo $uniqid; ?>">
<input type="hidden" class="form-id-3-<?php echo $uniqid; ?>">

<div id="silme-detay" class="easyui-dialog" title="Silme İşlemini Onayla" style="width:400px;height:200px;" data-options="iconCls:'icon-cancel',closed:true,resizable:false,modal:true ,
 buttons: [{
                    text:'Onayla',
                    iconCls:'icon-ok',
                    handler:function(){
                    delete_form();
           }
         }]">

    <label>Silme Nedeni Belirtiniz:</label>
    <input class="easyui-textbox" id="delete_detail_form" style="width:100%"></div>

<script>
    var uniquid = $(".w").find('form:visible').attr("uniquid");
    $('#table-<?php echo $uniqid; ?>').datagrid({
        onSelect: function (index, row) {

            var form_id = $(".w").find('form:visible').attr("id");
            var uniquid = $(".w").find('form:visible').attr("uniquid");
            var form_table_name =   $("#" + form_id).find(".form-table-name").attr("value-2");

            var name =   form_table_name;
            var adi =    $('#hasta-adi-soyadi-'+uniquid).val();
            var tc =     $('#hasta-tc-no-'+uniquid).val();
            var birim =  $('#hasta-birim-id-'+uniquid).val();
            var doktor = $('#hasta-doktoru-'+uniquid).val();

            $('#ff-'+uniquid).form('clear');

            $('#hasta-adi-soyadi-'+uniquid).textbox('setValue', adi);
            $('#hasta-tc-no-'+uniquid).textbox('setValue',tc);
            $('#hasta-birim-id-'+uniquid).textbox('setValue',birim);
            $('#hasta-doktoru-'+uniquid).textbox('setValue',doktor);

            $('#ff-'+uniquid).form('load', 'ajax/formlar-raporlar/genel-formlar/hasta-gecmis-rapor-form.php?islem=hasta-gecmis-form-getir&id="'+row.form_id+'"&name="'+name+'"');
            $('.form-id-2-'+uniquid).val(row.form_id);
            $('.form-id-3-'+uniquid).val(row.id);
        }
    });

    $("body").off("click", "#update-tool").on("click", "#update-tool", function (e) {
        var uniquid = $(".w").find('form:visible').attr("uniquid");

        var control_1 = $('.form-id-2-'+uniquid).val();
        var control_2 = $('.form-id-3-'+uniquid).val();
        if(control_1 && control_2){

        var form_id = $(".w").find('form:visible').attr("id");
        var form_data = $("#" + form_id).serialize();

        var form_table_name =   $("#" + form_id).find(".form-table-name").attr("value-2");

        var from_id = $('.form-id-2-'+uniquid).val();
        form_data += "&table_name="+form_table_name;
        form_data += "&form-id="+from_id;
        $.ajax({
            type: 'POST',
            url: 'ajax/formlar-raporlar/genel-formlar/forms-sql.php?islem=hasta-form-guncelle',
            data: form_data,
            success: function (e) {
              $(".sonucyaz").html(e);
            }
        });
    }else{
        alertify.set('notifier', 'delay', 8);
        alertify.success('İşlemi Gerçekleştirmeniz İçin Listeden Seçim Yapınız!');
    }
    });

    $("body").off("click", "#delete-tool").on("click", "#delete-tool", function (e) {
        var uniquid = $(".w").find('form:visible').attr("uniquid");
        var control_1 = $('.form-id-2-'+uniquid).val();
        var control_2 = $('.form-id-3-'+uniquid).val();

        if(control_1 && control_2){
        $('#silme-detay').dialog('open');
        }else{
            alertify.set('notifier', 'delay', 8);
            alertify.success('İşlemi Gerçekleştirmeniz İçin Listeden Seçim Yapınız!');
        }

    });

    function delete_form(){

        var uniquid = $(".w").find('form:visible').attr("uniquid");
        var name = $(".w").find('form:visible').attr("table-name");

        var delete_detail = $('#delete_detail_form').val();
        var form_id =  $('.form-id-2-'+uniquid).val();
        var patients_reports_forms_id =  $('.form-id-3-'+uniquid).val();
        $.ajax({
             type: 'GET',
             url: 'ajax/formlar-raporlar/genel-formlar/forms-sql.php?islem=hasta-form-sil',
             data: { form_id:form_id , name:name  , patients_reports_forms_id:patients_reports_forms_id , delete_detail:delete_detail},
             success: function (e) {
                 $(".sonucyaz").html(e);
                 $('#silme-detay').dialog('close');
                 setTimeout(function (){
                     $('#table-'+uniquid).datagrid('reload');
                 },500);
             }
         });
    }

    $("body").off("click", "#save-tool").on("click", "#save-tool", function (e) {

        var form_id = $(".w").find('form:visible').attr("id");
        var uniquid = $(".w").find('form:visible').attr("uniquid");

        var form = $("#" + form_id).serialize();

        var form_table_name =   $("#" + form_id).find(".form-table-name").attr("value-2");

        form += "&table_name=" + form_table_name;
        $.ajax({
            type: 'POST',
            url:  "ajax/formlar-raporlar/genel-formlar/forms-sql.php?islem=form-ekle",
            data:form ,
            success: function (e) {
                $(".sonucyaz").html(e);
                $('#table-'+uniquid).datagrid('reload');
            }
        });
    });

    $("body").off("click", "#print-tool").on("click", "#print-tool", function (e) {

     var uniquid = $(".w").find('form:visible').attr("uniquid");
     var print_url = $(".w").find('form:visible').attr("print-url");
     var form_adi = $(".w").find('form:visible').attr("form-adi");

     var control_1 = $('.form-id-2-'+uniquid).val();
     var control_2 = $('.form-id-3-'+uniquid).val();

     if(control_1 >0 && control_2 >0){
      var url = print_url;

        $.ajax({
            type: 'POST',
            url:  "<?php echo $_GET['print_url']; ?>",
            data: {uniquid:uniquid , form_adi:form_adi},
            success: function (e){

               $("#pp3-"+uniquid).html(e);
               $("#pp3-"+uniquid).find("#print-form-ana-icerik-dom").css("font-size" , "7px");

                printJS({printable: 'pp3-'+uniquid, type: 'html',style: ['@page { size: A4; margin: 5mm;} body {margin: 0;} h5 {margin:0}'],  targetStyles: ['*']});
                // printJS('pp3', 'html');
            }
        });
     }else{
         alertify.set('notifier', 'delay', 8);
         alertify.success('İşlemi Gerçekleştirmeniz İçin Listeden Seçim Yapınız!');
     }

    });

    $("body").off("click", "#pdf-tool").on("click", "#pdf-tool", function (e) {

        var uniquid = $(".w").find('form:visible').attr("uniquid");
        var url = $(".w").find('form:visible').attr("print-url");

        var control_1 = $('.form-id-2-'+uniquid).val();
        var control_2 = $('.form-id-3-'+uniquid).val();
        if(control_1 && control_2){

        var adi = $('#hasta-adi-soyadi-'+uniquid).val();
        var tc = $('#hasta-tc-no-'+uniquid).val();

            $.ajax({
                type: 'POST',
                url: url,
                data: { uniquid:uniquid } ,
                success: function (e){
                 $("#pp3-"+uniquid).html(e);
                 $("#pp3-"+uniquid).find('#print-form-ana-icerik-dom').attr("class" , "w-100");

                    var element = document.getElementById('pp3-'+uniquid);
                    var opt = {
                        margin: [0.2, 0.2, 0.2, 0.2],
                        filename:     adi+"-"+tc+".pdf",
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 5 },
                        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
                    };
                    html2pdf(element,opt);
                }
            });

        }else{
            alertify.set('notifier', 'delay', 8);
            alertify.success('İşlemi Gerçekleştirmeniz İçin Listeden Seçim Yapınız!');
        }
    });

    $.extend($.fn.datebox.defaults,{
        formatter:function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'.'+(m<10?('0'+m):m)+'.'+(d<10?('0'+d):d);
        },
    });

</script>


<div style="display:none;">
<div id="pp3-<?php echo $uniqid; ?>"></div>
</div>