<?php 

    $hosts = require_once $_SERVER['DOCUMENT_ROOT'] . "/util/config/hosts.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/util/api/mysql.php";

?>
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

                <?php
                    foreach($hosts as $host_id => $host_data) { 
                

                ?>

                    <div class="host-status-container">
                        <div class="host-status-inner-container">
                            <div class="host-title-container">
                                <div class="host-title" id="<?php echo $host_id; ?>-host-collapse-button" data-bs-toggle="collapse" href="#<?php echo $host_id; ?>-host-collapse" role="button" aria-expanded="true" aria-controls="<?php echo $host_id; ?>-host-collapse" type="button">
                                    <div class="host-title-text h5 mb-0"><?php echo $host_data["name"]; ?></div>
                                    <div class="host-current-status ms-auto">Online</div>
                                    <div class="host-title-extend-img">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down host-title-arrow-icon" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" stroke="currentColor" stroke-width="2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="collapse show" id="<?php echo $host_id; ?>-host-collapse">

                                    <div class="chart-container mt-4">
                                        <svg class="status-chart-svg" name="<?php echo $host_id; ?>" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"></svg>
                                    </div>

                                    <?php
                                        if (count($host_data["services"]) > 0) {
                                            foreach($host_data["services"] as $service_id => $service_data) {
                                    ?>

                                    <div class="service-status-section d-flex flex-column mt-2">

                                        <div class="service-status-container">
                                            <div class="service-status-inner-container">
                                                <div class="host-title-container">
                                                    <div class="host-title collapsed" data-bs-toggle="collapse" href="#<?php echo $service_id; ?>-host-collapse2" role="button" aria-expanded="false" aria-controls="<?php echo $service_id; ?>-host-collapse2" type="button">
                                                        <div class="host-title-text h5 mb-0"><?php echo $service_data["name"]; ?></div>
                                                        <div class="host-current-status ms-auto">Online</div>
                                                        <div class="host-title-extend-img">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down host-title-arrow-icon" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" stroke="currentColor" stroke-width="2"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="collapse show" id="<?php echo $service_id; ?>-host-collapse">

                                                        <div class="chart-container mt-4">
                                                            <svg class="status-chart-svg" name="<?php echo $service_id; ?>" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"></svg>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <?php }} ?>

                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

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

        .status-chart-svg {
            height: 34px;
            width: 100%; /* Full width of the container */
        }

    </style>

    <script>

        (async function() {

            const status_charts = Array.from(document.getElementsByClassName('status-chart-svg'));

            function getStatusColor(status_code) {
                switch (status_code) {
                    case 0:
                        // "green"
                        return "#198754";
                    case 2:
                        // "yellow"
                        return '#FFC107';
                    case 1:
                        // "red"
                        return "#DC3545";
                    default:
                        return "#B3BAC5";
                }
            }

            function updateStatusCharts() {

                status_charts.forEach((status_chart) => {

                    const containerWidth = status_chart.clientWidth;
                    let daysToShow;
                    if (containerWidth > 1000 ) {
                        daysToShow = 90;
                    } else if (containerWidth > 550) {
                        daysToShow = 60;
                    } else {
                        daysToShow = 30;
                    }

                    function setRectsVisible() {
                    
                        if (!(daysToShow === 90)) {
                            Array.from(status_chart.childNodes).slice(0, 90 - daysToShow).forEach((rect, index) => {
                                rect.setAttribute("hidden", true);
                            });
                        }

                        if (!(daysToShow === 30)) {
                            Array.from(status_chart.childNodes).slice(-daysToShow).forEach((rect, index) => {
                                rect.removeAttribute("hidden");
                            });
                        }
                        
                    }

                    const totalWidth = daysToShow * (5) - 2;
                    status_chart.setAttribute('viewBox', `0 0 ${totalWidth} 34`);
                });

            }

            function createStatusChart(status_chart, outagesData) {
                status_chart.setAttribute('viewBox', `0 0 0 34`);

                const startDate = new Date();
                startDate.setUTCHours(0, 0, 0, 0); // Set UTC time to midnight

                for (let day = 0; day < 90; day++) {
                    const currentDate = new Date(startDate);
                    currentDate.setUTCDate(startDate.getUTCDate() + day);

                    const outage = findOutageForDay(currentDate, outagesData);

                    const rect = document.createElementNS("http://www.w3.org/2000/svg", 'rect');
                    rect.setAttribute('x', day * 5);
                    rect.setAttribute('y', 0);
                    rect.setAttribute('width', 3);
                    rect.setAttribute('height', 34);
                    rect.setAttribute('fill', getStatusColor(outage));

                    // Add event listeners
                    rect.addEventListener('mouseover', () => showTooltip(currentDate, outage));
                    rect.addEventListener('mouseout', hideTooltip);

                    status_chart.appendChild(rect);
                }
            }

            function showTooltip(date, outage) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                
                const tooltipText = document.createElement('span');
                tooltipText.textContent = `Day: ${date.getUTCDate()}, Outage: ${outage.message || 'No message'}`;
                tooltip.appendChild(tooltipText);

                // Set position-fixed style
                tooltip.style.position = 'fixed';
                tooltip.style.background = '#f0f0f0'; // Set your desired background color
                tooltip.style.padding = '5px';
                tooltip.style.border = '1px solid #ccc'; // Set your desired border style
                tooltip.style.borderRadius = '5px';
                
                // Ensure tooltip does not render outside of the page
                const maxX = window.innerWidth - tooltip.offsetWidth - 10;
                const maxY = window.innerHeight - tooltip.offsetHeight - 10;
                
                const x = Math.min(event.clientX, maxX);
                const y = Math.min(event.clientY, maxY);

                tooltip.style.left = `${x}px`;
                tooltip.style.top = `${y}px`;

                document.body.appendChild(tooltip);
            }


            function hideTooltip() {
                const tooltip = document.querySelector('.tooltip');
                if (tooltip) {
                    tooltip.remove();
                }
            }

            function findOutageForDay(currentDate, outagesData) {
                for (const outage of outagesData) {
                    const outageDate = new Date(outage.created_at);
                    const fixedDate = outage.fixed_at ? new Date(outage.fixed_at) : null;

                    outageDate.setUTCHours(0, 0, 0, 0);
                    if (fixedDate) {
                        fixedDate.setUTCHours(0, 0, 0, 0);
                    }

                    if (
                        outageDate <= currentDate &&
                        (!fixedDate || fixedDate >= currentDate)
                    ) {
                        return outage.code;
                    }
                }

                return 0; // Status code for no outage (green)
            }


            window.addEventListener('resize', updateStatusCharts);

            <?php

                $host_outages = json_encode(getHostOutages($hosts));
                
                echo "const hosts_outages_data = $host_outages;";

            ?>

            status_charts.forEach((status_chart) => {

                createStatusChart(status_chart, hosts_outages_data[status_chart.getAttribute("name")]);

            });

            updateStatusCharts();

        })();

    </script>

</body>
</html>