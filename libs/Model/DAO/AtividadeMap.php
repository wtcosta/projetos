<?php
/** @package    WesleyProjetos::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * AtividadeMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the AtividadeDAO to the atividade datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package WesleyProjetos::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class AtividadeMap implements IDaoMap, IDaoMap2
{

	private static $KM;
	private static $FM;
	
	/**
	 * {@inheritdoc}
	 */
	public static function AddMap($property,FieldMap $map)
	{
		self::GetFieldMaps();
		self::$FM[$property] = $map;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function SetFetchingStrategy($property,$loadType)
	{
		self::GetKeyMaps();
		self::$KM[$property]->LoadType = $loadType;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetFieldMaps()
	{
		if (self::$FM == null)
		{
			self::$FM = Array();
			self::$FM["Id"] = new FieldMap("Id","atividade","id",true,FM_TYPE_INT,11,null,true,false);
			self::$FM["Projeto"] = new FieldMap("Projeto","atividade","projeto",false,FM_TYPE_INT,11,null,false,true);
			self::$FM["Descricao"] = new FieldMap("Descricao","atividade","descricao",false,FM_TYPE_TEXT,null,null,false,true);
			self::$FM["DataInicio"] = new FieldMap("DataInicio","atividade","data_inicio",false,FM_TYPE_DATE,null,null,false,true);
			self::$FM["DataEntrega"] = new FieldMap("DataEntrega","atividade","data_entrega",false,FM_TYPE_DATE,null,null,false,true);
			self::$FM["Obs"] = new FieldMap("Obs","atividade","obs",false,FM_TYPE_TEXT,null,null,false,true);
			self::$FM["Status"] = new FieldMap("Status","atividade","status",false,FM_TYPE_ENUM,array("Aguardando","Iniciada","Pendente","Concluida","Atrasada"),null,false,true);
		}
		return self::$FM;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetKeyMaps()
	{
		if (self::$KM == null)
		{
			self::$KM = Array();
			self::$KM["atv_pro"] = new KeyMap("atv_pro", "Projeto", "Projeto", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>