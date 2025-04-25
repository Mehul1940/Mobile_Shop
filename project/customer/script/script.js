

document.getElementById('search-button').addEventListener('click', function () {
  const query = document.getElementById('search-input').value.trim();
  if (query) {
    // Redirect to a search results page or filter products dynamically
    window.location.href = `search.html?q=${encodeURIComponent(query)}`;
  }
});

/*about page*/
document.getElementById('contact-btn').addEventListener('click', function() {
  alert('Thank you for wanting to get in touch! Please email us at contact@nilkanthmobiles.com.');
});

/* contact page*/
document.getElementById('contact-form').addEventListener('submit', function(event) {
  event.preventDefault();

  // Get the form data
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const message = document.getElementById('message').value;

  // Simulate a successful submission
  if (name && email && message) {
    document.getElementById('response-message').style.display = 'block';
    document.getElementById('response-message').textContent = `Thank you, ${name}! Your message has been sent successfully.`;
    
    // Clear the form after submission
    document.getElementById('contact-form').reset();
  } else {
    document.getElementById('response-message').style.display = 'block';
    document.getElementById('response-message').textContent = 'Please fill out all fields.';
    document.getElementById('response-message').style.color = 'red';
  }
});
/* profile page*/
document.addEventListener("DOMContentLoaded", function () {
  const firstNameInput = document.querySelector("#first-name");
  const lastNameInput = document.querySelector("#last-name");
  const emailInput = document.querySelector("#email");
  const phoneInput = document.querySelector("#phone");
  const passwordInput = document.querySelector("#password");
  const confirmPasswordInput = document.querySelector("#confirm-password");
  const updateButton = document.querySelector(".update-button");

  // Function to validate form fields
  function validateForm() {
    let isValid = true;

    if (firstNameInput.value.trim() === "") {
      alert("First name is required.");
      isValid = false;
    }

    if (lastNameInput.value.trim() === "") {
      alert("Last name is required.");
      isValid = false;
    }

    if (!validateEmail(emailInput.value)) {
      alert("Please enter a valid email address.");
      isValid = false;
    }

    if (!validatePhone(phoneInput.value)) {
      alert("Please enter a valid phone number.");
      isValid = false;
    }

    if (passwordInput.value !== confirmPasswordInput.value) {
      alert("Passwords do not match.");
      isValid = false;
    }

    return isValid;
  }

  // Function to validate email format
  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Function to validate phone number format
  function validatePhone(phone) {
    const phoneRegex = /^\d{10}$/; // Assumes 10-digit phone numbers
    return phoneRegex.test(phone);
  }

  // Event listener for update button
  updateButton.addEventListener("click", function (e) {
    e.preventDefault();

    if (validateForm()) {
      alert("Profile updated successfully!");
      // Add logic to save the updated profile details
    }
  });
});
/*Service page*/
let menu = document.querySelector('#menu-btn');
let navbarLinks = document.querySelector('.header .navbar .links');

menu.onclick = () =>{
   menu.classList.toggle('fa-times');
   navbarLinks.classList.toggle('active');
}

window.onscroll = () =>{
   menu.classList.remove('fa-times');
   navbarLinks.classList.remove('active');

   if(window.scrollY > 60){
      document.querySelector('.header .navbar').classList.add('active');
   }else{
      document.querySelector('.header .navbar').classList.remove('active');
   }
}
// offer
// Mobile Navbar Toggle
const navbarToggle = document.getElementById('navbar-toggle');
const navbarMenu = document.getElementById('navbar');

navbarToggle.addEventListener('click', () => {
  navbarMenu.classList.toggle('active');
});

// Image Hover Effects
const offerItems = document.querySelectorAll('.offer-item');

offerItems.forEach(item => {
  const image = item.querySelector('img');
  image.addEventListener('mouseenter', () => {
    image.style.transform = 'scale(1.1)';
    image.style.transition = 'transform 0.3s ease';
  });

  image.addEventListener('mouseleave', () => {
    image.style.transform = 'scale(1)';
  });
});

// Price Comparison Highlight
const priceElements = document.querySelectorAll('.offer-item .price');

priceElements.forEach(price => {
  const oldPrice = price.querySelector('.old-price');
  if (oldPrice) {
    price.addEventListener('mouseenter', () => {
      oldPrice.style.textDecoration = 'line-through';
      oldPrice.style.color = '#aaa';
    });

    price.addEventListener('mouseleave', () => {
      oldPrice.style.textDecoration = 'none';
      oldPrice.style.color = '#e74c3c';
    });
  }
});

// Scroll to Top Button
const scrollToTopButton = document.createElement('button');
scrollToTopButton.textContent = '↑';
scrollToTopButton.classList.add('scroll-to-top');
document.body.appendChild(scrollToTopButton);

// Display the scroll-to-top button when scrolling down
window.addEventListener('scroll', () => {
  if (window.scrollY > 300) {
    scrollToTopButton.style.display = 'block';
  } else {
    scrollToTopButton.style.display = 'none';
  }
});

// Scroll to top functionality
scrollToTopButton.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  });
});



