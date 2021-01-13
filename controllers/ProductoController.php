<?php 

/**
 * Controlador
 */
class ProductoController extends BaseController{
	

	public function IndexAction(Request $req, Response $res, Data $data){
		
		$this->render('producto/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		$this->page = $req->get('page', 1);
		$this->limit = $req->get('count', 10);

		$this->result = TblProducto::findAndCountAll([
			'limit' => $this->limit,
			'offset' => ($this->page-1)*$this->limit,
			'order' => 'nombre asc',
		]);
	
		$this->render('producto/listado', $data);
	}

	public function FormAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_producto', 0);
		$this->producto = new TblProducto(['id_producto'=>0]);

		if($id != 0){
			$this->producto = TblProducto::findById($id);
		}
	
		$this->nuevo = $this->producto->id_producto == 0;
		$this->render('producto/formulario', $data);
	}
	
	public function GuardarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_producto', 0);
		$nombre = $req->post('nombre');
		$descripcion = $req->post('descripcion');

		$producto = new TblProducto();
		if($id != 0){
			$producto = TblProducto::findById($id);
			if($producto){
				$producto->nombre = $nombre;
				$producto->descripcion = $descripcion;
				if($producto->update()){
					$data->setSuccessMessage('El producto ha sido actualizado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar. '.$producto->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el producto');
			}
		}else{
			$producto->nombre = $nombre;
			$producto->descripcion = $descripcion;
			$producto->id_usuario = $this->user->id;
			if($producto->save()){
				$data->setSuccessMessage('El producto ha sido guardado correctamente');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar guardar el producto. '.$producto->getError());
			}
		}


		$res->json($data->forJSON());
	}

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_producto', 0);

		$producto = TblProducto::findById($id);
		if($producto){

			$uso = TblAdaProducto::findOne([
				'where' => [
					'id_producto' => $id,
				]
			]);

			if($uso){
				$data->setErrorMessage('No se puede eliminar el producto porque se encuentra en uso');
			}else{
				if($producto->delete()){
					$data->setSuccessMessage('El producto ha sido eliminado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar el producto. '.$producto->getError());
				}
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el producto solicitado');
		}

		$res->json($data->forJSON());
	}

}