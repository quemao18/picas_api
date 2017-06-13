<?php

class Picas_model extends CI_Model
{
    public function pica_id($id){
        $this->db->select("*");
        $query = $this->db->get_where("picas", array("id_pica" => $id));
        if($query->num_rows() == 1){
            return $query->row();
        }
    }

    public function picas(){
        $query = $this->db->get("picas");
        if($query->num_rows() > 0){
            return $query->result();
        }
    }

   

    public function nueva($pica){
        $data = array(
            //"id_pica" => $pica->id_pica,
            "nombre" => $pica->nombre, 
            "estado" => $pica->estado, 
            "fecha_creacion" => date('Y-m-d H:i:s'),
            "dificultad" =>  $pica->dificultad,
        );
        $query = $this->db->get_where("picas", array("nombre" => $pica->nombre));
        if($query->num_rows() == 0){
             $this->db->insert("picas", $data);
            return true;
        }else{
            return false;
        }
       
    }

        public function actualizar($pica){
        $data = array(
            "id_pica" => $pica->id_pica,
            "nombre" => $pica->nombre, 
            "estado" => $pica->estado, 
            "fecha_modificacion" => date('Y-m-d H:i:s'),
            "dificultad" =>  $pica->dificultad,
        );
       //$query = $this->db->where(("id_pica" , $pica->id_pica));
        $query = $this->db->update('picas', $data, array('id_pica' => $pica->id_pica));
        if($query){
            return true;
        }else{
            return false;
        }
       
    }

    public function estado($id_pica,$estado){
        $data = array(
            "id_pica" => $id_pica,
            "estado" => $estado,
            "fecha_modificacion" => date('Y-m-d H:i:s')
        );
        $this->db->where("id_pica", $id_pica);
        return $this->db->update("picas", $data);  
    }

        public function dificultad($id_pica,$dificultad){
        $data = array(
            "id_pica" => $id_pica,
            "dificultad" => $dificultad,
            "fecha_modificacion" => date('Y-m-d H:i:s')
        );
        $this->db->where("id_pica", $id_pica);
        return $this->db->update("picas", $data);  
    }

    
    function get_enum_values( $table, $field )
    {
        $type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }
}