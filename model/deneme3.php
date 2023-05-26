<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Build CRUD DataGrid with jQuery EasyUI - jQuery EasyUI Demo</title>
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/demo/demo.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.edatagrid.js"></script>
</head>
<body>
<h2>CRUD DataGrid</h2>
<p>Double click the row to begin editing.</p>

<table id="dg" title="My Users" style="width:700px;height:250px"
       toolbar="#toolbar" pagination="true" idField="idsi"
       rownumbers="true" fitColumns="true" singleSelect="true">
    <thead>
    <tr>
        <th field="idsi" width="50" editor="{type:'validatebox',options:{required:true}}">First Name</th>
        <th field="idsi" width="50" editor="{type:'validatebox',options:{required:true}}">Last Name</th>

    </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">New</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Destroy</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>
</div>
<script type="text/javascript">
    $(function(){
        $('#dg').edatagrid({
            url: 'ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay&service_requestsid=370',
            saveUrl: 'save_user.php',
            updateUrl: 'update_user.php',
            destroyUrl: 'destroy_user.php'
        });
    });
</script>

</body>
</html>