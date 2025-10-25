@extends('layouts.app')

@section('title', 'Import Dramas - Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-cloud-download-alt me-2"></i>
                    Import Dramas
                </h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Quick Import Trending -->
            <div class="card mb-4 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-fire me-2"></i>Quick Import Trending
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Import trending shows from Trakt.tv</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type</label>
                            <select class="form-select" id="trendingType">
                                <option value="shows">TV Shows</option>
                                <option value="movies">Movies</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Limit</label>
                            <select class="form-select" id="trendingLimit">
                                <option value="5">5 items</option>
                                <option value="10" selected>10 items</option>
                                <option value="20">20 items</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="importTrendingBtn">
                                <i class="fas fa-download me-2"></i>Import Trending
                            </button>
                        </div>
                    </div>
                    
                    <!-- Progress -->
                    <div id="trendingProgress" class="mt-3" style="display: none;">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: 0%"
                                 id="trendingProgressBar">
                                Importing...
                            </div>
                        </div>
                        <div id="trendingResults" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <!-- Search and Import -->
            <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>Search & Import
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Search for movies or TV shows and import them</p>
                    
                    <!-- Search Form -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="form-label">Search Query</label>
                            <input type="text" class="form-control" id="searchQuery" placeholder="e.g., Breaking Bad, Avengers...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Source</label>
                            <select class="form-select" id="searchSource">
                                <option value="omdb">OMDb</option>
                                <option value="trakt">Trakt.tv</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="searchBtn">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div id="searchResults">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-search fa-3x mb-3 opacity-25"></i>
                            <p>Search for content to get started</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Importing Drama...</h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Please wait while we import the drama...</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.result-card {
    transition: all 0.3s ease;
    cursor: pointer;
}
.result-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
.result-poster {
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.badge-type {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchBtn').click(function() {
        const query = $('#searchQuery').val();
        const source = $('#searchSource').val();

        if (!query) {
            alert('Please enter a search query');
            return;
        }

        $('#searchResults').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Searching...</p>
            </div>
        `);

        $.ajax({
            url: '{{ route("admin.import.search") }}',
            method: 'GET',
            data: { query, source },
            success: function(response) {
                if (!response.success || response.results.length === 0) {
                    $('#searchResults').html(`
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No results found. Try a different search term.
                        </div>
                    `);
                    return;
                }

                let html = '<div class="row g-3">';
                response.results.forEach(item => {
                    const poster = item.poster || 'https://via.placeholder.com/300x450?text=' + encodeURIComponent(item.title);
                    html += `
                        <div class="col-md-4">
                            <div class="card result-card h-100 shadow-sm" data-imdb="${item.imdb_id}">
                                <div class="position-relative">
                                    <img src="${poster}" class="card-img-top result-poster" alt="${item.title}">
                                    <span class="badge bg-primary badge-type">${item.type}</span>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title mb-1">${item.title}</h6>
                                    <p class="card-text text-muted small mb-2">${item.year}</p>
                                    <button class="btn btn-sm btn-success w-100 import-btn" data-imdb="${item.imdb_id}">
                                        <i class="fas fa-download me-1"></i>Import
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                $('#searchResults').html(html);
            },
            error: function() {
                $('#searchResults').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        An error occurred. Please try again.
                    </div>
                `);
            }
        });
    });

    // Import single drama
    $(document).on('click', '.import-btn', function() {
        const imdbId = $(this).data('imdb');
        const $modal = new bootstrap.Modal(document.getElementById('importModal'));
        $modal.show();

        $.ajax({
            url: '{{ route("admin.import.store") }}',
            method: 'POST',
            data: {
                imdb_id: imdbId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $modal.hide();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: true,
                        confirmButtonText: 'View Drama'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.drama.url;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: response.message
                    });
                }
            },
            error: function() {
                $modal.hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred during import.'
                });
            }
        });
    });

    // Import trending
    $('#importTrendingBtn').click(function() {
        const type = $('#trendingType').val();
        const limit = $('#trendingLimit').val();

        $(this).prop('disabled', true);
        $('#trendingProgress').show();
        $('#trendingProgressBar').css('width', '0%').text('Starting...');

        $.ajax({
            url: '{{ route("admin.import.trending") }}',
            method: 'POST',
            data: {
                type: type,
                limit: limit,
                _token: '{{ csrf_token() }}'
            },
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                return xhr;
            },
            success: function(response) {
                $('#trendingProgressBar').css('width', '100%').text('Complete!');
                
                let html = `
                    <div class="alert alert-success">
                        <h6 class="mb-2"><i class="fas fa-check-circle me-2"></i>Import Complete!</h6>
                        <ul class="mb-0">
                            <li>Imported: ${response.summary.imported_count}</li>
                            <li>Skipped: ${response.summary.skipped_count}</li>
                            <li>Failed: ${response.summary.failed_count}</li>
                        </ul>
                    </div>
                `;

                if (response.imported.length > 0) {
                    html += '<div class="alert alert-info"><strong>Imported:</strong> ' + response.imported.join(', ') + '</div>';
                }

                $('#trendingResults').html(html);
                $('#importTrendingBtn').prop('disabled', false);

                // Reload page after 3 seconds
                setTimeout(() => location.reload(), 3000);
            },
            error: function() {
                $('#trendingProgressBar').css('width', '100%').removeClass('progress-bar-animated').addClass('bg-danger').text('Failed');
                $('#importTrendingBtn').prop('disabled', false);
            }
        });
    });

    // Enter key to search
    $('#searchQuery').keypress(function(e) {
        if (e.which === 13) {
            $('#searchBtn').click();
        }
    });
});
</script>
@endpush
