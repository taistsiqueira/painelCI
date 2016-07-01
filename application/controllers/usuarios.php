<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Usuarios extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->library('sistema');
        init_painel();//esta carregando as libraries, helpers e vai setar o padrão da variavel tema
    }

    public function index()
    {
        $this->load->view('nomeview');
    }

    public function login()
    {
        //carregar o módulo usuários e mostrar a tela de login
        $this->form_validation->set_rules('usuario', 'USUÁRIO', 'trim|required|min_length[4]|strtolower');
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required|min_length[4]|strtolower');
        if ($this->form_validation->run()):
            $usuario = $this->input->post('usuario', TRUE);
            $senha = md5($this->input->post('senha', TRUE));
            $redirect = $this->input->post('redirect');
            if ($this->usuarios->do_login($usuario, $senha) == TRUE):
                $query = $this->usuarios->get_bylogin($usuario)->row();
                $dados = array(
                    'user_id' => $query->id,
                    'user_nome' => $query->nome,
                    'user_admin' => $query->adm,
                    'user_logado' => TRUE,
                );
                $this->session->set_userdata($dados);
                auditoria('Login no sistema', 'Login efetuado com sucesso');
                redirect('painel');
            else:
                $query = $this->usuarios->get_bylogin($usuario)->row();
                if (empty($query)):
                    set_msg('errologin', 'Usuario inexistente', 'erro');
                elseif ($query->senha != $senha): //verifica a senha
                    set_msg('errologin', 'Senha incorreta', 'erro');
                elseif ($query->ativo == 0):
                    set_msg('errologin', 'Este usuário está inativo', 'erro');
                else:
                    set_msg('errologin', 'Erro desconhecido, contate p desenvolvedor', 'erro');
                endif;
                redirect('usuarios/login');
            endif;
        endif;
        set_tema('titulo', 'Login');
        set_tema('conteudo', load_modulo('usuarios', 'login'));//$tema['conteudo'] = load_modulo('usuarios', 'login');//recebe o valor da funçõao loa_modulo, carregamos o modulo usauros e a tela login
        set_tema('rodape', '');
        load_template();
    }


    public function logoff()
    {
        auditoria('Logoff no sistema', 'Logoff efetuado com sucesso');
        $this->session->set_userdata(array(
            'user_id' => '',
            'user_nome' => '',
            'user_admin' => '',
            'user_logado' => ''
        ));//seta toda a minha sessão com valores vazios
        //$this->session->sess_destroy();//destrói a sessão. //nao utilizado devido modificações de versão
        set_msg('logoffok', 'Logoff efetuado com sucesso!', 'success'); //VERIFICAR, NÃO ESTA VERDE

        redirect('usuarios/login');//retorna para a tela usuarios
    }


    public function nova_senha()
    {
        //carregar o módulo usuários e mostrar a tela de login
        $this->form_validation->set_rules('email', 'EMAIL', 'trim|required|valid_email|strtolower');
        if ($this->form_validation->run() == TRUE):
            $email = $this->input->post('email');
            $query = $this->usuarios->get_byemail($email);
            if ($query->num_rows() == 1):
                $novasenha = substr(str_shuffle('qwertyuiopasdfghjklzxcvbnm0123456789'), 0, 6);//gera senhas de seis digitos
                $mensagem = "<p>Você solicitou uma nova senha para acesso ao painel de Administração do site, a partir de agora use a seguinte senha para acesso: <strong>$novasenha</strong></p><p>Troque essa senha para uma senha segura e de sua preferência o quanto antes. </p>";
                if ($this->sistema->enviar_email($email, 'Nova senha de acesso', $mensagem)):
                    $dados['senha'] = md5($novasenha);
                    $this->usuarios->do_update($dados, array('email' => $email), FALSE);
                    auditoria('Redifinição de senha', 'O usuário solicitou uma nova senha por email');
                    set_msg('msgok', 'Uma nova senha foi enviada para seu email', 'sucesso');
                    redirect('usuarios/nova_senha');
                else:
                    set_msg('msgerro', 'Erro ao enviar nova senha, contato o administrado', 'erro');
                    redirect('usuarios/nova_senha');
                endif;
            else:
                set_msg('msgerro', 'Este email não possui cadastro no sistesma', 'erro');
                redirect('usuarios/nova_senha');
            endif;
        endif;

        set_tema('titulo', 'Recuperar senhas');
        set_tema('conteudo', load_modulo('usuarios', 'nova_senha'));//$tema['conteudo'] = load_modulo('usuarios', 'login');//recebe o valor da funçõao loa_modulo, carregamos o modulo usauros e a tela login
        set_tema('rodape', '');
        load_template();
    }

    public function cadastrar()
    {
        esta_logado();
        $this->form_validation->set_message('is_unique', 'Este %s já está cadastrado no sistema');
        $this->form_validation->set_message('matches', 'O campo %s está diferente do campo %s');
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|min_length[4]|ucwords');
        $this->form_validation->set_rules('email', 'EMAIL', 'trim|required|valid_email|is_unique[usuarios.email]|strtolower');
        $this->form_validation->set_rules('login', 'LOGIN', 'trim|required|min_length[4]|is_unique[usuarios.login]|strtolower');
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required|min_length[4]|strtolower');
        $this->form_validation->set_rules('senha2', 'REPITA SENHA', 'trim|required|min_length[4]|strtolower|matches[senha]');

        if ($this->form_validation->run()):
            $dados = elements(array('nome', 'email', 'login'), $this->input->post());
            $dados['senha'] = md5($this->input->post('senha'));
            if (is_admin()) $dados['adm'] = ($this->input->post('adm') == 1) ? 1 : 0;//se marcar adm na tela, vale 1, senao vale 0.
            $this->usuarios->do_insert($dados);
        endif;
        set_tema('titulo', 'Cadastro de usuários');
        set_tema('conteudo', load_modulo('usuarios', 'cadastrar'));
        load_template();
    }


    public function gerenciar()
    {
        esta_logado();
        set_tema('footerinc', load_js(array('data-table', 'table')), FALSE);//
        set_tema('titulo', 'Listagem de Usuários');
        set_tema('conteudo', load_modulo('usuarios', 'gerenciar'));
        load_template();
    }


    public function alterar_senha()
    {
        esta_logado();
        $this->form_validation->set_message('matches', 'O campo %s est? diferente do campo %s');
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required|min_length[4]|strtolower');
        $this->form_validation->set_rules('senha2', 'REPITA SENHA', 'trim|required|min_length[4]|strtolower|matches[senha]');
        if ($this->form_validation->run()):
            $dados['senha'] = md5($this->input->post('senha'));
            $this->usuarios->do_update($dados, array('id' =>$this->input->post('idusuario')));
        endif;
        set_tema('titulo', 'Alteração de senha');
        set_tema('conteudo', load_modulo('usuarios', 'alterar_senha'));//alterar do adm
        load_template();
    }


    public function editar()
    {
        esta_logado();
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|min_length[4]|ucwords');
        if ($this->form_validation->run()):
            $dados['nome'] = ($this->input->post('nome'));
            $dados['ativo'] = ($this->input->post('ativo')==1 ? 1 : 0);
            if (is_admin()) $dados['adm'] = ($this->input->post('adm') == 1) ? 1 : 0;//se marcar adm na tela, vale 1, senao vale 0.
            $this->usuarios->do_update($dados, array('id' =>$this->input->post('idusuario')));
        endif;
        set_tema('titulo', 'Alteração de usuários');
        set_tema('conteudo', load_modulo('usuarios', 'editar'));//alterar do adm
        load_template();
    }


    public function excluir(){
        esta_logado();
        if(is_admin(TRUE)):
            $iduser = $this->uri->segment(3);
            if ($iduser != NULL):
                $query = $this->usuarios->get_byid($iduser);
                if ($query->num_rows()==1): //se o numero de linha for igual a 1 então:
                    $query = $query->row();//para retornar o registro queveio do BD
                        if ($query->id != 1):
                            $this->usuarios->do_delete(array('id'=>$query->id), FALSE);
                        else:
                            set_msg('msgerro', 'Este usuário não pode ser excluído','erro');
                        endif;
                else:
                    set_msg('msgerro', 'Usuário não encontrado para exclusão','erro');
                endif;
            else:
                set_msg('msgerro', 'Escolha  um usuário para excluir', 'erro');
            endif;
        endif;
        redirect('usuarios/gerenciar');
    }
}

/*End of file usuarios.php */
/*Location: ./application/controllers/usuarios.php */