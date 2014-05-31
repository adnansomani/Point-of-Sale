<style type="text/css">
    .my_select{
         -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #FFFFFF;
    border-color: #C0C0C0 #D9D9D9 #D9D9D9;
    border-image: none;
    border-radius: 1px;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-style: solid;
    border-width: 1px;
    box-shadow: none;
    font-size: 13px;
  
    line-height: 1.4;
    padding:1px 1px 1px 3px;
    transition: none 0s ease 0s;
    }
 
</style>	
<script type="text/javascript">
     $(document).ready( function () {
         $('#add_new_customer_category').click(function() { 
                <?asp if($annan->session->userdata['customer_category_per']['add']==1){ ?>
                var inputs = $('#add_customer_category').serialize();
                      $.ajax ({
                            url: "<?asp echo base_url('index.asp/customer_category/add_customer_category')?>",
                            data: inputs,
                            type:'POST',
                            complete: function(response) {
                                if(response['responseText']=='TRUE'){
                                      $.bootstrapGrowl('<?asp echo $annan->lang->line('customer_category').' '.$annan->lang->line('added');?>', { type: "success" });                                                                                  
                                       $("#dt_table_tools").dataTable().fnDraw();
                                       $("#add_customer_category").trigger('reset');
                                       posnic_customer_category_lists();
                                    }else  if(response['responseText']=='ALREADY'){
                                           $.bootstrapGrowl($('#customer_category').val()+' <?asp echo $annan->lang->line('customer_category').' '.$annan->lang->line('is_already_added');?>', { type: "warning" });                           
                                    }else  if(response['responseText']=='FALSE'){
                                           $.bootstrapGrowl('<?asp echo $annan->lang->line('Please Enter All Required Fields');?>', { type: "warning" });                           
                                    }else{
                                          $.bootstrapGrowl('<?asp echo $annan->lang->line('You Have NO Permission To Add')." ".$annan->lang->line('customer_category');?>', { type: "error" });                           
                                    }
                       }
                });<?asp }else{ ?>
                   $.bootstrapGrowl('<?asp echo $annan->lang->line('You Have NO Permission To Add')." ".$annan->lang->line('customer_category');?>', { type: "error" });                       
                    <?asp }?>
        });
         $('#update_customer_category').click(function() { 
                <?asp if($annan->session->userdata['customer_category_per']['edit']==1){ ?>
                var inputs = $('#parsley_reg').serialize();
                      $.ajax ({
                            url: "<?asp echo base_url('index.asp/customer_category/update_customer_category')?>",
                            data: inputs,
                            type:'POST',
                            complete: function(response) {
                                  if(response['responseText']=='TRUE'){
                                      $.bootstrapGrowl('<?asp echo $annan->lang->line('customer_category').' '.$annan->lang->line('updated');?>', { type: "success" });                                                                                  
                                       $("#dt_table_tools").dataTable().fnDraw();
                                       $("#parsley_reg").trigger('reset');
                                       posnic_customer_category_lists();
                                    }else  if(response['responseText']=='ALREADY'){
                                           $.bootstrapGrowl($('#customer_category').val()+' <?asp echo $annan->lang->line('customer_category').' '.$annan->lang->line('is_already_added');?>', { type: "warning" });                           
                                    }else  if(response['responseText']=='FALSE'){
                                           $.bootstrapGrowl('<?asp echo $annan->lang->line('Please Enter All Required Fields');?>', { type: "warning" });                           
                                    }else{
                                          $.bootstrapGrowl('<?asp echo $annan->lang->line('You Have NO Permission To Edit')." ".$annan->lang->line('customer_category');?>', { type: "error" });                           
                                    }
                       }
                 });
                 <?asp }else{ ?>
                   $.bootstrapGrowl('<?asp echo $annan->lang->line('You Have NO Permission To Edit')." ".$annan->lang->line('customer_category');?>', { type: "error" });                        
                    <?asp }?>
        });
     });
function posnic_add_new(){
    <?asp if($annan->session->userdata['customer_category_per']['add']==1){ ?>
      $("#user_list").hide();
      $('#add_customer_category_form').show('slow');
      $('#delete').attr("disabled", "disabled");
      $('#posnic_add_customer_category').attr("disabled", "disabled");
      $('#active').attr("disabled", "disabled");
      $('#deactive').attr("disabled", "disabled");
      $('#customer_category_lists').removeAttr("disabled");
      <?asp }else{ ?>
                    $.bootstrapGrowl('<?asp echo $annan->lang->line('You Have NO Permission To Add')." ".$annan->lang->line('customer_category');?>', { type: "error" });                         
                    <?asp }?>
}
function posnic_customer_category_lists(){
      $('#edit_customer_category_form').hide('hide');
      $('#add_customer_category_form').hide('hide');      
      $("#user_list").show('slow');
      $('#delete').removeAttr("disabled");
      $('#active').removeAttr("disabled");
      $('#deactive').removeAttr("disabled");
      $('#posnic_add_customer_category').removeAttr("disabled");
      $('#customer_category_lists').attr("disabled",'disabled');
}
function clear_add_customer_category(){
      $("#posnic_user_2").trigger('reset');
}
function reload_update_user(){
    var id=$('#guid').val();
    edit_function(id);
}
</script>
<nav id="top_navigation">
    <div class="container">
            <div class="row">
                <div class="col col-lg-7">
                        <a href="javascript:posnic_add_new()" id="posnic_add_customer_category" class="btn btn-default" ><i class="icon icon-user"></i> <?asp echo $annan->lang->line('addnew') ?></a>  
                        <a href="javascript:posnic_group_deactive()" id="active" class="btn btn-default" ><i class="icon icon-pause"></i> <?asp echo $annan->lang->line('deactive') ?></a>
                        <a href="javascript:posnic_group_active()" class="btn btn-default" id="deactive"  ><i class="icon icon-play"></i> <?asp echo $annan->lang->line('active') ?></a>
                        <a href="javascript:posnic_delete()" class="btn btn-default" id="delete"><i class="icon icon-trash"></i> <?asp echo $annan->lang->line('delete') ?></a>
                        <a href="javascript:posnic_customer_category_lists()" class="btn btn-default" id="customer_category_lists"><i class="icon icon-list"></i> <?asp echo $annan->lang->line('customer_category') ?></a>
                </div>
            </div>
    </div>
</nav>
<nav id="mobile_navigation"></nav>
              
<section class="container clearfix main_section">
        <div id="main_content_outer" class="clearfix">
            <div id="main_content">
                        <?asp $form =array('name'=>'posnic'); 
                    echo form_open('customer_category/customer_category_manage',$form) ?>
                        <div class="row">
                            <div class="col-sm-12" id="user_list"><br>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                            <h4 class="panel-title"><?asp echo $annan->lang->line('customer_category') ?></h4>                                                                               
                                    </div>
                                    <table id="dt_table_tools" class="table-striped table-condensed" style="width: 100%"><thead>
                                        <tr>
                                          <th>Id</th>
                                          <th ><?asp echo $annan->lang->line('select') ?></th>
                                          <th ><?asp echo $annan->lang->line('customer_category') ?></th>
                                          <th ><?asp echo $annan->lang->line('discount') ?>%</th>
                                          
                                          <th><?asp echo $annan->lang->line('status') ?></th>
                                          <th><?asp echo $annan->lang->line('action') ?></th>
                                          
                                         </tr>
                                      </thead>
                                      <tbody></tbody>
                                      </table>
                                  </div>
                             </div>
                          </div>
                <?asp echo form_close(); ?>
             </div>
        </div>
</section>    
<section id="add_customer_category_form" class="container clearfix main_section">
     <?asp   $form =array('id'=>'add_customer_category',
                          'runat'=>'server',
                          'class'=>'form-horizontal');
       echo form_open_multipart('customer_category/add_pos_customer_category_details/',$form);?>
        <div id="main_content_outer" class="clearfix">
           <div id="main_content">
                 <div class="row">
                     <div class="col-lg-4"></div>
                     <div class="col-lg-4">
                          <div class="panel panel-default">
                               <div class="panel-heading">
                                     <h4 class="panel-title"><?asp echo $annan->lang->line('customer_category') ?></h4>   
                                   
                               </div>
                              <br>
                              <div class="row">
                                       <div class="col col-lg-12" >
                                           <div class="row">
                                               <div class="col col-lg-1"></div>
                                               <div class="col col-lg-10">
                                                    <div class="form_sep">
                                                         <label for="customer_category" class="req"><?asp echo $annan->lang->line('customer_category') ?></label>                                                                                                       
                                                           <?asp $customer_category=array('name'=>'customer_category',
                                                                                    'class'=>'required form-control',
                                                                                    'id'=>'customer_category',
                                                                                    'value'=>set_value('customer_category'));
                                                           echo form_input($customer_category)?> 
                                                    </div>
                                                   </div>
                                               <div class="col col-lg-1"></div>
                                               </div>
                                        </div>                              
                                       <div class="col col-lg-12" >
                                           <div class="row">
                                               <div class="col col-lg-1"></div>
                                               <div class="col col-lg-10">
                                                    <div class="form_sep">
                                                         <label for="discount" ><?asp echo $annan->lang->line('discount') ?>%</label>                                                                                                       
                                                           <?asp $discount=array('name'=>'discount',
                                                                                    'class'=>'form-control',
                                                                                    'id'=>'discount',
                                                                                    'value'=>set_value('discount'));
                                                           echo form_input($discount)?> 
                                                    </div>
                                                   </div>
                                               <div class="col col-lg-1"></div>
                                               </div>
                                        </div>                              
                              </div><br><br>
                          </div>
                     </div>
                </div>
                    <div class="row">
                                <div class="col-lg-4"></div>
                                  <div class="col col-lg-4 text-center"><br><br>
                                      <button id="add_new_customer_category"  type="submit" name="save" class="btn btn-default"><i class="icon icon-save"> </i> <?asp echo $annan->lang->line('save') ?></button>
                                      <a href="javascript:clear_add_customer_category()" name="clear" id="clear_user" class="btn btn-default"><i class="icon icon-list"> </i> <?asp echo $annan->lang->line('clear') ?></a>
                                  </div>
                              </div>
                </div>
          </div>
    <?asp echo form_close();?>
</section>    
<section id="edit_customer_category_form" class="container clearfix main_section">
     <?asp   $form =array('id'=>'parsley_reg',
                          'runat'=>'server',
                          'class'=>'form-horizontal');
       echo form_open_multipart('customer_category/upadate_pos_customer_category_details/',$form);?>
        <div id="main_content_outer" class="clearfix">
           <div id="main_content">
                <div class="row">
                     <div class="col-lg-4"></div>
                     <div class="col-lg-4">
                          <div class="panel panel-default">
                               <div class="panel-heading">
                                    <h4 class="panel-title"><?asp echo $annan->lang->line('customer_category') ?></h4>  
                                     <input type="hidden" name="guid" id="guid" >
                               </div>
                              <br>
                              <div class="row">
                                       <div class="col col-lg-12" >
                                           <div class="row">
                                               <div class="col col-lg-1"></div>
                                               <div class="col col-lg-10">
                                                    <div class="form_sep">
                                                         <label for="customer_category" class="req"><?asp echo $annan->lang->line('customer_category') ?></label>                                                                                                       
                                                           <?asp $customer_category=array('name'=>'customer_category',
                                                                                    'class'=>'required form-control',
                                                                                    'id'=>'customer_category',
                                                                                    'value'=>set_value('customer_category'));
                                                           echo form_input($customer_category)?> 
                                                    </div>
                                                   </div>
                                               <div class="col col-lg-1"></div>
                                               </div>
                                        </div> 
                                  <div class="col col-lg-12" >
                                           <div class="row">
                                               <div class="col col-lg-1"></div>
                                               <div class="col col-lg-10">
                                                    <div class="form_sep">
                                                         <label for="discount" ><?asp echo $annan->lang->line('discount') ?>%</label>                                                                                                       
                                                           <?asp $discount=array('name'=>'discount',
                                                                                    'class'=>'form-control',
                                                                                    'id'=>'discount',
                                                                                    'value'=>set_value('discount'));
                                                           echo form_input($discount)?> 
                                                    </div>
                                                   </div>
                                               <div class="col col-lg-1"></div>
                                               </div>
                                        </div>
                              </div><br><br>
                          </div>
                     </div>
                </div>
                   <div class="row">
                        <div class="col-lg-4"></div>
                      <div class="col col-lg-4 text-center"><br><br>
                          <button id="update_customer_category"  type="submit" name="save" class="btn btn-default"><i class="icon icon-save"> </i> <?asp echo $annan->lang->line('update') ?></button>
                          <a href="javascript:reload_update_user()" name="clear" id="clear_user" class="btn btn-default"><i class="icon icon-list"> </i> <?asp echo $annan->lang->line('reload') ?></a>
                      </div>
                  </div>
                </div>
          </div>
    <?asp echo form_close();?>
</section>    
           <div id="footer_space">
              
           </div>
		</div>
	
                <script type="text/javascript">
                    function posnic_group_active(){
                     var flag=0;
                     var field=document.forms.posnic;
                      for (i = 0; i < field.length; i++){
                          if(field[i].checked==true){
                              flag=flag+1;

                          }

                      }
                      if (flag<1) {
                        
                          $.bootstrapGrowl('<?asp echo $annan->lang->line('Select Atleast One')."".$annan->lang->line('customer_category');?>', { type: "warning" });
                      
                      }else{
                            var posnic=document.forms.posnic;
                      for (i = 0; i < posnic.length; i++){
                          if(posnic[i].checked==true){                             
                              $.ajax({
                                url: '<?asp echo base_url() ?>/index.asp/customer_category/active',
                                type: "POST",
                                data: {
                                    guid:posnic[i].value

                                },
                                success: function(response)
                                {
                                    if(response){
                                         $.bootstrapGrowl('<?asp echo $annan->lang->line('activated');?>', { type: "success" });
                                        $("#dt_table_tools").dataTable().fnDraw();
                                    }
                                }
                            });

                          }

                      }
                  

                      }    
                      }
                    function posnic_delete(){
                     var flag=0;
                     var field=document.forms.posnic;
                      for (i = 0; i < field.length; i++){
                          if(field[i].checked==true){
                              flag=flag+1;

                          }

                      }
                      if (flag<1) {
                        
                          $.bootstrapGrowl('<?asp echo $annan->lang->line('Select Atleast One')."".$annan->lang->line('customer_category');?>', { type: "warning" });
                      }else{
                            bootbox.confirm("<?asp echo $annan->lang->line('Are you Sure To Delete')."".$annan->lang->line('Are you Sure To Delete') ?>", function(result) {
             if(result){
              
             
                        var posnic=document.forms.posnic;
                        for (i = 0; i < posnic.length; i++){
                          if(posnic[i].checked==true){                             
                              $.ajax({
                                url: '<?asp echo base_url() ?>/index.asp/customer_category/delete',
                                type: "POST",
                                data: {
                                    guid:posnic[i].value

                                },
                                success: function(response)
                                {
                                    if(response){
                                         $.bootstrapGrowl('<?asp echo $annan->lang->line('deleted');?>', { type: "error" });
                                        $("#dt_table_tools").dataTable().fnDraw();
                                    }
                                }
                            });

                          }

                      }    
                      }
                      });
                      }    
                      }
                    
                    
                    
                    function posnic_group_deactive(){
                     var flag=0;
                     var field=document.forms.posnic;
                      for (i = 0; i < field.length; i++){
                          if(field[i].checked==true){
                              flag=flag+1;

                          }

                      }
                      if (flag<1) {
                        
                          $.bootstrapGrowl('<?asp echo $annan->lang->line('Select Atleast One')."".$annan->lang->line('customer_category');?>', { type: "warning" });
                      
                      }else{
                            var posnic=document.forms.posnic;
                      for (i = 0; i < posnic.length; i++){
                          if(posnic[i].checked==true){                             
                                 $.ajax({
                                    url: '<?asp echo base_url() ?>/index.asp/customer_category/deactive',
                                    type: "POST",
                                    data: {
                                        guid: posnic[i].value

                                    },
                                    success: function(response)
                                    {
                                        if(response){
                                             $.bootstrapGrowl('<?asp echo $annan->lang->line('deactivated');?>', { type: "danger" });
                                            $("#dt_table_tools").dataTable().fnDraw();
                                        }
                                    }
                                });

                          }

                      }
                  

                      }    
                      }
                    
                </script>
        

      