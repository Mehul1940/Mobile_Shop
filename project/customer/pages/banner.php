<style>
  .carousel {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    margin: 2rem auto;
    max-width: 1400px;
    position: relative;
  }

  /* Fix for fade animation */
  .carousel-fade .carousel-item {
    opacity: 0;
    transition: opacity 0.6s ease-in-out;
    display: block !important;
  }

  .carousel-fade .carousel-item.active {
    opacity: 1;
  }

  .carousel-inner {
    border-radius: 18px;
    overflow: hidden;
  }

  .carousel-item img {
    filter: brightness(0.98);
    min-height: 500px;
    object-fit: cover;
    width: 100%;
    height: auto;
  }

  /* Responsive image scaling */
  @media (max-width: 992px) {
    .carousel-item img {
      min-height: 400px;
    }
  }

  @media (max-width: 768px) {
    .carousel {
      border-radius: 12px;
      margin: 1rem;
    }

    .carousel-item img {
      min-height: 300px;
    }

    .carousel-control-prev,
    .carousel-control-next {
      width: 40px;
      height: 40px;
      margin: 0 0.5rem;
    }
  }

  @media (max-width: 576px) {
    .carousel-item img {
      min-height: 250px;
    }

  }

  .carousel-control-prev,
  .carousel-control-next {
    width: 60px;
    height: 60px;
    background: #6c5ce7;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    backdrop-filter: blur(4px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0.8;
    margin: 0 1.5rem;
  }

  .carousel-control-prev:hover,
  .carousel-control-next:hover {
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  .carousel-indicators [data-bs-target] {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    background: transparent;
    margin: 0 8px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .carousel-indicators [data-bs-target].active {
    border-color: #6CD4CC;
    background: #6CD4CC;
  }
</style>

<div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="../assets/imgs/b1.png" class="d-block w-100" alt="banner1">
      <div class="carousel-caption">
      </div>
    </div>
    <div class="carousel-item">
      <img src="../assets/imgs/b2.png" class="d-block w-100" alt="banner2">
      <div class="carousel-caption">
      </div>
    </div>
    <div class="carousel-item">
      <img src="../assets/imgs/b3.png" class="d-block w-100" alt="banner2">
      <div class="carousel-caption">
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>