<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xmlContent = file_get_contents('php://input');

    if (!empty($xmlContent)) {
        libxml_use_internal_errors(true); // Enable internal XML errors handling
        $xml = simplexml_load_string($xmlContent);

        if ($xml === false) {
            $errors = [];
            foreach (libxml_get_errors() as $error) {
                $errors[] = [
                    'code' => $error->code,
                    'message' => trim($error->message),
                ];
            }
            libxml_clear_errors();
            echo json_encode(['error' => 'XML parsing error', 'errors' => $errors]);
        } else {
            $json = json_encode($xml);

            if ($json === false) {
                echo json_encode(['error' => 'JSON encoding error']);
            } else {
                echo $json;
            }
        }
    } else {
        echo json_encode(['error' => 'XML content is empty']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
}
?>
