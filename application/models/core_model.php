<?asp

class Core_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    function item_setting($guid,$branch){
        $data=array('item_id'=>$guid,
            'branch_id'=>$branch);
        $annan->db->insert('items_setting',$data);
        $id=$annan->db->insert_id();
        $orderid=md5($id.'items_setting');
        $guid=str_replace(".", "", "$orderid");
        $value=array('guid'=>$guid);
        $annan->db->where('id',$id);
        $annan->db->update('items_setting',$value);
    }
    
    
    function delete_item_setting($guid,$bid){
        $data=array('active_status'=>0,'delete_status'=>1);
        $annan->db->where('item_id',$guid);
        $annan->db->where('branch_id',$bid);
        $annan->db->update('items_setting',$data);
        
            
        }
    function restore_item_setting($guid,$bid){
        $data=array('active_status'=>1,'delete_status'=>0);
        $annan->db->where('item_id',$guid);
        $annan->db->where('barnch_id',$bid);
        $annan->db->update('items_setting',$data);
        
        
            
        }
    function suppliers_x_items($id,$bid,$mrp,$supplier,$selling_price,$cost_price){
            $data=array('item_id'=>$id,'mrp'=>$mrp,'supplier_id'=>$supplier,'price'=>$selling_price,'cost'=>$cost_price,'branch_id'=>$bid);
            $annan->db->insert('suppliers_x_items',$data);
            $id=  $annan->db->insert_id();
            $annan->db->where('id',$id);
            $annan->db->update('suppliers_x_items',array('guid'=>md5($id.'supplier_x_items')));
    }
   function posnic_join_like($table,$bid){
       
         $annan->db->select()->from('suppliers_x_items');
         
         $annan->db->join('items', "$table".'.item_id=items.guid AND suppliers_x_items.active = 0','left');
          $annan->db->group_by("items".'.guid');
         $sql=  $annan->db->get();
          $j=0;
        $data=array();
    foreach ($sql->result() as $row){
           $data[$j] = array(
                                          'label' =>$row->code ,
                                          'desc' =>$row->description,                                          
                                          'cost' =>$row->cost,                                          
                                          'sell' =>$row->price ,                                          
                                          'mrp' =>$row->mrp,                                          
                                          'id'=>$row->guid
                                );			
                                        $j++; 
    }
    return $data;
    }
    function user_fetch_array($like,$start,$end,$sOrder){
        $annan->db->select();
         $annan->db->limit($start,$end);       
        $annan->db->order_by($sOrder);
        $annan->db->or_like($like);
        
      
                 
                $query = $annan->db->get('ajax');
                return $query->result_array();
    }
      function posnic_data_table($start,$end,$branch,$uid,$order,$like){
         
        $annan->db->select('users.*, users_x_branches.user_active  as user_active , users_x_branches.user_id as user_id')->from('users_x_branches')->where('users_x_branches.branch_id',$branch);  
        $annan->db->limit($start,$end);  
        $annan->db->order_by($order);
        $annan->db->where('users.guid <>',$uid);
        $annan->db->where('users.user_type <>',2 );
      //  $annan->db->where('users_x_branches.user_active ',0 );
        
        $annan->db->or_like($like);
        $annan->db->join('users', "users_x_branches.user_id=users.guid AND  users.user_type!=2 ".'','left');
        $query=$annan->db->get();
        return $query->result_array();
            
    }
    function items_data_table($end,$start,$order,$like,$branch){
                $annan->db->select('items.* ,items_category.guid as c_guid,items_category.category_name as c_name,brands.guid as b_guid,brands.name as b_name,items_department.department_name as d_name')->from('items')->where('items.branch_id',$branch)->where('items.delete_status',0);
                $annan->db->join('items_category', 'items.category_id=items_category.guid','left');
                $annan->db->join('brands', 'items.brand_id=brands.guid','left');
                $annan->db->join('items_department', 'items.depart_id=items_department.guid','left');
                $annan->db->limit($end,$start); 
                $annan->db->or_like($like);     
                $query=$annan->db->get();
                return $query->result_array(); 
    }
    function get_items_details_for_update($branch,$guid){
                $annan->db->select('items.* ,taxes_area.name as area_name,suppliers.first_name as s_first_name,suppliers.last_name as s_last_name,suppliers.phone as s_phone,suppliers.email as s_email,suppliers.company_name as company_name,suppliers.guid as s_guid,items_department.department_name,items_department.guid as d_guid,taxes.guid as tax_guid,tax_types.type as type,taxes.value as value, items_category.guid as c_guid,items_category.category_name as c_name,brands.guid as b_guid,brands.name as b_name')->from('items')->where('items.branch_id',$branch)->where('items.guid',$guid);
                $annan->db->join('items_category', 'items.category_id=items_category.guid','left');
                $annan->db->join('brands', 'items.brand_id=brands.guid','left');
                $annan->db->join('suppliers', 'items.supplier_id=suppliers.guid','left');
                $annan->db->join('items_department', 'items.depart_id=items_department.guid','left');
                $annan->db->join('taxes_area', 'items.tax_area_id=taxes_area.guid','left');
                $annan->db->join('taxes', 'items.tax_id=taxes.guid','left');
                $annan->db->join('tax_types', 'taxes.type=tax_types.guid','left');
                
                $query=$annan->db->get();
                $data=array();
                foreach ($query->result_array() as $row){
                    $row['start_date']=date('d-m-Y',$row['start_date']);
                    $row['end_date']=date('d-m-Y',$row['end_date']);
                    $data[]=$row;
                } 
                return $data;
    }
    function get_items_setting_details($branch,$guid){
                $annan->db->select()->from('items_setting')->where('branch_id',$branch)->where('item_id',$guid);               
                $query=$annan->db->get();
                return $query->result_array();  
    }
    function get_taxes($branch,$like){
         $annan->db->select('taxes.* ,taxes.guid as guid,tax_types.type as name,tax_types.guid as t_guid')->from('taxes')->where('taxes.branch_id',$branch)->where('taxes.active_status',1)->where('taxes.delete_status',0);
                $annan->db->join('tax_types', 'taxes.type=tax_types.guid','left');
                $annan->db->like($like);
                $query=$annan->db->get();
                return $query->result_array();  
    }
                                    
}
?>
