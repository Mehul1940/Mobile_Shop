<?php
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";

// Fetch all complaints using the correct database schema
$complaintQuery = "SELECT 
                    ID, 
                    FEEDBACK_TYPE,
                    TYPE,
                    NAME,
                    CONTACT_METHOD,
                    PHONE,
                    EMAIL,
                    DETAILS,
                    OUTCOMES,
                    SUBMISSION_DATE,
                    SERVICE_ID,
                    ORDER_ID 
                   FROM feedback_complaints
                   WHERE FEEDBACK_TYPE = 'Complaint'";
$complaintResult = $conn->query($complaintQuery);

if (!$complaintResult) {
    die("Query Failed: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle admin reply submission using OUTCOMES field
    $complaint_id = $_POST['complaint_id'];
    $reply = $_POST['reply'];

    $updateQuery = "UPDATE feedback_complaints SET OUTCOMES = ? WHERE ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('si', $reply, $complaint_id);

    if ($stmt->execute()) {
        echo "<script>alert('Reply added successfully.');</script>";
        // Refresh the page to show updated data
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to add reply.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>
    <link rel="stylesheet" href="/Project/assets/css/complaint.css">
  
</head>

<body>
    <?php require_once "../Nav.php" ?>

    <div class="main">
        <?php require_once "../main.php" ?>
        <div class="complaint-container">
            <h1>Manage Complaints</h1>

            <?php if ($complaintResult->num_rows > 0): ?>
                <table class="complaint-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact Info</th>
                            <th>Details</th>
                            <th>Submission Date</th>
                            <th>Outcomes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($complaint = $complaintResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($complaint['ID']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($complaint['NAME']) ?></strong><br>
                                </td>
                                <td>
                                    <?= htmlspecialchars($complaint['PHONE']) ?><br>
                                    <?= htmlspecialchars($complaint['CONTACT_METHOD']) ?>
                                </td>
                                <td><?= htmlspecialchars($complaint['DETAILS']) ?></td>
                                <td><?= htmlspecialchars($complaint['SUBMISSION_DATE']) ?></td>
                                <td>
                                    <?php if (!empty($complaint['OUTCOMES'])): ?>
                                        <?= nl2br(htmlspecialchars($complaint['OUTCOMES'])) ?>
                                    <?php else: ?>
                                        <span class="no-reply">No response yet</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-complaints">No complaints found in the system.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>