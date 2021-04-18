<?php

    require_once '../config/config.php'; // incluyo el fichero de configuracion de la aplicacion
    
    if(isset($_REQUEST['insertar'])){ // si se ha pulsado insertar
        header('Location: '.PATH.'codigoPHP/altaDepartamento.php'); // redirige al archivo de alta de departamento
        exit;
    }
    if(isset($_REQUEST['importar'])){ // si se ha pulsado importar
        header('Location: '.PATH.'codigoPHP/importarDepartamentos.php'); // redirige al archivo de importar departamentos
        exit;
    }
    if(isset($_REQUEST['exportar'])){ // si se ha pulsado exportar
        header('Location: '.PATH.'codigoPHP/exportarDepartamentos.php'); // redirige al archivo de exportar departamentos
        exit;
    }
    if(isset($_REQUEST['mostrarCodigo'])){ // si se ha pulsado mostrar codigo
        header('Location: '.PATH.'codigoPHP/mostrarCodigo.php'); //redireige al archivo con los codigos de los archivos de la aplicacion
        exit;
    }
    
    if(isset($_REQUEST['volver'])){ // si se ha pulsado voler
        header('Location: '.PATHPROYECTOS.'proyectoDWES/indexProyectoDWES.php'); // redireige a la pagina de proyecto DWES
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Mto. de Departamentos Tema 4</title> 
        <meta charset="UTF-8">
        <meta name="viewport"   content="width=device-width, initial-scale=1.0">
        <meta name="robots"     content="index, follow">      
        <link rel="stylesheet"  href="../webroot/css/estilos.css"       type="text/css" >
        <link rel="icon"        href="../webroot/media/favicon.ico"    type="image/x-icon">
    </head>
    <body>
        <header>
            <h1>Mto. de Departamentos Tema 4</h1>
        </header>

        <main>

        <?php
        /**
         *   @author: Javier Nieto Lorenzo
         *   @since: 17/11/2020
         *   Mto de Departamentos Tema 4

        */ 
        
            require_once '../core/201109libreriaValidacion.php'; // incluyo la libreria de validacion para validar los campos de formulario
            require_once '../config/confDBPDO.php'; // incluyo el fichero de configuracion de acceso a la basde de datos
            
            define("OPCIONAL",0);// defino e inicializo la constante a 0 para los campos que son opcionales
            
            $entradaOK=true; // declaro la variable que determina si esta bien la entrada de los campos introducidos por el usuario
            
            $errorDescDepartamento = null; // inciializo la variable de errores de la descripcion del departamento
            
            $descDepartamento = null; // incializo la variable de la descripcion del departamento
        ?>
        
            <form  class="buscador" name="formularioBuscador" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <div>
                    <label for="DescDepartamento">Descripcion del Departamento</label>
                    <input type="text" id="DescDepartamento" name="DescDepartamento" placeholder="Introduzca Descripcion del Departamento" value="<?php 
                        echo (isset($_REQUEST['DescDepartamento'])) ? $_REQUEST['DescDepartamento'] : null; // si el campo esta correcto mantengo su valor en el formulario
                    ?>">
                    <?php
                        echo ($errorDescDepartamento!=null) ? "<span style='color:#FF0000'>".$errorDescDepartamento."</span>" : null;// si el campo es erroneo se muestra un mensaje de error
                    ?>
                </div>
                <button type="submit" name="Buscar">&#128270; Buscar</button>
            </form>
        
        <?php
            if (isset($_REQUEST['avanzarPagina'])) { // si se ha pulsado avanzar pagina
                $numPagina = $_REQUEST['avanzarPagina']; // el numero de pagina es el valor del boton avanzar pagina ($numero de pagina +1)
            } else if(isset($_REQUEST['retrocederPagina'])){ // si se ha pulsado retroceder pagina
                $numPagina = $_REQUEST['retrocederPagina']; // el numero de pagina es el valor del boton retroceder pagina ($numero de pagina -1)
            }else if(isset($_REQUEST['paginaInicial'])){ // si se ha pulsado pagina inicial
                $numPagina = $_REQUEST['paginaInicial']; // el numero de pagina es el valor del boton pagina inicial (1)
            }else if(isset($_REQUEST['paginaFinal'])){ // si se ha pulsado pagina final
                $numPagina = $_REQUEST['paginaFinal']; // el numero de pagina es el valor del boton pagina inicial ($numPaginaMaximo)
            } else{ // si no se ha pulsado ningun boton
                $numPagina = 1;
            }
                
            if(isset($_REQUEST["Buscar"])){ // compruebo que el usuario le ha dado a al boton de enviar y valido la entrada de todos los campos
                $errorDescDepartamento= validacionFormularios::comprobarAlfaNumerico($_REQUEST['DescDepartamento'], 255, 0, OPCIONAL); // comprueba que el valor del campo introducido sea alfanumerico
                
                if($errorDescDepartamento != null){ // compruebo si hay algun mensaje de error en algun campo
                    $entradaOK=false; // le doy el valor false a $entradaOK
                    $_REQUEST[$campo]=""; // si hay algun campo que tenga mensaje de error pongo $_REQUEST a null
                }

            }else{ // si el usuario no le ha dado al boton de enviar
                $_REQUEST['DescDepartamento']=""; // inicializo el valor del campo de busqueda a "" para que me muestre todos los departamentos al iniciar la aplicacion
            }
            
            if($entradaOK){ // si la entrada esta bien recojo los valores introducidos y hago su tratamiento
                $descDepartamento=$_REQUEST['DescDepartamento']; 
                
                //echo "<h2>Contenido tabla Departamentos</h2>";
                try { // Bloque de cÃ³digo que puede tener excepciones en el objeto PDO
                    $miDB = new PDO(DNS,USER,PASSWORD); // creo un objeto PDO con la conexion a la base de datos

                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establezco el atributo para la apariciopn de errores y le pongo el modo para que cuando haya un error se lance una excepcion
                    
                    
                    $sqlDepartamentosLimit = 'SELECT * FROM T02_Departamento WHERE T02_DescDepartamento LIKE "%":DescDepartamento"%" LIMIT '.(($numPagina-1)*MAX_NUMERO_REGISTROS).','.MAX_NUMERO_REGISTROS;
                    
                    $consultaDepartamentosLimit = $miDB->prepare($sqlDepartamentosLimit); // preparo la consulta
                    
                    $parametros = [":DescDepartamento" => $descDepartamento];
                    
                    $consultaDepartamentosLimit->execute($parametros); // ejecuto la consulta con los paremtros del array de parametros 
                    
                    $sqlNumeroDepartamentos = 'SELECT count(*) FROM T02_Departamento WHERE T02_DescDepartamento LIKE "%":DescDepartamento"%"';
                    
                    $consultaNumeroDepartamentos = $miDB->prepare($sqlNumeroDepartamentos); // preparo la consulta
                    $parametrosNumDepartamentos = [":DescDepartamento" => $descDepartamento];
                    $consultaNumeroDepartamentos->execute($parametrosNumDepartamentos); // ejecuto la consulta con los parametros del array de parametros 
                    
                    $resultado = $consultaNumeroDepartamentos->fetch(); // devuelve el numero de departamentos que hay en la posicion 0 de un array
                    
                    if(($resultado[0]%MAX_NUMERO_REGISTROS)==0){ // si el resto del numero de registros entre el numero de paginas es cero el maximo de paginas es su division
                        $numPaginaMaximo=$resultado[0]/MAX_NUMERO_REGISTROS;
                    }else{ // si el resto no es cero el numero de paginas es la divisopn redondeada a la baja + 1
                        $numPaginaMaximo =  floor($resultado[0]/MAX_NUMERO_REGISTROS)+1;
                    }
                    settype($numPaginaMaximo,"integer"); // cambio el tipo a integer $numPaginaMaximo
        ?>  
            
            <div class="content">             
                <table class="tablaDepartamentos">
                    <thead>
                        <tr>
                            <th>CodDepartamento</th>
                            <th>DescDepartamento</th>
                            <th>FechaBaja</th>
                            <th>VolumenNegocio</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php 
                    if($consultaDepartamentosLimit->rowCount()>0){ // si la consulta devuelve algun registro
                        $oDepartamento = $consultaDepartamentosLimit->fetchObject(); // Obtengo el primer registro de la consulta como un objeto
                        while($oDepartamento) { // recorro los registros que devuelve la consulta de la consulta 
                            $codDepartamento = $oDepartamento->T02_CodDepartamento; // variable que almacena el codigo del departamento

            ?>
                <tr <?php echo (($oDepartamento->T02_FechaBaja)==null)? "class='verde'" : "class='bajaLogica'";?>>
                    <td><?php echo $codDepartamento; // obtengo el valor del codigo del departamento del registro actual ?></td>
                    <td><?php echo $oDepartamento->T02_DescDepartamento; // obtengo el valor de la descripcion del departamento del registro actual ?></td>
                    <td><?php echo (($oDepartamento->T02_FechaBaja)==null)? "NULL": $oDepartamento->T02_FechaBaja; // obtengo el valor de la fecha de baja del departamento del registro actual ?></td>
                    <td><?php echo $oDepartamento->T02_VolumenNegocio; // obtengo el valor de la fecha de baja del departamento del registro actual ?></td>
                    <td>
                        <button name="editar"><a href="editarDepartamento.php?<?php echo "CodigoDepartamento=".$codDepartamento;?>">&#9999;&#65039;</a></button>
                        <button name="consultar"><a href="mostrarDepartamento.php?<?php echo "CodigoDepartamento=".$codDepartamento;?>">&#128220;</a></button>
                        <button name="borrar"><a href="bajaDepartamento.php?<?php echo "CodigoDepartamento=".$codDepartamento;?>">&#128465;&#65039;</a></button>
                        <?php if($oDepartamento->T02_FechaBaja==null){ // si la fecha de baja es null?>
                        <button name="baja"><a href="bajaLogicaDepartamento.php?<?php echo "CodigoDepartamento=".$codDepartamento;?>"><img class="imgButton" src="../webroot/media/baja.png"></a></button>
                        <?php }else{ ?>
                        <button name="alta"><a href="rehabilitacionDepartamento.php?<?php echo "CodigoDepartamento=".$codDepartamento;?>"><img class="imgButton" src="../webroot/media/alta.png"></a></button>
                        <?php } ?>
                    </td>
                </tr>
                    <?php 
                            $oDepartamento = $consultaDepartamentosLimit->fetchObject(); // guardo el registro actual como un objeto y avanzo el puntero al siguiente registro de la consulta 
                        }

                    }else{
                    ?>
                <tr>
                    <td class="rojo">No Hay ningun departamento con esa descripcion</td>
                </tr>
                    <?php }?>
                </tbody>
                </table>
                <?php if($consultaDepartamentosLimit->rowCount()>0){ // si la consulta devuelve algun registro?>
                <form name="formularioPaginacion" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <button <?php echo ($numPagina==1)? "hidden" : null;?> type="submit" name="paginaInicial" value="1"><img class="imgPaginacion" src="../webroot/media/pagInicial.png"></button>
                    <button <?php echo ($numPagina==1)? "hidden" : null;?> type="submit" name="retrocederPagina" value="<?php echo $numPagina-1;?>"><img class="imgPaginacion" src="../webroot/media/pagAnterior.png"></button>
                    <div><?php echo $numPagina." de ".$numPaginaMaximo?></div>
                    <button <?php echo ($numPagina>=$numPaginaMaximo)? "hidden" : null;?> type="submit" name="avanzarPagina" value="<?php echo $numPagina+1;?>"><img class="imgPaginacion" src="../webroot/media/pagSiguiente.png"></button>
                    <button <?php echo ($numPagina>=$numPaginaMaximo)? "hidden" : null;?> type="submit" name="paginaFinal" value="<?php echo $numPaginaMaximo;?>"><img class="imgPaginacion" src="../webroot/media/pagFinal.png"></button>
                </form>
                <?php } ?>
                <form name="formularioBotones" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <button type="submit" name="importar" ><img src="../webroot/media/importar.png" alt="importar"> Importar</button>
                    <button type="submit" name="exportar" ><img src="../webroot/media/exportar.png" alt="exportar"> Exportar</button>
                    <button type="submit" name="insertar" ><img src="../webroot/media/insertar.png" alt="insertar"> Insertar</button>
                    <button type="submit" name="mostrarCodigo"> Mostrar codigo</button>
                    <button type="submit" name="volver" value="Volver">&#11152; Volver</button>
                </form>
        <?php
                
                
                }catch (PDOException $miExceptionPDO) { // Codigo que se ejecuta si hay alguna excepcion
                    echo "<p style='color:red;'>ERROR EN LA CONEXIÓN</p>";
                    echo "<p style='color:red;'>Código de error: ".$miExceptionPDO->getCode()."</p>"; // Muestra el codigo del error
                    echo "<p style='color:red;'>Error: ".$miExceptionPDO->getMessage()."</p>"; // Muestra el mensaje de error
                    die(); // Finalizo el script
                }finally{ // codigo que se ejecuta haya o no errores
                    unset($miDB);// destruyo la variable 
                }
            } 
        ?>
              </div> 
        </main> 
        <footer>
            <address> <a href="../../index.html">&Elena de AntOn &copy; 2020/21</a> <a href="https://github.com/elenaABSauces/proyectoMtoDepartamentosTema4" target="_blank"><img src="webroot/media/github.png" widht="20" height="20" /></a></address>
        </footer>
    </body>
</html>