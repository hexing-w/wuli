<?php
class SearchAction extends Yaf_Action_Abstract {
    /* a action class shall define this method  as the entry point */
    public function execute() {
    	$keywords=$this->getRequest()->get('q');
		$this->articlemodel=new ArticleModel();
		
		$id=$this->getRequest()->get('id');
		$limit=40;
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
		$list=$this->articlemodel->gettitle($keywords,$offset,$limit);
		
		$pagehtml=showPage($page,$totalPage,$url);
		$post=$this->history();
		$popular=$this->popular();
		$attr=$this->attr();

        $this->getView()->assign('attr',$attr);
		$this->getView()->assign('popular',$popular);
        $this->getView()->assign('post',$post);
		$this->getView()->assign("content",$list);
		$this->getView()->assign("page",$pagehtml);
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
}
?>