<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        #document {
            width: 100%;
            height: 100%;
            transform-origin: 0px 0px;
            transform: scale(1) translate(0px, 0px);
        }
    </style>
</head>
<body>
some stuff before
<div id="document">
    some test
    <img style="width: 100%"
         src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQJxo2NFiYcR35GzCk5T3nxA7rGlSsXvIfJwg&s"
         crossOrigin="" />
</div>
stuff after
<script>
    let scale = 1,
        xOffset = 0,
        yOffset = 0,
        clicked = false,
        doc = document.getElementById("document");

    function setTransform() {
        if (clicked) {
            doc.style.transform = "translate(" + xOffset + "px, " + yOffset + "px) scale(" + scale + ")";
        } else {
            doc.style.transform = "scale(1) translate(0px, 0px)";
        }
    }

    doc.onclick = function(e) {
        e.preventDefault();
        const xs = (e.clientX - xOffset) / scale;
        const ys = (e.clientY - yOffset) / scale;

        clicked ? (scale /= 1.7) : (scale *= 1.7);
        clicked = !clicked;

        xOffset = e.clientX - xs * scale;
        yOffset = e.clientY - ys * scale;

        setTransform();
    }
</script>
</body>
</html>
