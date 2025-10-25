<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>Test - If you see this, the route works!</h1>
    <p>Total Dramas: {{ $dramas->total() }}</p>
    <p>Genres Count: {{ $genres->count() }}</p>
</body>
</html>
