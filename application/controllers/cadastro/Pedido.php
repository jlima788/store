<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pedido extends CI_Controller {

    private $data = array("content" => "",
        "footer_content" => array("javascripts" => array("assets/js/accounting.min", "assets/js/checkout")),
    );

    public function __construct() {
        parent::__construct();
        $this->load->model("Pedido_model", "Pedido", true);
        // $this->load->model("Conhecimento_nfe_model", "Conhecimento_nfe", true);
        // $this->load->model("Cliente_model", "Cliente", true);
        // $this->load->model("Cidade_model", "Cidade", true);
        // $this->load->model("Navio_model", "Navio", true);
        // $this->load->model("Especie_model", "Especie", true);
        // $this->load->model("Mercadoria_model", "Mercadoria", true);
        // $this->load->model("Motorista_model", "Motorista", true);
        // $this->load->model("Servico_model", "Servico", true);
    }

    public function index() {
        $p = $this->input->get('p') ? ($this->input->get('p') * 10 - 10) : 0;
        $s = $this->input->get('s') ? $this->input->get('s') : "";

        $options["like"] = array(
            "id_pedido" => $s
        );

        $options["pagination"] = array("offset" => $p, "per_page" => 10);

        $pedido = $this->Pedido->get(null, $options, true);

        if (!$pedido)
            show_message_alert("Não a registro para sua pesquisa", "warning");

        $count = count($this->Pedido->get(null, array("like" => array("id_pedido" => $s))));
        $url = site_url('cadastro/pedido');
        $links = create_pagination($url, $count);

        $this->data["content"]["data"] = $pedido;
        $this->data["content"]["links"] = $links;
        $this->data["content"]["data_options"]["s"] = $s;
        load_content("cadastro/pedido/index", $this->data);
    }

    public function checkout (){
        load_content("checkout", $this->data);
    }

    public function editar($id = "") {
        if ($conhecimento = $this->Conhecimento->get($id)) {
            $conhecimento = array_shift($conhecimento);
            $this->data["content"]["data"] = $conhecimento;
            load_content("cadastro/conhecimento/editar", $this->data);
        } else {
            show_message_alert("Conhecimento não existe, por favor tentar novamente", "danger");
            redirect('cadastro/conhecimento');
        }
    }

    public function update($id = "") {
        if ($this->Conhecimento->get($id)) {
            if ($this->validation_fields("update")) {
                $post = $this->input->post();
                $post = convert_values_save(array("money", "date"), $post);
                $chave_nfe = array();
                $chave_nfe = $post["chave_nfe"];
                unset($post["chave_nfe"]);
                unset($post["submit"]);
                $conhecimento = $this->Conhecimento->get($id);
                $conhecimento = array_shift($conhecimento);


                if (!empty($post["chave_nfe"])) {

                    if (!$this->Conhecimento_nfe->get(array("seq" => $conhecimento->seq))) {
                        $this->Conhecimento_nfe->insert(array(
                            "seq" => $conhecimento->seq,
                            "id_solicitacao" => $conhecimento->id_solicitacao,
                            "chave_nfe" => $chave_nfe
                        ));
                    } else {
                        $this->Conhecimento_nfe->update(array(
                            "id_solicitacao" => $conhecimento->id_solicitacao,
                            "chave_nfe" => $chave_nfe
                                ), array(
                            "seq" => $conhecimento->seq
                        ));
                    }
                }
                if (!empty($post["codservico"])) {
                    $this->Conhecimento->delete_conhecimento_servico(array(
                        "seq" => $conhecimento->seq
                    ));
                    foreach ($post["codservico"] as $key => $value) {
                        $this->Conhecimento->insert_servicos_conhecimento(array(
                            "id_solicitacao" => $conhecimento->id_solicitacao,
                            "seq" => $conhecimento->seq,
                            "codservico" => $value,
                            "valor" => money_convert_save($post["valor_servico"][$key])
                        ));
                    }
                    unset($post["valor_servico"]);
                    unset($post["codservico"]);
                } else {
                    $this->Conhecimento->delete_conhecimento_servico(array(
                        "seq" => $conhecimento->seq
                    ));
                }
                $conhecimento = $this->Conhecimento->update($post, $id);
            }
            redirect("cadastro/conhecimento/editar/$id");
        } else {
            show_message_alert("Conhecimento não existe, por favor tentar novamente", "danger");
            redirect('cadastro/conhecimento');
        }
    }

    public function envioLoteCte($id_solicitacao = "", $seq = "") {
        $data = json_decode($this->input->post("data"));
        $id_solicitacao = $id_solicitacao; //$data->id_solicitacao;
        $seq = $seq; //$data->seq;

        $this->load->model("Conhecimento_model", "", true);
        $this->load->model("Cliente_model", "", true);

        $confidence = $this->Cliente_model->get("1");
        $confidence = removeEspecialCode($confidence);
        $confidence = array_shift($confidence);

        $id_solicitacao = str_replace("-", "/", $id_solicitacao);

        $conhecimento = $this->Conhecimento_model->get(array(
            "id_solicitacao" => $id_solicitacao
        ));

        $dadosXML = $this->Conhecimento_model->get_xml_conhecimento(array(
            "id_solicitacao" => $id_solicitacao,
            "seq" => $seq
        ));

        $randomCT = mt_rand(1, 9);
        $nCT = str_pad($randomCT, 9, "0", STR_PAD_LEFT);

        $cCT = str_pad(mt_rand(1, 9), 8, "0", STR_PAD_LEFT);

        $chaveAcesso = chave_acesso("35", date("ym"), $confidence->cpfcnpj, "57", "0", $nCT, "1", $cCT);

        $dadosXML = removeEspecialCode($dadosXML);

        $dadosXML = array_shift($dadosXML);
        $xmlObject = simplexml_load_file("xml/cteEnvio.xml");
        $xmlObject->idLote = str_pad(mt_rand(1, 999999999999999), 15, "0", STR_PAD_LEFT);
        $xmlObject->CTe->infCte["Id"] = "CTe" . $chaveAcesso;
        $ideNode = $xmlObject->CTe->infCte->ide;
        $ideNode->cUF = "35";
        $ideNode->CFOP = $dadosXML->cfop;
        $ideNode->natOp = "SERVICO DE TRANSPORTE";
        $ideNode->forPag = "1";
        $ideNode->mod = "57";
        $ideNode->serie = "0";
        $ideNode->nCT = $randomCT;
        $ideNode->cCT = $cCT;
        $ideNode->cDV = calcula_dv($chaveAcesso);
        $ideNode->dhEmi = date("Y-m-d\\TH:i:s");
        $ideNode->tpImp = "1";
        $ideNode->tpEmis = "1";
        $ideNode->tpAmb = "2"; //NAO ESQUECER DE MUDAR ISSO DEPOIS
        $ideNode->verProc = "1";
        $ideNode->procEmi = "3";
        $ideNode->tpCTe = "0";
        $ideNode->procEmi = "3";
        $ideNode->cMunEnv = "3548500";
        $ideNode->xMunEnv = "SANTOS";
        $ideNode->UFEnv = "SP";
        $ideNode->tpServ = "0";
        $ideNode->cMunIni = $dadosXML->origem == "" ? "0000000" : $dadosXML->origem;
        $ideNode->xMunIni = $dadosXML->ibge_cidade_rem == "" ? "NAOTEM" : $dadosXML->ibge_cidade_rem;
        $ideNode->UFIni = $dadosXML->uf_origem == "" ? "IF" : $dadosXML->uf_origem;
        $ideNode->cMunFim = $dadosXML->destino == "" ? "0000000" : $dadosXML->destino;
        $ideNode->xMunFim = $dadosXML->ibge_cidade_dest == "" ? "NAOTEM" : $dadosXML->ibge_cidade_dest;
        $ideNode->UFFim = $dadosXML->uf_destino == "" ? "IF" : $dadosXML->uf_destino;
        $ideNode->retira = "1";
        $ideNode->modal = "01";
        $ideNode->xDetRetira = "Detalhe do retira";

        if ($dadosXML->cep_rec != "") {
            if ($dadosXML->cep_rec <= 4) {
                $tomaNum = $dadosXML->cep_rec - 1;
                $ideNode->addChild("toma03");
                $ideNode->toma03->addChild("toma", $tomaNum);
            } else if ($dadosXML->cep_rec > 4) {
                $ideNode->addChild("toma04");
                $ideNode->toma04->addChild("toma", "4");
                $ideNode->toma04->addChild("fone", $dadosXML->telefone_tomador == "" ? "00000000000000" : $dadosXML->telefone_tomador);
                $ideNode->toma04->addChild("IE", $dadosXML->ie_tomador);
                $ideNode->toma04->addChild("xNome", $dadosXML->razao_tomador);
                $ideNode->toma04->addChild("CNPJ", $dadosXML->cnpj_tomador);
                $ideNode->toma04->addChild("enderToma");
                $ideNode->toma04->enderToma->addChild("xLgr", $dadosXML->logradouro_tomador);
                $ideNode->toma04->enderToma->addChild("nro", $dadosXML->numero_tomador);
                $ideNode->toma04->enderToma->addChild("xCpl", $dadosXML->complemento_tomador);
                $ideNode->toma04->enderToma->addChild("xBairro", $dadosXML->bairro_tomador);
                $ideNode->toma04->enderToma->addChild("xPais", "Brasil");
                $ideNode->toma04->enderToma->addChild("cPais", "1058");
                $ideNode->toma04->enderToma->addChild("CEP", $dadosXML->cep_tomador);
                $ideNode->toma04->enderToma->addChild("UF", $dadosXML->estado_tomador);
                $ideNode->toma04->enderToma->addChild("xMun", $dadosXML->cidade_tomador);
            }
        } else {
            $tomaNum = 0;
            $ideNode->addChild("toma03");
            $ideNode->toma03->addChild("toma", $tomaNum);
        }

        $complNode = $xmlObject->CTe->infCte->compl;
        $complNode->xObs = $dadosXML->observacoes;

        $emitNode = $xmlObject->CTe->infCte->emit;
        $emitNode->CNPJ = "46036000000142"; //$confidence->cpfcnpj;
        $emitNode->IE = $confidence->ie;
        $emitNode->xNome = "CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL"; //$confidence->razao_social;
        $emitNode->xFant = $confidence->nome_fantasia;
        $emitNode->enderEmit->xLgr = $confidence->logradouro;
        $emitNode->enderEmit->nro = $confidence->numero;
        $emitNode->enderEmit->xBairro = $confidence->bairro;
        $emitNode->enderEmit->cMun = "3548500";
        $emitNode->enderEmit->xMun = $confidence->cidade;
        $emitNode->enderEmit->CEP = $confidence->cep_empresa;
        $emitNode->enderEmit->UF = $confidence->estado;
        $emitNode->enderEmit->fone = $confidence->telefone1;

        $remNode = $xmlObject->CTe->infCte->rem;

        $remNode->CNPJ = $dadosXML->cnpj_rem;
        $remNode->IE = $dadosXML->ie_rem == "" ? "000000000000" : $dadosXML->ie_rem;
        $remNode->xNome = "CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL"; //$dadosXML->razao_rem == "" ? "NAO TEM" : $dadosXML->razao_rem;
        $remNode->xFant = $dadosXML->fantasia_rem == "" ? "NAO TEM" : $dadosXML->fantasia_rem;
        $remNode->enderReme->xLgr = $dadosXML->logradouro_rem == "" ? "NAO TEM" : $dadosXML->logradouro_rem;
        $remNode->enderReme->nro = $dadosXML->numero_rem == "" ? "00" : $dadosXML->numero_rem;
        $remNode->enderReme->xBairro = $dadosXML->bairro_rem == "" ? "NAO TEM" : $dadosXML->bairro_rem;
        $remNode->enderReme->cMun = $dadosXML->im_rem == "" ? "0000000" : $dadosXML->im_rem;
        $remNode->enderReme->xMun = $dadosXML->cidade_rem == "" ? "NAO TEM" : $dadosXML->cidade_rem;
        $remNode->enderReme->CEP = $dadosXML->cep_rem == "" ? "00000000" : $dadosXML->cep_rem;
        $remNode->enderReme->UF = $dadosXML->estado_rem == "" ? "EX" : $dadosXML->estado_rem;
        $remNode->fone = $dadosXML->telefone_rem == "" ? "0000000000" : $dadosXML->telefone_rem;
        $remNode->enderReme->cPais = "0000";
        $remNode->enderReme->xPais = $dadosXML->pais_rem == "" ? "NAOTEM" : $dadosXML->pais_rem;

        if (!empty($dadosXML->cnpj_exp)) {
            $expedNode = $xmlObject->CTe->infCte->exped;
            $expedNode->CNPJ = $dadosXML->cnpj_exp;
            $expedNode->IE = $dadosXML->ie_exp;
            $expedNode->xNome = "CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL"; //$dadosXML->razao_exp;
            // $expedNode->xFant              = $dadosXML->fantasia_rem;
            $expedNode->enderExped->xLgr = $dadosXML->logradouro_exp;
            $expedNode->enderExped->nro = $dadosXML->numero_exp;
            $expedNode->enderExped->xBairro = $dadosXML->bairro_exp;
            // $expedNode->enderExped->cMun = $dadosXML->im_rem;
            $expedNode->enderExped->xMun = $dadosXML->cidade_exp;
            $expedNode->enderExped->CEP = $dadosXML->cep_exp;
            $expedNode->enderExped->UF = $dadosXML->estado_exp;
            $expedNode->enderExped->fone = $dadosXML->telefone_exp;
        }

        // if (!empty($dadosXML->cnpj_rec)) {
        //     $recebNode                      = $xmlObject->CTe->infCte->receb;
        //     $recebNode->CNPJ                = $dadosXML->cnpj_rec;
        //     $recebNode->IE                  = $dadosXML->ie_rec;
        //     $recebNode->xNome               = $dadosXML->razao_rec;
        //     // $recebNode->xFant              = $dadosXML->fantasia_rem;
        //     $recebNode->enderReceb->xLgr    = $dadosXML->logradouro_rec;
        //     $recebNode->enderReceb->nro     = $dadosXML->numero_rec;
        //     $recebNode->enderReceb->xBairro = $dadosXML->bairro_rec;
        //     // $recebNode->enderReceb->cMun = $dadosXML->im_rem;
        //     $recebNode->enderReceb->xMun    = $dadosXML->cidade_rec;
        //     $recebNode->enderReceb->CEP     = $dadosXML->cep_rec;
        //     $recebNode->enderReceb->UF      = $dadosXML->estado_rec;
        //     $recebNode->enderReceb->fone    = $dadosXML->telefone_rec;
        // }

        $destNode = $xmlObject->CTe->infCte->dest;
        $destNode->CNPJ = $dadosXML->cnpj_dest;
        $destNode->IE = $dadosXML->ie_dest == "" ? "000000000000" : $dadosXML->ie_dest;
        $destNode->xNome = "CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL"; //$dadosXML->razao_dest == "" ? "NAO TEM" : $dadosXML->razao_dest;
        // $destNode->xFant              = $dadosXML->fantansia_dest == "" ? "NAO TEM" : $dadosXML->fantansia_dest;
        $destNode->enderDest->xLgr = $dadosXML->logradouro_dest == "" ? "NAO TEM" : $dadosXML->logradouro_dest;
        $destNode->enderDest->nro = $dadosXML->numero_dest == "" ? "00" : $dadosXML->numero_dest;
        $destNode->enderDest->xBairro = $dadosXML->bairro_dest == "" ? "NAO TEM" : $dadosXML->bairro_dest;
        $destNode->enderDest->cMun = $dadosXML->im_dest == "" ? "0000000" : $dadosXML->im_dest;
        $destNode->enderDest->xMun = $dadosXML->cidade_dest == "" ? "NAO TEM" : $dadosXML->cidade_dest;
        $destNode->enderDest->CEP = $dadosXML->cep_dest == "" ? "00000000" : $dadosXML->cep_dest;
        $destNode->enderDest->UF = $dadosXML->estado_dest == "" ? "EX" : $dadosXML->estado_dest;
        // $destNode->fone               = $dadosXML->telefone_dest == "" ? "00000000" : $dadosXML->telefone_dest;
        $destNode->enderDest->cPais = "0000";
        $destNode->enderDest->xPais = $dadosXML->pais_des == "" ? "NAO TEM" : $dadosXML->pais_des;

        $vPrestNode = $xmlObject->CTe->infCte->vPrest;
        $vPrestNode->vTPrest = $dadosXML->prestacao;
        $vPrestNode->vRec = $dadosXML->prestacao;

        if (!empty($dadosXML->frete_peso) && $dadosXML->frete_peso != 0.00) {
            $new = $vPrestNode->addChild("Comp");
            $new->addChild("xNome", "FRETE PESO");
            $new->addChild("vComp", $dadosXML->frete_peso);
        }
        if (!empty($dadosXML->frete) && $dadosXML->frete != 0.00) {
            $new = $vPrestNode->addChild("Comp");
            $new->addChild("xNome", "FRETE VALOR");
            $new->addChild("vComp", $dadosXML->frete);
        }
        if (!empty($dadosXML->despacho) && $dadosXML->despacho != 0.00) {
            $new = $vPrestNode->addChild("Comp");
            $new->addChild("xNome", "DESPACHO");
            $new->addChild("vComp", $dadosXML->despacho);
        }
        if (!empty($dadosXML->pedagio) && $dadosXML->pedagio != 0.00) {
            $new = $vPrestNode->addChild("Comp");
            $new->addChild("xNome", "PEDAGIO");
            $new->addChild("vComp", $dadosXML->pedagio);
        }

        $impNode = $xmlObject->CTe->infCte->imp;
        $impNode->ICMS->ICMS00->CST = $dadosXML->base == 0.00 ? "00" : $dadosXML->base;
        $impNode->ICMS->ICMS00->vBC = $dadosXML->perc;
        $impNode->ICMS->ICMS00->pICMS = $dadosXML->icms;
        $impNode->ICMS->ICMS00->vICMS = "0.00";

        $infCteNormNode = $xmlObject->CTe->infCte->infCTeNorm;
        $infCteNormNode->infCarga->vCarga = $dadosXML->valor_mercadoria;
        $infCteNormNode->infCarga->proPred = $dadosXML->mercadoria;

        $infCteNormNode->infCarga->infQ->cUnid = "01";
        $infCteNormNode->infCarga->infQ->tpMed = "PESO BRUTO";
        $infCteNormNode->infCarga->infQ->qCarga = number_format($dadosXML->peso_bruto, 4, '.', '');

        // $infCteNormNode->addChild("seq");
        // $infCteNormNode->seq->addChild("respSeg", "4");
        // $infCteNormNode->seq->addChild("xSeg", "GENERALE BRASIL SEGUROS");
        // $infCteNormNode->seq->addChild("nApol", "35541000994");
        // $infCteNormNode->infModal->rodo->RNTRC = $dadosXML->antt;
        // $infCteNormNode->infModal->rodo->dPrev = $dadosXML->previsao_entrega;
        // $infCteNormNode->infModal->rodo->lota  = "0";
        // $xmlObject->CTe->infCte->addChild("autXML");
        // $xmlObject->CTe->infCte->autXML->addChild("CNPJ", "00000000000000");
        // $xmlObject->CTe->infCte->autXML->addChild("CPF", $dadosXML->cpf);
        // $xmlObject->CTe->infCte->addChild("moto");
        // $xmlObject->CTe->infCte->moto->addChild("xNome", $dadosXML->motorista);
        // $xmlObject->CTe->infCte->moto->addChild("cpf", $dadosXML->cpf);

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;

        $dom->loadXML($xmlObject->asXML());

        $xmlOutput = $dom->saveXML();

        // $this->load->helper('download');
        // force_download("CTe" . $chaveAcesso . ".xml", $xmlOutput);

        $code = base64_encode(date("Y-m-d H:i:ss"));
        file_put_contents("xml/$code.xml", (string) $xmlOutput);
        // print_r($xmlOutput);

        echo json_encode(array(
            "status" => true,
            "data" => array(
                "xml" => $xmlOutput,
                "code" => $code,
                "conhecimento_id" => $dadosXML->conhecimento_id
            )
        ));
    }

    public function gera_txt($id_solicitacao = "", $seq = "") {
        $id_solicitacao = $id_solicitacao; //$data->id_solicitacao;
        $seq = $seq; //$data->seq;

        $this->load->model("Conhecimento_model", "", true);
        $this->load->model("Conhecimento_nfe_model", "", true);
        $this->load->model("Cliente_model", "", true);

        $confidence = $this->Cliente_model->get("1");
        $confidence = removeEspecialCode($confidence);
        $confidence = array_shift($confidence);

        $id_solicitacao = str_replace("-", "/", $id_solicitacao);

        $conhecimento = $this->Conhecimento_model->get(array(
            "id_solicitacao" => $id_solicitacao
        ));

        $dadosTXT = $this->Conhecimento_model->get_xml_conhecimento(array(
            "id_solicitacao" => $id_solicitacao,
            "seq" => $seq
        ));
        $dadosTXT = removeEspecialCode($dadosTXT);
        $dadosTXT = array_shift($dadosTXT);
        $tpAmb = "2"; // 1 = producao 2 = homologacao
        $cCT = str_pad(mt_rand(1, 9), 8, "0", STR_PAD_LEFT);
        $randomCT = mt_rand(1, 9);
        $nCT = str_pad($randomCT, 9, "0", STR_PAD_LEFT);
        $chaveAcesso = chave_acesso("35", date("ym"), $confidence->cpfcnpj, "57", "0", $nCT, "1", $cCT);
        $cDV = calcula_dv($chaveAcesso);
        $n = chr(13) . PHP_EOL;
        $txtString = 'REGISTROSCTE|1' . $n;
        $txtString .= 'CTE|2.00||' . $n;
        $txtString .= 'IDE|35||' . $dadosTXT->cfop . '|SERVICO DE TRANSPORTE|1|57|0|' . $cCT . '|' . date("Y-m-d\\TH:i:s") . '|1|1|' . $cDV . '|' . $tpAmb . '|0|3|||3548500|SANTOS|SP|01|0|' . trim($dadosTXT->origem) . '|' . trim($dadosTXT->descr_cidade_rem) . '||' . trim($dadosTXT->destino) . '|' . trim($dadosTXT->descr_cidade_des) . '||1||||' . $n;
        if ($dadosTXT->cep_rec != "") {
            if ($dadosTXT->cep_rec <= 4) {
                $tomaNum = $dadosTXT->cep_rec - 1;
                $txtString .= 'TOMA03|' . $tomaNum . '|' . $n;
            } else if ($dadosTXT->cep_rec > 4) {
                $txtString .= 'TOMA4|' . $dadosTXT->cnpj_tomador . '|' . $dadosTXT->ie_tomador . '||' . trim($dadosTXT->razao_tomador) . '|' . trim($dadosTXT->logradouro_tomador) . '|' . trim($dadosTXT->numero_tomador) . '|' . trim($dadosTXT->complemento_tomador) . '|' . trim($dadosTXT->bairro_tomador) . '|' . trim($dadosTXT->cidade_tomador) . '|' . trim($dadosTXT->estado_tomador) . '|' . trim($dadosTXT->cep_tomador) . '||' . trim($dadosTXT->telefone_tomador) . '||1058|BRASIL||' . $n;
            }
        } else {
            $tomaNum = 0;
            $txtString .= 'TOMA03|' . $tomaNum . '|' . $n;
        }
        $txtString .= 'COMPL||||||' . trim($dadosTXT->observacoes) . '|' . $n;
        $txtString .= 'FLUXO||||' . $n;
        $txtString .= 'EMIT|' . $confidence->cpfcnpj . '|' . $confidence->ie . '|' . $confidence->razao_social . '|' . $confidence->nome_fantasia . '|' . $confidence->logradouro . '|' . $confidence->numero . '|' . $confidence->complemento . '|' . $confidence->bairro . '|3548500|' . $confidence->cidade . '|' . $confidence->cep_empresa . '|' . $confidence->estado . '|' . $confidence->telefone1 . '|' . $n;
        $cnpj_rem = $dadosTXT->cnpj_rem == "" ? "00000000000000" : $dadosTXT->cnpj_rem;
        $txtString .= 'REM|' . $cnpj_rem . '||' . $dadosTXT->ie_rem . '|' . trim($dadosTXT->razao_rem) . '|' . trim($dadosTXT->fantasia_rem) . '|' . trim($dadosTXT->logradouro_rem) . '|' . trim($dadosTXT->numero_rem) . '|' . trim($dadosTXT->complemento_rem) . '|' . trim($dadosTXT->bairro_rem) . '|' . trim($dadosTXT->ibge_cidade_rem) . '|' . trim($dadosTXT->cidade_rem) . '|' . trim($dadosTXT->cep_rem) . '|' . trim($dadosTXT->estado_rem) . '|' . trim($dadosTXT->pais_rem) . '|||' . $n;
        if (!empty($dadosTXT->cnpj_exp)) {
            $txtString .= 'EXPED|' . $dadosTXT->cnpj_exp . '||' . $dadosTXT->exp_ie . '|' . trim($dadosTXT->telefone_rec) . '|' . trim($dadosTXT->tipo_tomador) . '|' . trim($dadosTXT->cnpj_tomador) . '||' . trim($dadosTXT->razao_tomador) . '|' . trim($dadosTXT->bairro_local_coleta) . '|' . trim($dadosTXT->logradouro_tomador) . '|' . trim($dadosTXT->complemento_tomador) . '|' . trim($dadosTXT->numero_tomador) . '|1058|BRASIL||' . $n;
        }
        if (!empty($dadosTXT->cnpj_rec)) {
            $txtString .= 'RECEB|' . $dadosTXT->cnpj_rec . '||' . $dadosTXT->ie_rec . '|' . trim($dadosTXT->cep_tomador) . '|' . trim($dadosTXT->telefone_tomador) . '|' . trim($dadosTXT->loc_coleta) . '||' . trim($dadosTXT->ie_local_coleta) . '|' . trim($dadosTXT->cidade_local_coleta) . '|' . trim($dadosTXT->razao_local_coleta) . '|' . trim($dadosTXT->numero_local_coleta) . '|' . trim($dadosTXT->logradouro_local_coleta) . '|1058|BRASIL||' . $n;
        }
        $cnpj_dest = $dadosTXT->cnpj_dest == "" ? "00000000000000" : $dadosTXT->cnpj_dest;
        $txtString .= 'DEST|' . $cnpj_dest . '||' . $dadosTXT->ie_dest . '|' . trim($dadosTXT->razao_dest) . '||' . trim($dadosTXT->logradouro_dest) . '|' . trim($dadosTXT->numero_dest) . '||' . trim($dadosTXT->bairro_dest) . '|' . trim($dadosTXT->ibge_cidade_dest) . '|||' . trim($dadosTXT->estado_dest) . '|' . trim($dadosTXT->pais_des) . '|||' . $n;
        $txtString .= 'VPREST|' . trim($dadosTXT->prestacao) . '|' . trim($dadosTXT->prestacao) . '|' . $n;
        if ($dadosTXT->frete_peso != 0.00) {
            $txtString .= 'COMP|FRETE PESO|' . trim($dadosTXT->frete_peso) . '|' . $n;
        }
        if ($dadosTXT->frete != 0.00) {
            $txtString .= 'COMP|FRETE VALOR|' . trim($dadosTXT->frete) . '|' . $n;
        }
        if ($dadosTXT->despacho != 0.00) {
            $txtString .= 'COMP|DESPACHO|' . trim($dadosTXT->despacho) . '|' . $n;
        }
        if ($dadosTXT->pedagio != 0.00) {
            $txtString .= 'COMP|PEDAGIO|' . trim($dadosTXT->pedagio) . '|' . $n;
        }

        $servicos_conhecimento = $this->Conhecimento->get_servicos_conhecimento(array(
                    "tbl_itensctrc.seq" => $seq,
                    "id_solicitacao" => $id_solicitacao
                )) ? $this->Conhecimento->get_servicos_conhecimento(array(
                    "tbl_itensctrc.seq" => $seq,
                    "id_solicitacao" => $id_solicitacao
                )) : array();
        foreach ($servicos_conhecimento as $key => $value) {
            $txtString .= 'COMP|' . $value->descricao . '|' . $value->valor . '|' . $n;
        }

        $txtString .= 'IMP|||' . $n;
        $txtString .= 'ICMS00|00|' . trim($dadosTXT->base) . '|' . trim($dadosTXT->perc) . '|' . trim($dadosTXT->icms) . '|' . $n;
        $txtString .= 'INFCTENORM|' . $n;
        $txtString .= 'INFCARGA|' . trim($dadosTXT->valor_mercadoria) . '|' . trim($dadosTXT->mercadoria) . '||' . $n;
        $txtString .= 'INFQ|01|PESO BRUTO|' . number_format($dadosTXT->peso_bruto, 4, '.', '') . '|' . $n;
        $txtString .= 'INFDOC|' . $n;

        $chave_nfes = $this->Conhecimento_nfe_model->get(array(
                    "seq" => $seq,
                    "id_solicitacao" => $id_solicitacao
                )) ? $this->Conhecimento_nfe_model->get(array(
                    "seq" => $seq,
                    "id_solicitacao" => $id_solicitacao
                )) : array();
        foreach ($chave_nfes as $key => $value) {
            $txtString .= 'INFNFE|' . $value->chave_nfe . '|' . $n;
        }

        $txtString .= 'SEG|4|GENERALE BRASIL SEGUROS|35541000994|||' . $n;
        $txtString .= 'INFMODAL|2.00|' . $n;
        $txtString .= 'RODO|' . trim($dadosTXT->antt) . '|' . trim($dadosTXT->previsao_entrega == "0000-00-00" ? "" : $dadosTXT->previsao_entrega) . '|0||' . $n;
        $txtString .= 'MOTO|' . trim($dadosTXT->motorista) . '|' . trim($dadosTXT->cpf) . '|';

        $this->load->helper('download');
        force_download("CTe" . $chaveAcesso . ".txt", $txtString);
    }

    public function saveRetorno() {
        $data = json_decode($this->input->post('data'));
        $this->load->model("Conhecimento_model", "", true);
        $update = $this->Conhecimento_model->update(array(
            "xml_retorno_envio" => $data->xml
                ), $data->conhecimento_id);
        if ($update) {
            echo json_encode(array(
                "status" => true
            ));
        }
    }

    private function validation_fields($type) {
        $this->load->library('form_validation');
        $data = array(
            'nr_documento' => $this->input->post('nr_documento')
        );
        $msg = array('required' => 'Preencha o campo %s.');
        $this->form_validation->set_message($msg);
        $this->form_validation->set_data($data);
        $rules = array(
            'insert' => array(
                array(
                    'field' => 'nr_documento',
                    'label' => 'Documento',
                    'rules' => 'required'
                )
            ),
            'update' => array(
                array(
                    'field' => 'nr_documento',
                    'label' => 'Documento',
                    'rules' => 'required'
                )
            )
        );
        $this->form_validation->set_rules($rules[$type]);
        if ($this->form_validation->run() == FALSE) {
            show_message_alert(validation_errors(), "danger");
            return false;
        } else {
            show_message_alert("Conhecimento salvo com sucesso", "success");
            return true;
        }
    }

}

/* End of file Conhecimento.php */
/* Location: ./application/controllers/cadastro/Conhecimento.php */
