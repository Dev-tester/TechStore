<script type="text/javascript">
	BX.addCustomEvent(BX.adminMenu, 'onMenuChange', BX.delegate(BX.adminFav.onMenuChange, this));
</script>

<?php 
$favMenu = new CBXFavAdmMenu;
$favMenuText = GetMessage("MAIN_PR_ADMIN_FAV");
$favMenuItems = $favMenu->GenerateItems();
?>