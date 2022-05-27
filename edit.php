<?php 
 
require_once('connection.php');

if (!$pdo) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}else{
  //echo 'conecatado';
}

if(isset($_POST['action']) &&  $_POST['action']=='change'){
    
    $stmt = $pdo->query("update imoveis_de_leilao set   
    status_analise ='".$_POST['change-status']."',
    status_analise_data  ='".date('yy-m-d')."',
    motivo = '".$_POST['motivo']."'
    where id_imovel = '".$_POST['id_change']."' ");
    if (!$stmt) {
        $dados['erro']=true;
        $dados['message']=$dbh->errorInfo();
    }else{
        $dados['erro']=false;
        $dados['message']='Alterado com sucesso!';
    }
    echo json_encode($dados);
}else if(isset($_GET['action']) &&  $_GET['action']=='descartar'){

    $stmt = $pdo->query("update imoveis_de_leilao set   
    status_analise ='3',
    status_analise_data  ='".date('yy-m-d')."',
    motivo = 'Descarte direto'
    where id_imovel = '".$_GET['id_change']."' ");
    if (!$stmt) {
        $dados['erro']=true;
        $dados['message']=$dbh->errorInfo();
    }else{
        $dados['erro']=false;
        $dados['message']='Alterado com sucesso!';
    }
    echo json_encode($dados);

}else if(isset($_GET['action']) &&  $_GET['action']=='descartar-lista'){

    $stmt = $pdo->query("update imoveis_de_leilao set   
    status_analise ='3',
    status_analise_data  ='".date('yy-m-d')."',
    motivo = 'Descarte em lote'
    where id_imovel in (".$_GET['ids'].") ");
    if (!$stmt) {
        $dados['erro']=true;
        $dados['message']=$dbh->errorInfo();
    }else{
        $dados['erro']=false;
        $dados['message']='Alterado com sucesso!';
    }
    echo json_encode($dados);

}else if(isset($_GET['action']) &&  $_GET['action']=='municipios'){

    $stmt = $pdo->query("select * from municipios where uf= '".$_GET['estado']."'");
    $row = $stmt->fetchAll();
    echo json_encode($row);
}else if(isset($_GET['action']) &&  $_GET['action']=='fonte'){

    $stmt = $pdo->query("select fonte from imoveis_de_leilao.imoveis_de_leilao group by fonte");
    $row = $stmt->fetchAll();
    echo json_encode($row);
}else if(isset($_POST['action']) &&  $_POST['action']=='sync'){
    $api = new API();
    $credentials = ['username'    => 'daniel.gava@rooftop.com.br', 'password' => 'zztsijlj'];
    $auth = $api->postApi('/authenticate', json_encode($credentials));
    if(!$auth['erro']){
        $api->setToken($auth['response_body']['token']);
        $api->setDebug(false);
        $lat = $_POST['latitude']; 
        $lng = $_POST['longitude']; 
        $servico =$_POST['servico'];
        $distancia =$_POST['distancia'];
        $imoveisOfertas= $api->getApi('/municipio/sao-paulo/'.$servico.'/' . $lng . '/' . $lat.'?distance='.$distancia.'');
        echo json_encode($imoveisOfertas);
    }
}


class API {
    private $token;
    private $hostName;
    private $debug = false;
    public function __construct()
    {
        $this->setHostName('https://api.urbit.com.br');
    }
    public function setToken($token)
    {
        return $this->token = $token;
    }
    public function getToken()
    {
        return $this->token;
    }
    public function setHostName($hostName)
    {
        return $this->hostName= $hostName;
    }
    public function getHostName()
    {
        return $this->hostName;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
    public function postApi($url, $dados = '')
    {
        $this->debug($this->getHostName() . $url);
        $res = $this->postCurl($this->getHostName() . $url, $dados);
        return $res;
    }

    function getApi($url, $params = '', $method = "GET"){
        
        $url =  $this->getHostName() . $url;
        $this->debug($url);
        $authorization = "Authorization: Bearer " . $this->getToken();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 55);
        curl_setopt($ch, CURLOPT_TIMEOUT, 55);
        $data = curl_exec($ch);
        // $information = curl_getinfo($ch);
        // var_dump($information);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode == '200') {
            if ($data !== null) {
                $data  = json_decode($data, true);
            } else {
                $data = null;
            }

            return $data;
        }
    }
    
 
    function debug($url){

        if ($this->debug) {
            echo $url;
            echo '<br/>';
            echo $this->getToken();
        }
    }

    function postCurl($url, $dados)
    {   
        set_time_limit(0);
        $authorization = "Authorization: Bearer " . $this->getToken();
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
        // var_dump($dados);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 55);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 55);
        $data = curl_exec($ch);
        $information = curl_getinfo($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // var_dump($information);
        // var_dump($data);
        // die();
        if (is_string($data)) {
            $res_str = $data;
            if (str_split($res_str)[0] == '{' || str_split($res_str)[0] == '[') {
                $response =  json_decode($res_str, true);

                if ($httpcode == '200') {
                    $output = array('erro' => false, "response_body" => $response);
                } else {
                    $output = array('erro' => true, "msg" => $response['message']);
                }
            } else {
                $output = array('erro' => true, "msg" => 'Erro Api (1)');
            }
        } else {
            $output = array('erro' => true, "msg" => 'Erro Api (2)');
        }

        return $output;

        // var_dump($data);

        // // if ($httpcode == '200') {
        //     return $data;
        // // }
    }
}