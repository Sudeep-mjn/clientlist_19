
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Search Client</title>
     <style>
        * {
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 500px;
    margin: 0 auto;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 30px;
    font-size: 24px;
    font-weight: 600;
}

.search-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

label {
    font-size: 14px;
    font-weight: 600;
    color: #34495e;
}

input[type="text"] {
    padding: 12px;
    border: 1px solid #bdc3c7;
    border-radius: 6px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s;
}

input[type="text"]:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

button {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 10px;
}

button:hover {
    background-color: #2980b9;
}

/* Responsive */
@media (max-width: 600px) {
    .container {
        padding: 20px;
        margin: 15px;
    }

    h1 {
        font-size: 20px;
    }

    input[type="text"], button {
        font-size: 15px;
    }
}
     </style>
</head>
<body>
    <div class="container">
        <h1>Update Client</h1>

        <form action="search.php" method="POST" class="search-form">
            <div class="form-group">
                <label>Client Name</label>
                <input type="text" name="ClientName" required>
            </div>

            <div class="form-group">
                <label>Client Code</label>
                <input type="text" name="ClientCode" required>
            </div>

            <button type="submit">Search</button>
        </form>
    </div>
</body>
</html>