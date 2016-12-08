<?php
/*用户活动表*/

class RechargeModel extends CI_Model {
    public function __construct(){
        parent::__construct();
    }   
    public function getInfo($table, $where='1=1', $orderby="id", $field="*", $sore='desc', $result_type='result_array'){
        if(!$table) 
			return false;
        $sql    = "select $field from $table where $where order by $orderby $sore";
        $query  =  $this->db->query($sql); 
        switch($result_type){
            case 'result_array':
            return $query->result_array();
            case 'row_array':
            return $query->row_array();
            case 'num_rows':
            return $query->num_rows();
            default:
            return $query->result_array();
        }
       
    }
 function sqlStr($data,$table,$type='insert',$where=' where 1=1'){
	$sqlStr = '' ;
	if($type == 'insert'){
		if( count($data)>0 ){
			foreach($data as $dd=>$tt){
				$set     .= '`'.$dd.'`,';
				$values  .= "'".$tt."',";
			}
			$sqlStr = 'insert into '.$table.'('.(substr($set,0,-1)).')values('.(substr($values,0,-1)).')';
		}
	}else{
		if( count($data)>0 ){
			foreach($data as $dd=>$tt){
				$set[]  = $dd.'='."'".$tt."'";
			}
			$sqlStr = 'update '.$table.' set '.implode(',',$set).$where;
		}
	}
	return $sqlStr?$this->db->query($sqlStr):'';
}
  




}
