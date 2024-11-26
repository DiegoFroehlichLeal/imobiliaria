<?php
function uploadImagem($file, $pasta)
{
    $targetFile = $pasta . '/' . basename($file["name"]);
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    }
    return null;
}

function uploadMultiplasImagens($files, $pasta)
{
    $uploadedFiles = [];
    foreach ($files['name'] as $index => $name) {
        $tmpName = $files['tmp_name'][$index];
        $targetFile = $pasta . '/' . basename($name);
        if (move_uploaded_file($tmpName, $targetFile)) {
            $uploadedFiles[] = $targetFile;
        }
    }
    return json_encode($uploadedFiles); // Salva como JSON no banco
}
?>