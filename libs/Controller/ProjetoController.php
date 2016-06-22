<?php

/** @package    Wt Projetos::Controller */
/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Projeto.php");

/**
 * ProjetoController is the controller class for the Projeto object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Wt Projetos::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ProjetoController extends AppBaseController {

    /**
     * Override here for any controller-specific functionality
     *
     * @inheritdocs
     */
    protected function Init() {
        parent::Init();
    }

    /**
     * Displays a list view of Projeto objects
     */
    public function ListView() {
        $this->Render();
    }

    /**
     * API Method queries for Projeto records and render as JSON
     */
    public function Query() {
        try {
            $criteria = new ProjetoCriteria();

            // TODO: this will limit results based on all properties included in the filter list 
            $filter = RequestUtil::Get('filter');
            if ($filter)
                $criteria->AddFilter(
                        new CriteriaFilter('Id,Nome,Cliente,DataInicio,DataEntrega,Valor,Obs,Prioridade,Status'
                        , '%' . $filter . '%')
                );

            // TODO: this is generic query filtering based only on criteria properties
            foreach (array_keys($_REQUEST) as $prop) {
                $prop_normal = ucfirst($prop);
                $prop_equals = $prop_normal . '_Equals';

                if (property_exists($criteria, $prop_normal)) {
                    $criteria->$prop_normal = RequestUtil::Get($prop);
                } elseif (property_exists($criteria, $prop_equals)) {
                    // this is a convenience so that the _Equals suffix is not needed
                    $criteria->$prop_equals = RequestUtil::Get($prop);
                }
            }

            $output = new stdClass();

            // if a sort order was specified then specify in the criteria
            $output->orderBy = RequestUtil::Get('orderBy');
            $output->orderDesc = RequestUtil::Get('orderDesc') != '';
            if ($output->orderBy)
                $criteria->SetOrder($output->orderBy, $output->orderDesc);

            $page = RequestUtil::Get('page');

            if ($page != '') {
                // if page is specified, use this instead (at the expense of one extra count query)
                $pagesize = $this->GetDefaultPageSize();

                $projetos = $this->Phreezer->Query('ProjetoReporter', $criteria)->GetDataPage($page, $pagesize);
                $output->rows = $projetos->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = $projetos->TotalResults;
                $output->totalPages = $projetos->TotalPages;
                $output->pageSize = $projetos->PageSize;
                $output->currentPage = $projetos->CurrentPage;
            } else {
                // return all results
                $projetos = $this->Phreezer->Query('ProjetoReporter', $criteria);
                $output->rows = $projetos->ToObjectArray(true, $this->SimpleObjectParams());
                $output->totalResults = count($output->rows);
                $output->totalPages = 1;
                $output->pageSize = $output->totalResults;
                $output->currentPage = 1;
            }


            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method retrieves a single Projeto record and render as JSON
     */
    public function Read() {
        /**
         * Informe o tipo de permissao
         */
        $this->RequirePermission(User::$PERMISSION_READ, 'Secure.LoginForm', 'Login requerido para acessar está página', 'Permissao de leitura é obrigatória');

        try {
            $pk = $this->GetRouter()->GetUrlParam('id');
            $projeto = $this->Phreezer->Get('Projeto', $pk);
            $this->RenderJSON($projeto, $this->JSONPCallback(), true, $this->SimpleObjectParams());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method inserts a new Projeto record and render response as JSON
     */
    public function Create() {
        /**
         * Informe o tipo de permissao
         */
        $this->RequirePermission(User::$PERMISSION_WRITE, 'Secure.LoginForm', 'Login requerido para acessar está página', 'Permissao de escrita é obrigatória');

        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $projeto = new Projeto($this->Phreezer);

            // TODO: any fields that should not be inserted by the user should be commented out
            // this is an auto-increment.  uncomment if updating is allowed
            // $projeto->Id = $this->SafeGetVal($json, 'id');

            $projeto->Nome = $this->SafeGetVal($json, 'nome');
            $projeto->Cliente = $this->SafeGetVal($json, 'cliente');
            $projeto->DataInicio = date('Y-m-d H:i:s', strtotime($this->SafeGetVal($json, 'dataInicio')));
            $projeto->DataEntrega = date('Y-m-d H:i:s', strtotime($this->SafeGetVal($json, 'dataEntrega')));
            $projeto->Valor = $this->SafeGetVal($json, 'valor');
            $projeto->Obs = $this->SafeGetVal($json, 'obs');
            $projeto->Prioridade = $this->SafeGetVal($json, 'prioridade');
            $projeto->Status = $this->SafeGetVal($json, 'status');

            $projeto->Validate();
            $errors = $projeto->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Por Favor, verifique os erros', $errors);
            } else {
                $projeto->Save();
                $this->RenderJSON($projeto, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method updates an existing Projeto record and render response as JSON
     */
    public function Update() {
        /**
         * Informe o tipo de permissao
         */
        $this->RequirePermission(User::$PERMISSION_EDIT, 'Secure.LoginForm', 'Login requerido para acessar está página', 'Permissao de edição é obrigatória');

        try {

            $json = json_decode(RequestUtil::GetBody());

            if (!$json) {
                throw new Exception('The request body does not contain valid JSON');
            }

            $pk = $this->GetRouter()->GetUrlParam('id');
            $projeto = $this->Phreezer->Get('Projeto', $pk);

            // TODO: any fields that should not be updated by the user should be commented out
            // this is a primary key.  uncomment if updating is allowed
            // $projeto->Id = $this->SafeGetVal($json, 'id', $projeto->Id);

            $projeto->Nome = $this->SafeGetVal($json, 'nome', $projeto->Nome);
            $projeto->Cliente = $this->SafeGetVal($json, 'cliente', $projeto->Cliente);
            $projeto->DataInicio = date('Y-m-d H:i:s', strtotime($this->SafeGetVal($json, 'dataInicio', $projeto->DataInicio)));
            $projeto->DataEntrega = date('Y-m-d H:i:s', strtotime($this->SafeGetVal($json, 'dataEntrega', $projeto->DataEntrega)));
            $projeto->Valor = $this->SafeGetVal($json, 'valor', $projeto->Valor);
            $projeto->Obs = $this->SafeGetVal($json, 'obs', $projeto->Obs);
            $projeto->Prioridade = $this->SafeGetVal($json, 'prioridade', $projeto->Prioridade);
            $projeto->Status = $this->SafeGetVal($json, 'status', $projeto->Status);

            $projeto->Validate();
            $errors = $projeto->GetValidationErrors();

            if (count($errors) > 0) {
                $this->RenderErrorJSON('Por Favor, verifique os erros', $errors);
            } else {
                $projeto->Save();
                $this->RenderJSON($projeto, $this->JSONPCallback(), true, $this->SimpleObjectParams());
            }
        } catch (Exception $ex) {


            $this->RenderExceptionJSON($ex);
        }
    }

    /**
     * API Method deletes an existing Projeto record and render response as JSON
     */
    public function Delete() {
        /**
         * Informe o tipo de permissao
         */
        $this->RequirePermission(User::$PERMISSION_ADMIN, 'Secure.LoginForm', 'Login requerido para acessar está página', 'Permissao de leitura é obrigatória');

        try {

            // TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

            $pk = $this->GetRouter()->GetUrlParam('id');
            $projeto = $this->Phreezer->Get('Projeto', $pk);

            $projeto->Delete();

            $output = new stdClass();

            $this->RenderJSON($output, $this->JSONPCallback());
        } catch (Exception $ex) {
            $this->RenderExceptionJSON($ex);
        }
    }

}

?>
