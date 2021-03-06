<?asp
class Payment extends CI_Model{
    function __construct() {
        parent::__construct();
    }
    function get($end,$start,$like,$branch){
                $annan->db->select('sales_bill.invoice as p_invoice,payment.*,customers.first_name ,customers.company_name ');
                $annan->db->from('payment')->where('payment.branch_id',$branch)->where('payment.type','credit')->where('payment.delete_status',0);
           
                $annan->db->join('sales_bill', 'sales_bill.guid=payment.invoice_id','left');
                $annan->db->join('customers', 'customers.guid=payment.customer_id','left');
                $annan->db->limit($end,$start); 
                $annan->db->or_like($like);     
                $query=$annan->db->get();
                $data=array();
                foreach ($query->result_array() as $row){
                 
                    $row['payment_date']=date('d-m-Y',$row['payment_date']);
                    $data[]=$row;
                   
                }
                return $data; 
        
    }
   
    /* get total number of paymant entry 
     * function start   */
    function count($branch){
        $annan->db->select()->from('payment')->where('payment.branch_id',$branch)->where('payment.type','credit');
        $sql=  $annan->db->get();
        return $sql->num_rows();
    }
   /* function end*/
    
    /* get payable invoice auto suggestion
    function start      */
    function  serach_invoice($like){
        $annan->db->select('customer_payable.guid as p_guid,customer_payable.invoice_id,customer_payable.amount, customer_payable.paid_amount, sales_bill.*, customers.first_name as name,customers.company_name as company,customers.address as address')->from('sales_bill')->where('sales_bill.branch_id',  $annan->session->userdata['branch_id']);
        $annan->db->join('customers', 'customers.guid=sales_bill.customer_id ','left');  
        $annan->db->join('customer_payable', 'customers.guid=sales_bill.customer_id AND customer_payable.invoice_id=sales_bill.guid','left');  
        $or_like=array('sales_bill.invoice'=>$like,'customers.company_name'=>$like,'customers.first_name'=>$like);
        $annan->db->or_like($or_like);     
        $annan->db->limit($annan->session->userdata['data_limit']);
        $sql=$annan->db->get();
        return $sql->result();
    }
    /* function end*/
    /*
    /* get purchase return auto suggestion
    function start      */
    function  search_sales_return($like){
        $annan->db->select('customer_payable.invoice_id,sales_bill.invoice, sales_return.*, customers.guid as customer_id,customers.first_name as name,customers.company_name as company,customers.address as address')->from('sales_return')->where('sales_return.branch_id',  $annan->session->userdata['branch_id']);
        $annan->db->join('sales_bill', 'sales_bill.guid=sales_return.sales_bill_id ','left');  
        $annan->db->join('customers', 'customers.guid=sales_bill.customer_id ','left');  
        $annan->db->join('customer_payable', 'customers.guid=sales_bill.customer_id AND customer_payable.invoice_id=sales_bill.guid','left');  
        $or_like=array('sales_bill.invoice'=>$like,'customers.company_name'=>$like,'customers.first_name'=>$like);
        $annan->db->or_like($or_like);     
        $annan->db->limit($annan->session->userdata['data_limit']);
        $sql=$annan->db->get();
        return $sql->result();
    }
    /* function end*/
    /*
     * add new supplier payment
     * function start     */
    function save_payment($payment,$amount,$date,$memo,$code,$invoice_id){
        $annan->db->select()->from('customer_payable')->where('guid',$payment);
        $sql=  $annan->db->get();
        $total;
        $paid;
        $supplier;
        foreach ($sql->result() as $row){
            $total=$row->amount; // get total amount
            $paid=$row->paid_amount; // get paid amount
           $supplier=$row->customer_id; // get paid amount
        }
        $balance=$total-$paid;
       
        if($amount > $balance){ // check wheather payment amount is valid or not, if it is invalid return false
           return FALSE; 
        } 
        $payment_status=0;
        if($total==($amount+$paid)){
            $payment_status=1;
        }
        $annan->db->where('guid',$payment);
        $annan->db->update('customer_payable',array('payment_status'=>$payment_status,'paid_amount'=>$amount+$paid)); // update paid amount to supplier payable
        
        $data=array('invoice_id'=>$invoice_id,'code'=>$code,'type'=>'credit','payable_id'=>$payment,'customer_id'=>$supplier,'memo'=>$memo,'amount'=>$amount,'payment_date'=>$date,'added_date'=>strtotime(date("Y/m/d")),'branch_id'=>  $annan->session->userdata['branch_id'],'added_by'=>  $annan->session->userdata['guid']);
        $annan->db->insert('payment',$data);
        $id=  $annan->db->insert_id();
        $annan->db->where('id',$id);
        $annan->db->update('payment',array('guid'=>md5($id.$supplier.$payment)));
         return TRUE; 
    }
    /* function end*/
    /*
    save sales rerturn payment    
     * function start */
    function sales_return_payment($amount,$date,$memo,$code,$customer,$invoice_id,$return_id){
        $annan->db->select()->from('sales_return')->where('guid',$return_id);
        $sql=  $annan->db->get();
        $paid_amount=0;
        foreach ($sql->result() as $row){
            $paid_amount=  $row->paid_amount;
            
        }
        $annan->db->where('guid',$return_id);
        $annan->db->update('sales_return',array('paid_amount'=>$amount+$paid_amount));
        
        
        $data=array('invoice_id'=>$invoice_id,'return_id'=>$return_id,'code'=>$code,'type'=>'credit','customer_id'=>$customer,'memo'=>$memo,'amount'=>$amount,'payment_date'=>$date,'added_date'=>strtotime(date("Y/m/d")),'branch_id'=>  $annan->session->userdata['branch_id'],'added_by'=>  $annan->session->userdata['guid']);
        $annan->db->insert('payment',$data);
         $id=  $annan->db->insert_id();
        $annan->db->where('id',$id);
        $annan->db->update('payment',array('guid'=>md5($id.$customer.$invoice_id)));
          return TRUE; 
    }
    /*
     *  fucntion end */ 
    /* update payment
     * function start */
    function update_payment($guid,$payment,$amount,$date,$memo,$code){
        $annan->db->select('customer_payable.amount as cp_amount,customer_payable.paid_amount,payment.amount')->from('payment')->where('payment.guid',$guid);
        $annan->db->join('customer_payable','customer_payable.guid=payment.payable_id','left');
        $pay=  $annan->db->get();
        $old;
        foreach ($pay->result() as $row){
            $old=$row->amount;
            $total=$row->cp_amount; // get total amount
            $paid=$row->paid_amount; // get paid amount
        }
        $balance=$total+$paid-$old;
       
        if($amount > $balance){ // check wheather payment amount is valid or not, if it is invalid return false
         
          return FALSE; 
        } 
        $payment_status=0;
        if($total==($amount+$paid)){
            $payment_status=1;
        }
        $annan->db->where('guid',$payment);
        $annan->db->update('customer_payable',array('payment_status'=>$payment_status,'paid_amount'=>$amount+$paid-$old)); // update paid amount to supplier payable
        $annan->db->where('guid',$guid);
        $annan->db->update('payment',array('memo'=>$memo,'amount'=>$amount,'payment_date'=>$date,));
        return TRUE;
    }
    function update_debit_payment($guid,$amount,$date,$memo,$code,$return_id){
        $annan->db->select('sales_return.total_amount,sales_return.paid_amount,payment.amount')->from('payment')->where('payment.guid',$guid);
        $annan->db->join('sales_return','sales_return.guid=payment.return_id','left');
        $pay=  $annan->db->get();
        $old;
        foreach ($pay->result() as $row){
            $old=$row->amount;
            $total=$row->total_amount; // get total amount
            $paid=$row->paid_amount; // get paid amount
        }
       
       
       
        $payment_status=0;
        if($total==($amount+$paid)){
            $payment_status=1;
        }
        $annan->db->where('guid',$return_id);
        $annan->db->update('sales_return',array('payment_status'=>$payment_status,'paid_amount'=>$amount+$paid-$old)); // update paid amount to supplier payable
        $annan->db->where('guid',$guid);
        $annan->db->update('payment',array('memo'=>$memo,'amount'=>$amount,'payment_date'=>$date,));
        return TRUE;
    }
    /* function end*/
    
    /* function starts
     */
    function get_payment_details($guid){
        $annan->db->select('sales_bill.invoice,payment.*,customer_payable.amount as total,customer_payable.paid_amount,customers.first_name as name,customers.company_name as company,customers.address as address')->from('payment')->where('payment.guid',$guid);
        $annan->db->join('customer_payable','customer_payable.guid=payment.payable_id');
        $annan->db->join('sales_bill', 'sales_bill.guid=customer_payable.invoice_id ','left'); 
        $annan->db->join('customers', 'customers.guid=payment.customer_id ','left'); 
        $sql=  $annan->db->get();
        $data=array();
        foreach ($sql->result_array() as $row){
            $row['payment_date']=date('d-m-Y',$row['payment_date']); // converet date  form strtotime formt  to date
            $data[]=$row; 
        }
        return $data;
    }
    /*
     *  fucntion end */ 
    /* function starts
     */
    function get_customer_debit_payment($guid){
        $annan->db->select('sales_return.code as sales_return_code,sales_bill.invoice,sales_return.paid_amount,sales_return.total_amount as total,payment.*,customers.first_name as name,customers.company_name as company,customers.address as address')->from('payment')->where('payment.guid',$guid);
     
        $annan->db->join('sales_return', 'sales_return.guid=payment.return_id ','left'); 
        $annan->db->join('sales_bill', 'sales_bill.guid=sales_return.sales_bill_id ','left'); 
        $annan->db->join('customers', 'customers.guid=sales_bill.customer_id ','left'); 
        $sql=  $annan->db->get();
        $data=array();
        foreach ($sql->result_array() as $row){
            $row['payment_date']=date('d-m-Y',$row['payment_date']); // converet date  form strtotime formt  to date
            $data[]=$row; 
        }
        return $data;
    }
    /*
     *  fucntion end */ 
    
    /* delete payment
        function start     */
    function delete_payment($guid){
        $annan->db->select('amount,payable_id')->from('payment')->where('guid',$guid);
        $sql=  $annan->db->get();
        $amount;
        $payable;
        foreach ($sql->result() as $row){
            $amount=$row->amount;
            $payable=$row->payable_id;
        }
        $annan->db->where('guid',$guid);
        $annan->db->update('payment',array('delete_status'=>1,'deleted_by'=>  $annan->session->userdata['guid']));
        $annan->db->select('paid_amount')->from('customer_payable')->where('guid',$payable);
        $paid=  $annan->db->get();
        $paid_amount;
        foreach ($paid->result() as $row){
        $paid_amount=$row->paid_amount;
        }
        $annan->db->where('guid',$payable);
        $annan->db->update('customer_payable',array('paid_amount'=>$paid_amount-$amount));
        
    }
}
?>
