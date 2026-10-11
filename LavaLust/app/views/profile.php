<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Student Profile'; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f6f9; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007bff; font-weight: bold; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 450px; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        p { margin: 10px 0; font-size: 15px; }
        strong { color: #555; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="<?= site_url('student'); ?>">Home</a> |
        <a href="<?= site_url('student/profile'); ?>">Student Profile</a>
    </div>
    <div class="card">
        <h1>Student Information</h1>
        <p><strong>Student ID:</strong> <?= $student_id ?? ''; ?></p>
        <p><strong>Name:</strong> <?= $name ?? ''; ?></p>
        <p><strong>Course:</strong> <?= $course ?? ''; ?></p>
        <p><strong>Year Level:</strong> <?= $year ?? ''; ?></p>
        <p><strong>Section:</strong> <?= $section ?? ''; ?></p>
        <p><strong>Email:</strong> <?= $email ?? ''; ?></p>
        <p><strong>Skills:</strong> <?= $skills ?? ''; ?></p>
        <br>
        <a href="<?= site_url('student/logout'); ?>" style="color:red; text-decoration:none;">Revoke Access & Logout</a>
    </div>
</body>
</html>