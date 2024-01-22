<!doctype html>
<html lang="de">
<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/util/defaults/head.php";?>
    <link href="/index/codes.css" rel="stylesheet">
</head>
<body>

    <header id="header"><?php include $_SERVER['DOCUMENT_ROOT'] . "/util/defaults/header.php"; ?></header>

    <main>
        <div class="container">

            <div class="total-status text-center h4 mb-5 code-green-bg">Alle Systeme Online</div>

            <div class="host-status-section mt-5 d-flex flex-column">

                <div class="main-host host-status-component">
                    <div class="host-name-component">
                        <span class="host-name">Main Server</span>
                        <span class="host-current-status">Online</span>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="footer" id="footer"><?php include $_SERVER['DOCUMENT_ROOT'] . "/util/defaults/footer.php"; ?></footer>

    <style>
        .total-status {
            padding: 12px 20px;
        }
        .host-status-component {
            border-top: 1px #E0E0E0, solid;
            background-color: #1a1b2e;
        }
        .host-current-status {
            float: right;
        }
    </style>

</body>
</html>
