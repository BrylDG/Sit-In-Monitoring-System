<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);

// Fetch the purpose counts
$values = countPurpose($conn);
?>

<div class="StatsContainer">
    <div class="PurposeChart">
        <canvas id="PurposeChart"></canvas>
    </div>
</div>
<script>
    const values = <?php echo json_encode($values); ?>;

    // Extract labels (keys) and data (values) from PHP array
    const labels = Object.keys(values);
    const data = Object.values(values);

    createPieChart('PurposeChart', labels, data, ['#0088FE', '#00C49F', '#FFBB28', '#FF8042']);
</script>