<?php
/** @package WesleyProjetos::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/Phreezable.php");
require_once("AtividadeMap.php");

/**
 * AtividadeDAO provides object-oriented access to the atividade table.  This
 * class is automatically generated by ClassBuilder.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * Add any custom business logic to the Model class which is extended from this DAO class.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package WesleyProjetos::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class AtividadeDAO extends Phreezable
{
	/** @var int */
	public $Id;

	/** @var int */
	public $Projeto;

	/** @var string */
	public $Descricao;

	/** @var date */
	public $DataInicio;

	/** @var date */
	public $DataEntrega;

	/** @var string */
	public $Obs;

	/** @var enum */
	public $Status;


	/**
	 * Returns the foreign object based on the value of Projeto
	 * @return Projeto
	 */
	public function GetProjeto()
	{
		return $this->_phreezer->GetManyToOne($this, "atv_pro");
	}


}
?>