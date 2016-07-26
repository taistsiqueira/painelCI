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
                    anchor("paginas/excluir/$linha->id",' ',array('class'=>'table-action table-delete deletareg','title'=>'Excluir'))
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
        $idpagina = $this->uri->segment(3);
        if($idpagina == NULL)
            //set_msg('msgerro','Escolha uma página para alterar', 'erro');
            //redirect('paginas/gerenciar');
            return;
        echo '<div class="row">';//cria uma linha
        echo '<div class="colums medium-6 medium-centered">';
            $query = $this->paginas->get_byid($idpagina)->row();
            echo breadcrumb();
            erros_validacao();
            get_msg('msgok');
            get_msg('msgerro');
            echo form_open(current_url(), array('class'=>'custom')); //multipart devido as midias
            echo form_fieldset('Alterar página',array('class' => 'fieldset'));
            echo form_label('Título');
            echo form_input(array('name'=>'titulo', 'class'=>'medium-12'), set_value('titulo', $query->titulo), 'autofocus');
            echo form_label('Slug (deixe em branco se não souber do que se trata)');
            echo form_input(array('name'=>'slug', 'class'=>'medium-12'), set_value('slug', $query->slug));

            echo '<p>'.anchor('#', 'Inserir imagens', 'class="addimg button tiny radius"');
            echo anchor('midia/cadastrar', 'Upload de imagens', 'target="_blank" class="button tiny secondary radius"').'</p>';

            echo form_label('Conteúdo');
            echo form_textarea(array('name'=>'conteudo', 'class'=>'medium-12 htmleditor', 'rows'=>20), set_value('conteudo', to_html($query->conteudo)));

            echo '<div class="colums medium-6">';
            echo form_submit(array('name'=>'editar', 'class'=>'button radius'), 'Salvar dados');
            echo anchor('paginas/gerenciar', 'Cancelar', array('class'=>'button radius right alert espaco'));
            echo form_hidden('idpagina', $query->id);
            echo '</div>';
            echo '</div>';
            echo form_fieldset_close();
            echo form_close();

            incluir_arquivo('insertimg'/*, 'includes', TRUE*/);
        break;

    default:
        echo '<div class="Alert-box alert"><p>A tela solicitada não existe</p></div>';
        break;
endswitch;