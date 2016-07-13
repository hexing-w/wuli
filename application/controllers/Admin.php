<?php
/**
 * @name AdminController
 * @author root
 * @desc 后台控制器		
 */
class AdminController extends Yaf_Controller_Abstract{

	public function init(){
		$this->usermodel = new UserModel();
		$this->session=Yaf_Session::getInstance();
		$action=$this->getRequest()->getActionName();
		$user=$this->session->get('user');
		if(!$user&&$action!=='login'){
			 $this->redirect(BASE_PATH."/admin/login");
		}

	}
	public function indexAction(){
		$user=$this->session->get('user');
		$this->getView()->assign("content",$user);
	}


	public function loginAction()
	{
		if($this->getRequest()->isPost()){
			$username = $this->getRequest()->getPost('username');
			$pwd = $this->getRequest()->getPost('password');
			$loginip=$_SERVER["SERVER_NAME"];
			
			$data = $this->usermodel->selectUser($username);
			if($data['passwd']==md5(md5($pwd).$data['salt'])){

			   $info=array(
			   	'loginip'=>$loginip,
			   	'status'=>1);
			   $condition="username='".$username."'";
			   $assco=$this->usermodel->updateUser($info,$condition);

			   	$this->session->set('user',$username);
			    // 	$this->getView()->assign("content",'登陆成功！！');
				$ret['code']='0';
				$ret['msg']='登陆成功';
			   	exit(json_encode($ret));
			   

			}
			else{
				// $this->getView()->assign("content",'登陆不成功！！');
				$ret['code']='1';
				$ret['msg']='登陆不成功';
				exit(json_encode($ret));
			}
		}

		return true;
		
	}

	public function captch(){
		$captch=new Verify();
	}



	public function delAction(){
		$this->session->del('admin');
		return false;
	}

	public function loginoutAction(){
		$this->session->del('admin');
		$this->redirect(BASE_PATH."/admin/login");
	}

	public  function userListAction(){
		
		$limit=1;
		$total=$this->usermodel->count();
		$page=$this->getRequest()->get('page');
		$totalPage=ceil($total/$limit);//总页数
		if($page<1||$page==null||!is_numeric($page))$page=1;
		if($page>=$totalPage)$page=$totalPage;
		$controller=$this->getRequest()->getControllerName();
		$action=$this->getRequest()->getActionName();		
		$url = BASE_PATH."/".$controller."/".$action;
		$offset=($page-1)*$limit;
		$list=$this->usermodel->userList($offset,$limit);
		$pagehtml=showPage($page,$totalPage,$url);
		$this->getView()->assign("content",$list);
		$this->getView()->assign("page",$pagehtml);
	}
	public function mainAction(){
		
	}

	public function attrListAction(){
		$limit=1;
		$this->attrmodel=new AttrModel();
		$total=$this->attrmodel->count();
		$page=$this->getRequest()->get('page');
		$totalPage=ceil($total/$limit);//总页数
		if($page<1||$page==null||!is_numeric($page))$page=1;
		if($page>=$totalPage)$page=$totalPage;
		
		
		$offset=($page-1)*$limit;
		$controller=$this->getRequest()->getControllerName();
		$action=$this->getRequest()->getActionName();		
		$url = BASE_PATH."/".$controller."/".$action;
		$list=$this->attrmodel->attrList($offset,$limit);
		$pagehtml=showPage($page,$totalPage,$url);
		$this->getView()->assign("content",$list);
		$this->getView()->assign("page",$pagehtml);
		

	}

	public function attrAddAction(){
		
				$name=$this->getRequest()->get('attr_name');
				$pid=$this->getRequest()->get('attr_pid');
				$this->attrmodel=new AttrModel();
				
				if($name){
					$data=array(
					 	'attr_name'=>$name,
					 	'attr_pid'=>$pid);
					$id=$this->attrmodel->attrInsert($data);

					if($id){
					 	$this->redirect(BASE_PATH."/admin/attrList");
					 }
				}
				$cate=$this->attrmodel->attrSelect();
				 // $limit=new limited();
				$data=Functions::unlimited($cate);
				$this->getView()->assign("attr",$data);

	}

	public function articleAddAction(){

				$data['title']=$this->getRequest()->getPost('title');
				$data['attr_id']=$this->getRequest()->getPost('attr_id');
				$data['url']=$this->getRequest()->getPost('url');
				$data['hot']=$this->getRequest()->getPost('hot');
				$data['content']=$this->getRequest()->getPost('content');
				$data['click']=50;
				$data['author']=$this->session->get('user');
				$data['images']='';
				if($data['title']){
					$path='uploads/banner/';
					$images =uploadFile($path);
					if(is_array($images)){
						$data['images']=$path.$images[0]['name'];
					}
						$this->articlemodel=new ArticleModel();
						$id=$this->articlemodel->articleAdd($data);
						if($id){
							echo "ok";
						}
				}

				$this->attrmodel=new AttrModel();
				$cate=$this->attrmodel->attrSelect();
				$data=Functions::unlimited($cate);
				$this->getView()->assign("attr",$data);
	}
	public function articleListAction(){
				$limit=1;
		$this->attrmodel=new ArticleModel();
		$total=$this->attrmodel->count();
		$page=$this->getRequest()->get('page');
		$totalPage=ceil($total/$limit);//总页数
		if($page<1||$page==null||!is_numeric($page))$page=1;
		if($page>=$totalPage)$page=$totalPage;
		
		
		$offset=($page-1)*$limit;
		$controller=$this->getRequest()->getControllerName();
		$action=$this->getRequest()->getActionName();		
		$url = BASE_PATH."/".$controller."/".$action;
		$list=$this->attrmodel->aList($offset,$limit);
		//var_dump($list);
		$pagehtml=showPage($page,$totalPage,$url);
		$this->getView()->assign("content",$list);
		$this->getView()->assign("page",$pagehtml);
	}

	public function curlAction(){
		


		      // if ($this->getRequest()->isXmlHttpRequest()) {
		$url=$this->getRequest()->getPost('url');
		//$url="http://fashion.sina.com.cn/s/ce/2016-06-30/0729/doc-ifxtmwei9518537.shtml";
		//$url="http://tech.sina.com.cn/mobile/n/n/2016-06-30/doc-ifxtsatm1074791.shtml";
		// echo $url;
		$data=Functions::curl($url);
		$ret['code']='0';
		$ret['msg']=$data;
		// //var_dump($ret);
		$json=json_encode($ret);
		// echo $json;
		//var_dump($data);
		 
  //           //如果是Ajax请求, 关闭自动渲染, 由我们手工返回Json响应
            Yaf_Dispatcher::getInstance()->autoRender(FALSE);
            exit($json);
       
	}


	
}


?>