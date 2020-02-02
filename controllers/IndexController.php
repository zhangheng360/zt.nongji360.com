<?php
class IndexController extends \ControllerBase
{
	/**
	 * @purpose 专题列表
	 * @param int $id 分类id
	 * @author hangrlt
	 * @created 2015-4-20 14:02:16
	 */
	public function indexAction($id=null)
	{
		$nongJiVideos = \Edition\Section::fetch(1003003, 4);
		$nongJiTus = \Edition\Section::fetch(1003004, 4);
		$offers = \Edition\Section::fetch(1004002, 4);
		$nongJiProduct = \Edition\Section::fetch(1003005, 4);

		if(is_null($id))
		{
			$builder = $this->modelsManager->createBuilder()->from('Zhuanti\Collect')->orderBy('id DESC')
				->columns('id,thumb,title,url,description')
				->where('status='.\Zhuanti\Collect::STATUS_ENABLE." AND url<>''");
		}
		else
		{
			if(!RegEx::number($id) )
			{
				die('{"message:"参数错误"}');
			}
			$builder = $this->modelsManager->createBuilder()->from('Zhuanti\Collect')->orderBy('id DESC')
				->columns('id,thumb,title,url,description')
				->where('category_id='.$id.' AND status='.\Zhuanti\Collect::STATUS_ENABLE." AND url<>''");
		}
		$paginator = new \Page(10);
		$collects = $paginator->queryBuilder($builder);
		if(!in_array($id,array_keys(\Zhuanti\Collect::$categories)) )
		{
			$id = null;
		}
		/**
		 * SEO描述
		 */
		$channel = \Channel::findFirst('id='.\Data::WEB_ZT);

		$this->setVars(array(
			'title'        => $channel->title,
			'keywords'     => $channel->keyword,
			'description'  => $channel->description,
			'nongJiVideos' => $nongJiVideos,
			'nongJiTus'    => $nongJiTus,
			'nongJiProduct'    => $nongJiProduct,
			'offers'       => $offers,
			'categories'   => \Zhuanti\Collect::$categories,
			'collects'     => $collects,
			'category_id'  => $id,
			'pageHtml'     => $paginator->jump()
		));
	}
}