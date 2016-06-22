<?php

$this->assign('title', 'Wt Projetos | Projetos');
$this->assign('nav', 'projetos');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/projetos.js").wait(function () {
        $(document).ready(function () {
            page.init();
        });

        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function () {
            if (!page.isInitialized)
                page.init();
        }, 1000);
    });
</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Projetos
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Buscar..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>
    <!-- underscore template for the collection -->
    <script type="text/template" id="projetoCollectionTemplate">
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Nome">Nome<% if (page.orderBy == 'Nome') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Cliente">Cliente<% if (page.orderBy == 'Cliente') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Valor">Valor<% if (page.orderBy == 'Valor') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_DataInicio">Data Inicio<% if (page.orderBy == 'DataInicio') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_DataEntrega">Data Entrega<% if (page.orderBy == 'DataEntrega') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Prioridade">Prioridade<% if (page.orderBy == 'Prioridade') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th>Atividades<br />Aguardando</th>
        <th>Atividades<br />Pendentes</th>
        <th>Atividades<br />Atrasadas</th>
        <th>% p/ ok</th>
        <th id="header_Status">Status<% if (page.orderBy == 'Status') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
        <th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Obs">Obs<% if (page.orderBy == 'Obs') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        -->
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('id')) %>">
        <td><%= _.escape(item.get('nome') || '') %></td>
        <td><%= _.escape(item.get('nome_cliente') || '') %></td>
        <td><%= _.escape(item.get('valor') || '') %></td>
        <td><%if (item.get('dataInicio')) { %><%= _date(app.parseDate(item.get('dataInicio'))).format('DD/MM/YYYY') %><% } else { %>NULL<% } %></td>
        <td><%if (item.get('dataEntrega')) { %><%= _date(app.parseDate(item.get('dataEntrega'))).format('DD/MM/YYYY') %><% } else { %>NULL<% } %></td>
        <td><%= _.escape(item.get('prioridade') || '') %></td>
        <td><%= _.escape(item.get('a_aguardando') || '') %></td>
        <td><%= _.escape(item.get('a_pendente') || '') %></td>
        <td><%= _.escape(item.get('a_atrasada') || '') %></td>
        <td><%= _.escape(item.get('a_perc')+'%' || '') %></td>
        <td><%= _.escape(item.get('status') || '') %></td>
        <!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
        <td><%= _.escape(item.get('id') || '') %></td>
        <td><%= _.escape(item.get('obs') || '') %></td>
        -->
        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="projetoModelTemplate">
        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="idInputContainer" class="control-group">
        <label class="control-label" for="id">Id</label>
        <div class="controls inline-inputs">
        <span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="nomeInputContainer" class="control-group">
        <label class="control-label" for="nome">Nome</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="nome" placeholder="Nome" value="<%= _.escape(item.get('nome') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="clienteInputContainer" class="control-group">
        <label class="control-label" for="cliente">Cliente</label>
        <div class="controls inline-inputs">
        <select id="cliente" name="cliente"></select>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="dataInicioInputContainer" class="control-group">
        <label class="control-label" for="dataInicio">Data Inicio</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="dataInicio" type="text" value="<%= _date(app.parseDate(item.get('dataInicio'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="dataEntregaInputContainer" class="control-group">
        <label class="control-label" for="dataEntrega">Data Entrega</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="dataEntrega" type="text" value="<%= _date(app.parseDate(item.get('dataEntrega'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="valorInputContainer" class="control-group">
        <label class="control-label" for="valor">Valor</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="valor" placeholder="Valor" value="<%= _.escape(item.get('valor') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="obsInputContainer" class="control-group">
        <label class="control-label" for="obs">Obs</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="obs" rows="3"><%= _.escape(item.get('obs') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="prioridadeInputContainer" class="control-group">
        <label class="control-label" for="prioridade">Prioridade</label>
        <div class="controls inline-inputs">
        <select id="prioridade" name="prioridade">
        <option value=""></option>
        <option value="Alta"<% if (item.get('prioridade')=='Alta') { %> selected="selected"<% } %>>Alta</option>
        <option value="Media"<% if (item.get('prioridade')=='Media') { %> selected="selected"<% } %>>Media</option>
        <option value="Baixa"<% if (item.get('prioridade')=='Baixa') { %> selected="selected"<% } %>>Baixa</option>
        </select>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="statusInputContainer" class="control-group">
        <label class="control-label" for="status">Status</label>
        <div class="controls inline-inputs">
        <select id="status" name="status">
        <option value=""></option>
        <option value="Aguardando"<% if (item.get('status')=='Aguardando') { %> selected="selected"<% } %>>Aguardando</option>
        <option value="Iniciado"<% if (item.get('status')=='Iniciado') { %> selected="selected"<% } %>>Iniciado</option>
        <option value="Pendente"<% if (item.get('status')=='Pendente') { %> selected="selected"<% } %>>Pendente</option>
        <option value="Concluido"<% if (item.get('status')=='Concluido') { %> selected="selected"<% } %>>Concluido</option>
        </select>
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteProjetoButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteProjetoButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Remover Projeto</button>
        <span id="confirmDeleteProjetoContainer" class="hide">
        <button id="cancelDeleteProjetoButton" class="btn btn-mini">Cancelar</button>
        <button id="confirmDeleteProjetoButton" class="btn btn-mini btn-danger">Confirmar</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="projetoDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Editar Projeto
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="projetoModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveProjetoButton" class="btn btn-primary">Salvar Altera&ccedil;&otilde;es</button>
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="projetoCollectionContainer" class="collectionContainer">
    </div>

    <p id="newButtonContainer" class="buttonContainer">
        <button id="newProjetoButton" class="btn btn-primary">Adicionar Projeto</button>
    </p>

</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
