<?php
/*
 * Main View
 * Displays the Humanitarian Assistance Management Interface.
 * Follows the layout specified in the Exam PDF.
 */
require_once 'presenter/AssistancePresenter.php';

$presenter = new AssistancePresenter();
$presenter->handleRequest();

$incomingData = $presenter->getIncomingList();
$distributedData = $presenter->getDistributedList();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UAS DPBO - Bantuan Kemanusiaan</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h2 { border-bottom: 2px solid black; padding-bottom: 5px; }

        /* Form Styling */
        .form-container {
            border: 1px solid #ccc;
            padding: 20px;
            width: 60%;
            margin-bottom: 40px;
        }
        .form-row { display: flex; gap: 20px; margin-bottom: 10px; }
        .form-group { flex: 1; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        
        /* Buttons */
        .btn-simpan { background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-top: 10px; }
        .btn-ubah { background-color: #FFC107; border: none; padding: 5px 10px; cursor: pointer; }
        .btn-hapus { background-color: #F44336; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        .btn-salur { background-color: #2196F3; color: white; border: none; padding: 5px 10px; cursor: pointer; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f0f0f0; }
        
        /* Utility */
        .input-inline { width: 100%; box-sizing: border-box; }
    </style>
</head>
<body>

    <h2>Bantuan Kemanusiaan</h2>
    <div class="form-container">
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="add">
            
            <div class="form-row">
                <div class="form-group">
                    <label>id</label>
                    <input type="text" name="id" required>
                </div>
                <div class="form-group">
                    <label>Daerah Penyaluran</label>
                    <input type="text" name="daerahsalur" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Donatur</label>
                    <input type="text" name="donatur" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Masuk</label>
                    <input type="date" name="tanggalmasuk" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nilai</label>
                    <input type="number" step="0.01" name="nilai" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Isi Bantuan</label>
                    <textarea name="isibantuan" rows="3" required></textarea>
                </div>
            </div>

            <button type="submit" class="btn-simpan">Simpan</button>
        </form>
    </div>

    <h2>Bantuan Masuk</h2>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Donatur</th>
                <th>Tanggal Masuk</th>
                <th>Nilai</th>
                <th>Daerah Penyaluran</th>
                <th>Isi Bantuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $incomingData->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <form action="index.php" method="POST">
                    <td>
                        <?php echo $row['id']; ?>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    </td>
                    <td><?php echo $row['donatur']; ?></td>
                    <td><?php echo $row['tanggalmasuk']; ?></td>
                    
                    <td>
                        <input type="number" step="0.01" name="nilai" value="<?php echo $row['nilai']; ?>" class="input-inline">
                    </td>
                    
                    <td><?php echo $row['daerahsalur']; ?></td>
                    <td><?php echo $row['isibantuan']; ?></td>
                    
                    <td>
                        <select name="status">
                            <option value="masuk" <?php if($row['status'] == 'masuk') echo 'selected'; ?>>masuk</option>
                            <option value="verifikasi" <?php if($row['status'] == 'verifikasi') echo 'selected'; ?>>verifikasi</option>
                        </select>
                    </td>
                    
                    <td>
                        <button type="submit" name="action" value="update_incoming" class="btn-ubah">Ubah</button>
                        <button type="submit" name="action" value="delete" class="btn-hapus">Hapus</button>
                        <button type="submit" name="action" value="distribute" class="btn-salur">Salur</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Bantuan Diproses Penyaluran</h2>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama</th> <th>Tanggal Masuk</th>
                <th>Nilai</th>
                <th>Daerah Penyaluran</th>
                <th>Deskripsi</th> <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $distributedData->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <form action="index.php" method="POST">
                    <td>
                        <?php echo $row['id']; ?>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    </td>
                    <td><?php echo $row['donatur']; ?></td>
                    <td><?php echo $row['tanggalmasuk']; ?></td>
                    <td><?php echo $row['nilai']; ?></td>
                    <td><?php echo $row['daerahsalur']; ?></td>
                    <td><?php echo $row['isibantuan']; ?></td>
                    
                    <td>
                        <select name="status">
                            <option value="tersalur" <?php if($row['status'] == 'tersalur') echo 'selected'; ?>>tersalur</option>
                            <option value="hilang" <?php if($row['status'] == 'hilang') echo 'selected'; ?>>hilang</option>
                        </select>
                    </td>

                    <td>
                        <button type="submit" name="action" value="update_distributed" class="btn-ubah">Ubah</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>