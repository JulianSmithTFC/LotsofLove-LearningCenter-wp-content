<?php
if(isset( $_POST['fname']))
    $fname = $_POST['fname'];
if(isset( $_POST['lname']))
    $lname = $_POST['lname'];
if(isset( $_POST['email']))
    $email = $_POST['email'];
if(isset( $_POST['phone']))
    $phone = $_POST['phone'];
if(isset( $_POST['program_0'])){
    $programHTMLstr0 .= "<tr><td><strong>Two-Year-Old Program</strong></td></tr>";
}
if(isset( $_POST['program_1'])){
    $programHTMLstr1 .= "<tr><td><strong>School-Age Before & After School Program</strong></td></tr>";
}
if(isset( $_POST['program_2'])){
    $programHTMLstr2 .= "<tr><td><strong>Preschool Program</strong></td></tr>";
}
if(isset( $_POST['program_3'])){
    $programHTMLstr3 .= "<tr><td><strong>Pre-K Program</strong></td></tr>";
}
if(isset( $_POST['program_4'])){
    $programHTMLstr4 .= "<tr><td><strong>Infants/Toddler Program</strong></td></tr>";
}
if(isset( $_POST['message']))
    $comment = $_POST['message'];
    $subject = 'Enrollment Contact Form Submission';





$mailheader .= "From: $fname $lname \r\n";
$mailheader .= "MIME-Version: 1.0\r\n";
$mailheader .= "Content-Type: text/html; charset=ISO-8859-1\r\n";



$message = '<html><body>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= "<tr style='background: #eee;'><td><strong>Name:</strong></td><td > $fname $lname </td></tr>";
$message .= "<tr><td><strong>Email:</strong></td><td > $email </td></tr>";
$message .= "<tr><td><strong>Phone Number:</strong></td><td > $phone </td></tr>";
$message .= "<tr><td><strong>Message:</strong></td><td > $comment </td></tr>";
$message .= "</table>";
$message .= "<h3>Programs Interested In</h3>";
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= $programHTMLstr0;
$message .= $programHTMLstr1;
$message .= $programHTMLstr2;
$message .= $programHTMLstr3;
$message .= $programHTMLstr4;
$message .= "</table>";
$message .= "</body></html>";

$recipient = "lotsoflovelearning@gmail.com";

mail($recipient, $subject, $message, $mailheader) or die("Error!");
header('Location: https://lotsoflove-learningcenter.com/enrollment-information/');
?>