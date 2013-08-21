<?php

/**
 * Service de Departamentos
 *
 * @package		SanSIS
 * @category	Service
 * @name		Departamento
 * @author		Pablo Santiago Sánchez <phackwer@gmail.com> 
 * @version		1.0.0
 */
class SanSIS_Service_Departamento extends SanSIS_Service
{
	public $crudClass = 'SanSIS_Model_Departamento';
	
	public function getFormData($id = null)
	{
		$data = array();
		
		$list = $this->getList();
		
		foreach ($list as $item)
			$data['departamento'][$item['id']] = $item['nome'];
		
		return $data;
	}
}