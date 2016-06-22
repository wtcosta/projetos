<?php
/** @package    WesleyProjetos::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Projeto object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package WesleyProjetos::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ProjetoReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `projeto` table
	public $nome_cliente;
        public $a_aguardando;
        public $a_iniciada;
        public $a_pendente;
        public $a_concluida;
        public $a_atrasada;
        public $a_total;
        public $a_perc;
	public $Id;
	public $Nome;
	public $Cliente;
	public $DataInicio;
	public $DataEntrega;
	public $Valor;
	public $Obs;
	public $Prioridade;
	public $Status;

	/*
	* GetCustomQuery returns a fully formed SQL statement.  The result columns
	* must match with the properties of this reporter object.
	*
	* @see Reporter::GetCustomQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomQuery($criteria)
	{
		$sql = "select
			`cliente`.`nome` as nome_cliente
                        ,(SELECT COUNT(*) FROM atividade WHERE atividade.projeto = projeto.Id AND status='Aguardando') AS a_aguardando
                        ,(SELECT COUNT(*) FROM atividade WHERE atividade.projeto = projeto.Id AND status='Iniciada') AS a_iniciada
                        ,(SELECT COUNT(*) FROM atividade WHERE atividade.projeto = projeto.Id AND status='Pendente') AS a_pendente
                        ,(SELECT COUNT(*) FROM atividade WHERE atividade.projeto = projeto.Id AND status='Concluida') AS a_concluida
                        ,(SELECT COUNT(*) FROM atividade WHERE atividade.projeto = projeto.Id AND status='Atrasada') AS a_atrasada
                        ,((100*(SELECT COUNT(*) FROM atividade WHERE atividade.projeto = projeto.Id AND status='Concluida'))/(SELECT COUNT(*) FROM atividade WHERE atividade.projeto = projeto.Id)) as a_perc
			,`projeto`.`id` as Id
			,`projeto`.`nome` as Nome
			,`projeto`.`cliente` as Cliente
			,`projeto`.`data_inicio` as DataInicio
			,`projeto`.`data_entrega` as DataEntrega
			,`projeto`.`valor` as Valor
			,`projeto`.`obs` as Obs
			,`projeto`.`prioridade` as Prioridade
			,`projeto`.`Status` as Status
		from `projeto`
		INNER JOIN cliente ON cliente.id=projeto.cliente
		";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();
		$sql .= $criteria->GetOrder();

		return $sql;
	}
	
	/*
	* GetCustomCountQuery returns a fully formed SQL statement that will count
	* the results.  This query must return the correct number of results that
	* GetCustomQuery would, given the same criteria
	*
	* @see Reporter::GetCustomCountQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomCountQuery($criteria)
	{
		$sql = "select count(1) as counter from `projeto`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>