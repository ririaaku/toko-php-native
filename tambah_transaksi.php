<?php
require "cek_session.php";
cek_akses(['kasir']);
require "koneksi.php";
 
// Ambil data pelanggan, karyawan, dan produk untuk dropdown
$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY nama_pelanggan");
$karyawan  = mysqli_query($koneksi, "SELECT * FROM karyawan ORDER BY nama_karyawan");
$produk    = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY nama_produk");
 
// Simpan data produk ke array supaya bisa dipakai untuk validasi & hitung total
$daftar_produk = [];
while ($row = mysqli_fetch_assoc($produk)) {
    $daftar_produk[$row['id_produk']] = $row;
}
 
$error = "";
 
if (isset($_POST['simpan'])) {
    $id_pelanggan  = (int) $_POST['id_pelanggan'];
    $id_karyawan   = (int) $_POST['id_karyawan'];
    $id_produk_arr = $_POST['id_produk'];
    $qty_arr       = $_POST['qty'];
 
    // Validasi qty dan stok SEBELUM menyimpan apa pun
    foreach ($id_produk_arr as $i => $id_p) {
        $qty = (int) $qty_arr[$i];
        $stok_tersedia = $daftar_produk[$id_p]['stok'];
        if ($qty < 1) {
            $error = "Jumlah produk harus lebih dari 0.";
            break;
        }
        if ($qty > $stok_tersedia) {
            $error = "Stok " . $daftar_produk[$id_p]['nama_produk'] . " tidak cukup (tersisa $stok_tersedia).";
            break;
        }
    }
 
    if ($error === "") {
        // Hitung total dari harga di database (server-side),
        // bukan dari input client
        $total = 0;
        foreach ($id_produk_arr as $i => $id_p) {
            $qty   = (int) $qty_arr[$i];
            $harga = $daftar_produk[$id_p]['harga'];
            $total += $harga * $qty;
        }
 
        // 1) Simpan header transaksi
        $stmt = mysqli_prepare($koneksi, "INSERT INTO transaksi_penjualan (id_pelanggan, id_karyawan, total) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iii", $id_pelanggan, $id_karyawan, $total);
        mysqli_stmt_execute($stmt);
        $id_transaksi = mysqli_insert_id($koneksi);
 
        // 2) Simpan detail transaksi + kurangi stok produk, baris per baris
        foreach ($id_produk_arr as $i => $id_p) {
            $qty      = (int) $qty_arr[$i];
            $harga    = $daftar_produk[$id_p]['harga'];
            $subtotal = $harga * $qty;
 
            $stmt = mysqli_prepare($koneksi, "INSERT INTO detail_transaksi (id_transaksi, id_produk, qty, subtotal) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiii", $id_transaksi, $id_p, $qty, $subtotal);
            mysqli_stmt_execute($stmt);
 
            $stmt = mysqli_prepare($koneksi, "UPDATE produk SET stok = stok - ? WHERE id_produk = ?");
            mysqli_stmt_bind_param($stmt, "ii", $qty, $id_p);
            mysqli_stmt_execute($stmt);
        }
        require "log_helper.php";
        catat_log('CREATE', 'Transaksi', "Transaksi #$id_transaksi, total Rp$total");
        
        header("Location: detail_transaksi.php?id=" . $id_transaksi);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Tambah Transaksi Penjualan</h3>
 
  <?php if ($error) { ?>
  <div class="alert alert-danger"><?= $error ?></div>
  <?php } ?>
 
  <form method="POST">
    <div class="row mb-3">
      <div class="col">
        <label>Pelanggan</label>
        <select name="id_pelanggan" class="form-control" required>
          <?php mysqli_data_seek($pelanggan, 0);
          while ($pl = mysqli_fetch_assoc($pelanggan)) { ?>
          <option value="<?= $pl['id_pelanggan'] ?>"><?= $pl['nama_pelanggan'] ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col">
        <label>Kasir</label>
        <select name="id_karyawan" class="form-control" required>
          <?php mysqli_data_seek($karyawan, 0);
          while ($kr = mysqli_fetch_assoc($karyawan)) { ?>
          <option value="<?= $kr['id_karyawan'] ?>"><?= $kr['nama_karyawan'] ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
 
    <table class="table table-bordered" id="tabel-produk">
      <thead>
        <tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Stok</th><th></th></tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <select name="id_produk[]" class="form-control pilih-produk" required>
              <?php foreach ($daftar_produk as $pr) { ?>
              <option value="<?= $pr['id_produk'] ?>" data-harga="<?= $pr['harga'] ?>" data-stok="<?= $pr['stok'] ?>">
                <?= $pr['nama_produk'] ?>
              </option>
              <?php } ?>
            </select>
          </td>
          <td class="kolom-harga">-</td>
          <td><input type="number" name="qty[]" class="form-control" min="1" value="1" required></td>
          <td class="kolom-stok">-</td>
          <td><button type="button" class="btn btn-danger btn-sm hapus-baris">Hapus</button></td>
        </tr>
      </tbody>
    </table>
 
    <button type="button" id="tambah-baris" class="btn btn-secondary mb-3">+ Tambah Baris Produk</button>
    <br>
    <button type="submit" name="simpan" class="btn btn-primary">Simpan Transaksi</button>
    <a href="transaksi.php" class="btn btn-outline-secondary">Batal</a>
  </form>
</div>
 
<script>
document.getElementById('tambah-baris').addEventListener('click', function () {
    const tbody = document.querySelector('#tabel-produk tbody');
    const baris = tbody.rows[0].cloneNode(true);
    baris.querySelector('input[name="qty[]"]').value = 1;
    tbody.appendChild(baris);
    pasangEvent();
});
 
function pasangEvent() {
    document.querySelectorAll('.pilih-produk').forEach(function (select) {
        select.onchange = function () {
            const opsi  = select.options[select.selectedIndex];
            const baris = select.closest('tr');
            baris.querySelector('.kolom-harga').innerText = 'Rp' + Number(opsi.dataset.harga).toLocaleString('id-ID');
            baris.querySelector('.kolom-stok').innerText  = 'Stok: ' + opsi.dataset.stok;
        };
        select.onchange();
    });
    document.querySelectorAll('.hapus-baris').forEach(function (btn) {
        btn.onclick = function () {
            const tbody = document.querySelector('#tabel-produk tbody');
            if (tbody.rows.length > 1) {
                btn.closest('tr').remove();
            }
        };
    });
}
pasangEvent();
</script>
</body>
</html>