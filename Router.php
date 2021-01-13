<?php 

class Router {

	const FOLDER_VISTAS = "views/";
	const MODO_DEBUG = true;
	
	public static function getDespreciable(){
		return PREF_DESP;
	}

	const RUTAS = [
		'root' => [
			'ruta' => '','accion' => 'IndexController::IndexAction','titulo' => 'Asistente competencias','metodos' => ['get']
		],
		'institucion' => ['ruta' => 'institucion', 'accion' => 'IndexController::InstitucionAction', 'titulo' => 'Institución', 'metodos' => ['get']],
		'asistente'	=> [
			'ruta' => 'asistente','accion' => 'AsistenteController::IndexAction','titulo' => 'Asistente competencias','metodos' => ['get'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fase1' => [ 
			'ruta' => 'asistente/fase1-datos-basicos', 'ajax' => true, 'accion' => 'AsistenteController::FormularioFase1Action', 'metodos' => ['get'],
			'permiso' => 'asistente_leer',
		],
		'asistente.guardar_fase1' => [ 
			'ruta' => 'asistente/guardar-fase1', 'json' => true, 'accion' => 'AsistenteController::GuardarFase1Action', 'metodos' => ['post'], 'parametros' => ['id_pe','id_asignatura_pe','descripcion'],
			'permiso' => 'asistente_escribir',
		],
		'asistente.fase2' => [ 
			'ruta' => 'asistente/fase2-edicion-competencia', 'ajax' => true, 'accion' => 'AsistenteController::FormularioFase2Action', 'metodos' => ['get'], 'parametros' => ['id_competencia'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fase2_elementos' => [ 
			'ruta' => 'asistente/elementos-fase2', 'json' => true, 'accion' => 'AsistenteController::ElementosCompetenciaAction', 'metodos' => ['get'], 'parametros'=>['id_competencia'],
			'permiso' => 'asistente_leer',
		],
		'asistente.guardar_fase2' => [ 
			'ruta' => 'asistente/guardar-fase2', 'json' => true, 'accion' => 'AsistenteController::GuardarFase2Action', 'metodos' => ['post'], 'parametros' => ['id_competencia','competencia_editable'],
			'permiso' => 'asistente_escribir',
		],
		'asistente.fase3' => [ 
			'ruta' => 'asistente/fase3-desglosar-componentes', 'ajax' => true, 'accion' => 'AsistenteController::FormularioFase3Action', 'metodos' => ['get'], 'parametros' => ['id_competencia'],
			'permiso' => 'asistente_leer',
		],
		'asistente.guardar_fase3' => [ 
			'ruta' => 'asistente/guardar-fase3', 'json' => true, 'accion' => 'AsistenteController::GuardarFase3Action', 'metodos' => ['post'], 'parametros' => ['id_competencia'],
			'permiso' => 'asistente_escribir',
		],
		'asistente.fase4' => [ 
			'ruta' => 'asistente/fase4-seleccionar-comp-genericas', 'ajax' => true, 'accion' => 'AsistenteController::FormularioFase4Action', 'metodos' => ['get'], 'parametros' => ['id_competencia'],
			'permiso' => 'asistente_leer',
		],
		'asistente.guardar_fase4' =>[
			'ruta' => 'asistente/fase4-guardar-cgs', 'json' =>true,'accion' => 'AsistenteController::GuardarFase4Action', 'metodos'=>['post'],
			'permiso' => 'asistente_escribir',
		],
		'asistente.fase3_conocimientos' => [ 
			'ruta' => 'f3-conocimientos', 'json' => true, 'accion' => 'AsistenteController::ConocimientosCompetenciaAction', 'metodos' => ['get'], 'parametros' => ['id_competencia'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fase3_habilidades' => [ 
			'ruta' => 'f3-habilidades', 'json' => true, 'accion' => 'AsistenteController::HabilidadesCompetenciaAction', 'metodos' => ['get'], 'parametros' => ['id_competencia'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fase3_avs' => [ 
			'ruta' => 'f3-avs', 'json' => true, 'accion' => 'AsistenteController::AVsCompetenciaAction', 'metodos' => ['get'], 'parametros' => ['id_competencia'],
			'permiso' => 'asistente_leer',
		],
		'login' => [ 
			'ruta' => 'login', 'accion' => 'IndexController::LoginAction', 'titulo' => 'Login Usuario', 'metodos' => ['get','post'], 'parametros' => ['post' => ['username','contrasenia']],
		],
		'logout' => [ 
			'ruta' => 'logout','accion' => 'IndexController::LogoutAction','titulo' => 'Cerrar sesión','metodos' => ['get']
		],
		// ruta para verbos
		'verbo' => [ 
			'ruta' 	=> 'verbos', 'json'	=> true, 'ajax'  => true, 'accion' => 'VerboController::ObtenerTodosAction', 'metodos' => ['get'],
			'permiso' => 'verbo_leer',
		],
		'verbo.nuevo' => [ 
			'ruta' 	=> 'nuevo-verbo', 'json'  => true, 'accion' => 'VerboController::NuevoVerboAction', 'metodos' => ['get','post'], 'parametros' => ['post' => ['nombre_verbo','tipo_saber_verbo']],
			'permiso' => 'verbo_escribir',
		],
		'verbo.editar' => [ 
			'ruta' 	=> 'editar-verbo', 'json'  => true, 'accion' => 'VerboController::EditarVerboAction', 'metodos' => ['get','post'], 'parametros' => ['get'=>['id_verbo'],'post' => ['id_verbo','nombre_verbo','tipo_saber_verbo']],
			'permiso' => 'verbo_escribir',
		],
		'verbo.eliminar' => [
			'ruta' => 'eliminar-verbo', 'json' => true, 'accion' => 'VerboController::EliminarAction','metodo'=>'post',
			'permiso' => 'verbo_eliminar'
		],
		'verbo.index' => [
			'ruta' => 'verbos/todos','accion' => 'VerboController::IndexAction','titulo' => 'Verbos','metodos' => ['get'],
			'permiso' => 'verbo_leer',
		],
		'verbo.listado' => [
			'ruta' => 'verbos/todos/listado','ajax' => true,'accion' => 'VerboController::ListadoAction','titulo' => 'Verbos','metodos' => ['get'],
			'permiso' => 'verbo_leer',
		],
		// ruta para contenidos
		'contenido'     => [
			'ruta' 	=> 'contenidos','json'	=> true,'ajax'  => true,'accion' => 'ContenidoController::ObtenerTodosAction','metodos' => ['get'],
			'permiso' => 'contenido_leer',
		],
		'contenido.nuevo'     => [
			'ruta' 	=> 'nuevo-contenido','ajax'  => true,'accion' => 'ContenidoController::NuevoContenidoAction','metodos' => ['get', 'post'],'parametros' => ['post' => ['nombre_contenido']],
			'permiso' => 'contenido_escribir',
		],
		'contenido.crear' => [
			'ruta' => 'crear-contenido','json' => true, 'accion' => 'ContenidoController::CrearContenidoAction', 'metodos' => ['post'],
		],
		'contenido.editar' => [
			'ruta' => 'editar-contenido','ajax' => true,'accion' => 'ContenidoController::EditarContenidoAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_contenido'],'post'=>['id_contenido','nombre_contenido']],
			'permiso' => 'contenido_escribir',
		],
		'contenido.index' => [
			'ruta' => 'contenidos/todos','accion' => 'ContenidoController::IndexAction','titulo' => 'Contenido','metodos' => ['get'],
			'permiso' => 'contenido_leer',
		],
		'contenido.listado' => [
			'ruta' => 'contenidos/todos/listado','ajax' => true,'accion' => 'ContenidoController::ListadoAction','metodos' => ['get'],
			'permiso' => 'contenido_leer',
		],
		'contenido.eliminar' => [
			'ruta' => 'contenido/eliminar', 'json' => true, 'accion' => 'ContenidoController::EliminarAction', 'metodo'=>'post'
		],
		// ruta para contextos
		'contexto'     => [
			'ruta' 	=> 'contextos','json'	=> true,'ajax'  => true,'accion' => 'ContextoController::ObtenerTodosAction','metodos' => ['get'],
			'permiso' => 'contexto_leer',
		],
		'contexto.crear' => [
			'ruta' => 'crear-context', 'json' => true, 'accion' => 'ContextoController::CrearContextoAction', 'metodos' => ['post'],
		],
		'contexto.nuevo'     => [
			'ruta' 	=> 'nuevo-contexto','ajax'  => true,'accion' => 'ContextoController::NuevoContextoAction','metodos' => ['get', 'post'],'parametros' => ['post' => ['nombre_contexto']],
			'permiso' => 'contexto_escribir',
		],
		'contexto.editar'     => [
			'ruta' 	=> 'editar-contexto','ajax'  => true,'accion' => 'ContextoController::EditarContextoAction','metodos' => ['get', 'post'],'parametros' => ['get'=>['id_contexto'],'post' => ['id_contexto','nombre_contexto']],
			'permiso' => 'contexto_escribir',
		],
		'contexto.index' => [
			'ruta' => 'contextos/todos','accion' => 'ContextoController::IndexAction','titulo' => 'Contextos','metodos' => ['get'],
			'permiso' => 'contexto_leer',
		],
		'contexto.listado' => [
			'ruta' => 'contextos/todos/listado','ajax' => true,'accion' => 'ContextoController::ListadoAction','metodos' => ['get'],
			'permiso' => 'contexto_leer',
		],
		'contexto.eliminar' => [
			'ruta' => 'contexto/eliminar','json'=>true,'accion'=>'ContextoController::EliminarAction', 'metodo'=>'post'
		],
		// ruta para contextos
		'criterio'     => [
			'ruta' 	=> 'criterios','json'	=> true,'ajax'  => true,'accion' => 'CriterioController::ObtenerTodosAction','metodos' => ['get'],
			'permiso' => 'criterio_leer',
		],
		'criterio.crear' => [
			'ruta' => 'crear-criterio', 'json' => true, 'accion' => 'CriterioController::CrearCriterioAction', 'metodos' => ['post'],
		],
		'criterio.nuevo'     => [
			'ruta' 	=> 'nuevo-criterio','ajax'  => true,'accion' => 'CriterioController::NuevoCriterioAction','metodos' => ['get', 'post'],'parametros' => ['post' => ['nombre_criterio']],
			'permiso' => 'criterio_escribir',
		],
		'criterio.editar' => [
			'ruta' 	=> 'editar-criterio','ajax'  => true,'accion' => 'CriterioController::EditarCriterioAction','metodos' => ['get', 'post'],'parametros' => ['get'=>['id_criterio'],'post' => ['id_criterio','nombre_criterio']],
			'permiso' => 'criterio_escribir',
		],
		'criterio.index' => [
			'ruta' => 'criterios/todos','accion' => 'CriterioController::IndexAction','titulo' => 'Criterios','metodos' => ['get'],
			'permiso' => 'criterio_leer',
		],
		'criterio.listado' => [
			'ruta' => 'criterios/todos/listado','ajax' => true,'accion' => 'CriterioController::ListadoAction','metodos' => ['get'],
			'permiso' => 'criterio_leer',
		],
		'criterio.eliminar' => [
			'ruta' => 'criterio/eliminar','json'=>true,'accion'=>'CriterioController::EliminarAction','metodo'=>'post'
		],
		'usuario.index' => 	[
			'ruta' => 'usuario','titulo' => 'Usuarios','accion' => 'UsuarioController::IndexAction','metodos' => ['get'],
			'permiso' => 'usuario_leer',
		],
		'usuario.listado' => [
			'ruta' => 'usuario/todos/listado','ajax' => true,'accion' => 'UsuarioController::ListadoAction','metodos' => ['get'],
			'permiso' => 'usuario_leer',
		],
		'usuario.nuevo'  => [
			'ruta' => 'usuario/registrar','titulo' => 'Registro','accion' => 'UsuarioController::RegistroUsuarioAction','metodos' => ['get','post'],'parametros' => [ 'post' => ['nombre','username','contrasena','confirmar_contrasena','tipo_usuario']],
			'permiso' => 'usuario_escribir',
		],
		'usuario.nuevo_interno' => [
			'ruta' => 'usuario/nuevo','ajax' => true,'accion' => 'UsuarioController::RegistroUsuarioAction','metodos' => ['get','post'],'parametros' => [ 'post' => ['nombre','username','tipo_usuario','contrasena','confirmar_contrasena']],
			'permiso' => 'usuario_escribir',
		],
		'usuario.editar' => [
			'ruta' => 'usuario/editar','ajax' => true,'accion' => 'UsuarioController::EditarAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_usuario'],'post'=>['id_usuario','nombre','username','tipo_usuario']],
			'permiso' => 'usuario_escribir',
		],
		'usuario.asignatura' => [
			'ruta' => 'usuario/asignatura', 'accion' => 'UsuarioController::AsignaturaAction', 'metodo' => 'get',
		],
		'usuario.asignatura.listado' => [
			'ajax' => true,'ruta' => 'usuario/asignatura-listado','accion' => 'UsuarioController::AsignaturaListadoAction','metodos' => ['get'],
		],
		'usuario.asignatura.guardar' => [
			'json' => true, 'ruta' => 'usuario/asignatura/guardar', 'accion' => 'UsuarioController::GuardarAsignaturaAction', 'metodo' => 'post'
		],
		'usuario.asignatura.eliminar' => [
			'json' => true, 'ruta' => 'usuario/asignatura/eliminar', 'accion' => 'UsuarioController::EliminarAsignaturaAction', 'metodo' => 'post'
		],
		'usuario.validaruser' => [
			'json' => true, 'ruta' => 'usuario/validar-username', 'accion' => 'UsuarioController::ValidarUsernameAction', 'metodo' => 'get',
		],
		'actitud_valor.index' => [
			'ruta' => 'actitudes-y-valores','titulo' => 'Actitudes y valores','accion' => 'ActitudValorController::IndexAction','metodos' => ['get'],
			'permiso' => 'av_leer',
		],
		'actitud_valor.listado' => [
			'ajax' => true,'ruta' => 'actitudes-y-valores/listado','accion' => 'ActitudValorController::ListadoAction','metodos' => ['get'],
			'permiso' => 'av_leer',
		],
		'actitud_valor.nuevo' => [
			'ajax' => true,'ruta' => 'actitudes-y-valores/nuevo-av','accion' => 'ActitudValorController::NuevoAction','metodos' => ['get','post'],'parametros' => ['post'=>['descrip_actitud_valor','tipo']],
			'permiso' => 'av_escribir',
		],
		'actitud_valor.editar' => [
			'ajax' => true,'ruta' => 'actitudes-y-valores/editar-av','accion' => 'ActitudValorController::EditarAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_compo_valor'],'post'=>['id_compo_valor','descrip_actitud_valor','tipo']],
			'permiso' => 'av_escribir',
		],
		'actitud_valor.eliminar'=>[
			'ruta'=>'av/eliminar','accion'=>'ActitudValorController::DeleteAction','metodo' => 'post','json'=>true,
			'permiso' => 'av_eliminar'
		],
		'academico' => [
			'ruta' => 'configuraciones-academicas','titulo' => 'Académico','accion' => 'AcademicoController::IndexAction','metodos' => ['get'],
		],

		'unidad' => [
			'ruta' => 'unidades','titulo'=>'Unidades','accion'=>'UnidadController::IndexAction',
			'permiso' => 'unidad_leer',
		],
		'unidad.listado' => [
			'ruta' => 'unidades/listado','ajax'=>true,'accion'=>'UnidadController::ListAction',
			'permiso' => 'unidad_leer',
		],
		'unidad.formulario' => [
			'ruta' =>'unidades/formulario','ajax'=>true, 'accion' => 'UnidadController::FormAction',
			'permiso' => 'unidad_escribir',
		],
		'unidad.unidades_existentes' => [
			'ruta' => 'unidades/existentes', 'json' => true, 'accion' => 'UnidadController::UnidadesDisponiblesAction',
		], 
		'unidad.guardar' => [
			'ruta' => 'unidades/guardar','metodos' => ['post'], 'json'=>true,'accion'=>'UnidadController::SaveAction', 
				'parametros' => [ 'id_unidad_asignatura','id_asignatura','num_unidad','duracion_unidad_hp','duracion_unidad_hnp','nombre_unidad'],
				'permiso' => 'unidad_escribir',
		],
		'unidad.eliminar' => [
			'ruta' => 'unidades/eliminar', 'metodos' => ['post'], 'json' =>true,'accion'=>'UnidadController::DeleteAction',
			'permiso' => 'unidad_eliminar',
		],
		'pe' => [
			'ruta' => 'pe','accion' => 'PlanEstudioController::IndexAction','metodo' => 'get',
			'permiso' => 'pe_leer',
		],
		'pe.listado' => [
			'ruta' => 'pe/lista', 'ajax'=>true, 'accion' => 'PlanEstudioController::ListAction',
			'permiso' => 'pe_leer',
		],
		'pe.listado_usuario' => [
			'ruta' => 'configuraciones-academicas/pe/listai','json' => true,'accion' => 'PlanEstudioController::PlanesEstudioUsuarioAction','metodos' => ['get'],
			'permiso' => 'pe_leer',
		],
		'pe.nuevo' => [
			'ruta' => 'configuraciones-academicas/pe/nuevo','ajax' => true,'accion' => 'PlanEstudioController::NuevoAction','metodos' => ['get','post'],'parametros' => ['post'=>['nombre_pe']],
			'permiso' => 'pe_escribir',
		],
		'pe.editar' => [
			'ruta' => 'configuraciones-academicas/pe/editar','ajax' => true,'accion' => 'PlanEstudioController::EditarAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_pe'],'post'=>['id_pe','nombre_pe']],
			'permiso' => 'pe_escribir',
		],
		'pe.eliminar' => [
			'json' => true,'ruta' => 'configuraciones-academicas/pe/eliminar','accion' => 'PlanEstudioController::EliminarAction','metodos' => ['post'],'parametros' => ['id_pe'],
			'permiso' => 'pe_eliminar',
		],
		'pe.form_pe_asignaturas' => [
			'ruta' => 'configuraciones-academicas/pe/form-asignaturas','ajax' => true,'accion' => 'PlanEstudioController::FromPEAsignaturasAction','metodos' => ['get'],'parametros' => ['id_pe'],
			'permiso' => 'pe_leer',
		],
		'pe.asignaturas' => [
			'ruta' => 'configuraciones-academicas/pe/asignaturas','json' => true, 'accion' => 'PlanEstudioController::AsignaturasAction'
		],
		'pe.eliminar_asignatura' => [
			'ruta' => 'configuraciones-academicas/pe/eliminar-asignatura','json' => true, 'accion' => 'PlanEstudioController::EliminarAsignaturaAction', 'metodos'=>['post']
		],
		'pe.agregar_asignatura' => [
			'ruta' => 'configuraciones-academicas/pe/agregar-asignatura','json' => true, 'accion' => 'PlanEstudioController::AgregarAsignaturaAction', 'metodos'=>['post']
		],
		// 'pe.cgs' => [
		// 	'ruta' => 'configuraciones-academicas/pe/cgs','ajax' => true,'accion' => 'PlanEstudioController::CGsAction','metodos' => ['get'],'parametros' => ['id_pe'],
		// 	'permiso' => 'pe_leer',
		// ],
		// 'pe.asignar_asignatura'=> [
		// 	'ruta' => 'configuraciones-academicas/pe/asignar-asignaturas','ajax' => true,'accion' => 'PlanEstudioController::AsignarAsignaturaPeAction','metodos' => ['get','post'],
		// 	'parametros' => ['get'=>['id_pe'], 'post'=>['id_pe','id_asignatura']],
		// 	'permiso' => 'pe_escribir',
		// ],
		// 'pe.ver_asignatura'=> [
		// 	'ruta' => 'configuraciones-academicas/pe/detalles-asignatura','ajax' => true,'accion' => 'PlanEstudioController::DetallesAsignaturaPeAction','metodos' => ['get'],'parametros' => ['id_asignatura'],
		// 	'permiso' => 'pe_leer',
		// ],		
		'asignatura' => [
			'ruta' => 'asignaturas','accion' => 'AsignaturaController::IndexAction'
		],
		'asignatura.listado' => [
			'ruta' => 'asignaturas/listado','ajax' => true,'accion' => 'AsignaturaController::ListAction'
		],
		'asignatura.listado_pe' => [
			'ruta' => 'asignaturas/lista-pe','json' => true,'accion' => 'AsignaturaController::AsignaturasPeAction','metodos' => ['get'],'parametros' => ['id_pe'],
			'permiso' => 'asignatura_leer',
		],
		'asignatura.nuevo' => [
			'ruta' => 'asignaturas/nuevo','ajax' => true,'accion' => 'AsignaturaController::NuevoAction','metodos' => ['get','post'],'parametros' => [ 'post' => ['nombre_asignatura','tipo_asignatura','modalidad','num_unidades','semestre_ubicacion','horas_duracion','horas_presenciales','horas_nopresenciales','creditos','competencia_asignada','contextualizacion']],
			'permiso' => 'asignatura_escribir',
		],
		'asignatura.editar' => [
			'ruta' => 'asignaturas/editar','ajax' => true,'accion' => 'AsignaturaController::EditarAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_asignatura'], 'post' => ['id_asignatura','nombre_asignatura','tipo_asignatura','modalidad','num_unidades','semestre_ubicacion','horas_duracion','horas_presenciales','horas_nopresenciales','creditos','competencia_asignada','contextualizacion']],
			'permiso' => 'asignatura_escribir',
		],
		'asignatura.eliminar' => [
			'json' => true,'ruta' => 'asignaturas/eliminar','accion' => 'AsignaturaController::EliminarAction','metodos' => ['post'],'parametros' => ['id_asignatura'],
			'permiso' => 'asignatura_eliminar',
		],
		'asignatura.pes' => [
			'ajax' => true,'ruta' => 'asignaturas/pes','accion'=> 'AsignaturaController::PlanesEstudioAction','metodos' => ['get'],'parametros' => ['id_asignatura'],
			'permiso' => 'asignatura_leer',
		],
		'asignatura.nuevo_pe' => [
			'ajax'=>true,'ruta'=>'asignaturas/pes/nuevo','accion'=>'AsignaturaController::NuevoPEAsignaturaAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_asignatura'], 'post'=>['id_asignatura','id_pe']],
			'permiso' => 'asignatura_escribir',
		],

		// 'asignatura.editar_pe' => [
		// 	'ajax'=>true,'ruta'=>'asignaturas/pes/editar','accion'=>'AsignaturaController::EditarPEAsignaturaAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_asignatura_pe'], 'post'=>['id_asignatura_pe','id_asignatura','id_pe']],
		// 	'permiso' => 'asignatura_escribir',
		// ],
		'asignatura.cg_asignatura' => [
			'ruta' => 'asignatura/cgs-asignaturas','ajax' => true,'accion' => 'AsignaturaController::CGsAsignaturasAction','metodo' => 'get','parametros' => ['id_asignatura'],
			'permiso' => 'pe_leer',
		],
		'asignatura.agregar_cg_asignatura' => [
			'ruta' => 'asignatura/nuevo-cg-asig','json' => true,'accion' => 'AsignaturaController::AgregarCgsAsignaturaPeAction','metodo' => 'post',
			'parametros' => ['id_asignatura','id_cg'],
			'permiso' => 'pe_escribir',
		],
		'asignatura.eliminar_cg_asignatura' => [
			'ruta' => 'asignatura/eliminar-cg-asig','json' => true,'accion' => 'AsignaturaController::EliminarCgsAsignaturaPeAction','metodos' => ['post'],'parametros' => ['id_asignatura','id_cg'],
			'permiso' => 'pe_escribir',
		],
		'asignatura.cd_asignatura' => [
			'ruta' => 'asignatura/cds-asignaturas','ajax' => true,'accion' => 'AsignaturaController::CDsAsignaturasAction','metodos' => ['get'],'parametros' => ['id_asignatura_pe'],
			'permiso' => 'pe_leer',
		],
		'asignatura.cd_agregar_cd_ape' => [
			'ruta' => 'asignatura/guardar-cdape', 'json' => true, 'accion' => 'AsignaturaController::GuardarCDAPEAction','metodos' => ['post'],
			'permiso' => 'pe_escribir',
		],
		'asignatura.cd_remover_cd_ape' => [
			'ruta' => 'asignatura/remover-cdape', 'json'=>true,'metodo'=>'post','accion' => 'AsignaturaController::EliminarCDAPEAction',
			'permiso' => 'pe_escribir',
		],
		'cg'=>[
			'ruta' => 'cgs','accion' => 'CompetenciaGenericaController::IndexAction','metodo' => 'get',
			'permiso' => 'cg_leer',
		],
		'cg.listado'=>[
			'ruta' => 'cgs/listado','ajax' => true,'accion' => 'CompetenciaGenericaController::ListAction','metodo' => 'get',
			'permiso' => 'cg_leer',
		],
		'cg.nuevo' => [
			'ruta' => 'cgs/nuevo','ajax' => true,'accion' => 'CompetenciaGenericaController::NuevoAction','metodos' => ['get','post'],'parametros' => ['post' => ['descripcion_cg']],
			'permiso' => 'cg_escribir',
		],
		'cg.editar' => [
			'ruta' => 'cgs/editar','ajax' => true,'accion' => 'CompetenciaGenericaController::EditarAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_cg'], 'post' => ['id_cg','descripcion_cg']],
			'permiso' => 'cg_escribir',
		],
		'cg.eliminar' => [
			'ruta' => 'cgs/eliminar','json' => true,'accion' => 'CompetenciaGenericaController::EliminarAction','metodos' => ['post'],'parametros' => ['id_cg'],
			'permiso' => 'cg_eliminar',
		],

		'conocimiento' => [
			'ruta' => 'conocimiento','accion' => 'ConocimientoController::IndexAction','metodos' => ['get'],
			'permiso' => 'conocimiento_leer',
		],
		'conocimiento.listado' => [
			'ajax' => true,'ruta' => 'conocimiento/list','accion' => 'ConocimientoController::ListadoAction','metodos' => ['get'],
			'permiso' => 'conocimiento_leer',
		],
		'conocimiento.nuevo' => [
			'ruta' => 'conocimiento/nuevo','ajax' => true,'accion' => 'ConocimientoController::NuevoAction','metodos' => ['get','post'],'parametros' => ['post' => ['descrip_conocimiento']],
			'permiso' => 'conocimiento_escribir',
		],
		'conocimiento.editar' => [
			'ajax' => true,'ruta' => 'conocimiento/editar','accion' => 'ConocimientoController::EditarAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_compo_conocimiento'],'post'=>['id_compo_conocimiento','descrip_conocimiento']],
			'permiso' => 'conocimiento_escribir',
		],
		'conocimiento.eliminar' => [
			'ruta' => 'conocimiento/eliminar','json' => true,'accion' => 'ConocimientoController::EliminarAction','metodo' => 'post','parametros' => ['id_compo_conocimiento'],
			'permiso' => 'conocimiento_eliminar',
		],

		'habilidad' => [
			'ruta' => 'habilidad','accion' => 'HabilidadController::IndexAction','metodos' => ['get'],
			'permiso' => 'habilidad_leer',
		],
		'habilidad.listado' => [
			'ajax' => true,'ruta' => 'habilidad/list','accion' => 'HabilidadController::ListadoAction','metodos' => ['get'],
			'permiso' => 'habilidad_leer',
		],
		'habilidad.nuevo' => [
			'ruta' => 'habilidad/nuevo','ajax' => true,'accion' => 'HabilidadController::NuevoAction','metodos' => ['get','post'],'parametros' => ['post' => ['descrip_habilidad']],
			'permiso' => 'habilidad_escribir',
		],
		'habilidad.editar' => [
			'ajax' => true,'ruta' => 'habilidad/editar','accion' => 'HabilidadController::EditarAction','metodos' => ['get','post'],'parametros' => ['get'=>['id_compo_habilidad'],'post'=>['id_compo_habilidad','descrip_habilidad']],
			'permiso' => 'habilidad_escribir',
		],
		'habilidad.eliminar' => [
			'ruta' => 'habilidad/eliminar','json' => true,'accion' => 'HabilidadController::EliminarAction','metodo' => 'post','parametros' => ['id_compo_habilidad'],
			'permiso' => 'habilidad_eliminar',
		],


		'competencia' => [
			'ruta' => 'mis-competencias', 'accion' => 'MisCompetenciasController::IndexAction',
			'permiso' => 'mcompetencia_leer',
		],

		'competenciafin' => [
			'ruta' => 'mis-competencias-finalizadas', 'accion' => 'MisCompetenciasController::IndexAction',
			'permiso' => 'mcompetencia_leer',
		],

		'competencia.listado' => [
			'ruta' => 'listado-comps', 'ajax' => true,'accion' => 'MisCompetenciasController::ListadoAction',
			'permiso' => 'mcompetencia_leer',
		],

		'competencia.perfil' => [
			'ruta' => 'competencial/perfil', 'accion' => 'MisCompetenciasController::PerfilAction',
			'permiso' => 'mcompetencia_leer',
		],

		'competencia.eliminar' => [
			'ruta' => 'competencia/eliminar', 'accion' => 'MisCompetenciasController::EliminarAction', 'ajax' => true, 'metodo' => 'post',
		],

		'competencia.arbol' => [
			'ruta' => 'competencia/arbol', 'accion' => 'MisCompetenciasController::DatosCompetenciaAction', 'json' => true,
			'permiso' => 'mcompetencia_leer',
		],

		'asistente.funidad' => [
			'ruta' => 'competencia/unidad', 'accion' => 'AsistenteUnidadController::IndexFormUnidadAction', 'titulo' => 'Competencia de unidad',
			'parametros' => ['id_competencia_asignatura','id_competencia_unidad','unidad'],
			'permiso' => 'asistente_leer',
		],
		'asistente.funidad_form1' => [
			'ruta' => 'competencia/unidad-f1', 'accion' => 'AsistenteUnidadController::Form1Action', 'ajax' => true,
			'parametros' => ['id_competencia_asignatura','id_competencia_unidad','unidad'],
			'permiso' => 'asistente_leer',
		],
		'asistente.funidad_form2' => [
			'ruta' => 'competencia/unidad-f2', 'accion' => 'AsistenteUnidadController::Form2Action', 'ajax' => true,
			'parametros' => ['id_competencia_asignatura','id_competencia_unidad','unidad'],
			'permiso' => 'asistente_leer',
		],
		'asistente.funidad_form3' => [
			'ruta' => 'competencia/unidad-f3', 'accion' => 'AsistenteUnidadController::Form3Action', 'ajax' => true,
			'parametros' => ['id_competencia_asignatura','id_competencia_unidad','unidad'],
			'permiso' => 'asistente_leer',
		],
		'asistente.funidad_guardarf1' => [
			'ruta' => 'competencia/guardar-unidad-f1', 'metodos' => ['post'] , 'accion' => 'AsistenteUnidadController::GuardarFase1Action','json' => true,
			'permiso' => 'asistente_escribir',
		],

		'asistente.fresultado' => [
			'ruta' => 'competencia/resultado', 'accion' => 'AsistenteResultadoController::IndexFormResultadoAction', 'titulo' => 'Asistente Evaluación Competencias',
			// 'parametros' => ['id_competencia_unidad'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_form1' => [
			'ruta' => 'competencia/resultado-f1', 'accion' => 'AsistenteResultadoController::Form1Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_resultados_unidad' => [
			'ruta' => 'competencia/resultados-unidad', 'accion' => 'AsistenteResultadoController::CompetenciasResultadoMismaUnidadAction', 'ajax' => true,
		],
		'asistente.fresultado_guardarf1' => [
			'ruta' => 'competencia/guardar-resultado-f1', 'metodos' => ['post'] , 'accion' => 'AsistenteResultadoController::GuardarFase1Action','json' => true,
			'permiso' => 'asistente_escribir',
		],
		'asistente.fresultado_guardarf4' => [
			'ruta' => 'competencia/guardar-resultado-f4', 'metodo' => 'post' , 'accion' => 'AsistenteResultadoController::GuardarFase4Action','json' => true,
			'permiso' => 'asistente_escribir',
		],
		'asistente.fresultado_guardarf5' => [
			'ruta' => 'competencia/guardar-resultado-f5', 'metodo' => 'post', 'accion' => 'AsistenteResultadoController::GuardarFase5Action', 'json' => true,
			'permiso' => 'asistente_escribir',
		],
		'asistente.fresultado_guardarf6' => [
			'ruta' => 'competencia/guardar-resultado-f6', 'metodo' => 'post', 'accion' => 'AsistenteResultadoController::GuardarFase6Action', 'json' => true,
			'permiso' => 'asistente_escribir',
		],
		'asistente.fresultado_f6_ada_competencias' => [
			'ruta' => 'competencia/resultado-f6/ada-competencias-extra', 'metodo' => 'get', 'accion' => 'AsistenteResultadoController::ListaAdaCompetenciasExtra', 'json' => true
		],
		'asistente.fresultado_f6_guardar_ada_competencias' => [
			'ruta' => 'competencia/resultado-f6/guardar-ada-competencias', 'metodo' => 'post', 'accion' => 'AsistenteResultadoController::GuardarAdaCompetenciasExtrasAction', 'json' => true,
		],
		'asistente.fresultado_guardarf7' => [
			'ruta' => 'competencia/guardar-resultado-f7', 'metodo' => 'post', 'accion' => 'AsistenteResultadoController::GuardarFase7Action', 'json' => true,
			'permiso' => 'asistente_escribir',
		],
		'asistente.fresultado_form2' => [
			'ruta' => 'competencia/resultado-f2', 'accion' => 'AsistenteResultadoController::Form2Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_form3' => [
			'ruta' => 'competencia/resultado-f3', 'accion' => 'AsistenteResultadoController::Form3Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_form4' => [
			'ruta' => 'competencia/resultado-f4', 'accion' => 'AsistenteResultadoController::Form4Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_form5' => [
			'ruta' => 'competencia/resultado-f5', 'accion' => 'AsistenteResultadoController::Form5Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_form6' => [
			'ruta' => 'competencia/resultado-f6', 'accion' => 'AsistenteResultadoController::Form6Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_form6_rubrica' => [
			'ruta' => 'competencia/resultado-f6-rubrica', 'accion' => 'AsistenteResultadoController::RubricaF6Action', 'json' => true,
		],
		'asistente.fresultado_form6_guardar_rubrica' => [
			'ruta' => 'competencia/resultado-f6-guardar-rubrica', 'accion' => 'AsistenteResultadoController::GuardarRubricaF6Action', 'json' => true, 'metodo' => 'post',
		],
		'asistente.fresultado_form7' => [
			'ruta' => 'competencia/resultado-f7', 'accion' => 'AsistenteResultadoController::Form7Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_form8' => [
			'ruta' => 'competencia/resultado-f8', 'accion' => 'AsistenteResultadoController::Form8Action', 'ajax' => true,
			'parametros' => ['id_competencia_padre','id_competencia_resultado'],
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_ladas' => [
			'ruta' => 'adas-resultado', 'accion'=>'AsistenteResultadoController::AdasResAction', 'ajax' => true,
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_formada' => [
			'ruta' => 'adas-resultado/form', 'accion'=>'AsistenteResultadoController::FormAdaAction', 'ajax' => true,
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_guardar_ada' => [
			'ruta' => 'guardar-ada', 'accion' => 'AsistenteResultadoController::GuardarAdaAction', 'metodo' => 'post',
			'permiso' => 'asistente_escribir'
		],
		'asistente.fresultado_eliminar_ada' => [
			'ruta' => 'eliminar-ada' ,'accion' => 'AsistenteResultadoController::EliminarAdaAction', 'metodo' => 'post'
		],
		'asistente.fresultado_recomendacion' => [
			'ruta' => 'adas-resultado/recomendacion-herramienta', 'accion' => 'AsistenteResultadoController::RecomendacionHerramientaAction','json'=>true,
			'permiso' => 'asistente_leer',
		],
		'asistente.fresultado_palabras_clave' => [
			'ruta' => 'agregar-palabra-clave', 'accion' =>'AsistenteResultadoController::AgregarPlabrasClaveAction','ajax'=>true,
			'permiso' => 'asistente_leer',
		],
		'cd' => [
			'ruta' => 'competencia-disciplina', 'accion' => 'CompetenciaDisciplinarController::IndexAction',
			'permiso' => 'cd_leer',
		],
		'cd.listado' => [
			'ruta' => 'cd/listado', 'accion' => 'CompetenciaDisciplinarController::ListAction','ajax'=>true,
			'permiso' => 'cd_leer',
		],
		'cd.formulario' => [
			'ruta' => 'cd/form', 'accion' => 'CompetenciaDisciplinarController::FormAction','ajax'=>true,
			'permiso' => 'cd_escribir',
		],
		'cd.guardar' => [
			'ruta' => 'cd/guardar', 'accion' => 'CompetenciaDisciplinarController::GuardarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'cd_escribir',
		],
		'cd.eliminar' => [
			'ruta' => 'cd/eliminar', 'accion' => 'CompetenciaDisciplinarController::EliminarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'cd_eliminar',
		],

		'cont_unidad' => [
			'ruta' => 'unidades/contenido', 'accion' => 'ContenidoUnidadAsignaturaController::IndexAction',
			'permiso' => 'contenido_unidad_leer',
		],
		'cont_unidad.listado' => [
			'ruta' => 'unidades/contenido/listado', 'accion' => 'ContenidoUnidadAsignaturaController::ListAction','ajax'=>true,
			'permiso' => 'contenido_unidad_leer',
		],
		'cont_unidad.formulario' => [
			'ruta' => 'unidades/contenido/form', 'accion' => 'ContenidoUnidadAsignaturaController::FormAction','ajax'=>true,
			'permiso' => 'contenido_unidad_escribir',
		],
		'cont_unidad.guardar' => [
			'ruta' => 'unidades/contenido/guardar', 'accion' => 'ContenidoUnidadAsignaturaController::GuardarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'contenido_unidad_escribir',
		],
		'cont_unidad.eliminar' => [
			'ruta' => 'unidades/contenido/eliminar', 'accion' => 'ContenidoUnidadAsignaturaController::EliminarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'contenido_unidad_eliminar',
		],


		'ea' => [
			'ruta' => 'estrategia-ea', 'accion' => 'EstrategiaEaController::IndexAction',
			'permiso' => 'ea_leer',
		],
		'ea.listado' => [
			'ruta' => 'estrategia-ea/listado', 'accion' => 'EstrategiaEaController::ListAction','ajax'=>true,
			'permiso' => 'ea_leer',
		],
		'ea.formulario' => [
			'ruta' => 'estrategia-ea/form', 'accion' => 'EstrategiaEaController::FormAction','ajax'=>true,
			'permiso' => 'ea_escribir',
		],
		'ea.guardar' => [
			'ruta' => 'estrategia-ea/guardar', 'accion' => 'EstrategiaEaController::GuardarAction','json'=>true, 'metodo' => 'post',
			'permiso' => 'ea_escribir',
		],
		'ea.eliminar' => [
			'ruta' => 'estrategia-ea/eliminar', 'accion' => 'EstrategiaEaController::EliminarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'ea_eliminar',
		],

		'modulo' => [
			'ruta' => 'modulo', 'accion' => 'ModuloController::IndexAction',
			'permiso' => 'modulo_leer',
		],
		'modulo.listado' => [
			'ruta' => 'modulo/listado', 'accion' => 'ModuloController::ListAction','ajax'=>true,
			'permiso' => 'modulo_leer',
		],
		'modulo.formulario' => [
			'ruta' => 'modulo/form', 'accion' => 'ModuloController::FormAction','ajax'=>true,
			'permiso' => 'modulo_escribir',
		],
		'modulo.guardar' => [
			'ruta' => 'modulo/guardar', 'accion' => 'ModuloController::GuardarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'modulo_escribir',
		],
		'modulo.instalar' => [
			'ruta' => 'modulo/instalar', 'accion' => 'ModuloController::InstalarAction','json'=>true,'metodo' => 'post',
		],
		'modulo.eliminar' => [
			'ruta' => 'modulo/eliminar', 'accion' => 'ModuloController::EliminarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'modulo_eliminar',
		],

		'permiso' => [
			'ruta' => 'permiso', 'accion' => 'PermisoController::IndexAction',
			'permiso' => 'permiso_leer',
		],
		'permiso.listado' => [
			'ruta' => 'permiso/listado', 'accion' => 'PermisoController::ListAction','ajax'=>true,
			'permiso' => 'permiso_leer',
		],
		'permiso.formulario' => [
			'ruta' => 'permiso/form', 'accion' => 'PermisoController::FormAction','ajax'=>true,
			'permiso' => 'permiso_escribir',
		],
		'permiso.guardar' => [
			'ruta' => 'permiso/guardar', 'accion' => 'PermisoController::GuardarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'permiso_escribir',
		],
		'permiso.eliminar' => [
			'ruta' => 'permiso/eliminar', 'accion' => 'PermisoController::EliminarAction','json'=>true,'metodo' => 'post',
			'permiso' => 'permiso_eliminar',
		],
		
		'competencia2' => [
			'ruta' => 'competencia-au', 'accion' => 'CompetenciaAsignaturaUnidadController::IndexAction',
		],

		'competencia2.listado' => [
			'ruta' => 'competencia-au/listado', 'accion' => 'CompetenciaAsignaturaUnidadController::ListAction', 'ajax' => true
		],
		'competencia2.formulario' => [
			'ruta' => 'competencia-au/form', 'accion' => 'CompetenciaAsignaturaUnidadController::FormAction', 'ajax' => true,
		],
		'competencia2.guardar' => [
			'ruta' => 'competencia-au/guardar', 'accion' => 'CompetenciaAsignaturaUnidadController::GuardarAction', 'json' => true, 'metodo' => 'post'
		],
		'competencia2.cgs_ape' => [
			'ruta' => 'competencia-au/cgs-ape', 'accion' => 'CompetenciaAsignaturaUnidadController::CgsApeAction'
		],
		'desagregado' => [ 
			'ruta'=>'desagregado','accion' => 'DesagregadoController::IndexAction', 'titulo'=>'Desagregados'
		],
		'desagregado.listado' => [ 
			'ruta'=>'desagregado/list','accion' => 'DesagregadoController::ListAction', 'ajax'=>true,
		],
		'desagregado.formulario' => [ 
			'ruta'=>'desagregado/form','accion' => 'DesagregadoController::FormAction', 'ajax'=>true
		],
		'desagregado.guardar' => [ 
			'ruta'=>'desagregado/guardar', 'metodo'=>'post' , 'accion' => 'DesagregadoController::GuardarAction', 'json'=>true
		],
		'desagregado.eliminar' => [ 
			'ruta'=>'desagregado/eliminar', 'metodo'=>'post', 'accion' => 'DesagregadoController::EliminarAction', 'json'=>true
		],

		'instrumeval' => [ 
			'ruta'=>'instrum-eval','accion' => 'InstrumentoEvalController::IndexAction', 'titulo'=>'Instrumentos de evaluación'
		],
		'instrumeval.listado' => [ 
			'ruta'=>'instrum-eval/list','accion' => 'InstrumentoEvalController::ListAction', 'ajax'=>true,
		],
		'instrumeval.formulario' => [ 
			'ruta'=>'instrum-eval/form','accion' => 'InstrumentoEvalController::FormAction', 'ajax'=>true
		],
		'instrumeval.guardar' => [ 
			'ruta'=>'instrum-eval/guardar', 'metodo'=>'post' , 'accion' => 'InstrumentoEvalController::GuardarAction', 'json'=>true
		],
		'instrumeval.eliminar' => [ 
			'ruta'=>'instrum-eval/eliminar', 'metodo'=>'post', 'accion' => 'InstrumentoEvalController::EliminarAction', 'json'=>true
		],

		'producto' => [ 
			'ruta'=>'producto','accion' => 'ProductoController::IndexAction', 'titulo'=>'Productos ADA'
		],
		'producto.listado' => [ 
			'ruta'=>'producto/list','accion' => 'ProductoController::ListAction', 'ajax'=>true,
		],
		'producto.formulario' => [ 
			'ruta'=>'producto/form','accion' => 'ProductoController::FormAction', 'ajax'=>true
		],
		'producto.guardar' => [ 
			'ruta'=>'producto/guardar', 'metodo'=>'post' , 'accion' => 'ProductoController::GuardarAction', 'json'=>true
		],
		'producto.eliminar' => [ 
			'ruta'=>'producto/eliminar', 'metodo'=>'post', 'accion' => 'ProductoController::EliminarAction', 'json'=>true
		],

		'recurso' => [ 
			'ruta'=>'recurso','accion' => 'RecursoController::IndexAction', 'titulo'=>'Recursos ADA'
		],
		'recurso.listado' => [ 
			'ruta'=>'recurso/list','accion' => 'RecursoController::ListAction', 'ajax'=>true,
		],
		'recurso.formulario' => [ 
			'ruta'=>'recurso/form','accion' => 'RecursoController::FormAction', 'ajax'=>true
		],
		'recurso.guardar' => [ 
			'ruta'=>'recurso/guardar', 'metodo'=>'post' , 'accion' => 'RecursoController::GuardarAction', 'json'=>true
		],
		'recurso.eliminar' => [ 
			'ruta'=>'recurso/eliminar', 'metodo'=>'post', 'accion' => 'RecursoController::EliminarAction', 'json'=>true
		],

		'herramienta' => [ 
			'ruta'=>'herramienta','accion' => 'HerramientaController::IndexAction', 'titulo'=>'Herramientas moodle'
		],
		'herramienta.listado' => [ 
			'ruta'=>'herramienta/list','accion' => 'HerramientaController::ListAction', 'ajax'=>true,
		],
		'herramienta.json_listado' => [
			'ruta' => 'herramienta/list-json', 'accion' => 'HerramientaController::JSONListAction', 'json' => true
		],
		'herramienta.formulario' => [ 
			'ruta'=>'herramienta/form','accion' => 'HerramientaController::FormAction', 'ajax'=>true
		],
		'herramienta.guardar' => [ 
			'ruta'=>'herramienta/guardar', 'metodo'=>'post' , 'accion' => 'HerramientaController::GuardarAction', 'json'=>true
		],
		'herramienta.eliminar' => [ 
			'ruta'=>'herramienta/eliminar', 'metodo'=>'post', 'accion' => 'HerramientaController::EliminarAction', 'json'=>true
		],


		'rubrica' => [ 
			'ruta'=>'rubrica','accion' => 'RubricaController::IndexAction', 'titulo'=>'Rubricas'
		],
		'rubrica.listado' => [ 
			'ruta'=>'rubrica/list','accion' => 'RubricaController::ListAction', 'ajax'=>true,
		],
		'rubrica.formulario' => [ 
			'ruta'=>'rubrica/form','accion' => 'RubricaController::FormAction', 'titulo'=> 'Formulario'
		],
		'rubrica.formulario_asistente' => [ 
			'ruta'=>'rubrica/form-asistente','accion' => 'RubricaController::FormAsistenteAction','ajax' => true,
		],
		'rubrica.guardar' => [ 
			'ruta'=>'rubrica/guardar', 'metodo'=>'post' , 'accion' => 'RubricaController::GuardarAction', 'json'=>true
		],
		'rubrica.eliminar' => [ 
			'ruta'=>'rubrica/eliminar', 'metodo'=>'post', 'accion' => 'RubricaController::EliminarAction', 'json'=>true
		],

		'plantillarubrica' => [ 
			'ruta'=>'plantillarubrica','accion' => 'RubricaController::IndexPlantillaAction', 'ajax'=>true,
		],
		'plantillarubrica.formulario' => [ 
			'ruta'=>'plantillarubrica/form','accion' => 'RubricaController::FormPlantillaAction', 'titulo'=>'Formulario Rúbrica','ajax'=>true,
		],
		'plantillarubrica.guardar' => [ 
			'ruta'=>'plantillarubrica/guardar', 'metodo'=>'post' , 'accion' => 'RubricaController::GuardarPlantillaAction', 'json'=>true
		],
		'plantillarubrica.eliminar' => [ 
			'ruta'=>'plantillarubrica/eliminar', 'metodo'=>'post', 'accion' => 'RubricaController::EliminarPlantillaAction', 'json'=>true
		],

		'plantillarubrica.listado_completo' => [
			'ruta' => 'plantillarubrica/listado-completo', 'accion' => 'RubricaController::ListadoCompletoAction', 'json'=>true
		],

		'shared.asignatura_unidad' => [
			'ruta' => 'shared/asignatura-unidad', 'accion' => 'SharedController::AsignaturaUnidadListaAction', 'json' => true,
		],
		'shared.competencia_unidad' => [
			'ruta' => 'shared/competencia-unidad', 'accion' => 'SharedController::CompetenciaUnidadAction', 'json' => true,
		],
		'shared.existe_competencia_resultado' => [
			'ruta' => 'shared/existe-competencia-resultado', 'accion' => 'SharedController::ExisteCompetenciaResultadoAction', 'json' => true,
		],
		'shared.competencia_asignatura' => [
			'ruta' => 'shared/competencia-asignatura', 'accion' => 'SharedController::CompetenciaAsignaturaAction', 'json' => true,
		],
		'shared.contenido_unidad' => [
			'ruta' => 'shared/contenido-unidad', 'accion' => 'SharedController::ContenidosUnidadAction', 'json' => true,
		],
		'shared.asignatura' => [
			'ruta' => 'shared/asignaturas', 'accion' => 'SharedController::AsignaturasAction', 'json' => true,
		],
		'shared.conocimiento' => [
			'ruta' => 'shared/conocimiento', 'accion' => 'SharedController::ConocimientosAction', 'json' => true
		],
		'shared.habilidad' => [
			'ruta' => 'shared/habilidad', 'accion' => 'SharedController::HabilidadesAction', 'json' => true
		],
		'shared.actitud' => [
			'ruta' => 'shared/actitud', 'accion' => 'SharedController::ActitudesValoresAction', 'json' => true
		],
		'ayuda' => [ 'ruta' => 'ayuda', 'accion' => 'IndexController::AyudaAction', 'titulo' => 'Ayuda' ],
		'autores' => [ 'ruta' => 'autores', 'accion' => 'IndexController::AutoresAction', 'titulo' => 'Autores' ],
		'ada.listado' => [
			'ruta' => 'adas-listado', 'accion' => 'AsistenteResultadoController::ListaAdasAction', 'json' => true,
		],
		'ada.formato_exportar' => [
			'ruta' => 'ada-formato-exportar', 'accion' => 'AsistenteResultadoController::FormatoExportacionAda', 'ajax' => true,
		],
		'reporte.pd' => [
			'ruta' => 'formato/planeacion-didactica', 'accion' => 'ReporteController::PlaneacionDidacticaIndexAction'
		],
		'reporte.generar_pd' => [
			'ruta' => 'formato/planeacion-didactica/pdf',
			'accion' => 'ReporteController::FormatoPlaneacionDidacticaAction',
			'json' => true,
		],
		'reporte.generar_pd_desde_html' => [
			'ruta' => 'formato/planeacion-didactica/desde-html',
			'accion' => 'ReporteController::FormatoPlaneacionDidacticaDesdeHtmlAction',
			'json' => true,
			'metodo' => 'post',
		],
		'ada.validar_uso' => [
			'ruta' => 'ada/validar-uso', 'accion' => 'AsistenteResultadoController::ValidarUsoHerramientaAdas',
			'json' => true,
			'metodo' => 'get',
		],

	];

	const ERRORES = [
            '404' => [
                'metodos' => ['get','post','put','delete'],
                'ruta' => '404',
                'titulo'  => 'Error 404',
                'accion'  => 'BaseController::Error404Action'
            ],
            '404.ajax' => [
            	'metodos' => ['get','post'],
            	'ruta' => '404ajax',
            	'ajax' => true,
            	'accion' => 'BaseController::Error404Action'
            ],
            '404.json' => [
            	'metodos' => ['get','post'],
            	'ruta' => '404json',
            	'json' => true,
            	'accion' => 'BaseController::Error404JsonAction'
            ],
            '401' => [
                'metodos' => ['get','post','put','delete'],
                'ruta' => '401',
                'titulo'  => 'Error 401',
                'accion'  => 'BaseController::Error401Action'
            ],
            '401.ajax' => [
            	'metodos' => ['get','post'],
            	'ruta' => '401ajax',
            	'ajax' => true,
            	'accion' => 'BaseController::Error401Action'
            ],
            '401.json' => [
            	'metodos' => ['get','post'],
            	'ruta' => '401json',
            	'json' => true,
            	'accion' => 'BaseController::Error401JsonAction'
            ],
	];

	const RUTAS_LIBRES = [
		'login',
		'usuario.nuevo',
		'404',
		'logout'
	];

	public static function metodoValido($metodo, $data){
		if(isset($data['metodos']) && is_array($data['metodos'])){
			return in_array($metodo, $data['metodos']);
		}else if(isset($data['metodo'])){
			return $metodo == strtolower($data['metodo']);
		}else{
			return $metodo == 'get';
		}
	}

	public function encontrarRuta($path){
		$metodo = strtolower($_SERVER['REQUEST_METHOD']);
        $sesion = Sesion::obtener();
		$desp =  self::getDespreciable();
		$div = explode("?", $path);
		$pathLimpio = $div[0];
		$nombreRuta = "404";
        $permisoRuta = "";
        
		foreach (self::RUTAS as $nombre => $data) {
			if($desp.$data['ruta'] == $pathLimpio && ( self::metodoValido($metodo, $data) ) ){
				$nombreRuta = $nombre;
                $permisoRuta = isset($data['permiso']) ? $data['permiso'] : "";
				break;
			}
		}

		// Estas lineas permiten validar los permisos
        // if($sesion != null && $nombreRuta != "404" && $permisoRuta != ""){
        //     if(!in_array($permisoRuta, $sesion->obtenerPermisos())){
        //         $nombreRuta = "401";
        //     }
        // }

        if($nombreRuta == "404" || $nombreRuta == "401"){
        	if(isset($data['ajax'])){
        		$nombreRuta.=".ajax";
        	}else if(isset($data['json'])){
        		$nombreRuta.=".json";
        	}
        }
		return $nombreRuta;
	}

	public function obtenerRuta($nombre){
		return isset(self::RUTAS[$nombre]) ? self::RUTAS[$nombre] : self::ERRORES[$nombre];
	}

	public function iniciarAccion($nombre){
		$data = $this->obtenerRuta($nombre);
		$info = explode("::", $data['accion']);

		$metodo = strtolower($_SERVER['REQUEST_METHOD']);
		$controllerNameClass = $info[0];
		$actionNameMethod = $info[1];

		$controller = new $controllerNameClass();

		// ponemos un acceso rapido
		$data['metodo'] = $metodo;
		$data['nombre_ruta'] = $nombre;
		$user = Sesion::obtener();
		$controller->esAjax = (isset($data['ajax']) && $data['ajax']) || (isset($data['json']) && $data['json']);
		$controller->titulo = $controller->esAjax ? "" : (isset($data['titulo']) ? $data['titulo'] : DEFAULT_TITLE );
		$controller->nombre_ruta = $nombre;
		$controller->existe_sesion = $user != null;
		$controller->user = $user;
		Request::dispatchAction(
			$controller, 
			$actionNameMethod, 
			$data, 
			$metodo, 
			function($req, $res, $args, $ctrlObject, $actMethod){
				call_user_func_array( 
					array($ctrlObject, $actMethod), 
					array($req, $res, $args)
				);
			}
		);

	}

	public static function obtenerData($name){
		return isset(self::RUTAS[$name]) ? self::RUTAS[$name] : self::ERRORES['404'];
	}

	public function esLogin($name){
		return $name == "login";
	}
}