<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tabs Tools - jQuery EasyUI Demo</title>
    <link rel="stylesheet" type="text/css" href="themes/black/easyui.css"> 
    <link rel="stylesheet" type="text/css" href="demo/demo.css">
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="jquery.easyui.min.js"></script>
</head>
<body> 
    <div style="margin:20px 0;"></div>
    <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'" style="width:700px;height:250px">
    </div>
    <div id="tab-tools">
        <a href="javascript:void(0)" class="btntanimla easyui-linkbutton" data-options="plain:true,iconCls:'icon-add'" id="branstanimla">Branş Tanımla</a>
        <a href="javascript:void(0)" class="btntanimla easyui-linkbutton" data-options="plain:true,iconCls:'icon-add'" id="islemler">İşlem Tanımla</a>
        <a href="javascript:void(0)" class="btntanimla easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove'" onclick="removePanel()"></a>
    </div>
    <script type="text/javascript">
        var index = 0; 
    
        function removePanel(){
            var tab = $('#tt').tabs('getSelected');
            if (tab){
                var index = $('#tt').tabs('getTabIndex', tab);
                $('#tt').tabs('close', index);
            }
        }
		
		
		
		   
                $(document).ready(function () {
                    $(".btntanimla").on("click", function(){
                         var secilen=$(this).attr('id');
                        $.get( "../ajax/tanimlar/"+secilen+".php?islem=listeyi-getir",function(get){
                            $('#tt').tabs('add',{
                title: secilen,
                content: '<div style="padding:10px">'+get+'</div>',
                closable: false
            }); 
                        });
                    });
                });
				
				
    </script>
</body>
</html>