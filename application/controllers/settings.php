<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        init_painel();//esta carregando as libraries, helpers e vai setar o padrão da variavel tema
        esta_logado();
        $this->load->model('settings_model', 'settings');//segundo é apelido
    }

    public function index()
    {
        $this->gerenciar();
    }


    public function gerenciar(){
        if ($this->input->post('salvardados')):
            if (is_admin(TRUE)): //verifica se é adm
                $settings = elements(array('nome_site', 'url_logomarca', 'email_adm'), $this->input->post());
                foreach ($settings as $nome_config => $valor_config):
                    set_settings($nome_config, $valor_config); //p cada item chama a função, manda o valor e o nome dela
                endforeach;
                set_msg('msgok', 'Configurações atualizadas com sucesso','sucesso');
                redirect('settings/gerenciar');
            else:
                redirect('settings/gerenciar');
            endif;
        endif;

        set_tema('titulo', 'Configuração do sistema' );
        set_tema('conteudo', load_modulo('settings', 'gerenciar'));
        load_template();
    }

  }




/*End of file settings.php */
/*Location: ./application/controllers/settings.php */