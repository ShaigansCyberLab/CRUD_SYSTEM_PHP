<h1 class="h3 mb-4">
    <?= e($view_bag['title'] ?? '') ?>
</h1>

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

<a href="index.php" class="btn btn-outline-light btn-sm">
    &larr; Back to list
</a>
