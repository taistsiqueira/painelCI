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
            });
        </script>
        <div class="column row">
            <?php
            echo breadcrumb();
            get_msg('msgok');
            get_msg('msgerro');

            $modo = $this->uri->segment(3);
            if ($modo=='all'):
                $limite = 0;
            else:
                $limite = 50;
                echo '<p>Mostrando os últimos 50 registros, para ver todo o histórico '.anchor('auditoria/gerenciar/all', 'clique aqui').'</p>';
            endif;

            ?>

        <table class="data-table">
            <thead>
            <tr>
                <th>Usuário</th>
                <th>Data/Hora</th>
                <th>Operação</th>
                <th>Observação</th>
            </tr>
            <tbody>
            <?php
            $query= $this->auditoria->get_all($limite)->result();//limite de 50 registros
            foreach ($query as $linha){
                echo '<tr>';
                printf('<td>%s</td>',$linha->usuario);
                printf('<td>%s</td>',date('d/m/Y H:i:s', strtotime($linha->data_hora)));
                printf('<td>%s</td>','<span class="has-tip tip-top" title="'.$linha->query.'">'.$linha->operacao.'</span>'); //configurado span para visualziar o SQL executado
                printf('<td>%s</td>',$linha->observacao);
                echo '</tr>';
            }
            ?>
            </tbody>
            </thead>
        </table>
        </div>
        <?php
        break;
    default:
        echo '<div class="Alert-box alert"><p>A tela solicitada não existe</p></div>';
        break;
endswitch;