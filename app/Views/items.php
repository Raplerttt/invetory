<?= $this->include('template/header') ?>

<h2>Master Data Items</h2>

<div class="mb-3">
    <a href="<?= site_url('/items/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Item
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['code'] ?></td>
                        <td><?= $item['name'] ?></td>
                        <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                        <td><?= $item['stock'] ?></td>
                        <td>
                            <a href="<?= site_url('/items/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= site_url('/items/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('template/footer') ?>