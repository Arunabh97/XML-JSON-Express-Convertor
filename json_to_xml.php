<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON input from the POST request
    $jsonInput = file_get_contents('php://input');

    // Convert JSON to an associative array
    $jsonData = json_decode($jsonInput, true);

    if ($jsonData !== null) {
        // Convert the associative array to XML
        $xmlData = array_to_xml($jsonData);

        // Output the XML
        header('Content-Type: application/xml');
        echo $xmlData;
    } else {
        // Invalid JSON input
        header('HTTP/1.1 400 Bad Request');
        echo 'Invalid JSON input';
    }
} else {
    // Invalid request method
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Method Not Allowed';
}

// Function to convert an associative array to XML
function array_to_xml($array, $xml = null) {
    if ($xml === null) {
        $xml = new SimpleXMLElement('<root/>');
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            array_to_xml($value, $xml->addChild($key));
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }

    return $xml->asXML();
}
?>