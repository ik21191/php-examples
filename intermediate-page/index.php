<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Async PHP Processing with Intermediate Page</title>
    <style>
        /* Base page styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form Input Field Styling */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #444;
        }

        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Intermediate Loading Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 999;
            justify-content: center;
            align-items: center;
            color: white;
            flex-direction: column;
            text-align: center;
        }

        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Response Popup Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .modal-content h3 {
            margin-top: 0;
            color: #28a745;
        }

        .modal-content p {
            color: #555;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .modal-content button {
            background-color: #6c757d;
            width: auto;
            padding: 8px 25px;
            display: inline-block;
        }

        .modal-content button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

    <!-- Main Visible Form Container -->
    <div class="form-container">
        <h2>Process Data Form</h2>
        <form id="processForm">
            <!-- Control trigger for backend identification -->
            <input type="hidden" name="action" value="submit_data">

            <!-- Name Input Field -->
            <div class="form-group">
                <label for="username">Your Name:</label>
                <input type="text" id="username" name="username" required placeholder="e.g. John Doe">
            </div>

            <button type="submit" id="submitBtn">Submit Request</button>
        </form>
    </div>

    <!-- 1. Intermediate Processing HTML Screen (Initially Hidden) -->
    <div id="loadingOverlay" class="overlay">
        <div class="spinner"></div>
        <h2>Processing Request...</h2>
        <p>Your details are being validated at the backend. Please wait.</p>
    </div>

    <!-- 2. Final Response Popup Modal (Initially Hidden) -->
    <div id="responseModal" class="modal">
        <div class="modal-content">
            <h3 id="modalHeader">Server Response</h3>
            <p id="responseText"></p>
            <button type="button" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        document.getElementById('processForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevents standard full page reload

            const overlay = document.getElementById('loadingOverlay');
            const modal = document.getElementById('responseModal');
            const modalHeader = document.getElementById('modalHeader');
            const responseText = document.getElementById('responseText');
            
            // Step 1: Immediately show the intermediate screen
            overlay.style.display = 'flex';

            // Automatically aggregate all visible form fields
            const formData = new FormData(this);

            // Step 2: Pass data to backend script
            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                
                return response.json(); // Map response payload to object
            })
            .then(data => {
                // Step 3: Clear intermediate loader, parse response into final popup
                overlay.style.display = 'none';
                
                if(data.status === 'success') {
                    modalHeader.style.color = '#28a745'; // Green headers for success
                } else {
                    modalHeader.style.color = '#dc3545'; // Red headers for errors
                }
                
                responseText.textContent = data.message;
                modal.style.display = 'flex';
            })
            .catch(error => {
                // Emergency state handling if server drops or errors out
                overlay.style.display = 'none';
                modalHeader.style.color = '#dc3545';
                modalHeader.textContent = "Error Occurred";
                responseText.textContent = "Could not communicate with the processing engine.";
                modal.style.display = 'flex';
                console.error('Fetch operation error:', error);
            });
        });

        // Close logic for popup handling
        function closeModal() {
            document.getElementById('responseModal').style.display = 'none';
            document.getElementById('processForm').reset(); // Clear input fields for next run
        }
    </script>
</body>
</html>
