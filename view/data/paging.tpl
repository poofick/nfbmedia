<?php 
$count_pages = isset($count_pages) && (int)$count_pages > 0 ? (int)$count_pages : 0;
$current_page = isset($current_page) && (int)$current_page > 0 ? (int)$current_page : 1;	
?>

<?php if($count_pages > 1): ?>
	<div class="paging">
		<?php $this->render('data/pagingItem', array('index' => $current_page-1, 'current_page' => $current_page, 'count_pages' => $count_pages, 'title' => 'Prev')); ?>
		
		<?php for($i=$current_page-5; $i<=$current_page+5; $i++): ?>
			<?php $i >=1 && $i <=$count_pages && $this->render('data/pagingItem', array('index' => $i, 'current_page' => $current_page, 'count_pages' => $count_pages)); ?>
		<?php endfor; ?>
		
		<?php $this->render('data/pagingItem', array('index' => $current_page+1, 'current_page' => $current_page, 'count_pages' => $count_pages, 'title' => 'Next')); ?>
	</div>
<?php endif; ?>