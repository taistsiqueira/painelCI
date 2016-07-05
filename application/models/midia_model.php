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
}

/*End of file midia_model.php */
/*Location: ./application/controllers/midia_model.php */