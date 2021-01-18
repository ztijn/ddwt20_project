<?php
/**
 * Model
 * User: reinardvandalen
 * Date: 05-11-18
 * Time: 15:25
 */

/* Enable error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Connects to the database using PDO
 * @param string $host database host
 * @param string $db database name
 * @param string $user database user
 * @param string $pass database password
 * @return pdo object
 */
function connect_db($host, $db, $user, $pass){
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        echo sprintf("Failed to connect. %s",$e->getMessage());
    }
    return $pdo;
}

/**
 * Check if the route exist
 * @param string $route_uri URI to be matched
 * @param string $request_type request method
 * @return bool
 *
 */
function new_route($route_uri, $request_type){
    $route_uri_expl = array_filter(explode('/', $route_uri));
    $current_path_expl = array_filter(explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($route_uri_expl == $current_path_expl && $_SERVER['REQUEST_METHOD'] == strtoupper($request_type)) {
        return True;
    }
}

/**
 * Creates a new navigation array item using url and active status
 * @param string $url The url of the navigation item
 * @param bool $active Set the navigation item to active or inactive
 * @return array
 */
function na($url, $active){
    return [$url, $active];
}

/**
 * Creates filename to the template
 * @param string $template filename of the template without extension
 * @return string
 */
function use_template($template){
    $template_doc = sprintf("views/%s.php", $template);
    return $template_doc;
}

/**
 * Creates breadcrumb HTML code using given array
 * @param array $breadcrumbs Array with as Key the page name and as Value the corresponding url
 * @return string html code that represents the breadcrumbs
 */
function get_breadcrumbs($breadcrumbs) {
    $breadcrumbs_exp = '
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">';
    foreach ($breadcrumbs as $name => $info) {
        if ($info[1]){
            $breadcrumbs_exp .= '<li class="breadcrumb-item active" aria-current="page">'.$name.'</li>';
        }else{
            $breadcrumbs_exp .= '<li class="breadcrumb-item"><a href="'.$info[0].'">'.$name.'</a></li>';
        }
    }
    $breadcrumbs_exp .= '
    </ol>
    </nav>';
    return $breadcrumbs_exp;
}

/**
 * Pretty Print Array
 * @param $input
 */
function p_print($input){
    echo '<pre>';
    print_r($input);
    echo '</pre>';
}

/**
 * Creates HTML alert code with information about the success or failure
 * @param array $feedback Array with keys 'type' and 'message'.
 * @return string
 */
function get_error($feedback){
    $feedback = json_decode($feedback, True);
    $error_exp = '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
    return $error_exp;
}

/**
 * Creates navigation HTML code using given array
 * @param array $navigation Array with as Key the page name and as Value the corresponding url
 * @return string html code that represents the navigation
 */
function get_navigation($template, $active_id){
    $navigation_exp = '
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">Groningen Room Network</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">';
    foreach ($template as $id => $info) {
        if ($id == $active_id){
            $navigation_exp .= '<li class="nav-item active">';
            $navigation_exp .= '<a class="nav-link" href="'.$info['url'].'">'.$info['name'].'</a>';
        }else{
            $navigation_exp .= '<li class="nav-item">';
            $navigation_exp .= '<a class="nav-link" href="'.$info['url'].'">'.$info['name'].'</a>';
        }

        $navigation_exp .= '</li>';
    }
    $navigation_exp .= '
    </ul>
    </div>
    </nav>';
    return $navigation_exp;
}

function redirect($location){
    header(sprintf('Location: %s', $location));
    die();
}

function register_user($pdo, $user_info){
    /* Check if all fields are set */
    if (
        empty($user_info['username']) or
        empty($user_info['password']) or
        empty($user_info['role']) or
        empty($user_info['full_name']) or
        empty($user_info['birth_date']) or
        empty($user_info['biography']) or
        empty($user_info['stud_prof']) or
        empty($user_info['language']) or
        empty($user_info['email']) or
        empty($user_info['phone'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'All fields need to be filled in order for you to register!'
        ];
    }

    /* Check if user already exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$user_info['username']]);
        $user_exists = $stmt->rowCount();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Return error message for existing username */
    if ( !empty($user_exists) ) {
        return [
            'type' => 'danger',
            'message' => 'The username you entered does already exists!'
        ];
    }

    /* Hash password */
    $password = password_hash($user_info['password'], PASSWORD_DEFAULT);
    /* Save user to the database */
    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role, full_name, birth_date, biography, stud_prof, language, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $user_info['username'],
            $password,
            $user_info['role'],
            $user_info['full_name'],
            $user_info['birth_date'],
            $user_info['biography'],
            $user_info['stud_prof'],
            $user_info['language'],
            $user_info['email'],
            $user_info['phone']
        ]);
        $user_id = $pdo->lastInsertId();
    } catch (PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Login user and redirect */
    session_start();
    $_SESSION['user_id'] = $user_id;
    $feedback = [
        'type' => 'success',
        'message' => sprintf('%s, your account was successfully created!', $user_info['username'])
    ];
    redirect(sprintf('/ddwt20_project/myaccount/?error_msg=%s',
        json_encode($feedback)));

}

function add_room($pdo, $room_info, $current_user){
    /* Check if all fields are set */
    if (
        empty($room_info['Address']) or
        empty($room_info['Type']) or
        empty($room_info['Price']) or
        empty($room_info['Size']) or
        empty($room_info['Status'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }

    /* Check data type */
    if (!is_numeric($room_info['Price'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field price.'
        ];
    }

    if (!is_numeric($room_info['Size'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field size.'
        ];
    }

    /* Check if room already exists */
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE address = ?');
    $stmt->execute([$room_info['Address']]);
    $serie = $stmt->rowCount();
    if ($serie){
        return [
            'type' => 'danger',
            'message' => 'This Room was already added.'
        ];
    }
    /* Add room */
    $stmt = $pdo->prepare("INSERT INTO rooms (owner, address, type, price, size, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $current_user,
        $room_info['Address'],
        $room_info['Type'],
        $room_info['Price'],
        $room_info['Size'],
        $room_info['Status']
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("room '%s' added to room page .", $room_info['Address'])
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. The room was not added. Please try it again.'
        ];
    }
}

function update_room($pdo, $room_info, $current_user){
    /* Check if all fields are set */
    if (
        empty($room_info['Address']) or
        empty($room_info['Type']) or
        empty($room_info['Price']) or
        empty($room_info['Size']) or
        empty($room_info['Status'])

    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }

    /* Check data type */
    if (!is_numeric($room_info['Price'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field price.'
        ];
    }

    if (!is_numeric($room_info['Size'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. You should enter a number in the field size.'
        ];
    }

    /* Get current room */
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE room_id = ?');
    $stmt->execute([$room_info['room_id']]);
    $room = $stmt->fetch();
    $current_name = $room['address'];

    /* Check if serie already exists */
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE address = ?');
    $stmt->execute([$room_info['Address']]);
    $room = $stmt->fetch();
    if ($room_info['Address'] == $room['address'] and $room['address'] != $current_name){
        return [
            'type' => 'danger',
            'message' => sprintf("The name of the room cannot be changed. %s already exists.", $room_info['address'])
        ];
    }

    /* Update Room */
    $stmt = $pdo->prepare("UPDATE rooms SET owner = ?, address = ?, type = ?, price = ?, size = ?, status = ? WHERE room_id = ?");
    $stmt->execute([
        $current_user,
        $room_info['Address'],
        $room_info['Type'],
        $room_info['Price'],
        $room_info['Size'],
        $room_info['Status'],
        $room_info['room_id'],
    ]);
    $updated = $stmt->rowCount();
    if ($updated ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Room '%s' was edited!", $room_info['Address'])
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'The room was not edited. No changes were detected.'
        ];
    }
}

function get_user_id(){
    if (isset($_SESSION['user_id'])){
        return $_SESSION['user_id'];
    } else {
        return False;
    }
}

function get_user($pdo, $id){
    $stmt = $pdo->prepare('SELECT full_name FROM users where user_id = ?');
    $stmt->execute([$id]);
    $user_name = $stmt->fetch();
    return sprintf("%s", htmlspecialchars($user_name['full_name']));
}

function user_information($pdo, $id){
    $stmt = $pdo->prepare('SELECT * FROM users where user_id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    return $user;
}

function room_information($pdo, $id){
    $stmt = $pdo->prepare('SELECT * FROM rooms where room_id = ?');
    $stmt->execute([$id]);
    $room = $stmt->fetch();
    return $room;
}

function count_users($pdo){
    /* user count */
    $stmt = $pdo->prepare('SELECT * FROM users');
    $stmt->execute();
    $user_count = $stmt->rowCount();
    return $user_count;
}

function count_rooms($pdo){
    /* room count */
    $stmt = $pdo->prepare('SELECT * FROM rooms');
    $stmt->execute();
    $series = $stmt->rowCount();
    return $series;
}

function count_rooms_available($pdo){
    /* room count */
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE status = "available"');
    $stmt->execute();
    $series = $stmt->rowCount();
    return $series;
}

function logout_user(){
    /* empty the session and than destroy it */
    session_unset();
    session_destroy();
    $feedback = [
        'type' => 'success',
        'message' => sprintf('You were logged out')
    ];

    /* Redirect to homepage */
    redirect(sprintf('/ddwt20_project/?error_msg=%s', json_encode($feedback)));
}

function edit_user($pdo, $user_info){

    /* Check if all fields are set */
    if (
        empty($user_info['username']) or
        empty($user_info['full_name']) or
        empty($user_info['birth_date']) or
        empty($user_info['biography']) or
        empty($user_info['stud_prof']) or
        empty($user_info['language']) or
        empty($user_info['email']) or
        empty($user_info['phone'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Not all fields were filled in.'
        ];
    }

    /* Update user */
    $stmt = $pdo->prepare("UPDATE users SET username = ?, full_name = ?, birth_date = ?, biography = ?, stud_prof = ?, language = ?, email = ?, phone = ? WHERE user_id = ?");
    $stmt->execute([
        $user_info['username'],
        $user_info['full_name'],
        $user_info['birth_date'],
        $user_info['biography'],
        $user_info['stud_prof'],
        $user_info['language'],
        $user_info['email'],
        $user_info['phone'],
        $_SESSION['user_id']
    ]);
    $updated = $stmt->rowCount();
    if ($updated ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Your account was edited!")
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Your account was not edited. No changes were detected.'
        ];
    }
}

function remove_user($pdo, $user_id){
    /* Delete User */
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        session_unset();
        session_destroy();
        return [
            'type' => 'success',
            'message' => sprintf("Bye, your account has been removed!")
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. Your account has not been removed.'
        ];
    }
}

function check_login(){
    /* Check if a session already been started */
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (isset($_SESSION['user_id'])) {
        return True;
    } else {
        return False;
    }
}

function login_user($pdo, $form_data){
    /* Check if all fields are set */
    if (
        empty($form_data['username']) or
        empty($form_data['password'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'You should enter a username and password.'
        ];
    }

    /* Check if user exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_info = $stmt->fetch();
    } catch (PDOException $e){
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Return error message for wrong username */
    if (empty($user_info)){
        return [
            'type' => 'danger',
            'message' => 'The entered username does not exist in the database'
        ];
    }

    /* Check password */
    if ( !password_verify($form_data['password'], $user_info['password']) ){
        return [
            'type' => 'danger',
            'message' => 'The password you entered is incorrect!'
        ];
    } else {
        session_start();
        $_SESSION['user_id'] = $user_info['user_id'];
        $feedback = [
            'type' => 'success',
            'message' => sprintf('%s, you were logged in successfully!', get_user($pdo, $_SESSION['user_id']))
        ];
        redirect(sprintf('/ddwt20_project/myaccount/?error_msg=%s', json_encode($feedback)));
    }
}

function get_rooms($pdo){
    $stmt = $pdo->prepare('SELECT * FROM rooms');
    $stmt->execute();
    $rooms = $stmt->fetchAll();
    $rooms_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($rooms as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $rooms_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $rooms_exp;
}

function get_rooms_available($pdo){
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE status = "available"');
    $stmt->execute();
    $rooms = $stmt->fetchAll();
    $rooms_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($rooms as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $rooms_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $rooms_exp;
}

function get_room_table($rooms, $pdo){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <tr>
        <th scope="col">Room address</th>
        <th scope="col">Listed by</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($rooms as $key => $value){
        $table_exp .= '
        <tr>
            <th scope="row">'.$value['address'].'</th>
            <th scope="row">'.get_user($pdo, $value['owner']).'</th>
            <td><a href="/ddwt20_project/rooms/?room_id='.$value['room_id'].'" role="button" class="btn btn-primary">More info</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

function get_rooms_owned($user_info, $pdo){
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE owner = ?');
    $stmt->execute([$user_info]);
    $rooms = $stmt->fetchAll();
    $rooms_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($rooms as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $rooms_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $rooms_exp;

}

function get_rooms_owned_table($rooms_owned){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <tr>
        <th scope="col">Room address</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($rooms_owned as $key => $value){
        $table_exp .= '
        <tr>
            <th scope="row">'.$value['address'].'</th>
            <td><a href="/ddwt20_project/rooms/?room_id='.$value['room_id'].'" role="button" class="btn btn-primary">More info</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

function remove_room($pdo, $room_id){
    /* Get room info */
    $room_info = room_information($pdo, $room_id);

    /* Delete optins */
    $stmt = $pdo->prepare("DELETE FROM optins WHERE room = ?");
    $stmt->execute([$room_id]);
    /* Delete leases */
    $stmt = $pdo->prepare("DELETE FROM leases WHERE room = ?");
    $stmt->execute([$room_id]);
    /* Delete Room */
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE room_id = ?");
    $stmt->execute([$room_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("room '%s' was removed successfully!", $room_info['address'])
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The room was not removed.'
        ];
    }
}

function optin_room($pdo, $room_id, $user_id){
    /* Get room info */
    $room_info = room_information($pdo, $room_id);

    /* Check if optin already exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM optins WHERE tenant = ? AND room = ? ');
        $stmt->execute([$user_id, $room_info['room_id']]);
        $user_exists = $stmt->rowCount();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('There was an error: %s', $e->getMessage())
        ];
    }

    /* Return error message for existing username */
    if ( !empty($user_exists) ) {
        return [
            'type' => 'danger',
            'message' => 'You already opted in to this room'
        ];
    }

    /* Add room */
    $stmt = $pdo->prepare("INSERT INTO optins (room, tenant) VALUES (?, ?)");
    $stmt->execute([
        $room_info['room_id'],
        $user_id
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("You succesfully opted in to the room!")
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. The optin did not succeed. Please try it again.'
        ];
    }
}

function get_rooms_optin($user_info, $pdo){
    $stmt = $pdo->prepare('SELECT * FROM optins WHERE tenant = ?');
    $stmt->execute([$user_info]);
    $rooms = $stmt->fetchAll();
    $rooms_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($rooms as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $rooms_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $rooms_exp;

}

function get_room_address($pdo, $id){
    $stmt = $pdo->prepare('SELECT address FROM rooms where room_id = ?');
    $stmt->execute([$id]);
    $room_info = $stmt->fetch();
    return sprintf("%s", htmlspecialchars($room_info['address']));

}

function get_rooms_optin_table($rooms_optin, $pdo){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <tr>
        <th scope="col">Room address</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($rooms_optin as $key => $value){
        $table_exp .= '
            <tr>
                <th scope="row">'.get_room_address($pdo, $value['room']).'</th>
                <td><a href="/ddwt20_project/rooms/?room_id='.$value['room'].'" role="button" class="btn btn-primary">More info</a></td>
                <td>
                    <form action="/ddwt20_project/myoptins/remove/" method="POST">
                        <input type="hidden" value="'  .$value['optin_id'].  '" name="optin_id">
                        <button type="submit" class="btn btn-danger">Undo optin</button>
                   </form>
                </td>
            </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;

}

function get_optin_info($pdo, $optin_id){
    $stmt = $pdo->prepare('SELECT * FROM optins WHERE optin_id = ?');
    $stmt->execute([$optin_id]);
    $optin_info = $stmt->fetch();
    $optin_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optin_info as $key => $value){
        $optin_info_exp[$key] = htmlspecialchars($value);
    }
    return $optin_info_exp;
}

function optins_information($pdo, $id){
    $stmt = $pdo->prepare('SELECT * FROM optins WHERE optin_id = ?');
    $stmt->execute([$id]);
    $room = $stmt->fetch();
    return $room;
}

function remove_optin($pdo, $optin_id){
    /* Get room info */
    $optin_info = optins_information($pdo, $optin_id);

    /* Delete Room */
    $stmt = $pdo->prepare("DELETE FROM optins WHERE optin_id = ?");
    $stmt->execute([$optin_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("room '%s' was removed successfully!", $optin_info['room'])
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'An error occurred. The optin was not undone.'
        ];
    }
}

function optin_owners($pdo, $room_id){
    $stmt = $pdo->prepare('SELECT * FROM optins WHERE room = ?');
    $stmt->execute([$room_id]);
    $optin_info = $stmt->fetchAll();
    $optin_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($optin_info as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $optin_info_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }

    return $optin_info_exp;
}

function get_chats($pdo, $user_id){
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id IN (SELECT receiver from messages WHERE sender = ?) OR user_id IN (SELECT sender from messages WHERE receiver = ?)');
    $stmt->execute([$user_id, $user_id]);
    $chats = $stmt->fetchAll();
    $chats_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($chats as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $chats_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $chats_exp;
}

function get_chats_table($chats){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <tr>
        <th scope="col">Name</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($chats as $key => $value){
        $table_exp .= '
            <tr>
                <th scope="row">'.$value['full_name'].'</th>
                <td><a href="/ddwt20_project/messages/chats/?other_id='.$value['user_id'].'" role="button" class="btn btn-primary">View Messages</a></td>
            </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

function get_messages($pdo, $user_id, $other_id){
    $stmt = $pdo->prepare('SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY datetime');
    $stmt->execute([$user_id, $other_id, $other_id, $user_id]);
    $messages = $stmt->fetchAll();
    $messages_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($messages as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $messages_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $messages_exp;
}

function get_messages_table($messages, $pdo){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Message</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($messages as $key => $value){
        $table_exp .= '
            <tr>
                <th scope="row">'.get_user($pdo,$value['sender']).'</th>
                <td>'.$value['message'].'</td>
            </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

function send_message($pdo, $message){
    /* Check if all fields are set */
    if (
        empty($message['message'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. No text was entered.'
        ];
    }

    /* Check data type */
    if (!is_numeric($message['sender'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Invalid Sender ID.'
        ];
    }

    if (!is_numeric($message['receiver'])) {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Invalid Receiver ID.'
        ];
    }

    /* Add message */
    $stmt = $pdo->prepare("INSERT INTO messages (sender, receiver, datetime, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $message['sender'],
        $message['receiver'],
        date("Y-m-d H:i:s"),
        $message['message'],
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Message Sent")
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Message was not sent.'
        ];
    }
}

function get_optins_table($pdo, $optins, $room_id){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <tr>
        <th scope="col">Name</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($optins as $key => $value){
        $table_exp .= '
        <tr>
            <th scope="row">'.get_user($pdo, $value['tenant']).'</th>
            <td><a href="/ddwt20_project/messages/chats/?other_id='.$value['tenant'].'" role="button" class="btn btn-primary">Send Message</a></td>
            <td>
                <form action="/ddwt20_project/lease/add/" method="POST">
                    <input type="hidden" value="'.$room_id.'" name="room_id">
                    <input type="hidden" value="'.$value['tenant'].'" name="tenant">
                    <button type="submit" class="btn btn-success">Choose user for lease</button>
                </form>
            </td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

function add_lease($pdo, $optin){
    $stmt = $pdo->prepare("INSERT INTO leases (room, tenant, start_date) VALUES (?, ?, ?)");
    $stmt->execute([
        $optin['room_id'],
        $optin['tenant'],
        date("Y-m-d"),
    ]);
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        $stmt = $pdo->prepare("UPDATE rooms SET status = ? WHERE room_id = ?");
        $stmt->execute(['unavailable', $optin['room_id']]);
        $updated = $stmt->rowCount();
        if ($updated ==  1) {
            /* Delete optins */
            $stmt = $pdo->prepare("DELETE FROM optins WHERE room = ?");
            $stmt->execute([$optin['room_id']]);
            return [
                'type' => 'success',
                'message' => sprintf("Your room has been leased!")
            ];
        }
        else {
            return [
                'type' => 'warning',
                'message' => 'Warning, Status of room has not been changed.'
            ];
        }
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'There was an error. Lease could not be entered in the database.'
        ];
    }
}

function end_lease($pdo, $room_id){
    $stmt = $pdo->prepare("UPDATE leases SET end_date= ? WHERE end_date IS NULL AND room = ?");
    $stmt->execute([
        date("Y-m-d"),
        $room_id
    ]);
    $updated = $stmt->rowCount();
    if ($updated ==  1) {
        /* Delete optins */
        $stmt = $pdo->prepare("UPDATE rooms SET status = ? WHERE room_id = ?");
        $stmt->execute(['available', $room_id]);
        return [
            'type' => 'success',
            'message' => sprintf("Lease has been ended!")
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Warning, lease has not ended.'
        ];
    }
}