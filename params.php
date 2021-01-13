<?php 

define('FACULTADES', [
	'fedu' => 'Facultad de Educación',
	'fmat' => 'Facultad de Matemáticas',
	'fca' => 'Facultad de Contaduría y Administración',
	'fenf' => 'Facultad de Enfermería',
]);

define('MODALIDAD_INDIVIDUAL_SINONIMOS', [ 'individual' ]);

define('MODALIDAD_PARES_SINONIMOS', [ 'pares', 'binas', 'parejas','pareja','dos' ]);

define('MODALIDAD_EQUIPO_SINONIMOS', [ 'equipo','equipos' ]);

define('MODALIDAD_GRUPAL_SINONIMOS', [ 'grupo','grupos','agrupado' ]);

define('SEPARADORES',[
		'y', 
		'e', 
		'ni', 
		'ademas', 
		'también', 
		'del mismo modo', 
		'incluso', 
		'tampoco',
		'o',
		'u',
		'pero', 
		'en cambio', 
		'sino', 
		'a pesar de', 
		'por el contrario', 
		'mas', 
		'sino', 
		'sin embargo', 
		'no obstante',
		'por lo tanto', 
		'así que, en consecuencia', 
		'por consiguiente', 
		'por ende', 
		'de manera',
		'de manera que', 
		'de modo que', 
		'mientras', 
		'en efecto', 
		'pues', 
		'luego',
		'porque', 
		'pues', 
		'ya que', 
		'puesto que', 
		'dado que', 
		'debido a que', 
		'a causa de', 
		'por eso', 
		'como',
		'antes de',
		'previamente',
		'al principio',
		'mucho antes',
		'cuando','mientras','mientras tanto', 'en cuanto',
		'donde',
		'a',
		'de',
		'que',
		'el',
		'la',
		'los',
		'las',
		'le',
		'en',
		'sus',
		'su',
		'ellos',
	]);

// Sirve para saber si determinado registro de la tabla
// InstrumentoEvaluacion tiene asociado alguna tabla de tipos
define('PLANTILLAS', [
	'2' => [ // 2 <- id del registro en instrumentos de 'Matriz de valoracion o rubrica'
		'nombre'    => 'rubrica',
		'tipo'      => 'TblRubrica',
		'plantilla' => 'TblPlantillaRubrica',
	]
]);

define('MENU_SIDEBAR', [
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Planeación didáctica ',
	// 	'faicon' => 'home',		
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Panel de control',
	// 	'faicon' => 'home',
	// 	'ruta' => 'root',
	// 	'mostrar' => true,
	// ],
	
	// ===================== ACADEMICO ======================
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Institucional',
	// 	'faicon' => false,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Institución',
	// 	'faicon' => false,
	// 	'ruta' => 'institucion',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Planes de estudio',
	// 	'faicon' => false,
	// 	'ruta' => 'pe',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Asignaturas',
	// 	'faicon' => false,
	// 	'ruta' => 'asignatura',
	// 	'mostrar' => true,
	// ],

	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Unidades de asignaturas',
	// 	'faicon' => false,
	// 	'ruta' => 'unidad',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Competencias de Unidad',
	// 	'faicon' => false,
	// 	'ruta' => 'competencia2',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Desagregados de contenidos',
	// 	'faicon' => false,
	// 	'ruta' => 'desagregado',
	// 	'mostrar' => true,
	// ],
	
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Competencias Genéricas',
	// 	'faicon' => false,
	// 	'ruta' => 'cg',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Competencias Disciplinares',
	// 	'faicon' => false,
	// 	'ruta' => 'cd',
	// 	'mostrar' => true,
	// ],
	

	// // ===================== CATALOGOS ======================
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Catálogos de sintaxis',
	// 	'faicon' => false,
	// ],

	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Verbos',
	// 	'faicon' => 'sort-alpha-asc',
	// 	'ruta' => 'verbo.index',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Contenidos',
	// 	'faicon' => 'sort-alpha-asc',
	// 	'ruta' => 'contenido.index',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Contextos',
	// 	'faicon' => 'sort-alpha-asc',
	// 	'ruta' => 'contexto.index',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Criterios',
	// 	'faicon' => 'sort-alpha-asc',
	// 	'ruta' => 'criterio.index',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Catálogos de componentes',
	// 	'faicon' => false,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Conocimientos',
	// 	'faicon' => 'sort-alpha-asc',
	// 	'ruta' => 'conocimiento',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Habilidades',
	// 	'faicon' => 'sort-alpha-asc',
	// 	'ruta' => 'habilidad',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Actitudes y valores',
	// 	'faicon' => 'sort-alpha-asc',
	// 	'ruta' => 'actitud_valor.index',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Herramientas de Evaluación',
	// 	'faicon' => false,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Estrategias de Enseñanza/Aprendizaje',
	// 	'faicon' => false,
	// 	'ruta' => 'ea',
	// 	'mostrar' => true,
	// ],

	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Instrumentos de evaluación',
	// 	'faicon' => false,
	// 	'ruta' => 'instrumeval',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Productos de ADA',
	// 	'faicon' => false,
	// 	'ruta' => 'producto',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Recursos para ADA',
	// 	'faicon' => false,
	// 	'ruta' => 'recurso',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Competencias genéricas',
	// 	'faicon' => false,
	// 	'ruta' => 'cg',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Competencias disciplinares',
	// 	'faicon' => false,
	// 	'ruta' => 'cd',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Competencias asignatura y unidad',
	// 	'faicon' => false,
	// 	'ruta' => 'competencia2',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Rúbricas',
	// 	'faicon' => false,
	// 	'ruta' => 'rubrica',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Herramientas de Moodle',
	// 	'faicon' => false,
	// 	'ruta' => 'herramienta',
	// 	'mostrar' => true,
	// ],



	// ===================== ASISTENTE ======================
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Asistente',
	// 	'faicon' => false,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Nueva evaluación',
	// 	'faicon' => false,
	// 	'ruta' => 'asistente.fresultado',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Evaluaciones en proceso',
	// 	'faicon' => 'list',
	// 	'ruta' => 'competencia',
	// 	'params' => [ 'finalizado' => 0 ],
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Evaluaciones finalizadas',
	// 	'params' => [ 'finalizado' => 1 ],
	// 	'faicon' => 'list',
	// 	'ruta' => 'competenciafin',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Mis materias asignadas',
	// 	'faicon' => 'list',
	// 	'ruta' => 'usuario.asignatura',
	// 	'mostrar' => true,
	// ],


	// ===================== ADMINISTRACION ======================
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Administración',
	// 	'faicon' => false,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Usuarios',
	// 	'faicon' => 'users',
	// 	'ruta' => 'usuario.index',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Permisos',
	// 	'faicon' => 'key',
	// 	'ruta' => 'permiso',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Módulos',
	// 	'faicon' => 'key',
	// 	'ruta' => 'modulo',
	// 	'mostrar' => true,
	// ],
	// [
	// 	'tipo' => 'seccion',
	// 	'label' => 'Reportes',
	// 	'faicon' => false,
	// ],
	// [
	// 	'tipo' => 'item',
	// 	'label' => 'Planeación didáctica',
	// 	'faicon' => 'file-pdf-o',
	// 	'ruta' => 'reporte.pd',
	// 	'mostrar' => true,
	// ],
]);

define('AYUDA_FASES',[
	'f0' => '<strong>Fase 0</strong><br>Proporcione la información del contexto de la competencia que desea evaluar',
	'f1' => '<strong>Fase 1</strong><br>Introduzca o construya la competencia que desea evaluar, introduciendo o seleccionando el verbo, el contenido, el contexto y el criterio. Puede apoyarse de los resultados de aprendizaje esperados. Usted decide si desea evaluar una competencia de unidad con una única Actividad de Aprendizaje o con varias.',
	'f1_verbo' => '¿Cual es la acción esperada? El verbo es único. Haga clic en el verbo deseado para seleccionarlo y añadirlo a la competencia en construcción. Si el verbo no aparece en la lista, puede añadirlo.',
	'f1_contenido' => '¿Qué objeto recibe la acción? Escriba o seleccione de la lista el (los) contenido(s) deseados. Clic o enter para añadirlo. Puede añadir varios contenidos, uno por uno.',
	'f1_contexto' => '¿Dónde? ¿Cuál es el entorno, escenario o ambiente físico, de situación, político, histórico, cultural, social o de cualquier índole en el que se realiza la acción? Escriba o seleccione de la lista el (los) contexto(s) deseados. Clic o enter para añadirlo. Puede añadir varios contextos, uno por uno y al final puede editar el texto de la competencia creada.',
	'f1_criterio' => '¿Qué criterios de calidad deben estar presentes en la acción esperada? Escriba o seleccione de la lista el (los) contexto(s) deseados. Clic o enter para añadirlo. Puede añadir varios contextos, uno por uno y al final puede editar el texto de la competencia creada.',
	'f2' => '<strong>Fase 2</strong><br>En cada caso (conocimientos, habilidades, actitudes y valores) seleccione las sugerencias que considere pertinentes para ser evaluadas posteriormente. Si el texto sugerido no le parece adecuado, puede escribir uno o más componentes usando los cuadros de textos de abajo. Presione enter al terminar, en cada caso. Los cambios se guardan al hacer clic en el botón Siguiente.',
	'f3' => '<strong>Fase 3</strong><br>Debe seleccionar la(s) competencia(s) genérica(s) que se desea desarrollar junto con la competencia específica que se evaluará con la actividad de aprendizaje que se está construyendo.',
	'f4_7' => '<strong>Fase 4-6</strong><br>En esta fase puede crear una o más ADAs. Recuerde considerar la competencia que va evaluar, sus componentes desglosados y las competencias genéricas seleccionadas en los pasos anteriores... Ingrese la modalidad, el nombre, las instrucciones y pasos del procedimiento. Después, oprima el botón “Sugerir herramienta” para obtener las sugerencias automáticas de las herramientas recomendadas para implementar esta ADA.',
	'f8' => '<strong>Fase 7</strong><br>En esta fase usted puede exportar la ADA construida en formato textual.',	
]);