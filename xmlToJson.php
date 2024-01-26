<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if XML content is provided
    $xmlContent = file_get_contents('php://input');

    if (!empty($xmlContent)) {
        // Try to load XML content
        libxml_use_internal_errors(true); // Enable internal XML errors handling
        $xml = simplexml_load_string($xmlContent);

        // Check for XML parsing errors
        if ($xml === false) {
            $errors = [];
            foreach(libxml_get_errors() as $error) {
                $errors[] = [
                    'code' => $error->code,
                    'message' => trim($error->message),
                ];
            }
            libxml_clear_errors();
            echo json_encode(['error' => 'XML parsing error', 'errors' => $errors]);
        } else {
            // Convert to JSON
            $json = json_encode($xml);

            // Check for JSON encoding errors
            if ($json === false) {
                echo json_encode(['error' => 'JSON encoding error']);
            } else {
                // Output JSON
                echo $json;
            }
        }
    } else {
        echo json_encode(['error' => 'XML content is empty']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
