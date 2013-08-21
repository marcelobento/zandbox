<?php

/**
 * Formulário para Edição/Criação
 *
 * @package		Admin
 * @subpackage	Usuario
 * @category	Form
 * @name		Edit
 * @author		Pablo Santiago Sánchez <phackwer@gmail.com> 
 * @version		1.0.0
 */
class Admin_Form_Usuario_Edit extends SanSIS_Form
{
	/**
     * Inicializa a criação do formulário
     */
    public function init()
    {
		$id = $this->createElement('Hidden', 'id', array('value' => ''));

		$login = $this->createElement('Text', 'login', array('label' => 'Login:', 'required' => true, 'maxLength' => 30, 'style' => 'width:300px'));
		$login->addValidator('StringLength', false, array(3, 30));

    	$senha = $this->createElement('Password', 'senha', array('label' => 'Senha:', 'maxLength' => 30, 'style' => 'width:300px'));

    	$nome = $this->createElement('Text', 'nome', array('label' => 'Nome:', 'required' => true, 'maxLength' => 100, 'style' => 'width:300px'));
		$nome->addValidator('StringLength', false, array(1, 100));
		
		$sobrenome = $this->createElement('Text', 'sobrenome', array('label' => 'Sobrenome:', 'required' => true, 'maxLength' => 100, 'style' => 'width:300px'));
		$sobrenome->addValidator('StringLength', false, array(1, 100));
		
		$cargo = $this->createElement('Text', 'cargo', array('label' => 'Cargo:', 'maxLength' => 100, 'style' => 'width:300px'));
		$cargo->addValidator('StringLength', false, array(1, 100));

		$email = $this->createElement('Text', 'email', array('label' => 'Email:', 'required' => true, 'maxLength' => 100, 'style' => 'width:300px'));
		$email->addValidator('EmailAddress', false, array('domain' => false));
		
		$telefone = $this->createElement('Text', 'telefone', array('label' => 'Telefone:', 'required' => true, 'maxLength' => 30, 'style' => 'width:300px', 'class' => 'phone'));
		$telefone->addValidator('StringLength', false, array(14, 14));
		
		$id_departamento = $this->createElement('Select', 'id_departamento', array('label' => 'Departamento:', 'style' => 'width:300px'));
		
		$id_perfil =  $this->createElement('Multiselect', 'id_perfil', array('label' => 'Perfis:', 'required' => true, 'style' => 'height:100px; width:300px'));
		
		$status_tupla = $this->createElement('Select', 'status_tupla', array('label' => 'Status:', 'required' => true, 'style' => 'width:300px'));

        $this->addElements(
        	array(
        		$id,
	        	$login,
	        	$senha,
	        	$nome,
	        	$sobrenome,
	        	$cargo,
	        	$email,
	        	$telefone,
	        	$id_departamento,
        		$id_perfil,
	        	$status_tupla
           )
       );
        
        $this->addDisplayGroup(
            array(
	        	'nome',
	        	'sobrenome',
	        	'cargo',
	        	'email',
	        	'telefone',
	        	'id_departamento',
           ),
            'formulario',
            array(
                'legend' => 'Usuário'
           ));        
        
        $this->addDisplayGroup(
	        array(
		        'login',
	        	'senha',
		        'id_perfil',
		        'status_tupla'
			),
	        'acesso',
	                    array(
	        'legend' => 'Dados de Acesso'
        ));
        
        $sub = new SanSIS_Form_SubForm_Endereco();
        $sub->setLegend('Endereço');        
		$this->addSubForm($sub, 'localizacao');
		
		$adicionar = $this->createElement('submit', 'adicionar', array('class' => 'button'));
		$cancelar = $this->createElement('submit', 'cancelar', array('class' => 'button'));
		 
		$this->addElements(
			array(
				$adicionar,
				$cancelar
			)
		);
		
		$this->addDisplayGroup(
			array(
				'adicionar',
				'cancelar'
			),
			'ActionBar'
		);

        parent::init();
    }

    public function populateFromModel($data)
    {
    	if(isset($data['perfil']) && count($data['perfil']))
    	{
	    	foreach ($data['perfil'] as $key=>$value)
	    		$this->getElement('id_perfil')->addMultiOption($key, $value);
    	}
    	else
    	{
    		$this->getElement('id_perfil')->addMultiOptions(array('' => 'Nenhum Perfil cadastrado'));
    	}
    	
    	if(isset($data['departamento']) && count($data['departamento']))
    	{
    		$this->getElement('id_departamento')->addMultiOptions(array('' => 'Selecione abaixo'));
    		$this->getElement('id_departamento')->addMultiOptions($data['departamento']);
    	}
    	else
    	{
    		$this->getElement('id_departamento')->addMultiOptions(array('' => 'Nenhum Departamento cadastrado'));
    	}
    	
    	$this->getElement('status_tupla')->addMultiOptions(array('1' => 'Ativo', '0' => 'Inativo'));
    	
    	$sub = $this->getSubForm('localizacao');
    	
    	if(isset($data['pais']) && count($data['pais']))
		{
			$sub->getElement('id_pais')->addMultiOptions(array('' => 'Selecione abaixo'));
			$sub->getElement('id_pais')->addMultiOptions($data['pais']);
		}
		else
		{
			$sub->getElement('id_pais')->addMultiOptions(array('' => 'Nenhum País cadastrado'));
		}
    	
    	if(isset($data['uf']) && count($data['uf']))
		{
			$sub->getElement('id_uf')->addMultiOptions(array('' => 'Selecione abaixo'));
			$sub->getElement('id_uf')->addMultiOptions($data['uf']);
		}
		else
		{
			$sub->getElement('id_uf')->addMultiOptions(array('' => 'Selecione o País antes'));
		}
			
		if(isset($data['cidade']) && count($data['cidade']))
		{
			$sub->getElement('id_cidade')->addMultiOptions(array('' => 'Selecione abaixo'));
			$sub->getElement('id_cidade')->addMultiOptions($data['cidade']);
		}
		else
		{
			$sub->getElement('id_cidade')->addMultiOptions(array('' => 'Selecione a UF antes'));
		}
    }
    
    public function isValid($data)
    {
 	   $valid = true;
    
    	$valid = parent::isValid ($data);
    
    	if (!$data['id'] && !$data['senha'])
    	{
    	$this->markAsError();
    		$this->senha->addError('É obrigatório informar a senha para novos usuários');
    			$this->senha->setAttrib('class', 'campoErro');
    		}
    		
    
    		return $valid;
    }
}