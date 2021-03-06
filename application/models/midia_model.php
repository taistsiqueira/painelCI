<?php if ( ! defined("BASEPATH")) exit("No direct script access allowe");

class Midia_model extends CI_Model{

    public function do_insert($dados=NULL, $redir=TRUE){
        if($dados != NULL):
            $this->db->insert('midia', $dados);
            if ($this->db->affected_rows()>0):
                auditoria('Inclusão de midia','Nova mídia cadastrada no sistema');
                set_msg('msgok', 'Cadastro efetuado com sucesso', 'sucesso');
            else:
                set_msg('msgerro','Erro ao inserir dados','erro');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    public function do_update($dados=NULL, $condicao=NULL, $redir=TRUE){
        if ($dados != NULL && is_array($condicao)):
            $this->db->update('midia', $dados, $condicao);
            if ($this->db->affected_rows()>0):
                auditoria('Alteração de mídia','A mídia com o id "'.$condicao['id'].'"foi alterada');
                set_msg('msgok', 'Alteração efetuada com sucesso', 'sucesso');
            else:
                set_msg('msgerro', 'Erro ao atualizar dados', 'erro');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    public function do_delete($condicao=NULL, $redir=TRUE){
        if ($condicao !=NULL && is_array($condicao)):
            $this->db->delete('midia', $condicao);
            if ($this->db->affected_rows()>0):
                set_msg('msgok', 'Registro excluído com sucesso', 'sucesso');
                auditoria('Exclusão de mídia','A midia com o id "'.$condicao['id'].'" foi excluída');
            else:
                set_msg('msgerro', 'Erro ao excluir registro', 'erro');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }


    public function do_upload($campo){
        $config['upload_path'] = './uploads';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config); //library do codeigniter p fazer upload
        if($this->upload->do_upload($campo)):
            return $this->upload->data();
        else:
            return $this->upload->display_errors();
        endif;
    }

    public function get_all(){ //pegar todos os registros do BD
        return $this->db->get('midia'); //retorna todos os registros da tabela usuarios
    }

    public function get_byid($id=NULL){
        if ($id != NULL):
            $this->db->where('id', $id);
            $this->db->limit(1);
            return $this->db->get('midia');
        else:
            return FALSE;
        endif;
    }
}

/*End of file midia_model.php */
/*Location: ./application/controllers/midia_model.php */