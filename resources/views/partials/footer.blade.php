<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4" data-aos="fade-up">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-film me-2"></i>DramaVault
                </h5>
                <p class="text-muted">
                    Your ultimate destination for exploring, rating, and discussing dramas and movies from around the world.
                </p>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-github fa-lg"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <h6 class="fw-bold mb-3">Explore</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('dramas.index') }}" class="text-muted text-decoration-none">Dramas</a></li>
                    <li><a href="{{ route('casts.index') }}" class="text-muted text-decoration-none">Cast</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-muted text-decoration-none">News</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Top Rated</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <h6 class="fw-bold mb-3">Community</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-muted text-decoration-none">Forums</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Reviews</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Discussions</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Events</a></li>
                </ul>
            </div>
            
            <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                <h6 class="fw-bold mb-3">Newsletter</h6>
                <p class="text-muted mb-3">Subscribe to get updates on new dramas and features.</p>
                <form class="d-flex">
                    <input type="email" class="form-control me-2" placeholder="Your email">
                    <button class="btn btn-primary" type="submit">Subscribe</button>
                </form>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-muted mb-0">&copy; 2024 DramaVault. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none me-3">Terms of Service</a>
                <a href="#" class="text-muted text-decoration-none">Contact</a>
            </div>
        </div>
    </div>
</footer>