<h1 class="h3 mb-4">
    <?= e($view_bag['title'] ?? '') ?>
</h1>

<?php if (isset($model) && $model !== false): ?>

    <dl class="row">
        <dt class="col-sm-3">Term</dt>
        <dd class="col-sm-9">
            <?= e($model->term ?? '') ?>
        </dd>

        <dt class="col-sm-3">Definition</dt>
        <dd class="col-sm-9">
            <?= e($model->definition ?? '') ?>
        </dd>
    </dl>

<?php else: ?>

    <p class="text-warning">
        Definition not found.
    </p>

<?php endif; ?>

<a href="index.php" class="btn btn-outline-light btn-sm">
    &larr; Back to list
</a>
