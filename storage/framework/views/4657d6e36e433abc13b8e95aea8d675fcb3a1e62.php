<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Error - SuperDaily</title>
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
                        <i class="fas fa-database me-2"></i>Database Error
                    </h3>
                    <p class="mb-0 mt-2">A database connection issue occurred</p>
                </div>
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-database fa-4x text-danger mb-3"></i>
                        <h4 class="text-danger">Database Connection Error</h4>
                        <p class="text-muted">
                            We're experiencing temporary database connectivity issues. This could be due to:
                        </p>
                    </div>

                    <div class="alert alert-warning text-start">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Possible Causes:
                        </h6>
                        <ul class="mb-0">
                            <li>Database server is temporarily unavailable</li>
                            <li>Connection timeout or network issues</li>
                            <li>Database maintenance in progress</li>
                            <li>Configuration issues</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">What you can do:</h6>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Wait a few minutes and try again
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Check your internet connection
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Contact support if the problem persists
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Try refreshing the page
                            </li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <a href="javascript:location.reload()" class="btn btn-primary">
                            <i class="fas fa-refresh me-2"></i>Refresh Page
                        </a>
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Technical Information -->
            <?php if(config('app.debug')): ?>
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-bug me-2"></i>Debug Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?php echo e($exception->getMessage() ?? 'Database connection failed'); ?>

                    </div>
                    <div class="small text-muted">
                        <strong>Time:</strong> <?php echo e(now()->format('Y-m-d H:i:s')); ?><br>
                        <strong>URL:</strong> <?php echo e(request()->fullUrl()); ?><br>
                        <strong>Method:</strong> <?php echo e(request()->method()); ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\superdaily\resources\views/errors/database.blade.php ENDPATH**/ ?>