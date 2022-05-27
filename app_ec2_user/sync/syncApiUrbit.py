import re
import sys
import os
import requests
import json
#from sqlalchemy import create_engine
import pymysql
import time
#import psycopg2
import datetime 
from datetime import date
import smtplib, ssl
#conn_string = "host='177.85.162.153' dbname='roofttop' user='postgres' password='f1Fehzzt'"
#conn = psycopg2.connect(conn_string)

#conn_stringMysql = "host='127.0.0.1:3306' dbname='imoveis_de_leilao' user='urbit' password='Urbit#1.$'"

# conn = pymysql.connect(host='localhost',
#                              port =3306,
#                              user='urbit',
#                              password='Urbit#1.$',
#                              db='imoveis_de_leilao',
#                              charset='utf8mb4',
#                              cursorclass=pymysql.cursors.DictCursor)


conn = pymysql.connect(host='rooftopmariadb.c0mrcyyivthm.us-east-1.rds.amazonaws.com',
                        port = 3306,
                        user='admin',
                        password='K4t1Oilm',
                        db='imoveis_de_leilao',
                        charset='utf8mb4',
                        cursorclass=pymysql.cursors.DictCursor)

#connMysql  = pymysql.connect()
#engine = create_engine('postgresql://postgres:f1Fehzzt@177.85.162.153:5432/roofttop')

# with connection.cursor() as cursor:
#     # Read a single record
#     sql = "SELECT * FROM `imoveis_de_leilao` limit 10"
#     cursor.execute(sql)
#     result = cursor.fetchone()
#     print(result)


auth = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI1ZDBhODJmODYwODVjMjA5Zjg1NTVjNjIiLCJyb2xlIjpbIlVzZXIiLCJBZG1pbiJdLCJpYXQiOjE1NjYzODcwODh9.kV7Wk2baOTHsAGTUZjBrmjoQ-zkYgbnTHIqqqoLdBOc"


def get_elements(legth,start):
    domain = ['api.urbit.com.br'][0]
    queue = "https://%s/lista-imoveis-leilao-app-rooftop?length=%s&start=%s" % (domain,legth,start)
    response = requests.post(queue, 
                            headers={'Authorization': auth})
    print(response)
    out = json.loads(response.content)
    return out

def get_total():
    domain = ['api.urbit.com.br'][0]
    queue = "https://%s/lista-imoveis-leilao-app-total" % (domain)
    response = requests.post(queue, 
                            headers={'Authorization': auth})
    out = json.loads(response.content)
    return out


def query(SQL):
    with conn.cursor() as cursor:
        try:
            sql_exec = cursor.execute(SQL)  
            if sql_exec:
                return cursor.fetchall()
            else:
                return 0
        except (pymysql.Error, pymysql.Warning) as e:
            print(f'error! {e}')
        
        #finally:
            #conn.close()

def insertElement(elements):

    #print(elements['itens'])

    elColumnsInsert = []
    elColumnsUpdate = []
    elUpdateStatus = []
    for el in elements['itens']:
        
        data_acess = datetime.datetime.strptime(el["data_acess"], "%d/%m/%Y")
        # with connection.cursor() as cursor:
#     # Read a single record

#if(result )
        el['data_acess'] = data_acess.strftime("%Y-%m-%d")
        if el['data_remoc'] == '' or el['data_remoc'] == None:
            el['data_remoc'] = None
        else:
            data_remoc = datetime.datetime.strptime(el["data_remoc"], "%d/%m/%Y")
            el['data_remoc'] = data_acess.strftime("%Y-%m-%d")

        if el['imovel_ocupado']=='ocupado' :
            el['imovel_ocupado'] = 1
        else:
            el['imovel_ocupado'] = 0
        #print("SELECT * FROM `imoveis_de_leilao` where link_anunc = '" + el['link_anunc']+"' " )
        result = query("SELECT * FROM `imoveis_de_leilao` where link_anunc = '" + el['link_anunc']+"' " )
        if( result == 0):
            
            #elColumnsInsert.append((el['id_imovel'],el["data_acess"],el["tipo"],el["finalidade"],el["valor"],el["iptu"],el["condominio"],el["banheiros"],el["suites"],el["quartos"],el["vagas"],el["area"],el["endereco"],el["municipio"],el["link_anunc"],el["anunciante"],el["status"],el["data_remoc"],el["fonte"],el["praca_1"],el["praca_1_data"],el["praca_2"],el["praca_2_data"],el["imovel_ocupado"],el["latitude"],el["longitude"],el["imovel_verificado"],el["lote_vendido"],el["valor_ultimo_lance"],el["tipo_leilao"],el["situacao_leilao"],el["descricao"], el["caracteristicas"], el["praca_unica"], el["praca_unica_data"],el['situacao'],el['numero_processo'], el['edital'], el['matricula'], el['imagens']))
            print('-->')
            print(el["valor"])
            print('<--')
            cursor = conn.cursor()
            sql ="""INSERT INTO imoveis_de_leilao (id_urbit,data_acess,
            tipo,
            finalidade,
            valor,
            iptu,
            condominio,
            banheiros,
            suites,
            quartos,
            vagas,
            area,
            endereco,
            cidade,
            link_anunc,
            anunciante,
            status,
            data_remoc,
            fonte,
            praca_1,
            praca_1_data,
            praca_2,
            praca_2_data,
            imovel_ocupado,
            latitude,
            longitude,
            imovel_verificado,
            lote_vendido,
            valor_ultimo_lance,
            tipo_leilao,
            situacao_leilao,
            descricao,
            caracteristicas,
            praca_unica,
            praca_unica_data,
            situacao,
            numero_processo,
            edital,
            matricula,
            imagens
            ) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"""

            values = (el['id_imovel'],el["data_acess"],el["tipo"],el["finalidade"],
              (el["valor"] is None and 0 or el["valor"]),el["iptu"],el["condominio"],el["banheiros"],el["suites"],el["quartos"],el["vagas"],
              (el["area"] is None and 0 or el["area"]) ,
              el["endereco"],
              el["municipio"],
              el["link_anunc"],
              el["anunciante"],
              el["status"],
              el["data_remoc"],
              el["fonte"],
              el["praca_1"],
              el["praca_1_data"],
              el["praca_2"],el["praca_2_data"],el["imovel_ocupado"],el["latitude"],el["longitude"],el["imovel_verificado"],el["lote_vendido"],el["valor_ultimo_lance"],el["tipo_leilao"],el["situacao_leilao"],el["descricao"], el["caracteristicas"], el["praca_unica"], el["praca_unica_data"],el['situacao'],el['numero_processo'], el['edital'], el['matricula'], el['imagens'] )
            # print(values)
            result = cursor.execute(sql,values)
            conn.commit()
            cursor.close()
            cursor = conn.cursor()   
        else:
            # print('Update')
            #elUpdateStatus.append((el,result))
            # elColumnsUpdate.append((el["tipo"],el["valor"],el["endereco"],el["municipio"],el["link_anunc"],el["status"],el["data_remoc"],el["fonte"],el["praca_1"],el["praca_1_data"],el["praca_2"],el["praca_2_data"],el["imovel_ocupado"],el["latitude"],el["longitude"],el["imovel_verificado"],el["lote_vendido"],el["valor_ultimo_lance"],el["tipo_leilao"],el["situacao_leilao"],el["descricao"], el["caracteristicas"], el["praca_unica"], el["praca_unica_data"],el['situacao'],el['numero_processo'], el['edital'], el['matricula'], el['imagens'],el['id_imovel']))
            # if result[0]['pipefy_id'] is not None:
            #     print(el)
            updateStatusRetorno = updateStatusPipefy(result, el['situacao'],el['id_imovel'])
            writeLog(updateStatusRetorno)
        
    print('Inserts:')    
    print(len(elColumnsInsert))
    
    print('Updates:')    
    print(len(elColumnsUpdate))

   
       

    if(len(elColumnsUpdate) > 0 ):
        #print(elColumnsUpdate[0])
        cursor = conn.cursor()
        result = cursor.executemany("""update imoveis_de_leilao set tipo = %s, valor = %s, endereco = %s, cidade = %s , link_anunc = %s, status = %s, data_remoc = %s, fonte = %s, praca_1 = %s, praca_1_data = %s, praca_2 = %s, praca_2_data = %s, imovel_ocupado = %s, latitude = %s , longitude = %s , imovel_verificado = %s, lote_vendido = %s, valor_ultimo_lance = %s , tipo_leilao = %s , situacao_leilao = %s, descricao= %s, caracteristicas= %s, praca_unica= %s, praca_unica_data = %s, situacao=%s, numero_processo = %s , edital =  %s, matricula = %s, imagens=%s where id_urbit = %s """,  elColumnsUpdate)
        conn.commit()
        cursor.close()
        cursor = conn.cursor()



def writeLog(log):
    if log is not None:
        # Append-adds at last 
        print(log)
        print('-------------')
        file1 = open("/home/ubuntu/app/sync/log.txt","a")#append mode 
        file1.write( log + " \n") 
        file1.close() 


def updateStatusPipefy(result, statusNovo, idimovel2 ):
    #template = 'mutation {moveCardToPhase(input:{clientMutationId: "1", card_id:2, destination_phase_id: 3){card {id}}}'
    #result = query("SELECT * FROM `imoveis_de_leilao` where pipefy_id is not null and id_urbit ="+str(idimovel2 ))
    if result[0]['pipefy_id'] is not None:
 
        if str(result[0]['situacao']) != str(statusNovo) :
            if statusNovo == 4 : 
                #>Cancelado/Sustado  
                queryMutation = '''mutation {{moveCardToPhase(input:{{clientMutationId: "{idimovel}", card_id:{pipefy_id}, destination_phase_id:  {destination_phase_id}}}) {{card {{id}}}}}}'''
                variables = {
                    'idimovel' : result[0]['id_imovel'],
                    'pipefy_id' : result[0]['pipefy_id'],
                    'destination_phase_id' :309663051
                }
                return  postPipefy(queryMutation.format(**variables),statusNovo, result[0],309663051)
            if statusNovo == 5 :
                queryMutation = '''mutation {{moveCardToPhase(input:{{clientMutationId: "{idimovel}", card_id:{pipefy_id}, destination_phase_id: {destination_phase_id}}}) {{card {{id}}}}}}'''
                variables = {
                    'idimovel' : result[0]['id_imovel'],
                    'pipefy_id' : result[0]['pipefy_id'],
                    'destination_phase_id' :308850582
                }
                return postPipefy(queryMutation.format(**variables),statusNovo, result[0],308850582)
        else:
            d = date.today()
            return 'Data: '+ str(d) + '- Sem atualização no id_imovel:'+ str(result[0]['id_imovel'])
    
def postPipefy(dataInput, statusNovo, resultImovel, phase):
    dataPost = {}
    dataPost['query']= dataInput
    response = requests.post("https://api.pipefy.com/graphql",data = json.dumps(dataPost), headers={'Content-type': 'application/json', 'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VyIjp7ImlkIjozMDEwMDcwODUsImVtYWlsIjoiZWR1YXJkby5hbG1laWRhQHJvb2Z0b3AuY29tLmJyIiwiYXBwbGljYXRpb24iOjMwMDA3OTAwOH19.Mrscv3nzs6JRhw5K6LJ9XEMUpm37_ix6FE6Ja7sB7lfZEjOPPdmf4gFk_bswpBYuKXEhEV-xZ4Hf-PSMpdW3Bg'})
    out = json.loads(response.content.decode('utf-8'))
    #print(out['data']["moveCardToPhase"]);
    if out['data']["moveCardToPhase"] is None :
       d = date.today()
       return 'Data: '+ str(d) + ' - Id Imóvel:' + str(resultImovel['id_imovel']) + ' - ERRO: ' +out["errors"][0]['message'] + '- De: '+ str(resultImovel['situacao']) + ' Para:' + str(statusNovo)+';'
    else:
        
        d = date.today()
        cursor = conn.cursor()
        result = cursor.execute("""insert into historico_situacao (id_imovel,situacao_antiga,situacao_nova,phase_destination,data_alteracao )values(%s,%s,%s,%s,%s)""",  (resultImovel['id_imovel'], resultImovel['situacao'],statusNovo, phase, d ))
        conn.commit()
        cursor.close()
        cursor = conn.cursor()
        d = date.today()
        out =  'Data: '+ str(d) + '- Id imóvel: '+str(resultImovel['id_imovel'])+'  -  Cartão movido: '+  str(out['data']["moveCardToPhase"]["card"]['id']) + ' Status Origem: ' + str(resultImovel['situacao']) +' Status Destino: ' + str(statusNovo) +''
    return out

def sendEmail():
    port = 465  # For SSL
    smtp_server = "email-smtp.us-west-2.amazonaws.com"
    sender_email = "contato@urbit.com.br"  # Enter your address
    receiver_email = "rafael.butt@hotmail.com"  # Enter receiver address
    password ='AuKplSghaS8/asewloR+gUKQ2qZ8ubOnMMVomepYu2w8'
    username='AKIAIVDPQCYKYFDWCLJA'
    message = """\
    Subject: Hi there

    This message is sent from Python."""

    context = ssl.create_default_context()
    with smtplib.SMTP_SSL(smtp_server, port, context=context) as server:
        server.login(sender_email, password)
        server.sendmail(sender_email, receiver_email, message)





#sendEmail()

elements  = get_total()
total = elements['total']
paginacao = int(total)/100


print('Total')
print(total)
print('Paginação')
print(round(paginacao))

count=0

pageSize = 500
while(count <= (paginacao+1)):
    print('Pagina:')
    print(count)
    print('length:')
    print(count * pageSize)
    elements  = get_elements(pageSize,count * pageSize)
    insertElement(elements)
    time.sleep(4)
    count  = count+1

conn.close()  
