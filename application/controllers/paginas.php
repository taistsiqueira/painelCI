<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Paginas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        init_painel();//esta carregando as libraries, helpers e vai setar o padrão da variavel tema
        esta_logado();
        $this->load->model('paginas_model', 'paginas');//segundo é apelido
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function cadastrar()
    {
        $this->form_validation->set_rules('titulo','TITULO', 'trim|required|ucfirst');
        $this->form_validation->set_rules('slug','SLUG','trim');
        $this->form_validation->set_rules('conteudo','CONTEUDO','trim|required|htmlentities');
        if ($this->form_validation->run()==TRUE):
            $dados = elements(array('titulo', 'slug','conteudo'), $this->input->post());
                ($dados['slug'] != '') ? $dados['slug']=slug($dados['slug']) : $dados['slug']=slug($dados['titulo']);
                $this->paginas->do_insert($dados);
        endif;
        init_htmleditor();
        set_tema('titulo', 'Cadastrar nova página');
        set_tema('conteudo', load_modulo('paginas','cadastrar'));
        load_template();
    }

   public function gerenciar(){
        set_tema('footerinc', load_js('data-table','table'), FALSE);
        set_tema('titulo', 'Páginas' );
        set_tema('conteudo', load_modulo('paginas', 'gerenciar'));
        load_template();
    }
}

/*End of file paginas.php */
/*Location: ./application/controllers/paginas.php */