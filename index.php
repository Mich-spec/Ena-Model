<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the sentence from the form
    $input_sentence = $_POST['sentence'];

    // Call Python script and pass the sentence
    $command = "python translator.py " . escapeshellarg($input_sentence);
    $output = shell_exec($command);  // Execute Python script and get the result
    $translated_sentence = trim($output);  // Clean the output

    // Output just the translated sentence, no extra HTML
    echo $translated_sentence;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentence Translator</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Box container styling */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
            text-align: left;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        h2 {
            color: #333;
            margin-top: 20px;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-top: 10px;
        }

        /* Spinner (Loading effect) */
        .loading {
            display: none;
            margin-top: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #4CAF50;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }

        /* Animation for spinner */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Yoruba to Ẹnà Translator</h1>
        
        <!-- Form to take user input -->
        <form method="POST" id="translatorForm">
            <label for="sentence">Enter Sentence:</label>
            <input type="text" name="sentence" id="sentence" required>
            <button type="submit">Translate</button>
        </form>

        <!-- Loading Spinner -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Processing...</p>
        </div>

        <!-- Translated Sentence Display -->
        <div id="result"></div>
    </div>

    <script>
        // Show loading spinner when the form is submitted
        const form = document.getElementById('translatorForm');
        const loading = document.getElementById('loading');
        const result = document.getElementById('result');

        form.addEventListener('submit', function(event) {
            // Prevent the form from actually submitting and reloading the page
            event.preventDefault();

            // Show the loading spinner
            loading.style.display = 'block';
            result.style.display = 'none'; // Hide result before processing

            // Create a FormData object to send data via AJAX
            const formData = new FormData(form);

            // Use Fetch API to send the data to the PHP backend
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Hide the loading spinner and show the result
                loading.style.display = 'none';
                result.style.display = 'block';

                // Check if there's data in the response
                if(data.trim() !== "") {
                    // Update the result with the translated sentence
                    result.innerHTML = `<h2>Translated Sentence:</h2><p>${data}</p>`;
                } else {
                    result.innerHTML = `<p>Sorry, no translation available.</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loading.style.display = 'none';
                result.style.display = 'block';
                result.innerHTML = `<p>Error occurred while processing. Please try again.</p>`;
            });
        });
    </script>
</body>
</html>
