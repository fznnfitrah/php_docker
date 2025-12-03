<?php
// --- BAGIAN BACKEND (DATABASE MYSQL) ---

require 'db.php'; // Panggil koneksi database

// 1. Logika POST (Simpan Transaksi ke MySQL)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'] ?? '';
    $amountInput = floatval($_POST['amount'] ?? 0);
    $type = $_POST['tipe_transaksi'] ?? 'pemasukan';

    // Logika positif/negatif untuk penyimpanan
    // Kita simpan nilai aslinya (negatif jika pengeluaran) agar mudah dijumlah
    if ($type === 'pengeluaran') {
        $finalAmount = -abs($amountInput);
    } else {
        $finalAmount = abs($amountInput);
    }

    if ($description && $amountInput) {
        $stmt = $pdo->prepare("INSERT INTO transactions (description, amount, type) VALUES (?, ?, ?)");
        $stmt->execute([$description, $finalAmount, $type]);
        
        // Redirect
        header("Location: index.php");
        exit();
    }
}

// 2. Ambil Data dari Database untuk Dashboard & Riwayat
// Ambil semua transaksi diurutkan dari yang terbaru
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC");
$transactions = $stmt->fetchAll();

// 3. Hitung Saldo & Total
$totalPemasukan = 0;
$totalPengeluaran = 0;
$saldo = 0;

foreach ($transactions as $tx) {
    $val = floatval($tx['amount']);
    $saldo += $val; // Saldo adalah jumlah seluruh transaksi (+ dan -)

    if ($val > 0) {
        $totalPemasukan += $val;
    } else {
        $totalPengeluaran += $val; // Akan bernilai negatif
    }
}

// Pengeluaran di-positifkan untuk tampilan UI
$totalPengeluaran = abs($totalPengeluaran);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DompetKu (MySQL Version)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>DompetKu (Sister)</h1>
            <p>Aplikasi Dompet Terdistribusi dengan Docker & MySQL.</p>
            
            <div class="theme-switch-wrapper">
                <label class="theme-switch" for="theme-toggle">
                    <input type="checkbox" id="theme-toggle" />
                    <span class="slider round"></span>
                </label>
            </div>
        </header>

        <div class="summary-grid">
            <div class="summary-box">
                <h3><span class="icon-green">↑</span>Pemasukan</h3>
                <div class="amount amount-green">Rp <?php echo number_format($totalPemasukan, 0, ',', '.'); ?></div>
            </div>
            <div class="summary-box">
                <h3><span class="icon-red">↓</span>Pengeluaran</h3>
                <div class="amount amount-red">Rp <?php echo number_format($totalPengeluaran, 0, ',', '.'); ?></div>
            </div>
            <div class="summary-box">
                <h3><span class="icon-blue">💰</span>Saldo Saat Ini</h3>
                <div class="amount amount-blue">Rp <?php echo number_format($saldo, 0, ',', '.'); ?></div>
            </div>
        </div>
        
        <div class="card">
            <h2>Tambah Transaksi</h2>
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <input type="text" id="description" name="description" class="form-control" placeholder="Contoh: Bayar Cloud Hosting" required>
                </div>
                <div class="form-group">
                    <label for="amount">Jumlah (Rp)</label>
                    <input type="number" id="amount" name="amount" class="form-control" placeholder="Contoh: 50000" min="0" required>
                </div>
                <div class="form-group">
                    <label>Tipe Transaksi</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="tipe_transaksi" value="pemasukan" checked>
                            Pemasukan
                        </label>
                        <label>
                            <input type="radio" name="tipe_transaksi" value="pengeluaran">
                            Pengeluaran
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn">Simpan Transaksi</button>
            </form>
        </div>

        <div class="card">
            <h2>Riwayat Transaksi</h2>
            <ul class="history-list">
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $tx): ?>
                        <li class="history-item">
                            <span class="description"><?php echo htmlspecialchars($tx['description']); ?></span>
                            <span class="amount <?php echo ($tx['amount'] > 0) ? 'amount-green' : 'amount-red'; ?>">
                                Rp <?php echo number_format($tx['amount'], 0, ',', '.'); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="no-data">
                        <span>🔌</span>
                        <p>Belum ada data di Database MySQL.</p>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

    </div> 
    <script src="script.js"></script>
</body>
</html>