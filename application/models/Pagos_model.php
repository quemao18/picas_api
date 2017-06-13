<?php

class Pagos_model extends CI_Model
{
    public function pago_id($id){
        $this->db->select("*");
        $query = $this->db->get_where("pagos", array("id_pago" => $id));
        if($query->num_rows() == 1){
            return $query->result();
        }
    }

    public function pagos(){
        $this->db->select('usuarios.id_usuario, usuarios.nombre, pagos.referencia, pagos.monto, pagos.fecha_creacion, pagos.estado, pagos.id_pago');
        $this->db->join('usuarios', 'usuarios.id_usuario=pagos.id_usuario');
        $this->db->order_by('pagos.fecha_creacion DESC');
        $query = $this->db->get("pagos");
        if($query->num_rows() > 0){
            return $query->result();
        }
    }

   public function bancos(){
        $query = $this->db->get("bancos");
        if($query->num_rows() > 0){
            return $query->result();
        }
    }


    public function nuevo($usuario, $pago){
        $data = array(
            "id_usuario" => $usuario->id_usuario,
            "monto" => $pago->monto, 
            "id_usuario_banco" => $pago->id_usuario_banco, 
            "fecha_creacion" => date('Y-m-d H:i:s'),
            "estado" => "No verificado",
            "referencia" => $pago->referencia
        );
        $query = $this->db->get_where("pagos", array("referencia" => $pago->referencia, "id_usuario" => $usuario->id_usuario));
        if($query->num_rows() == 0){
             $this->db->insert("pagos", $data);
            return true;
        }else{
            return false;
        }
       
    }

    public function actualizar($estado){
        $data = array(
            "id_pago" => $estado->id_pago,
            "estado" => $estado->estado,
            "fecha_modificacion" => date('Y-m-d H:i:s')
        );
        $query = $this->db->update('pagos', $data, array('id_pago' => $estado->id_pago));
        if($query){
            return true;
        }else{
            return false;
        }  
    }
}