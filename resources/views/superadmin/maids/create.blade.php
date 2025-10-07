@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-user-plus"></i>
@endsection

@section('page-title', 'Add New Maid')

@section('page-subtitle', 'Create a new maid profile')

@section('header-actions')
<a href="{{ route('superadmin.maids.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Back to Maids
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('superadmin.maids.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hourly_rate" class="form-label">Hourly Rate (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" required>
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="3" placeholder="Tell us about yourself...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2"></i>Service Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                   id="specialization" name="specialization" value="{{ old('specialization') }}" 
                                   placeholder="e.g., General Cleaning, Cooking">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="experience_years" class="form-label">Experience (Years)</label>
                            <input type="number" min="0" max="50" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years') }}">
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_categories" class="form-label">Service Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('service_categories') is-invalid @enderror" 
                                    id="service_categories" 
                                    name="service_categories" 
                                    required>
                                <option value="">Select Service Category</option>
                                <option value="electrical" {{ old('service_categories') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                                <option value="plumbing" {{ old('service_categories') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                                <option value="washing" {{ old('service_categories') == 'washing' ? 'selected' : '' }}>Washing</option>
                                <option value="washroom" {{ old('service_categories') == 'washroom' ? 'selected' : '' }}>Washroom Cleaning</option>
                                <option value="cooking" {{ old('service_categories') == 'cooking' ? 'selected' : '' }}>Cooking</option>
                                <option value="cleaning" {{ old('service_categories') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                <option value="home_maid" {{ old('service_categories') == 'home_maid' ? 'selected' : '' }}>Home Maid</option>
                                <option value="caretakers" {{ old('service_categories') == 'caretakers' ? 'selected' : '' }}>Caretakers</option>
                                <option value="cooking_subscription" {{ old('service_categories') == 'cooking_subscription' ? 'selected' : '' }}>Cooking (Subscription)</option>
                                <option value="car_cleaning" {{ old('service_categories') == 'car_cleaning' ? 'selected' : '' }}>Car Cleaning</option>
                                <option value="washroom_cleaning" {{ old('service_categories') == 'washroom_cleaning' ? 'selected' : '' }}>Washroom Cleaning (Subscription)</option>
                            </select>
                            <div class="form-text">Select the primary service category for this maid</div>
                            @error('service_categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_areas" class="form-label">Service Areas</label>
                            <input type="text" class="form-control @error('service_areas') is-invalid @enderror" 
                                   id="service_areas" name="service_areas" value="{{ old('service_areas') }}" 
                                   placeholder="e.g., Delhi, Gurgaon, Noida">
                            <div class="form-text">Separate multiple areas with commas</div>
                            @error('service_areas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="skills" class="form-label">Skills</label>
                            <input type="text" class="form-control @error('skills') is-invalid @enderror" 
                                   id="skills" name="skills" value="{{ old('skills') }}" 
                                   placeholder="e.g., Cleaning, Cooking, Laundry">
                            <div class="form-text">Separate multiple skills with commas</div>
                            @error('skills')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="languages" class="form-label">Languages</label>
                            <input type="text" class="form-control @error('languages') is-invalid @enderror" 
                                   id="languages" name="languages" value="{{ old('languages') }}" 
                                   placeholder="e.g., Hindi, English, Bengali">
                            <div class="form-text">Separate multiple languages with commas</div>
                            @error('languages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="working_hours" class="form-label">Working Hours</label>
                            <input type="text" class="form-control @error('working_hours') is-invalid @enderror" 
                                   id="working_hours" name="working_hours" value="{{ old('working_hours') }}" 
                                   placeholder="e.g., 9:00 AM - 6:00 PM">
                            @error('working_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-image me-2"></i>Profile Image
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                               id="profile_image" name="profile_image" accept="image/*">
                        <div class="form-text">Upload a profile image (Max 2MB)</div>
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Status Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_verified">
                                    Verified Maid
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">
                                    Available for Booking
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.maids.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>Create Maid
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Basic styling for the single select dropdown */
#service_categories {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 8px;
    background-color: #fff;
    transition: all 0.3s ease;
    display: block;
    width: 100%;
    font-size: 14px;
    line-height: 1.5;
}

#service_categories:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    outline: none;
}

/* Style for hover effect */
#service_categories option:hover {
    background-color: #f8f9fa;
    color: #dc3545;
}
</style>

<script>
// No special JavaScript needed for single select dropdown
</script>
@endsection
