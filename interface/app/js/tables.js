// Call the dataTables jQuery plugin
$(document).ready(function () {
  Core.init();
  Pipefy.init();
});

var Core = {
  table: {},
  pageActive: 1,
  init: function () {
    this.table = $("#table");
    this.loadEvents();

    this.initTable();
  },
  initTable: function () {
    var dados = {
      status_analise: $("#status").val(),
      status_leilao: $("#status_leilao").val(),
      maior_data_inicio: $("#maior_data_inicio").val(),
      maior_data_fim: $("#maior_data_fim").val(),
      praca_1_data_inicio: $("#praca_1_data_inicio").val(),
      praca_1_data_fim: $("#praca_1_data_fim").val(),
      tipo: $("#tipo").val(),
      municipio: $("#municipio").val(),
      fonte: $("#fonte").val(),
      tipo_leilao: $("#tipo_leilao").val(),
      exibir_sem_geometria: $("#exibir_sem_geometria:checked").val(),
      busca:$("#busca").val()
    };

    var pageNumber = Core.pageActive;
    console.log(pageNumber);
    this.table.bootstrapTable("destroy").bootstrapTable({
      url: "load-imoveis.php",
      locale: "pt-BR",
      fixedColumns: true,
      showExport: true,
      pageSize:100,
      pageNumber: pageNumber,
      //selectPage: pageNumber,
      exportTypes: ["csv", "txt", "excel"],
      queryParams: function (param) {
        Object.assign(param, dados);
        return param;
      },
      exportOptions: {
        fileName: `Imóveis em Leilão`,
        onTableExportBegin: function () {
          $(".bootstrap-table .page-list ul li a").each(function (index, el) {
            const val = $(el).html();
            if (val == "Tudo") {
              $(el).click();
            }
          });
        },
        onTableExportEnd: function () {
          $(".bootstrap-table .page-list ul li a").each(function (index, el) {
            const val = $(el).html();
            if (val == 10) {
              $(el).click();
            }
          });
        },
        onCellHtmlHyperlink: function (cell) {
          let link = "";
          if ($(cell).find("a").data("rel") == "externo") {
            link = $(cell).find("a").attr("href");
          } else {
            link = $(cell).find("a").attr("title");
          }
          return link;
        },
        ignoreColumn: [0, 2],
      },
    });
    
    Core.table.bootstrapTable("selectPage", pageNumber);
    

    $("#table").on("click", ".img-modal", function () {
      //  $('#myModal').modal('show')
      var modal = document.getElementById("myModal");

      // Get the image and insert it inside the modal - use its "alt" text as a caption
      var img = $(this);
      var modalImg = document.getElementById("img01");
      var captionText = document.getElementById("caption");
      //img.onclick = function(){
      modal.style.display = "block";
      modalImg.src = this.src;
      captionText.innerHTML = this.alt;
      //}
    });
  },
  filtrar: function () {},
  loadEvents: function () {
    $("#frmfiltro").submit(function () {
      Core.pageActive = Core.table.bootstrapTable("getOptions").pageNumber;
      Core.initTable();
      return false;
    });
    $("#municipio").select2({
      placeholder: "Selecione",
      language: "pt",
      width: "resolve",
      language: {
        noResults: function () {
          return "Não encontrado.";
        },
        searching: function () {
          return "Procurando...";
        },
      },
    });

    $("#fonte").select2({
      placeholder: "Selecione",
      language: "pt",
      width: "resolve",
      language: {
        noResults: function () {
          return "Não encontrado.";
        },
        searching: function () {
          return "Procurando...";
        },
      },
    });

    $("#estado").change(function () {
      var estadoId = $(this).val();

      Core.loadMunicipio(estadoId);
    });

    $("#form-sync").submit(function () {
      
      // if($('#servico').val() == 'imoveis-ofertas-estatisticas') {
      //   $('.loading1').show()
      // }else if($('#servico').val() == 'apartamentos-ofertas') {
      //   $('.loading2').show()
      // }
      // if($('#servico').val() == ''){
      //    alert('Informe o serviço!')
      //    return false;
      // }else
      if ($("#latitude").val() == "") {
        alert("Informe a latitude!");
        return false;
      } else if ($("#longitude").val() == "") {
        alert("Informe a longitude!");
        return false;
      } else if ($("#distancia").val() == "") {
        alert("Informe a distância!");
        return false;
      }
      array = ["imoveis-ofertas-estatisticas", "apartamentos-ofertas"];

      for (var i in array) {
        $(".loading-"+array[i]).show();
        $("#servico").val(array[i]);
        var form = $("#form-sync").serialize();
        $.post("edit.php?action=sync", form).then(function (res) {
          var response = JSON.parse(res);
          var html = "";
          var htmlHeade = "";
          var valor = "";
          if(response.itens.length > 0 ){
            for (var i in response.itens) {
              html += "<tr>";
              htmlHeade = "<tr>";
              for (var j in response.itens[i]) {
                valor = response.itens[i][j];
                htmlHeade += "<th>" + j + "</th>";
                if (valor != null) {
                  if (!isNaN(parseFloat(valor))) {
                    valorNumber = valor.toLocaleString("pt-br", {
                      // style: 'currency',
                      currency: "BRL",
                    });
                    html += "<td>" + valorNumber + "</td>";
                  } else if (valor.indexOf("http") !== -1) {
                    html +=
                      '<td><a href="' + valor + '" target="_blank">Ver</a></td>';
                  } else {
                    html += "<td>" + valor + "</td>";
                  }
                } else {
                  html += "<td>-</td>";
                }
              }
              htmlHeade += "</tr>";
              html += "</tr>";
            }
          }else{
            htmlHeade +='<tr><td>Dados</td></tr>'
            html += "<tr><td>Nenhuma amotras encontrada</td></tr>";
          }
          $("#table-head-"+response.service).html(htmlHeade);
          $("#table-response-"+response.service).html(html);
          $(".loading-"+array[i]).hide();
        });
      }
      $(".loading-apartamentos-ofertas").hide();
      $(".loading-imoveis-ofertas-estatisticas").hide();
      

      return false;
    });


    $("#profile-tab").click(function () {
      console.log($(this).attr("aria-controls"));
      $(".tab-pane").hide();
      $("#" + $(this).attr("aria-controls")).show();
    });


    $("#change").submit(function () {
      var form = $("#change").serialize();
      Core.pageActive = Core.table.bootstrapTable("getOptions").pageNumber;
      console.log(Core.pageActive);
      $.post("edit.php?action=change", form).then(function (res) {
        var response = JSON.parse(res);

        if (response.erro == true) {
          $("#error_change").show();
          $("#error_change .alert").addClass("alert-danger");
          $("#error_change .alert").removeClass("alert-success");
          $("#error_change .alert").html(response.message);
          $("#error_change .alert").append(
            "<br><br>Erro:<br>" + JSON.stringify(response.erros) + "<br>"
          );
        } else {
          $("#change")[0].reset();
          $("#error_change .alert").removeClass("alert-danger");
          $("#error_change .alert").addClass("alert-success");
          $("#error_change").show();
          $("#error_change .alert").html("Alterado com sucesso");
          $("#Modalchange").modal("hide");
          Core.initTable();
        }
      });
      Core.initTable();
      return false;
    });

    $.datepicker.regional["pt_BR"];
    $("#praca_1_data_fim").datepicker();
    $("#praca_1_data_inicio").datepicker();
    $("#maior_data_inicio").datepicker();
    $("#maior_data_fim").datepicker();

    $("#praca_1_data_fim").datepicker("option", "dateFormat", "dd/mm/yy");
    $("#praca_1_data_inicio").datepicker("option", "dateFormat", "dd/mm/yy");

    $("#maior_data_inicio").datepicker("option", "dateFormat", "dd/mm/yy");
    $("#maior_data_fim").datepicker("option", "dateFormat", "dd/mm/yy");

    $("#maior_data_inicio").datepicker().datepicker("setDate", new Date());

    $('select[multiple="multiple"]').select2({
      placeholder: "Selecione",
      language: "pt",
      width: "resolve",
      language: {
        noResults: function () {
          return "Não encontrado.";
        },
        searching: function () {
          return "Procurando...";
        },
      },
    });

    $("#send").submit(function () {
      if ($("#selectFundo1").val() == "" && $("#selectFundo2").val() == "") {
        alert("Informa para qual fundo deseja enviar.");
      } else {
        const offers = Core.table.bootstrapTable("getData", {
          includeHiddenRows: true,
          formatted: true,
        });
        var id = $("#id").val();
        const offerFound = offers.filter((offer) => offer.id_imovel == id);

        var sendForm = Pipefy.dicionario(offerFound[0], document.getElementById("pipe_selected").value );

        $("#data").val(JSON.stringify(sendForm));
        var form = $("#send").serialize();
        $.post("pipefy.php?action=sendCard", form).then(function (res) {
          var response = JSON.parse(res);

          if (response.erro == true) {
            $("#error").show();
            $("#error .alert").addClass("alert-danger");
            $("#error .alert").removeClass("alert-success");
            $("#error .alert").html(response.message);
            $("#error .alert").append(
              "<br><br>Erro:<br>" + JSON.stringify(response.erros) + "<br>"
            );
          } else {
            $("#error .alert").removeClass("alert-danger");
            $("#error .alert").addClass("alert-success");
            $("#error").show();
            $("#error .alert").html("Enviado com sucesso");
            Core.pageActive = Core.table.bootstrapTable(
              "getOptions"
            ).pageNumber;
            Core.initTable();
          }
        });
      }

      return false;
    });
    $("#table").on("click", ".enviar", function () {
      $("#logoutModal").modal("show");
      $("#error").hide();
      $("#id").val($(this).data("rel"));
      const offers = Core.table.bootstrapTable("getData", {
        includeHiddenRows: true,
        formatted: true,
      });
      var id = $("#id").val();

      const offerFound = offers.filter((offer) => offer.id_imovel == id)[0];

      $("#due_date").val(offerFound.ultima_data_leilao);
      $("#latitude").val(offerFound.latitude);
      $("#longitude").val(offerFound.longitude);
      $('#table-response-apartamentos-ofertas').html('')
      $('#table-response-imoveis-ofertas-estatisticas').html('')
      $(".loading-apartamentos-ofertas").hide();
      $(".loading-imoveis-ofertas-estatisticas").hide();
      $("#title").val(offerFound.endereco);
    });

    $("#table").on("click", ".editar", function () {
      $("#Modalchange").modal("show");
      $("#error_change").hide();
      $("#id_change").val($(this).data("rel"));
      const offers = Core.table.bootstrapTable("getData", {
        includeHiddenRows: true,
        formatted: true,
      });
      var id = $("#id_change").val();
      const offerFound = offers.filter((offer) => offer.id_imovel == id)[0];
    });

    $("#table").on("click", ".descarta", function () {
      
      if ( confirm("Deseja descartar este imóvel?")){
         Core.descarta($(this).data("rel"))
        // $("#error_change").hide();
        // $("#id_change").val($(this).data("rel"));
         Core.initTable();
        // var id = $("#id_change").val();
        // const offerFound = offers.filter((offer) => offer.id_imovel == id)[0];
      }
      
    });

    Core.loadMunicipio("SP");
    Core.loadFontes();
    $('#remove').click(function(){
      
      if(confirm('Deseja descartar o itens selecionados?')){
      var itens =[]
      $("input[type=checkbox][name=btSelectItem]").each(function(e,x){
     
        if($(x).prop('checked')){
          var index =  $(x).data('index');
          var line = $("#table").bootstrapTable('getData')[index]
          console.log(line);
          console.log(line.id_imovel);
          itens.push(line.id_imovel)
        }
       
      })
      var data = [];
        $.post("edit.php?action=descartar-lista&ids="+itens.join(','), data).then(function (res) {
          var response = JSON.parse(res);
          if(response.erro == false){
            alert(response.message)
            Core.initTable();
          } else {
            alert(response.message)
          }
        });
      }

    });
    // $('#toolbar').find('select').change(function () {
    //   $table.bootstrapTable('destroy').bootstrapTable({
    //     exportDataType: $(this).val(),
    //     exportDataType: 'all',
    //     exportTypes: ['csv', 'txt', 'excel'],
    //     // columns: [
    //     //   {
    //     //     field: 'state',
    //     //     checkbox: true,
    //     //     visible: $(this).val() === 'selected'
    //     //   },
    //     //   {
    //     //     field: 'id',
    //     //     title: 'ID'
    //     //   }, {
    //     //     field: 'name',
    //     //     title: 'Item Name'
    //     //   }, {
    //     //     field: 'price',
    //     //     title: 'Item Price'
    //     //   }
    //     // ]
    //   })
    // }).trigger('change')
  },
  descarta:function(id){
    var data = [];
    data['id_change']= id
    $.post("edit.php?action=descartar&id_change="+id, data).then(function (res) {
      var response = JSON.parse(res);

      if (response.erro == true) {
        $("#error_change").show();
        $("#error_change .alert").addClass("alert-danger");
        $("#error_change .alert").removeClass("alert-success");
        $("#error_change .alert").html(response.message);
        $("#error_change .alert").append(
          "<br><br>Erro:<br>" + JSON.stringify(response.erros) + "<br>"
        );
      } else {
        $("#change")[0].reset();
        $("#error_change .alert").removeClass("alert-danger");
        $("#error_change .alert").addClass("alert-success");
        $("#error_change").show();
        $("#error_change .alert").html("Alterado com sucesso");
        $("#Modalchange").modal("hide");
        Core.initTable();
      }
    });
  },
  loadMunicipio: function (estadoId) {
    $("#municipio").html("Carregando...");
    $.get("edit.php?action=municipios&estado=" + estadoId).then(function (res) {
      $("#municipio").html("");
      var response = JSON.parse(res);
      var selecionado = ''
      for (var i in response) {

        if(response[i].nome == 'Presidente Prudente' 
        || response[i].nome == 'Cotia'
        || response[i].nome == 'Guarulhos'
        || response[i].nome == 'Ribeirão Preto'
        || response[i].nome == 'São Paulo'
        || response[i].nome == 'São Bernardo do Campo'
        || response[i].nome == 'São Caetano do Sul'
        || response[i].nome == 'São José dos Campos'
        || response[i].nome == 'Campinas'

        ){
          selecionado = 'selected '
        }else{
          selecionado = ''
        }

        $("#municipio").append(
          '<option value="' +
            response[i].nome +
            '" '+selecionado+'>' +
            response[i].nome +
            "</option>"
        );
      }
    });
    $("#municipio").select2({
      placeholder: "Selecione",
      language: "pt",
      width: "resolve",
      language: {
        noResults: function () {
          return "Não encontrado.";
        },
        searching: function () {
          return "Procurando...";
        },
      },
    });
  },
  loadFontes: function () {
    $("#fonte").html("Carregando...");
    $.get("edit.php?action=fonte").then(function (res) {
      $("#fonte").html("");
      var response = JSON.parse(res);
      for (var i in response) {
        $("#fonte").append(
          '<option value="' +
            response[i].fonte +
            '">' +
            response[i].fonte +
            "</option>"
        );
      }
    });
    $("#fonte").select2({
      placeholder: "Selecione",
      language: "pt",
      width: "resolve",
      language: {
        noResults: function () {
          return "Não encontrado.";
        },
        searching: function () {
          return "Procurando...";
        },
      },
    });
  },
};

var Pipefy = {
  formPhase: {},
  init: function () {
    this.loadPhases();
  },
  loadPhases: function () {
    $.get("pipefy.php?action=phases").then(function (res) {
      res = JSON.parse(res);
      var phases = res.data.pipe.phases;
      Pipefy.formPhase = res.data.pipe.start_form_fields;
      var html = `<option value=''>--selecione--</option>`;
      for (var i in phases) {
        html += `<option value='${phases[i].id}'>${phases[i].name}</option>`;
      }
      $("#phases").html(html);
    });
  },
  dicionario: function (dados, fundo) {
    var fields = [];
    var dicField = "";
    for (var i in dados) {
      if(fundo == "301327004"){
        dicField = this.getIdField1(i);
      }else{
        dicField = this.getIdField2(i);
      }

      if (dicField) {
        if (typeof dicField.formatted != "undefined") {
          fields.push({
            field_id: dicField.id,
            field_value: dicField.formatted(dados[i]),
          });
        } else {
          fields.push({ field_id: dicField.id, field_value: dados[i] });
        }
      }
    }
    return fields;
  },
  getIdField1: function (field) {
    var dic = [
      { label: "ID Urbit", id: "id_urbit", fieldSend: "id_urbit" },
      { label: "ID Rooftop", id: "id_rooftop", fieldSend: "id_imovel" },
      { label: "URL do imóvel", id: "url_do_im_vel", fieldSend: "link_anunc" },
      {
        label: "Endereço completo",
        id: "full_address_w_zip",
        fieldSend: "endereco",
        formatted: limitString,
      },
      {
        label: "Nome do ativo, cidade",
        id: "site_address_submarket",
        fieldSend: "title",
        formatted: titleValue,
      },
      {
        label: "Numero do processo",
        id: "numero_do_processo",
        fieldSend: "numero_processo",
      },
      {
        label: "Descrição completa do imóvel",
        id: "descri_o_do_im_vel",
        fieldSend: "descricao",
      },
      { label: "Matrícula", id: "matr_cula_do_im_vel", fieldSend: "matricula" },
      {
        label: "Endereço completo",
        id: "full_address_w_zip",
        fieldSend: "endereco",
      },
      { label: "Cidade", id: "cidade", fieldSend: "cidade" },
      { label: "Estado", id: "estado", fieldSend: "uf" },
      {
        label: "Área útil privativa (Apto) | Área construída (Casa)",
        id: "rea_construida_em_metros_quadrados",
        fieldSend: "area",
      },
      {
        label: "Quartos/Unidades",
        id: "n_mero_de_quartos_unidades",
        fieldSend: "dormitorios",
      },
      { label: "Banheiros", id: "banheiros", fieldSend: "banheiros" },
      { label: "Suites", id: "suites", fieldSend: "suites" },
      { label: "Vagas", id: "garagem", fieldSend: "vagas" },
      {
        label: "Data primeira praça",
        id: "data_do_primeiro_lote",
        fieldSend: "praca_1_data",
        formatted: dataFormat,
      },
      {
        label: "Valor primeira praça",
        id: "valor_primeira_pra_a",
        fieldSend: "praca_1",
        formatted: currencyFormat,
      },
      {
        label: "Data segunda praça",
        id: "data_do_segundo_lote",
        fieldSend: "praca_2_data",
        formatted: dataFormat,
      },
      {
        label: "Valor segunda praça",
        id: "valor_segunda_pra_a",
        fieldSend: "praca_2",
        formatted: currencyFormat,
      },
      {
        label: "Data praça única",
        id: "data_pra_a_nica",
        fieldSend: "praca_unica_data",
        formatted: dataFormat,
      },
      {
        label: "Valor praça única",
        id: "valor_pra_a_nica",
        fieldSend: "praca_unica",
        formatted: currencyFormat,
      },
      {
        label: "Situação",
        id: "status_leilao",
        fieldSend: "situacao",
        formatted: situacaoFormat,
      },

      //{label: "Leiloeiro", id: "leiloeiro_1", fieldSend : "fonte"}
    ];
    return dic.filter((column) => {
      return column.fieldSend == field ? column.fieldSend : "";
    })[0];
  },
  getIdField2: function (field) {
    var dic = [
      { label: "ID Urbit", id: "id_urbit", fieldSend: "id_urbit" },
      { label: "ID Rooftop", id: "id_rooftop", fieldSend: "id_imovel" },
      { label: "URL do imóvel", id: "url_do_im_vel", fieldSend: "link_anunc" },
      {
        label: "Endereço completo",
        id: "endere_o_completo",
        fieldSend: "endereco",
        formatted: limitString,
      },
      {
        label: "Nome do ativo, cidade",
        id: "site_address_submarket",
        fieldSend: "title",
        formatted: titleValue,
      },
      {
        label: "Numero do processo",
        id: "numero_do_processo",
        fieldSend: "numero_processo",
      },
      {
        label: "Descrição completa do imóvel",
        id: "descri_o_completa_do_im_vel",
        fieldSend: "descricao",
      },
      { label: "Matrícula", id: "matr_cula", fieldSend: "matricula" },
      {
        label: "Endereço completo",
        id: "endere_o_completo",
        fieldSend: "endereco",
      },
      { label: "Cidade", id: "cidade", fieldSend: "cidade" },
      { label: "Estado", id: "estado", fieldSend: "uf" },
      {
        label: "Área útil privativa (Apto) | Área construída (Casa)",
        id: "rea_total_com_vaga_apto_rea_total_terreno_casa",
        fieldSend: "area",
      },
      {
        label: "Quartos/Unidades",
        id: "n_mero_de_quartos_unidades",
        fieldSend: "dormitorios",
      },
      { label: "Banheiros", id: "banheiros", fieldSend: "banheiros" },
      { label: "Suites", id: "suites", fieldSend: "suites" },
      { label: "Vagas", id: "vagas", fieldSend: "vagas" },
      {
        label: "Data primeira praça",
        id: "data_primeira_pra_a",
        fieldSend: "praca_1_data",
        formatted: dataFormat,
      },
      {
        label: "Valor primeira praça",
        id: "valor_primeira_pra_a",
        fieldSend: "praca_1",
        formatted: currencyFormat,
      },
      {
        label: "Data segunda praça",
        id: "data_segunda_pra_a",
        fieldSend: "praca_2_data",
        formatted: dataFormat,
      },
      {
        label: "Valor segunda praça",
        id: "valor_segunda_pra_a",
        fieldSend: "praca_2",
        formatted: currencyFormat,
      },
      {
        label: "Data praça única",
        id: "data_pra_a_nica",
        fieldSend: "praca_unica_data",
        formatted: dataFormat,
      },
      {
        label: "Valor praça única",
        id: "valor_pra_a_nica",
        fieldSend: "praca_unica",
        formatted: currencyFormat,
      },
      {
        label: "Situação",
        id: "status_leilao",
        fieldSend: "situacao",
        formatted: situacaoFormat,
      },

      //{label: "Leiloeiro", id: "leiloeiro_1", fieldSend : "fonte"}
    ];
    return dic.filter((column) => {
      return column.fieldSend == field ? column.fieldSend : "";
    })[0];
  },
};

// your custom ajax request here
function ajaxRequest(params) {
  var url = "load-imoveis.php";
  $.get(url + "?" + $.param(params.data)).then(function (res) {
    params.success(res);
  });
}

// $('#table').bootstrapTable({
//   url: "load-imoveis.php",
//   locale: 'pt-BR'} );
function imagens(valor, x) {
  if (valor == null || typeof valor == "object") {
  } else {
    if (valor.indexOf(",,,")) {
      if (valor.indexOf("[") == 0) {
        image = valor.replace(/\[|\]|\'/gi, "");
        image = image.split(",");
        return `<img src="${image[0]}" class='img-reponsive img-modal' width=100 >`;
      } else {
        return `<img src="${valor}" class='img-reponsive img-modal' width=100 >`;
      }
    }
  }
}

function linkAlterar(valor, x) {
  if (x.status_analise == "2") {
    return `-`;
  } else {
    return `<a href="javascript:void(0)"  class="editar btn btn-small" data-rel="${valor}" ><i class="fas fa-edit"></i></a>`;
  }
}

function linkDescartar(valor, x) {
  if (x.status_analise == "3") {
    return `-`;
  } else {
    return `<a href="javascript:void(0)"  class="descarta btn btn-small" data-rel="${valor}" ><i class="fas fa-trash"></i></a>`;
  }
}


function stateFormatter(value, row, index) {
  
    return {
      class: 'remove',
      value:value
    }
}



function linkPipefy(valor, x) {
  var field = this;
  if (x.status_analise == "1") {
    return `<a href="javascript:void(0)"  class="enviar btn btn-small" data-rel="${valor}" ><i class=" fas fa-share-alt"></i></a>`;
  } else if (x.status_analise == "2") {
    return `<a href="javascript:void(0)"  class="btn btn-small" data-rel="${valor}" title="${
      "Enviado em " + dataFormat(x.pipefy_data_envio) + " com id " + x.pipefy_id
    }" ><i class=" fas fa-paper-plane"></i></a>`;
  } else if (x.status_analise == "3") {
    return `<a href="javascript:void(0)"  class="btn btn-small" data-rel="${valor}" title="${
      "Descartado em " +
      dataFormat(x.status_analise_data) +
      " motivo " +
      x.motivo
    }" ><i class=" fas fa-trash-alt"></i></a>`;
  } else {
    return `<a href="javascript:void(0)"  class="enviar btn btn-small" data-rel="${valor}" ><i class=" fas fa-share-alt"></i></a>`;
  }
}

function linkDownload(valor) {
  if (valor != null) {
    return `<a href="${valor}" target="_blank" class="btn btn-small" data-rel="${valor}" ><i class="fas fa-download"></i></a>`;
  }
}
function linkSync(valor, x) {
  return `<a href="javascript:void(0)"  class="sync btn btn-small" data-rel="${valor}" ><i class="fas fa-sync"></i></a>`;
}

function currencyFormat(valor) {
  if (valor != null && valor != "" && typeof valor != "object") {
    valor = valor.replace(",", ".");
    return Number(valor).toLocaleString("pt-br", {
      style: "currency",
      currency: "BRL",
    });
  } else {
    return "0";
  }
}
function link(valor) {
  return `<a href="${valor}" target="_blank" data-rel="externo"><i class="fas fa-link"></i></a>`;
}



function situacaoFormat(valor) {
  switch (valor) {
    case "0":
      return "inativo";
      break;
    case "1":
      return "Aberto";
      break;
    case "3":
      return "Aguardando Abertura";
      break;
    case "4":
      return "Sustado/Cancelado";
      break;
    case "5":
      return "Sem Licitante";
      break;
    case "6":
      return "Encerrado";
      break;
    default:
      return "-";
  }
}

function detailFormatter(index, row) {
  var html = [];
  $.each(row, function (key, value) {
    html.push("<p><b>" + key + ":</b> " + value + "</p>");
  });
  return html.join("");
}
function titleValue(valor){
  return $('#title').val()
}
function limitString(valor){
  console.log(valor)
  if(valor != null){
    return valor.substring(0,254)
  }
}
function dataFormat(valor) {
  if (valor != null && typeof valor != "object") {
    data = valor.replace("00:00:00", "");
    var dia = data.split("-")[2];
    var mes = data.split("-")[1];
    var ano = data.split("-")[0];

    var dataFinal = dia.trim() + "/" + mes.trim() + "/" + ano.trim();

    return dataFinal;
  } else {
    return null;
  }
}
$(".close").click(function () {
  var modal = document.getElementById("myModal");
  modal.style.display = "none";
});

function GetSelectedItem(el) {
  // if (el.value == "Input") {
    document.getElementById("pipe_selected").value = el.value
  // } else if(el.value == "Output") {
    // document.getElementById("pipe_selected").value
  // }
}