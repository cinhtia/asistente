<div class="sidebar-sticky">
	<?php 
	$existe_ul = false;
	foreach (MENU_SIDEBAR as $menu) {
		if($menu['tipo'] == 'item'){
			if(!$existe_ul){ $existe_ul = true; ?>
				<ul class="nav flex-column mb-2">
			<?php } ?>

			<li class="nav-item" id="mnu<?= $menu['ruta'] ?>" >
				<a class="nav-link <?= $menu['ruta'] === $ruta ? 'active' : '' ?> "href="<?= ruta($menu['ruta'], isset($menu['params']) ? $menu['params'] : null  ) ?>#mnu<?= $menu['ruta'] ?>">
					<span data-feather="home"></span>
					<i aria-hidden="true" class="fa fa-<?= $menu['faicon'] ? $menu['faicon'] : 'angle-right' ?>"></i> <?= $menu['label'] ?> <span class="sr-only">(current)</span>
				</a>
			</li>
			<?php
		}else if($menu['tipo'] == 'seccion'){
			if($existe_ul){ $existe_ul = false; ?>
				</ul>
			<?php } ?>
			<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
				<span><?= $menu['label'] ?></span>
			</h6>
			<?php
		}
	}
	if($existe_ul){ print "</ul>"; $existe_ul = false; }
	?>
</div>