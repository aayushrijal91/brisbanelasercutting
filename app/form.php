<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Lee0qYgAAAAACDJ_0PC2bZEcn3cNMH2Tq6-oui5';
    $recaptcha_response = $_POST['token'];
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    try {
        if ($recaptcha->score < 0.5) {
            throw new Exception('Low Score');
        }

        $site = "Brisbane Laser Cutting";
        $to = 'arijal@aiims.com.au'; //jayoub@aiims.com.au 

        $subject = "Message from " . $site;

        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $service = $_POST['service'];
        $comment = $_POST['comment'];

        $message = '<!DOCTYPE html><html><body>' .
            'Name: <strong>' . strip_tags($name) . '</strong><br>' .
            'Phone: <strong>' . strip_tags($phone) . '</strong><br>' .
            'Email Address: <strong>' . strip_tags($email) . '</strong><br>' .
            'Service: <strong>' . strip_tags($service) . '</strong><br>'.
            'Message: <strong>' . strip_tags($comment) . '</strong><br>' ;

        $headers = "MIME-Version: 1.0\r\n" .
            "Content-type: text/html; charset=utf-8\r\n" .
            "From: " . $site . " <no-reply@aayushrijal.com.au>" . "\r\n" .
            "Reply-To: " . $site . " <no-reply@aayushrijal.com.au>" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        $result = mail($to, $subject, $message, $headers);

        if ($result) {
            header('location:thankyou.html');
        } else {
            throw new Exception('Failed, please submit form again or call us directly.');
        }
    } catch (Exception $e) {
        echo '<script language="javascript">alert("' . $e->getMessage() . '")</script>';
        echo '<script language="javascript">history.go(-1);</script>';
    }
}
