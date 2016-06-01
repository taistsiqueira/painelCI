<?php if ( ! defined("BASEPATH")) exit("No direct script access allowe");

    class Usuarios_model extends CI_Model{
        public function do_login($usuario=NULL, $senha=NULL){
            if ($usuario && $senha):
                $this->db->where('login', $usuario);
                $this->db->where('senha', $senha);
                $this->db->where('ativo', 1);
                $query = $this->db->get('usuarios');
                if ($query->num_rows == 1):// se teve 1 linha retornada do banco de dados
                    return TRUE;
                else:
                    return FALSE;
                endif;
            else:
                return FALSE;
            endif;
        }
    }

/*End of file usuarios_model.php*/
/*Location: ./application/models/usuarios_model.php*/