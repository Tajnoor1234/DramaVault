@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Add New Drama/Movie</h1>
                    <p class="text-muted">Create a new drama/movie entry</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.dramas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Synopsis -->
                        <div class="mb-3">
                            <label for="synopsis" class="form-label">Synopsis <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('synopsis') is-invalid @enderror" 
                                      id="synopsis" name="synopsis" rows="5" required>{{ old('synopsis') }}</textarea>
                            @error('synopsis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Type -->
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="drama" {{ old('type') == 'drama' ? 'selected' : '' }}>Drama</option>
                                    <option value="movie" {{ old('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                                    <option value="series" {{ old('type') == 'series' ? 'selected' : '' }}>Series</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Episodes -->
                            <div class="col-md-4 mb-3">
                                <label for="episodes" class="form-label">Episodes <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('episodes') is-invalid @enderror" 
                                       id="episodes" name="episodes" value="{{ old('episodes', 1) }}" min="1" required>
                                @error('episodes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duration -->
                            <div class="col-md-4 mb-3">
                                <label for="duration" class="form-label">Duration (min)</label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" name="duration" value="{{ old('duration') }}" min="1">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Country -->
                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Release Year -->
                            <div class="col-md-4 mb-3">
                                <label for="release_year" class="form-label">Release Year <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('release_year') is-invalid @enderror" 
                                       id="release_year" name="release_year" value="{{ old('release_year', date('Y')) }}" 
                                       min="1900" max="{{ date('Y') + 5 }}" required>
                                @error('release_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Airing Date -->
                        <div class="mb-3">
                            <label for="airing_date" class="form-label">Airing Date</label>
                            <input type="date" class="form-control @error('airing_date') is-invalid @enderror" 
                                   id="airing_date" name="airing_date" value="{{ old('airing_date') }}">
                            @error('airing_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Genres -->
                        <div class="mb-3">
                            <label class="form-label">Genres <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach($genres as $genre)
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="genres[]" 
                                                   value="{{ $genre->id }}" id="genre{{ $genre->id }}"
                                                   {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genre{{ $genre->id }}">
                                                {{ $genre->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('genres')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cast Members -->
                        <div class="mb-3">
                            <label class="form-label">Cast Members <span class="text-danger">*</span></label>
                            <div id="castMembers">
                                <div class="row mb-2 cast-row">
                                    <div class="col-md-5">
                                        <select class="form-select" name="cast_id[]" required>
                                            <option value="">Select Cast</option>
                                            @foreach($casts as $cast)
                                                <option value="{{ $cast->id }}">{{ $cast->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="character_name[]" 
                                               placeholder="Character Name" required>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-select" name="role_type[]" required>
                                            <option value="main">Main</option>
                                            <option value="supporting" selected>Supporting</option>
                                            <option value="guest">Guest</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-cast">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addCast">
                                <i class="fas fa-plus"></i> Add Cast Member
                            </button>
                            @error('cast')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Poster -->
                        <div class="mb-3">
                            <label for="poster" class="form-label">Poster Image</label>
                            <input type="file" class="form-control @error('poster') is-invalid @enderror" 
                                   id="poster" name="poster" accept="image/*">
                            <small class="text-muted">Max 2MB, recommended size: 400x600px</small>
                            @error('poster')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Banner -->
                        <div class="mb-3">
                            <label for="banner" class="form-label">Banner Image</label>
                            <input type="file" class="form-control @error('banner') is-invalid @enderror" 
                                   id="banner" name="banner" accept="image/*">
                            <small class="text-muted">Max 5MB, recommended size: 1920x400px</small>
                            @error('banner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Drama/Movie
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('addCast').addEventListener('click', function() {
    const container = document.getElementById('castMembers');
    const firstRow = container.querySelector('.cast-row');
    const newRow = firstRow.cloneNode(true);
    
    // Clear values
    newRow.querySelectorAll('input, select').forEach(el => {
        if (el.type !== 'button') el.value = '';
    });
    
    container.appendChild(newRow);
});

document.getElementById('castMembers').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-cast') || e.target.closest('.remove-cast')) {
        const rows = document.querySelectorAll('.cast-row');
        if (rows.length > 1) {
            e.target.closest('.cast-row').remove();
        }
    }
});
</script>
@endsection
