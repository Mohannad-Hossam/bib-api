<?php 
$message = "";

// The base address of your API
$apiUrl = "http://localhost:8000/api.php";

// Helper function to make API calls safely using cURL with a fallback
function makeApiCall($url) {
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);
    } else {
        // Fallback if cURL extension is not enabled in php.ini
        $response = @file_get_contents($url);
    }
    
    return json_decode($response, true);
}

// Default page load or "Print All" button (1 Parameter: action=get_all)
if (isset($_GET["print_all"]) || empty($_GET)) {
    $data = makeApiCall($apiUrl . "?action=get_all");

    if (isset($data["status"]) && $data["status"] === "success") {
        $message = "Array contents: " . implode(", ", $data["data"]);
    }
}

// "Print at Index" form submission (2 Parameters: action=get_index & index=X)
if (isset($_GET["index"]) && $_GET["index"] !== "") {
    $input = $_GET["index"];

    $data = makeApiCall($apiUrl . "?action=get_index&index=" . urlencode($input));

    if (isset($data["status"]) && $data["status"] === "success") {
        $message = $data["result"];
    } else {
        $message = $data["message"] ?? "Error fetching data.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Array Viewer (cURL Version)</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1e1e1e; color: #fff; text-align: center; padding-top: 50px; }
        .card { background: #2b2b2b; display: inline-block; padding: 20px 40px; border-radius: 8px; }
        form { margin-bottom: 15px; }
        input, button { padding: 8px; margin: 5px; border-radius: 4px; border: none; }
        button { background: #4CAF50; color: white; cursor: pointer; }
        .result { margin-top: 15px; background: #333; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="card">
    <h1>Array Viewer (cURL)</h1>

    <form method="GET">
        <button type="submit" name="print_all" value="true">Print All</button>
    </form>

    <form method="GET">
        <input type="text" name="index" placeholder="Enter index">
        <button type="submit">Print at Index</button>
    </form>

    <div class="result">
        <?php 
        if ($message != "") {
            echo htmlspecialchars($message);
        } else {
            echo "Click a button or type ?index=0 in the URL!";
        }
        ?>
    </div>
</div>

</body>
</html>