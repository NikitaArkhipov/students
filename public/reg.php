
<?php
/** **************controller for registration or change information **********
 * 
 * 
 * 
 * here need validate data POST array throw Validator
 * 
 */


require_once "../bootstrap.php";
use App\Validator;

// echo __DIR__;




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = array_key_exists('name', $_POST) ? $_POST['name'] : '';
    $lastName = array_key_exists('lastName', $_POST) ? $_POST['lastName'] : '';
    $email = array_key_exists('email', $_POST) ? $_POST['email'] : '';
    $gender = array_key_exists('gender', $_POST) ? $_POST['gender'] : '';
    $group = array_key_exists('group', $_POST) ? $_POST['group'] : '';
    $useResult = (int) array_key_exists('useResult', $_POST) ? $_POST['useResult'] : '';
    $birthYear = (int) array_key_exists('birthYear', $_POST) ? $_POST['birthYear'] : '';
    $token = array_key_exists('token', $_POST) ? $_POST['token'] : '';
    try {
        Validator::validForm($name, $lastName, $email, $gender, $group, $useResult, $birthYear);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
    /***
     * CHECK HTTP!!!** 
     * INSERT DATA INTO DATABASE
     * LOCATION TO PAGE with student list (studentList.php)
     */
    if ($_COOKIE['token'] == $token) {
        $result = $studentsService->insertStudent($name, $lastName, $email, $gender, $group, $useResult, $birthYear);
        $infoStudent= [
            "name" => $name,
            "lastName" => $lastName,
            "email" => $email,
            "gender" => $gender,
            "group" => $group,
            "useResult" => $useResult,
            "birthYear" => $birthYear,
        ];
        /* save data about student in cookies */
        $infoStudent = base64_encode(serialize($infoStudent));
        setcookie('infoStudent', $infoStudent, time() + 60 * 60 * 24 * 365 * 10);
        if ($result == true){
            header("Location: studentList.php");
        }
    }
    else{
        echo "Error token";
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' and isset($_COOKIE['infoStudent'])){
    /**
     * CHANGE FORM
     */
    $infoStudent = unserialize(base64_decode($_COOKIE['infoStudent']));
    
    echo "<pre>";
    var_dump($infoStudent);

}

else {
    /**
     * REQUIQRE REG FORM 
     */
    $token = uniqid();
    setcookie('token', $token, time() + 60 * 60 * 24 );
    require_once "../views/reg.phtml";
}


