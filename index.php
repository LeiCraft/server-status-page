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

            <div class="total-status text-center h4 mb-5">Alle Systeme Online</div>

            <div class="host-status-section mt-5">

                <div class="main-host">
                    <div class></div>
                </div>

            </div>

        </div>
    </main>

    <footer class="footer" id="footer"><?php include $_SERVER['DOCUMENT_ROOT'] . "/util/defaults/footer.php"; ?></footer>

    <style>
        .total-status {
            padding: 12px 20px;
        }
    </style>

</body>
</html>
