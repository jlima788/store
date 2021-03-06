<main class="ls-main ">
    <div class="container-fluid">
        <h1 class="ls-title-intro">Pedidos</h1>
        <div class="ls-box-filter">
            <form action="<?php echo site_url('cadastro/pedido') ?>" class="ls-form ls-form-inline" method="GET">
                <label class="ls-label col-md-6 col-sm-4">
                    <b class="ls-label-text">Digite</b>
                    <input type="text" name="s" class="" placeholder="Pedido" value="<?php echo $data_options["s"] ?>">
                </label>
                <div class="ls-actions-btn">
                    <button class="ls-btn">Buscar</button>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <?php if (!empty($data)): ?>
                <div class="ls-collapse-group">

                <?php foreach ($data as $key => $value): ?>
                    <div data-ls-module="collapse" data-target="#acordeon<?php echo $value->id ?>" class="ls-collapse ">
                        
                        <a href="#" class="ls-collapse-header">Pedido:<h3 class="ls-collapse-title"><?php echo $value->id ?></h3>
                        </a>
                        <div class="ls-collapse-body" id="acordeon<?php echo $value->id ?>">
                            <?php 
                                $pedido = $this->Pedido->get(array("id"=>$value->id)); 
                                $itens_pedido = $this->Itens_Pedido->get(array("id_pedido"=>$value->id));
                            ?>
                            <table class="ls-ms-space ls-table ls-table-striped ls-bg-header">
                                
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Valor Total</th>
                                    </tr>
                               
                                <tbody>
                                <?php foreach ($itens_pedido as $k => $v): ?>
                                    <tr>
                                        <td><?php $produto = $this->Produto->get($v->id_produto_pai);
                                                echo $produto ? $produto[0]->nome : ""?></td>
                                        <td><?php echo $v->quantidade ?></td>
                                        <td><?php echo $v->valor_unitario ?></td>
                                        <td><?php echo $v->valor_total ?></td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                                
                            </table>
                            <form data-ls-module="form" id="formPedido" action="<?php echo site_url('cadastro/pedido/checkout/'.$value->id) ?>" class="ls-form ls-form-horizontal row" method="post" enctype="multipart/form-data">
                                <div class="ls-actions-btn">
                                    <button class="ls-btn-primary" name="submit">Finalizar Pedido</button>
                                </div>
                            </form>
                        </div>
                    </div>
                        
                <?php endforeach ?>
                </div>
                <?php echo $pagination ?>
            <?php endif ?>
        </div>
    </div>
</main>
