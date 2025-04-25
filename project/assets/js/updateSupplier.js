const modal = document.getElementById('updateModal');
const supplierIDInput = document.getElementById('modalSupplierID');
const supplierNameInput = document.getElementById('modalSupplierName');
const emailInput = document.getElementById('modalEmail');
const phoneInput = document.getElementById('modalPhone');
const addressInput = document.getElementById('modalAddress');

function openModal(supplier) {
    if (!supplier) {
        console.error("Supplier data is missing!");
        return;
    }

    // Populate modal fields
    supplierIDInput.value = supplier.SUPPLIER_ID;
    supplierNameInput.value = supplier.SUPPLIER_NAME;
    emailInput.value = supplier.EMAIL;
    phoneInput.value = supplier.PHONE;
    addressInput.value = supplier.ADDRESS;

    // Display modal
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
}
