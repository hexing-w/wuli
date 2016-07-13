<?php
/**
 * @name UserModel
 * @desc User数据获取类, 可以访问数据库，文件，其它系统等
 * @author 2012-20140427rs\administrator
 */
class ArticleModel{
	protected $_table = "wuli_article";
	protected $_index = "";

    public function __construct() {
    	$this->db=Yaf_Registry::get('db');
    }   

    public function articleAdd($arr){
    	$data=$this->db->insert($this->_table,$arr);
    	return $data;
    }

    public function attrSelect(){
    	$sql="select * from ".$this->_table;
    	$data=$this->db->get_all($sql);
    	return $data;
    }

   public function articleList($offset=0,$limit=2) {

        $sql = "select * from ".$this->_table." order by create_time desc limit {$offset},{$limit}";
        
        //var_dump($this->db);
        $data = $this->db->get_all($sql);
        return $data;
     }
    
    
    public function aList($offset=0,$limit=2) {

        $sql = "select wuli_article.*,wuli_attr.attr_name from ".$this->_table." left join wuli_attr on wuli_article.attr_id=wuli_attr.attr_id order by create_time desc limit {$offset},{$limit}";
        
        //var_dump($this->db);
        $data = $this->db->get_all($sql);
        return $data;
     }

     public function getById($id){
        $sql="select * from ".$this->_table."  where id=".$id;
        $data = $this->db->get_one($sql);
        return $data;
     }

    public function getByAttrId($attr_id,$id,$offset=0,$limit=3){
        $sql="select * from ".$this->_table."  where attr_id=".$attr_id." and id !={$id} order by create_time desc limit {$offset},{$limit}";
        // echo $sql;
        $data = $this->db->get_all($sql);
        return $data;
     }

    public function count(){
        $con="select * from ".$this->_table;
        $total=$this->db->num_rows($con);
        return $total;
     }

     public function getClick(){
        $sql="select * from ".$this->_table." order by click limit 0,4";
        $data = $this->db->get_all($sql);
        return $data;
     }

     public function gettitle($key,$offset=0,$limit=2){
        $sql="select * from ".$this->_table."  where title like '%".$key."%' order by click  limit {$offset},{$limit}";
        //echo $sql;
        $data = $this->db->get_all($sql);
        return $data;
     }
}