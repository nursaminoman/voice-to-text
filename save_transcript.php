<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['transcript'])) {
        $transcript = $data['transcript'];
        file_put_contents('transcript.txt', $transcript . "\n", FILE_APPEND);
        echo "Transcript saved successfully!";
    } else {
        echo "No transcript received!";
    }
} else {
    echo "Invalid request method!";
}
?>
