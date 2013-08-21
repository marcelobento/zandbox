<?php

/**
 * Service de UF
 *
 * @package		SanSIS
 * @category	Service
 * @name		Uf
 * @author		Pablo Santiago Sánchez <phackwer@gmail.com> 
 * @version		1.0.0
 */
class SanSIS_Service_Uf extends SanSIS_Service
{
	public $crudClass = 'SanSIS_Model_Uf';
	
	public function getFormData($values = null)
	{
		$src = new SanSIS_Service_Pais();
		$paises = $src->getList();
		
		$data = array();
		$data['pais'] = array();
		
		foreach($paises as $pais)
		{
			$data['pais'][$pais['id']] = $pais['nome'];
		}
		
		return $data;
	}
	
	public function getlistufspais($values)
	{
		return $this->crudObj->getList($values);
	}
}