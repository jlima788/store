<main class="ls-main ">
    <div class="container-fluid">
        <h1 class="ls-title-intro">Editando Conhecimento: <strong><u><?php echo $data->id_solicitacao ?></u></strong></h1>
        <div class="col-md-12">
            <form data-ls-module="form" id="formConhecimento" action="<?php echo site_url('ctrc/conhecimento/update/'.$data->conhecimento_id) ?>" class="ls-form ls-form-horizontal row" method="post" enctype="multipart/form-data">

                    <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Natureza Operação</b>
                            <?php $natureza = array("ESTADUAL","INTERESTADUAL"); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="natureza_operacao">
                                    <?php foreach ($natureza as $key => $value): ?>
                                    <?php if ($value == $data->natureza_operacao): ?>
                                    <option value="<?php echo $value ?>" selected><?php echo $value ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value ?>"><?php echo $value ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Tipo Carregamento</b>
                            <input type="text" name="tipo_carregamento" id="tipo_carregamento" class="ls-field" value="<?php echo $data->tipo_carregamento ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Remetente</b>
                            <?php $remetente = $this->Cliente->get();?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="remetente">
                                    <option value="">Selecione</option>
                                    <?php foreach ($remetente as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->remetente): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Local Coleta diferente</b>
                            <?php $local_coleta = $this->Cliente->get();?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="local_coleta">
                                    <option value="">Selecione</option>
                                    <?php foreach ($local_coleta as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->local_coleta): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Destinatário</b>
                            <?php $destinatario = $this->Cliente->get();?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="destinatario">
                                    <option value="">Selecione</option>
                                    <?php foreach ($destinatario as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->destinatario): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Local Entrega diferente</b>
                            <?php $local_entrega = $this->Cliente->get();?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="local_entrega">
                                    <option value="">Selecione</option>
                                    <?php foreach ($local_entrega as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->local_entrega): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Consignatário</b>
                            <?php $consignatario = $this->Cliente->get();?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="consignatario">
                                    <option value="">Selecione</option>
                                    <?php foreach ($consignatario as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->consignatario): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Expedidor</b>
                            <?php $expedidor = $this->Cliente->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="expedidor">
                                    <option value="">Selecione</option>
                                    <?php foreach ($expedidor as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->expedidor): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Recebedor</b>
                            <?php $recebedor = $this->Cliente->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="recebedor">
                                    <option value="">Selecione</option>
                                    <?php foreach ($recebedor as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->recebedor): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Tipo Tomador</b>
                            <?php $tipo_tomador = get_tipo_tomador(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="tipo_tomador">
                                    <option value="">Selecione</option>
                                    <?php foreach ($tipo_tomador as $key => $value): ?>
                                    <?php if ($key == $data->tipo_tomador): ?>
                                    <option value="<?php echo $key ?>" selected><?php echo $value ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Tomador</b>
                            <?php $tomador = $this->Cliente->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="tomador">
                                    <option value="">Selecione</option>
                                    <?php foreach ($tomador as $key => $value): ?>
                                    <?php if ($value->id_empresa == $data->tomador): ?>
                                    <option value="<?php echo $value->id_empresa ?>" selected><?php echo $value->nome_empresa ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->id_empresa ?>"><?php echo $value->nome_empresa ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <label class="ls-label col-md-5 col-xs-12">
                            <b class="ls-label-text">Chave NFE's</b>
                            <?php $chaves = $this->Conhecimento_nfe->get($data->seq); ?>
                            <?php if (!empty($chaves)) : ?>
                                <?php foreach ($chaves as $key => $value): ?>
                                    <input type="text" maxlength="44" name="chave_nfe[]" id="chave_nfe" class="ls-field" value="<?php echo $value->chave_nfe ?>">
                                <?php endforeach ?>
                            <?php else: ?>
                                <input type="text" maxlength="44" name="chave_nfe" id="chave_nfe" class="ls-field" >
                            <?php endif ?>

                        </label>
                    </fieldset>
                    <hr/>
                    <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Documento</b>
                            <input type="text" name="nr_documento" id="nr_documento" class="ls-field" value="<?php echo $data->nr_documento ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Previsão Entrega</b>
                            <input type="text" name="previsao_entrega" id="previsao_entrega" class="ls-field datepicker-here" data-language='pt-BR'  value="<?php echo convert_date($data->previsao_entrega) ?>">
                        </label>
                    </fieldset>
                    <hr/>
                    <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Origem</b>
                            <?php $origem = $this->Cidade->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="origem">
                                    <option value="">Selecione</option>
                                    <?php foreach ($origem as $key => $value): ?>
                                        <?php if ($value->codigo == $data->origem): ?>
                                            <option value="<?php echo $value->codigo ?>" selected><?php echo $value->descricao ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo $value->codigo ?>"><?php echo $value->descricao ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Destino</b>
                            <?php $destino = $this->Cidade->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="destino">
                                    <option value="">Selecione</option>
                                    <?php foreach ($destino as $key => $value): ?>
                                    <?php if ($value->codigo == $data->destino): ?>
                                    <option value="<?php echo $value->codigo ?>" selected><?php echo $value->descricao ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->codigo ?>"><?php echo $value->descricao ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Navio</b>
                            <?php $navio = $this->Navio->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="navio">
                                    <option value="">Selecione</option>
                                    <?php foreach ($navio as $key => $value): ?>
                                    <?php if ($value->codigo == $data->navio): ?>
                                    <option value="<?php echo $value->codigo ?>" selected><?php echo $value->descricao ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->codigo ?>"><?php echo $value->descricao ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Número Container</b>
                            <input type="text" name="numero_cntr" id="numero_cntr" class="ls-field" value="<?php echo $data->numero_cntr ?>">
                        </label>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Qtde</b>
                            <input type="text" name="qtde" id="qtde" class="ls-field" value="<?php echo $data->qtde ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Espécie</b>
                            <?php $especie = $this->Especie->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="especie">
                                    <option value="">Selecione</option>
                                    <?php foreach ($especie as $key => $value): ?>
                                    <?php if ($value->codigo == $data->especie): ?>
                                    <option value="<?php echo $value->codigo ?>" selected><?php echo $value->descricao ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->codigo ?>"><?php echo $value->descricao ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Peso Bruto</b>
                            <input type="text" name="peso_bruto" id="peso_bruto" class="ls-field" value="<?php echo $data->peso_bruto ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Mercadoria</b>
                            <?php $mercadoria = $this->Mercadoria->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="mercadoria">
                                    <option value="">Selecione</option>
                                    <?php foreach ($mercadoria as $key => $value): ?>
                                    <?php if ($value->codigo == $data->mercadoria): ?>
                                    <option value="<?php echo $value->codigo ?>" selected><?php echo $value->descricao ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->codigo ?>"><?php echo $value->descricao ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Valor Mercadoria</b>
                            <input type="text" name="vlr_mercadoria" id="vlr_mercadoria" class="ls-field moneyMask" value="<?php echo convert_money($data->vlr_mercadoria,false) ?>">
                        </label>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Motorista</b>
                            <?php $motorista = $this->Motorista->get(); ?>
                            <div class="ls-custom-select">
                                <select class="ls-select" name="cod_motorista">
                                    <option value="">Selecione</option>
                                    <?php foreach ($motorista as $key => $value): ?>
                                    <?php if ($value->codigo == $data->cod_motorista): ?>
                                    <option value="<?php echo $value->codigo ?>" selected><?php echo $value->nome ?></option>
                                    <?php else: ?>
                                    <option value="<?php echo $value->codigo ?>"><?php echo $value->nome ?></option>
                                    <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Placa Veículo</b>
                            <input type="text" name="placa_veiculo" id="placa_veiculo" class="ls-field placaMask" value="<?php echo $data->placa_veiculo ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Placa Reboque</b>
                            <input type="text" name="placa_reboque" id="placa_reboque" class="ls-field placaMask" value="<?php echo $data->placa_reboque ?>">
                        </label>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <label class="ls-label col-md-12 col-xs-12">
                            <b class="ls-label-text">Observações</b>
                            <textarea type="text" name="observacoes" id="observacoes" class="ls-field" cols="30" rows="10"><?php echo $data->observacoes ?></textarea>
                        </label>
                    </fieldset>

                    <fieldset>
                        <div class="ls-label col-md-3">
                            <p><b>Tipo Frete</b></p>
                            <label class="ls-label-text">
                                <input type="radio" name="tipo_frete" value="A" class="ls-field-radio" <?php echo $data->tipo_frete == "A" ? "checked='checked'" : '' ?>>
                                A Pagar
                            </label>
                            <label class="ls-label-text">
                                <input type="radio" name="tipo_frete" value="P" class="ls-field-radio" <?php echo $data->tipo_frete == "P" ? "checked='checked'" : '' ?>>
                                Pago
                            </label>
                        </div>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Frete Peso</b>
                            <input type="text" name="vlr_frete_peso" id="vlr_frete_peso" class="ls-field moneyMask vlr_icms_base_calculo" value="<?php echo convert_money($data->vlr_frete_peso,false) ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Aliquota Frete %</b>
                            <input type="text" name="aliq_frete" id="aliq_frete" class="ls-field numberMask" value="<?php echo convert_money($data->aliq_frete,false) ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Frete</b>
                            <input type="text" name="vlr_frete" id="vlr_frete" class="ls-field vlr_icms_base_calculo moneyMask" value="<?php echo convert_money($data->vlr_frete,false) ?>" disabled>
                        </label>
                    </fieldset>
                  <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">SEC / CAT</b>
                            <input type="text" name="vlr_seccat" id="vlr_seccat" class="ls-field moneyMask vlr_icms_base_calculo" value="<?php echo convert_money($data->vlr_seccat,false) ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Despacho</b>
                            <input type="text" name="vlr_despacho" id="vlr_despacho" class="ls-field moneyMask vlr_icms_base_calculo" value="<?php echo convert_money($data->vlr_despacho,false) ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Pedágio</b>
                            <input type="text" name="vlr_pedagio" id="vlr_pedagio" class="ls-field moneyMask vlr_icms_base_calculo" value="<?php echo convert_money($data->vlr_pedagio,false) ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Outros Serviços</b>
                            <?php $servicos_conhecimento = $this->Conhecimento->get_servicos_conhecimento(array('tbl_itensctrc.seq'=>$data->seq)); ?>
                            <?php $total_servicos = 0.00; ?>
                            <?php if (!empty($servicos_conhecimento)) { foreach ($servicos_conhecimento as $key => $value): ?>
                                <?php $total_servicos += $value->valor; ?>
                            <?php endforeach; }?>
                            <input type="text" name="vlr_servico" id="vlr_servico" class="ls-field moneyMask vlr_icms_base_calculo" value="<?php echo convert_money($data->vlr_servico,false) ?>">
                            <input type="hidden" id="vlr_servico_soma" class="ls-field" value="<?php echo $data->vlr_servico ?>">
                        </label>
                    </fieldset>
               <fieldset>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Percentual ICMS %</b>
                            <input type="text" name="vlr_icms_percentual" id="vlr_icms_percentual" class="ls-field numberMask vlr_icms" value="<?php echo convert_money($data->vlr_icms_percentual,false) ?>">
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Base cálculo ICMS</b>
                            <input type="text" name="vlr_icms_base_calculo" id="vlr_icms_base_calculo" class="ls-field vlr_icms" value="<?php echo convert_money($data->vlr_icms_base_calculo,false) ?>" disabled>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Valor ICMS</b>
                            <input type="text" name="vlr_icms" id="vlr_icms" class="ls-field" value="<?php echo convert_money($data->vlr_icms,false) ?>" disabled>
                        </label>
                        <label class="ls-label col-md-3 col-xs-12">
                            <b class="ls-label-text">Prestação</b>
                            <input type="text" name="vlr_prestacao" id="vlr_prestacao" class="ls-field" value="<?php echo convert_money($data->vlr_prestacao,false) ?>" disabled>
                        </label>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <legend><h3>Serviços <a href="#" id="addServico">(+)</a></h3></legend>
                        <hr>
                        <div class="modelServicos" id="appendServico">
                            <?php $servicos_conhecimento = $this->Conhecimento->get_servicos_conhecimento(array("tbl_itensctrc.seq"=>$data->seq)); ?>
                            <?php if (!empty($servicos_conhecimento)) { foreach ($servicos_conhecimento as $key => $value): ?>
                                <div class="col-md-12 col-xs-12 modelServicos">
                                    <label class="ls-label col-md-3 col-xs-12">
                                        <b class="ls-label-text">Serviço</b>
                                        <?php $servicos = $this->Servico->get(); ?>
                                        <div class="ls-custom-select">
                                            <select class="ls-select" name="codservico[]">
                                                <option value="">Selecione</option>
                                                <?php foreach ($servicos as $k => $v): ?>
                                                    <option value="<?php echo $v->codigo ?>" <?php echo $v->codigo == $value->codservico ? 'selected' : '' ?>><?php echo $v->descricao ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </label>
                                    <label class="ls-label col-md-3 col-xs-12">
                                        <b class="ls-label-text">Valor</b>
                                        <input type="text" name="valor_servico[]" data-name="valor_servico" class="ls-field moneyMask" value="<?php echo convert_money($value->valor,false) ?>">
                                        <input type="hidden" class="ls-field moneyMask" id="id_valor_view_soma" value="<?php echo $value->valor ?>">
                                    </label>
                                    <a href="#" name="removerServicos"><i class="ls-ico-close"></i></a>
                                </div>
                            <?php endforeach; } ?>
                        </div>
                    </fieldset>
            <div class="ls-actions-btn">
                <button class="ls-btn-primary" name="submit">Salvar</button>
                <?php $id_solicitacao = str_replace("/", "-", $data->id_solicitacao); ?>
                <a href="<?php echo site_url('ctrc/conhecimento/gera_txt/'.$id_solicitacao.'/'.$data->seq) ?>" id="EnviarLote" data-id_solicitacao="<?php echo $id_solicitacao ?>" data-seq="<?php echo $data->seq ?>"class="ls-btn-danger">Gera TXT</a>
                <a href="<?php echo site_url('ctrc/conhecimento') ?>" class="ls-btn-danger">Cancelar</a>
            </div>
        </form>
        </div>
        <fieldset id="modelServico" style="display: none;">
            <label class="ls-label col-md-2 col-xs-12">
                <b class="ls-label-text">Serviço</b>
                <div class="ls-custom-select">
                    <select class="ls-select" name="codservico[]">
                        <option value="">Selecione</option>
                        <?php $servicos = $this->Servico->get(); ?>
                        <?php foreach ($servicos as $key => $value): ?>
                            <option value="<?php echo $value->codigo ?>"><?php echo $value->descricao ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </label>
            <label class="ls-label col-md-3 col-xs-12">
                <b class="ls-label-text">Valor</b>
                <input type="text" name="valor_servico[]" data-name="valor_servico" class="ls-field moneyMask" value="">
                <input type="hidden" class="ls-field moneyMask" id="id_valor_soma" value="<?php echo $value->valor ?>">
            </label>
            <label class="ls-label col-md-2 col-xs-12">
                <b class="ls-label-text"><a href="#" class="ls-txt-right" name="delServico">Deletar (X)</a></b>
            </label>
        </fieldset>
    </div>
</main>
