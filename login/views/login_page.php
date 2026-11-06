<?php
/**
 * login/views/login_page.php - full login / register / forgot-password
 * HTML page. Extracted verbatim from the original login.php (lines 659-1245).
 */
defined('LOGIN_CONTROLLER') || exit;

// defaults; real values come from login/bootstrap.php
$mode = $mode ?? 'login';
$resetStage = $resetStage ?? 'email';
$errorMessage = $errorMessage ?? '';
$successMessage = $successMessage ?? '';

$pageTitles = [

    'login' =>
        'Login',

    'register' =>
        'Create Account',

    'forgot' =>
        'Reset Password'
];
$pageTitle =
    $pageTitles[$mode];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        <?php
        echo htmlspecialchars(
            $pageTitle
        );
        ?>
        | IMS Nepal
    </title>

    <link
        rel="stylesheet"
        href="css/login.css?v=28"
    >

</head>
<body>
<div class="auth-shell">
    <main class="auth-main">
<div class="lc<?php echo $mode === 'forgot' ? ' lc-compact' : ''; ?>">
    <h1 class="auth-page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
    <p class="sub">
        <?php if ($mode === 'register'): ?>
            Create an account to record sales as staff.
        <?php elseif ($mode === 'forgot' && $resetStage === 'email'): ?>
            Enter your username and email to receive a reset code.
        <?php elseif ($mode === 'forgot' && $resetStage === 'otp'): ?>
            Enter the verification code sent to your email.
        <?php elseif ($mode === 'forgot' && $resetStage === 'newpass'): ?>
            Choose a new password for your account.
        <?php else: ?>
            Sign in to manage inventory and sales.
        <?php endif; ?>
    </p>
    <!-- =========================================================
         ERROR
    ========================================================== -->

    <?php if (
        $errorMessage
    ): ?>

        <div class="msg me">

            <?php
            echo htmlspecialchars(
                $errorMessage
            );
            ?>

        </div>

    <?php endif; ?>
    <!-- =========================================================
         SUCCESS
    ========================================================== -->

    <?php if (
        $successMessage
    ): ?>

        <div class="msg ms">

            <?php
            echo htmlspecialchars(
                $successMessage
            );
            ?>

        </div>

    <?php endif; ?>
    <!-- =========================================================
         FORM
    ========================================================== -->

    <form
        action="login.php?action=<?php
            echo htmlspecialchars(
                $mode
            );
        ?>"
        method="POST"
        autocomplete="off"
    >
        <?php if (
            $mode === 'register'
        ): ?>
            <!-- =================================================
                 REGISTER
            ================================================== -->

            <div class="fg">

                <label>

                    Username

                    <span class="req">
                        *
                    </span>

                </label>
                <input
                    type="text"
                    name="username"
                    required
                    maxlength="15"
                    value="<?php
                    echo htmlspecialchars(
                        $_POST['username'] ?? ''
                    );
                    ?>"
                >

            </div>
            <div class="fg">

                <label>
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="<?php
                    echo htmlspecialchars(
                        $_POST['email'] ?? ''
                    );
                    ?>"
                >

            </div>
            <div class="fg">

                <label>

                    Password

                    <span class="req">
                        *
                    </span>

                </label>
                <div class="pw-input-wrap">

                    <input
                        type="password"
                        id="password-register"
                        name="password"
                        required
                    >

                </div>
                <small class="ph">

                    8+ chars, uppercase,
                    lowercase, number,
                    special char.

                </small>

            </div>
            <div class="fg">

                <label>

                    Confirm Password

                    <span class="req">
                        *
                    </span>

                </label>
                <div class="pw-input-wrap">

                    <input
                        type="password"
                        id="confirm-register"
                        name="confirm_password"
                        required
                    >

                </div>

            </div>
        <?php elseif (
            $mode === 'forgot' &&
            $resetStage === 'email'
        ): ?>
            <!-- =================================================
                 FORGOT PASSWORD
                 STEP 1
            ================================================== -->

            <div class="fg">

                <label>

                    Username

                    <span class="req">
                        *
                    </span>

                </label>
                <input
                    type="text"
                    name="username"
                    required
                    maxlength="15"
                    autocomplete="username"
                    value="<?php
                    echo htmlspecialchars(
                        $_POST['username'] ?? ''
                    );
                    ?>"
                >

            </div>
            <div class="fg">

                <label>

                    Email for OTP

                    <span class="req">
                        *
                    </span>

                </label>
                <input
                    type="email"
                    name="email"
                    required
                    autocomplete="email"
                    value="<?php
                    echo htmlspecialchars(
                        $_POST['email'] ?? ''
                    );
                    ?>"
                >

            </div>
        <?php elseif (
            $mode === 'forgot' &&
            $resetStage === 'otp'
        ): ?>
            <!-- =================================================
                 OTP
                 STEP 2
            ================================================== -->

            <div class="fg">

                <label>

                    OTP Code

                    <span class="req">
                        *
                    </span>

                </label>
                <input
                    type="text"
                    name="otp"
                    required
                    maxlength="6"
                    minlength="6"
                    pattern="[0-9]{6}"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    placeholder="6-digit code"
                >
                <small class="ph">

                    OTP sent to

                    <?php
                    echo htmlspecialchars(
                        $_SESSION[
                            'reset_email'
                        ] ?? ''
                    );
                    ?>.

                    Valid for 2 minutes.

                </small>

            </div>
        <?php elseif (
            $mode === 'forgot' &&
            $resetStage === 'newpass'
        ): ?>
            <!-- =================================================
                 NEW PASSWORD
                 STEP 3
            ================================================== -->

            <div class="fg">

                <label>

                    New Password

                    <span class="req">
                        *
                    </span>

                </label>
                <div class="pw-input-wrap">

                    <input
                        type="password"
                        id="password-reset"
                        name="new_password"
                        required
                    >

                </div>
                <small class="ph">

                    8+ chars, uppercase,
                    lowercase, number,
                    special char.

                </small>

            </div>
            <div class="fg">

                <label>

                    Confirm Password

                    <span class="req">
                        *
                    </span>

                </label>
                <div class="pw-input-wrap">

                    <input
                        type="password"
                        id="confirm-reset"
                        name="confirm_password"
                        required
                    >

                </div>

            </div>
        <?php else: ?>
            <!-- =================================================
                 LOGIN
            ================================================== -->

            <div class="fg">

                <label>

                    Username

                    <span class="req">
                        *
                    </span>

                </label>
                <input
                    type="text"
                    name="username"
                    required
                    autocomplete="username"
                    value="<?php
                    echo htmlspecialchars(
                        $_POST['username'] ?? ''
                    );
                    ?>"
                >

            </div>
            <div class="fg">

                <label>

                    Password

                    <span class="req">
                        *
                    </span>

                </label>
                <div class="pw-input-wrap">

                    <input
                        type="password"
                        id="password-login"
                        name="password"
                        required
                        autocomplete="current-password"
                    >

                </div>

            </div>
        <?php endif; ?>
        <!-- =========================================================
             BUTTONS
        ========================================================== -->

        <div class="fa">
            <?php if ($mode === 'login'): ?>
                <button
                    type="submit"
                    class="bs"
                >
                    Log In
                </button>
                <div class="auth-link-row">
                    <a
                        href="login.php?action=register"
                        class="sl"
                    >
                        Create account
                    </a>
                    <a
                        href="login.php?action=forgot"
                        class="fl"
                    >
                        Forgot password?
                    </a>
                </div>
            <?php elseif (
                $mode === 'register'
            ): ?>
                <button
                    type="submit"
                    class="bs"
                >
                    Create Account
                </button>
                <a
                    href="login.php?action=login"
                    class="sl"
                >
                    Back to Login
                </a>
            <?php elseif (
                $mode === 'forgot' &&
                $resetStage === 'email'
            ): ?>
                <button
                    type="submit"
                    class="bs"
                >
                    Send OTP
                </button>
                <a
                    href="login.php?action=login"
                    class="sl"
                >
                    Back to Login
                </a>
            <?php elseif (
                $mode === 'forgot' &&
                $resetStage === 'otp'
            ): ?>
                <button
                    type="submit"
                    class="bs"
                >
                    Verify OTP
                </button>
                <a
                    href="login.php?action=forgot&restart=1"
                    class="sl"
                >
                    Back
                </a>
            <?php elseif (
                $mode === 'forgot' &&
                $resetStage === 'newpass'
            ): ?>
                <button
                    type="submit"
                    class="bs"
                >
                    Reset Password
                </button>
                <a
                    href="login.php?action=login"
                    class="sl"
                >
                    Back to Login
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>
    </main>
    <aside class="auth-visual" aria-label="Warehouse shelves and inventory"></aside>
</div>
</body>

</html>
