<style>
    /* Updated Card Container */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        justify-content: center;
    }

    /* Card Styling */
    .card {
        background: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        width: 100%;
    }

    /* Rest of the CSS remains the same */
    .card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    .icon {
        font-size: 2.8rem;
        color: #4a90e2;
        margin-bottom: 1.5rem;
        transition: all 0.4s ease;
        display: inline-block;
    }

    .card:hover .icon {
        transform: translateY(-8px) rotate(-5deg);
        filter: drop-shadow(0 4px 6px rgba(74, 144, 226, 0.2));
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #2d3436;
    }

    .card-text {
        font-size: 1rem;
        color: #636e72;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .card-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #4a90e2;
        text-decoration: none;
        font-weight: 500;
        position: relative;
        transition: all 0.3s ease;
    }

    .card-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #4a90e2;
        transition: width 0.3s ease;
    }

    .card-link:hover {
        color: #6c5ce7;
    }

    .card-link:hover::after {
        width: 100%;
    }

    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .card {
            padding: 1.5rem;
        }

        .icon {
            font-size: 2.2rem;
        }

        .card-title {
            font-size: 1.3rem;
        }
    }
</style>

<div class="card-container">
    <div class="card">
        <i class="fas fa-mobile-alt icon"></i>
        <h5 class="card-title">Smartphones</h5>
        <p class="card-text">Explore the latest smartphones with cutting-edge technology and premium features.</p>
        <a href="#" class="card-link">
            View More
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="card">
        <i class="fas fa-headphones icon"></i>
        <h5 class="card-title">Accessories</h5>
        <p class="card-text">Premium audio accessories for immersive sound experiences.</p>
        <a href="#" class="card-link">
            View More
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="card">
        <i class="fas fa-charging-station icon"></i>
        <h5 class="card-title">Chargers</h5>
        <p class="card-text">Fast charging solutions for all your devices.</p>
        <a href="#" class="card-link">
            View More
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="card">
        <i class="fas fa-sim-card icon"></i>
        <h5 class="card-title">SIM & Storage</h5>
        <p class="card-text">Expand your device's capabilities with our storage solutions.</p>
        <a href="#" class="card-link">
            View More
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>