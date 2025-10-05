@extends('layouts.admin')

@section('page-icon')
<i class="fas fa-user-plus"></i>
@endsection

@section('page-title', 'Add New Maid')

@section('page-subtitle', 'Create a new maid profile with complete documentation')

@section('header-actions')
<a href="{{ route('admin.maids.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Back to Maids
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.maids.store') }}" enctype="multipart/form-data">
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
                            <label for="marital_status" class="form-label">Marital Status</label>
                            <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status" onchange="toggleFamilyFields()">
                                <option value="">Select Marital Status</option>
                                <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                            @error('marital_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Family Information -->
                        <div class="col-md-6 mb-3" id="husband_name_field" style="display: none;">
                            <label for="husband_name" class="form-label">Husband Name</label>
                            <input type="text" class="form-control @error('husband_name') is-invalid @enderror" 
                                   id="husband_name" name="husband_name" value="{{ old('husband_name') }}">
                            @error('husband_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3" id="father_name_field">
                            <label for="father_name" class="form-label">Father Name</label>
                            <input type="text" class="form-control @error('father_name') is-invalid @enderror" 
                                   id="father_name" name="father_name" value="{{ old('father_name') }}">
                            @error('father_name')
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

            <!-- Document Proofs -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-id-card me-2"></i>Document Proofs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Aadhar Card -->
                        <div class="col-md-6 mb-3">
                            <label for="aadhar_number" class="form-label">Aadhar Number</label>
                            <input type="text" class="form-control @error('aadhar_number') is-invalid @enderror" 
                                   id="aadhar_number" name="aadhar_number" value="{{ old('aadhar_number') }}" 
                                   maxlength="12" pattern="[0-9]{12}" placeholder="123456789012">
                            @error('aadhar_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="aadhar_card" class="form-label">Aadhar Card Document</label>
                            <input type="file" class="form-control @error('aadhar_card') is-invalid @enderror" 
                                   id="aadhar_card" name="aadhar_card" accept="image/*,.pdf">
                            <div class="form-text">Upload Aadhar card image or PDF (Max 5MB)</div>
                            @error('aadhar_card')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PAN Card -->
                        <div class="col-md-6 mb-3">
                            <label for="pan_number" class="form-label">PAN Number</label>
                            <input type="text" class="form-control @error('pan_number') is-invalid @enderror" 
                                   id="pan_number" name="pan_number" value="{{ old('pan_number') }}" 
                                   maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" placeholder="ABCDE1234F">
                            @error('pan_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pan_card" class="form-label">PAN Card Document</label>
                            <input type="file" class="form-control @error('pan_card') is-invalid @enderror" 
                                   id="pan_card" name="pan_card" accept="image/*,.pdf">
                            <div class="form-text">Upload PAN card image or PDF (Max 5MB)</div>
                            @error('pan_card')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Proof -->
                        <div class="col-md-6 mb-3">
                            <label for="address_proof_type" class="form-label">Address Proof Type</label>
                            <select class="form-select @error('address_proof_type') is-invalid @enderror" id="address_proof_type" name="address_proof_type">
                                <option value="">Select Address Proof</option>
                                <option value="voter_id" {{ old('address_proof_type') == 'voter_id' ? 'selected' : '' }}>Voter ID</option>
                                <option value="driving_license" {{ old('address_proof_type') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                <option value="passport" {{ old('address_proof_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                <option value="bank_statement" {{ old('address_proof_type') == 'bank_statement' ? 'selected' : '' }}>Bank Statement</option>
                                <option value="utility_bill" {{ old('address_proof_type') == 'utility_bill' ? 'selected' : '' }}>Utility Bill</option>
                                <option value="rent_agreement" {{ old('address_proof_type') == 'rent_agreement' ? 'selected' : '' }}>Rent Agreement</option>
                            </select>
                            @error('address_proof_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address_proof_document" class="form-label">Address Proof Document</label>
                            <input type="file" class="form-control @error('address_proof_document') is-invalid @enderror" 
                                   id="address_proof_document" name="address_proof_document" accept="image/*,.pdf">
                            <div class="form-text">Upload address proof document (Max 5MB)</div>
                            @error('address_proof_document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Documents -->
                        <div class="col-md-6 mb-3">
                            <label for="police_verification" class="form-label">Police Verification</label>
                            <input type="file" class="form-control @error('police_verification') is-invalid @enderror" 
                                   id="police_verification" name="police_verification" accept="image/*,.pdf">
                            <div class="form-text">Upload police verification document (Max 5MB)</div>
                            @error('police_verification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="medical_certificate" class="form-label">Medical Certificate</label>
                            <input type="file" class="form-control @error('medical_certificate') is-invalid @enderror" 
                                   id="medical_certificate" name="medical_certificate" accept="image/*,.pdf">
                            <div class="form-text">Upload medical certificate (Max 5MB)</div>
                            @error('medical_certificate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Location Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="28.6139">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="77.2090">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="google_maps_link" class="form-label">Google Maps Link</label>
                            <input type="url" class="form-control @error('google_maps_link') is-invalid @enderror" 
                                   id="google_maps_link" name="google_maps_link" value="{{ old('google_maps_link') }}" 
                                   placeholder="https://maps.google.com/...">
                            <div class="form-text">Paste Google Maps link for precise location</div>
                            @error('google_maps_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary" onclick="getCurrentLocation()">
                                <i class="fas fa-location-arrow me-2"></i>Get Current Location
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2"></i>Professional Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                   id="specialization" name="specialization" value="{{ old('specialization') }}" 
                                   placeholder="e.g., House Cleaning, Cooking, Elderly Care">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="experience_years" class="form-label">Experience (Years)</label>
                            <input type="number" min="0" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years') }}">
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_categories" class="form-label">Service Categories</label>
                            <input type="text" class="form-control @error('service_categories') is-invalid @enderror" 
                                   id="service_categories" name="service_categories" value="{{ old('service_categories') }}" 
                                   placeholder="e.g., Cleaning, Cooking, Childcare">
                            @error('service_categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_areas" class="form-label">Service Areas</label>
                            <input type="text" class="form-control @error('service_areas') is-invalid @enderror" 
                                   id="service_areas" name="service_areas" value="{{ old('service_areas') }}" 
                                   placeholder="e.g., Downtown, Suburbs, North Side">
                            @error('service_areas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="skills" class="form-label">Skills</label>
                            <input type="text" class="form-control @error('skills') is-invalid @enderror" 
                                   id="skills" name="skills" value="{{ old('skills') }}" 
                                   placeholder="e.g., Cooking, Cleaning, First Aid, Pet Care">
                            @error('skills')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="languages" class="form-label">Languages</label>
                            <input type="text" class="form-control @error('languages') is-invalid @enderror" 
                                   id="languages" name="languages" value="{{ old('languages') }}" 
                                   placeholder="e.g., English, Hindi, Bengali">
                            @error('languages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="working_hours" class="form-label">Working Hours</label>
                            <input type="text" class="form-control @error('working_hours') is-invalid @enderror" 
                                   id="working_hours" name="working_hours" value="{{ old('working_hours') }}" 
                                   placeholder="e.g., 9 AM - 6 PM, Monday to Friday">
                            @error('working_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reference Information -->
                        <div class="col-md-6 mb-3">
                            <label for="reference_contact" class="form-label">Reference Contact Name</label>
                            <input type="text" class="form-control @error('reference_contact') is-invalid @enderror" 
                                   id="reference_contact" name="reference_contact" value="{{ old('reference_contact') }}">
                            @error('reference_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="reference_phone" class="form-label">Reference Phone Number</label>
                            <input type="tel" class="form-control @error('reference_phone') is-invalid @enderror" 
                                   id="reference_phone" name="reference_phone" value="{{ old('reference_phone') }}">
                            @error('reference_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Profile Image -->
                        <div class="col-12 mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                   id="profile_image" name="profile_image" accept="image/*">
                            <div class="form-text">Upload a professional photo (Max 2MB, JPEG/PNG/GIF/WebP)</div>
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Options -->
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_verified">
                                            Verified Maid
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_available">
                                            Available for Work
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Maid
                </button>
                <a href="{{ route('admin.maids.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Documentation Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Required Documents:</h6>
                    <ul class="mb-0">
                        <li><strong>Aadhar Card:</strong> 12-digit number + document upload</li>
                        <li><strong>PAN Card:</strong> 10-character PAN number + document upload</li>
                        <li><strong>Address Proof:</strong> Any valid address verification document</li>
                        <li><strong>Police Verification:</strong> Background check document</li>
                        <li><strong>Medical Certificate:</strong> Health verification document</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes:</h6>
                    <ul class="mb-0">
                        <li>All document uploads must be clear and readable</li>
                        <li>Maximum file size: 5MB per document</li>
                        <li>Accepted formats: JPG, PNG, PDF</li>
                        <li>Family information is required for verification</li>
                        <li>Location coordinates help with service area mapping</li>
                    </ul>
                </div>

                <div class="alert alert-success">
                    <h6><i class="fas fa-check-circle me-2"></i>Verification Process:</h6>
                    <ul class="mb-0">
                        <li>Documents will be verified by admin team</li>
                        <li>Background checks will be conducted</li>
                        <li>Medical fitness will be confirmed</li>
                        <li>Reference contacts will be verified</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

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

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            
            // Generate Google Maps link
            const mapsLink = `https://www.google.com/maps?q=${position.coords.latitude},${position.coords.longitude}`;
            document.getElementById('google_maps_link').value = mapsLink;
            
            alert('Location captured successfully!');
        }, function(error) {
            alert('Unable to get current location. Please enter manually.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize family fields
    toggleFamilyFields();
    
    // File validation
    const fileInputs = ['profile_image', 'aadhar_card', 'pan_card', 'address_proof_document', 'police_verification', 'medical_certificate'];
    
    fileInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Please select a valid file (JPEG, PNG, GIF, WebP, PDF)');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file size (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size must be less than 5MB');
                        this.value = '';
                        return;
                    }
                }
            });
        }
    });
    
    // Aadhar number validation
    const aadharInput = document.getElementById('aadhar_number');
    if (aadharInput) {
        aadharInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 12) {
                this.value = this.value.slice(0, 12);
            }
        });
    }
    
    // PAN number validation
    const panInput = document.getElementById('pan_number');
    if (panInput) {
        panInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            this.value = this.value.replace(/[^A-Z0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }
});
</script>
@endsection