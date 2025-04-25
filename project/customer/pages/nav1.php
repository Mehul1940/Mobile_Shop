<style>
    :root {
        --primary-color: #6c5ce7;
        --secondary-color: #FF7F7F;
        --accent-color: #6CD4CC;
        --text-color: #2D3436;
        --glass-effect: rgba(255, 255, 255, 0.95);
        --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        --shadow-sm: 0 4px 20px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.1);
        --gradient-accent: linear-gradient(90deg, var(--secondary-color) 0%);
    }

    body {
        background: linear-gradient(135deg, #fdfdfd 0%, #f7f9fc 100%);
        min-height: 100vh;
    }

    /* Enhanced Navbar with Refined Glass Effect */
    .navbar {
        background: var(--glass-effect) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        box-shadow: var(--shadow-sm);
        border-bottom: 1px solid rgba(255, 255, 255, 0.8);
        transition: var(--transition-smooth);
        padding: 0.8rem 1rem;
        font-size: large;
    }

    .navbar.scrolled {
        padding: 0.5rem 1rem;
        box-shadow: var(--shadow-md);
    }

    /* Brand Animation */
    .navbar-brand {
        position: relative;
        overflow: hidden;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 0.5rem 0;
        transition: var(--transition-smooth);
    }

    .navbar-brand::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--gradient-accent);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1);
    }

    .navbar-brand:hover {
        letter-spacing: 1px;
    }

    .navbar-brand:hover::before {
        transform: scaleX(1);
        transform-origin: left;
    }

    /* Improved Search Container */
    .search-container {
        position: relative;
        transition: var(--transition-smooth);
    }

    .search-input {
        padding: 0.7rem 2.2rem 0.7rem 1.3rem;
        border-radius: 50px;
        border: 2px solid #e9ecef;
        background: rgba(255, 255, 255, 0.85);
        transition: var(--transition-smooth);
        width: 180px;
        font-size: 0.95rem;
    }

    .search-input:focus {
        width: 240px;
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 5px 20px rgba(108, 212, 204, 0.25);
        background: rgba(255, 255, 255, 1);
    }

    .search-button {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #adb5bd;
        transition: var(--transition-smooth);
        padding: 5px;
        cursor: pointer;
    }

    .search-input:focus+.search-button,
    .search-button:hover {
        color: var(--secondary-color);
    }

    .search-button:hover .fa-magnifying-glass {
        animation: searchSpin 0.8s cubic-bezier(0.68, -0.6, 0.32, 1.6);
    }

    @keyframes searchSpin {
        0% {
            transform: rotate(0deg) scale(1);
        }

        50% {
            transform: rotate(180deg) scale(1.2);
        }

        100% {
            transform: rotate(360deg) scale(1);
        }
    }

    /* Cart Icon Animation */
    .fa-cart-plus {
        position: relative;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .fa-cart-plus:hover {
        transform: scale(1.2) translateY(-3px);
        color: var(--secondary-color);
    }

    /* Enhanced Cart Badge */
    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background: var(--secondary-color);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 8px rgba(255, 127, 127, 0.4);
        transform-origin: center;
        animation: badgePop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes badgePop {
        0% {
            transform: scale(0) translateY(10px);
            opacity: 0;
        }

        70% {
            transform: scale(1.3);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Interactive Link Animations */
    .nav-link {
        position: relative;
        padding: 0.6rem 1rem !important;
        margin: 0 0.25rem;
        transition: var(--transition-smooth);
        font-weight: 500;
        color: var(-dark-color);
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 10%;
        width: 80%;
        height: 2px;
        background: var(--gradient-accent);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        opacity: 0;
    }

    .nav-link:hover {
        color: var(--secondary-color) !important;
        transform: translateY(-2px);
    }

    .nav-link:hover::after {
        transform: scaleX(1);
        transform-origin: left;
        opacity: 1;
    }

    .nav-link.active::after {
        transform: scaleX(1);
        opacity: 1;
    }

    /* Enhanced Button Styling */
    .btn-outline-danger {
        padding: 0.5rem 1.4rem;
        border-radius: 50px;
        position: relative;
        overflow: hidden;
        border-width: 2px;
        font-weight: 500;
        transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
    }

    .btn-outline-danger::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--gradient-accent);
        opacity: 0;
        z-index: -1;
        transition: var(--transition-smooth);
    }

    .btn-outline-danger:hover {
        color: white !important;
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(255, 127, 127, 0.3);
        transform: translateY(-3px);
    }

    .btn-outline-danger:hover::before {
        opacity: 1;
    }

    .btn-outline-danger:active {
        transform: translateY(0);
        box-shadow: 0 4px 10px rgba(255, 127, 127, 0.2);
    }

    /* Enhanced Dropdown Menu */
    .dropdown-menu {
        border: none;
        background: var(--glass-effect);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        box-shadow: var(--shadow-md);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 0.8rem 0;
        animation: dropdownFade 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: top center;
    }

    @keyframes dropdownFade {
        0% {
            opacity: 0;
            transform: translateY(10px) scale(0.95);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .dropdown-item {
        padding: 0.7rem 1.5rem;
        position: relative;
        transition: var(--transition-smooth);
    }

    .dropdown-item::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 50%;
        height: 5px;
        width: 5px;
        border-radius: 50%;
        background: var(--secondary-color);
        transform: translateY(-50%) scale(0);
        transition: transform 0.3s ease;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.7);
        padding-left: 1.8rem;
    }

    .dropdown-item:hover::before {
        transform: translateY(-50%) scale(1);
    }

    /* User Account Icons */
    .text-decoration-none {
        display: flex;
        align-items: center;
        padding: 0.5rem 0.8rem;
        border-radius: 50px;
        font-weight: 500;
        transition: var(--transition-smooth);
    }

    .text-decoration-none:hover {
        background: rgba(255, 255, 255, 0.7);
        transform: translateY(-2px);
        color: var(--secondary-color) !important;
    }

    .text-decoration-none i {
        margin-right: 0.5rem;
        transition: var(--transition-smooth);
    }

    .text-decoration-none:hover i {
        transform: scale(1.2);
    }

    /* Toggler Animation */
    .navbar-toggler {
        border: none;
        padding: 0.5rem;
        transition: var(--transition-smooth);
    }

    .navbar-toggler:focus {
        box-shadow: none;
        outline: none;
    }

    .navbar-toggler:hover {
        transform: rotate(90deg);
    }

    .navbar-toggler-icon {
        transition: var(--transition-smooth);
    }

    /* Custom Animation for Content Load */
    @keyframes contentLoad {
        0% {
            opacity: 0;
            transform: translateY(30px);
            filter: blur(5px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }
    }

    main {
        animation: contentLoad 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Enhanced Responsive Design */
    @media (max-width: 992px) {
        .navbar-collapse {
            background: var(--glass-effect);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 1.5rem;
            border-radius: 16px;
            margin-top: 1rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: mobileMenuFade 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes mobileMenuFade {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-input {
            width: 100%;
        }

        .search-input:focus {
            width: 100%;
        }

        .search-container {
            width: 100%;
            margin-bottom: 1rem;
        }

        .d-flex.align-items-center.gap-3 {
            flex-direction: column;
            align-items: flex-start !important;
            width: 100%;
        }

        .nav-link {
            padding: 0.8rem 1rem !important;
        }

        .navbar-nav {
            margin-bottom: 1rem !important;
        }

        .text-decoration-none {
            margin: 0.3rem 0;
        }
    }
</style>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index1.php">NILKANTH MOBILES</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="./index1.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Categories
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Mobile</a></li>
                        <li><a class="dropdown-item" href="#">EarBuds</a></li>
                        <li><a class="dropdown-item" href="#">HeadPhones</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Update the search bar section in your navbar -->
            <div class="d-flex align-items-center gap-3">
                <div class="search-container position-relative">
                    <form class="d-flex" role="search">
                        <input class="search-input" type="search" placeholder="Search products...">
                        <button class="search-button" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>

                <a href="#" class="position-relative text-dark">
                    <i class="fa-solid fa-cart-plus fs-5"></i>
                    <span class="cart-badge">3</span>
                </a>

                <a href="#" class="text-decoration-none text-dark">
                    <i class="fa-solid fa-cash-register me-1"></i>Register
                </a>
                <a href="#" class="text-decoration-none text-dark">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i>Login
                </a>
            </div>
        </div>
</nav>
<script>
    /* Add scroll detection functionality with JavaScript */
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.querySelector('.navbar');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    });
</script>