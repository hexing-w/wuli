<?php
/**
 * @name UserModel
 * @desc User数据获取类, 可以访问数据库，文件，其它系统等
 * @author 2012-20140427rs\administrator
 */
class AttrModel{
	protected $_table = "wuli_attr";
	protected $_index = "";

    public function __construct() {
    	$this->db=Yaf_Registry::get('db');
    }   

    public function attrInsert($arr){
    	$data=$this->db->insert($this->_table,$arr);
    	return $data;
    }

    public function attrSelect(){
    	$sql="select * from ".$this->_table;
    	$data=$this->db->get_all($sql);
    	return $data;
    }

    public function attrList($offset=0,$limit=2){
        $sql="select u.* ,s.attr_name pname from ".$this->_table." as u left join ".$this->_table." as s on u.attr_pid=s.attr_id order by u.attr_id  limit {$offset},{$limit} ";
        $data=$this->db->get_all($sql);
        return $data;
    }

    public function count(){
        $con="select * from ".$this->_table;
        $total=$this->db->num_rows($con);
        return $total;
     }

    public function getById($id){
        $sql="select * from ".$this->_table."  where attr_id=".$id;
        $data = $this->db->get_all($sql);
        return $data;
     }
}