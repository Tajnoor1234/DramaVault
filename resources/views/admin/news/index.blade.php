@extends('layouts.app')

@section('title', 'News Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">News Management</h3>
                    <div class="btn-group">
                        <a href="{{ route('admin.news.fetch-api') }}" class="btn btn-success">
                            <i class="fas fa-cloud-download-alt"></i> Import from NewsAPI
                        </a>
                        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create News
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($news->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Source</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Published</th>
                                        <th>Views</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($news as $item)
                                        <tr>
                                            <td>
                                                @if($item->image_path)
                                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="fas fa-newspaper text-white"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($item->title, 50) }}</strong>
                                                @if($item->is_featured)
                                                    <span class="badge bg-warning text-dark ms-1">Featured</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ Str::limit($item->excerpt, 80) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $item->category }}</span>
                                            </td>
                                            <td>
                                                @if($item->source)
                                                    <small>
                                                        {{ $item->source }}
                                                        @if($item->source_url)
                                                            <a href="{{ $item->source_url }}" target="_blank" class="text-muted">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->author->name ?? 'Unknown' }}</td>
                                            <td>
                                                @if($item->is_published)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $item->published_at ? $item->published_at->format('M d, Y') : '-' }}</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <i class="fas fa-eye"></i> {{ number_format($item->views_count) }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('news.show', $item->slug) }}" class="btn btn-sm btn-info" target="_blank" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this news article?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $news->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No news articles found. 
                            <a href="{{ route('admin.news.create') }}">Create your first article</a> or 
                            <a href="{{ route('admin.news.fetch-api') }}">import from NewsAPI</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
