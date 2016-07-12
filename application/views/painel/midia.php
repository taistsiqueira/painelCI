<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
switch ($tela):
    case 'cadastrar':
        echo'<div class="row">';
        echo '<div class="colums medium-6 medium-centered">';
        echo breadcrumb();
        erros_validacao();
        get_msg('msgok');
        get_msg('msgerro');
        echo form_open_multipart('midia/cadastrar', array('class'=>'custom')); //multipart devido as midias
        echo form_fieldset('Upload de Mídia',array('class' => 'fieldset'));
        echo form_label('Nome para exibição');
        echo form_input(array('name'=>'nome', 'class'=>'medium-12'), set_value('nome'), 'autofocus');
        echo form_label('Descrição');
        echo form_input(array('name'=>'descricao', 'class'=>'medium-12'), set_value('descricao'));
        echo form_label('Arquivo');
        echo form_upload(array('name'=>'arquivo', 'class'=>'medium-12'), set_value('arquivo'));

        echo form_submit(array('name'=>'cadastrar', 'class'=>'button radius'), 'Salvar Dados');
        echo anchor('midia/gerenciar', 'Cancelar', array('class'=>'button radius right alert espaco'));

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
                $('input').click(function(){
                    (this).select();
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
                <th>Link</th>
                <th>Miniatura</th>
                <th class="text-center">Ações</th>
            </tr>
            <tbody>
            <?php
            $query = $this->midia->get_all()->result();//limite de 50 registros
            foreach ($query as $linha){
                echo '<tr>';
                printf('<td>%s</td>',$linha->nome);
                printf('<td><input type="text" value="%s" /></td>', base_url("uploads/$linha->arquivo"));
                printf('<td>%s</td>',thumb($linha->arquivo)); //configurado span para visualziar o SQL executado
                printf('<td class="text-center">%s%s%s</td>',
                    anchor("uploads/$linha->arquivo",' ',array('class'=>'table-action table-view','title'=>'Visualizar', 'target'=>'_blank')), //blank abre uma guia nova qdo visualziar
                    anchor("midia/editar/$linha->id",' ',array('class'=>'table-action table-edit','title'=>'Editar')),
                    anchor("midia/excluir/$linha->id",' ',array('class'=>'table-action table-delete deletareg','title'=>'Excluir'))
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

    case 'editar':
        $idmidia = $this->uri->segment(3);
        if($idmidia == NULL)
            //set_msg('msgerro','Escolha uma mídia para alterar', 'erro');
            //redirect('midia/gerenciar');
            return;
        echo '<div class="row">';//cria uma linha
        echo '<div class="colums medium-6 medium-centered">';
        echo breadcrumb();
            $query = $this->midia->get_byid($idmidia)->row();
            erros_validacao();
            get_msg('msgok');
            echo form_open(current_url(), array('class'=>'custom')); //multipart devido as midias
            echo form_fieldset('Alteração de Mídia',array('class' => 'fieldset'));

            echo form_label('Nome para exibição');
            echo form_input(array('name'=>'nome', 'class'=>'medium-12'), set_value('nome', $query->nome), 'autofocus');
            echo form_label('Descrição');
            echo form_input(array('name'=>'descricao', 'class'=>'medium-12'), set_value('descricao', $query->descricao));

            echo '<div class="colums medium-6">';
            echo form_submit(array('name'=>'editar', 'class'=>'button radius'), 'Salvar Dados');
            echo anchor('midia/gerenciar', 'Cancelar', array('class'=>'button radius right alert espaco'));
            echo form_hidden('idmidia', $query->id);
            echo '</div>';

            echo '<br>';
            echo '<div class="columns medium-6 medium-centered">';
            echo thumb($query->arquivo, 300, 180);
            echo '</div>';

            echo form_fieldset_close();
            echo form_close();
         break;

    default:
        echo '<div class="Alert-box alert"><p>A tela solicitada não existe</p></div>';
        break;
endswitch;