<?php    
   
    //Función mediante la cual se creará un fichero de contadores en formato xml. Comprueba si el fichero existe, si existe no hace nada y si no existe genera la raiz del fichero xml que contendrá todos los contadores.
    function CrearArchivoContadores()//($nombre, $usuario)
    {
        //$archivoXml = "gs://contadores-160617.appspot.com/contadores.xml";
        $archivoXml = "contadores-160617.appspot.com/contadores.xml";
                
        if(!file_exists($archivoXml))
        {
            $dom = new SimpleXMLElement("<raiz></raiz>");
            $dom->asXML($archivoXml);               
        }
        
    }

    function CrearContador($nombre, $usuario)
    {
    	//$archivoXml = "gs://contadores-160617.appspot.com/contadores.xml";
        $archivoXml = "contadores-160617.appspot.com/contadores.xml";
    	$crea = TRUE;

    	if(leer_contador($nombre, $usuario) == -1) {
            $dom = simplexml_load_file($archivoXml);
            $nuevo_contador = $dom->addChild('contador');
            $nuevo_contador->addChild('usuario', $usuario);
            $nuevo_contador->addChild('nombre', $nombre);
            $nuevo_contador->addChild('valor', 0);
            $dom->asXML($archivoXml);
        } else {
            $crea = FALSE;            
        }
            
    	return $crea;
    }


    //Función para leer el valor del contador
    //Recibe el nombre del contador y el usuario para localizarlo
    //Si el contador existe devuelve su valor y si no existe devuelve false
    function leer_contador($nombre, $usuario)
    {
        //$archivoXml = "gs://contadores-160617.appspot.com/contadores.xml";
        $archivoXml = 'contadores-160617.appspot.com/contadores.xml';
        
       
	        $dom = simplexml_load_file($archivoXml);
	        
	        $encontrado = false;
	        $i=0;
	        
	        while(($i<$dom->contador->count())&&($encontrado==false))
	        {
	            if(($dom->contador[$i]->usuario==$usuario)&&($dom->contador[$i]->nombre==$nombre))
	            $encontrado=true;
	            else $i++;
	        }
	        
	        if($encontrado) return (int)$dom->contador[$i]->valor;
	        else return -1;
   		
    }

    //Función para incrementar el valor del contador
    //Devuelve false si no se puede incrementar
    function incrementar_contador($nombre, $usuario)
    {
        //$archivoXml = "gs://contadores-160617.appspot.com/contadores.xml";
        $archivoXml = "contadores-160617.appspot.com/contadores.xml";
        
        $dom = simplexml_load_file($archivoXml);
        
       
	        $encontrado = false;        
	        $i=0;
	        while(($i<$dom->contador->count())&&($encontrado==false))
	        {
	            if (($dom->contador[$i]->usuario == $usuario) && ($dom->contador[$i]->nombre == $nombre)) {
	                $encontrado = true;
	            } else {
	                $i++;
	            }
	        }
	        
	        if($encontrado)
	        {
	            $dom->contador[$i]->valor++;
	            $dom->asXML($archivoXml);
	        }
	        
	        return $encontrado;
       
    }
    
    function asignar_contador($nombre, $usuario, $nuevo_valor)
    {
        //$archivoXml = "gs://contadores-160617.appspot.com/contadores.xml";
        $archivoXml = "contadores-160617.appspot.com/contadores.xml";
        
        $dom = simplexml_load_file($archivoXml);
        
        $encontrado = false;        
        $i=0;
        while(($i<$dom->contador->count())&&($encontrado==false))
        {
            if (($dom->contador[$i]->usuario == $usuario) && ($dom->contador[$i]->nombre == $nombre)) {
                $encontrado = true;
            } else {
                $i++;
            }
        }
        
        if($encontrado)
        {
            if ($nuevo_valor > 0) {
                $dom->contador[$i]->valor = $nuevo_valor;
                $dom->asXML($archivoXml);
            } else {
                throw new SoapFault("Client", "Debe enviar un valor numerico mayor que 0");
            }
        }
        
        return $encontrado;
    }
    
    function resetear_contador($nombre, $usuario) {
        //$archivoXml = "gs://contadores-160617.appspot.com/contadores.xml";
        $archivoXml = "contadores-160617.appspot.com/contadores.xml";
        
        $dom = simplexml_load_file($archivoXml);
        
        $encontrado = false;        
        $i=0;
        while(($i<$dom->contador->count())&&($encontrado==false))
        {
            if (($dom->contador[$i]->usuario == $usuario) && ($dom->contador[$i]->nombre == $nombre)) {
                $encontrado = true;
            } else {
                $i++;
            }
        }
        
        if($encontrado)
        {
            $dom->contador[$i]->valor = 0;
            $dom->asXML($archivoXml);
        }
        
        return $encontrado;
    }

    function eliminar_contador($nombre, $usuario) {
        //$archivoXml = "gs://contadores-160617.appspot.com/contadores.xml";
        $archivoXml = "contadores-160617.appspot.com/contadores.xml";
        
        $dom = simplexml_load_file($archivoXml);
        
        $encontrado = false;        
        $i=0;
        while(($i<$dom->contador->count())&&($encontrado==false))
        {
            if (($dom->contador[$i]->usuario == $usuario) && ($dom->contador[$i]->nombre == $nombre)) {
                $encontrado = true;
            } else {
                $i++;
            }
        }
        
        if($encontrado)
        {
            unset($dom->contador[$i]);
            $dom->asXML($archivoXml);
        } else {
            throw new SoapFault("Client", "Contador inexistente");
        }
        
        return $encontrado;
    }

    libxml_disable_entity_loader(false);
    ini_set('soap.wsdl_cache_enabled',0);
    ini_set('soap.wsdl_cache_ttl',0);
    
    $server = new SoapServer (null, array('uri'=>"http://localhost:8889/"));
    $server->addFunction ("CrearArchivoContadores");
    $server->addFunction ("CrearContador");
    $server->addFunction ("leer_contador");
    $server->addFunction ("incrementar_contador");
    $server->addFunction ("asignar_contador");
    $server->addFunction ("resetear_contador");
    $server->addFunction ("eliminar_contador");
    $server->handle();
    echo "Servidor contador operando..."
?>