<?php
	$this->assign('title','Wt Projetos | Atividades');
	$this->assign('nav','atividades');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/atividades.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<i class="icon-th-list"></i> Atividades
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Buscar..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="atividadeCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Projeto">Projeto<% if (page.orderBy == 'Projeto') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Descricao">Descricao<% if (page.orderBy == 'Descricao') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_DataInicio">Data Inicio<% if (page.orderBy == 'DataInicio') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_DataEntrega">Data Entrega<% if (page.orderBy == 'DataEntrega') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_Obs">Obs<% if (page.orderBy == 'Obs') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Status">Status<% if (page.orderBy == 'Status') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%= _.escape(item.get('projeto') || '') %></td>
				<td><%= _.escape(item.get('descricao') || '') %></td>
				<td><%if (item.get('dataInicio')) { %><%= _date(app.parseDate(item.get('dataInicio'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('dataEntrega')) { %><%= _date(app.parseDate(item.get('dataEntrega'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('obs') || '') %></td>
				<td><%= _.escape(item.get('status') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="atividadeModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="projetoInputContainer" class="control-group">
					<label class="control-label" for="projeto">Projeto</label>
					<div class="controls inline-inputs">
						<select id="projeto" name="projeto"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="descricaoInputContainer" class="control-group">
					<label class="control-label" for="descricao">Descricao</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="descricao" rows="3"><%= _.escape(item.get('descricao') || '') %></textarea>
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
				<div id="obsInputContainer" class="control-group">
					<label class="control-label" for="obs">Obs</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="obs" rows="3"><%= _.escape(item.get('obs') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="statusInputContainer" class="control-group">
					<label class="control-label" for="status">Status</label>
					<div class="controls inline-inputs">
						<select id="status" name="status">
							<option value=""></option>
							<option value="Aguardando"<% if (item.get('status')=='Aguardando') { %> selected="selected"<% } %>>Aguardando</option>
							<option value="Iniciada"<% if (item.get('status')=='Iniciada') { %> selected="selected"<% } %>>Iniciada</option>
							<option value="Pendente"<% if (item.get('status')=='Pendente') { %> selected="selected"<% } %>>Pendente</option>
							<option value="Concluida"<% if (item.get('status')=='Concluida') { %> selected="selected"<% } %>>Concluida</option>
							<option value="Atrasada"<% if (item.get('status')=='Atrasada') { %> selected="selected"<% } %>>Atrasada</option>
						</select>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteAtividadeButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteAtividadeButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Remover Atividade</button>
						<span id="confirmDeleteAtividadeContainer" class="hide">
							<button id="cancelDeleteAtividadeButton" class="btn btn-mini">Cancelar</button>
							<button id="confirmDeleteAtividadeButton" class="btn btn-mini btn-danger">Confirmar</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="atividadeDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Editar Atividade
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="atividadeModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveAtividadeButton" class="btn btn-primary">Salvar Altera&ccedil;&otilde;es</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="atividadeCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newAtividadeButton" class="btn btn-primary">Adicionar Atividade</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
