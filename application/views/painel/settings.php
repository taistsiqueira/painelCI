<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
switch ($tela):

    case 'gerenciar':

        echo'<div class="row">';
        echo '<div class="colums medium-6 medium-centered">';
        echo breadcrumb();
        erros_validacao();
        get_msg('msgok');
        get_msg('msgerro');
        echo form_open('settings/gerenciar', array('class'=>'custom'));
            echo form_fieldset('Configuração do sistema',array('class' => 'fieldset'));
                echo form_label('Nome do Site');
                echo form_input(array('name'=>'nome_site', 'class'=>'medium-12'), set_value('nome_site', get_setting('nome_site')), 'autofocus');
                echo form_label('URL da logomarca');
                echo form_input(array('name'=>'url_logomarca', 'class'=>'medium-12'), set_value('url_logomarca',get_setting('url_logomarca')));
                echo form_label('Email do Administrador');
                echo form_input(array('name'=>'email_adm', 'class'=>'medium-12'), set_value('email_adm', get_setting('email_adm')));
                echo form_submit(array('name'=>'salvardados', 'class'=>'button radius'), 'Salvar Configuração');
            echo form_fieldset_close();
        echo form_close();
        echo '</div>';
        echo '</div>';
        break;

    default:
        echo '<div class="Alert-box alert"><p>A tela solicitada não existe</p></div>';
        break;
endswitch;