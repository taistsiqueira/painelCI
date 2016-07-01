<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Auditoria extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        init_painel();//esta carregando as libraries, helpers e vai setar o padrão da variavel tema
        esta_logado();
        $this->load->model('auditoria_model','auditoria');//segundo é apelido
    }

    public function index()
    {
        $this->load->view('nomeview');
    }

    public function gerenciar()
    {
        set_tema('footerinc', load_js(array('data-table', 'table')), FALSE);//
        set_tema('titulo', 'Registros de auditoria');
        set_tema('conteudo', load_modulo('auditoria', 'gerenciar'));
        load_template();
    }

}

/*End of file auditoria.php */
/*Location: ./application/controllers/auditoria.php */