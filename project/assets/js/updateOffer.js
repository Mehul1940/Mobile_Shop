// Function to open the modal and populate the form fields
function openUpdateModal(offer) {
    // Set form values
    document.getElementById("OFFER_ID").value = offer.OFFER_ID;
    document.getElementById("OFFER_NAME").value = offer.OFFER_NAME;
    document.getElementById("PRODUCT_ID").value = offer.PRODUCT_ID;
    document.getElementById("START_DATE").value = offer.START_DATE;
    document.getElementById("END_DATE").value = offer.END_DATE;
    document.getElementById("DISCOUNT_RATE").value = offer.DISCOUNT_RATE;
    document.getElementById("DESCRIPTION").value = offer.DESCRIPTION;

    // Show the current image preview if available
    if (offer.OFFER_IMG) {
        document.getElementById("OFFER_IMG_PREVIEW").style.display = 'block';
        document.getElementById("OFFER_IMG_PREVIEW").src = offer.OFFER_IMG;
    } else {
        document.getElementById("OFFER_IMG_PREVIEW").style.display = 'none';
    }

    // Show the modal
    document.getElementById("updateModal").style.display = 'block';
}

// Function to close the modal
function closeModal() {
    document.getElementById("updateModal").style.display = 'none';
}

// Function to handle the update button click
function handleUpdateButtonClick(OFFER_ID, OFFER_NAME, PRODUCT_ID, START_DATE, END_DATE, DISCOUNT_RATE, DESCRIPTION, OFFER_IMG) {
    openUpdateModal({
        OFFER_ID,
        OFFER_NAME,
        PRODUCT_ID,
        START_DATE,
        END_DATE,
        DISCOUNT_RATE,
        DESCRIPTION,
        OFFER_IMG
    });
}

// Close the modal if clicked outside the modal content
window.onclick = function(event) {
    if (event.target == document.getElementById("updateModal")) {
        closeModal();
    }
}
