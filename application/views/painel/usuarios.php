<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
switch ($tela):
    case 'login':
        echo'<div class="row">';
        echo '<div class="colums medium-6 medium-centered">';
        echo form_open('usuarios/login', array('class'=>'custom loginform'));
        echo form_fieldset('Identifique-se',array('class' => 'fieldset'));
        erros_validacao();
        get_msg('logoffok');
        get_msg('errologin');
        echo form_label('Usuário');
        echo form_input(array('name'=>'usuario'), set_value('usuario'), 'autofocus');
        echo form_label('Senha');
        echo form_password(array('name'=>'senha'),set_value('senha'));
        echo form_hidden('redirect', $this->session->userdata('redir_para'));
        echo '<div class="row">';
        echo '<div class="columns medium-6">';
        echo '<p>'.anchor('usuarios/nova_senha', 'Esqueci minha senha').'<p>';
        echo '</div>';

        echo '<div class="columns medium-6 text-right">';
        echo form_submit(array('name'=>'logar','class'=>'button radius right'), 'Login');
        echo '</div>';
        echo '</div>';
        echo form_fieldset_close();
        echo form_close();
        echo '</div>';
        echo '</div>';
        break;
        break;

    case 'nova_senha':
        echo'<div class="row">';
        echo '<div class="colums medium-6 medium-centered">';
        echo form_open('usuarios/nova_senha', array('class'=>'custom loginform'));
        echo form_fieldset('Recuperação de senha',array('class' => 'fieldset'));
        get_msg('msgok');
        get_msg('msgerro');
        erros_validacao();
        echo form_label('Seu email:');
        echo form_input(array('name'=>'email'), set_value('email'), 'autofocus');

        echo'<div class="row">';
        echo '<div class="columns medium-6">';
        echo '<p>'.anchor('usuarios/login', 'Fazer login').'<p>';
        echo '</div>';

        echo '<div class="columns medium-6 text-right">';
        echo form_submit(array('name'=>'novasenha','class'=>'button radius right'), 'Enviar nova senha');
        echo form_fieldset_close();
        echo form_close();
        echo '</div>';
        echo '</div>';
        break;

    case 'cadastrar':
        echo'<div class="row">';
        echo '<div class="colums medium-6 medium-centered">';
        echo breadcrumb();
        erros_validacao();
        get_msg('msgok');
        get_msg('msgerro');
        echo form_open('usuarios/cadastrar', array('class'=>'custom'));
        echo form_fieldset('Cadastrar novo usuario',array('class' => 'fieldset'));
        echo form_label('Nome completo');
        echo form_input(array('name'=>'nome', 'class'=>'medium-12'), set_value('nome'), 'autofocus');
        echo form_label('Email');
        echo form_input(array('name'=>'email', 'class'=>'medium-12'), set_value('email'));
        echo form_label('Login');
        echo form_input(array('name'=>'login', 'class'=>'medium-12'), set_value('login'));

        echo '<div class="columns medium-6 noleft">';
        echo form_label('Senha');
        echo form_password(array('name'=>'senha'),set_value('senha'));
        echo '</div>';

        echo '<div class="columns medium-6 noright">';
        echo form_label('Repita a senha');
        echo form_password(array('name'=>'senha2'),set_value('senha2'));
        echo '</div>';
        echo form_checkbox(array('name'=>'adm'), '1').' Dar poderes administrativos a este usuário<br /><br />';
        echo form_submit(array('name'=>'cadastrar', 'class'=>'button radius'), 'Salvar Dados');
        echo anchor('usuarios/gerenciar', 'Cancelar', array('class'=>'button radius right alert espaco'));

        echo form_fieldset_close();
        echo form_close();
        echo '</div>';
        echo '</div>';
        break;

    case 'gerenciar':
        ?>
        <script type="text/javascript">
            $(function () {
               $('.deletareg').click(function() {
                   if (confirm("Deseja realmente excluir este registro?\nEsta operação não poderá ser desfeita!"))return true; else return false;
               });
            });
        </script>
        <div class="column row">
            <?php
            echo breadcrumb();
            get_msg('msgok');
            get_msg('msgerro');
            ?>
        <table class="data-table">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Login</th>
                <th>Email</th>
                <th>Ativo/Adm</th>
                <th class="text-center">Ações</th>
            </tr>
            <tbody>
            <?php
            $query= $this->usuarios->get_all()->result();
            foreach ($query as $linha){
                echo '<tr>';
                printf('<td>%s</td>',$linha->nome);
                printf('<td>%s</td>',$linha->login);
                printf('<td>%s</td>',$linha->email);
                printf('<td>%s / %s</td>',$linha->ativo==0 ? 'Não' : 'Sim',$linha->adm==0 ? 'Não' : 'Sim');
                printf('<td class="text-center">%s%s%s</td>',
                    anchor("usuarios/editar/$linha->id",' ',array('class'=>'table-action table-edit','title'=>'Editar')),
                    anchor("usuarios/alterar_senha/$linha->id",' ',array('class'=>'table-action table-pass','title'=>'Alterar senha')),
                    anchor("usuarios/excluir/$linha->id",' ',array('class'=>'table-action table-delete deletareg','title'=>'Excluir'))
                );
                echo '</tr>';
            }
            ?>
            </tbody>
            </thead>
        </table>
        </div>
        <?php
        break;
    case 'alterar_senha':
        $iduser = $this->uri->segment(3);
        if($iduser == NULL)
            return;
        echo '<div class="row">';
        echo breadcrumb();
        if( is_admin(true) || $iduser == $this->session->userdata('user_id')) {
            $query = $this->usuarios->get_byid($iduser)->row();
            erros_validacao();
            get_msg('msgok');
            echo form_open(current_url(), array('class' => 'custom'));
            echo form_fieldset('Alterar senha', array('class' => 'fieldset'));
            echo form_label('Nome completo');
            echo form_input(array('name' => 'nome', 'disabled' => 'disabled'), set_value('nome', $query->nome));
            echo form_label('E-mail');
            echo form_input(array('name' => 'email', 'disabled' => 'disabled'), set_value('email',$query->email));
            echo form_label('Login');
            echo form_input(array('name' => 'login', 'disabled' => 'disabled'), set_value('login',$query->login));
            echo '<div class="columns medium-6 noleft">';
            echo form_label('Nova Senha');
            echo form_password(array('name' => 'senha'), set_value('senha'),'autofocus');
            echo '</div>';
            echo '<div class="columns medium-6 noright">';
            echo form_label('Repita a senha');
            echo form_password(array('name' => 'senha2'), set_value('senha2'));
            echo '</div>';
            echo form_submit(array('name' => 'alterarsenha', 'class' => 'button radius'), 'Salvar dados');
            echo anchor('usuarios/gerenciar', 'Cancelar', array('class' => 'button radius right alert espaco'));
            echo form_hidden('idusuario',$iduser);
            echo form_fieldset_close();
            echo form_close();
        }
        else{
            set_msg('msgerro', 'Seu usuário não tem permissão para executar esta operação' , 'erro');
            redirect('usuarios/gerenciar');
        }
        break;

    case 'editar':
        $iduser = $this->uri->segment(3);
        if($iduser == NULL)
            return;
        echo '<div class="row">';
        echo breadcrumb();
        if( is_admin(true) || $iduser == $this->session->userdata('user_id')) {
            $query = $this->usuarios->get_byid($iduser)->row();
            erros_validacao();
            get_msg('msgok');
            echo form_open(current_url(), array('class' => 'custom'));
            echo form_fieldset('Alterar usuário', array('class' => 'fieldset'));
            echo form_label('Nome completo');
            echo form_input(array('name' => 'nome'), set_value('nome', $query->nome),'autofocus');
            echo form_label('E-mail');
            echo form_input(array('name' => 'email', 'disabled' => 'disabled'), set_value('email',$query->email));
            echo form_label('Login');
            echo form_input(array('name' => 'login', 'disabled' => 'disabled'), set_value('login',$query->login));
            echo '<div class="columns medium-6 noleft">';
            echo form_checkbox(array('name'=>'ativo'), '1', ($query->ativo==1) ? TRUE : FALSE).'Permitir o acesso deste usuário ao sistema<br /><br />';
            echo form_checkbox(array('name'=>'adm'), '1', ($query->adm==1) ? TRUE : FALSE).' Dar poderes administrativos a este usuário<br /><br />';
            echo '</div>';
            echo form_submit(array('name' => 'editar', 'class' => 'button radius'), 'Salvar dados');
            echo anchor('usuarios/gerenciar', 'Cancelar', array('class' => 'button radius right alert espaco'));
            echo form_hidden('idusuario',$iduser);
            echo form_fieldset_close();
            echo form_close();
        }
        else{
            set_msg('msgerro', 'Seu usuário não tem permissão para executar esta operação' , 'erro');
            redirect('usuarios/gerenciar');
        }
        break;

    default:
        echo '<div class="Alert-box alert"><p>A tela solicitada não existe</p></div>';
        break;
endswitch;