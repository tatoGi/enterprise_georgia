@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 class="mb-2">Create New Post</h2>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="e.g., Technology, Travel, Food" required>
        </div>

        <div class="form-group">
            <label for="photo">Photo (optional)</label>
            <input type="file" id="photo" name="photo" accept="image/*">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn">Submit for Approval</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
