<!DOCTYPE html>
<html><head><title>Dashboard - ZED CORE</title>
<style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;background:#f5f5f4;margin:0}
.box{background:#fff;border-radius:16px;padding:40px;text-align:center;border:1px solid #e5e5e5}
h1{font-size:24px;font-weight:700;margin-bottom:8px}p{color:#737373;margin-bottom:20px}
a{background:#0a0a0a;color:#fff;padding:10px 24px;border-radius:50px;text-decoration:none;font-size:14px;font-weight:600}
</style></head>
<body><div class="box">
<h1>Dashboard ZED CORE</h1>
<p>Halaman dashboard sedang dalam pengembangan.</p>
<form method="POST" action="/logout">{{ csrf_field() }}<button type="submit" style="background:#0a0a0a;color:#fff;padding:10px 24px;border-radius:50px;border:none;cursor:pointer;font-size:14px;font-weight:600">Logout</button></form>
</div></body></html>
