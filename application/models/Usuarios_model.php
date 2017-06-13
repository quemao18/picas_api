<?php

class Usuarios_model extends CI_Model
{
    public function usuario_id($id_usuario){
        $this->db->select("*");
        //$this->db->join('bancos', 'bancos.id_usuario=usuarios.id_usuario');
        $query = $this->db->get_where("usuarios", array("usuarios.id_usuario" => $id_usuario));
        if($query->num_rows() == 1){
            return $query->row();
        }
    }

public function usuarios_bancos()
    {
        $this->db->select('usuarios.id_usuario, usuarios.nombre, bancos.nombre as banco');
        $this->db->join('bancos', 'bancos.id_usuario=usuarios.id_usuario');
        $query = $this->db->get("usuarios");
       if($query->num_rows() > 0){
            return $query->result();
        }
    }

    function get_enum_values( $table, $field )
    {
        $type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    public function nuevo($usuario){
   //$this->db->select("*");
     $data = array(
            "id_usuario" => $usuario->id_usuario,
            "nombre" => $usuario->nombre,
            "email" => $usuario->email,
            "telefono" => $usuario->telefono, 
            "fecha_creacion" => date('Y-m-d H:i:s'),
            "tipo" =>  "Piloto"
        );
    $query = $this->db->get_where("usuarios", array("id_usuario" => $usuario->id_usuario));
    if($query->num_rows() == 0){
        $this->db->insert("usuarios", $data);
        return true;
    }else{
        return false;
    }
}

    public function usuarios()
    {
        //$this->db->join('bancos', 'bancos.id_usuario=usuarios.id_usuario');
        $query = $this->db->get("usuarios");
       if($query->num_rows() > 0){
            return $query->result();
        }
    }

}