<?php

require_once('connection.php');

$request_body = file_get_contents('php://input');
$data = json_decode($request_body);
$export = $request_body;
setLog($export,'DATA');


if($_GET['action']=='cardmove'){
    if($data->data->action  == 'card.move'){
        $de = $data->data->from->id ;
        $para = $data->data->to->id;
        $movidoPor = $data->data->moved_by->name;
        $card = $data->data->card->id;
        $stmt = $pdo->query("select * from imoveis_de_leilao where pipefy_id = '".$data->data->card->id."' ");
        $row = $stmt->fetch();
        if($row) {
            if($row) {
                $stmt = $pdo->query("insert into historico_cardmove (move_from, move_from_name, move_to, move_to_name, usuario, id_imovel, pipefy_id, move_data)
                                 values('".$data->data->from->id."', 
                                        '".$data->data->from->name."',
                                        '".$data->data->to->id."',
                                        '".$data->data->to->name."',
                                        '".$data->data->moved_by->name."',
                                        '".$row->id_imovel."' ,
                                        '".$data->data->card->id."' ,
                                        '".date('d-m-y H:i')."')");
                if($stmt){
                    $concatenar = " pipefy_status = '".$data->data->to->id."' ";
                    $stmt = $pdo->query("update imoveis_de_leilao set ".$concatenar ." where id_imovel = '".$row->id_imovel."' ");
                    if( $stmt){
                        setLog($export,'SUCCESS');
                        echo '{"erro":false}';
                    }else{
                        $erro = var_export($pdo->errorInfo(),true);
                        setLog('ERRO AO ATUALIZAR: ' .$erro .' --'. $export,'ERRO');
                        echo '{"erro":true}';
                    }
                }else{
                    $erro = var_export($pdo->errorInfo(),true);
                    setLog('ERRO AO INSERIR: ' . $erro .' --'. $export,'ERRO');
                    echo '{"erro":true}';
                }

            }else{
                $erro = var_export($pdo->errorInfo(),true);
                setLog('PIPEFY ID NÃO ENCONTRADO: ' . $erro .' --'. $export,'ERRO');   
                echo '{"erro":true}';
            } 
        }else{
            $erro = var_export($pdo->errorInfo(),true);
            setLog('PIPEFY ID NÃO ENCONTRADO: ' . $erro .' --'. $export,'ERRO');   
            echo '{"erro":true}';
        } 
    }

}else{

    //Arrematado
    $dicionario['valor_pelo_que_foi_arrematado'] ='valor_pelo_que_foi_arrematado'; // valor arrematado

    //Perdido por preço
    $dicionario['currency_t_tulo'] ='currency_t_tulo';//Valor arrematado
    $dicionario['motivo_da_perda'] ='motivo_da_perda';//Motivo perda

    //Oferta para o comitê
    $dicionario['valor_teto_de_lance_definido_pelo_comit'] ='valor_teto_de_lance_definido_pelo_comit';//Valor teto de lance definido pelo comitê
    $dicionario['observa_es_do_comit'] ='observa_es_do_comit';//Valor teto de lance definido pelo comitê


    //Análise do processo judicial
    $dicionario['aprovado_pelo_jur_dico'] ='aprovado_pelo_jur_dico';//Aprovado pelo jurídico?


    //Precificação
    $dicionario['precifica_o_final_do_im_vel'] ='precifica_o_final_do_im_vel';//Precificação final do imóvel

    $dicionario['avalia_o_do_im_vel_pelo_agente'] ='avalia_o_do_im_vel_pelo_agente';//Enviar para precificação do Agente local

    $dicionario['valor_do_m2_do_terreno'] ='valor_do_m2_do_terreno';//Valor do m2 do terreno
    $dicionario['valor_do_m2_de_constru_o'] ='valor_do_m2_de_constru_o';//Valor do m2 de construção
    $dicionario['avalia_o_1_valor_do_im_vel'] = 'avalia_o_1_valor_do_im_vel';//Avaliação sistema 1 - valor do imóvel
    $dicionario['avalia_o_2_valor_m2'] =  'avalia_o_2_valor_m2';//Avaliação sistema 2 - valor m2
    $dicionario['avalia_o_3_valor_do_m2'] = 'avalia_o_3_valor_do_m2';//Avaliação 3 - valor do m2
    $dicionario['cap_rate_obrigat_rio_para_comercial'] = 'cap_rate_obrigat_rio_para_comercial'; //Cap rate % (obrigatório para comercial)
    #$dicionario['avalia_o_do_im_vel_pelo_agente'] ='avalia_o_do_im_vel_pelo_agente'; //Avaliação do imóvel pelo Agente

    $arrayIntesUpdate = array_keys($dicionario);


    if($data->data->action  == 'card.field_update'){
            
        if(isset($dicionario[$data->data->field->id]) ){
            $nomeCampo = $dicionario[$data->data->field->id];
            $newValue  = $data->data->new_value;

            if( in_array($data->data->field->id,$arrayIntesUpdate)  ){
                $stmt = $pdo->query("select * from imoveis_de_leilao where pipefy_id = '".$data->data->card->id."' ");
                $row = $stmt->fetch();
                if($row) {
                    $valueOld = $row->$nomeCampo;
                    $stmt = $pdo->query("insert into historico_field_update (id_imovel, field,value_old,new_value,data) values('".$row->id_imovel."', '".$nomeCampo."','".$valueOld."', '".$newValue."', '".date('d-m-y H:i')."')");
                    if($stmt){
                        $concatenar = " ". $nomeCampo." = '".$newValue."' ";
                        $stmt = $pdo->query("update imoveis_de_leilao set ".$concatenar ." where id_imovel = '".$row->id_imovel."' ");
                        if( $stmt){
                            setLog($export,'SUCCESS');
                            return '{"erro":false}';
                        }else{
                            $erro = var_export($pdo->errorInfo(),true);
                            setLog('ERRO AO ATUALIZAR: ' .$erro .' --'. $export,'ERRO');
                            echo '{"erro":true}';
                        }
                    }else{
                        $erro = var_export($pdo->errorInfo(),true);
                        setLog('ERRO AO INSERIR: ' . $erro .' --'. $export,'ERRO');
                        echo '{"erro":true}';
                    }

                }else{
                    $erro = var_export($pdo->errorInfo(),true);
                    setLog('PIPEFY ID NÃO ENCONTRADO: ' . $erro .' --'. $export,'ERRO');   
                    echo '{"erro":true}';
                } 
            } 
        } 
    }
}


function setLog($obj,$tipo='SUCCESS'){
    if($tipo =='SUCCESS'){
        $export =  str_replace(PHP_EOL,' ', $obj);
        $fp = fopen('log_success.txt', 'a+');
        fwrite($fp,'SUCCESS: '. date('d-m-y H:i') .' - '. $export . PHP_EOL) ;
        fclose($fp);
    }else if($tipo =='ERRO'){
        $export =  str_replace(PHP_EOL,' ', $obj);
        $fp = fopen('log_erro.txt', 'a+');
        fwrite($fp,'ERRO: '. date('d-m-y H:i') .' - '. $export . PHP_EOL) ;
        fclose($fp);
    }else{
        $export =  str_replace(PHP_EOL,' ', $obj);
        $fp = fopen('log_data.txt', 'a+');
        fwrite($fp,'DATA: '. date('d-m-y H:i') .' - '. $export . PHP_EOL) ;
        fclose($fp);
    }
}