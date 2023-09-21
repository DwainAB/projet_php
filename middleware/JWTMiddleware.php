<?php

function verifyJWTToken() {
    $headers = apache_request_headers();
    $authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

    if (preg_match('/Bearer\s+(.*)$/i', $authorizationHeader, $matches)) {
        $token = $matches[1];

        try {
            // Vérification du token JWT en utilisant la clé secrète
            $decoded = JWT::decode($token, $yourSecretKey, ['HS256']);

            // Les informations de l'utilisateur sont maintenant dans $decoded
            $userId = $decoded->user_id;
            $email = $decoded->email;
            // Vous pouvez ajouter d'autres informations de l'utilisateur ici

            // Vous pouvez stocker ces informations dans une variable de session pour une utilisation ultérieure
            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $email;

        } catch (Exception $e) {
            // Le token n'est pas valide, renvoyez une erreur d'authentification
            http_response_code(401);
            echo json_encode(['error' => 'Authentication failed']);
            exit;
        }
    } else {
        // Aucun token n'a été fourni dans l'en-tête Authorization
        http_response_code(401);
        echo json_encode(['error' => 'Authentication failed']);
        exit;
    }
}

?>
