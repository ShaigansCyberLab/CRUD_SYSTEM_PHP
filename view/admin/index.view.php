<div class="d-flex justify-content-between align-items-center mb-3">

    <h1 class="h3">
        Admin Panel
    </h1>

    <div class="d-flex gap-2">

        <a
            href="create.php"
            class="btn btn-outline-success btn-sm"
        >
            + New Term
        </a>

        <form
            method="post"
            action="../logout.php"
            class="d-inline"
        >
            <?= csrf_field() ?>

            <button
                type="submit"
                class="btn btn-outline-danger btn-sm"
            >
                Logout
            </button>
        </form>

    </div>

</div>

<?php if (empty($items)): ?>

    <p class="text-muted">
        No terms yet.
        <a href="create.php">
            Create one.
        </a>
    </p>

<?php else: ?>

    <div class="table-responsive">

        <table
            class="table table-bordered table-striped table-hover"
        >

            <thead>

                <tr>
                    <th>#</th>
                    <th>Term</th>
                    <th>Definition</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($items as $item): ?>

                    <tr>

                        <td>
                            <?= e($item->id) ?>
                        </td>

                        <td>
                            <?= e($item->term) ?>
                        </td>

                        <td>
                            <?= e($item->definition) ?>
                        </td>

                        <td>

                            <a
                                href="edit.php?key=<?= e($item->id) ?>"
                                class="btn btn-outline-warning btn-sm"
                            >
                                Edit
                            </a>

                            <a
                                href="delete.php?key=<?= e($item->id) ?>"
                                class="btn btn-outline-danger btn-sm"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

<?php endif; ?>
