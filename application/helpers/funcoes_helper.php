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

        set_tema('titulo_padrao','Gerenciamento de Conteúdo');
        set_tema('rodape', '<p>&copy; 2016 | Todos os direitos reservados para Taís T. Siqueira');
        set_tema('template','painel_view');

        set_tema('headerinc', load_css(array('foundation.min','app')), FALSE);//cria uma variavel headrinc, carrega o css foundation.mim
        set_tema('footerinc', load_js(array('foundation.min')), FALSE);
    }

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














