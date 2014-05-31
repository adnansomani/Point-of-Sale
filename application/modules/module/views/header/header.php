
<script type="text/javascript" charset="utf-8">
          $(document).ready( function () {
           
                    $('#add_module_form').hide();
                    $('#edit_module_form').hide();
                              posnic_table();
                               
                                parsley_reg.onsubmit=function()
                                { 
                                  return false;
                                } 
                         
                        } );
                        
           function posnic_table(){
           $('#dt_table_tools').dataTable({
                                      "bProcessing": true,
				      "bServerSide": true,
                                      "sAjaxSource": "<?asp echo base_url() ?>index.asp/module/module_data_table",
                                       aoColumns: [  
                                    
         { "bVisible": false} , {	"sName": "ID",
                   						"bSearchable": false,
                   						"bSortable": false,
                                                                
                   						"fnRender": function (oObj) {
                   							return "<input type=checkbox value='"+oObj.aData[0]+"' ><input type='hidden' id='name_"+oObj.aData[0]+"' value='"+oObj.aData[2]+"'>";
								},
								
								
							},
        
        null, 

 							{	"sName": "ID",
                   						"bSearchable": false,
                   						"bSortable": false,
                                                                
                   						"fnRender": function (oObj) {
                   							if(oObj.aData[5]==1){
                                                                            return 'Core';
                                                                        }else{
                                                                            return 'No Core';
                                                                        }
								},
								
								
							},
 							{	"sName": "ID1",
                   						"bSearchable": false,
                   						"bSortable": false,
                                                                
                   						"fnRender": function (oObj) {
                                                                if(oObj.aData[5]==1){
                   							return '&nbsp<a ><span data-toggle="tooltip" class="label label-info hint--top hint--info" ><i class="icon-edit"></i></span></a>'+"&nbsp;<a  ><span data-toggle='tooltip' class='label label-danger hint--top hint--error' ><i class='icon-trash'></i></span> </a>";
								}else{
                                                                        return '&nbsp<a href=javascript:edit_function("'+oObj.aData[0]+'") ><span data-toggle="tooltip" class="label label-info hint--top hint--info" data-hint="<?asp echo $this->lang->line('edit') ?>"><i class="icon-edit"></i></span></a>'+"&nbsp;<a href=javascript:user_function('"+oObj.aData[0]+"'); ><span data-toggle='tooltip' class='label label-danger hint--top hint--error' data-hint='<?asp  echo $this->lang->line('delete'); ?>'><i class='icon-trash'></i></span> </a>";
                                                                }
                                                                },
								
								
							},

 							

 						]
		}
						
						
                                    
                                    );
                                   
			}
    function user_function(guid){
    var module=$('#name_'+guid).val();
    <?asp if($this->session->userdata['module_per']['delete']==1){ ?>
             bootbox.confirm("Are you Sure To Delete This module ("+module+")", function(result) {
             if(result){
            $.ajax({
                url: '<?asp echo base_url() ?>/index.asp/module/delete',
                type: "POST",
                data: {
                    guid: guid
                    
                },
                success: function(response)
                {
                    if(response){
                          $.bootstrapGrowl('<?asp echo $this->lang->line('module') ?> '+module+' <?asp echo $this->lang->line('deleted');?>', { type: "danger" });
                        $("#dt_table_tools").dataTable().fnDraw();
                    }}
            });
        

                        }
    }); <?asp }else{?>
             $.bootstrapGrowl('<?asp echo $this->lang->line('You Have NO Permission To Delete')." ".$this->lang->line('module');?>', { type: "error" });         
   <?asp }
?>
                        }
            function posnic_deactive(guid){
                var module=$('#name_'+guid).val();
                $.ajax({
                url: '<?asp echo base_url() ?>index.asp/module/deactive',
                type: "POST",
                data: {
                    guid: guid
                    
                },
                success: function(response)
                {
                    if(response){
                         $.bootstrapGrowl('<?asp echo $this->lang->line('module') ?> '+module+' <?asp echo $this->lang->line('isdeactivated');?>', { type: "danger" });
                        $("#dt_table_tools").dataTable().fnDraw();
                    }
                }
            });
            }
            function posnic_active(guid){
            var module=$('#name_'+guid).val();
                           $.ajax({
                url: '<?asp echo base_url() ?>index.asp/module/active',
                type: "POST",
                data: {
                    guid: guid
                    
                },
                success: function(response)
                {
                    if(response){
                         
                          $.bootstrapGrowl('<?asp echo $this->lang->line('module') ?> '+module+' <?asp echo $this->lang->line('isactivated');?>', { type: "success" });
                        $("#dt_table_tools").dataTable().fnDraw();
                    }
                }
            });
            }
           function edit_function(guid){
                       $("#parsley_reg").trigger('reset');
                       $("#update_button").show();
                       $("#add_button").hide();
                        <?asp if($this->session->userdata['module_per']['edit']==1){ ?>
                            $.ajax({                                      
                             url: "<?asp echo base_url() ?>index.asp/module/edit_module/"+guid,                      
                             data: "", 
                             dataType: 'json',               
                             success: function(data)        
                             {    
                                 $("#user_list").hide();
                                 $('#add_module_form').show('slow');
                                 $('#delete').attr("disabled", "disabled");
                                 $('#posnic_add_module').attr("disabled", "disabled");
                                 $('#active').attr("disabled", "disabled");
                                 $('#deactive').attr("disabled", "disabled");
                                 $('#module_lists').removeAttr("disabled");
                                 $('#parsley_reg #guid').val(data[0]['guid']);
                                 $('#parsley_reg #module_name').val(data[0]['Category_name']);
                                 $('#parsley_reg #icon_class').val(data[0]['icon_class']);
                                 $('#parsley_reg #order').val(data[0]['order']);
                               
                             } 
                           });
                         
                        
                              
                         
                        <?asp }else{?>
                                bootbox.alert("<?asp echo $this->lang->line('You Have NO permission To Edit This Records') ?>");
                        <?asp }?>
                       }
		</script>
                <script type="text/javascript" charset="utf-8" language="javascript" src="<?asp echo base_url() ?>template/data_table/js/DT_bootstrap.js"></script>


  