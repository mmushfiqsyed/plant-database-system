<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        li{ text-decoration: none;}
        .header{ display: flex; box-sizing: border-box; justify-content: space-between; align-items: center;}
        .sign-out{ text-decoration:none; color:red; border: 1px solid #000; padding:12px;}
        
        .menu {
            font-family: sans-serif;
            list-style: none; /* Remove default styling*/
            padding: 0;
            display: flex;
            flex-wrap: wrap; /* Put the buttons on the same line and make them wrap with max-width*/
            gap: 15px;
            margin-top: 20px;
        }

        .menu li a {
            display: inline-block;
            text-decoration: none;
            color: #333;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 50px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            min-width: 180px;
            text-align: center;
        }
        
        .menu li a:hover {
            background-color: #e0e0e0;
            border-color: #888;
            transform: translateY(-2px); 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .menu li a:active {
            transform: translateY(0);
            box-shadow: none;
        }
    </style>
</head>
<body>
    <?php 
    include "private/db_connect.php";
    include "private/login_check.php";
    ?>
    <div class="header">
        <h1>Welcome to the User Dashboard!</h1>
        <div>
            <a class="sign-out" href=login_form.php>Sign Out</a>
        </div>
    </div>
    <p>Currently logged in as: <?php echo $_SESSION['username']?> </p>
    <h3>Options:</h3>
    <nav>
        <ul class="menu">
            <li><a href="your-report.php">Your reports</a></li>
            <li><a href="add-report.php">Add report</a></li>
            <li><a href="browse-records.php">Browse records</a></li>
        </ul>
    </nav>
    <?php $conn->close(); ?>
</body>
</html>