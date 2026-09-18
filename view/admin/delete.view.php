<h1 class="h3 mb-4">
    Delete Term
</h1>

<?php if (!empty($view_bag['error'])): ?>

    <div class="alert alert-danger py-2">
        <?= e($view_bag['error']) ?>
    </div>

<?php endif; ?>

<p>
    Are you sure you want to delete
    <strong><?= e($model->term) ?></strong>?
</p>

<form method="post" action="delete.php">

    <?= csrf_field() ?>

    <input
        type="hidden"
        name="term_id"
        value="<?= e($model->id) ?>"
    >

    <button
        type="submit"
        class="btn btn-danger"
    >
        Yes, Delete
    </button>

    <a
        href="index.php"
        class="btn btn-outline-light ms-2"
    >
        Cancel
    </a>

</form>
