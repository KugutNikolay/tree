<?php

/** @var \app\models\Node $rootNode */
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tree Nodes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        li {
            list-style: none;
        }
        li ul li{
            padding-top: 0.5rem;
        }
        .js-show-child,
        .js-remove-node,
        .js-add-node,
        .js-edit-node
        {
            cursor: pointer;
        }
        #delete-confirmation .modal-footer{
            justify-content: space-between;
        }
    </style>
    </head>
    <body>
        <div class="container">
            <h1>Tree Nodes</h1>

            <div class="<?= $rootNode ? "visually-hidden" : ''; ?>">
                <button type="button" id="js-show-create-modal" class="btn btn-success">Create Root</button>
            </div>

            <div id="tree">
                <?php if ($rootNode) : ?>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <?= $rootNode->isChilds() ? '<i data-id="' . $rootNode->getId() . '" class="js-show-child bi bi-caret-right-fill"></i>' : ''; ?>
                            <span id="node-<?= $rootNode->getId(); ?>"><?= $rootNode->getText(); ?></span>
                            <i data-parent_id="<?= $rootNode->getId(); ?>" class="js-add-node bi bi-file-plus"></i>
                            <i data-id="<?= $rootNode->getId(); ?>" class="js-remove-node bi bi-file-minus"></i>
                            <i data-id="<?= $rootNode->getId(); ?>" data-text="<?= $rootNode->getText(); ?>" class="js-edit-node bi-pencil"></i>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
        <script src="assets/js/app.js"></script>

        <div class="modal fade" id="delete-confirmation" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="delete-confirmation-label">Delete Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        This is very dangerous, you shouldn`t do it! Are you really sure?
                    </div>
                    <div class="modal-footer">
                        <div id="timer-confirm">

                        </div>
                        <div>
                            <button id="js-delete-item" type="button" class="btn btn-primary">Yes I am</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="create-node-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="create-node-modal-label">Create</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="create-node" action="tree/save">
                            <div class="mb-3">
                                <label for="text-input" class="form-label">Text</label>
                                <input type="text" name="text" class="form-control" id="node-text" placeholder="root"/>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="create-node" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
