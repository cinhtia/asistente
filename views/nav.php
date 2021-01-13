<nav class="navbar navbar-expand-lg fixed-top p-0 flex-md-nowrap">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?= ruta('root') ?>"><i class="fa fa-home fa-lg"></i> Inicio</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
    </ul>

    <ul class="nav navbar-nav navbar-right">
    
      <?php if($user) { ?>
        <!--
        <li class="nav-item <?= $ruta == 'asistente.fresultado' ? 'active' : '' ?>">
            <a class="nav-link" href="<?= ruta('asistente.fresultado') ?>">
              <i class="fa fa-"></i> Asistente
            </a>
        </li> -->

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarAsistente" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-"></i> Asistente
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a href="<?= ruta('asistente.fresultado') ?>" class="dropdown-item">
              <i class="fa fa-" aria-hidden="true"></i> Nueva evaluación
            </a>
            <a class="dropdown-item" href="<?= ruta('competencia', ['finalizado'=>0]) ?>">
              <i class="fa fa-" aria-hidden="true"></i> Evaluaciones en proceso
            </a>
            <a class="dropdown-item" href="<?= ruta('competencia', ['finalizado'=>1]) ?>">
              <i class="fa fa-" aria-hidden="true"></i> Evaluaciones finalizadas
            </a>
            <a href="<?= ruta('usuario.asignatura') ?>" class="dropdown-item">
              <i class="fa fa-briefcase" aria-hidden="true"></i> Mis asignaturas: 
              <?= $user->nombre; ?>
            </a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarCatálogos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-"></i> Catálogos
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="<?= ruta('verbo.index') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Verbos
            </a>
            <a href="<?= ruta('contenido.index') ?>" class="dropdown-item">
              <i class="fa fa-" aria-hidden="true"></i> Contenidos
            </a>
            <a class="dropdown-item" href="<?= ruta('contexto.index') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Contextos
            </a>
            <a class="dropdown-item" href="<?= ruta('criterio.index') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Criterios
            </a>
            <a class="dropdown-item" href="<?= ruta('conocimiento') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Conocimientos
            </a>
             <a class="dropdown-item" href="<?= ruta('habilidad') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Habilidades
            </a>
             <a class="dropdown-item" href="<?= ruta('actitud_valor.index') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Actitudes y valores
            </a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarInstitucional" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-"></i> Institucional
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="<?= ruta('asignatura') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Asignaturas
            </a>
            <a class="dropdown-item" href="<?= ruta('institucion') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Institución
            </a>
            <a href="<?= ruta('pe') ?>" class="dropdown-item">
              <i class="fa fa-" aria-hidden="true"></i> Planes de Estudio
            </a>
            <a class="dropdown-item" href="<?= ruta('cg') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Competencias Genéricas
            </a>
            <a class="dropdown-item" href="<?= ruta('cd') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Competencias Disciplinares
            </a>
            <a class="dropdown-item" href="<?= ruta('desagregado') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Desagregados de contenido
            </a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarHerramienta" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-"></i> Herramientas
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a href="<?= ruta('ea') ?>" class="dropdown-item">Estrategias de Enseñanza/Aprendizaje</a>
            <a href="<?= ruta('instrumeval') ?>" class="dropdown-item">Instrumentos de evaluación</a>
            <a href="<?= ruta('producto') ?>" class="dropdown-item">Productos de ADA</a>
            <a href="<?= ruta('recurso') ?>" class="dropdown-item">Recursos para ADA</a>
            <a href="<?= ruta('rubrica') ?>" class="dropdown-item">Rúbricas</a>
            <a href="<?= ruta('herramienta') ?>" class="dropdown-item">Herramientas de Moodle</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarReportes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-"></i> Reportes
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a href="<?= ruta('reporte.pd') ?>" class="dropdown-item">Planeaciones Didácticas</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-"></i> Administración
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a href="<?= ruta('usuario.index') ?>" class="dropdown-item">
              <i class="fa fa-user" aria-hidden="true"></i> Usuarios
            </a>
            <a class="dropdown-item" href="<?= ruta('modulo') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Módulos
            </a>
            <a class="dropdown-item" href="<?= ruta('permiso') ?>">
              <i class="fa fa-" aria-hidden="true"></i> Permisos
            </a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user"></i> Acerca de...
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="<?= ruta('ayuda') ?>">
              <i class="fa fa-sign-out" aria-hidden="true"></i> Ayuda
            </a>
            <a class="dropdown-item" href="<?= ruta('autores') ?>">
              <i class="fa fa-sign-out" aria-hidden="true"></i> Autores
            </a>
            <a class="dropdown-item" href="<?= ruta('logout') ?>">
              <i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar sesión
            </a>
          </div>
        </li>
      <?php } else { ?>
        <li class="nav-item <?= $ruta=='login' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= ruta('login') ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> Iniciar sesión</a>
        </li>
      <?php } ?>
      
    </ul>



  </div>
</nav>