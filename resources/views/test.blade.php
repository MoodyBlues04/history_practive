<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .hotspot {
            position: absolute;
            width: 32px;
            height: 32px;
            border: 2px solid #fff;
            border-radius: 50%;
            background: #2c3e50;
            color: white;
            cursor: pointer;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .hotspot:hover {
            background: #e74c3c;
            transform: translate(-50%, -50%) scale(1.1);
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .info-popup {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            z-index: 1000;
            min-width: 300px;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
some stuff before
<div class="image-container" style="position: relative; display: inline-block;">
    <!-- Main Image -->
    <img src="http://127.0.0.1:8000/storage/map/optics_museum_scheme.jpg" alt="Museum Map" class="map-image" style="max-width: 100%; height: auto; user-drag: none;">

    <!-- Numbered Hotspots -->
    <button class="hotspot" style="top: 30%; left: 25%;" data-number="5" data-info="Ancient Vase Collection">
        <span>5</span>
    </button>

    <button class="hotspot" style="top: 45%; left: 60%;" data-number="12" data-info="Renaissance Paintings">
        <span>12</span>
    </button>

    <!-- Add more buttons following the pattern -->

    <!-- Information Popup -->
    <div class="info-popup" id="infoPopup">
        <div class="popup-header">
            <h3>Exhibit #<span id="popupNumber">5</span></h3>
            <button class="close-btn">&times;</button>
        </div>
        <div class="popup-body">
            <p id="popupInfo">Sample exhibit information</p>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.hotspot').forEach(button => {
        button.addEventListener('click', (e) => {
            const popup = document.getElementById('infoPopup');
            const number = e.currentTarget.dataset.number;
            const info = e.currentTarget.dataset.info;

            document.getElementById('popupNumber').textContent = number;
            document.getElementById('popupInfo').textContent = info;
            popup.style.display = 'block';
        });
    });

    document.querySelector('.close-btn').addEventListener('click', () => {
        document.getElementById('infoPopup').style.display = 'none';
    });
</script></body>
</html>
