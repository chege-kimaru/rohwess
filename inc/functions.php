<?php
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * @return \Symfony\Component\HttpFoundation\Request
 */
function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

function redirect($path, $extra = []) {
    $response = Response::create(null, Response::HTTP_FOUND, ['Location' => '/rohwess_upload_3/'.$path]);

    if(key_exists('cookies', $extra)) {
        foreach($extra['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }

    $response->send();
    exit;
}

function display_alerts($level, $messages = []) {
    $response = '<div class="alert alert-'.$level.' alert-dismissable">';
    foreach($messages as $message ) {
        $response .= "{$message}<br>";
    }
    $response .= '</div>';

    return $response;
}

function display_errors($bag = 'error') {
    global $session;

    if(!$session->getFlashBag()->has($bag)) {
        return;
    }

    $messages = $session->getFlashBag()->get($bag);

    return display_alerts('danger', $messages);
}

function display_success($bag = 'success') {
    global $session;

    if(!$session->getFlashBag()->has($bag)) {
        return;
    }

    $messages = $session->getFlashBag()->get($bag);

    return display_alerts('success', $messages);
}

function findUserByEmail($email) {
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch ( \Exception $e ) {
        throw $e;
    }
}

function findUserById($id) {
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch ( \Exception $e ) {
        throw $e;
    }
}

function createUser($firstName, $lastName, $email, $password) {
    global $db;

    try {
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password, role_id) VALUES (:first_name, :last_name, :email,  :password, 2)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->execute();
        return $db->lastInsertId();
    } catch ( \Exception $e ) {
        throw $e;
    }
}

function isAuthenticated() {
    if(!request()->cookies->has('access_token')) {
        return false;
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );

        return true;
    } catch (\Exception $e) {
        return false;
    }

}

function requireAuth() {
    if(!isAuthenticated()) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time()-3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('login.php', ['cookies' => [$accessToken]]);
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time()-3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('login.php', ['cookies' => [$accessToken]]);
    }

}

function isAdmin() {
    if(!isAuthenticated()) {
        return false;
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        return false;
    }

    return $jwt->is_admin;
}

function requireAdmin() {

    global $session;

    if(!isAuthenticated()) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time()-3600, '/', getenv('COOKIE_DOMAIN'));
        $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
        redirect('index.php', ['cookies' => [$accessToken]]);
    }


    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time()-3600, '/', getenv('COOKIE_DOMAIN'));
        $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
        redirect('index.php', ['cookies' => [$accessToken]]);
        exit;
    }

    if(!$jwt->is_admin) {
        $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
        redirect('index.php');
    }
}

function user($item = null) {
    if(!isAuthenticated()) {
        return false;
    }
    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time()-3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('login.php', ['cookies' => [$accessToken]]);
        exit;
    }

    $user = findUserById($jwt->sub);

    if(!$user) {
        return false;
    }

    if($item) {
        return $user[$item];
    }

    return $user;
}

function accessToken($item = null) {
    if(!isAuthenticated()) {
        return false;
    }
    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        return false;
    }

    if($item) {
        return $jwt->{$item};
    }

    return $jwt;
}


function updatePassword($password) {
    global $db;

    try {
        $stmt = $db->prepare('UPDATE users SET password=:password WHERE id = :userId');
        $stmt->execute([":password"=> $password, ":userId"=>accessToken('sub')]);
    } catch (\Exception $e) {
        return false;
    }

    return true;
}

function getAllUsers() {
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch ( \Exception $e ) {
        throw $e;
    }
}

function promote($user) {
    global $db;

    try {
        $stmt = $db->prepare("UPDATE users SET role_id=1 WHERE id = ?");
        $stmt->execute([$user['id']]);
    } catch (\Exception $e) {
        throw $e;
    }
}

function demote($user) {
    global $db;

    try {
        $stmt = $db->prepare("UPDATE users SET role_id=2 WHERE id = ?");
        $stmt->execute([$user['id']]);
    } catch (\Exception $e) {
        throw $e;
    }
}