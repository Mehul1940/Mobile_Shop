document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("updateBookingModal");
    const closeModalBtn = document.getElementById("closeModal");
    const cancelModalBtn = document.getElementById("cancelModal");
    const updateButtons = document.querySelectorAll(".update-btn");

    const openModal = (button) => {
        document.getElementById("booking_id").value = button.dataset.id;
        document.getElementById("estimate_id").value = button.dataset.estimate;
        document.getElementById("booking_date").value = button.dataset.date;
        document.getElementById("address").value = button.dataset.address;
        document.getElementById("status").value = button.dataset.status;
        document.getElementById("pickup_status").value = button.dataset.pickup;
        document.getElementById("delivery_status").value = button.dataset.delivery;
        modal.style.display = "block";
    };

    const closeModal = () => {
        modal.style.display = "none";
    };

    updateButtons.forEach((button) => {
        button.addEventListener("click", () => openModal(button));
    });

    closeModalBtn.addEventListener("click", closeModal);
    cancelModalBtn.addEventListener("click", closeModal);

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
});
