<?php
include "mydb_connect.php";

$sql = "SELECT * FROM events ORDER BY event_day_time ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row["event_name"] . "</h3>";
        echo "<p>Venue: " . $row["venue_name"] . " - " . $row["venue_location"] . "</p>";
        echo "<p>Date & Time: " . $row["event_day_time"] . "</p>";
        echo "<p>Price: " . ($row["is_free_event"] ? "Free" : "$" . $row["event_amount"]) . "</p>";
        echo "</div><hr>";
    }
} else {
    echo "No upcoming events.";
}

$conn->close();
?>
