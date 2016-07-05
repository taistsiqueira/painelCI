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
  }




/*End of file midia.php */
/*Location: ./application/controllers/midia.php */