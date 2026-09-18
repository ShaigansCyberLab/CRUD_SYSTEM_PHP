<h1 class="h3 mb-4">
    Edit Term
</h1>

<?php if (!empty($view_bag['error'])): ?>

    <div
        class="alert alert-danger py-2"
        role="alert"
    >
        <?= e($view_bag['error']) ?>
    </div>

<?php endif; ?>

<form
    method="post"
    action="edit.php"
    autocomplete="off"
>

    <?= csrf_field() ?>

    <input
        type="hidden"
        name="original_id"
        value="<?= e($model->id) ?>"
    >

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
            value="<?= e($model->term) ?>"
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
            maxlength="10000"
            required
        ><?= e($model->definition) ?></textarea>

    </div>

    <button
        type="submit"
        class="btn btn-warning"
    >
        Update
    </button>

    <a
        href="index.php"
        class="btn btn-outline-light ms-2"
    >
        Cancel
    </a>

</form>
