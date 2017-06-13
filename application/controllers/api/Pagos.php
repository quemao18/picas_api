<?php

//debemos colocar esta línea para extender de REST_Controller
require(APPPATH.'/libraries/REST_Controller.php');
header('Access-Control-Allow-Origin: *');
class Pagos extends REST_Controller
{
    //con esto limitamos las consultas y los permisos a la api
    protected $methods = array(
        'pagos_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'pago_id_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'nuevo_post' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'actualizar_post' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
    );

   
    //obtener usuario por id
    //usuarios/usuario_id/id/id_usuario/X-API-KEY/miapikey
    public function pago_id_get()
    {
        if(!$this->get("id")){
            $this->response(NULL, 400);
        }
        $this->load->model("pagos_model");
        $pago = $this->pagos_model->pago_id($this->get("id"));
        if($pago){
            $this->response($pago, 200);
        }else{
            $this->response(NULL, 400);
        }
    }

    //obtener picas
    //usuarios/usuarios/X-API-KEY/miapikey
    public function pagos_get()
    {
        $this->load->model("pagos_model");
        $pagos = $this->pagos_model->pagos();
        if($pagos){
            $this->response($pagos, REST_Controller::HTTP_OK);
        }else{
            $this->response(array(
                "status" => FALSE,
                "message" => "Error..."
            ),REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function bancos_get()
    {
        $this->load->model("pagos_model");
        $bancos = $this->pagos_model->bancos();
        if($bancos){
            $this->response($bancos, REST_Controller::HTTP_OK);
        }else{
            $this->response(array(
                "status" => FALSE,
                "message" => "Error..."
            ),REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //crear un nueva pica
    //usuarios/nuevo/X-API-KEY/miapikey
    public function nuevo_post()
    {
        if($this->post("id_usuario") && $this->post("monto")){
            $this->load->model("pagos_model");
            $new = $this->pagos_model->nuevo($this->post("id_usuario"),$this->post("monto"));
            if($new === false){
                $this->response(array("status" => "failed"));
            }else{
                $this->response(array("status" => "success"));
            }
        }
    }

    //actualizar pica
    //usuarios/actualizar/X-API-KEY/miapikey
    public function actualizar_post()
    {
        $this->load->model("pagos_model");
        $result = $this->pagos_model->actualizar(json_decode($this->post("estado")));

        if($result === false){
           $this->response(array("status" => "failed", "message" => "Error actualizando..."));
        }else{
            $this->response(array("status" => "success", "message" => "Estado actualizado..."));
        }
    }

}