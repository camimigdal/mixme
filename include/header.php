<div class="button-back-to-top is-hidden"><span>Ir arriba</span></div>

<?php require_once(dirname(__FILE__).'/login.php') ?>

<section class="navigation">
    <form class="search-form" action="<?php echo WEB_ROOT ?>productos.php" method="GET" name="buscForm" id="buscForm">
        <div class="input-group">
            <input type="search" name="buscar" id="buscar" class="form-control search-input" placeholder="Buscar...">
            <span class="input-group-btn">
                    <button class="close-search" type="button"><i class="fas fa-times-circle"></i></button>
                </span>
        </div>
    </form>
    <header>
        <div class="top-bar">
            <div class="container">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-auto d-none d-lg-block p-0">
                        <div class="social-media">
                            <a href="https://www.instagram.com/mixmeargentina/" target="_blank" class="px-1"><i class="fab fa-instagram px-1"></i></a>
                            <a href="https://www.facebook.com/Mixme-104355035121030" target="_blank" class="px-1"><i class="fab fa-facebook-f px-1"></i></a>
                            <a href="#" target="_blank" class="px-1"><i class="fab fa-youtube px-1"></i></a>
                        </div>
                    </div>
                    <div class="col-auto col-lg-5 text-right p-0 ml-auto">
                        <div class="icons">
                            <ul class="list-inline m-0">
                                <?php if ($logueado) { ?>
                                    <li class="list-inline-item">
                                        <a href="<?php echo WEB_ROOT ?>index.php?salir=ok" class="item-link text-success"><i class="fas fa-user-check fa-lg px-1"></i> Salir</a>
                                    </li>

                                    <?php $descargas = $Obj->getDescargas();
                                    if (is_array($descargas)) {  ?>
                                        <?php foreach($descargas as $mat) { ?>
                                            <li class="list-inline-item"><a href="<?php echo WEB_ROOT ?>descargas/<?php echo $mat['des_nombre'] ?>" target="_blank" class="btn btn-primary pt-2 text-white"><i class="fa fa-file-pdf fa-lg d-inline"></i> Descargar Catálogo</a></li>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                <?php  } else { ?>
                                    <li class="list-inline-item"><a href="#" class="btn btn-primary text-white" data-toggle="modal" data-target="#modLogin"><i class="fas fa-user-lock fa-lg px-2 d-inline"></i> Mayoristas</a></li>
                                <?php } ?>
                                <li class="list-inline-item"><a href="javascript:void(0);" class="show-search"><img src="<?php echo WEB_ROOT ?>img/icon-search.svg" alt="Buscar"></a></li>
                                <li class="list-inline-item"><a href="#" data-toggle="modal" data-target="#pop_cart" id="icon_cart"><img src="<?php echo WEB_ROOT ?>img/icon-cart.svg" alt="Carrito de compras"><span></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-content">
            <div class="logo-mobile mr-auto">
                <img src="<?php echo WEB_ROOT ?>img/mixme-logo.svg" alt="Mixme Logo">
            </div>
            <div class="header-nav">
                <nav class="d-lg-flex align-items-center justify-content-between">
                    <ul class="primary-nav">
                        <!-- <li><a href="<?php echo WEB_ROOT ?>#productos"><img src="<?php echo WEB_ROOT ?>img/icon-mezclas.svg" alt="Nuestras mezclas" height="50"><span>Nuestras mezclas</span></a></li> -->
                        <li><a href="<?php echo WEB_ROOT ?>"><img src="<?php echo WEB_ROOT ?>img/home.svg" alt="Home" height="45"><span>Home</span></a></li>
                        <li><a href="<?php echo WEB_ROOT.'productos/' ?>"><img src="<?php echo WEB_ROOT ?>img/icon-market.svg" alt="Market" height="40"><span>Market</span></a></li>
                        <li><a href="<?php echo WEB_ROOT ?>mixer/" class="anim_mezclador"><img src="<?php echo WEB_ROOT ?>img/icon-mezclador.svg" alt="Mezclador" height="50"><span>Mezclador</span></a></li>                    </ul>
                    <div class="logo mx-auto">
                        <img src="<?php echo WEB_ROOT ?>img/mixme-logo.svg" alt="Mixme Logo">
                    </div>
                    <ul class="primary-nav">
                        <li><a href="<?php echo WEB_ROOT ?>nosotros.php"><img src="<?php echo WEB_ROOT ?>img/icon-mixme.svg" alt="Nosotros" height="40"><span>Nosotros</span></a></li>
                        <li><a href="<?php echo WEB_ROOT ?>#contacto"><img src="<?php echo WEB_ROOT ?>img/icon-contacto.svg" alt="Contacto" height="40"><span>Contactanos</span></a></li>
                        <li><a href="<?php echo WEB_ROOT ?>faqs.php"><img src="<?php echo WEB_ROOT ?>img/icon-faqs.svg" alt="Faqs" height="38"><span>FAQs</span></a></li>
                    </ul>
                </nav>
            </div>

            <div class="navicon">
                <a class="nav-toggle" href="#"><span></span></a>
            </div>
        </div>
    </header>
</section>