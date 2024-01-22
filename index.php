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

                <div class="main-host host-status-container">
                    <div class="host-status-inner-container">
                        <div class="host-title-container">
                            <div class="host-title">
                                <div class="host-title-extend-img">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </div>
                                <div class="host-title-text h5 mb-0">Main Server</div>
                                <div class="host-current-status ms-auto">Online</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-host host-status-container">
                    <div class="host-status-inner-container">
                        <div class="host-title-container">
                            <div class="host-title collapsed" data-bs-toggle="collapse" href="#main-host-collapse" role="button" aria-expanded="false" aria-controls="main-host-collapse" type="button">
                                <div class="host-title-text h5 mb-0">Main Server</div>
                                <div class="host-current-status ms-auto">Online</div>
                                <div class="host-title-extend-img">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down host-title-arrow-icon" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" stroke="currentColor" stroke-width="2"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="main-host-collapse"></div>
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
        .host-status-container {
            border: 1px white solid;
            border-top: none !important;
            background-color: #1a1b2e;
        }
        .host-status-container:first-child {
            border: 1px white solid !important;
        }
        .host-status-inner-container {
            margin: 10px 15px;
        }
        .host-title {
            display: flex;
            align-items: center;
        }
        .host-title-extend-img {
            display: flex;
            align-items: center
        }
        .host-title-text {
            padding-left: 10px;
        }
        .host-title-arrow-icon {
            transition: transform 0.3s;
            transform: rotate(-180deg);
        }
        .host-title.collapsed .host-title-arrow-icon {
            transform: none;
        }
    </style>

    <script>

    </script>

</body>
</html>
