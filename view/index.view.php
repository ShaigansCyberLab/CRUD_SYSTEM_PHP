<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Glossary</h1>

    <?php if (!is_user_authenticated()): ?>
        <a href="login.php" class="btn btn-outline-light btn-sm">
            Login
        </a>
    <?php else: ?>
        <a href="admin/index.php" class="btn btn-outline-info btn-sm">
            Admin
        </a>
    <?php endif; ?>
</div>

<form method="get" action="index.php" class="mb-4">
    <div class="input-group">
        <input
            type="search"
            name="search"
            class="form-control"
            placeholder="Search terms..."
            value="<?= e($search ?? '') ?>"
        >

        <button type="submit" class="btn btn-outline-info">
            Search
        </button>
    </div>
</form>

<?php if (empty($items)): ?>

    <p class="text-muted">No terms found.</p>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-dark table-bordered table-hover">
            <thead>
                <tr>
                    <th>Term</th>
                    <th>Definition</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <a href="detail.php?id=<?= e($item->id) ?>">
                                <?= e($item->term) ?>
                            </a>
                        </td>

                        <td>
                            <?= e($item->definition) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>
