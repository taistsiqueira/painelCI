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

        set_tema('titulo_padrao','Gerenciamento de Conteúdo');
        set_tema('rodape', '<p>&copy; 2016 | Todos os direitos reservados para Taís T. Siqueira');
        set_tema('template','painel_view');
    }

//carrega um template passando o array $tema como parametro
    function load_template(){
        $CI =& get_instance();
        $CI->load->library('sistema');
        $CI->parser->parse($CI->sistema->tema['template'], get_tema());
    }
