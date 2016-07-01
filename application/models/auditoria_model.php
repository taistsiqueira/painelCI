<?php if ( ! defined("BASEPATH")) exit("No direct script access allowe");

class Auditoria_model extends CI_Model{

    public function do_insert($dados=NULL, $redir=FALSE){ //redir=false pq ele ñ vai ter redirecionamento auto
        if($dados != NULL): // se dados for diferente de nulo
            $this->db->insert('auditoria', $dados); //vai pegar na tabela os dados que vieram pra ca
            if ($this->db->affected_rows()>0): //se o num de linhas for >0 seta msg... senã ...seta outra msg
                set_msg('msgok', 'Cadastro efetuado com sucesso', 'sucesso');
            else:
                set_msg('msgerro','Erro ao inserir dados','erro');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    public function get_byid($id=NULL){
        if ($id != NULL):
            $this->db->where('id', $id);
            $this->db->limit(1);
            return $this->db->get('auditoria');
        else:
            return FALSE;
        endif;
    }


    public function get_all($limit=NULL){ //pegar todos os registros do BD
        if ($limit  > 0) $this->db->limit($limit);
        return $this->db->get('auditoria'); //retorna todos os registros da tabela usuarios
    }
}

/*End of file auditoria_model.php*/
/*Location: ./application/models/auditoria_model.php*/