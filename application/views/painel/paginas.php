<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
switch ($tela):
    case 'cadastrar':
        echo'<div class="row">';
        echo '<div class="colums medium-12 medium-centered">';
        echo breadcrumb();
        erros_validacao();
        get_msg('msgok');
        get_msg('msgerro');
        echo form_open('paginas/cadastrar', array('class'=>'custom')); //multipart devido as midias
        echo form_fieldset('Cadastrar nova página',array('class' => 'fieldset'));
        echo form_label('Título da Página');
        echo form_input(array('name'=>'titulo', 'class'=>'medium-12'), set_value('titulo'), 'autofocus');
        echo form_label('Slug (deixe em branco se não souber do que se trata)');
        echo form_input(array('name'=>'slug', 'class'=>'medium-12'), set_value('slug'));
        echo form_label('Conteúdo');
        echo form_textarea(array('name'=>'conteudo', 'class'=>'medium-12 htmleditor', 'rows'=>20), set_value('conteudo'));
        echo form_submit(array('name'=>'cadastrar', 'class'=>'button radius'), 'Publicar Página');
        echo anchor('paginas/gerenciar', 'Cancelar', array('class'=>'button radius right alert espaco'));

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
                <th>Título</th>
                <th>Slug</th>
                <th>Resumo</th>
                <th class="text-center">Ações</th>
            </tr>
            <tbody>
            <?php
            $query = $this->paginas->get_all()->result();//limite de 50 registros
            foreach ($query as $linha){
                echo '<tr>';
                printf('<td>%s</td>',$linha->titulo);
                printf('<td>%s</td>',$linha->slug);
                printf('<td>%s</td>',resumo_post($linha->conteudo, 8)); //mostra 10 palavras
                printf('<td class="text-center">%s%s</td>',
                    anchor("paginas/editar/$linha->id",' ',array('class'=>'table-action table-edit','title'=>'Editar')),
                    anchor("pagians/excluir/$linha->id",' ',array('class'=>'table-action table-delete deletareg','title'=>'Excluir'))
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