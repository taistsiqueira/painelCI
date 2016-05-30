<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Sistema
{

    //A propriedade CI recebe a isntancia do code igniter que esta sendo executada,

    protected $CI;
    public $tema = array();//setar as propreiedades do meu tema

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('funcoes'); //utilizar o CI para carregar o helper funções
    }

}
/*End of file sistema.php */
/*Location: ./application/libraries/sistema.php */