<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 * @var array $link
 */

$pager->setSurroundCount(2);
?>
<nav aria-label="Pagination">
	<ul class="pagination justify-content-center">
		<?php if ($pager->hasPrevious()) : ?>
			<li class="page-item">
				<a href="<?= $pager->getFirst() ?>" aria-label="В начало" class="page-link">
					<span aria-hidden="true">Первая</span>
				</a>
			</li>
			<li>
				<a href="<?= $pager->getPreviousPage() ?>" aria-label="Предыдущая" class="page-link">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li class="<?= $link['active'] ? 'page-item active' : 'page-item' ?>">
				<a href="<?= $link['uri'] ?>" class="page-link">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li>
				<a href="<?= $pager->getNextPage() ?>" aria-label="Следующая" class="page-link">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
			<li class="page-item">
				<a href="<?= $pager->getLast() ?>" aria-label="В конец" class="page-link">
					<span aria-hidden="true">Последняя</span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>