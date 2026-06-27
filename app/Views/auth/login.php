<section class="login-card">
    <div class="login-visual">
        <div class="brand mb-4">
            <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
            <span><?= e(app_config('name')) ?></span>
        </div>
        <h1>Run the school day from one secure workspace.</h1>
        <p>Secure access for administrators, staff, families, and learners.</p>
    </div>
    <form class="login-form" action="<?= e(url('/login')) ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <h2>Sign in</h2>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
        <?php endif; ?>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control form-control-lg" id="email" name="email" type="email" value="admin@school.test" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control form-control-lg" id="password" name="password" type="password" value="Admin@12345" required>
        </div>
        <button class="btn btn-primary btn-lg w-100" type="submit">Sign in</button>
        <p class="text-muted small mt-3 mb-0">Seed admin: admin@school.test / Admin@12345</p>
    </form>
</section>
