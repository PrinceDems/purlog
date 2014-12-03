<script>
	
	var url;
	$(document).ready(function(){

		listSRO = function (){
			$('#dialog').dialog({
				title: 'List Shipment Request Order',
				width: $(window).width() * 0.8,
				height: $(window).height() * 0.99,
				closed: true,
				cache: false,
				href: base_url+'delivery_order/listSRO',
				modal: true
			});
			 
			$('#dialog').dialog('open');
			url = base_url+'departement/save/add';
		}
				
		actiondetail = function(value, row, index){
			var col='';
					col += '&nbsp;&nbsp; | &nbsp;&nbsp;<a href="#" onclick="detailData(\''+row.id+'\');" class="easyui-linkbutton" iconCls="icon-edit" plain="false">Detail</a>';			
					col += '&nbsp;&nbsp; | &nbsp;&nbsp;<a href="#" onclick="detailData(\''+row.id+'\');" class="easyui-linkbutton" iconCls="icon-edit" plain="false">Cancel</a>';			
			return col;
		}

		//# Tombol Bawah
		$(function(){
			var pager = $('#dg').datagrid().datagrid('getPager');	// get the pager of datagrid
			pager.pagination({
				buttons:[
					{
						iconCls:'icon-add',
						text:'Alocate All',
						handler:function(){
							newData();
						}
					}				
				]
			});			
		});
		

		$(function(){ // init
			$('#dtgrd').datagrid({url:"delivery_order/grid"});	
			//$('#dg').datagrid('enableFilter'); 
		});	
		
	});
</script>

<table id="dtgrd" data-options="
			rownumbers:true,
			singleSelect:false,
			autoRowHeight:false,
			fit:true,
			toolbar:'#toolbar_detail',
		">		
	<thead>
		<tr>
			<th data-options="field:'id_krs_detail',width:'100', hidden:true">aa</th>
			<th field="nama_kategori" sortable="true" width="120">ID Request Order</th>
			<th field="kode_barang" sortable="true" width="120">ID Ext Document</th>
			<th field="kode_barang" sortable="true" width="120">ID Barang</th>
			<th field="nama_sub_kategori" sortable="true" width="120">Nama Barang</th>
			<th field="kode_barang" sortable="true" width="120">Qty</th>
			<th field="kode_barang" sortable="true" width="120">Requestor </th>
			<th field="kode_barang" sortable="true" width="120">Date Create</th>
			<th field="kode_barang" sortable="true" width="120">Note</th>			
			<th field="action" align="center" formatter="actiondetail" width="140">Aksi</th>
		</tr>
	</thead>
</table>
<div id="toolbar_detail" style="padding:5px;height:auto">
	<div>
		<table>
			<tr>
					<td>&nbsp;&nbsp;<a href="#" onclick="listSRO()" class="easyui-linkbutton" iconCls="icon-detail">Add SRO</a></td>					
			</tr>
			<tr> 
					<td>&nbsp;</td>
			</tr>		
			<tr> 
				<td>
						<label style="width:120px">SRO </label>:

						<select id="#" name="#" style="width:200px;">
						</select>
						&nbsp;&nbsp;<a href="#" onclick="filter()" class="easyui-linkbutton" iconCls="icon-ok">Done</a>
				</td>
			</tr>			
		</table>
	</div>
</div>


