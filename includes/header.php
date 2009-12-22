<?php
   $array_path = explode("/", strtolower($_SERVER['REQUEST_URI']));
   $pagename = $array_path[count($array_path)-1];
?>

<div class="top_page">
    <div class="soporte left">
        <a href="#" onclick="Chat.open(); return false;"><img src="images/icon_support_on_line.png" alt="soporte on line" border="0" align="top" /></a>
        <a href="#" onclick="Chat.open(); return false;">Soporte en linea</a>
    </div>
    <div class="top_menu right">
        <ul>
            <li><a href="disenio_web.php">Dise&ntilde;o Web</a><span class="<?php echo $pagename=="disenio_web.php" ? "menu current" : "menu hover";?>"></span></li>
            <li><a href="disenio_grafico.php">Dise&ntilde;o Grafico</a><span class="<?php echo $pagename=="disenio_grafico.php" ? "menu current" : "menu hover";?>"></span></li>
            <li><a href="marketing_online.php">Marketing On-line</a><span class="<?php echo $pagename=="marketing_online.php" ? "menu current" : "menu hover";?>"></span></li>
            <li><a href="portfolio.php">Portfolio</a><span class="<?php echo $pagename=="portfolio.php" ? "menu current" : "menu hover";?>"></span></li>
            <li><a href="/blog">Blog</a><span class="menu hover"></span></li>
            <li><a href="contacto.php">Cont&aacute;ctenos</a><span class="<?php echo $pagename=="contacto.php" ? "menu current" : "menu hover";?>"></span></li>
        </ul>
    </div>
</div>

<div class="logo left">
    <a href="index.php"><img src="images/logo_mydesign.png" alt="Diseño de Paginas web - www.MyDesign.com.ar" border="0" /></a>
</div>

<div class="ultimos_trabajos left">
    <div class="arrow_left"><a href="#" onclick="SG.prev(); return false;"><img src="images/ico_left.png" alt="" /></a></div>
    <div id="cont_slider"></div>
    <div class="arrow_right"><a href="#" onclick="SG.next(); return false;"><img src="images/ico_right.png" alt="" /></a></div>
</div>