<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Midia extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        init_painel();//esta carregando as libraries, helpers e vai setar o padrão da variavel tema
        esta_logado();
        //$this->load->model('midia_model', 'midia');//segundo é apelido
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function cadastrar()
    {
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|ucfirst');
        $this->form_validation->set_rules('descricao', 'DESCRICAO', 'trim');
        if ($this->form_validation->run()==TRUE):
            $upload = $this->midia->do_upload('arquivo');
            if (is_array($upload) && $upload['file_name']!= ''):
                $dados = elements(array('nome', 'descricao'), $this->input->post());
                $dados['arquivo'] = $upload['file_name'];
                $this->midia->do_insert($dados);
            else:
                set_msg('msgerro', $upload, 'erro');
                redirect(current_url());
            endif;

        endif;
        set_tema('titulo', 'Upload de Imagens');
        set_tema('conteudo', load_modulo('midia','cadastrar'));
        load_template();
    }

    public function gerenciar(){
        set_tema('footerinc', load_js('data-table','table'), FALSE);
        set_tema('titulo', 'Listagem de mídias' );
        set_tema('conteudo', load_modulo('midia', 'gerenciar'));
        load_template();
    }

    public function editar()
    {
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|ucfirst');
        $this->form_validation->set_rules('descricao', 'DESCRICAO', 'trim');
        if ($this->form_validation->run()):
            $dados = elements(array('nome', 'descricao'), $this->input->post());
            $this->midia->do_update($dados, array('id' =>$this->input->post('idmidia')));
        endif;
        set_tema('titulo', 'Alteração de mídia');
        set_tema('conteudo', load_modulo('midia', 'editar'));//alterar do adm
        load_template();
    }

    public function excluir(){
           if(is_admin(TRUE)):
                $idmidia = $this->uri->segment(3);
                if ($idmidia != NULL):
                    $query = $this->midia->get_byid($idmidia);
                    if ($query->num_rows()==1): //se o numero de linha for igual a 1 então:
                        $query = $query->row();//para retornar o registro queveio do BD
                        unlink('./uploads/'.$query->arquivo); //unlink: função do php responsavel por deletar arquivos // exclui o arquivo da pasta UPLOADS em c:\xampp\htdocs\painelci\uploads
                        $thumbs = glob('./uploads/thumbs/*_'.$query->arquivo);//excluir todas as miniaturas desta imagem
                        foreach ($thumbs as $arquivo):
                            unlink($arquivo);
                        endforeach;
                        $this->midia->do_delete(array('id'=>$query->id), FALSE); //deleta as imgs do banco de dados
                    endif;
                else:
                    set_msg('msgerro', 'Escolha uma mídia para excluir', 'erro');
                endif;
            endif;
            redirect('midia/gerenciar');
    }
    
  }




/*End of file midia.php */
/*Location: ./application/controllers/midia.php */