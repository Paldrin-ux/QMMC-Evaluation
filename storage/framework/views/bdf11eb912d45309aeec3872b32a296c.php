<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — QMMC General Services</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --navy: #002060; --navy-lt: #003087; --accent: #E8A020;
  --bg: #F0F3FA; --surface: #FFFFFF; --border: #DDE2EE;
  --text: #1A2340; --muted: #6B7494; --danger: #C0392B;
}
body { font-family: 'Segoe UI', system-ui, sans-serif; font-size: 14px; background: var(--bg); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
.login-wrap { width: 100%; max-width: 420px; }
.login-brand { text-align: center; margin-bottom: 28px; }
.login-brand .crest { width: 64px; height: 64px; border-radius: 16px; background: var(--navy); margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 800; color: var(--accent); letter-spacing: -1px; }
.login-brand h1 { font-size: 18px; font-weight: 700; color: var(--navy); }
.login-brand p  { font-size: 12px; color: var(--muted); margin-top: 3px; }
.card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 32px; box-shadow: 0 4px 24px rgba(0,32,96,.06); }
.card h2 { font-size: 16px; font-weight: 700; margin-bottom: 22px; }
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 11px; font-weight: 700; color: var(--muted); letter-spacing: .06em; text-transform: uppercase; margin-bottom: 5px; }
.form-control { width: 100%; padding: 10px 13px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; outline: none; transition: border-color .15s; background: #FAFBFD; }
.form-control:focus { border-color: var(--navy-lt); box-shadow: 0 0 0 3px rgba(0,48,135,.08); background: #fff; }
.form-control.is-invalid { border-color: var(--danger); }
.invalid-feedback { color: var(--danger); font-size: 11px; margin-top: 4px; }
.remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: 13px; color: var(--muted); }
.remember-row input { width: 14px; height: 14px; cursor: pointer; }
.btn-submit { width: 100%; padding: 11px; background: var(--navy-lt); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .15s; letter-spacing: .02em; }
.btn-submit:hover { background: var(--navy); }
.footer { text-align: center; font-size: 11px; color: var(--muted); margin-top: 20px; }
.alert { padding: 10px 14px; border-radius: 8px; background: #F8D7DA; border: 1px solid #F5C6CB; color: #721C24; font-size: 13px; margin-bottom: 16px; }
</style>
</head>
<body>
<div class="login-wrap">

  <div class="login-brand">
    <div class="crest">Q</div>
    <h1>QUIRINO MEMORIAL MEDICAL CENTER</h1>
    <p>General Services Section — Evaluation System</p>
  </div>

  <div class="card">
    <h2>Sign in to your account</h2>

    <?php if($errors->any()): ?>
      <div class="alert"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('login.post')); ?>">
      <?php echo csrf_field(); ?>

      <div class="form-group">
        <label class="form-label" for="email">Email address</label>
        <input type="email" id="email" name="email"
               class="form-control <?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>"
               value="<?php echo e(old('email')); ?>" required autofocus autocomplete="email">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password"
               class="form-control <?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
               required autocomplete="current-password">
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="remember-row">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Keep me signed in</label>
      </div>

      <button type="submit" class="btn-submit">Sign In</button>
    </form>
  </div>

  <div class="footer">GSU-005 · Rev 1 · 09 January 2026 &nbsp;|&nbsp; QMMC General Services</div>
</div>
</body>
</html><?php /**PATH C:\qmmc_laravel\qmmc_laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>