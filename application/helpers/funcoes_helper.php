<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

//carrega um módulo do sistema devolvendo a tela solicitada

function load_modulo($modulo=NULL, $tela=NULL, $diretorio='painel'){
    $CI =& get_instance();
    if ($modulo!=NULL): //se o modulo for diferente de nulo, ou seja, se um módulo foi setado entao
        return $CI->load->view("$diretorio/$modulo", array('tela'=>$tela),TRUE); //carrego o modulo usando a tela que passei na função e retornando na forma d euma variavel apra utilizar.
    else:                                                                            //TRUE para que não mostre na tela mas sim, devovla em forma de variavel.
        return FALSE;
    endif;
}

