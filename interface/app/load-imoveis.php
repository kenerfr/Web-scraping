<?php 

require_once('connection.php');

if (!$pdo) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}else{
 // echo 'conecatado';
}
//Cria um array ($tables) com todas as tabelas presentes no banco de dados
$concatenar = '';
$concatenar2 = '';
if(isset($_GET['status_analise']) && $_GET['status_analise'] !='' ){
    $concatenar .= " and status_analise in ('".implode("','",$_GET['status_analise'])."')";
}

if(isset($_GET['status_leilao']) && $_GET['status_leilao'] !='' ){
    $concatenarTemp = '';
    if(  in_array('NULL', $_GET['status_leilao'] )){
        $concatenarTemp =  ' or situacao is null ';
    }
    $concatenar .= " and (situacao  in ('".implode("','",$_GET['status_leilao'])."') ".$concatenarTemp.") ";
}

if(isset($_GET['maior_data_inicio']) && $_GET['maior_data_inicio'] !='' && isset($_GET['maior_data_fim']) && $_GET['maior_data_fim'] !='' ){
    $concatenar .= "and ultima_data_leilao  >=  '".convertData($_GET['maior_data_inicio'])."' and ultima_data_leilao  <= '".convertData($_GET['maior_data_fim'])."' ";
}


if(isset($_GET['maior_data_fim']) && $_GET['maior_data_fim'] !='' ){
    $concatenar .= "and  (ultima_data_leilao  <= '".convertData($_GET['maior_data_fim'])."' or ultima_data_leilao is null )  ";
}

if(isset($_GET['praca_1_data_inicio']) && $_GET['praca_1_data_inicio'] !='' && isset($_GET['praca_1_data_fim']) && $_GET['praca_1_data_fim'] !='' ){

    $concatenar .= " and praca_1_data >=  '".convertData( $_GET['praca_1_data_inicio'])."' and praca_1_data <= '".convertData($_GET['praca_1_data_fim'])."'";
}

if(isset($_GET['tipo']) && $_GET['tipo'] !='' ){
    $concatenar .= " and tipo in ('".implode("','",$_GET['tipo'])."')";
}

if(isset($_GET['tipo_leilao']) && $_GET['tipo_leilao'] !='' ){
    
    if($_GET['tipo_leilao'][0]=='outros'){
        $concatenar .= " and tipo_leilao  is null";
    }else{
        $concatenar .= " and tipo_leilao in ('".implode("','",$_GET['tipo_leilao'])."')";
    }
    
}

if(isset($_GET['busca']) && $_GET['busca'] !='' ){
     $palavras = explode(" ", $_GET['busca']); 
    foreach($palavras  as $p){
        $concatenar .= " and (endereco like '%".$p."%' or descricao like '%".$p."%' ) ";
    }
    
}


if(isset($_GET['exibir_sem_geometria']) && $_GET['exibir_sem_geometria'] !='' ){
    if($_GET['exibir_sem_geometria'] == '1'){
        $concatenar .= " and latitude is null ";
    }
}

if(isset($_GET['municipio']) && $_GET['municipio'] !='' ){
    $concatenar .= " and cidade in ('".implode("','",$_GET['municipio'])."')";
}


if(isset($_GET['fonte']) && $_GET['fonte'] !='' ){
    $concatenar .= " and fonte in ('".implode("','",$_GET['fonte'])."')";
}


function convertData($data) {
    $dia = substr($data,0,2);
    $mes = substr($data,3,2);
    $ano = substr($data,6,4);
    return  $ano.'-'.$mes.'-'.$dia;
}

if(isset($_GET['debug']) && $_GET['debug']=='true'){
echo "select * from (SELECT latitude,longitude, id_urbit,id_imovel,link_anunc,tipo,endereco,praca_1_data,
praca_1 ,praca_2_data,praca_2,
FORMAT((praca_2/praca_1)-1, 2) as diferenca,
praca_unica,  
case 	when praca_1_data is null and praca_2_data is null then praca_unica_data
       when praca_2_data is null then praca_1_data
       else praca_2_data 
   end        as ultima_data_leilao,
case when  imovel_ocupado = 1 then 'ocupado' else 'desocupado' end as imovel_ocupado  ,edital,matricula,tipo_leilao,fonte,
status_analise,
descricao,
numero_processo,
cidade ,uf,
pipefy_id,
pipefy_status,
pipefy_data_envio,
motivo,
status_analise_data,
situacao,
imagens,
'0' as valor_mercado,
lote_vendido,area,vagas
FROM imoveis_de_leilao   

) as tb
 where 1=1  and lote_vendido is null
".$concatenar."

order by id_urbit desc
";
// die();
}

$stmt = $pdo->query("select * from (SELECT id_urbit,id_imovel,link_anunc,tipo,endereco,praca_1_data,
 praca_1 ,praca_2_data,praca_2,
 FORMAT((praca_2/praca_1)-1, 2) as diferenca,
 praca_unica,  
 case 	when praca_1_data is null and praca_2_data is null then praca_unica_data
		when praca_2_data is null then praca_1_data
		else praca_2_data 
	end        as ultima_data_leilao,
 case when  imovel_ocupado = 1 then 'ocupado' else 'desocupado' end as imovel_ocupado  ,edital,matricula,tipo_leilao,fonte,
 status_analise,
 descricao,
numero_processo,
cidade ,uf,
pipefy_id,
pipefy_status,
pipefy_data_envio,
motivo,
status_analise_data,
situacao,
imagens,latitude,longitude,lote_vendido,area,vagas
 FROM imoveis_de_leilao   
 
) as tb
  where 1=1  and lote_vendido is null
 ".$concatenar."
 order by id_imovel desc 
 ");
$dados = [];
while ($row = $stmt->fetch()) {
    if(isset($_GET['debug']) && $_GET['debug']=='true'){
        echo '<pre>';
    print_r($row);
    }
    $dados[] =$row;
}

echo json_encode($dados);