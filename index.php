<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8', false);
date_default_timezone_set('Asia/Shanghai');
mb_internal_encoding('utf-8');

try
{
	define('DR', __DIR__);
	$confDb = json_decode(file_get_contents(DR.'/../_config/database.json'));
	$confDir = json_decode(file_get_contents(DR.'/../_config/directory.json'));
	$confSess = json_decode(file_get_contents(DR.'/../_config/session.json'), true);

	//Register an autoloader
	$loader = new \Phalcon\Loader();
	$loader->registerDirs(array(
		DR.$confDir->default->controllers,
		DR.$confDir->public->models,
		DR.$confDir->public->plugins,
		DR.$confDir->public->library,
		DR.$confDir->public->config,
		DR.$confDir->public->controller,
		DR.$confDir->public->jar
	))->register();

	//Create a DI
	$di = new \Phalcon\DI\FactoryDefault();

	$di->set('router', function () {
		$router = new \Phalcon\Mvc\Router();

		$router->add('(/?[\d]*)?', array(
			'controller'=>'index',
			'action'=>'index',
			'params'=>1
		));

		return $router;
	});

	$di->set('url', function() {
		$url = new \Phalcon\Mvc\Url();
		$url->setBaseUri('/');
		return $url;
	});

	//Set the database service
	$di->setShared('db', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->article->host,
			'username'=>$confDb->article->username,
			'password'=>$confDb->article->password,
			'dbname'=>$confDb->article->dbname,
			'charset'=>$confDb->article->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->article->charset)
		));

		return $connection;
	});

	$di->setShared('collectionDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->collection->host,
			'username'=>$confDb->collection->username,
			'password'=>$confDb->collection->password,
			'dbname'=>$confDb->collection->dbname,
			'charset'=>$confDb->collection->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->collection->charset)
		));

		return $connection;
	});

	$di->setShared('editionDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->edition->host,
			'username'=>$confDb->edition->username,
			'password'=>$confDb->edition->password,
			'dbname'=>$confDb->edition->dbname,
			'charset'=>$confDb->edition->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->edition->charset)
		));

		return $connection;
	});

	$di->set('wisdomDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->wisdom->host,
			'username'=>$confDb->wisdom->username,
			'password'=>$confDb->wisdom->password,
			'dbname'=>$confDb->wisdom->dbname,
			'charset'=>$confDb->wisdom->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->wisdom->charset)
		));

		return $connection;
	});

    $di->setShared('videoDB', function() use ($confDb) {
        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            'host'=>$confDb->video->host,
            'username'=>$confDb->video->username,
            'password'=>$confDb->video->password,
            'dbname'=>$confDb->video->dbname,
            'charset'=>$confDb->video->charset,
            'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->video->charset)
        ));

        return $connection;
    });

	$di->setShared('resourceDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->article->host,
			'username'=>$confDb->article->username,
			'password'=>$confDb->article->password,
			'dbname'=>$confDb->article->dbname_resource,
			'charset'=>$confDb->article->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->article->charset)
		));

		return $connection;
	});

	$di->setShared('resourceViewDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->article->host,
			'username'=>$confDb->article->username,
			'password'=>$confDb->article->password,
			'dbname'=>$confDb->article->dbname_resource_view,
			'charset'=>$confDb->article->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->article->charset)
		));

		return $connection;
	});

	$di->setShared('commentDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->comment->host,
			'username'=>$confDb->comment->username,
			'password'=>$confDb->comment->password,
			'dbname'=>$confDb->comment->dbname,
			'charset'=>$confDb->comment->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->comment->charset)
		));

		return $connection;
	});

	$di->setShared('userDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->users->host,
			'username'=>$confDb->users->username,
			'password'=>$confDb->users->password,
			'dbname'=>$confDb->users->dbname,
			'charset'=>$confDb->users->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->users->charset)
		));

		return $connection;
	});

	$di->setShared('advDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->adv->host,
			'username'=>$confDb->adv->username,
			'password'=>$confDb->adv->password,
			'dbname'=>$confDb->adv->dbname,
			'charset'=>$confDb->adv->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->adv->charset)
		));

		return $connection;
	});

	$di->setShared('bangDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->bang->host,
			'username'=>$confDb->bang->username,
			'password'=>$confDb->bang->password,
			'dbname'=>$confDb->bang->dbname,
			'charset'=>$confDb->bang->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->bang->charset)
		));

		return $connection;
	});

	$di->setShared('ztDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->zt->host,
			'username'=>$confDb->zt->username,
			'password'=>$confDb->zt->password,
			'dbname'=>$confDb->zt->dbname,
			'charset'=>$confDb->zt->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->zt->charset)
		));

		return $connection;
	});
	
	$di->set('zhuantiExpoDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->zhuanti_expo->host,
			'username'=>$confDb->zhuanti_expo->username,
			'password'=>$confDb->zhuanti_expo->password,
			'dbname'=>$confDb->zhuanti_expo->dbname,
			'charset'=>$confDb->zhuanti_expo->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->zhuanti_expo->charset)
		));
		
		return $connection;
	});

	$di->setShared('imageDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->image->host,
			'username'=>$confDb->image->username,
			'password'=>$confDb->image->password,
			'dbname'=>$confDb->image->dbname,
			'charset'=>$confDb->image->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->image->charset)
		));
		return $connection;
	});

	$di->setShared('walletDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->wallet->host,
			'username'=>$confDb->wallet->username,
			'password'=>$confDb->wallet->password,
			'dbname'=>$confDb->wallet->dbname,
			'charset'=>$confDb->wallet->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->wallet->charset)
		));

		return $connection;
	});

	$di->setShared('walletProfitDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->wallet->host,
			'username'=>$confDb->wallet->username,
			'password'=>$confDb->wallet->password,
			'dbname'=>$confDb->wallet->dbname_profit,
			'charset'=>$confDb->wallet->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->wallet->charset)
		));

		return $connection;
	});

	$di->set('duduOldDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->dudu->host,
			'username'=>$confDb->dudu->username,
			'password'=>$confDb->dudu->password,
			'dbname'=>$confDb->dudu->dbname,
			'charset'=>$confDb->dudu->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->dudu->charset)
		));

		return $connection;
	});

	$jgbConnections = array(
		array(
			'host'=>$confDb->jgb->host,
			'username'=>$confDb->jgb->username,
			'password'=>$confDb->jgb->password,
			'dbname'=>$confDb->jgb->dbname,
			'charset'=>$confDb->jgb->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->jgb->charset)
		),
		array(
			'host'=>$confDb->jgb2->host,
			'username'=>$confDb->jgb2->username,
			'password'=>$confDb->jgb2->password,
			'dbname'=>$confDb->jgb2->dbname,
			'charset'=>$confDb->jgb2->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->jgb2->charset)
		),
		array(
			'host'=>$confDb->jgb3->host,
			'username'=>$confDb->jgb3->username,
			'password'=>$confDb->jgb3->password,
			'dbname'=>$confDb->jgb3->dbname,
			'charset'=>$confDb->jgb3->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->jgb3->charset)
		),
		array(
			'host'=>$confDb->jgb4->host,
			'username'=>$confDb->jgb4->username,
			'password'=>$confDb->jgb4->password,
			'dbname'=>$confDb->jgb4->dbname,
			'charset'=>$confDb->jgb4->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->jgb4->charset)
		)
	);
	$jgbReadConnection = \ModelBase::dispatch($jgbConnections, 'wrr', array(100, 0, 0, 0));
	//Set the database service
	$di->setShared('jgbDB', function() use ($jgbConnections) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($jgbConnections[0]);

		return $connection;
	});
	$di->setShared('jgbReadDB', function() use ($jgbReadConnection) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($jgbReadConnection);

		return $connection;
	});

	$di->setShared('mallDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->zyd->host,
			'username'=>$confDb->zyd->username,
			'password'=>$confDb->zyd->password,
			'dbname'=>$confDb->zyd->dbname,
			'charset'=>$confDb->zyd->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->zyd->charset)
		));
		return $connection;
	});

	$productConnections = array(
		array(
			'host'=>$confDb->product->host,
			'username'=>$confDb->product->username,
			'password'=>$confDb->product->password,
			'dbname'=>$confDb->product->dbname,
			'charset'=>$confDb->product->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product->charset)
		),
		array(
			'host'=>$confDb->product2->host,
			'username'=>$confDb->product2->username,
			'password'=>$confDb->product2->password,
			'dbname'=>$confDb->product2->dbname,
			'charset'=>$confDb->product2->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product2->charset)
		),
		array(
			'host'=>$confDb->product3->host,
			'username'=>$confDb->product3->username,
			'password'=>$confDb->product3->password,
			'dbname'=>$confDb->product3->dbname,
			'charset'=>$confDb->product3->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product3->charset)
		),
		array(
			'host'=>$confDb->product4->host,
			'username'=>$confDb->product4->username,
			'password'=>$confDb->product4->password,
			'dbname'=>$confDb->product4->dbname,
			'charset'=>$confDb->product4->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product4->charset)
		)
	);
	$di->setShared('productDB', function() use ($productConnections) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productConnections[0]);
		return $connection;
	});
	$di->setShared('productReadDB', function() use ($productConnections) {
		$productReadConnection = \ModelBase::dispatch($productConnections, 'wrr', array(5, 15, 40, 40));
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productReadConnection);
		return $connection;
	});

	$di->setShared('productViewDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->productView->host,
			'username'=>$confDb->productView->username,
			'password'=>$confDb->productView->password,
			'dbname'=>$confDb->productView->dbname,
			'charset'=>$confDb->productView->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productView->charset)
		));
		return $connection;
	});

	$productTableConnections = array(
		array(
			'host'=>$confDb->product->host,
			'username'=>$confDb->product->username,
			'password'=>$confDb->product->password,
			'dbname'=>$confDb->product->dbname_table,
			'charset'=>$confDb->product->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product->charset)
		),
		array(
			'host'=>$confDb->product2->host,
			'username'=>$confDb->product2->username,
			'password'=>$confDb->product2->password,
			'dbname'=>$confDb->product2->dbname_table,
			'charset'=>$confDb->product2->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product2->charset)
		),
		array(
			'host'=>$confDb->product3->host,
			'username'=>$confDb->product3->username,
			'password'=>$confDb->product3->password,
			'dbname'=>$confDb->product3->dbname_table,
			'charset'=>$confDb->product3->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product3->charset)
		),
		array(
			'host'=>$confDb->product4->host,
			'username'=>$confDb->product4->username,
			'password'=>$confDb->product4->password,
			'dbname'=>$confDb->product4->dbname_table,
			'charset'=>$confDb->product4->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->product4->charset)
		)
	);
	$di->setShared('productTableDB', function() use ($productTableConnections) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productTableConnections[0]);
		return $connection;
	});
	$di->setShared('productTableReadDB', function() use ($productTableConnections) {
		$productReadConnection = \ModelBase::dispatch($productTableConnections, 'wrr', array(5, 15, 40, 40));
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productReadConnection);
		return $connection;
	});

	$productStoreConnections = array(
		array(
			'host'=>$confDb->productStore->host,
			'username'=>$confDb->productStore->username,
			'password'=>$confDb->productStore->password,
			'dbname'=>$confDb->productStore->dbname,
			'charset'=>$confDb->productStore->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore->charset)
		),
		array(
			'host'=>$confDb->productStore2->host,
			'username'=>$confDb->productStore2->username,
			'password'=>$confDb->productStore2->password,
			'dbname'=>$confDb->productStore2->dbname,
			'charset'=>$confDb->productStore2->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore2->charset)
		),
		array(
			'host'=>$confDb->productStore3->host,
			'username'=>$confDb->productStore3->username,
			'password'=>$confDb->productStore3->password,
			'dbname'=>$confDb->productStore3->dbname,
			'charset'=>$confDb->productStore3->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore3->charset)
		),
		array(
			'host'=>$confDb->productStore4->host,
			'username'=>$confDb->productStore4->username,
			'password'=>$confDb->productStore4->password,
			'dbname'=>$confDb->productStore4->dbname,
			'charset'=>$confDb->productStore4->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore4->charset)
		)
	);
	$di->setShared('productStoreDB', function() use ($productStoreConnections) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productStoreConnections[0]);
		return $connection;
	});
	$di->setShared('productStoreReadDB', function() use ($productStoreConnections) {
		$productStoreReadConnection = \ModelBase::dispatch($productStoreConnections, 'wrr', array(5, 15, 40, 40));
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productStoreReadConnection);
		return $connection;
	});

	$productSubsidyConnections = $productConnections;
	$productSubsidyConnections[0]['dbname'] =
	$productSubsidyConnections[1]['dbname'] =
	$productSubsidyConnections[2]['dbname'] =
	$productSubsidyConnections[3]['dbname'] = $confDb->product4->dbname_subsidy;
	$di->setShared('productSubsidyDB', function() use ($productSubsidyConnections) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productSubsidyConnections[0]);

		return $connection;
	});
	$di->setShared('productSubsidyReadDB', function() use ($productSubsidyConnections) {
		$productReadConnection = \ModelBase::dispatch($productSubsidyConnections, 'wrr', array(5, 15, 40, 40));
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productReadConnection);

		return $connection;
	});

	$jieConnections = array(
		array(
			'host'=>$confDb->jie->host,
			'username'=>$confDb->jie->username,
			'password'=>$confDb->jie->password,
			'dbname'=>$confDb->jie->dbname,
			'charset'=>$confDb->jie->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->jie->charset)
		)
	);
	$jieConnections[1] = $jieConnections[2] = $jieConnections[3] = $jieConnections[0];
	$jieConnections[1]['host'] = $confDb->jie->host2;
	$jieConnections[2]['host'] = $confDb->jie->host3;
	$jieConnections[3]['host'] = $confDb->jie->host4;
	$di->setShared('jieDB', function() use ($jieConnections) {
		return  new \Phalcon\Db\Adapter\Pdo\Mysql($jieConnections[0]);
	});
	$di->setShared('jieReadDB', function() use ($jieConnections) {
		$hlwReadConnection = \ModelBase::dispatch($jieConnections, 'wrr', array(5, 15, 80, 0));
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($hlwReadConnection);
		return $connection;
	});


	$productStoreTableConnections = array(
		array(
			'host'=>$confDb->productStore->host,
			'username'=>$confDb->productStore->username,
			'password'=>$confDb->productStore->password,
			'dbname'=>$confDb->productStore->dbname_table,
			'charset'=>$confDb->productStore->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore->charset)
		),
		array(
			'host'=>$confDb->productStore2->host,
			'username'=>$confDb->productStore2->username,
			'password'=>$confDb->productStore2->password,
			'dbname'=>$confDb->productStore2->dbname_table,
			'charset'=>$confDb->productStore2->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore2->charset)
		),
		array(
			'host'=>$confDb->productStore3->host,
			'username'=>$confDb->productStore3->username,
			'password'=>$confDb->productStore3->password,
			'dbname'=>$confDb->productStore3->dbname_table,
			'charset'=>$confDb->productStore3->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore3->charset)
		),
		array(
			'host'=>$confDb->productStore4->host,
			'username'=>$confDb->productStore4->username,
			'password'=>$confDb->productStore4->password,
			'dbname'=>$confDb->productStore4->dbname_table,
			'charset'=>$confDb->productStore4->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->productStore4->charset)
		)
	);
	$di->setShared('productStoreTableDB', function() use ($productStoreTableConnections) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productStoreTableConnections[0]);
		return $connection;
	});
	$di->setShared('productStoreTableReadDB', function() use ($productStoreTableConnections) {
		$productStoreReadConnection = \ModelBase::dispatch($productStoreTableConnections, 'wrr', array(5, 15, 40, 40));
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($productStoreReadConnection);
		return $connection;
	});

	$di->setShared('subsidyDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->subsidy->host,
			'username'=>$confDb->subsidy->username,
			'password'=>$confDb->subsidy->password,
			'dbname'=>$confDb->subsidy->dbname,
			'charset'=>$confDb->subsidy->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->subsidy->charset)
		));

		return $connection;
	});


	$shequConnections = array(
		array(
			'host'=>$confDb->shequ->host,
			'username'=>$confDb->shequ->username,
			'password'=>$confDb->shequ->password,
			'dbname'=>$confDb->shequ->dbname,
			'charset'=>$confDb->shequ->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->shequ->charset)
		),
		array(
			'host'=>$confDb->shequ2->host,
			'username'=>$confDb->shequ2->username,
			'password'=>$confDb->shequ2->password,
			'dbname'=>$confDb->shequ2->dbname,
			'charset'=>$confDb->shequ2->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->shequ2->charset)
		),
		array(
			'host'=>$confDb->shequ3->host,
			'username'=>$confDb->shequ3->username,
			'password'=>$confDb->shequ3->password,
			'dbname'=>$confDb->shequ3->dbname,
			'charset'=>$confDb->shequ3->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->shequ3->charset)
		),
		array(
			'host'=>$confDb->shequ4->host,
			'username'=>$confDb->shequ4->username,
			'password'=>$confDb->shequ4->password,
			'dbname'=>$confDb->shequ4->dbname,
			'charset'=>$confDb->shequ4->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->shequ4->charset)
		)
	);
	$di->setShared('shequDB', function() use ($shequConnections) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($shequConnections[0]);
		return $connection;
	});
	$di->setShared('shequReadDB', function() use ($shequConnections) {
		$shequReadConnection = \ModelBase::dispatch($shequConnections,
			'wrr', array(5, 15, 40, 40));
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($shequReadConnection);
		return $connection;
	});

	$di->setShared('zbsDB', function() use ($confDb) {
		return  new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->zbs->host,
			'username'=>$confDb->zbs->username,
			'password'=>$confDb->zbs->password,
			'dbname'=>$confDb->zbs->dbname,
			'charset'=>$confDb->zbs->charset,
			'options'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->zbs->charset)
		));
	});

	//Set the session database service
	$di->set('sessionDB', function() use ($confDb) {
		$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			'host'=>$confDb->session->host,
			'username'=>$confDb->session->username,
			'password'=>$confDb->session->password,
			'dbname'=>$confDb->session->dbname,
			'charset'=>$confDb->session->charset,
			'options'=>array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.$confDb->session->charset)
		));

		return $connection;
	});

	$di->set('session', function() use ($di, $confSess) {
		if ( 'DB'==$confSess['adapter'] )
		{
			$session = new \SessionDb();
			$session->setOptions($confSess);
		}
		else
		{
			if ( '1'==$confSess['domain'] )
			{
				ini_set('session.cookie_domain', \Http::domain());
			}
			$session = new \Phalcon\Session\Adapter\Files();
		}
		$session->start();
		return $session;
	});

	//Register Volt as a service
	$di->set('voltService', function($view, $di) {
		$volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);
		$volt->setOptions(array(
			'compiledPath'=>'compiled/',	//编译volt模版生成文件的目录
			'compiledSeparator'=>'_',
			'compiledExtension'=>'.php',	//编译volt模版后的扩展名
			'compileAlways'=>Conf::COMPILE	//该选项始终检查父模版是否有修改
		));

		new \VoltFilter($volt);
		new \VoltFunction($volt);

		return $volt;
	});

	//Setting up the view component
	$di->set('view', function() use ($confDir){
		$view = new \Phalcon\Mvc\View();
		$view->setViewsDir(DR.$confDir->default->views);

		$view->registerEngines(array(
			'.html'=>'voltService'
		));

		return $view;
	});

	//Handle the request
	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	echo $application->handle()->getContent();

}
catch ( Phalcon\Exception $e )
{
	echo 'PhalconException:', $e->getMessage(), ' in ', $e->getFile(), ' on line ', $e->getLine();
}
catch ( PDOException $e )
{
	echo 'PDOException:', $e->getMessage(), ' in ', $e->getFile(), ' on line ', $e->getLine();
}
catch ( Exception $e )
{
	echo 'Exception:', $e->getMessage(), ' in ', $e->getFile(), ' on line ', $e->getLine();
}