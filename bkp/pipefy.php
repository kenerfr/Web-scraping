<?php


require_once('connection.php');

function setLog($obj,$tipo='SUCCESS'){
  if($tipo =='SUCCESS'){
      $export =  str_replace(PHP_EOL,' ', $obj);
      $fp = fopen('send_log_success.txt', 'a+');
      fwrite($fp,'SUCCESS: '. date('d-m-y H:i') .' - '. $export . PHP_EOL) ;
      fclose($fp);
  }else if($tipo =='ERRO'){
      $export =  str_replace(PHP_EOL,' ', $obj);
      $fp = fopen('send_log_erro.txt', 'a+');
      fwrite($fp,'ERRO: '. date('d-m-y H:i') .' - '. $export . PHP_EOL) ;
      fclose($fp);
  }else{
      $export =  str_replace(PHP_EOL,' ', $obj);
      $fp = fopen('send_log_data.txt', 'a+');
      fwrite($fp,'DATA: '. date('d-m-y H:i') .' - '. $export . PHP_EOL) ;
      fclose($fp);
  }
}

// Composer autoloading
// if (file_exists('../vendor/autoload.php')) {
//   $loader = include '../vendor/autoload.php';
// }


if ($_GET['action']  == 'phases') {
  // $query =  'query phase($pipeId: ID!) {
  //   pipe(id: $pipeId) {
  //     phases {
  //       id
  //       name
  //     }
  //   }
  // }
  // ';
  $query = ' {
    pipe(id: 301327004) {
      id
      name
      start_form_fields {
        label
        id
      }
      labels {
        name
        id
      }
      phases {
        id
        name
        fields {
          label
          id
        }
        cards(first: 5) {
          edges {
            node {
              id
              title
            }
          }
        }
      }
    }
  }';
  $dados = post($query);
  echo json_encode($dados);

}else if ($_GET['action']  == 'phasesRooftop') {

  $query = ' {
    pipe(id: 301327004) {
      id
      name
      start_form_fields {
        label
        id
      }
      labels {
        name
        id
      }
      phases {
        id
        name
        fields {
          label
          id
        }
        cards(first: 5) {
          edges {
            node {
              id
              title
            }
          }
        }
      }
    }
  }';
  $dados = post($query);
  echo json_encode($dados);

}else if ($_POST['action']  == 'sendCard') {

  if($_POST['phases'] !=''){
    // $postFields = $_POST['data'];
    $postFields = str_replace('"field_id"',"field_id",$_POST['data']);
    $postFields = str_replace('"field_value"',"field_value",$postFields);
    $duedate ='';
    if(isset($_POST['due_date']) && $_POST['due_date'] !=''){
      $duedate = 'due_date: "'.str_replace(' 00:00:00','',$_POST['due_date'].'",');
    }
    $query =  'mutation {
    createCard(input: {clientMutationId: "1", pipe_id: 301327004, phase_id:'.$_POST['phases'].', 
       '.$duedate.'
       title: "'.$_POST['title'].'", 
       fields_attributes: 
      '.$postFields.'
    }) {
      card {
        id
      }
    }
  }';
  
    $dados = post($query);

    if(isset($dados) && $dados['erro'] == false ){
      $stmt = $pdo->query("update imoveis_de_leilao set   
          pipefy_id ='".$dados['data']['createCard']['card']['id']."',
          pipefy_status ='".$_POST['phases']."',
          pipefy_data_envio  ='".date('yy-m-d')."',
          status_analise = 2
          where id_imovel = '".$_POST['id']."' ");
    }
  }
  $erro = var_export($dados,true);
  setLog('LOG ENVIO - sendCard : ' .$erro ,'CALLBACK');
  echo json_encode($dados);
}


function post($query){

  set_time_limit(0);
  //$authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VyIjp7ImlkIjozMDExMTcxMTMsImVtYWlsIjoicmFmYWVsLmJ1dHRAaG90bWFpbC5jb20iLCJhcHBsaWNhdGlvbiI6MzAwMDgwNTYwfX0.nXe0GVgHabACgG0Bvu3-TjcBS0FKY-ymIuJP5gHhaLVPCBz4qXRR4kcwvETSQIZVAPkrVOlDNNMifE8XZ-b7yg";
  //$authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VyIjp7ImlkIjozMDExMTcxMTMsImVtYWlsIjoicmFmYWVsLmJ1dHRAaG90bWFpbC5jb20iLCJhcHBsaWNhdGlvbiI6MzAwMDgwNTYwfX0.nXe0GVgHabACgG0Bvu3-TjcBS0FKY-ymIuJP5gHhaLVPCBz4qXRR4kcwvETSQIZVAPkrVOlDNNMifE8XZ-b7yg";
  $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VyIjp7ImlkIjozMDEwMDcwODUsImVtYWlsIjoiZWR1YXJkby5hbG1laWRhQHJvb2Z0b3AuY29tLmJyIiwiYXBwbGljYXRpb24iOjMwMDA3OTAwOH19.Mrscv3nzs6JRhw5K6LJ9XEMUpm37_ix6FE6Ja7sB7lfZEjOPPdmf4gFk_bswpBYuKXEhEV-xZ4Hf-PSMpdW3Bg";
  $url ='https://api.pipefy.com/graphql';
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
          'Content-Type: application/json',
          $authorization
      )
  );
  
  $array['query'] = $query;
  $array['variables'] = array('filter'=>null,'pipeId'=>301478383);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($array));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLINFO_HEADER_OUT, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 55);
  $data = curl_exec($ch);
  
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }
  
  $information = curl_getinfo($ch);
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  $header = substr($data, 0, $header_size);
  $body = substr($data, $header_size);
  
  if (is_string($data)) {
      $res_str = $data;
      if (str_split($res_str)[0] == '{' || str_split($res_str)[0] == '[') {
          $response =  json_decode($res_str, true);
  
          if ($httpcode == '200') {
              
              
              if(isset($response['errors']) &&  sizeof($response['errors']) > 0 ) {
                $output['erro'] = true;
                $output['erros'] = $response['errors'];
                $output['message'] = 'Erro ao enviar os dados para o Pipefy' ;
                return $output;
              }else{
                $output['erro'] =false;
                $output['data'] = $response['data'];
                return $output;
              }
          } else {
            return $output = array('erro' => true, "msg" => $response['message']);
          }
      } else {
        return $output = array('erro' => true, "msg" => 'Erro Api (1)');
      }
  } else {
    return $output = array('erro' => true, "msg" => 'Erro Api (2)');
  }
}

// $variable = new \GraphQL\Variable('name', 'String', 'Pikachu');
// $fragment = new \GraphQL\Fragment('pokemonFields', 'Pokemon');
// $fragment->use('number', 'name');

// $pokemon = new \GraphQL\Graph('pokemon', ['name' => $variable]);
// $pokemon->use('id', $fragment)
//     ->attacks
//     ->special(['name' => $variable])
//     ->use('name', 'type', 'damage');

// echo $pokemon;


// $hero = new GraphQL\Graph('hero');
// echo $hero->use('name')
//     ->friends
//         ->use('name')
//     ->root()
//         ->query();




// require_once('../vendor/pipefy-api/Pipefy.php');

// Pipefy::init("", "rafael.butt@hotmail.com");

// // Create Pipe definition; 
// $pipe = new Pipe();

// // Fetch data from Pipefy for the specified pipe ID
// $pipe->fetch(301478383);
// echo '<pre>';
// print_r($pipe);
// // Find phase in pipe
// $phase = $pipe->get_phase_by_name("Pendente");
// print_r($phase);
// // Fetch data for Phase. It's not necessary, but it will provide more detailed info about the phase and its cards.
// $phase->fetch();

// This code can be shorten like this
// $phase = (new Pipe())->fetch(1234)->get_phase_by_name("Phase name")->fetch();

// foreach ($phase->cards as $key => $card) {
//   echo ($card->title);
// }