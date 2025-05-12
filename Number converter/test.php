<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; }
        header { background-color: #4CAF50; padding: 15px; color: white; text-align: center; }
        form { margin-top: 20px; max-width: 400px; margin-left: auto; margin-right: auto; }
        input[type="text"], button { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .clear-btn { background-color: #f44336; }
        .clear-btn:hover { background-color: #e53935; }
        .message { color: red; margin-top: 10px; }
        .result { background-color: #e7f3fe; padding: 10px; border: 1px solid #b3d4fc; margin-top: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <header>
        <h1>Currency Converter (Riel to Dollar)</h1>
    </header>
    <form method="post" action="">
        <label for="riel">Enter Amount in Riels:</label>
        <input type="text" id="riel" name="riel" placeholder="e.g. 5000" 
               value="<?php echo isset($_POST['riel']) ? htmlspecialchars($_POST['riel']) : ''; ?>">
        <button type="submit" name="convert">Convert</button>
        <button type="button" class="clear-btn" onclick="clearResult()">Clear</button>
        <div class="message">
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

            echo '<div class="result" id="result">';
            echo '<p>a. ' . $textEnglish . '</p>';
            echo '<p>b. ' . $textKhmer . '</p>';
            echo '<p>c. ' . $textDollar . '</p>';
            echo '</div>';

            $data = $textEnglish . ' | ' . $textKhmer . ' | ' . $textDollar . "\n";
            file_put_contents('data.txt', $data, FILE_APPEND);
        }
    }
    ?>

    <script>
        function clearResult() {
            document.getElementById('riel').value = '';
            const resultDiv = document.getElementById('result');
            if (resultDiv) {
                resultDiv.innerHTML = '';
            }
        }
    </script>
</body>
</html>
