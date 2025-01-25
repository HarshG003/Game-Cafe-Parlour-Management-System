<?php
require_once 'config.php';

$id = $_POST['id'];
$gameName = $_POST['gameName'];
$genres = $_POST['genres'];
$description = $_POST['description'];
$imageUpload = $_FILES['imageUpload'];

$stmt = $conn->prepare("UPDATE games SET name = ?, genres = ?, description = ?, image = ? WHERE id = ?");
$stmt->bind_param("ssssi", $gameName, $genres, $description, $imageUpload['name'], $id);
$stmt->execute();

echo json_encode(['message' => 'Game updated successfully']);