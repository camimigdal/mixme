
          <div class="page-sidebar sidebar">
                <div class="page-sidebar-inner slimscroll">
                    <ul class="menu accordion-menu">
                        <li <?php if (isset($menuCompras)) echo 'class="active"'; ?>><a href="index.php" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-shopping-cart"></span><p>Ventas</p></a></li>
                        <li <?php if (isset($menuPreguntas)) echo 'class="active"'; ?>><a href="preguntas.php" class="waves-effect waves-button"><span class="menu-icon fa fa-comments"></span><p>Preguntas y Respuestas</p></a></li>
                        
                        <li class="droplink <?php if (isset($dashboard)) echo 'active'; ?>>"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-tags"></span><p>Market</p></a>
                            <ul class="sub-menu">
                                <li <?php if (isset($dashboard)) echo 'class="active"'; ?>><a href="productos.php" class="waves-effect waves-button"><p>Productos</p></a></li>
                                <li <?php if (isset($menuCategorias)) echo 'class="active"'; ?>><a href="categorias.php" class="waves-effect waves-button"><p>Categorias</p></a></li>
                                <li <?php if (isset($menuDestaques)) echo 'class="active"'; ?>><a href="ordenar-destaques.php" class="waves-effect waves-button"><p>Ordenar Destacados</p></a></li>
                                <li <?php if (isset($menuExclusivos)) echo 'class="active"'; ?>><a href="ordenar-exclusivos.php" class="waves-effect waves-button"><p>Ordenar Nuestras Mezclas</p></a></li>
                            </ul>
                        </li>
                        <li class="droplink <?php if (isset($mixer)) echo 'active'; ?>>"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-filter"></span><p>Mixer</p></a>
                            <ul class="sub-menu">
                                <li <?php if (isset($prodMixer)) echo 'class="active"'; ?>><a href="ingredientes-mixer.php" class="waves-effect waves-button"><p>Ingredientes</p></a></li>
                                <li <?php if (isset($menuCatMixer)) echo 'class="active"'; ?>><a href="categorias-mixer.php" class="waves-effect waves-button"><p>Categorias</p></a></li>
                                <li <?php if (isset($menuIconos)) echo 'class="active"'; ?>><a href="iconos.php" class="waves-effect waves-button"><p>Iconos</p></a></li>
                            </ul>
                        </li>
                        <li <?php if (isset($menuEnvios)) echo 'class="active"'; ?>><a href="envios.php" class="waves-effect waves-button"><span class="menu-icon fa fa-truck"></span><p>Costos de envío</p></a></li>
                        <li <?php if (isset($menuCupon)) echo 'class="active"'; ?>><a href="cupones.php" class="waves-effect waves-button"><span class="menu-icon fa fa-gift"></span><p>Códigos Descuento</p></a></li>
                        <li <?php if (isset($menuDatos)) echo 'class="active"'; ?>><a href="datos-transferencia.php" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-transfer"></span><p>Datos transferencia</p></a></li>
                        <li <?php if (isset($menuDescargas)) echo 'class="active"'; ?>><a href="archivos-descargas.php" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-download"></span><p>Catálogo Mayorista</p></a></li>
                        <li <?php if (isset($menuEstadisticas)) echo 'class="active"'; ?>><a href="estadisticas.php" class="waves-effect waves-button"><span class="menu-icon fa fa-line-chart"></span><p>Estadísticas</p></a></li>
                        <li <?php if (isset($menuFeed)) echo 'class="active"'; ?>><a href="feed.php" class="waves-effect waves-button"><span class="menu-icon fa fa-file-code-o"></span><p>Feed</p></a></li>
                        <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-home"></span><p>Sitio Web</p><span class="arrow"></span></a>
                            <ul class="sub-menu">
                                <li <?php if (isset($menuSlides)) echo 'class="active"'; ?>><a href="slides.php" class="waves-effect waves-button"><p>Slides</p></a></li>
                                <li <?php if (isset($menuSeo)) echo 'class="active"'; ?>><a href="seo.php" class="waves-effect waves-button"><p>SEO</p></a></li> 
                                <li <?php if (isset($menuScripts)) echo 'class="active"'; ?>><a href="scripts.php" class="waves-effect waves-button"><p>Scripts</p></a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- Page Sidebar Inner -->
            </div><!-- Page Sidebar -->