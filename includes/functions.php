<?php
function uploadImagem($file)
{
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($file["name"]);
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    }
    return null;
}

function uploadMultiplasImagens($files)
{
    $uploadedFiles = [];
    foreach ($files['name'] as $index => $name) {
        $tmpName = $files['tmp_name'][$index];
        $targetFile = "uploads/" . basename($name);
        if (move_uploaded_file($tmpName, $targetFile)) {
            $uploadedFiles[] = $targetFile;
        }
    }
    return json_encode($uploadedFiles); // Salva como JSON no banco
}
?>