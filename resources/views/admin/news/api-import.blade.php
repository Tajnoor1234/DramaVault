@extends('layouts.app')

@section('title', 'Import News from NewsData.io')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Import News from NewsData.io</h3>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to News
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(!config('services.newsdata.api_key'))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Warning:</strong> NewsData API key is not configured. Please add <code>NEWSDATA_API_KEY</code> to your .env file.
                            <br>
                            Get your free API key from <a href="https://newsdata.io/register" target="_blank">newsdata.io/register</a>
                        </div>
                    @else
                        <form id="importForm" action="{{ route('admin.news.import-api') }}" method="POST">
                            @csrf
                            
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle"></i> 
                                <strong>NewsData.io Free Tier:</strong> Provides up to 200 requests per day with access to latest news from entertainment, drama, movies, and more!
                            </div>
                            
                            <!-- Drama/Movie News Section -->
                            <div class="mb-5">
                                <h4 class="mb-3">
                                    <i class="fas fa-tv"></i> Drama & Movie News
                                    @if($dramaNews && isset($dramaNews['totalResults']))
                                        <span class="badge bg-info">{{ $dramaNews['totalResults'] }} results</span>
                                    @endif
                                </h4>
                                
                                @if($dramaNews && isset($dramaNews['results']) && count($dramaNews['results']) > 0)
                                    <div class="row">
                                        @foreach($dramaNews['results'] as $index => $article)
                                            <div class="col-md-6 col-lg-4 mb-4">
                                                <div class="card h-100 article-card">
                                                    @if(!empty($article['image_url']))
                                                        <img src="{{ $article['image_url'] }}" class="card-img-top" alt="Article image" style="height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                                            <i class="fas fa-image fa-3x text-white-50"></i>
                                                        </div>
                                                    @endif
                                                    <div class="card-body">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input article-checkbox" type="checkbox" 
                                                                   name="selected_drama[]" value="{{ $index }}" 
                                                                   id="drama_{{ $index }}">
                                                            <label class="form-check-label fw-bold" for="drama_{{ $index }}">
                                                                Select to Import
                                                            </label>
                                                        </div>
                                                        <h5 class="card-title">{{ $article['title'] }}</h5>
                                                        <p class="card-text">
                                                            <small class="text-muted">
                                                                <i class="fas fa-newspaper"></i> {{ $article['source_id'] ?? 'Unknown' }}
                                                                <br>
                                                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($article['pubDate'])->format('M d, Y') }}
                                                            </small>
                                                        </p>
                                                        <p class="card-text">{{ Str::limit($article['description'] ?? '', 100) }}</p>
                                                        <a href="{{ $article['link'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-external-link-alt"></i> View Original
                                                        </a>
                                                    </div>
                                                    <!-- Hidden inputs for article data -->
                                                    <input type="hidden" name="articles[{{ $index }}][title]" value="{{ $article['title'] }}">
                                                    <input type="hidden" name="articles[{{ $index }}][description]" value="{{ $article['description'] ?? '' }}">
                                                    <input type="hidden" name="articles[{{ $index }}][content]" value="{{ $article['content'] ?? '' }}">
                                                    <input type="hidden" name="articles[{{ $index }}][link]" value="{{ $article['link'] }}">
                                                    <input type="hidden" name="articles[{{ $index }}][image_url]" value="{{ $article['image_url'] ?? '' }}">
                                                    <input type="hidden" name="articles[{{ $index }}][pubDate]" value="{{ $article['pubDate'] }}">
                                                    <input type="hidden" name="articles[{{ $index }}][source_id]" value="{{ $article['source_id'] ?? 'Unknown' }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> No drama/movie news available at the moment.
                                    </div>
                                @endif
                            </div>

                            <!-- Entertainment News Section -->
                            <div class="mb-4">
                                <h4 class="mb-3">
                                    <i class="fas fa-film"></i> General Entertainment News
                                    @if($entertainmentNews && isset($entertainmentNews['totalResults']))
                                        <span class="badge bg-info">{{ $entertainmentNews['totalResults'] }} results</span>
                                    @endif
                                </h4>
                                
                                @if($entertainmentNews && isset($entertainmentNews['results']) && count($entertainmentNews['results']) > 0)
                                    <div class="row">
                                        @foreach($entertainmentNews['results'] as $index => $article)
                                            @php $entIndex = 'ent_' . $index; @endphp
                                            <div class="col-md-6 col-lg-4 mb-4">
                                                <div class="card h-100 article-card">
                                                    @if(!empty($article['image_url']))
                                                        <img src="{{ $article['image_url'] }}" class="card-img-top" alt="Article image" style="height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                                            <i class="fas fa-image fa-3x text-white-50"></i>
                                                        </div>
                                                    @endif
                                                    <div class="card-body">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input article-checkbox" type="checkbox" 
                                                                   name="selected_ent[]" value="{{ $entIndex }}" 
                                                                   id="{{ $entIndex }}">
                                                            <label class="form-check-label fw-bold" for="{{ $entIndex }}">
                                                                Select to Import
                                                            </label>
                                                        </div>
                                                        <h5 class="card-title">{{ $article['title'] }}</h5>
                                                        <p class="card-text">
                                                            <small class="text-muted">
                                                                <i class="fas fa-newspaper"></i> {{ $article['source_id'] ?? 'Unknown' }}
                                                                <br>
                                                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($article['pubDate'])->format('M d, Y') }}
                                                            </small>
                                                        </p>
                                                        <p class="card-text">{{ Str::limit($article['description'] ?? '', 100) }}</p>
                                                        <a href="{{ $article['link'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-external-link-alt"></i> View Original
                                                        </a>
                                                    </div>
                                                    <!-- Hidden inputs for article data -->
                                                    <input type="hidden" name="articles[{{ $entIndex }}][title]" value="{{ $article['title'] }}">
                                                    <input type="hidden" name="articles[{{ $entIndex }}][description]" value="{{ $article['description'] ?? '' }}">
                                                    <input type="hidden" name="articles[{{ $entIndex }}][content]" value="{{ $article['content'] ?? '' }}">
                                                    <input type="hidden" name="articles[{{ $entIndex }}][link]" value="{{ $article['link'] }}">
                                                    <input type="hidden" name="articles[{{ $entIndex }}][image_url]" value="{{ $article['image_url'] ?? '' }}">
                                                    <input type="hidden" name="articles[{{ $entIndex }}][pubDate]" value="{{ $article['pubDate'] }}">
                                                    <input type="hidden" name="articles[{{ $entIndex }}][source_id]" value="{{ $article['source_id'] ?? 'Unknown' }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> No entertainment news available at the moment.
                                    </div>
                                @endif
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" id="selectAll">
                                    <i class="fas fa-check-double"></i> Select All
                                </button>
                                <button type="button" class="btn btn-secondary" id="deselectAll">
                                    <i class="fas fa-times"></i> Deselect All
                                </button>
                                <button type="submit" class="btn btn-primary" id="importBtn">
                                    <i class="fas fa-download"></i> Import Selected Articles
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.article-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.article-checkbox:checked ~ label {
    color: #0d6efd;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');
    const importBtn = document.getElementById('importBtn');
    const importForm = document.getElementById('importForm');
    const checkboxes = document.querySelectorAll('.article-checkbox');

    // Select all articles
    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = true);
        updateImportButton();
    });

    // Deselect all articles
    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        updateImportButton();
    });

    // Update button state when checkboxes change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateImportButton);
    });

    // Update import button text and state
    function updateImportButton() {
        const selectedCount = document.querySelectorAll('.article-checkbox:checked').length;
        if (selectedCount > 0) {
            importBtn.innerHTML = `<i class="fas fa-download"></i> Import ${selectedCount} Article${selectedCount > 1 ? 's' : ''}`;
            importBtn.disabled = false;
        } else {
            importBtn.innerHTML = '<i class="fas fa-download"></i> Import Selected Articles';
            importBtn.disabled = true;
        }
    }

    // Remove unchecked articles from form submission
    importForm.addEventListener('submit', function(e) {
        checkboxes.forEach(cb => {
            if (!cb.checked) {
                const card = cb.closest('.article-card');
                card.querySelectorAll('input[type="hidden"]').forEach(input => {
                    input.remove();
                });
            }
        });
    });

    // Initial button state
    updateImportButton();
});
</script>
@endsection
