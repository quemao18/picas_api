<?php

//debemos colocar esta línea para extender de REST_Controller
require(APPPATH.'/libraries/REST_Controller.php');
header('Access-Control-Allow-Origin: *');
class Picas extends REST_Controller
{
    //con esto limitamos las consultas y los permisos a la api
    protected $methods = array(
        'picas_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'pica_id_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'nueva_post' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        //'new_pica_post' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
    );

   
    //obtener pica por id
    //picas/pica_id/id/id_pica/X-API-KEY/miapikey
    public function pica_id_get()
    {
        if(!$this->get("id_pica")){
            $this->response(NULL, 400);
        }
        $this->load->model("picas_model");
        $picas = $this->picas_model->pica_id($this->get("id_pica"));
        if($posts){
            $this->response($posts, 200);
        }else{
            $this->response(NULL, 400);
        }
    }

    //obtener picas
    //picas/picas/X-API-KEY/miapikey
    public function picas_get()
    {
        $this->load->model("picas_model");
        $picas = $this->picas_model->picas();
        if($picas){
            $this->response($picas, 200);
        }else{
            $this->response(NULL, 400);
        }
    }


     public function get_enum_values_get()
    {
        $this->load->model("picas_model");
        $picas = $this->picas_model->get_enum_values($this->get("tabla"), $this->get("columna"));
        if($picas){
            $this->response($picas, 200);
        }else{
            $this->response(NULL, 400);
        }
    }

    //crear un nueva pica
    //picas/nueva/X-API-KEY/miapikey
    public function nueva_post()
    {
            if($this->post("pica")){
            $this->load->model("picas_model");
            $new = $this->picas_model->nueva(json_decode($this->post("pica")));
            if($new === false){
               $this->response(array("status" => "failed", "message" => "La pica ya existe..."));
            }else{
               $this->response(array("status" => "success", "message" => "Pica creada..."));
            }
        }
    }

    //actualizar pica
    //picas/actualizar/X-API-KEY/miapikey
    public function actualizar_post()
    {
        $this->load->model("picas_model");
        $result = $this->picas_model->actualizar(json_decode($this->post("pica")));

        if($result === false){
           $this->response(array("status" => "failed", "message" => "Error actualizando..."));
        }else{
            $this->response(array("status" => "success", "message" => "Pica actualizada..."));
        }
    }

  

}