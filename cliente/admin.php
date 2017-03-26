<!DOCTYPE html>
<html>
    <head>
        <title lang="es">Administrador de Contadores</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php        
            if (isset($_COOKIE['estilocss'])) {
                if(isset($_POST["css_contraste"]) AND ($_COOKIE['estilocss'] != "admin-contraste.css")) {                
                    $estilo_css = "admin-contraste.css";                
                    setcookie ("estilocss", "admin-contraste.css", time() + 3600);
                } elseif(isset($_POST["css_normal"]) AND ($_COOKIE['estilocss'] != "admin.css")) {
                    $estilo_css = "admin.css";                
                    setcookie ("estilocss", "admin.css", time() + 3600);
                } else {
                    $estilo_css = $_COOKIE['estilocss'];
                }
                
            } else {
                $estilo_css = "admin.css";
                setcookie ("estilocss", "admin.css", time() + 3600);
            }           

            echo '<link rel="stylesheet" type="text/css" href="css/' . $estilo_css . '">';
        ?>

        <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body onload="accesible_font()">
        
				
        <header> 

            <div id="hdr-box1" class="box"></div>
            <div id="hdr-box2" class="box"></div>
            <div id="hdr-box3" class="box"></div>
            <div id="hdr-box4" class="box"></div>
            <h1 lang="es">Panel de control para contadores</h1>
            <p lang="es">Administrador de Acciones</p>
        </header>
        
        <form method="POST">
        
        <nav>
            <!-- <ul>
                <li>Consultar</li>
                <li>Crear</li>                    
                <li>Incrementar</li>
                <li>Resetear</li>
                <li>Asignar</li>
                <li>Eliminar</li>
            </ul> -->
            
            <input type="submit" name="acciones" value ="Consultar">
            <input type="submit" name="acciones" value ="Crear">
            <input type="submit" name="acciones" value ="Incrementar">
            <input type="submit" name="acciones" value ="Resetear">
            <input type="submit" name="acciones" value ="Asignar">
            <input type="submit" name="acciones" value ="Eliminar">
            
        </nav>        
           
        <aside>             
            <fieldset>
                <legend>
                    Datos del contador
                </legend>
                <p lang="es">
                    <label for="usuario">Usuario</label>
                    <input type="text" name="usuario" id="usuario" required placeholder="Nombre de usuario" size="30" value="<?php echo (isset($_POST["acciones"])) ? $_POST["usuario"] : ""; ?>"/>
                </p>
                <p lang="es">
                    <label for="usuario">Contador</label>
                    <input type="text" name="contador" id="contador" required placeholder="Nombre del contador" size="30" value="<?php echo (isset($_POST["acciones"])) ? $_POST["contador"] : ""; ?>"/>
                </p>
                <p lang="es">
                    <label for="valor">Introduce Valor <em>(solo para la opción de asignar)</em></label>
                    <input type="number" name="valor" id="valor" pattern="\d+" placeholder="Número de visitas para asignar al contador" size="40" value="<?php echo (isset($_POST["acciones"])) ? $_POST["valor"] : ""; ?>"/>
                </p>
            </fieldset>              
        </aside>   
        <section>    
            <div id="infosistema">
                <p lang="es" class="infocab">Información del sistema</p>
                <div class="resultados">
                <?php
                    
                    libxml_disable_entity_loader(false);                    
            
                    /*if($_POST["consultar"] or $_POST["consultar"] or $_POST["crear"] or
                            $_POST["incrementar"] or $_POST["resetear"] or $_POST["asignar"] or $_POST["eliminar"]) {*/
                    if(isset($_POST["acciones"])) {
                        $location = "http://localhost:8889/server.php";
                        $client = new SoapClient (null, array('location'=>$location, 'uri'=>"http://localhost:8889",
                            'trace'=>1));
                    	
                        $client->CrearArchivoContadores();

                        switch($_POST["acciones"]) {
                            //if($_POST["consultar"]) {                   
                            case 'Consultar': {
                                $visitas = $client->leer_contador($_POST["contador"], $_POST["usuario"]);
                                if($visitas >= 0) {                                
                                    echo "<p lang='es'>Numero de visitas: <b>".$visitas."</b></p>";
                                } else {
                                    echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> no encontrado" . 
                                            " para el usuario <strong>" . $_POST["usuario"]  . "</strong></p>";
                                }
                                break;
                            } 
                            //elseif($_POST["crear"]) {                   
                            case 'Crear': {
                                if ($client->CrearContador($_POST["contador"], $_POST["usuario"])) {
                                    echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> creado" . 
                                            " para el usuario <strong>" . $_POST["usuario"]  . "</strong></p>";
                                } else {
                                    echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> no creado." . 
                                            " Ya existe para para el usuario <strong>" . $_POST["usuario"]  . "</strong></p>";
                                }          
                                break;
                            } 
                            //elseif($_POST["incrementar"]) {  
                            case 'Incrementar': {
                                $visitas = $client->leer_contador($_POST["contador"], $_POST["usuario"]);
                                if($visitas >= 0) {                                
                                    echo "<p lang='es'>Numero de visitas antes de incrementar: <b>".$visitas."</b></p>";
                                    $client->incrementar_contador($_POST["contador"], $_POST["usuario"]);
                                    echo "<p lang='es'>Numero de visitas actual: <b>". ($visitas + 1) ."</b></p>";
                                } else {
                                    echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> no encontrado" . 
                                            " para el usuario <strong>" . $_POST["usuario"]  . "</strong></p>";
                                }
                                break;
                            } 
                            //elseif($_POST["resetear"]) {  
                            case 'Resetear': {
                                if ($client->resetear_contador($_POST["contador"], $_POST["usuario"])) {
                                    echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> reseteado a "
                                            . "<strong>0</strong>";
                                } else {
                                    echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> no encontrado" . 
                                            " para el usuario <strong>" . $_POST["usuario"]  . "</strong></p>";
                                }
                                break;
                            } 
                            //elseif($_POST["asignar"]) {  
                            case 'Asignar': {
                                try {
                                    if ($client->asignar_contador($_POST["contador"], $_POST["usuario"], $_POST["valor"])) {
                                        echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> asignado nuevo valor "
                                                . "<strong>" . ($_POST["valor"] > 0 ? $_POST["valor"] : "0") . "</strong></p>";                                
                                    } else {
                                        echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> no encontrado" . 
                                                " para el usuario <strong>" . $_POST["usuario"]  . "</strong></p>";
                                    }
                                }
                                catch (SoapFault $error) {
                                    echo "<p lang='es'>Excepción capturada: <strong>" . $error->faultstring . "</strong></p>";
                                }
                                break;
                            } 
                            //elseif($_POST["eliminar"]) {  
                            case 'Eliminar': {
                                try {
                                    if ($client->eliminar_contador($_POST["contador"], $_POST["usuario"])) {
                                        echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> eliminado";
                                    } /*else {
                                        echo "<p lang='es'>Contador <strong>" . $_POST["contador"] . "</strong> no encontrado" . 
                                                " para el usuario <strong>" . $_POST["usuario"]  . "</strong></p>";
                                    }*/
                                } catch (SoapFault $error) {
                                    echo "<p lang='es'>Excepción capturada: <strong>" . $error->faultstring . "</strong></p>";
                                }
                                break;
                            }
                        }
                    }
                ?>
                </div>
            </div>
            
        </section>             
        </form>
<!--        <div id="accesibilidad" style="clear: both;">-->
        <form id="accesibilidad" style="clear: both;" method="POST">
            <nav>
                <div id="titulo_accesibilidad">
                    <p lang='es'>Accesibilidad</p>
                </div>
                <input id="normal_css" type="submit" name="css_normal" value ="Estilo Normal">
                <input id="contraste_css" type="submit" name="css_contraste" value ="Estilo Contraste"> 
                <input style="font-size: 14px;" id="fmin" type="button" name="fmin" value ="A" onclick="accesible_font_size(14)">
                <input style="font-size: 18px;" id="fmed" type="button" name="fmed" value ="A" onclick="accesible_font_size(18)">
                <input style="font-size: 22px;" id="fmax" type="button" name="fmax" value ="A" onclick="accesible_font_size(22)">
                
            </nav>
        </form>
<!--        </div>-->

        
        
        <footer>
            <p lang="es">Alonso Gómez Torres · Ingeniería de Servidores Web<br/>Master en Ciencia de datos e Ingeniería de Computadores</p>
        </footer>
    </body>
</html>
