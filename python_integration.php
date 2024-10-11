<?php
// Función para ejecutar un script Python y devolver el resultado
function ejecutar_python($script_path, $args = []) {
    // Crear un archivo temporal para almacenar los argumentos
    $tmp_file = tempnam(sys_get_temp_dir(), 'py_args_');
    file_put_contents($tmp_file, json_encode($args));

    // Construir el comando para ejecutar el script Python con el archivo temporal como argumento
    $command = escapeshellcmd("python3 $script_path") . " " . escapeshellarg($tmp_file);

    // Ejecutar el comando y capturar la salida
    $output = shell_exec($command . " 2>&1"); // Captura también errores de shell
    unlink($tmp_file); // Eliminar el archivo temporal después de usarlo

    // Decodificar la salida de Python (asumiendo que es JSON)
    $result = json_decode($output, true);

    return $result;
}

// Función específica para calcular las similitudes usando Python
function calcular_similitudes_python($reporte, $reportes_encontrados) {
    $script_path = 'calcular_similitudes.py'; // Cambia esto al camino de tu script Python

    // Preparar los datos para pasar a Python
    $args = [
        'reporte' => $reporte,
        'reportes_encontrados' => $reportes_encontrados
    ];

    // Llamar a la función que ejecuta Python
    return ejecutar_python($script_path, $args);
}
?>
