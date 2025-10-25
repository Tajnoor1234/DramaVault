@extends('layouts.app')

@section('title', auth()->user()->name . ' - Profile')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4 mb-4">
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-body text-center">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.jpg') }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h4 class="fw-bold">{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    
                    @if(auth()->user()->bio)
                    <p class="card-text">{{ auth()->user()->bio }}</p>
                    @endif
                    
                    <div class="row text-center mt-4">
                        <div class="col-4">
                            <h6 class="fw-bold text-primary">{{ auth()->user()->ratings->count() }}</h6>
                            <small class="text-muted">Ratings</small>
                        </div>
                        <div class="col-4">
                            <h6 class="fw-bold text-success">{{ auth()->user()->comments->count() }}</h6>
                            <small class="text-muted">Reviews</small>
                        </div>
                        <div class="col-4">
                            <h6 class="fw-bold text-warning">{{ auth()->user()->watchlists->count() }}</h6>
                            <small class="text-muted">Watchlist</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Profile Edit Form -->
            <div class="card mb-4 animate__animated animate__fadeInUp">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" 
                                      placeholder="Tell us about yourself...">{{ old('bio', auth()->user()->bio) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            <small class="text-muted">Recommended size: 150x150 pixels</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        @php
                            $activities = collect();
                            
                            // Add ratings as activities
                            foreach(auth()->user()->ratings()->with('drama')->latest()->take(10)->get() as $rating) {
                                $activities->push([
                                    'type' => 'rating',
                                    'created_at' => $rating->created_at,
                                    'data' => $rating
                                ]);
                            }
                            
                            // Add comments as activities
                            foreach(auth()->user()->comments()->with('drama')->latest()->take(10)->get() as $comment) {
                                $activities->push([
                                    'type' => 'comment',
                                    'created_at' => $comment->created_at,
                                    'data' => $comment
                                ]);
                            }
                            
                            // Sort by creation date
                            $activities = $activities->sortByDesc('created_at')->take(10);
                        @endphp
                        
                        @forelse($activities as $activity)
                        <div class="activity-item d-flex align-items-start mb-3">
                            <div class="activity-icon me-3">
                                @if($activity['type'] === 'rating')
                                <i class="fas fa-star text-warning bg-warning bg-opacity-10 p-2 rounded-circle"></i>
                                @else
                                <i class="fas fa-comment text-primary bg-primary bg-opacity-10 p-2 rounded-circle"></i>
                                @endif
                            </div>
                            <div class="activity-content flex-grow-1">
                                @if($activity['type'] === 'rating')
                                <p class="mb-1">
                                    You rated 
                                    <a href="{{ route('dramas.show', $activity['data']->drama->slug) }}" class="fw-bold">
                                        {{ $activity['data']->drama->title }}
                                    </a>
                                    <span class="text-warning fw-bold">{{ $activity['data']->rating }}/10</span>
                                </p>
                                @if($activity['data']->review)
                                <p class="text-muted small mb-0">"{{ Str::limit($activity['data']->review, 100) }}"</p>
                                @endif
                                @else
                                <p class="mb-1">
                                    You commented on 
                                    <a href="{{ route('dramas.show', $activity['data']->drama->slug) }}#comment-{{ $activity['data']->id }}" 
                                       class="fw-bold">
                                        {{ $activity['data']->drama->title }}
                                    </a>
                                </p>
                                <p class="text-muted small mb-0">"{{ Str::limit($activity['data']->body, 100) }}"</p>
                                @endif
                                <small class="text-muted">{{ $activity['created_at']->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No activity yet. Start rating and reviewing dramas!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection