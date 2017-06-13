<?php

//debemos colocar esta línea para extender de REST_Controller
require(APPPATH.'/libraries/REST_Controller.php');
header('Access-Control-Allow-Origin: *');
class Usuarios extends REST_Controller
{
    //con esto limitamos las consultas y los permisos a la api
    protected $methods = array(
        'usuarios_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'nuevo_post' =>array('level' => 0),
        'usuario_id_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'usuario_post' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'new_usuario_post' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
        'nombre_rif_get' => array('level' => 0),//para acceder a users_get debe tener level 1 y no hay limite de consultas por hora
    );

public function nombre_rif_get(){
		
		$this->load->library('rif');
		
		$rif    = $this->get("rif");	
		$nombre_cne='';
		$nombre = '';
		
		if(substr($rif, 0, 1)=='V' || substr($rif, 0, 1)=='E')
		{
			$ced= 'http://www.cne.gob.ve/web/registro_civil/buscar_rep.php?nac=&ced='.substr($rif, 1);
			//$ced= 'http://www.cne.gob.ve/web/registro_civil/buscar_rep.php?nac=&ced='.substr($rif, 1);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ced);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$result = curl_exec ($ch);
			$nombre_cne = getBetween($result, '<b>', '</b>');
		}/*else{
			$ced= 'http://contribuyente.seniat.gob.ve/getContribuyente/getrif?rif='.substr($rif, 0);
			//echo $ced;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ced);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$result = curl_exec ($ch);
			$nombre_cne = getBetween($result, '(', ')');
			//echo $nombre_rif_;
		}		*/
		
		//$rif = new Rif($usuario);
		$this->rif->setRif($rif);
		 
		// Obtener los datos fiscales
		$datosFiscales = json_decode($this->rif->getInfo());
		//echo $datosFiscales->code_result;
		//
		//var_dump($datosFiscales);
		if($datosFiscales->code_result==1){
			//$nombre=utf8_decode(ucwords(strtolower($datosFiscales->seniat->nombre)));
			
				$nombre=utf8_decode((($datosFiscales->seniat->nombre)));
				$nombre = getBetween((($nombre)), '(', ')');
			
			$this->response(array(
					'status' => TRUE,
					'nombre' => $nombre
			), REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			//echo getBetween((($nombre)), '(', ')');
		}
		elseif($nombre_cne!=''){
			$nombre = $nombre_cne;
			$this->response(array(
					'status' => TRUE,
					'nombre' => $nombre
			), REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
		else {
			$this->response(array(
					'status' => FALSE,
					'nombre' => '',
					'message' => 'Rif o Cedula no encontrado...'
			), REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
		//echo $usuario;
	
	}
   
    //obtener usuario por id
    //usuarios/usuario_id/id/id_usuario/X-API-KEY/miapikey
    public function usuario_id_get()
    {
        if(!$this->get("id")){
            $this->response(NULL, 400);
        }
        $this->load->model("usuarios_model");
        
        $usuarios = $this->usuarios_model->usuario_id($this->get("id"));
        if($usuarios){
            $this->response($usuarios, REST_Controller::HTTP_OK);
        }else{
            $this->response(array(
                "status" => FALSE,
                "message" => "Usuario no encontrado..."
            ),REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //obtener picas
    //usuarios/usuarios/X-API-KEY/miapikey
    public function usuarios_get()
    {
        $this->load->model("usuarios_model");
        $usuarios = $this->usuarios_model->usuarios();
        if($usuarios){
            $this->response($usuarios, 200);
        }else{
            $this->response(NULL, 400);
        }
    }


 public function usuarios_bancos_get()
    {
        $this->load->model("usuarios_model");
        $usuarios = $this->usuarios_model->usuarios_bancos();
        if($usuarios){
            $this->response($usuarios, 200);
        }else{
            $this->response(NULL, 400);
        }
    }
    //crear un nueva pica
    //usuarios/nuevo/X-API-KEY/miapikey
    public function nuevo_post()
    {   

        if($this->post("usuario")){
            $this->load->model("usuarios_model");
            $new = $this->usuarios_model->nuevo(json_decode($this->post("usuario")));
            /*if($new === false){
                $this->response(array("status" => "exist"));
            }else{
                $this->response(array("status" => "success"));
            }*/

             $this->load->model("pagos_model");
                 $new = $this->pagos_model->nuevo(json_decode($this->post("usuario")), json_decode($this->post("pago")) );
                 if($new === false)
                  $this->response(array("status" => "failed", "message" => "Referencia ya Existe registrada"));
                  else
                  $this->response(array("status" => "success", "message" => "Aporte registrado..."));
        }
    }

    //actualizar pica
    //usuarios/actualizar/X-API-KEY/miapikey
    public function actualizar_post()
    {
        $this->load->model("usuarios_model");
        $result = $this->usuarios_model->actualizar($this->post("nombre"), array(
            "name"      =>      $this->post("nombre"),
            "email"     =>      $this->post("estado")
        ));

        if($result === false){
            $this->response(array("status" => "failed"));
        }else{
            $this->response(array("status" => "success"));
        }
    }

}