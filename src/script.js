// Menjalankan skrip setelah semua konten HTML dimuat
document.addEventListener('DOMContentLoaded', () => {
    
    // Ambil tombol saklar dan body dokumen
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;

    // --- 1. Cek Pilihan Tersimpan saat Membuka Halaman ---
    
    // Ambil tema yang tersimpan dari 'localStorage' browser
    const savedTheme = localStorage.getItem('theme');
    
    if (savedTheme === 'light') {
        // Jika tersimpan 'light', terapkan mode terang
        body.classList.add('light-mode');
        // Pastikan posisi saklar juga benar (menyala)
        themeToggle.checked = true;
    }

    // --- 2. Tambahkan Listener saat Saklar Di-klik ---
    
    themeToggle.addEventListener('change', () => {
        // 'change' digunakan untuk checkbox/saklar
        
        if (body.classList.contains('light-mode')) {
            // Jika SUDAH mode terang, ganti ke gelap
            body.classList.remove('light-mode');
            // Simpan pilihan 'dark' ke localStorage
            localStorage.setItem('theme', 'dark');
        } else {
            // Jika MASIH mode gelap, ganti ke terang
            body.classList.add('light-mode');
            // Simpan pilihan 'light' ke localStorage
            localStorage.setItem('theme', 'light');
        }
    });
});