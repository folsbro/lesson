<?php
header("Content-Type: application/json");

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

function readJson($file) {
    return json_decode(file_get_contents(__DIR__ . "/data/$file"), true);
}

function writeJson($file, $data) {
    file_put_contents(__DIR__ . "/data/$file", json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
if ($request === "/api/characters" && $method === "GET") {
    echo json_encode(readJson("characters.json"));
    exit;
}
if (preg_match("/\/api\/characters\/(\d+)/", $request, $m) && $method === "GET") {
    $characters = readJson("characters.json");
    foreach ($characters as $char) {
        if ($char['id'] == $m[1]) {
            echo json_encode($char);
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(["error" => "Character not found"]);
    exit;
}
if ($request === "/api/characters" && $method === "POST") {
    $characters = readJson("characters.json");
    $input = json_decode(file_get_contents("php://input"), true);

    $newId = end($characters)['id'] + 1;
    $input['id'] = $newId;

    $characters[] = $input;
    writeJson("characters.json", $characters);

    echo json_encode(["success" => true, "id" => $newId]);
    exit;
}
if (preg_match("/\/api\/characters\/(\d+)/", $request, $m) && $method === "DELETE") {
    $characters = readJson("characters.json");
    $newCharacters = array_filter($characters, fn($c) => $c['id'] != $m[1]);

    if (count($newCharacters) === count($characters)) {
        http_response_code(404);
        echo json_encode(["error" => "Character not found"]);
    } else {
        writeJson("characters.json", array_values($newCharacters));
        echo json_encode(["success" => true]);
    }
    exit;
}
if ($request === "/api/locations" && $method === "GET") {
    echo json_encode(readJson("locations.json"));
    exit;
}

if ($request === "/api/episodes" && $method === "GET") {
    echo json_encode(readJson("episodes.json"));
    exit;
}
