@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md border border-gray-200 font-sans">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Post</h1>

        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title Input -->
            <div>
                <label for="title" class="block text-gray-700 font-semibold mb-2">Title</label>
                <input type="text" id="title" name="title" value="{{ $post->title }}" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Content Textarea -->
            <div>
                <label for="content" class="block text-gray-700 font-semibold mb-2">Content</label>
                <textarea id="content" name="content" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $post->content }}</textarea>
            </div>

            <!-- Category Dropdown -->
            <div>
                <label for="category_id" class="block text-gray-700 font-semibold mb-2">Category</label>
                <select id="category_id" name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Dropdown -->
            <div>
                <label for="status" class="block text-gray-700 font-semibold mb-2">Status</label>
                <select id="status" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ $post->status == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-gray-700 font-semibold mb-2">Image</label>
                <input type="file" id="image" name="image" class="block w-full text-gray-700 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @if($post->image)
                    <div class="mt-4">
                        <p class="text-gray-600 text-sm">Current Image:</p>
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="w-full h-auto mt-2 rounded-md shadow-md">
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition duration-200">
                    Update
                </button>
            </div>
        </form>
    </div>
@endsection
