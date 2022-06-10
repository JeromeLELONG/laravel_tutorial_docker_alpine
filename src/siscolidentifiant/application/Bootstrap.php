<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
    /*
    protected function _initDatabase(){
    
    $config["adapter"] = getenv('MYSQL_ADAPTER');
    $config["params"]["dbname"] =  getenv('MYSQL_DATABASE');
    $config["params"]["host"] = getenv('MYSQL_HOST');
    $config["params"]["username"] = getenv('MYSQL_USER');
    $config["params"]["password"] = getenv('MYSQL_PASSWORD');

    $db = Zend_Db::factory($config['adapter'], $config['params']);

    //set default adapter
    Zend_Db_Table::setDefaultAdapter($db);

    //save Db in registry for later use
    Zend_Registry::set("db", $db);
    }
    */
    

}

