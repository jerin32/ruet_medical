<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $doctor = htmlspecialchars($_POST["doctor"]);
    $date = htmlspecialchars($_POST["date"]);
    $time = htmlspecialchars($_POST["time"]);

    // Check if the same appointment time already exists
    $sql_check = "SELECT * FROM appointments WHERE date = '$date' AND time = '$time'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        // If the time slot is already booked
        echo "<p>The selected time slot is already booked. Please choose a different time.</p>";
    } else {
        // Insert appointment details into the database
        $sql = "INSERT INTO appointments (name, email, doctor, date, time) VALUES ('$name', '$email', '$doctor', '$date', '$time')";
        if (mysqli_query($conn, $sql)) {
            // Confirmation message
            echo "<p>Appointment booked successfully!</p>";
            echo "<p>An email confirmation will be sent to $email shortly.</p>";
            
            // Send email using EmailJS API
            $url = "https://api.emailjs.com/api/v1.0/email/send";
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Set headers
            $headers = array(
                "Content-Type: application/json",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            // Prepare the data to be sent to EmailJS
            $data = json_encode(array(
                "service_id" => "service_i38ppei",  // Replace with your EmailJS service ID
                "template_id" => "template_g82gh79", // Replace with your EmailJS template ID
                "user_id" => "FP7hZa19T3dEynVel",   // Replace with your EmailJS user ID
                "accessToken" => "0N9yLtVFrB9HpLnYZbfNt",  // Replace with your EmailJS access token
                "template_params" => array(
                    "to_name" => $name,
                    "to_email" => $email,
                    "doctor" => $doctor,
                    "date" => $date,
                    "time" => $time
                )
            ));

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            // Execute the cURL request (without printing the result)
            $response = curl_exec($curl);

            // Close the cURL session
            curl_close($curl);
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    // Close database connection
    mysqli_close($conn);
}
?>
