<style>
    footer {
        background: #f8f9fa;
        color: #495057;
        padding: 3rem 0;
        font-family: 'Poppins', sans-serif;
    }

    footer h5 {
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }

    footer ul {
        list-style: none;
        padding: 0;
    }

    footer ul li {
        margin-bottom: 0.5rem;
    }

    footer ul li a {
        text-decoration: none;
        color: #6c757d;
        transition: color 0.3s ease-in-out, transform 0.2s ease-in-out;
    }

    footer ul li a:hover {
        color: #17a2b8;
        transform: translateX(5px);
    }

    .footer-bottom {
        border-top: 1px solid #dee2e6;
        padding-top: 1.5rem;
        text-align: center;
    }

    .footer-bottom p {
        margin: 0;
        font-size: 0.9rem;
    }

    .social-icons a {
        display: inline-block;
        margin: 0 10px;
        font-size: 1.5rem;
        color: #6c757d;
        transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .social-icons a:hover {
        transform: scale(1.2);
        color: #17a2b8;
    }

    /* Fade-in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    footer {
        animation: fadeIn 1s ease-in-out;
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        footer .row {
            text-align: center;
        }

        .social-icons {
            justify-content: center;
        }
    }
</style>
<div class="container">
    <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-4 py-5">
        <div class="col mb-3">
            <h5><img src="../assets/logo/1.png" alt="logo" height="100px"></h5>
            <p>Providing the best services since 2024.</p>
            <p>© 2024 Company, Inc. All rights reserved.</p>
        </div>

        <div class="col mb-3">
            <h5>Quick Links</h5>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Pricing</a></li>
                <li><a href="#">FAQs</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </div>

        <div class="col mb-3">
            <h5>Support</h5>
            <ul>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
            </ul>
        </div>

        <div class="col mb-3">
            <h5>Follow Us</h5>
            <div class="social-icons">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
            </div>
        </div>
    </footer>
</div>