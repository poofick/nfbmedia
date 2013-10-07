<?php 
$count_pages = isset($count_pages) && (int)$count_pages > 0 ? (int)$count_pages : 0;
$current_page = isset($current_page) && (int)$current_page > 0 ? (int)$current_page : 1;	
?>

<?php if($count_pages > 1 && $index >= 1 && $index <= $count_pages): ?>
	<a href="<?php echo $this->build_url(array($this->controller, $this->action, $index), Request::get_query()); ?>" class="button <?php echo $index != $current_page ? 'grey' : ''; ?>"><?php echo isset($title) ? $title : $index; ?></a>
<?php endif; ?>	