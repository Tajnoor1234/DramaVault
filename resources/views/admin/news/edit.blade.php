@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Edit News Article</h1>
                    <p class="text-muted">Update news article details</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('news.show', $news->slug) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-eye"></i> View Article
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $news->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" name="excerpt" rows="2">{{ old('excerpt', $news->excerpt) }}</textarea>
                            <small class="text-muted">Short summary of the article</small>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="10" required>{{ old('content', $news->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="announcement" {{ old('category', $news->category) == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                    <option value="news" {{ old('category', $news->category) == 'news' ? 'selected' : '' }}>News</option>
                                    <option value="review" {{ old('category', $news->category) == 'review' ? 'selected' : '' }}>Review</option>
                                    <option value="interview" {{ old('category', $news->category) == 'interview' ? 'selected' : '' }}>Interview</option>
                                    <option value="editorial" {{ old('category', $news->category) == 'editorial' ? 'selected' : '' }}>Editorial</option>
                                    <option value="Entertainment" {{ old('category', $news->category) == 'Entertainment' ? 'selected' : '' }}>Entertainment</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="published_at" class="form-label">Publish Date</label>
                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                       id="published_at" name="published_at" 
                                       value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Featured Image</label>
                            @if($news->image_path)
                                <div class="mb-2">
                                    <img src="{{ $news->image_url }}" alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                                    <p class="text-muted small mt-1">Current image</p>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <small class="text-muted">Max 5MB. Leave empty to keep current image.</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_published" 
                                       name="is_published" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Published
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured" 
                                       name="is_featured" value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Feature this article
                                </label>
                            </div>
                        </div>

                        @if($news->source_url)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Source:</strong> 
                            <a href="{{ $news->source_url }}" target="_blank" rel="noopener">
                                {{ $news->source ?? 'Original Article' }}
                            </a>
                        </div>
                        @endif

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Article
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="button" class="btn btn-danger ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Delete Article
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete News Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this news article?</p>
                <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                <p class="text-muted">"{{ $news->title }}"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.news.destroy', $news) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
