<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login & Register</title>
  <link
    rel="stylesheet"
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
  <canvas id="bg"></canvas>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <div class="container">
    <!-- Login -->
    <form action="../backend/login.php" method="post" class="form-box login active">
      <h1>Giriş Yap</h1>
      <div class="input-box">
        <input type="text" placeholder="Email" name="eposta" required />
        <i class="bx bx-envelope"></i>
      </div>
      <div class="input-box">
        <input type="password" name="sifre" placeholder="Password" required />
        <i class="bx bx-lock"></i>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register-link">
        <p>
          Hesabınız yok ise <span onclick="toggleForm()">Kayıt Olun</span>
        </p>
      </div>
    </form>

    <!-- Register -->
    <form method="post" action="../backend/register.php" class="form-box register">
      <h1>Kayıt Ol</h1>
      <div class="input-box">
        <input type="text" placeholder="Name" name="ad_soyad" required />
        <i class="bx bx-user"></i>
      </div>
      <div class="input-box">
        <input
          type="password"
          placeholder="Password"
          name="sifre"
          required />
        <i class="bx bx-lock"></i>
      </div>
      <div class="input-box">
        <input type="email" placeholder="Email" name="eposta" required />
        <i class="bx bx-envelope"></i>
      </div>
      <button type="submit" class="btn">Kayıt ol</button>
      <div class="register-link">
        <p>
          Hesabınız var ise <span onclick="toggleForm()">Giriş Yapın</span>
        </p>
      </div>
    </form>
  </div>

  <script src="script.js"></script>
</body>

</html>