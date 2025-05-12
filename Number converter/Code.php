<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f1f1f1;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #007bff;
            padding: 15px;
            color: white;
            text-align: center;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: none;
            border-radius: 10px;
        }
        button {
            border-radius: 5px;
        }
        .clear-btn {
            background-color: #dc3545;
            border: none;
        }
        .clear-btn:hover {
            background-color: #c82333;
        }
        .message {
            color: red;
        }
        table {
            width: 100%;
            background-color: white;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Currency Converter (Riel to Dollar)</h1>
    </header>

    <main class="container mt-5">
        <div class="card p-4">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="riel" class="form-label">Enter Amount in Riels:</label>
                    <input type="text" id="riel" name="riel" class="form-control" placeholder="e.g. 5000" value="<?php echo isset($_POST['riel']) ? htmlspecialchars($_POST['riel']) : ''; ?>">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="convert" class="btn btn-primary">Convert</button>
                    <button type="button" class="btn clear-btn text-white" onclick="clearResult()">Clear</button>
                    <button type="button" class="btn btn-info text-white" onclick="viewHistory()">View History</button>
                </div>
                <div class="message mt-3">
                    <?php
                    if (isset($_POST['convert'])) {
                        $input = trim($_POST['riel']);
                        if (!is_numeric($input) || floatval($input) < 0.1) {
                            echo "Please enter a valid number between 0.1 and 1 trillion.";
                        } elseif (floatval($input) > 1000000000000) {
                            echo "Cannot input more than 1 trillion.";
                        }
                    }
                    ?>
                </div>
            </form>

            <?php
            if (isset($_POST['convert'])) {
                $input = trim($_POST['riel']);
                if (is_numeric($input) && floatval($input) >= 0.1 && floatval($input) <= 1000000000000) {
                    $riel = floatval($input);
                    $exchangeRate = 4000;

                    function numberToWordsEN($number) {
                        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        return ucfirst($f->format($number));
                    }

                    function numberToWordsKH($number) {
                        $f = new NumberFormatter("km", NumberFormatter::SPELLOUT);
                        return ucfirst($f->format($number));
                    }

                    $textEnglish = numberToWordsEN($riel) . ' riel';
                    $textKhmer = numberToWordsKH($riel) . ' រៀល';
                    $dollar = $riel / $exchangeRate;
                    $textDollar = number_format($dollar, 2) . '$';

                    echo '<div class="alert alert-success mt-4" id="result">';
                    echo '<p>a. ' . $textEnglish . '</p>';
                    echo '<p>b. ' . $textKhmer . '</p>';
                    echo '<p>c. ' . $textDollar . '</p>';
                    echo '</div>';

                    $file = 'data.txt';
                    $history = "$textEnglish | $textKhmer | $riel\n";
                    file_put_contents($file, $history, FILE_APPEND);
                }
            }
            ?>
        </div>
    </main>

    <script>
        function clearResult() {
            document.getElementById('riel').value = '';
            const resultDiv = document.getElementById('result');
            if (resultDiv) {
                resultDiv.innerHTML = '';
            }
        }

        function viewHistory() {
            fetch('data.txt')
                .then(response => response.text())
                .then(data => {
                    
                    const rows = data.split('\n').filter(line => line).reverse();
                    let table = '<table><tr><th>In English</th><th>In Khmer</th><th>Number</th></tr>';
                    rows.forEach(row => {
                        const cols = row.split(' | ');
                        table += `<tr><td>${cols[0]}</td><td>${cols[1]}</td><td>${cols[2]}</td></tr>`;
                    });
                    table += '</table>';
                    document.getElementById('result').innerHTML = table;
                })
                .catch(error => {
                    alert('Failed to load history.');
                });
        }
    </script>
</body>
</html>
