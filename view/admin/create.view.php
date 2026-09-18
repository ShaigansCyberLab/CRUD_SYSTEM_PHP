<h1 class="h3 mb-4">
    Create New Term
</h1>

<?php if (!empty($view_bag['error'])): ?>

    <div class="alert alert-danger py-2">
        <?= e($view_bag['error']) ?>
    </div>

<?php endif; ?>

<form method="post" action="create.php">

    <?= csrf_field() ?>

    <div class="mb-3">

        <label
            for="term"
            class="form-label"
        >
            Term
        </label>

        <input
            type="text"
            id="term"
            name="term"
            class="form-control"
            required
            maxlength="255"
        >

    </div>

    <div class="mb-3">

        <label
            for="definition"
            class="form-label"
        >
            Definition
        </label>

        <textarea
            id="definition"
            name="definition"
            class="form-control"
            rows="4"
            required
        ></textarea>

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Create
    </button>

    <a
        href="index.php"
        class="btn btn-outline-light ms-2"
    >
        Cancel
    </a>

</form>
