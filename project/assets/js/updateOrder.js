const updateButtons = document.querySelectorAll('.update-btn');
const modal = document.getElementById('updateOrderModal');
const closeModal = document.getElementById('closeModal');

updateButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Populate form fields with data-* attributes
        document.getElementById('order_id').value = this.getAttribute('data-id');
        document.getElementById('customer_id').value = this.getAttribute('data-customer');
        document.getElementById('total_amt').value = this.getAttribute('data-amount');
        document.getElementById('delivery_address').value = this.getAttribute('data-address');
        document.getElementById('delivery_status').value = this.getAttribute('data-status');

        // Show the modal
        modal.style.display = 'block';
    });
});

// Close modal on close button click
closeModal.addEventListener('click', function() {
    modal.style.display = 'none';
});

// Close modal on cancel button click
document.getElementById('cancelModal').addEventListener('click', function() {
    modal.style.display = 'none';
});
