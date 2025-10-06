<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Error - SuperDaily</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #00d4aa, #00b894);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #00b894, #00a085);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 170, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-danger text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Upload Error
                    </h3>
                    <p class="mb-0 mt-2">There was an issue with your file upload</p>
                </div>
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-cloud-upload-alt fa-4x text-danger mb-3"></i>
                        <h4 class="text-danger">File Upload Error</h4>
                        <p class="text-muted">
                            We encountered an error while processing your file upload. This could be due to:
                        </p>
                    </div>

                    <div class="alert alert-warning text-start">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Possible Causes:
                        </h6>
                        <ul class="mb-0">
                            <li>Invalid file format or corrupted file</li>
                            <li>File size exceeds the maximum allowed limit</li>
                            <li>Unsupported file type</li>
                            <li>Server processing error</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">What you can do:</h6>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Try uploading a different file
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Ensure the file is under 2MB in size
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Use supported formats: JPEG, PNG, JPG, GIF, WebP
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Contact support if the problem persists
                            </li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <a href="javascript:history.back()" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </a>
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- File Upload Guidelines -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-upload me-2"></i>File Upload Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Supported Formats:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-image text-success me-2"></i>JPEG (.jpg, .jpeg)</li>
                                <li><i class="fas fa-image text-success me-2"></i>PNG (.png)</li>
                                <li><i class="fas fa-image text-success me-2"></i>GIF (.gif)</li>
                                <li><i class="fas fa-image text-success me-2"></i>WebP (.webp)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">File Requirements:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-weight text-info me-2"></i>Maximum size: 2MB</li>
                                <li><i class="fas fa-ruler text-info me-2"></i>Recommended: 800x600px</li>
                                <li><i class="fas fa-shield-alt text-info me-2"></i>No malicious content</li>
                                <li><i class="fas fa-check-circle text-info me-2"></i>Valid file format</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\superdaily\resources\views/errors/illegal-offset.blade.php ENDPATH**/ ?>