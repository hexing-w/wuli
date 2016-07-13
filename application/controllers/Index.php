<?php
/**
 * @name IndexController
 * @author 2012-20140427rs\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract {
   
    public $actions=array(
    	"search"=>'actions/search.php',
    	);
	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/sample/index/index/index/name/2012-20140427rs\administrator 的时候, 你就会发现不同
     */
	public function indexAction() {
		$id=$this->getRequest()->get('id');
		$limit=4;
		$this->model=new ArticleModel();
		$total=$this->model->count();
		$page=$this->getRequest()->get('page');
		$totalPage=ceil($total/$limit);//总页数
		if($page<1||$page==null||!is_numeric($page))$page=1;
		if($page>=$totalPage)$page=$totalPage;
		$offset=($page-1)*$limit;
		$controller=$this->getRequest()->getControllerName();
		$action=$this->getRequest()->getActionName();		
		$url = BASE_PATH."/".$controller."/".$action;
		if($id){
			$list=$this->model->getByAttrId($id,0,$offset,$limit);
		}else{
			$list=$this->model->articleList($offset,$limit);
		}
		
		$pagehtml=showPage($page,$totalPage,$url);
		$post=$this->history();
		$popular=$this->popular();
		$attr=$this->attr();

        $this->getView()->assign('attr',$attr);
		$this->getView()->assign('popular',$popular);
        $this->getView()->assign('post',$post);
		$this->getView()->assign("content",$list);
		$this->getView()->assign("page",$pagehtml);
        return TRUE;
	}
//根据article的点击量排序
	private function popular(){
		$this->model=new ArticleModel();
		$data=$this->model->getClick();
		return $data;
	}

	private function attr(){
		$this->attrmodel=new AttrModel();
		$cate=$this->attrmodel->attrSelect();
		$attr=Functions::cate($cate);
		return $attr;
	}

	//浏览记录
	private function history($id=NULL,$list=NULL){
		   // $post=$this->getRequest()->getCookie('post');
			$post=Yaf_Session::getInstance()->get('post');
			$rows=array();
			if(is_null($id)&&is_null($list)){
				$rows=json_decode($post,true);
				if(is_null($rows)) $rows=array();
				return $rows;
			}
			if($post){
				$data=json_decode($post,true);
				
				foreach ($data as $k=> $v) {
					if($id==$v['id']){
						continue;
					}
					$rows[]=$v;
				}
				$rows[]=$list;
				while(count($rows)>4){
					array_shift($rows);
				}
				$json=json_encode($rows);

			}else{
				$rows[]=$list;
			}
				$json=json_encode($rows);
				Yaf_Session::getInstance()->set('post',$json);
			return $rows;
	}

	public  function  detailAction(){
		$id=$this->getRequest()->get('id');
		$this->model=new ArticleModel();
		$list=$this->model->getById($id);
		
		$related=$this->model->getByAttrId($list['attr_id'],$id);
        $popular=$this->popular();
        $post=$this->history($id,$list);
        //var_dump($post);
        		$attr=$this->attr();

        $this->getView()->assign('attr',$attr);
        $this->getView()->assign('post',$post);
        $this->getView()->assign('popular',$popular);
		$this->getView()->assign("related",$related);
		$this->getView()->assign("content",$list);
	}

	public function cateAction(){

	}

}
