<?php
// view_bookings.php

$con = mysqli_connect("localhost", "root", "", "project") or die("Couldn't connect");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilkanth Mobiles Repair Service</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css">
    <link rel="stylesheet" href="/project/customer/style/style2.css">

    <style>
       h2 {
            margin-top: 30px;
            text-align: center;
            color: #2d3e50;
        }
        form {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 9px;
        }
        form input {
            padding: 10px;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
            margin-bottom: 20px;
        }
        form button {
            padding: 10px 20px;
            background-color: midnightblue;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: midnightblue;
            color: white;
            font-size: 18px;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        table button {
            padding: 6px 12px;
            background-color: midnightblue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        table button:disabled {
            background-color: grey;
            cursor: not-allowed;
        }
        #search-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem;
      position: relative;
}

    #search-input {
      width: 200px;
      height: 35px;
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 0 1rem;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
}
#product1 .pro img {
    width: 100%;
    height: 200px;
    object-fit: contain;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
    #search-input:focus {
      width: 300px;
      border-color: midnightblue;
      box-shadow: 0 0 5px rgba(0, 0, 255, 0.5);
}

    #search-button {
      background-color: midnightblue;
      color: white;
      border: none;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      margin-left: -40px;
      transition: background-color 0.3s ease;
}

    #search-button:hover {
      background-color: navy;
}

    #search-button i {
      font-size: 16px;
}
    </style>
</head>
<body>
    <?php require_once "../header.php" ?>
    <br>
    <h2>Your Bookings</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Your Email" required>
        <button type="submit">View Bookings</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $stmt = $con->prepare("SELECT * FROM service_bookings WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr><th>ID</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th>Estimate</th><th>Action</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['service_type']}</td>
                        <td>{$row['booking_date']}</td>
                        <td>{$row['booking_time']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['estimate']}</td>
                        <td>
                            <form action='update_status.php' method='POST'>
                                <input type='hidden' name='booking_id' value='{$row['booking_id']}'>
                                <button type='submit' name='action' value='Reject' " . (empty($row['estimate']) ? "disabled" : "") . ">Reject</button>
                            </form>
                            <form action='update_status.php' method='POST' onsubmit='return validateDateTime({$row['booking_id']})'>
                                <input type='hidden' name='booking_id' value='{$row['booking_id']}'>
                                <button type='button' onclick='showDateTimeInput({$row['booking_id']})' name='action' value='Accept' " . (empty($row['estimate']) ? "disabled" : "") . ">Accept</button>
                                <div id='date-time-input-{$row['booking_id']}' class='date-time-input' style='display: none;'>
                                    <input type='date' name='new_booking_date' id='new_booking_date_{$row['booking_id']}' required>
                                    <input type='time' name='new_booking_time' id='new_booking_time_{$row['booking_id']}' required>
                                    <button type='submit' name='action' value='Accept'>Confirm</button>
                                </div>
                            </form>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-bookings'><center>No bookings found.</center></p>";
        }
    }
    ?>

    <script>
        function showDateTimeInput(bookingId) {
            // Hide all other date-time inputs
            document.querySelectorAll('.date-time-input').forEach(function(el) {
                el.style.display = 'none';
            });

            // Show the date-time input for the specific booking
            var dateTimeInput = document.getElementById('date-time-input-' + bookingId);
            dateTimeInput.style.display = 'flex';

            // Set the minimum date to tomorrow
            var dateInput = document.getElementById('new_booking_date_' + bookingId);
            var today = new Date();
            var tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            var tomorrowFormatted = tomorrow.toISOString().split('T')[0];
            dateInput.setAttribute('min', tomorrowFormatted);

            // Disable Sundays
            dateInput.addEventListener('input', function() {
                var selectedDate = new Date(this.value);
                if (selectedDate.getDay() === 0) { // Sunday
                    this.value = ''; // Clear the input
                    alert('Sundays are not available for booking.');
                }
            });

            // Validate time input
            var timeInput = document.getElementById('new_booking_time_' + bookingId);
            timeInput.addEventListener('input', function() {
                var selectedTime = this.value;
                var minTime = '10:00';
                var maxTime = '21:00';

                if (selectedTime < minTime || selectedTime > maxTime) {
                    alert('Please select a time between 10:00 AM and 9:00 PM.');
                    this.value = ''; // Clear the input
                }
            });
        }

        function validateDateTime(bookingId) {
            var dateInput = document.getElementById('new_booking_date_' + bookingId);
            var timeInput = document.getElementById('new_booking_time_' + bookingId);

            if (dateInput && timeInput) {
                var selectedDate = new Date(dateInput.value);
                var currentDate = new Date();
                currentDate.setHours(0, 0, 0, 0); // Reset time to midnight for comparison

                if (selectedDate < currentDate) {
                    alert('Past dates are not allowed.');
                    return false;
                }
                if (selectedDate.getDay() === 0) { // Sunday
                    alert('Sundays are not allowed.');
                    return false;
                }
                if (timeInput.value < '10:00' || timeInput.value > '21:00') {
                    alert('Time must be between 10:00 AM and 9:00 PM.');
                    return false;
                }
            }
            return true;
        }
    </script>

    <script src="/project/customer/script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>

<?php require_once "../footer.php" ?>