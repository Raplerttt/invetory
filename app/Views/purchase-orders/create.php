<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Create Purchase Order<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create Purchase Order</h1>
    <a href="<?= base_url('/purchase-orders') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to POs
    </a>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Purchase Order Information</h6>
            </div>
            <div class="card-body">
                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $validation->listErrors() ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/purchase-orders/store') ?>" method="post" id="poForm">
                    <?= csrf_field() ?>
                    
                    <!-- PO Header -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">PO Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="po_number" value="<?= $po_number ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select class="form-control <?= (isset($validation) && $validation->hasError('supplier_id')) ? 'is-invalid' : '' ?>" 
                                    name="supplier_id" id="supplier_id" required>
                                <option value="">Select Supplier</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id'] ?>" <?= old('supplier_id') == $supplier['id'] ? 'selected' : '' ?>>
                                        <?= $supplier['name'] ?> (<?= $supplier['code'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('supplier_id')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('supplier_id') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Order Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?= (isset($validation) && $validation->hasError('order_date')) ? 'is-invalid' : '' ?>" 
                                   name="order_date" value="<?= old('order_date', date('Y-m-d')) ?>" required>
                            <?php if (isset($validation) && $validation->hasError('order_date')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('order_date') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" name="delivery_date" value="<?= old('delivery_date') ?>">
                        </div>
                    </div>

                    <!-- PO Items -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="text-primary">PO Items</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40%">Item</th>
                                            <th width="15%">Quantity</th>
                                            <th width="20%">Unit Price</th>
                                            <th width="20%">Total</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                        <!-- Items will be added dynamically -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                                            <td>
                                                <strong id="grandTotal">Rp 0</strong>
                                                <input type="hidden" name="total_amount" id="totalAmount" value="0">
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addItemRow()">
                                <i class="fas fa-plus me-1"></i> Add Item
                            </button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes..."><?= old('notes') ?></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Create Purchase Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let itemCounter = 0;
    const items = <?= json_encode($items) ?>;

    // Add new item row
    function addItemRow(itemData = null) {
        const tbody = document.getElementById('itemsBody');
        const row = document.createElement('tr');
        row.id = `itemRow-${itemCounter}`;
        
        const itemSelect = createItemSelect(itemData?.item_id, itemCounter);
        const quantity = itemData?.quantity || 1;
        const unitPrice = itemData?.unit_price || 0;
        const total = quantity * unitPrice;

        row.innerHTML = `
            <td>
                ${itemSelect}
            </td>
            <td>
                <input type="number" class="form-control quantity" name="items[${itemCounter}][quantity]" 
                       value="${quantity}" min="1" required onchange="calculateRowTotal(${itemCounter})">
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control unit-price" name="items[${itemCounter}][unit_price]" 
                           value="${unitPrice}" step="0.01" min="0" required onchange="calculateRowTotal(${itemCounter})">
                </div>
            </td>
            <td>
                <strong class="row-total">Rp ${total.toLocaleString('id-ID')}</strong>
                <input type="hidden" class="row-total-value" value="${total}">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItemRow(${itemCounter})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);

        // Set initial value jika ada
        const select = row.querySelector('.item-select');
        if (itemData?.item_id) {
            select.value = itemData.item_id;
        }

        itemCounter++;
        calculateGrandTotal();
    }

    // Create item select dropdown
    function createItemSelect(selectedId = null, rowIndex = 0) {
        let options = '<option value="">Select Item</option>';
        items.forEach(item => {
            const selected = selectedId == item.id ? 'selected' : '';
            options += `<option value="${item.id}" ${selected}>${item.name} (${item.code}) - Stock: ${item.stock}</option>`;
        });

        return `<select class="form-control item-select" name="items[${rowIndex}][item_id]" required>
                    ${options}
                </select>`;
    }

    // Remove item row
    function removeItemRow(rowId) {
        const row = document.getElementById(`itemRow-${rowId}`);
        if (row) {
            row.remove();
            calculateGrandTotal();
        }
    }

    // Calculate row total
    function calculateRowTotal(rowId) {
        const row = document.getElementById(`itemRow-${rowId}`);
        const quantity = row.querySelector('.quantity').value;
        const unitPrice = row.querySelector('.unit-price').value;
        const total = quantity * unitPrice;

        row.querySelector('.row-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        row.querySelector('.row-total-value').value = total;
        
        calculateGrandTotal();
    }

    // Calculate grand total
    function calculateGrandTotal() {
        let grandTotal = 0;
        const rowTotals = document.querySelectorAll('.row-total-value');
        
        rowTotals.forEach(input => {
            grandTotal += parseFloat(input.value) || 0;
        });

        document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        document.getElementById('totalAmount').value = grandTotal;
    }

    // Form validation before submit
    document.getElementById('poForm').addEventListener('submit', function(e) {
        const itemRows = document.querySelectorAll('#itemsBody tr');
        
        if (itemRows.length === 0) {
            e.preventDefault();
            alert('Please add at least one item to the purchase order.');
            return false;
        }

        let validItems = 0;
        itemRows.forEach((row, index) => {
            const itemId = row.querySelector('.item-select').value;
            const quantity = row.querySelector('.quantity').value;
            const unitPrice = row.querySelector('.unit-price').value;

            if (itemId && quantity > 0 && unitPrice > 0) {
                validItems++;
            }
        });

        if (validItems === 0) {
            e.preventDefault();
            alert('Please add valid items to the purchase order. Make sure to select item, quantity, and unit price.');
            return false;
        }

        return true;
    });

    // Add first empty row on page load
    document.addEventListener('DOMContentLoaded', function() {
        addItemRow();
    });
</script>
<?= $this->endSection() ?>