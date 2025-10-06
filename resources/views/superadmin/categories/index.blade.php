@extends('layouts.superadmin')

@section('title', 'Categories - SuperAdmin')

@section('page-icon')
<i class="fas fa-tags"></i>
@endsection
@section('page-title', 'Categories')
@section('page-subtitle', 'Manage product and service categories')

@section('header-actions')
<a href="{{ route('superadmin.categories.create') }}" class="action-btn">
    <i class="fas fa-plus"></i>Add Category
</a>
@endsection

@section('content')
<div class="content-card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-tags"></i>All Categories
        </h5>
    </div>
    <div class="card-body p-0">
        @if($categories->count() > 0)
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Color</th>
                            <th>Products</th>
                            <th>Services</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $category->name }}</div>
                            </td>
                            <td>
                                <code>{{ $category->slug }}</code>
                            </td>
                            <td>
                                @if($category->color)
                                    <span class="badge" style="background-color: {{ $category->color }}; color: white;">
                                        {{ $category->color }}
                                    </span>
                                @else
                                    <span class="text-muted">No color</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $category->products->count() }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $category->services->count() }}</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span class="status-badge active">Active</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('superadmin.categories.show', $category) }}" 
                                       class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.categories.edit', $category) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('superadmin.categories.destroy', $category) }}" 
                                          method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete">
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
        @else
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <h5>No Categories Found</h5>
                <p>Create your first category to organize your products and services.</p>
                <a href="{{ route('superadmin.categories.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>Add Category
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
