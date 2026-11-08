<?php
$host = getenv('DB_HOST');
$nama = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nama;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Gagal terhubung ke database. Coba muat ulang beberapa detik lagi.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && trim($_POST['isi'] ?? '') !== '') {
    $stmt = $pdo->prepare('INSERT INTO catatan (isi) VALUES (:isi)');
    $stmt->execute([':isi' => trim($_POST['isi'])]);
    header('Location: /');
    exit;
}

$daftar = $pdo->query('SELECT id, isi, dibuat FROM catatan ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Aplikasi Catatan BERHASILLLLLLLLLLLLLLL</title>
</head>
<body>
  <h1>Aplikasi Catatan GIWWW</h1>
  <form method="post">
    <input type="text" name="isi" placeholder="Tulis catatan..." required>
    <button type="submit">Simpan</button>
  </form>

  <h2>Daftar catatan (<?php echo count($daftar); ?>)</h2>
  <ul>
    <?php foreach ($daftar as $baris): ?>
      <li><?php echo htmlspecialchars($baris['isi']); ?> <small>(<?php echo $baris['dibuat']; ?>)</small></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
