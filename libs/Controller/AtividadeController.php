<?php
/** @package    Wt Projetos::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Atividade.php");

/**
 * AtividadeController is the controller class for the Atividade object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Wt Projetos::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class AtividadeController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();
		
		/**
		 * Informe o tipo de permissao
		 */
		$this->RequirePermission(User::$PERMISSION_READ, 
			'Secure.LoginForm', 
			'Login requerido para acessar esta pagina',
			'Permissao de leitura e obrigatoria');
	}

	/**
	 * Displays a list view of Atividade objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Atividade records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new AtividadeCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Projeto,Descricao,DataInicio,DataEntrega,Obs,Status'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$atividades = $this->Phreezer->Query('Atividade',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $atividades->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $atividades->TotalResults;
				$output->totalPages = $atividades->TotalPages;
				$output->pageSize = $atividades->PageSize;
				$output->currentPage = $atividades->CurrentPage;
			}
			else
			{
				// return all results
				$atividades = $this->Phreezer->Query('Atividade',$criteria);
				$output->rows = $atividades->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single Atividade record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$atividade = $this->Phreezer->Get('Atividade',$pk);
			$this->RenderJSON($atividade, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Atividade record and render response as JSON
	 */
	public function Create()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$atividade = new Atividade($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $atividade->Id = $this->SafeGetVal($json, 'id');

			$atividade->Projeto = $this->SafeGetVal($json, 'projeto');
			$atividade->Descricao = $this->SafeGetVal($json, 'descricao');
			$atividade->DataInicio = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataInicio')));
			$atividade->DataEntrega = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataEntrega')));
			$atividade->Obs = $this->SafeGetVal($json, 'obs');
			$atividade->Status = $this->SafeGetVal($json, 'status');

			$atividade->Validate();
			$errors = $atividade->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Por Favor, verifique os erros',$errors);
			}
			else
			{
				$atividade->Save();
				$this->RenderJSON($atividade, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Atividade record and render response as JSON
	 */
	public function Update()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('id');
			$atividade = $this->Phreezer->Get('Atividade',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $atividade->Id = $this->SafeGetVal($json, 'id', $atividade->Id);

			$atividade->Projeto = $this->SafeGetVal($json, 'projeto', $atividade->Projeto);
			$atividade->Descricao = $this->SafeGetVal($json, 'descricao', $atividade->Descricao);
			$atividade->DataInicio = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataInicio', $atividade->DataInicio)));
			$atividade->DataEntrega = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataEntrega', $atividade->DataEntrega)));
			$atividade->Obs = $this->SafeGetVal($json, 'obs', $atividade->Obs);
			$atividade->Status = $this->SafeGetVal($json, 'status', $atividade->Status);

			$atividade->Validate();
			$errors = $atividade->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Por Favor, verifique os erros',$errors);
			}
			else
			{
				$atividade->Save();
				$this->RenderJSON($atividade, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Atividade record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$atividade = $this->Phreezer->Get('Atividade',$pk);

			$atividade->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
}

?>
