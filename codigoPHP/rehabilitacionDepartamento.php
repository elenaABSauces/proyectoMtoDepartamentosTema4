<?php
/**
 *   @author: Javier Nieto Lorenzo
 *   @since: 17/11/2020
 *   Rehabilitacion Departamentos
 */

require_once '../config/config.php'; // incluyo el fichero de configuracion de la aplicacion
if (isset($_REQUEST['Cancelar'])) { // si se ha pulsado el boton cancelar
    header('Location: ' . PATH . 'codigoPHP/mtoDepartamentos.php');  // redirige a la pagina principal de la aplicacion
    exit;
}

require_once '../core/201109libreriaValidacion.php'; // incluyo la libreria de validacion para validar los campos de formulario
require_once '../config/confDBPDO.php'; // incluyo el fichero de configuracion de acceso a la basde de datos


try { // Bloque de código que puede tener excepciones en el objeto PDO
    $miDB = new PDO(DNS, USER, PASSWORD); // creo un objeto PDO con la conexion a la base de datos

    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establezco el atributo para la apariciopn de errores y le pongo el modo para que cuando haya un error se lance una excepcion

    $sql = "SELECT * FROM Departamento WHERE CodDepartamento=:CodDepartamento";

    $consultaObtencionDepartamento = $miDB->prepare($sql); // preparo la consulta

    $parametros = [":CodDepartamento" => $_REQUEST['CodigoDepartamento']]; // asigno los valores del formulario en el array de parametros

    $consultaObtencionDepartamento->execute($parametros); // ejecuto la consulta pasando los parametros del array de parametros
    
    $oDepartamento = $consultaObtencionDepartamento->fetchObject(); //guarda en la variable el resultado de la consulta en forma de objeto
    
    $codDepartamento = $oDepartamento->CodDepartamento; // guarda el codigo de departamento en una variable
    $descDepartamento = $oDepartamento->DescDepartamento; // guarda la descripcion del departamento en una variable
    $fechaBaja = $oDepartamento->FechaBaja; // guarda la fecha de baja del departamento en una variable
    $volumenNegocio = $oDepartamento->VolumenNegocio; // guarda el volumen de negocio del departamento en una variable
    
    if (isset($_REQUEST['Aceptar'])) { // si la entrada esta bien recojo los valores introducidos y hago su tratamiento
        $sqlUpdate = "UPDATE Departamento SET FechaBaja=:FechaBaja WHERE CodDepartamento=:CodDepartamento";

        $consultaUpdate = $miDB->prepare($sqlUpdate); // preparo la consulta

        $parametros = [":FechaBaja" => null, // asigno los valores del formulario en el array de parametros
                       ":CodDepartamento" => $_REQUEST['CodigoDepartamento']]; 
        $consultaUpdate->execute($parametros); // ejecuto la consulta pasando los parametros del array de parametros

        header('Location: ' . PATH . 'codigoPHP/mtoDepartamentos.php'); // redirige a la pagina principal de la aplicacion
        exit;
    }
    
} catch (PDOException $miExceptionPDO) { // Codigo que se ejecuta si hay alguna excepcion
    echo "<p style='color:red;'>ERROR EN LA CONEXION</p>";
    echo "<p style='color:red;'>Código de error: " . $miExceptionPDO->getCode() . "</p>"; // Muestra el codigo del error
    echo "<p style='color:red;'>Error: " . $miExceptionPDO->getMessage() . "</p>"; // Muestra el mensaje de error
    die(); // Finalizo el script
} finally { // codigo que se ejecuta haya o no errores
    unset($miDB); // destruyo la variable 
}



    ?> 
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Reabilitacion Departamento</title>
            <meta name="viewport"   content="width=device-width, initial-scale=1.0">
            <meta name="author"     content="Javier Nieto Lorenzo">
            <meta name="robots"     content="index, follow">      
            <link rel="stylesheet"  href="../webroot/css/estilos.css"       type="text/css" >
            <link rel="icon"        href="../webroot/media/favicon.ico"    type="image/x-icon">
        </head>
        <body>
            <header>
                <h1>Mto. de Departamentos - Reabilitacion Departamento</h1>
            </header>
            <main class="flex-container-align-item-center">
                <form name="departamento" action="<?php echo $_SERVER['PHP_SELF']."?CodigoDepartamento=".$codDepartamento; ?>" method="post">

                    <div>
                        <label for="CodDepartamento">Codigo de Departamento</label>
                        <input type="text" id="CodDepartamento" name="CodDepartamento" value="<?php echo $codDepartamento?>" readonly>
                    </div>
                    <div>
                        <label for="DescDepartamento">Descripcion del Departamento</label>
                        <input type="text" id="DescDepartamento" name="DescDepartamento" value="<?php echo $descDepartamento?>" readonly>
                    </div>                
                    <div>
                        <label for="FechaBaja">Fecha Baja</label>
                        <input type="text" id="FechaBaja" name="FechaBaja" value="NULL" readonly>
                    </div>
                    <div>
                        <label for="VolumenNegocio">Volumen Negocio</label>
                        <input type="text" id="VolumenNegocio" name="VolumenNegocio" value="<?php echo $volumenNegocio?>" readonly>
                    </div>
                    <div class="flex-container-align-item-center">
                        <input class="button" type="submit" value="Aceptar" name="Aceptar">
                        <input class="button" type="submit" value="Cancelar" name="Cancelar">
                    </div>

                </form>

        </main>
        <footer class="fixed">
            <address> <a href="../../index.html">&Elena de Ant�n &copy; 2020/21</a> <a href="https://github.com/elenaABSauces/proyectoTema4" target="_blank"><img src="webroot/media/github.png" widht="20" height="20" /></a></address>
        </footer>
    </body>
</html>