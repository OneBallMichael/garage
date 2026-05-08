<?php
header('Content-Type: application/json');
if (file_exists('event.json')) {
    echo file_get_contents('event.json');
} else {
    echo json_encode(null);
}
