<div class="row justify-content-center">

    <div class="col-md-5 col-lg-4">

        <h1 class="h4 mb-4 text-center">
            Login
        </h1>

        <?php if (!empty($view_bag['status'])): ?>

            <div
                class="alert alert-danger py-2"
                role="alert"
            >
                <?= e($view_bag['status']) ?>
            </div>

        <?php endif; ?>

        <form
            method="post"
            action="login.php"
            autocomplete="on"
        >

            <?= csrf_field() ?>

            <div class="mb-3">

                <label
                    for="email"
                    class="form-label"
                >
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    required
                    maxlength="254"
                    autocomplete="username"
                    value="<?= e($email ?? '') ?>"
                >

            </div>

            <div class="mb-3">

                <label
                    for="password"
                    class="form-label"
                >
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    required
                    maxlength="4096"
                    autocomplete="current-password"
                >

            </div>

            <button
                type="submit"
                class="btn btn-info w-100"
            >
                Sign in
            </button>

        </form>

    </div>

</div>
