<?php define("PROJECT_ROOT_PATH", __DIR__ . "/../");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XAMPP PHP Example</title>
    <style>
        /* CSS for Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { color: #333; }
        .date { color: #007bff; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to My PHP Site</h1>
        
        <?php
        // PHP Logic: Get current time and display a greeting
        $hour = date('H');
        if ($hour < 12) {
            $greeting = "Good Morning";
        } elseif ($hour < 18) {
            $greeting = "Good Afternoon";
        } else {
            $greeting = "Good Evening";
        }
        
        echo "<p>$greeting! Today is <span class='date'>" . date('l, F jS') . "</span></p>";
		echo PROJECT_ROOT_PATH;
		echo "<br/>";
		
		?>
        
    </div>
</body>
</html>
