<?php include_once "./layout/header.php"; ?>

<?php include_once "./layout/tree_layout.php"; ?>

<script>
treeAjaxSet('generateData2');
$( window ).resize(function() {
	goTopLink();
});
</script>

<?php include_once "./layout/footer.php"; ?> 
