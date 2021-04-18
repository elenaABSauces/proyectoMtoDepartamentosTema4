<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Script Borrar</title>
    </head>
    <body>
        <?php
            require_once '../config/confDBPDO.php';
            echo "<h2>BorraDAW216DBProyectoTema51&1</h2>";
            try { // Bloque de código que puede tener excepciones en el objeto PDO
                $miDB = new PDO(DNS,USER,PASSWORD); // creo un objeto PDO con la conexion a la base de datos
                
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establezco el atributo para la apariciopn de errores y le pongo el modo para que cuando haya un error se lance una excepcion
                
                $sql = <<<SQL
                    DROP TABLE T02_Departamento;

                    DROP TABLE T01_Usuario;
SQL;
                $miDB->exec($sql);

               
               echo "<p style='color:green;'>BORRADO CORRECTO</p>";
            }catch (PDOException $miExceptionPDO) { // Codigo que se ejecuta si hay alguna excepcion
                echo "<p style='color:red;'>ERROR</p>";
                echo "<p style='color:red;'>Código de error: ".$miExceptionPDO->getCode()."</p>"; // Muestra el codigo del error
                echo "<p style='color:red;'>Error: ".$miExceptionPDO->getMessage()."</p>"; // Muestra el mensaje de error
            }finally{
                unset($miDB);
            }

        ?> 
    </bod



