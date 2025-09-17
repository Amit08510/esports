<?php
header('Content-Type: application/json');

// Include database connection
include 'dbconnect.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get total number of teams
    $teams_stmt = $conn->prepare("SELECT COUNT(*) as total_teams FROM team");
    $teams_stmt->execute();
    $total_teams = $teams_stmt->fetch(PDO::FETCH_ASSOC)['total_teams'];
    
    // Get total number of participants
    $participants_stmt = $conn->prepare("SELECT COUNT(*) as total_participants FROM participant");
    $participants_stmt->execute();
    $total_participants = $participants_stmt->fetch(PDO::FETCH_ASSOC)['total_participants'];
    
    // Get team leaderboard with aggregated stats
    $leaderboard_stmt = $conn->prepare("
        SELECT 
            t.id,
            t.name,
            t.location,
            COUNT(p.id) as player_count,
            COALESCE(SUM(p.kills), 0) as total_kills,
            COALESCE(SUM(p.deaths), 0) as total_deaths,
            CASE 
                WHEN COALESCE(SUM(p.deaths), 0) > 0 
                THEN ROUND(COALESCE(SUM(p.kills), 0) / COALESCE(SUM(p.deaths), 1), 2)
                ELSE COALESCE(SUM(p.kills), 0)
            END as kd_ratio
        FROM team t
        LEFT JOIN participant p ON t.id = p.team_id
        GROUP BY t.id, t.name, t.location
        ORDER BY kd_ratio DESC, total_kills DESC
        LIMIT 10
    ");
    $leaderboard_stmt->execute();
    $leaderboard = $leaderboard_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    $response = [
        'success' => true,
        'total_teams' => intval($total_teams),
        'total_participants' => intval($total_participants),
        'leaderboard' => $leaderboard
    ];
    
    echo json_encode($response);
    
} catch(PDOException $e) {
    // Return error response
    $error_response = [
        'success' => false,
        'error' => 'Database connection error',
        'total_teams' => 0,
        'total_participants' => 0,
        'leaderboard' => []
    ];
    
    echo json_encode($error_response);
}
?>