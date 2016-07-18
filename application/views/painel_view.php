<!DOCTYPE html>

<hmtl class="no-js" lang="pt-br">
    <head>
        <meta charset="utf-8" />

        <!-- Definir a largura da janela de exibição para a largura do dispositivo para celular -->
        <meta name="viewport" content="width=device-width" />

        <title><?php if(isset($titulo)): ?>{titulo} | <?php endif; ?>{titulo_padrao}</title>

        <!-- IE Fix for HTML5 Tags -->
        <!--[if lt IE9]><!--<script src="http://html5shiv.googlecode.com/dvn/trunk/html5.js"></script><![endif]-->
        {headerinc}
    </head>
    <body>
    <?php if(esta_logado(FALSE)):; ?>
        <div class="row">
            <div class="columns medium-6 columns text-left">
                <a href="<?php echo base_url('painel'); ?>"><h1>Painel ADM</h1></a>
            </div>
            <div class="columns medium-6 columns text-right">
                Logado como <strong><?php echo $this->session->userdata('user_nome'); ?></strong></br>
                <p class="text-right">
                    <?php echo anchor ('usuarios/alterar_senha/'.$this->session->userdata('user_id'), 'Alterar senha', 'class="button radius tiny"'); ?>
                    <?php echo anchor ('usuarios/logoff', 'Sair', 'class="tiny alert button"') ?>
            </div>
        </div>


        <div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
            <button class="menu-icon" type="button" data-toggle></button> <!--1. insere o botão menu;-->
            <div class="title-bar-title">Menu</div>
        </div>

        <div class="top-bar" id="example-menu"><!-- Cria outra div--><!--insere a linha preta menu-->
            <div class="top-bar-left"><!-- Cria outra div--><!--insere a div com os menus-->
                <ul class="dropdown menu" data-dropdown-menu> <!--CONFIGURADA COR PRETA NO CSS:  .dropdown{background-color: black !important;}-->
                    <li><?php echo anchor('painel','Inicio');?></li> <!-- MENU INÍCIO -->

                    <li><?php echo anchor('usuarios/cadastrar','Usuários');?> <!--MENU USUÁRIOS-->
                        <ul class="menu vertical">
                            <li><?php echo anchor('usuarios/cadastrar','Cadastar');?></li>
                            <li><?php echo anchor('usuarios/gerenciar','Gerenciar');?></li>
                        </ul>
                    </li>

                    <li><?php echo anchor('midia/gerenciar','Midia');?> <!--MENU MÍDIA-->
                        <ul class="menu vertical">
                            <li><?php echo anchor('midia/cadastrar','Cadastrar');?></li>
                            <li><?php echo anchor('midia/gerenciar','Gerenciar');?></li>
                        </ul>
                    </li>

                    <li><?php echo anchor('paginas/gerenciar','Páginas');?> <!--MENU MÍDIA-->
                        <ul class="menu vertical">
                            <li><?php echo anchor('paginas/cadastrar','Cadastrar');?></li>
                            <li><?php echo anchor('paginas/gerenciar','Gerenciar');?></li>
                        </ul>
                    </li>


                    <li>
                        <a href="">Administração</a> <!--MENU ADMINISTRAÇÃO-->
                            <ul class="menu vertical">
                                <li><?php echo anchor('auditoria/gerenciar','Auditoria');?></li>
                            </ul>
                    </li>
                </ul>
            </div>
        </div>

    <?php endif; ?>
    <div class="row paineladm">
        {conteudo}
    </div>
    {footerinc}
    <!--<script src="js/foundation.min.js"></script>-->
    <div class="row rodape">
        <div class="columns medium-6 columns medium-centered">
            {rodape}
        </div>
    </div>

    </body>
</hmtl>