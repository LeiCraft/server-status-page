<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f5f5f5;
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
</head>
<body>
    <div id="chart-container">
        <svg id="chart-svg" xmlns="http://www.w3.org/2000/svg"></svg>
    </div>

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
