<?php
/**
 * @name UserModel
 * @desc User数据获取类, 可以访问数据库，文件，其它系统等
 * @author 2012-20140427rs\administrator
 */
class UserModel {
	protected $_table = "wuli_manager";
	protected $_index = "";

    public function __construct() {
    	$this->db=Yaf_Registry::get('db');
    }   
    
    public function selectUser($username) {
    	$sql = "select passwd,salt,loginip,status from ".$this->_table." where username='".$username."'";
    	//var_dump($this->db);
    	$data = $this->db->get_one($sql);
    	return $data;
     }

    public function userList($offset=0,$limit=2) {

        $sql = "select mid,username,loginip,status from ".$this->_table." order by mid limit {$offset},{$limit}";
        
        //var_dump($this->db);
        $data = $this->db->get_all($sql);
        return $data;
     }

     public function count(){
        $con="select * from ".$this->_table;
        $total=$this->db->num_rows($con);
        return $total;
     }

    public function updateUser($arrInfo,$condition) {
       $this->db->update($this->_table,$arrInfo,$condition);
    }
    public function insertUser($arrInfo) {
        return true;
    }
}
