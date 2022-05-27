<!DOCTYPE html>
<html lang="en">
<?php require_once('head.php');?>
<style>
.titles {
  float: left;
  clear: both;
}
.form-check {
    position: relative;
    display: block;
    padding-left: 1.25rem;
}
</style>
<body id="page-top" class='sidebar-toggled'>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once('sidebar.php');?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php require_once('topo.php');?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Imóveis</h1>
                    <p class="mb-4">Listagem de imóveis em leilão.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Imóveis</h6>
                        </div>
                        <div class="card-body">
                            <div>
                                <form autocomplete="off" id="frmfiltro" name="frmfiltro">
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="control-label" for="inputBasicFirstName">Status</label>
                                            <select class='form-control select' multiple="multiple" name="status"
                                                id="status">
                                                <option value="1" selected>Não enviado </option>
                                                <option value="2">Enviado </option>
                                                <option value="3">Avaliado e descartado</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="inputBasicLastName">Status Leilão</label>
                                            <select class='form-control select' multiple="multiple" name="status_leilao"
                                                id="status_leilao">
                                                <option value="1" selected>Aberto </option>
                                                <option value="3" selected>Aguardando Abertura</option>
                                                <option value="4">Sustado/Cancelado</option>
                                                <option value="5">Sem Licitante</option>
                                                <option value="6">Encerrado</option>
                                                <option value="NULL" selected>Sem Status</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="inputBasicFirstName">Maior data do
                                                leilão</label>
                                            <input type="text" class="form-control" id="maior_data_inicio"
                                                name="maior_data_inicio" placeholder="Data Início" autocomplete="off" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="inputBasicFirstName">Maior data do
                                                leilão</label>
                                            <input type="text" class="form-control" id="maior_data_fim"
                                                name="maior_data_fim" placeholder="Data fim" autocomplete="off" />
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <label class="control-label" for="inputBasicLastName">Tipologia</label>
                                            <select class='form-control select' multiple="multiple" name="tipo"
                                                id="tipo">
                                                <option value="apartamento" selected >Apartamento</option>
                                                <option value="condominio_residencial" selected >Condôminio Residencial</option>
                                                <option value="imovel-comercial">Imovél Comercial</option>
                                                <option value="sobrado" selected >Sobrado</option>
                                                <option value="casa" selected >Casa</option>
                                                <option value="loja">Loja</option>
                                                <option value="galpao">Galpão</option>
                                                <option value="lote-terreno" selected >Lote-Terreno</option>
                                                <option value="fazenda" selected >Fazenda</option>
                                                <option value="sitio" selected >Sítio</option>
                                                <option value="cobertura" selected >Cobertura</option>
                                                <option value="chacara" selected >Chácara </option>

                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="inputBasicFirstName">Praça 1 </label>
                                            <input type="text" class="form-control date" id="praca_1_data_inicio"
                                                name="praca_1_data_inicio" placeholder="Data Início"
                                                autocomplete="off" />
                                        </div>
                                        <div class="col-sm-3">
                                            Até
                                            <label class="control-label" for="inputBasicLastName">Praça 1</label>
                                            <input type="text" class="form-control date" id="praca_1_data_fim"
                                                name="praca_1_data_fim" placeholder="Data fim" autocomplete="off" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" for="inputBasicLastName">Tipo Leilão</label>
                                            <select class='form-control select' multiple="multiple" name="tipo_leilao"
                                                id="tipo_leilao">
                                                <option value="">--todos--</option>
                                                <option value="judicial">Judicial</option>
                                                <option value="extrajudicial">Extrajudicial</option>
                                                <option value="outros">outros</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="inputBasicLastName">Estado</label>
                                            <select name="estado" id="estado" class='form-control'>
                                                <option value="">--selecione--
                                                <option>
                                                <option value="AC">Acre
                                                <option>
                                                <option value="AL">Alagoas
                                                <option>
                                                <option value="AP">Amapá
                                                <option>
                                                <option value="AM">Amazonas
                                                <option>
                                                <option value="BA">Bahia
                                                <option>
                                                <option value="CE">Ceará
                                                <option>
                                                <option value="DF">Distrito Federal
                                                <option>
                                                <option value="ES">Espírito Santo
                                                <option>
                                                <option value="GO">Goiás
                                                <option>
                                                <option value="MA">Maranhão
                                                <option>
                                                <option value="MT">Mato Grosso
                                                <option>
                                                <option value="MS">Mato Grosso do Sul
                                                <option>
                                                <option value="MG">Minas Gerais
                                                <option>
                                                <option value="PA">Pará
                                                <option>
                                                <option value="PB">Paraíba
                                                <option>
                                                <option value="PR">Paraná
                                                <option>
                                                <option value="PE">Pernambuco
                                                <option>
                                                <option value="PI">Piauí
                                                <option>
                                                <option value="RJ">Rio de Janeiro
                                                <option>
                                                <option value="RN">Rio Grande do Norte
                                                <option>
                                                <option value="RS">Rio Grande do Sul
                                                <option>
                                                <option value="RO">Rondônia
                                                <option>
                                                <option value="RR">Roraima
                                                <option>
                                                <option value="SC">Santa Catarina
                                                <option>
                                                <option value="SP" selected>São Paulo
                                                <option>
                                                <option value="SE">Sergipe
                                                <option>
                                                <option value="TO">Tocantins
                                                <option>

                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label" for="inputBasicFirstName">Município</label>
                                            <select name="municipio" id="municipio" class='form-control'
                                                multiple="multiple" style="width:100%">
                                                <option value="">--selecione--</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label" for="inputBasicFirstName">Fontes</label>
                                            <select name="fonte" id="fonte" class='form-control' multiple="multiple"
                                                style="width:100%">
                                                <option value="">--selecione--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row " >
                                        <div class="col-sm-4">
                                             <label class="control-label" for="inputBasicFirstName">Busca </label>
                                            <input type="text" name="busca"  class='form-control'  id="busca" value="" >
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="col-sm-4">
                                            <label>
                                                <input type="checkbox" name="exibir_sem_geometria"
                                                    id="exibir_sem_geometria" value='1'> Exibir leilões sem a geometria
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="sybmit" class="btn btn-primary">Filtrar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">

                            
<div class="titles">
    <button id="remove" class="btn btn-danger" >
        <i class="fa fa-trash"></i> Descartar
    </button>
</div>

                                <table class="table table-bordered table-condensed " id="table" data-search="true"
                                    data-detail-view="true" data-detail-formatter="detailFormatter"
                                    data-show-columns="true" data-pagination="true" width="95%" cellspacing="0"
                                    data-show-export="true">
                                    <thead>
                                        <tr>
                                            <th data-checkbox="true"  data-formatter="stateFormatter"></th>
                                            <th data-field="id_imovel" data-sortable="true" data-formatter="linkDescartar">Descartar</th>
                                            <th data-field="id_imovel" data-sortable="true" data-formatter="linkPipefy">
                                                Ações</th>
                                                
                                            
                                            <th data-field="id_imovel" data-sortable="true"
                                                data-formatter="linkAlterar">Alterar Status</th>
                                            <!-- <th data-field="valor_mercado" data-sortable="true" data-formatter="linkSync" >
                                                Valor de mercado</th> -->
                                            <th data-field="imagens" data-sortable="true" data-formatter="imagens">
                                                Imagens</th>
                                            <th data-field="id_imovel" data-sortable="true">id</th>
                                            <th data-field="link_anunc" data-sortable="true" data-formatter="link">link
                                            </th>
                                            <th data-field="tipo" data-sortable="true">Tipo</th>
                                            <th data-field="endereco" data-sortable="true" data-width="200">Endereço
                                            </th>
                                            <th data-field="praca_1_data" data-sortable="true"
                                                data-formatter="dataFormat">Praça 1 Data</th>
                                            <th data-field="praca_1" data-sortable="true"
                                                data-formatter="currencyFormat">Praça 1 Valor</th>
                                            <th data-field="praca_2_data" data-sortable="true"
                                                data-formatter="dataFormat">Praça 2 Data</th>
                                            <th data-field="praca_2" data-sortable="true"
                                                data-formatter="currencyFormat">Praça 2 Valor</th>
                                            <th data-field="diferenca" data-sortable="true">Diferença P2/P1</th>
                                            <th data-field="praca_unica" data-sortable="true"
                                                data-formatter="currencyFormat">Valor Praça Única</th>
                                            <th data-field="ultima_data_leilao" data-sortable="true"
                                                data-formatter="dataFormat">Última data do leilão</th>
                                            <th data-field="imovel_ocupado" data-sortable="true">Ocupado</th>
                                            <th data-field="edital" data-sortable="true" data-formatter="linkDownload">
                                                Edital</th>
                                            <th data-field="matricula" data-sortable="true"
                                                data-formatter="linkDownload">Matricula</th>
                                            <th data-field="tipo_leilao" data-sortable="true">Tipo Leilão</th>
                                            <th data-field="fonte" data-sortable="true">fonte</th>


                                            <th data-field="situacao" data-sortable="true"
                                                data-formatter="situacaoFormat" data-visible="false">Situação</th>
                                            <th data-field="numero_processo" data-sortable="true" data-visible="false">
                                                Número do Processo</th>




                                            <!-- <th  data-field="lote_vendido" data-sortable="true">Vendido</th>
                       -->

                                            <!-- <th  data-field="situacao_leilao" data-sortable="true" data-visible="false">Situação Leilao</th>  -->
                                            <!-- <th  data-field="valor" data-sortable="true">valor</th> -->
                                            <!-- <th  data-field="iptu" data-sortable="true">iptu</th> -->
                                            <th data-field="banheiro" data-sortable="true" data-visible="false">banheiro
                                            </th>
                                            <th data-field="suites" data-sortable="true" data-visible="false">suites
                                            </th>
                                            <th data-field="quartos" data-sortable="true" data-visible="false">quartos
                                            </th>
                                            <th data-field="vagas" data-sortable="true" data-visible="true">Vagas</th>
                                            <th data-field="area" data-sortable="true" data-visible="true">area</th>
                                            <th data-field="status" data-sortable="true" data-visible="false">status
                                            </th>
                                            <th data-field="descricao" data-sortable="true" data-visible="false">
                                                descrição</th>
                                            <th data-field="caracteristicas" data-sortable="true" data-visible="false">
                                                Caracteristicas</th>
                                            <th data-field="edital" data-sortable="true" data-visible="false">Edital
                                            </th>
                                            <th data-field="matricula" data-sortable="true" data-visible="false">
                                                Matricula</th>

                                            <th data-field="numero_processo" data-sortable="true" data-visible="false">
                                                numero_processo</th>
                                            <th data-field="endereco" data-sortable="true" data-visible="false">endereco
                                            </th>
                                            <th data-field="cidade" data-sortable="true" data-visible="false">cidade
                                            </th>
                                            <th data-field="estado" data-sortable="true" data-visible="false">estado
                                            </th>

                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>Position</th>
                      <th>Office</th>
                      <th>Age</th>
                      <th>Start date</th>
                      <th>Salary</th>
                    </tr>
                  </tfoot> -->
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form name="send" id="send">
                    <input type="hidden" name="data" id="data" value="">
                    <input type="hidden" name="action" value="sendCard">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="pipe_selected" id="pipe_selected" value="">
                    <input type="hidden" name="due_date" id="due_date" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Enviar para o pipefy</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label" for="inputBasicFirstName">Detalhe do Card</label>
                                <input type="text" class="form-control" name="title" id="title" value="">
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label" for="inputBasicFirstName">Selecione a fase</label>
                                <select name="phases" id="phases" class='form-control'>
                                    <option value="">--selecione--</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group form-check">
                            <div class="col-sm-12">
                                <input class="form-check-input" type="radio" name="pipe_id" id="selectFundo1" value="301327004" onClick="GetSelectedItem(this)">
                                <label class="form-check-label" for="selectFundo1">
                                    Enviar para o Fundo 1
                                </label>
                                <!-- <input class="form-check-input" type="radio" name="pipe_id" value="301327004"/>Fundo 1
                                <input class="form-check-input" type="radio" name="pipe_id" value="301813272"/>Fundo 2 -->
                            </div>
                        </div>
                        <div class="form-group form-check">
                            <div class="col-sm-12">
                                <input class="form-check-input" type="radio" name="pipe_id" id="selectFundo2" value="301813272" onClick="GetSelectedItem(this)">
                                <label class="form-check-label" for="selectFundo2">
                                    Enviar para o Fundo 2
                                </label>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label" for="inputBasicFirstName">Valor de Mercado</label>
                                <input type="text" class="form-control" name="title" id="title" value="">
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label" for="inputBasicFirstName"></label>
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </div>

                        <div class="form-group row" id="error" style="display:none">
                            <div class="col-sm-12">
                                <div class='alert'></div>
                            </div>
                        </div>
                    </div>
                </form>


                <form name="form-sync" id="form-sync" style="    padding: 15px;">
                    <input type="hidden" name="action" value="sync">
                    <input type="hidden" name="servico"  id="servico" value="imoveis-ofertas-estatisticas">
                    
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label class="control-label" for="inputBasicFirstName">Latitude</label>
                            <input type="text" class="form-control" name="latitude" id="latitude" value="">
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label" for="inputBasicFirstName">Longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude" value="">
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label" for="inputBasicFirstName">Distância</label>
                            <input type="text" class="form-control" name="distancia" id="distancia" value="50">
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <!-- <label class="control-label" for="inputBasicFirstName">Serviço</label>
                            <select class="form-control" name="servico" id="servico">
                            <option value="">--selecione-- </option>
                            <option value="imoveis-ofertas-estatisticas">Estatísticas Imóveis Ofertas </option>
                            <option value="apartamentos-ofertas">Ofertas de Apartamentos </option>
                            </select> -->
                        </div>
                    </div>

                </form>


                <div class="row">
                    <div class='col-md-12'>

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home"
                                    role="tab" aria-controls="nav-home" aria-selected="true">Estatísticas Imóveis Ofertas</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile"
                                    role="tab" aria-controls="nav-profile" aria-selected="false">Ofertas de
                                    Apartamentos</a>
                             
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                aria-labelledby="nav-home-tab">
                                <div class='loading-imoveis-ofertas-estatisticas' style="display:none">Carregando....</div>
                                <table class='table table-responsive table-hover table-striped'>
                                    <thead id="table-head-imoveis-ofertas-estatisticas">
                                    </thead>
                                    <tbody id="table-response-imoveis-ofertas-estatisticas">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab">
                                <div class='loading-apartamentos-ofertas'  style="display:none">Carregando....</div>
                                <table class='table table-responsive table-hover table-striped'>
                                    <thead id="table-head-apartamentos-ofertas">
                                    </thead>
                                    <tbody id="table-response-apartamentos-ofertas">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Estatísticas Imóveis Ofertas </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Ofertas de
                                    Apartamentos </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                             1   <table class='table table-responsive table-hover table-striped'>
                                    <thead id="table-head">
                                    </thead>
                                    <tbody id="table-response">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                               2 <table class='table table-responsive table-hover table-striped'>
                                    <thead id="table-head2">
                                    </thead>
                                    <tbody id="table-response2">
                                    </tbody>
                                </table>
                            </div>
                            
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="Modalchange" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form name="change" id="change">
                    <input type="hidden" name="action" value="change">
                    <input type="hidden" name="id_change" id="id_change" value="">


                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label" for="inputBasicFirstName">Motivo</label>
                                <input type="text" class="form-control" name="motivo" id="motivo" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label" for="inputBasicFirstName">Enviar para</label>
                                <select name="change-status" id="change-status" class='form-control'>
                                    <option value="">--selecione--</option>
                                    <option value="1">Aguardando Análise</option>
                                    <option value="2">Enviado Pipefy</option>
                                    <option value="3">Descartado</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="error_change" style="display:none">
                            <div class="col-sm-12">
                                <div class='alert'></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSync" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content ">
                <form name="form-sync" id="form-sync">
                    <input type="hidden" name="action" value="sync">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sync</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label" for="inputBasicFirstName">Latitude</label>
                                <input type="text" class="form-control" name="latitude" id="latitude"
                                    value="-23.549225">
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" for="inputBasicFirstName">Longitude</label>
                                <input type="text" class="form-control" name="longitude" id="longitude"
                                    value="-46.658799">
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" for="inputBasicFirstName">Distância</label>
                                <input type="text" class="form-control" name="distancia" id="distancia" value="50">
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" for="inputBasicFirstName">Serviço</label>
                                <select class="form-control" name="servico" id="servico">
                                    <option value="">--selecione-- </option>
                                    <option value="imoveis-ofertas-estatisticas">Estatísticas Imóveis Ofertas </option>
                                    <option value="apartamentos-ofertas">Ofertas de Apartamentos </option>
                                </select>
                            </div>
                        </div>

                        <table class='table table-responsive table-hover table-striped'>
                            <thead id="table-head">
                            </thead>
                            <tbody id="table-response">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal2">

        <!-- The Close Button -->
        <span class="close">&times;</span>

        <!-- Modal Content (The Image) -->
        <img class="modal-content2" id="img01">

        <!-- Modal Caption (Image Text) -->
        <div id="caption"></div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script> -->

    <script src="vendor/bootstrap-table/dist/jquery.resizableColumns.min.js"></script>
    <script src="vendor/bootstrap-table/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
    <script src="vendor/bootstrap-table/dist/extensions/resizable/bootstrap-table-resizable.min.js"></script>
    <script src="vendor/select2/select2_4.0/js/select2.min.js"></script>
    <script src="vendor/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js"></script>



    <!-- Page level custom scripts -->
    <script src="js/tables.js?v=0.0.5"></script>

</body>

</html>