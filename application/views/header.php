<!DOCTYPE html>
<html class="ls-theme-orange">
  <head>
    <title><?php echo $title ?></title>

    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="//assets.locaweb.com.br/locastyle/edge/stylesheets/locastyle.css">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/custom.css') ?>">
    <link href="<?php echo site_url('assets/dist/css/datepicker.min.css'); ?>" rel="stylesheet" type="text/css">
  </head>
  <body>
  <input type="hidden" id="URL" name="URL" value="<?php echo site_url() ?>">
    <div class="ls-topbar ">

  <!-- Barra de Notificações -->
  <div class="ls-notification-topbar">

    <!-- Links de apoio -->
<!--     <div class="ls-alerts-list">
      <a href="#" class="ls-ico-bell-o" data-counter="8" data-ls-module="topbarCurtain" data-target="#ls-notification-curtain"><span>Notificações</span></a>
      <a href="#" class="ls-ico-bullhorn" data-ls-module="topbarCurtain" data-target="#ls-help-curtain"><span>Ajuda</span></a>
      <a href="#" class="ls-ico-question" data-ls-module="topbarCurtain" data-target="#ls-feedback-curtain"><span>Sugestões</span></a>
    </div> -->

    <!-- Dropdown com detalhes da conta de usuário -->
    <div data-ls-module="dropdown" class="ls-dropdown ls-user-account">
      <a href="#" class="ls-ico-user">
        <!-- <img src="" alt="" /> -->
        <span class="ls-name"><?php echo $email ?></span>
        (<?php echo $nome ?>)
      </a><!-- 

      <nav class="ls-dropdown-nav ls-user-menu">
        <ul>
          <li><a href="<?php echo site_url("cadastro/usuario/$id") ?>">Meus dados</a></li>
          <li><a href="<?php echo site_url("login/logout") ?>">Sair</a></li>
         </ul>
      </nav> -->
    </div>
  </div>

  <span class="ls-show-sidebar ls-ico-menu"></span>

  <!-- <a href="/locawebstyle/documentacao/exemplos//pre-painel"  class="ls-go-next"><span class="ls-text">Voltar à lista de serviços</span></a> -->

  <!-- Nome do produto/marca com sidebar -->
    <h1 class="ls-brand-name">
      <a href="home" class="ls-ico-earth">
        <small>Sistema de E-commerce</small>
        Teste Store
      </a>
    </h1>
  <!-- Nome do produto/marca sem sidebar quando for o pre-painel  -->
</div>

<aside class="ls-sidebar">

  <div class="ls-sidebar-inner">
      <!-- <a href="/locawebstyle/documentacao/exemplos//pre-painel"  class="ls-go-prev"><span class="ls-text">Voltar à lista de serviços</span></a> -->
      <nav class="ls-menu">
        <ul>
           <li><a href="<?php echo site_url('home') ?>" class="ls-ico-dashboard" title="Dashboard">Página Inicial</a></li>
           <li><a href="<?php echo site_url('cadastro/pedido') ?>" class="ls-ico-plus" title="Pedidos">Pedidos</a></li>
        </ul>
      </nav>
  </div>
</aside>
    <?php if (!empty($msg)): ?>
      <br>
      <br>
      <main class="ls-main ls-main-custom col-md-12" style="min-height:0%!important;height:6%;margin-top: 35px;padding-top:0px;position: fixed;z-index: 9999;width: 86%;">
            <div class="ls-dismissable ls-txt-center col-md-12 ls-alert-<?php echo $msg["type"] ?>">
              <strong><?php echo $msg["message"] ?></strong><span data-ls-module="dismiss" class="ls-dismiss">&times;</span>
            </div>
      </main>
    <?php endif ?>
