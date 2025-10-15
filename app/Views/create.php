<?= $this->include('template/header') ?>

<h2>Tambah Item Baru</h2>

<div class="card">
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label for="code" class="form-label">Kode Item</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nama Item</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stock" name="stock" value="0">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('/items') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->include('template/footer') ?>