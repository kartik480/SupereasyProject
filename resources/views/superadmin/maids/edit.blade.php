@extends('layouts.superadmin')

@section('page-icon')
<i class="fas fa-edit"></i>
@endsection

@section('page-title', 'Edit Maid')

@section('page-subtitle', 'Update maid information')

@section('header-actions')
<div class="btn-group">
    <a href="{{ route('superadmin.maids.show', $maid) }}" class="btn btn-outline-info">
        <i class="fas fa-eye me-2"></i>View Details
    </a>
    <a href="{{ route('superadmin.maids.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Maids
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Maid Information
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

                <form method="POST" action="{{ route('superadmin.maids.update', $maid) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $maid->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $maid->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $maid->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $maid->date_of_birth?->format('Y-m-d')) }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $maid->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $maid->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $maid->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="marital_status" class="form-label">Marital Status</label>
                            <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status" onchange="toggleFamilyFields()">
                                <option value="">Select Marital Status</option>
                                <option value="single" {{ old('marital_status', $maid->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status', $maid->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status', $maid->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status', $maid->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                            @error('marital_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Family Information -->
                        <div class="col-md-6 mb-3" id="husband_name_field" style="display: {{ old('marital_status', $maid->marital_status) == 'married' ? 'block' : 'none' }};">
                            <label for="husband_name" class="form-label">Husband Name</label>
                            <input type="text" class="form-control @error('husband_name') is-invalid @enderror" 
                                   id="husband_name" name="husband_name" value="{{ old('husband_name', $maid->husband_name) }}">
                            @error('husband_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3" id="father_name_field">
                            <label for="father_name" class="form-label">Father Name</label>
                            <input type="text" class="form-control @error('father_name') is-invalid @enderror" 
                                   id="father_name" name="father_name" value="{{ old('father_name', $maid->father_name) }}">
                            @error('father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hourly_rate" class="form-label">Hourly Rate (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $maid->hourly_rate) }}" required>
                            @error('hourly_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $maid->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $maid->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Professional Information -->
                        <div class="col-md-6 mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                   id="specialization" name="specialization" value="{{ old('specialization', $maid->specialization) }}" 
                                   placeholder="e.g., House Cleaning, Cooking, Elderly Care">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="experience_years" class="form-label">Experience (Years)</label>
                            <input type="number" min="0" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years', $maid->experience_years) }}">
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_categories" class="form-label">Service Category <span class="text-danger">*</span></label>
                            
                            <!-- Display currently selected category -->
                            @php
                                $selectedCategory = is_string($maid->service_categories) ? json_decode($maid->service_categories, true) : $maid->service_categories;
                                $selectedCategory = is_array($selectedCategory) ? (count($selectedCategory) > 0 ? $selectedCategory[0] : '') : $selectedCategory;
                            @endphp
                            
                            @if(!empty($selectedCategory))
                                <div class="mb-2">
                                    <small class="text-muted">Currently Selected:</small>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-danger text-white">{{ $selectedCategory }}</span>
                                    </div>
                                </div>
                            @endif
                            
                            <select class="form-select @error('service_categories') is-invalid @enderror" 
                                    id="service_categories" 
                                    name="service_categories" 
                                    required>
                                <option value="">Select Service Category</option>
                                <option value="electrical" {{ old('service_categories', $selectedCategory) == 'electrical' ? 'selected' : '' }}>Electrical</option>
                                <option value="plumbing" {{ old('service_categories', $selectedCategory) == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                                <option value="washing" {{ old('service_categories', $selectedCategory) == 'washing' ? 'selected' : '' }}>Washing</option>
                                <option value="washroom" {{ old('service_categories', $selectedCategory) == 'washroom' ? 'selected' : '' }}>Washroom Cleaning</option>
                                <option value="cooking" {{ old('service_categories', $selectedCategory) == 'cooking' ? 'selected' : '' }}>Cooking</option>
                                <option value="cleaning" {{ old('service_categories', $selectedCategory) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                <option value="home_maid" {{ old('service_categories', $selectedCategory) == 'home_maid' ? 'selected' : '' }}>Home Maid</option>
                                <option value="caretakers" {{ old('service_categories', $selectedCategory) == 'caretakers' ? 'selected' : '' }}>Caretakers</option>
                                <option value="cooking_subscription" {{ old('service_categories', $selectedCategory) == 'cooking_subscription' ? 'selected' : '' }}>Cooking (Subscription)</option>
                                <option value="car_cleaning" {{ old('service_categories', $selectedCategory) == 'car_cleaning' ? 'selected' : '' }}>Car Cleaning</option>
                                <option value="washroom_cleaning" {{ old('service_categories', $selectedCategory) == 'washroom_cleaning' ? 'selected' : '' }}>Washroom Cleaning (Subscription)</option>
                            </select>
                            <div class="form-text">Select the primary service category for this maid</div>
                            @error('service_categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_areas" class="form-label">Service Areas</label>
                            @php
                                $serviceAreas = is_string($maid->service_areas) ? json_decode($maid->service_areas, true) : $maid->service_areas;
                                $serviceAreasString = is_array($serviceAreas) ? implode(', ', $serviceAreas) : ($maid->service_areas ?? '');
                            @endphp
                            <input type="text" class="form-control @error('service_areas') is-invalid @enderror" 
                                   id="service_areas" name="service_areas" value="{{ old('service_areas', $serviceAreasString) }}" 
                                   placeholder="e.g., Downtown, Suburbs, North Side">
                            @error('service_areas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="skills" class="form-label">Skills</label>
                            @php
                                $skills = is_string($maid->skills) ? json_decode($maid->skills, true) : $maid->skills;
                                $skillsString = is_array($skills) ? implode(', ', $skills) : $maid->skills;
                            @endphp
                            <input type="text" class="form-control @error('skills') is-invalid @enderror" 
                                   id="skills" name="skills" value="{{ old('skills', $skillsString) }}" 
                                   placeholder="e.g., Cooking, Cleaning, First Aid, Pet Care">
                            @error('skills')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="languages" class="form-label">Languages</label>
                            @php
                                $languages = is_string($maid->languages) ? json_decode($maid->languages, true) : $maid->languages;
                                $languagesString = is_array($languages) ? implode(', ', $languages) : $maid->languages;
                            @endphp
                            <input type="text" class="form-control @error('languages') is-invalid @enderror" 
                                   id="languages" name="languages" value="{{ old('languages', $languagesString) }}" 
                                   placeholder="e.g., English, Hindi, Bengali">
                            @error('languages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="working_hours" class="form-label">Working Hours</label>
                            @php
                                $workingHours = is_string($maid->working_hours) ? json_decode($maid->working_hours, true) : $maid->working_hours;
                                $workingHoursString = is_array($workingHours) ? implode(', ', $workingHours) : ($maid->working_hours ?? '');
                            @endphp
                            <input type="text" class="form-control @error('working_hours') is-invalid @enderror" 
                                   id="working_hours" name="working_hours" value="{{ old('working_hours', $workingHoursString) }}" 
                                   placeholder="e.g., 9 AM - 6 PM, Monday to Friday">
                            @error('working_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Profile Image -->
                        <div class="col-12 mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            @if($maid->profile_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $maid->profile_image) }}" 
                                         alt="{{ $maid->name }}" 
                                         class="img-thumbnail" 
                                         width="100" 
                                         height="100">
                                    <small class="text-muted d-block">Current image</small>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                   id="profile_image" name="profile_image" accept="image/*">
                            <div class="form-text">Upload a new image to replace current one (Max 2MB, JPEG/PNG/GIF/WebP)</div>
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Options -->
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified', $maid->is_verified) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_verified">
                                            Verified Maid
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $maid->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $maid->is_available) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_available">
                                            Available for Work
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>Update Maid
                        </button>
                        <a href="{{ route('superadmin.maids.show', $maid) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Current Information
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ $maid->profile_image ? asset('storage/' . $maid->profile_image) : 'https://via.placeholder.com/100x100/6c757d/ffffff?text=M' }}" 
                         alt="{{ $maid->name }}" 
                         class="rounded-circle" 
                         width="100" 
                         height="100">
                </div>
                
                <h6 class="mb-1">{{ $maid->name }}</h6>
                <p class="text-muted mb-3">{{ $maid->specialization ?? 'General Maid' }}</p>
                
                <div class="mb-3">
                    @if($maid->is_active)
                        <span class="badge bg-success me-1">Active</span>
                    @else
                        <span class="badge bg-danger me-1">Inactive</span>
                    @endif
                    
                    @if($maid->is_verified)
                        <span class="badge bg-primary me-1">Verified</span>
                    @endif
                    
                    @if($maid->is_available)
                        <span class="badge bg-info">Available</span>
                    @else
                        <span class="badge bg-warning">Busy</span>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-center mb-3">
                    <span class="text-warning me-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $maid->rating ? '' : '-o' }}"></i>
                        @endfor
                    </span>
                    <span class="fw-bold">{{ number_format($maid->rating, 1) }}</span>
                    <small class="text-muted ms-1">({{ $maid->total_ratings }} reviews)</small>
                </div>

                <hr>

                <div class="small">
                    <div class="mb-2">
                        <strong>Email:</strong><br>
                        {{ $maid->email }}
                    </div>
                    <div class="mb-2">
                        <strong>Phone:</strong><br>
                        {{ $maid->phone }}
                    </div>
                    <div class="mb-2">
                        <strong>Hourly Rate:</strong><br>
                        ₹{{ number_format($maid->hourly_rate, 0) }}
                    </div>
                    @if($maid->experience_years)
                        <div class="mb-2">
                            <strong>Experience:</strong><br>
                            {{ $maid->experience_years }} years
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Important Notes
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <ul class="mb-0 small">
                        <li>Changes will be reflected immediately</li>
                        <li>Email changes require verification</li>
                        <li>Deactivating a maid will cancel pending bookings</li>
                        <li>Profile image changes are permanent</li>
                    </ul>
                </div>
            </div>
        </div>
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
function toggleFamilyFields() {
    const maritalStatus = document.getElementById('marital_status').value;
    const husbandField = document.getElementById('husband_name_field');
    const fatherField = document.getElementById('father_name_field');
    
    if (maritalStatus === 'married') {
        husbandField.style.display = 'block';
        fatherField.style.display = 'none';
    } else {
        husbandField.style.display = 'none';
        fatherField.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize family fields
    toggleFamilyFields();
    
    // Image preview functionality
    const profileImageInput = document.getElementById('profile_image');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, GIF, WebP)');
                    this.value = '';
                    return;
                }
                
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
            }
        });
    }

    // No special JavaScript needed for single select dropdown
});
</script>
@endsection