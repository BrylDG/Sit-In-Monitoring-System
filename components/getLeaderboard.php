<?php
include 'dbFunctions.php';

// Function that automatically fetches and formats all durations
function generateDurationLeaderboard() {
    global $conn; // Use the database connection from dbFunctions.php

    try {
        // Query to get total duration for each student
        $query = "SELECT 
                    idNo, 
                    name, 
                    SEC_TO_TIME(SUM(TIME_TO_SEC(Duration))) AS total_duration
                  FROM SitInHistory
                  GROUP BY idNo, name
                  ORDER BY total_duration DESC";
        
        $result = $conn->query($query);
        
        if (!$result) {
            throw new Exception("Error executing query: " . $conn->error);
        }
        
        $students = $result->fetch_all(MYSQLI_ASSOC);
        
        // Generate the HTML table rows
        $htmlRows = '';
        $rank = 1;
        
        foreach ($students as $student) {
            $medal = '';
            if ($rank == 1) {
                $medal = '<span class="medal-icon gold-medal"></span>';
            } elseif ($rank == 2) {
                $medal = '<span class="medal-icon silver-medal"></span>';
            } elseif ($rank == 3) {
                $medal = '<span class="medal-icon bronze-medal"></span>';
            }
            
            // Format the duration directly here
            $time = $student['total_duration'];
            $formattedDuration = '0 min';
            
            if ($time != '00:00:00' && !empty($time)) {
                $parts = explode(':', $time);
                $hours = (int)$parts[0];
                $minutes = (int)$parts[1];
                
                if ($hours > 0 && $minutes > 0) {
                    $formattedDuration = "$hours hours $minutes min";
                } elseif ($hours > 0) {
                    $formattedDuration = "$hours hours";
                } else {
                    $formattedDuration = "$minutes min";
                }
            }
            
            $htmlRows .= <<<HTML
                <tr class="table-row">
                    <td class="table-data rank-cell">$medal$rank</td>
                    <td class="table-data name-cell">{$student['name']}</td>
                    <td class="table-data duration-cell">$formattedDuration</td>
                </tr>
            HTML;
            
            $rank++;
        }
        
        // Return the complete HTML
        return <<<HTML
        <div id="leaderboard-container">
            <h1 id="header">Student Duration Leaderboard</h1>
            <table id="leaderboard-table">
                <thead>
                    <tr class="table-row">
                        <th class="table-header rank-header">Rank</th>
                        <th class="table-header name-header">Student Name</th>
                        <th class="table-header duration-header">Total Duration</th>
                    </tr>
                </thead>
                <tbody>
                    $htmlRows
                </tbody>
            </table>
        </div>
        HTML;
        
    } catch (Exception $e) {
        return "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// Display the leaderboard
echo generateDurationLeaderboard();

// Close connection if needed
if (isset($conn)) {
    $conn->close();
}
?>