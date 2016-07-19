<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

//carrega um módulo do sistema devolvendo a tela solicitada

    function load_modulo($modulo=NULL, $tela=NULL, $diretorio='painel'){
        $CI =& get_instance();
        if ($modulo != NULL): //se o modulo for diferente de nulo, ou seja, se um módulo foi setado entao
            return $CI->load->view("$diretorio/$modulo", array('tela' => $tela), TRUE); //carrego o modulo usando a tela que passei na função e retornando na forma d euma variavel apra utilizar.
        else:                                                                            //TRUE para que não mostre na tela mas sim, devovla em forma de variavel.
            return FALSE;
        endif;
    }

//seta valores ao array $tema da classe sistema
    function set_tema($prop, $valor, $replace=TRUE){ //replace é para quando deseja substituir atquivo, ou nao.
        $CI =& get_instance();
        $CI->load->library('sistema');
        if ($replace):
            $CI->sistema->tema[$prop] = $valor;
        else:
            if(!isset($CI->sistema->tema[$prop])) $CI->sistema->tema[$prop] = '';
            $CI->sistema->tema[$prop] .= $valor; // .= se ñ estiver definida uma propriedade, cria uma vazia e agrega o valor
                                                 // se tiver o valor, so acrescenta o valor q esta sendo mandado pela minha função
        endif;
    }

//retorna os valores do array $tema da classse sistema
    function get_tema(){
        $CI =& get_instance();
        $CI->load->library('sistema');
        return $CI->sistema->tema;
    }

//inicializa o painel adm carregando os recursos necessários
    function init_painel(){
        $CI =& get_instance();
        $CI->load->library(array('parser','sistema','session','form_validation'));
        $CI->load->helper(array('form','url','array','text'));
        //carregamento dos models do painel adm
        $CI->load->model('usuarios_model', 'usuarios');//nme e apelido
        $CI->load->model('midia_model', 'midia');//nme e apelido

        set_tema('titulo_padrao','Gerenciamento de Conteúdo');
        set_tema('rodape', '<p>&copy; 2016 | Todos os direitos reservados para Taís T. Siqueira');
        set_tema('template','painel_view');

        set_tema('headerinc', load_css(array('foundation.min','app')), FALSE);//cria uma variavel headrinc, carrega o css foundation.mim
        set_tema('footerinc', load_js(array('jquery-2.2.4','foundation.min','app')), FALSE);
        //set_tema('footerinc', '');
    }

//função que inicialzia o TINYMCE para a criação de textarea com editor html
    function init_htmleditor(){
        set_tema('footerinc', load_js(base_url('htmleditor/tiny_mce.js'), null, true), false );
        set_tema('footerinc', load_js(base_url('htmleditor/init_tiny_mce.js'), null, true), false );

    }

//retorna ou printa o conteudo de uma view
//function incluir_arquivo($view, $pasta='includes', $echo=TRUE){
//    $CI =& get_instance();
//    if ($echo == TRUE):
//        echo $CI->load->view("$pasta/$view",'',TRUE);
//        return TRUE;
//    endif;
//    return $CI->load->view("$pasta/$view",'',TRUE);
//}



//carrega um template passando o array $tema como parametro
    function load_template(){
        $CI =& get_instance();
        $CI->load->library('sistema');
        $CI->parser->parse($CI->sistema->tema['template'], get_tema());
    }

//função que carrega um ou vários arquivos .css de uma pasta
    function load_css($arquivo=NULL, $pasta='css', $media='all'){
        if ($arquivo != NULL):
            $CI =& get_instance();
            $CI->load->helper('url');
            $retorno = '';
            if (is_array($arquivo)): //se a variavel arquivo for um array, serão varios noems de arquivos p serem carregados
                foreach ($arquivo as $css) { //para cada arquivo destes
                    $retorno .= '<link rel="stylesheet" type="text/css" href="' . base_url("$pasta/$css.css") . '" media="' . $media . '" />'; //faz o retorno
                }
            else:
                $retorno .= '<link rel="stylesheet" type="text/css" href="'.base_url("$pasta/$arquivo.css").'" media="'.$media.'" />'; //faz o retorno
            endif;
        endif;
        return $retorno;
    }

//função que carrega um ou vários arquivos .js de uma pasta ou servidor remoto
    function load_js($arquivo=NULL, $pasta='js', $remoto=FALSE){
        if ($arquivo != NULL):
            $CI =& get_instance();
            $CI->load->helper('url');
            $retorno = '';
            if (is_array($arquivo)): //se a variavel arquivo for um array, serão varios noems de arquivos p serem carregados
                foreach ($arquivo as $js):
                    if ($remoto):
                        $retorno .= '<script type="text/javascript" src="'.$js.'"></script>';
                    else:
                        $retorno .= '<script type="text/javascript" src="'.base_url("$pasta/$js.js").'"></script>';
                    endif;
                endforeach;
            else:
                if ($remoto):
                    $retorno .= '<script type="text/javascript" src="'.$arquivo.'"></script>';
                else:
                    $retorno .= '<script type="text/javascript" src="'.base_url("$pasta/$arquivo.js").'"></script>';
                endif;
            endif;
        endif;
        return $retorno;
    }

// função que mostra erros de validação em forms
function erros_validacao(){
    if (validation_errors()) echo '<div class="alert callout">'.validation_errors('<p>','</p>').'</div>';
}

//função que verifica se o usuário está logado no sistema
//definir se vai redirecionar p outra pg ou nao
    function esta_logado($redir=TRUE){
        $CI =& get_instance();
        $CI->load->library('session');
        $user_status = $CI->session->userdata('user_logado');
        if (!isset($user_status) | $user_status!=TRUE):
           // $CI->session->sess_destroy();
            if ($redir):
                $CI->session->set_userdata(array('redir_para'=>current_url()));
                set_msg('errologin', 'Acesso restrito, faça login antes de prosseguir', 'erro');
                redirect('usuarios/login');
            else:
                return FALSE;
            endif;
        else:
            return TRUE;
        endif;
    }


//define uma mensagem para ser exibida na próxima tela carregada
    function set_msg($id='msgerro', $msg=NULL, $tipo='erro')
    {
        $CI =& get_instance();
        switch ($tipo):
            case 'erro':
                $CI->session->set_flashdata($id, '<div class="alert callout"><p>'.$msg.'</p></div>');
                //$CI->session->set_flashdata($id, '<div class="alert-box alert"><p>'.$msg.'</p></div>');
                break;
            case 'success':
                $CI->session->set_flashdata($id, '<div class="callout success"><p>'.$msg.'</p></div>');
                //$CI->session->set_flashdata($id, '<div data-alert class="alert-box success"><p>'.$msg.'</p></div>');
                break;
            default:
                $CI->session->set_flashdata($id, '<div class="callout success"><p>'.$msg.'</p></div>');
                //$CI->session->set_flashdata($id, '<div class="alert-box"><p>'.$msg.'</p></div>');
                break;
        endswitch;
    }

//verifica se existe uma mensagem para ser exibida na tela atual
    function get_msg($id, $printar=TRUE){
        $CI =& get_instance();
        if ($CI->session->flashdata($id)):
            if($printar):
                echo $CI->session->flashdata($id);
                return TRUE;
            else:
                return $CI->session->flashdata($id);
            endif;
        endif;
        return FALSE;
    }

//verifica se o usuário atual é adm
    function is_admin($set_msg=FALSE){
        $CI =& get_instance();
        $user_admin = $CI->session->userdata('user_admin');//admin
        if(!isset($user_admin) || !$user_admin) {
            if ($set_msg) {
                set_msg('msgerro', 'Seu usuário não tem permissão para executar esta operação' , 'erro');
            }
            return FALSE;
        }
            return TRUE;
    }

//gera um breadcrumb com base no controller atual

    function breadcrumb(){
        $CI =& get_instance();
        $CI->load->helper('url');
        $classe = ucfirst($CI->router->class);//ucfirts() :função p deixar a primeira letra da classe em maiusculo
        if ($classe ==  'Painel'):
            $classe = anchor($CI->router->class, 'Início');
        else:
            $classe = anchor($CI->router->class, $classe);
        endif;
        $metodo = ucwords(str_replace('_', ' ' , $CI->router->method)); //ucwords: para deixar a primeira letra de cada palavra em maisuculo
        if ($metodo && $metodo != 'Index'):
            $metodo = " &raquo; ".anchor($CI->router->class."/".$CI->router->method, $metodo);
        else:
            $metodo = '';
        endif;
        return '<p>Sua localização: '.anchor('painel', 'Painel').' &raquo; '.$classe.$metodo.'</p>';
    }

//função que seta um registro na tabela de auditoria

    function auditoria($operacao, $obs='', $query=TRUE){ //QUERY=TRUE , vai epgar sempre a ultima query setada no banco
        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->model('auditoria_model', 'auditoria');
        if($query):
            $last_query = $CI->db->last_query();
        else:
            $last_query = '';
        endif;

        if (esta_logado(FALSE)): //SE ESTIVER LOGADO
            $user_id = $CI->session->userdata('user_id');
            $user_login = $CI->usuarios->get_byid($user_id)->row()->login;
        else:
            $user_login = 'Desconhecido';
        endif;

        $dados = array(
            'usuario' => $user_login,
            'operacao' => $operacao,
            'query' => $last_query,
            'observacao' => $obs,
        );
        $CI->auditoria->do_insert($dados);
    }

    //gera uma miniatura de uma img caso ela ainda ñ exita.
    function thumb($imagem=NULL, $largura=100, $altura=75, $geratag=TRUE){
        $CI =& get_instance();
        $CI->load->helper('file');
        $thumb = $largura.'x'.$altura.'_'.$imagem;
        $thumbinfo = get_file_info('./uploads/thumbs/'.$thumb);
        if($thumbinfo!=FALSE):
            $retorno = base_url('uploads/thumbs/'.$thumb);
        else:
            $CI->load->library('image_lib');
            $config['image_library'] = 'gd2';
            $config['source_image'] = './uploads/'.$imagem;
            $config['new_image'] = './uploads/thumbs/'.$thumb;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $largura;
            $config['height'] = $altura;
            $CI->image_lib->initialize($config);
            if ($CI->image_lib->resize()):
                $CI->image_lib->clear();
                $retorno = base_url('uploads/thumbs/'.$thumb);
            else:
                $retorno = FALSE;
            endif;

        endif;
        if ($geratag && $retorno != FALSE) $retorno = '<img src="'.$retorno.'" alt="" />';
        return $retorno;
    }

//gera um slug baseado no título
    function slug($string=NULL){
        $string = remove_acentos($string); //remover os acentos
        return url_title($string, '-', TRUE); //faz ficar tudo em letra minuscula
    }

//função que remove acentos e caracteres especiais de uma string
    function remove_acentos($string=NULL){
        $procurar = array('À','Á','Ã','Â','É','Ê','Í','Ó','Õ','Ô','Ú','Ü','Ç','à','á','ã','â','é','ê','í','ó','õ','ô','ú','ü','ç');
        $substituir = array('A','A','A','A','E','E','I','O','O','O','U','U','C','a','a','a','a','e','e','i','o','o','o','o','u','u','c');
        return str_replace($procurar,$substituir ,$string );
    }

// função que gera o resumo de uma string
    function resumo_post($string=NULL, $palavras=50, $decodifica_html=TRUE, $remove_tags=TRUE){
        if ($string!=NULL):
            if ($decodifica_html) $string = html_entity_decode($string);
            if ($remove_tags) $string = strip_tags($string);
            $retorno = word_limiter($string, $palavras);
        else:
            $retorno = FALSE;
        endif;
        return $retorno;

    }





