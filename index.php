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

                                <div class="chart-container mt-4">
                                    <svg id="chart-svg" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"></svg>
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

        .chart-container {
            width: 100%;
        }

        #chart-svg {
            height: 34px;
            width: 100%; /* Full width of the container */
        }

    </style>

    <script>
        
        let serverStatusData = [];

        // dummy data
        for (let i = 0; i < 90; i++) {
            let randInt = Math.floor(Math.random() * 3);
            let status = "";
            switch (randInt) {
                case 0:
                    status = "green";
                    break;
                case 1:
                    status = "yellow";
                    break;
                case 2:
                    status = "red";
                    break;
            }
            serverStatusData.push(status);
        }

        const svg = document.getElementById('chart-svg');
        const container = document.getElementById('chart-svg');
        let daysToShow = 30; // Default to last 30 days
        const minContainerWidth = 400; // Set a minimum container width to trigger the update

        // Calculate the total width of the SVG
        function updateChart() {
            const containerWidth = container.clientWidth;
            const factor = 0.04; // Adjust this factor based on your preference
            //const boxWidth = containerWidth / serverStatusData.length - factor;
            const boxWidth = 3;

            // Adjust the days to show based on container width
            if (containerWidth < minContainerWidth) {
                daysToShow = 30; // If below the minimum width, show last 30 days
            } else if (containerWidth < minContainerWidth * 2) {
                daysToShow = 60; // If below twice the minimum width, show last 60 days
            } else {
                daysToShow = 90; // Otherwise, show last 90 days
            }

            const totalWidth = daysToShow * (boxWidth + 2) - 2;
            svg.setAttribute('viewBox', `0 0 ${totalWidth} 34`);
            svg.innerHTML = ""; // Clear existing rectangles

            serverStatusData.slice(-daysToShow).forEach((status, index) => {
                const rect = document.createElementNS("http://www.w3.org/2000/svg", 'rect');
                rect.setAttribute('x', index * (boxWidth + 2));
                rect.setAttribute('y', 0);
                rect.setAttribute('width', boxWidth);
                rect.setAttribute('height', 34);
                rect.setAttribute('fill', getStatusColor(status));
                //rect.setAttribute('rx', 8);
                //rect.setAttribute('ry', 8);

                svg.appendChild(rect);
            });
        }

        function getStatusColor(status_code) {
            switch (status_code) {
                case "green":
                    return "#198754";
                case "yellow":
                    return '#FFC107';
                case "red":
                    return "#DC3545";
                default:
                    return "#B3BAC5";
            }
        }

        // Example usage to update chart initially
        updateChart();

        // Resize event listener to update the chart on container width changes
        window.addEventListener('resize', updateChart);

    </script>

</body>
</html>
