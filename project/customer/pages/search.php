<?php
// Start the session only if it's not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['CUSTOMER_ID']);  // Check if the user is logged in

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Database connection
$con = new mysqli('localhost', 'root', '', 'project');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get search query from the URL
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

// If the search query is empty, prevent querying the entire database
if (empty($searchQuery)) {
    echo "Please enter a search term.";
    exit;
}

// Prepare SQL query to search products, categories, and brands
$sql = "
    SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRICE, c.CATEGORY_NAME, b.BRAND_NAME
    FROM product p
    LEFT JOIN categories c ON p.CATEGORY_ID = c.CATEGORY_ID
    LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
    WHERE p.PRODUCT_NAME LIKE ? OR c.CATEGORY_NAME LIKE ? OR b.BRAND_NAME LIKE ?
";

// Prepare the statement
$stmt = $con->prepare($sql);

if ($stmt === false) {
    // If the statement preparation fails, output the error
    die("Error in SQL prepare: " . $con->error);
}

// Prepare the search query parameter with wildcards
$searchQueryParam = "%" . $searchQuery . "%";

// Bind parameters
$stmt->bind_param("sss", $searchQueryParam, $searchQueryParam, $searchQueryParam);

// Execute the query
$stmt->execute();

// Get results
$result = $stmt->get_result();

// Fetch results and send as an array to display
$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

// Close connection
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .search-container {
            padding: 2rem;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            width: 80%;
            border-radius: 8px;
        }

        .search-container h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .search-results {
            margin-top: 2rem;
        }

        .result-item {
            display: flex;
            align-items: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .result-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
        }

        .result-item .details h3 {
            font-size: 1.25rem;
            margin: 0 0 0.5rem 0;
        }

        .result-item .details p {
            margin: 0;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="search-container">
        <h1>Search Results</h1>
        <div id="results" class="search-results">
            <!-- Results will be dynamically populated here -->
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $item): ?>
                    <a href="sproduct.php?id=<?php echo $item['PRODUCT_ID']; ?>" class="text-decoration-none">
                        <div class="result-item">
                            <div class="details">
                                <h3><?php echo $item['PRODUCT_NAME']; ?></h3>
                                <p>Price: ₹<?php echo $item['PRICE']; ?></p>
                                <p>Category: <?php echo $item['CATEGORY_NAME']; ?></p>
                                <p>Brand: <?php echo $item['BRAND_NAME']; ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No results found for your search.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
