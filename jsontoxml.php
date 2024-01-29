<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON input from the POST request
    $jsonInput = file_get_contents('php://input');

    // Convert JSON to an associative array
    $jsonData = json_decode($jsonInput, true);

    if ($jsonData !== null) {
        // Convert the associative array to XML
        $xmlData = convertJsonToXml($jsonData);

        // Output the XML
        header('Content-Type: application/xml');
        echo format_xml($xmlData);
    } else {
        // Invalid JSON input
        header('HTTP/1.1 400 Bad Request');
        echo 'Invalid JSON input: ' . json_last_error_msg() . ' - ' . $jsonInput;
    }
} else {
    // Invalid request method
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Method Not Allowed';
}


// Function to convert an associative array to XML
function convertJsonToXml($array, $xml = null) {
    if ($xml === null) {
        $xml = new SimpleXMLElement('<root/>');
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (is_numeric($key)) {
                $key = 'item'; // when the key is numeric, use 'item' as the element name
            }
            convertJsonToXml($value, $xml->addChild($key));
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }

    return $xml->asXML();
}

// Function to format XML output
function format_xml($xml) {
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml);
    
    $formattedXml = '';
    foreach ($dom->documentElement->childNodes as $node) {
        $formattedXml .= $dom->saveXML($node);
    }

    return $formattedXml;
}
?>
