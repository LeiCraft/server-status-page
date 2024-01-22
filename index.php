<!doctype html>
<html lang="de">
<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/util/defaults/head.php";?>
    <link href="/index/codes.css" rel="stylesheet">
</head>
<body>

    <header id="header"><?php include $_SERVER['DOCUMENT_ROOT'] . "/util/defaults/header.php"; ?></header>

    <main class="mb-auto">
        <div class="container">

            <div class="total-status text-center h4 mb-5 code-green-bg">Alle Systeme Online</div>

            <div class="host-status-section mt-5 d-flex flex-column">

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
                            <div class="collapse" id="main-host-collapse">

                                <div id="chart-container" class="mt-4">
                                    <svg id="chart-svg" xmlns="http://www.w3.org/2000/svg"></svg>
                                </div>

                                <div class="service-status-section d-flex flex-column mt-2">

                                    <div class="service-status-container">
                                        <div class="service-status-inner-container">
                                            <div class="host-title-container">
                                                <div class="host-title collapsed" data-bs-toggle="collapse" href="#main-host-collapse2" role="button" aria-expanded="false" aria-controls="main-host-collapse2" type="button">
                                                    <div class="host-title-text h5 mb-0">Main Server</div>
                                                    <div class="host-current-status ms-auto">Online</div>
                                                    <div class="host-title-extend-img">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down host-title-arrow-icon" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" stroke="currentColor" stroke-width="2"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
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
            margin: 15px;
        }
        .host-title {
            display: flex;
            align-items: center;
        }
        .host-title-extend-img {
            display: flex;
            align-items: center;
            padding-left: 10px;
        }
        .host-title-arrow-icon {
            transition: transform 0.3s;
            transform: rotate(-180deg);
        }
        .host-title.collapsed .host-title-arrow-icon {
            transform: none;
        }
        .service-status-container {
            border: 1px white solid;
            border-top: none !important;
            background-color: #1a1b2e;
        }
        .service-status-container:first-child {
            border: 1px white solid !important;
        }
        .service-status-inner-container {
            margin: 15px 5px 15px 15px;
        }

        #chart-container {
            max-width: 600px;
            overflow-y: scroll;
        }

        #chart-svg {
            /* height: 50px; Adjust the height of the SVG bar */
            width: 100%; /* Full width of the container */
        }

    </style>

    <script>
        // Dummy data for demonstration purposes
        const serverStatusData = [20, 15, 25, 18, 22, 30, 28, 24, 32, 28, 30, 35, 28, 22, 26, 30, 25, 20, 18, 15, 22, 28, 32, 35, 30, 26, 22, 18, 15, 20];

        const svg = document.getElementById('chart-svg');
        const boxWidth = 20; // Fixed box width
        const spacing = 5; // Fixed spacing between boxes

        // Calculate the total width of the SVG
        const totalWidth = serverStatusData.length * (boxWidth + spacing) - spacing;

        // Set the viewBox to allow dynamic resizing
        svg.setAttribute('viewBox', `0 0 ${totalWidth} 100`);

        serverStatusData.forEach((status, index) => {
            const rect = document.createElementNS("http://www.w3.org/2000/svg", 'rect');
            rect.setAttribute('x', index * (boxWidth + spacing));
            rect.setAttribute('y', 0);
            rect.setAttribute('width', boxWidth);
            rect.setAttribute('height', 100);
            rect.setAttribute('fill', getStatusColor(status));
            rect.setAttribute('rx', 8);
            rect.setAttribute('ry', 8);

            svg.appendChild(rect);
        });

        function getStatusColor(status) {
            if (status > 30) {
                return 'rgba(255, 0, 0, 0.8)';
            } else if (status > 20) {
                return 'rgba(255, 165, 0, 0.8)';
            } else {
                return 'rgba(0, 128, 0, 0.8)';
            }
        }

    </script>

</body>
</html>
