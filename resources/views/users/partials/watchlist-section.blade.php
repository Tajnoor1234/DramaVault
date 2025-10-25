@if($items->count() > 0)
<div class="row g-3">
    @foreach($items as $watchlist)
    @php $drama = $watchlist->drama; @endphp
    <div class="col-lg-4 col-md-6">
        <div class="watchlist-card card h-100 border-0 shadow-sm position-relative">
            <div class="row g-0 h-100">
                <div class="col-4">
                    <a href="{{ route('dramas.show', $drama->slug) }}" class="d-block h-100">
                        <img src="{{ $drama->poster_url }}" 
                             class="img-fluid rounded-start h-100" 
                             alt="{{ $drama->title }}"
                             style="object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-poster.png') }}';">
                    </a>
                </div>
                <div class="col-8">
                    <div class="card-body d-flex flex-column h-100">
                        <h6 class="card-title fw-bold">
                            <a href="{{ route('dramas.show', $drama->slug) }}" class="text-dark text-decoration-none">
                                {{ Str::limit($drama->title, 30) }}
                            </a>
                        </h6>
                        
                        <div class="drama-meta small text-muted mb-2">
                            <div><i class="fas fa-calendar me-1"></i>{{ $drama->release_year }}</div>
                            <div><i class="fas fa-play-circle me-1"></i>{{ $drama->episodes }} eps</div>
                        </div>
                        
                        <div class="rating mb-2">
                            <span class="text-warning small">
                                <i class="fas fa-star me-1"></i>{{ number_format($drama->avg_rating, 1) }}
                            </span>
                            <span class="text-muted small">({{ $drama->total_ratings }})</span>
                        </div>
                        
                        <div class="genres mb-2">
                            @foreach($drama->genres->take(2) as $genre)
                            <span class="badge bg-primary bg-opacity-10 text-primary small me-1 mb-1">
                                {{ $genre->name }}
                            </span>
                            @endforeach
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Added {{ $watchlist->created_at->diffForHumans() }}
                                </small>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary border-0" 
                                            data-bs-toggle="dropdown" type="button">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <form action="{{ route('watchlist.store', $drama) }}" method="POST" class="watchlist-form">
                                                @csrf
                                                <input type="hidden" name="status" value="watching">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-play me-2"></i>Watching
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('watchlist.store', $drama) }}" method="POST" class="watchlist-form">
                                                @csrf
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-check me-2"></i>Completed
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('watchlist.store', $drama) }}" method="POST" class="watchlist-form">
                                                @csrf
                                                <input type="hidden" name="status" value="on_hold">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-pause me-2"></i>On Hold
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('watchlist.destroy', $watchlist) }}" method="POST" class="watchlist-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i>Remove
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-bookmark fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">{{ $emptyMessage }}</h5>
    <a href="{{ route('dramas.index') }}" class="btn btn-primary mt-3">
        <i class="fas fa-search me-1"></i>Explore Dramas/Movies
    </a>
</div>
@endif