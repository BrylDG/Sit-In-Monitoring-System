<?php
include 'dbFunctions.php';

function generatePointsLeaderboard() {
    global $conn;

    try {
        // Query to get total points for each student with their names
        $query = "SELECT 
                    u.idNo, 
                    CONCAT(u.firstName, ' ', u.lastName) AS name,
                    IFNULL(s.totalPoints, 0) AS total_points
                  FROM users u
                  LEFT JOIN sessions s ON u.idNo = s.idNo
                  WHERE u.role = 'student'
                  ORDER BY total_points DESC";
        
        $result = $conn->query($query);
        
        if (!$result) {
            throw new Exception("Error executing query: " . $conn->error);
        }
        
        $students = $result->fetch_all(MYSQLI_ASSOC);
        
        $htmlRows = '';
        $rank = 1;
        $prevPoints = null;
        $actualRank = 1;
        
        foreach ($students as $student) {
            // Handle ties in ranking
            if ($prevPoints !== null && $student['total_points'] < $prevPoints) {
                $actualRank = $rank;
            }
            $prevPoints = $student['total_points'];
            
            $medal = '';
            if ($actualRank == 1) {
                $medal = '<span class="medal-icon gold-medal">🥇</span>';
            } elseif ($actualRank == 2) {
                $medal = '<span class="medal-icon silver-medal">🥈</span>';
            } elseif ($actualRank == 3) {
                $medal = '<span class="medal-icon bronze-medal">🥉</span>';
            }
            
            $points = $student['total_points'];
            
            $htmlRows .= <<<HTML
                <tr class="table-row">
                    <td class="table-data rank-cell">$medal$actualRank</td>
                    <td class="table-data name-cell">{$student['name']}</td>
                    <td class="table-data points-cell">$points pts</td>
                </tr>
            HTML;
            
            $rank++;
        }
        
        return <<<HTML
        <div id="leaderboard-container">
            <h1 id="header">Student Points Leaderboard</h1>
            <table id="leaderboard-table">
                <thead>
                    <tr class="table-row">
                        <th class="table-header rank-header">Rank</th>
                        <th class="table-header name-header">Student Name</th>
                        <th class="table-header points-header">Total Points</th>
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

echo generatePointsLeaderboard();

if (isset($conn)) {
    $conn->close();
}
?>