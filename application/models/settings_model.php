<?php if ( ! defined("BASEPATH")) exit("No direct script access allowe");

class Settings_model extends CI_Model{

    public function do_insert($dados=NULL, $redir=TRUE){
        if($dados != NULL):
            $this->db->insert('settings', $dados);
            if ($this->db->affected_rows()>0):
                auditoria('Inclusão de configuração','Nova configuração cadastrada no sistema');
                set_msg('msgok', 'Cadastro efetuado com sucesso', 'sucesso');
            else:
                set_msg('msgerro','Erro ao inserir dados','erro');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    public function do_update($dados=NULL, $condicao=NULL, $redir=TRUE){
        if ($dados != NULL && is_array($condicao)):
            $this->db->update('settings', $dados, $condicao);
            if ($this->db->affected_rows()>0):
                auditoria('Alteração de configuração','A configuração para o campo "'.$condicao['nome_config'].'"foi alterada');
                set_msg('msgok', 'Alteração efetuada com sucesso', 'sucesso');

            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    public function do_delete($condicao=NULL, $redir=TRUE){
        if ($condicao !=NULL && is_array($condicao)):
            $this->db->delete('settings', $condicao);
            if ($this->db->affected_rows()>0):
                set_msg('msgok', 'Registro excluído com sucesso', 'sucesso');
                auditoria('Exclusão de configuração','A configuração do campo "'.$condicao['nome_config'].'" foi excluída');
            else:
                set_msg('msgerro', 'Erro ao excluir registro', 'erro');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }


    public function get_all(){ //pegar todos os registros do BD
        return $this->db->get('settings'); //retorna todos os registros da tabela usuarios
    }

    public function get_bynome($nome=NULL){
        if ($nome != NULL):
            $this->db->where('nome_config', $nome);
            $this->db->limit(1);
            return $this->db->get('settings');
        else:
            return FALSE;
        endif;
    }
}

/*End of file settings_model.php */
/*Location: ./application/controllers/settings_model.php */