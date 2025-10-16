@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Admin Dashboard</h2>
        <p class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }}. This area is for administrators only.</p>

        <div class="mt-6">
            <a href="{{ route('categories.index') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded">Manage Categories</a>
        </div>
    </div>
</div>
@endsection

