// Function to open the modal and populate it with payment data
function openModal(button) {
    // Get data attributes from the button
    var paymentId = button.getAttribute("data-payment-id");
    var totalAmt = button.getAttribute("data-total-amt");
    var orderId = button.getAttribute("data-order-id");
    var bookingId = button.getAttribute("data-booking-id");
    var status = button.getAttribute("data-status");

    // Set the values to the form fields inside the modal
    document.getElementById("payment_id").value = paymentId;
    document.getElementById("total_amt").value = totalAmt;
    document.getElementById("order_id").value = orderId;
    document.getElementById("booking_id").value = bookingId;
    document.getElementById("status").value = status;

    // Display the modal
    document.getElementById("updatePaymentModal").style.display = "block";
}

// Function to close the modal
function closeModal() {
    document.getElementById("updatePaymentModal").style.display = "none";
}

// Close modal when clicking the close button
document.getElementById("closeModal").addEventListener("click", closeModal);

// Close modal if cancel button is clicked
document.getElementById("cancelModal").addEventListener("click", closeModal);

// Close modal if clicked outside the modal
window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("updatePaymentModal")) {
        closeModal();
    }
});
