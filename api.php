<?php
// Tell the browser this file returns JSON data
header("Content-Type: application/json");

// 1. Data Source
$fruits = ["Apple", "Banana", "Peach", "Grape", "Mango"];

// 2. Read parameters from GET or POST
$action = $_REQUEST["action"] ?? "";
$index  = $_REQUEST["index"] ?? "";

// 3. Process requests
if ($action === "get_all") {
    
    // 1 Parameter Request: Return all fruits
    echo json_encode([
        "status" => "success",
        "data" => $fruits
    ]);

} elseif ($action === "get_index") {

    // 2 Parameter Request: Check if the index is valid
    if (!is_numeric($index)) {
        echo json_encode([
            "status" => "error",
            "message" => "Please enter a valid number!"
        ]);
    } else {
        $i = (int)$index;

        if (isset($fruits[$i])) {
            echo json_encode([
                "status" => "success",
                "result" => "Element at index $i is: " . $fruits[$i]
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Index out of range! Use 0 to " . (count($fruits) - 1)
            ]);
        }
    }

} else {

    // Default error if no valid action parameter was sent
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing parameters."
    ]);

}