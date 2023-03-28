<?php
/**
 * @var array $comments
 * @var array $selector sorting options
 * @var int $current_sort_order current chosen sorting order
 */
?>
<?= $this->extend('layout/main'); ?>
<?= $this->section('pageContent'); ?>
<div class="card text-white bg-secondary w-100 p-3 mt-3">
    <div class="card-body">
        <p class="card-text">Здесь размещен контент, который комментируют...</p>
    </div>
</div>
<hr>
<?php if (session()->has('flash_errors')) { ?>
	<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?php
        echo implode('<br>', session()->getFlashdata('flash_errors'));
        ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>
<table class="table" id="commentsTbl">
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
    if (!empty($comments)) {
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
        echo '<tr><td colspan="5">Записей не найдено!</td></tr>' . PHP_EOL;
    }
    ?>
    </tbody>
</table>
<div class="d-flex justify-content-center">
    <form class="mr-md-3" name="order_by_frm" id="orderByFrm" method="post" action="/sort">
        <select class="custom-select" name="order_by" id="orderBy">
            <?php
            foreach($selector as $k => $v) {
                echo '<option value="' . $k . '" ' .($k === $current_sort_order ? ' selected' :''). '>' . $v . '</option>'.PHP_EOL;
            }
            ?>
        </select>
    </form>
	<?php if (!empty($pager)) {
        $page_path='/';
        $pager->setPath($page_path);
        echo $pager->links('default', 'bootstrap');
	}
	?>
</div>
<hr>
<div>
    <form class="no-sbm-by-enter" name="add_comment_frm" id="addCommentFrm" method="post" action="/add">
        <h4>Добавить комментарий</h4>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="creatorEmail">Электронная почта</label>
                <input type="email" class="form-control" id="creatorEmail" name="creator_email" aria-describedby="emailHelp" required>
                <small id="emailHelp" class="form-text text-muted">Поле обязательно для заполнения!</small>
            </div>
            <div class="form-group col-md-6">
                <label for="createdAt">Дата создания комментария</label>
                <input type="date" class="form-control" id="createdAt" name="created_at" aria-describedby="dateHelp" max="<?= strftime('%Y-%m-%d'); ?>" required>
                <small id="dateHelp" class="form-text text-muted">Дата создания комментария (выбирается создателем)</small>
            </div>
        </div>
        <div class="form-group">
            <label for="messageText">Комментарий</label>
            <textarea class="form-control" id="messageText" name="message_text" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" id="addCommentSbmBtn">Отправить</button>
        <button type="reset" class="btn btn-outline-secondary" id="addCommentResetBtn">Очистить</button>
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
        let delIndex = null;
        let commentForm = $('#addCommentFrm');

        $('.js-del-comment').on('click', function() {
            delCommentDialog.modal('toggle', $(this));
        });

        delCommentDialog.on('show.bs.modal', function (e) {
            delIndex = $(e.relatedTarget).parent().parent().data('line');
        });

        $('#modalDelBtn').on('click', function() {
            $.ajax({
                url: '/del',
                type: 'post',
                data: {id:delIndex},
                dataType: 'json',
                success: function(msg) {
                    delCommentDialog.modal('toggle');
                    if(msg.status === 'OK') {
                        $("tr[data-line='" + delIndex +"']").remove();
                    }else{
                        alert(msg.text);
                    }
                }
            });
        });

        /*$('#addCommentSbmBtn').on('click', function() {
            if(!$('#creatorEmail').val().trim().length || !$('#createdAt').val().trim().length) {
                return false;
            }else{
                commentForm.submit();
            }
        });*/

        $('#orderBy').on('change', function() {
           $('#orderByFrm').submit();
        });
    });
    $(document).on('keypress', '.no-sbm-by-enter', function(e){
        if(e.which === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>
<?= $this->endSection(); ?>
