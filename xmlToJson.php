<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if XML content is provided
    $xmlContent = file_get_contents('php://input');

    if (!empty($xmlContent)) {
        // Load XML content
        $xml = simplexml_load_string($xmlContent);

        // Convert to JSON
        $json = json_encode($xml);

        // Output JSON
        echo $json;
    } else {
        echo json_encode(['error' => 'XML content is empty']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
