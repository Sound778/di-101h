<?php
/**
 * @var array $comments
 */
?>
<?= $this->extend('layout/main'); ?>
<?= $this->section('pageContent'); ?>
<div class="card text-white bg-secondary w-100 p-3 mt-3">
    <div class="card-body">
        <p class="card-text">Здесь размещен контент, который и комментируют...</p>
    </div>
</div>
<hr>
<table class="table">
    <thead>
    <tr class="table-primary">
        <th>id</th>
        <th>Дата</th>
        <th>Создатель</th>
        <th>Комментарий</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($comments) > 0) {
        foreach ($comments as $row) {
            echo '<tr data-line="' . $row['id'] . '">',
                '<td>' . $row['id'] . '</td>',
                '<td>' . date_format(date_create($row['date']), 'd.m.Y') . '</td>',
                '<td>' . $row['name'] . '</td>',
                '<td>' . htmlspecialchars($row['text']) . '</td>',
                '<td><button class="btn btn-danger js-del-comment">Удалить</button></td>',
                '</tr>' . PHP_EOL;
        }
    } else {
        echo '<tr><td colspan="4">Записей не найдено!</td></tr>' . PHP_EOL;
    }
    ?>
    </tbody>
</table>
<hr>
<div>
    <form name="addCommentFrm">
        <h4>Добавить комментарий</h4>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="creatorEmail">Электронная почта</label>
                <input type="email" class="form-control" id="creatorEmail" name="creator_email" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">Поле обязательно для заполнения!</small>
            </div>
            <div class="form-group col-md-6">
                <label for="createdAt">Дата создания комментария</label>
                <input type="date" class="form-control" id="createdAt" name="created_at" aria-describedby="dateHelp">
                <small id="emailHelp" class="form-text text-muted">Дата создания комментария (выбирается создателем)</small>
            </div>
        </div>
        <div class="form-group">
            <label for="messageText">Комментарий</label>
            <textarea class="form-control" id="messageText" name="message_text"></textarea>
        </div>
        <button type="button" class="btn btn-primary">Отправить</button>
        <button type="button" class="btn btn-outline-secondary">Очистить</button>
    </form>
</div>
<div class="modal" id="delConfirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Удаление комментария</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Вы уверены? Подтвердите или отмените удаление.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="modalDelBtn">Удалить</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
    $(function() {
        let delCommentDialog = $('#delConfirmModal');
        let delIndx = null;
        $('.js-del-comment').on('click', function() {
            delCommentDialog.modal('toggle', $(this));
        });

        delCommentDialog.on('show.bs.modal', function (e) {
            delIndx = $(e.relatedTarget).parent().parent().data('line');
        });

        $('#modalDelBtn').on('click', function() {
            $.ajax({
                url: "/del",
                type: "post",
                data: {id:delIndx},
                dataType: "json",
                success: function(msg) {
                    delCommentDialog.modal('toggle');
                    if(msg.status === 'OK') {
                        $("tr[data-line='" + delIndx +"']").remove();
                    }else{
                        alert(msg.text);
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
