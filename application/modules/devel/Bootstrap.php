<?php

class Devel_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
				'basePath'	  	=> dirname(__FILE__),
				'namespace'	 	=> 'Devel_')
		);
		
		$autoloader->addResourceType('form', 		'forms/', 		'Form');
		$autoloader->addResourceType('service', 	'services/', 	'Service');
		
		return $autoloader;
	}
}