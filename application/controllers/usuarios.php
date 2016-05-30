<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Usuarios extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->library('sistema');
    }

    public function index(){
        $this->load->view('nomeview');
    }
    
    public function login(){
        //carregar o módulo usuários e mostrar a tela de login
        $tema['titulo'] = 'Login';
        $tema['conteudo'] = load_modulo('usuarios', 'login');//recebe o valor da funçõao loa_modulo, carregamos o modulo usauros e a tela login
        $this->load->view('painel_view', $tema);
    }
}

/*End of file usuarios.php */
/*Location: ./application/controllers/usuarios.php */