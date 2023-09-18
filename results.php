<?php
// Get the wallet address from the query parameters in the URL.
$queryAddress = $_GET['query_address'];

// Set your Etherscan API key.
$apiKey = '1RI5XYK14NB3M1G31NDI8INGSGIN2M8DAH';

// Assign the wallet address from the query parameters to a variable.
$walletAddress = $queryAddress;

// Define the starting block for fetching transactions.
$startBlock = '9000000';

// Define the ending block as 'latest,' meaning the most recent block.
$currentBlock = 'latest';

// Construct the API URL with the provided parameters.
$apiUrl = "https://api.etherscan.io/api?module=account&action=txlist&address=$walletAddress&startblock=$startBlock&endblock=$currentBlock&sort=asc&apikey=$apiKey";

// Retrieve the API response by making a GET request to the constructed URL.
$response = file_get_contents($apiUrl);

// Check if the response is false, indicating an issue with the API request.
if ($response === false) {
    die('Greška prilikom dobijanja podataka.'); // Die with an error message if the API request fails.
}

// Decode the JSON response from the API into a PHP object.
$data = json_decode($response);

// Check if the decoded data is null or if the API response status is not 1 (indicating an error).
if ($data === null || $data->status != 1) {
    die('Greška u odgovoru API-ja.'); // Die with an error message if there's an issue with the API response.
}

// Extract the transaction data from the API response.
$transactions = $data->result;

// Define the number of transactions per page
$transactionsPerPage = 100;

// Get the requested page number from the query parameters
$page = (isset($_GET['page']) && !empty($_GET['page'])) ? intval($_GET['page']) : 1;

// Calculate the start and end indices for the current page
$startIndex = ($page - 1) * $transactionsPerPage;
$endIndex = min($startIndex + $transactionsPerPage, count($transactions));

// Extract the transactions for the current page
$currentPageTransactions = array_slice($transactions, $startIndex, $endIndex - $startIndex);

// Now, $currentPageTransactions contains the transactions for the current page (up to 100 transactions)

// Calculate the total number of transactions
$totalTransactions = count($transactions);

// Calculate the total number of pages
$totalPages = ceil($totalTransactions / $transactionsPerPage);

require("php-includes/classes/database.class.php");
require("php-includes/auth.php");

$db = new Database('localhost', 'root', '', 'task');
$db->close();
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Home - Task</title>

		<meta name="keywords" content="Home - Task" />
		<meta name="description" content="Home - Task">
		<meta name="author" content="">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="vendor/animate/animate.compat.css">
		<link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="vendor/boxicons/css/boxicons.min.css" />
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>

	</head>
	<body>
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="../4.1.0" class="logo">
						<img src="img/logo.png" width="75" height="35" alt="Porto Admin" />
					</a>

					<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fas fa-bars" aria-label="Toggle sidebar"></i>
					</div>

				</div>

				<!-- start: search & user box -->
				<div class="header-right">
					
					<span class="separator"></span>

					<div id="userbox" class="userbox">
						<a href="#" data-bs-toggle="dropdown">
							<figure class="profile-picture">
								<img src="img/!logged-user.jpg" alt="Joseph Doe" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" />
							</figure>
							<div class="profile-info">
								<span class="name"><?php echo $_SESSION['username']; ?></span>
							</div>

							<i class="fa custom-caret"></i>
						</a>

						<div class="dropdown-menu">
							<ul class="list-unstyled mb-2">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="php-includes/logout.php"><i class="bx bx-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">

				    <div class="sidebar-header">
				        <div class="sidebar-title">
				            Navigation
				        </div>
				        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
				            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
				        </div>
				    </div>

				    <div class="nano">
				        <div class="nano-content">
				            <nav id="menu" class="nav-main" role="navigation">

				                <ul class="nav nav-main">
				                    <li>
				                        <a class="nav-link" href="index.php">
				                            <i class="bx bx-home-alt" aria-hidden="true"></i>
				                            <span>Home</span>
				                        </a>                        
				                    </li>
				                </ul>
				            </nav>

				            <hr class="separator" />
	        
				        </div>

				    </div>

				</aside>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Home</h2>
					</header>

					<!-- start: page -->
                        <div class="row row-gutter-sm justify-content-between">
                            <div class="col-lg-auto order-2 order-lg-1">
							    <p class="text-center text-lg-left mb-0">100 results per page</p>
							</div>
                            <div class="col-lg-auto order-1 order-lg-2 mb-3 mb-lg-0">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination pagination-modern pagination-modern-spacing justify-content-center justify-content-lg-start mb-0">
                                        <li class="page-item">
                                            <a class="page-link prev" href="#" aria-label="Previous">
                                                <span><i class="fas fa-chevron-left" aria-label="Previous"></i></span>
                                            </a>
                                        </li>
                                        <?php
                                        // Display page 1 only when not on page 1
                                        if ($page > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="?query_address=' . $_GET['query_address'] . '&page=1">1</a></li>';
                                        }

                                        // Display ellipsis if needed
                                        if ($page > 3) {
                                            echo '<li class="page-item"><a class="page-link" href="#" disabled="true">...</a></li>';
                                        }

                                        // Display pages around the current page
                                        for ($i = max(1, $page - 1); $i <= min($totalPages, $page + 1); $i++) {
                                            if ($i == $page) {
                                                echo '<li class="page-item active"><a class="page-link" href="?query_address=' 
                                                . $_GET['query_address'] . '&page=' . $i . '">' . $i . '</a></li>';
                                            } else {
                                                echo '<li class="page-item"><a class="page-link" href="?query_address=' 
                                                . $_GET['query_address'] . '&page=' . $i . '">' . $i . '</a></li>';
                                            }
                                        }

                                        // Display ellipsis if needed
                                        if ($page < $totalPages - 2) {
                                            echo '<li class="page-item"><a class="page-link" href="#" disabled="true">...</a></li>';
                                        }

                                        // Display the last page
                                        if ($page < $totalPages) {
                                            echo '<li class="page-item"><a class="page-link" href="?query_address=' 
                                            . $_GET['query_address'] . '&page=' . $totalPages . '">' . $totalPages . '</a></li>';
                                        }
                                        ?>
                                        <li class="page-item">
                                            <a class="page-link next" href="#" aria-label="Next">
                                                <span><i class="fas fa-chevron-right" aria-label="Next"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
							<div class="col-lg-12">
								<section class="card">
									<header class="card-header">
										<div class="card-actions">
											<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
											<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
										</div>

										<h2 class="card-title">Results</h2>
									</header>
									<div class="card-body">
										<table class="table table-responsive-md mb-0">
											<thead>
												<tr>
													<th>#</th>
                                                    <th>Transaction Hash</th>
                                                    <th>Method</th>
                                                    <th>Block</th>
													<th>From</th>
													<th>To</th>
													<th>Value</th>
													<th>Gas Price</th>
                                                    <th>Gas Used</th>
                                                    <th>Age</th>
												</tr>
											</thead>
											<tbody>
												<tr>
												<?php
                                                $iteration = 0;	
                                        
                                                foreach ($currentPageTransactions as $transaction) {
                                                    $iteration++;
                                                    $current_date = new DateTime();
                                                    $transaction_date = new DateTime(date('Y-m-d H:i:s', $transaction->timeStamp));
                                                    $interval = $current_date->diff($transaction_date);

                                                    if ($interval->y > 0) {
                                                        $time_ago = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->m > 0) {
                                                        $time_ago = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->d > 0) {
                                                        $time_ago = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->h > 0) {
                                                        $time_ago = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
                                                    } elseif ($interval->i > 0) {
                                                        $time_ago = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
                                                    } else {
                                                        echo 'just now';
                                                    }
                                                    echo '<tr>';
                                                    
                                                    echo '<td>' . $iteration . '</td>';

                                                    echo '<td><a href="transaction-details.php?query_hash=' . $transaction->hash 
                                                        . '">' . substr($transaction->hash, 0, 10) . '...</a></td>';

                                                    echo '<td>' . strstr($transaction->functionName, '(', true) . '</td>';

                                                    echo '<td>' . $transaction->blockNumber . '</td>';

                                                    echo '<td data-hover="'.$transaction->from.'" id="copyFrom" value="' 
                                                        . $transaction->from . '">' 
                                                        . '<span><a href="results.php?query_address=' . $transaction->from 
                                                        . '">' . substr($transaction->from, 0, 10) . '...</a></span>' 
                                                        . '<i onclick="copyValueFrom(this)" class="fa-regular fa-copy"></i></td>';

                                                    echo '<td id="copyTo" value="' . $transaction->to . '">' 
                                                        . '<span>Origin Trail: TRAC Token</span>' 
                                                        . ' <i onclick="copyValueTo(this)" class="fa-regular fa-copy"></i></td>';

                                                    echo '<td>' . $transaction->value . ' ETH</td>';

                                                    echo '<td>' . $transaction->gasPrice . '</td>';

                                                    echo '<td>' . $transaction->gasUsed . '</td>';

                                                    echo '<td>' . $time_ago . '</td>';

                                                    echo '</tr>';
                                                
                                                }
                                                ?>
												</tr>
											</tbody>
										</table>
									</div>
								</section>
							</div>
						</div>
                        <!-- end: page -->
				</section>
			</div>
		</section>

		<!-- Vendor -->
		<script src="vendor/jquery/jquery.js"></script>
		<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="vendor/popper/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="vendor/common/common.js"></script>
		<script src="vendor/nanoscroller/nanoscroller.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->

		<!-- Theme Base, Components and Settings -->
		<script src="js/theme.js"></script>

		<!-- Theme Custom -->
		<script src="js/custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="js/theme.init.js"></script>

        <script>
            function copyValueFrom(button) {
                var copyText = button.parentNode.getAttribute("value");
                var textArea = document.createElement("textarea");
                textArea.value = copyText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert("Copied address From: " + copyText);
            }

            function copyValueTo(button) {
                var copyText = button.parentNode.getAttribute("value");
                var textArea = document.createElement("textarea");
                textArea.value = copyText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert("Copied address To: " + copyText);
            }
        </script>

	</body>
</html>