@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 class="mb-2">Edit Post</h2>

    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" value="{{ old('category', $post->category) }}" required>
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            @if($post->photo)
                <img src="{{ asset('storage/' . $post->photo) }}" alt="Current photo" style="max-width: 200px; display: block; margin-bottom: 0.5rem;">
            @endif
            <input type="file" id="photo" name="photo" accept="image/*">
            <small>Leave empty to keep current photo</small>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description', $post->description) }}</textarea>
        </div>

        <button type="submit" class="btn">Update Post</button>
        <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
