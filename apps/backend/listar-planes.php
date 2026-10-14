<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->prepare('DELETE FROM plans WHERE id = 4 AND nombre = ?');
$stmt->execute(['Asado de prueba']);
echo 'borrados: ' . $stmt->rowCount() . PHP_EOL;
foreach ($db->query('SELECT id, nombre, estado FROM plans') as $row) {
    echo $row['id'] . ' | ' . $row['nombre'] . ' | ' . $row['estado'] . PHP_EOL;
}
