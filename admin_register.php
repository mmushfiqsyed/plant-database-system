<?php
require "private/admin_check.php"; 
require "private/db_connect.php";

$current_user_id = $_SESSION['user_id'];
$super_check = $conn->prepare("SELECT is_super_admin FROM admin WHERE user_id = ?");
//1. Note how bind_param is used here:
$super_check->bind_param("i", $current_user_id);
$super_check->execute();
$super_result = $super_check->get_result();
$admin_data = $super_result->fetch_assoc();

$is_super = ($admin_data && $admin_data['is_super_admin'] == 1);

// Handle Promotion, Update, and Demotion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_super) {
    //2. But bind_param is not used here:
    $target_user_id = intval($_POST['target_user_id']);
    $alert_msg = "";

    if (isset($_POST['demote_admin'])) {
        // DEMOTION LOGIC: Remove from admin table entirely
        $stmt = $conn->prepare("DELETE FROM admin WHERE user_id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $alert_msg = "User ID $target_user_id has been demoted to regular User.";
    } 
    elseif (isset($_POST['make_admin'])) {
        // PROMOTION/UPDATE LOGIC
        $set_super = isset($_POST['set_super']) ? 1 : 0;
        $exists = $conn->query("SELECT user_id FROM admin WHERE user_id = $target_user_id");
        
        if ($exists->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE admin SET is_super_admin = ? WHERE user_id = ?");
            $stmt->bind_param("ii", $set_super, $target_user_id);
            $alert_msg = "Permissions updated for User ID $target_user_id.";
        } else {
            $stmt = $conn->prepare("INSERT INTO admin (user_id, is_super_admin) VALUES (?, ?)");
            $stmt->bind_param("ii", $target_user_id, $set_super);
            $alert_msg = "User ID $target_user_id promoted to Admin.";
        }
        $stmt->execute();
    }

    if ($alert_msg) {
        echo "<script>alert('$alert_msg'); window.location.href='admin_register.php';</script>";
        exit();
    }
}

$query = "SELECT u.user_id, u.username, u.email, a.is_super_admin 
          FROM users u 
          LEFT JOIN admin a ON u.user_id = a.user_id 
          ORDER BY u.user_id ASC";
$users_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        nav { display:flex; box-sizing: border-box; justify-content: space-between; align-items:center;}
        nav a{ text-decoration:none; border: 1px solid #000; padding:12px;}
        .side-by-side{ display: flex; box-sizing: border-box; justify-content: space-between; align-items: flex-start; gap: 40px; margin-top: 20px;}
        table { width: 60%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f4f4f4; }
        .row-adder { background: #f9f9f9; padding: 20px; border: 2px solid #ddd; border-radius: 10px; width: 30%; }
        
        /* Updated modest Super Admin design */
        .status-super { color: orange; font-weight: bold; }
        .status-admin { color: blue; font-weight: bold; }
        .btn-demote { background-color: #ff4d4d; color: white; border: none; padding: 10px; cursor: pointer; margin-top: 10px; width: 100%; border-radius: 5px;}
    </style>
</head>
<body>
    <nav>
        <h2>Admin Registration</h2>
        <a href="admin_dashboard.php">Return to Dashboard</a>
    </nav>

    <div class="side-by-side">
        <div class="table-container" style="flex-grow: 1;">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php 
                                if ($user['is_super_admin'] === '1') {
                                    echo '<span class="status-super">Super Admin</span>';
                                } elseif ($user['is_super_admin'] === '0') {
                                    echo '<span class="status-admin">Admin</span>';
                                } else {
                                    echo 'User';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php if ($is_super): ?>
        <div class="row-adder">
            <h3>Manage Privileges</h3>
            <form method="POST" onsubmit="return confirm('Are you sure you want to perform this action?');">
                <label>Target User ID:</label><br>
                <input type="number" name="target_user_id" required style="width:100%; padding:8px;"><br><br>
                
                <label>
                    <input type="checkbox" name="set_super"> Grant Super Admin Privileges
                </label><br><br>
                
                <button type="submit" name="make_admin" style="padding: 10px; width: 100%; cursor: pointer; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">Update/Promote</button>
                
                <hr style="margin: 20px 0;">
                
                <p style="font-size: 0.85em; color: #666;">To remove all admin privileges from a user:</p>
                <button type="submit" name="demote_admin" class="btn-demote">Demote to Regular User</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php $conn->close(); ?>
</body>
</html>