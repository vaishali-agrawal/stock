<?php include_once "usersinfo.php" ?>
<?php

// Create page object
if (!isset($view_sales_details_grid)) $view_sales_details_grid = new cview_sales_details_grid();

// Page init
$view_sales_details_grid->Page_Init();

// Page main
$view_sales_details_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_sales_details_grid->Page_Render();
?>
<?php if ($view_sales_details->Export == "") { ?>
<script type="text/javascript">

// Form object
var fview_sales_detailsgrid = new ew_Form("fview_sales_detailsgrid", "grid");
fview_sales_detailsgrid.FormKeyCountName = '<?php echo $view_sales_details_grid->FormKeyCountName ?>';

// Validate form
fview_sales_detailsgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_Sales_Number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_sales_details->Sales_Number->FldCaption(), $view_sales_details->Sales_Number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Supplier_Number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_sales_details->Supplier_Number->FldCaption(), $view_sales_details->Supplier_Number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Stock_Item");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_sales_details->Stock_Item->FldCaption(), $view_sales_details->Stock_Item->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Sales_Quantity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_sales_details->Sales_Quantity->FldCaption(), $view_sales_details->Sales_Quantity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Purchasing_Price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_sales_details->Purchasing_Price->FldCaption(), $view_sales_details->Purchasing_Price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Sales_Price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_sales_details->Sales_Price->FldCaption(), $view_sales_details->Sales_Price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Sales_Total_Amount");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_sales_details->Sales_Total_Amount->FldCaption(), $view_sales_details->Sales_Total_Amount->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fview_sales_detailsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Sales_Number", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Supplier_Number", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Stock_Item", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Sales_Quantity", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Purchasing_Price", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Sales_Price", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Sales_Total_Amount", false)) return false;
	return true;
}

// Form_CustomValidate event
fview_sales_detailsgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_sales_detailsgrid.ValidateRequired = true;
<?php } else { ?>
fview_sales_detailsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_sales_detailsgrid.Lists["x_Supplier_Number"] = {"LinkField":"x_Supplier_Number","Ajax":true,"AutoFill":false,"DisplayFields":["x_Supplier_Name","","",""],"ParentFields":[],"ChildFields":["x_Stock_Item"],"FilterFields":[],"Options":[],"Template":""};
fview_sales_detailsgrid.Lists["x_Stock_Item"] = {"LinkField":"x_Stock_Number","Ajax":true,"AutoFill":true,"DisplayFields":["x_Stock_Name","","",""],"ParentFields":["x_Supplier_Number"],"ChildFields":[],"FilterFields":["x_Supplier_Number"],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($view_sales_details->CurrentAction == "gridadd") {
	if ($view_sales_details->CurrentMode == "copy") {
		$bSelectLimit = $view_sales_details_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$view_sales_details_grid->TotalRecs = $view_sales_details->SelectRecordCount();
			$view_sales_details_grid->Recordset = $view_sales_details_grid->LoadRecordset($view_sales_details_grid->StartRec-1, $view_sales_details_grid->DisplayRecs);
		} else {
			if ($view_sales_details_grid->Recordset = $view_sales_details_grid->LoadRecordset())
				$view_sales_details_grid->TotalRecs = $view_sales_details_grid->Recordset->RecordCount();
		}
		$view_sales_details_grid->StartRec = 1;
		$view_sales_details_grid->DisplayRecs = $view_sales_details_grid->TotalRecs;
	} else {
		$view_sales_details->CurrentFilter = "0=1";
		$view_sales_details_grid->StartRec = 1;
		$view_sales_details_grid->DisplayRecs = $view_sales_details->GridAddRowCount;
	}
	$view_sales_details_grid->TotalRecs = $view_sales_details_grid->DisplayRecs;
	$view_sales_details_grid->StopRec = $view_sales_details_grid->DisplayRecs;
} else {
	$bSelectLimit = $view_sales_details_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($view_sales_details_grid->TotalRecs <= 0)
			$view_sales_details_grid->TotalRecs = $view_sales_details->SelectRecordCount();
	} else {
		if (!$view_sales_details_grid->Recordset && ($view_sales_details_grid->Recordset = $view_sales_details_grid->LoadRecordset()))
			$view_sales_details_grid->TotalRecs = $view_sales_details_grid->Recordset->RecordCount();
	}
	$view_sales_details_grid->StartRec = 1;
	$view_sales_details_grid->DisplayRecs = $view_sales_details_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$view_sales_details_grid->Recordset = $view_sales_details_grid->LoadRecordset($view_sales_details_grid->StartRec-1, $view_sales_details_grid->DisplayRecs);

	// Set no record found message
	if ($view_sales_details->CurrentAction == "" && $view_sales_details_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_sales_details_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_sales_details_grid->SearchWhere == "0=101")
			$view_sales_details_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_sales_details_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$view_sales_details_grid->RenderOtherOptions();
?>
<?php $view_sales_details_grid->ShowPageHeader(); ?>
<?php
$view_sales_details_grid->ShowMessage();
?>
<?php if ($view_sales_details_grid->TotalRecs > 0 || $view_sales_details->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fview_sales_detailsgrid" class="ewForm form-inline">
<?php if ($view_sales_details_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($view_sales_details_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_view_sales_details" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_view_sales_detailsgrid" class="table ewTable">
<?php echo $view_sales_details->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_sales_details_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view_sales_details_grid->RenderListOptions();

// Render list options (header, left)
$view_sales_details_grid->ListOptions->Render("header", "left");
?>
<?php if ($view_sales_details->Sales_Number->Visible) { // Sales_Number ?>
	<?php if ($view_sales_details->SortUrl($view_sales_details->Sales_Number) == "") { ?>
		<th data-name="Sales_Number"><div id="elh_view_sales_details_Sales_Number" class="view_sales_details_Sales_Number"><div class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sales_Number"><div><div id="elh_view_sales_details_Sales_Number" class="view_sales_details_Sales_Number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Number->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_sales_details->Sales_Number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_sales_details->Sales_Number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_sales_details->Supplier_Number->Visible) { // Supplier_Number ?>
	<?php if ($view_sales_details->SortUrl($view_sales_details->Supplier_Number) == "") { ?>
		<th data-name="Supplier_Number"><div id="elh_view_sales_details_Supplier_Number" class="view_sales_details_Supplier_Number"><div class="ewTableHeaderCaption"><?php echo $view_sales_details->Supplier_Number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Supplier_Number"><div><div id="elh_view_sales_details_Supplier_Number" class="view_sales_details_Supplier_Number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_sales_details->Supplier_Number->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_sales_details->Supplier_Number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_sales_details->Supplier_Number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_sales_details->Stock_Item->Visible) { // Stock_Item ?>
	<?php if ($view_sales_details->SortUrl($view_sales_details->Stock_Item) == "") { ?>
		<th data-name="Stock_Item"><div id="elh_view_sales_details_Stock_Item" class="view_sales_details_Stock_Item"><div class="ewTableHeaderCaption"><?php echo $view_sales_details->Stock_Item->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Stock_Item"><div><div id="elh_view_sales_details_Stock_Item" class="view_sales_details_Stock_Item">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_sales_details->Stock_Item->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_sales_details->Stock_Item->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_sales_details->Stock_Item->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_sales_details->Sales_Quantity->Visible) { // Sales_Quantity ?>
	<?php if ($view_sales_details->SortUrl($view_sales_details->Sales_Quantity) == "") { ?>
		<th data-name="Sales_Quantity"><div id="elh_view_sales_details_Sales_Quantity" class="view_sales_details_Sales_Quantity"><div class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Quantity->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sales_Quantity"><div><div id="elh_view_sales_details_Sales_Quantity" class="view_sales_details_Sales_Quantity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Quantity->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_sales_details->Sales_Quantity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_sales_details->Sales_Quantity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_sales_details->Purchasing_Price->Visible) { // Purchasing_Price ?>
	<?php if ($view_sales_details->SortUrl($view_sales_details->Purchasing_Price) == "") { ?>
		<th data-name="Purchasing_Price"><div id="elh_view_sales_details_Purchasing_Price" class="view_sales_details_Purchasing_Price"><div class="ewTableHeaderCaption"><?php echo $view_sales_details->Purchasing_Price->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Purchasing_Price"><div><div id="elh_view_sales_details_Purchasing_Price" class="view_sales_details_Purchasing_Price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_sales_details->Purchasing_Price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_sales_details->Purchasing_Price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_sales_details->Purchasing_Price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_sales_details->Sales_Price->Visible) { // Sales_Price ?>
	<?php if ($view_sales_details->SortUrl($view_sales_details->Sales_Price) == "") { ?>
		<th data-name="Sales_Price"><div id="elh_view_sales_details_Sales_Price" class="view_sales_details_Sales_Price"><div class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Price->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sales_Price"><div><div id="elh_view_sales_details_Sales_Price" class="view_sales_details_Sales_Price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_sales_details->Sales_Price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_sales_details->Sales_Price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_sales_details->Sales_Total_Amount->Visible) { // Sales_Total_Amount ?>
	<?php if ($view_sales_details->SortUrl($view_sales_details->Sales_Total_Amount) == "") { ?>
		<th data-name="Sales_Total_Amount"><div id="elh_view_sales_details_Sales_Total_Amount" class="view_sales_details_Sales_Total_Amount"><div class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Total_Amount->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sales_Total_Amount"><div><div id="elh_view_sales_details_Sales_Total_Amount" class="view_sales_details_Sales_Total_Amount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_sales_details->Sales_Total_Amount->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_sales_details->Sales_Total_Amount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_sales_details->Sales_Total_Amount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_sales_details_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$view_sales_details_grid->StartRec = 1;
$view_sales_details_grid->StopRec = $view_sales_details_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($view_sales_details_grid->FormKeyCountName) && ($view_sales_details->CurrentAction == "gridadd" || $view_sales_details->CurrentAction == "gridedit" || $view_sales_details->CurrentAction == "F")) {
		$view_sales_details_grid->KeyCount = $objForm->GetValue($view_sales_details_grid->FormKeyCountName);
		$view_sales_details_grid->StopRec = $view_sales_details_grid->StartRec + $view_sales_details_grid->KeyCount - 1;
	}
}
$view_sales_details_grid->RecCnt = $view_sales_details_grid->StartRec - 1;
if ($view_sales_details_grid->Recordset && !$view_sales_details_grid->Recordset->EOF) {
	$view_sales_details_grid->Recordset->MoveFirst();
	$bSelectLimit = $view_sales_details_grid->UseSelectLimit;
	if (!$bSelectLimit && $view_sales_details_grid->StartRec > 1)
		$view_sales_details_grid->Recordset->Move($view_sales_details_grid->StartRec - 1);
} elseif (!$view_sales_details->AllowAddDeleteRow && $view_sales_details_grid->StopRec == 0) {
	$view_sales_details_grid->StopRec = $view_sales_details->GridAddRowCount;
}

// Initialize aggregate
$view_sales_details->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_sales_details->ResetAttrs();
$view_sales_details_grid->RenderRow();
if ($view_sales_details->CurrentAction == "gridadd")
	$view_sales_details_grid->RowIndex = 0;
if ($view_sales_details->CurrentAction == "gridedit")
	$view_sales_details_grid->RowIndex = 0;
while ($view_sales_details_grid->RecCnt < $view_sales_details_grid->StopRec) {
	$view_sales_details_grid->RecCnt++;
	if (intval($view_sales_details_grid->RecCnt) >= intval($view_sales_details_grid->StartRec)) {
		$view_sales_details_grid->RowCnt++;
		if ($view_sales_details->CurrentAction == "gridadd" || $view_sales_details->CurrentAction == "gridedit" || $view_sales_details->CurrentAction == "F") {
			$view_sales_details_grid->RowIndex++;
			$objForm->Index = $view_sales_details_grid->RowIndex;
			if ($objForm->HasValue($view_sales_details_grid->FormActionName))
				$view_sales_details_grid->RowAction = strval($objForm->GetValue($view_sales_details_grid->FormActionName));
			elseif ($view_sales_details->CurrentAction == "gridadd")
				$view_sales_details_grid->RowAction = "insert";
			else
				$view_sales_details_grid->RowAction = "";
		}

		// Set up key count
		$view_sales_details_grid->KeyCount = $view_sales_details_grid->RowIndex;

		// Init row class and style
		$view_sales_details->ResetAttrs();
		$view_sales_details->CssClass = "";
		if ($view_sales_details->CurrentAction == "gridadd") {
			if ($view_sales_details->CurrentMode == "copy") {
				$view_sales_details_grid->LoadRowValues($view_sales_details_grid->Recordset); // Load row values
				$view_sales_details_grid->SetRecordKey($view_sales_details_grid->RowOldKey, $view_sales_details_grid->Recordset); // Set old record key
			} else {
				$view_sales_details_grid->LoadDefaultValues(); // Load default values
				$view_sales_details_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$view_sales_details_grid->LoadRowValues($view_sales_details_grid->Recordset); // Load row values
		}
		$view_sales_details->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($view_sales_details->CurrentAction == "gridadd") // Grid add
			$view_sales_details->RowType = EW_ROWTYPE_ADD; // Render add
		if ($view_sales_details->CurrentAction == "gridadd" && $view_sales_details->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$view_sales_details_grid->RestoreCurrentRowFormValues($view_sales_details_grid->RowIndex); // Restore form values
		if ($view_sales_details->CurrentAction == "gridedit") { // Grid edit
			if ($view_sales_details->EventCancelled) {
				$view_sales_details_grid->RestoreCurrentRowFormValues($view_sales_details_grid->RowIndex); // Restore form values
			}
			if ($view_sales_details_grid->RowAction == "insert")
				$view_sales_details->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$view_sales_details->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($view_sales_details->CurrentAction == "gridedit" && ($view_sales_details->RowType == EW_ROWTYPE_EDIT || $view_sales_details->RowType == EW_ROWTYPE_ADD) && $view_sales_details->EventCancelled) // Update failed
			$view_sales_details_grid->RestoreCurrentRowFormValues($view_sales_details_grid->RowIndex); // Restore form values
		if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) // Edit row
			$view_sales_details_grid->EditRowCnt++;
		if ($view_sales_details->CurrentAction == "F") // Confirm row
			$view_sales_details_grid->RestoreCurrentRowFormValues($view_sales_details_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$view_sales_details->RowAttrs = array_merge($view_sales_details->RowAttrs, array('data-rowindex'=>$view_sales_details_grid->RowCnt, 'id'=>'r' . $view_sales_details_grid->RowCnt . '_view_sales_details', 'data-rowtype'=>$view_sales_details->RowType));

		// Render row
		$view_sales_details_grid->RenderRow();

		// Render list options
		$view_sales_details_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($view_sales_details_grid->RowAction <> "delete" && $view_sales_details_grid->RowAction <> "insertdelete" && !($view_sales_details_grid->RowAction == "insert" && $view_sales_details->CurrentAction == "F" && $view_sales_details_grid->EmptyRow())) {
?>
	<tr<?php echo $view_sales_details->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_sales_details_grid->ListOptions->Render("body", "left", $view_sales_details_grid->RowCnt);
?>
	<?php if ($view_sales_details->Sales_Number->Visible) { // Sales_Number ?>
		<td data-name="Sales_Number"<?php echo $view_sales_details->Sales_Number->CellAttributes() ?>>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Number" class="form-group view_sales_details_Sales_Number">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Number->EditValue ?>"<?php echo $view_sales_details->Sales_Number->EditAttributes() ?>>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Number" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Number" class="form-group view_sales_details_Sales_Number">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Number->EditValue ?>"<?php echo $view_sales_details->Sales_Number->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Number" class="view_sales_details_Sales_Number">
<span<?php echo $view_sales_details->Sales_Number->ViewAttributes() ?>>
<?php echo $view_sales_details->Sales_Number->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->FormValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Number" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->OldValue) ?>">
<?php } ?>
<a id="<?php echo $view_sales_details_grid->PageObjName . "_row_" . $view_sales_details_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_ID" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_ID" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_ID" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_ID->CurrentValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_ID" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_ID" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_ID" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_ID->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT || $view_sales_details->CurrentMode == "edit") { ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_ID" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_ID" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_ID" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_ID->CurrentValue) ?>">
<?php } ?>
	<?php if ($view_sales_details->Supplier_Number->Visible) { // Supplier_Number ?>
		<td data-name="Supplier_Number"<?php echo $view_sales_details->Supplier_Number->CellAttributes() ?>>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Supplier_Number" class="form-group view_sales_details_Supplier_Number">
<?php $view_sales_details->Supplier_Number->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$view_sales_details->Supplier_Number->EditAttrs["onchange"]; ?>
<select data-table="view_sales_details" data-field="x_Supplier_Number" data-value-separator="<?php echo ew_HtmlEncode(is_array($view_sales_details->Supplier_Number->DisplayValueSeparator) ? json_encode($view_sales_details->Supplier_Number->DisplayValueSeparator) : $view_sales_details->Supplier_Number->DisplayValueSeparator) ?>" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number"<?php echo $view_sales_details->Supplier_Number->EditAttributes() ?>>
<?php
if (is_array($view_sales_details->Supplier_Number->EditValue)) {
	$arwrk = $view_sales_details->Supplier_Number->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($view_sales_details->Supplier_Number->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $view_sales_details->Supplier_Number->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($view_sales_details->Supplier_Number->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->CurrentValue) ?>" selected><?php echo $view_sales_details->Supplier_Number->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $view_sales_details->Supplier_Number->OldValue = "";
?>
</select>
<?php
switch (@$gsLanguage) {
	case "id":
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
}
$lookuptblfilter = "`Is_Stock_Available` = 'Y'";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$view_sales_details->Supplier_Number->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$view_sales_details->Supplier_Number->LookupFilters += array("f0" => "`Supplier_Number` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$view_sales_details->Lookup_Selecting($view_sales_details->Supplier_Number, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Supplier_ID`";
if ($sSqlWrk <> "") $view_sales_details->Supplier_Number->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo $view_sales_details->Supplier_Number->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Supplier_Number" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Supplier_Number" class="form-group view_sales_details_Supplier_Number">
<?php $view_sales_details->Supplier_Number->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$view_sales_details->Supplier_Number->EditAttrs["onchange"]; ?>
<select data-table="view_sales_details" data-field="x_Supplier_Number" data-value-separator="<?php echo ew_HtmlEncode(is_array($view_sales_details->Supplier_Number->DisplayValueSeparator) ? json_encode($view_sales_details->Supplier_Number->DisplayValueSeparator) : $view_sales_details->Supplier_Number->DisplayValueSeparator) ?>" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number"<?php echo $view_sales_details->Supplier_Number->EditAttributes() ?>>
<?php
if (is_array($view_sales_details->Supplier_Number->EditValue)) {
	$arwrk = $view_sales_details->Supplier_Number->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($view_sales_details->Supplier_Number->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $view_sales_details->Supplier_Number->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($view_sales_details->Supplier_Number->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->CurrentValue) ?>" selected><?php echo $view_sales_details->Supplier_Number->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $view_sales_details->Supplier_Number->OldValue = "";
?>
</select>
<?php
switch (@$gsLanguage) {
	case "id":
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
}
$lookuptblfilter = "`Is_Stock_Available` = 'Y'";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$view_sales_details->Supplier_Number->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$view_sales_details->Supplier_Number->LookupFilters += array("f0" => "`Supplier_Number` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$view_sales_details->Lookup_Selecting($view_sales_details->Supplier_Number, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Supplier_ID`";
if ($sSqlWrk <> "") $view_sales_details->Supplier_Number->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo $view_sales_details->Supplier_Number->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Supplier_Number" class="view_sales_details_Supplier_Number">
<span<?php echo $view_sales_details->Supplier_Number->ViewAttributes() ?>>
<?php echo $view_sales_details->Supplier_Number->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Supplier_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->FormValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Supplier_Number" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_sales_details->Stock_Item->Visible) { // Stock_Item ?>
		<td data-name="Stock_Item"<?php echo $view_sales_details->Stock_Item->CellAttributes() ?>>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($view_sales_details->Stock_Item->getSessionValue() <> "") { ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Stock_Item" class="form-group view_sales_details_Stock_Item">
<span<?php echo $view_sales_details->Stock_Item->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Stock_Item->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Stock_Item" class="form-group view_sales_details_Stock_Item">
<?php $view_sales_details->Stock_Item->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$view_sales_details->Stock_Item->EditAttrs["onchange"]; ?>
<select data-table="view_sales_details" data-field="x_Stock_Item" data-value-separator="<?php echo ew_HtmlEncode(is_array($view_sales_details->Stock_Item->DisplayValueSeparator) ? json_encode($view_sales_details->Stock_Item->DisplayValueSeparator) : $view_sales_details->Stock_Item->DisplayValueSeparator) ?>" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item"<?php echo $view_sales_details->Stock_Item->EditAttributes() ?>>
<?php
if (is_array($view_sales_details->Stock_Item->EditValue)) {
	$arwrk = $view_sales_details->Stock_Item->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($view_sales_details->Stock_Item->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $view_sales_details->Stock_Item->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($view_sales_details->Stock_Item->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->CurrentValue) ?>" selected><?php echo $view_sales_details->Stock_Item->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $view_sales_details->Stock_Item->OldValue = "";
?>
</select>
<?php
switch (@$gsLanguage) {
	case "id":
		$sSqlWrk = "SELECT `Stock_Number`, `Stock_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_stock_items`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `Stock_Number`, `Stock_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_stock_items`";
		$sWhereWrk = "{filter}";
		break;
}
$lookuptblfilter = "`Quantity` > 0";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$view_sales_details->Stock_Item->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$view_sales_details->Stock_Item->LookupFilters += array("f0" => "`Stock_Number` = {filter_value}", "t0" => "200", "fn0" => "");
$view_sales_details->Stock_Item->LookupFilters += array("f1" => "`Supplier_Number` IN ({filter_value})", "t1" => "200", "fn1" => "");
$sSqlWrk = "";
$view_sales_details->Lookup_Selecting($view_sales_details->Stock_Item, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $view_sales_details->Stock_Item->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo $view_sales_details->Stock_Item->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="ln_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price,x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price">
</span>
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Stock_Item" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($view_sales_details->Stock_Item->getSessionValue() <> "") { ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Stock_Item" class="form-group view_sales_details_Stock_Item">
<span<?php echo $view_sales_details->Stock_Item->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Stock_Item->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Stock_Item" class="form-group view_sales_details_Stock_Item">
<?php $view_sales_details->Stock_Item->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$view_sales_details->Stock_Item->EditAttrs["onchange"]; ?>
<select data-table="view_sales_details" data-field="x_Stock_Item" data-value-separator="<?php echo ew_HtmlEncode(is_array($view_sales_details->Stock_Item->DisplayValueSeparator) ? json_encode($view_sales_details->Stock_Item->DisplayValueSeparator) : $view_sales_details->Stock_Item->DisplayValueSeparator) ?>" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item"<?php echo $view_sales_details->Stock_Item->EditAttributes() ?>>
<?php
if (is_array($view_sales_details->Stock_Item->EditValue)) {
	$arwrk = $view_sales_details->Stock_Item->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($view_sales_details->Stock_Item->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $view_sales_details->Stock_Item->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($view_sales_details->Stock_Item->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->CurrentValue) ?>" selected><?php echo $view_sales_details->Stock_Item->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $view_sales_details->Stock_Item->OldValue = "";
?>
</select>
<?php
switch (@$gsLanguage) {
	case "id":
		$sSqlWrk = "SELECT `Stock_Number`, `Stock_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_stock_items`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `Stock_Number`, `Stock_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_stock_items`";
		$sWhereWrk = "{filter}";
		break;
}
$lookuptblfilter = "`Quantity` > 0";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$view_sales_details->Stock_Item->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$view_sales_details->Stock_Item->LookupFilters += array("f0" => "`Stock_Number` = {filter_value}", "t0" => "200", "fn0" => "");
$view_sales_details->Stock_Item->LookupFilters += array("f1" => "`Supplier_Number` IN ({filter_value})", "t1" => "200", "fn1" => "");
$sSqlWrk = "";
$view_sales_details->Lookup_Selecting($view_sales_details->Stock_Item, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $view_sales_details->Stock_Item->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo $view_sales_details->Stock_Item->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="ln_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price,x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price">
</span>
<?php } ?>
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Stock_Item" class="view_sales_details_Stock_Item">
<span<?php echo $view_sales_details->Stock_Item->ViewAttributes() ?>>
<?php echo $view_sales_details->Stock_Item->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->FormValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Stock_Item" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_sales_details->Sales_Quantity->Visible) { // Sales_Quantity ?>
		<td data-name="Sales_Quantity"<?php echo $view_sales_details->Sales_Quantity->CellAttributes() ?>>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Quantity" class="form-group view_sales_details_Sales_Quantity">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Quantity" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Quantity->EditValue ?>"<?php echo $view_sales_details->Sales_Quantity->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Quantity->ReadOnly && !$view_sales_details->Sales_Quantity->Disabled && @$view_sales_details->Sales_Quantity->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Quantity->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '0', aForm: false});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Quantity" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Quantity" class="form-group view_sales_details_Sales_Quantity">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Quantity" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Quantity->EditValue ?>"<?php echo $view_sales_details->Sales_Quantity->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Quantity->ReadOnly && !$view_sales_details->Sales_Quantity->Disabled && @$view_sales_details->Sales_Quantity->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Quantity->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '0', aForm: false});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Quantity" class="view_sales_details_Sales_Quantity">
<span<?php echo $view_sales_details->Sales_Quantity->ViewAttributes() ?>>
<?php echo $view_sales_details->Sales_Quantity->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Quantity" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->FormValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Quantity" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_sales_details->Purchasing_Price->Visible) { // Purchasing_Price ?>
		<td data-name="Purchasing_Price"<?php echo $view_sales_details->Purchasing_Price->CellAttributes() ?>>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Purchasing_Price" class="form-group view_sales_details_Purchasing_Price">
<input type="text" data-table="view_sales_details" data-field="x_Purchasing_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Purchasing_Price->EditValue ?>"<?php echo $view_sales_details->Purchasing_Price->EditAttributes() ?>>
<?php if (!$view_sales_details->Purchasing_Price->ReadOnly && !$view_sales_details->Purchasing_Price->Disabled && @$view_sales_details->Purchasing_Price->EditAttrs["readonly"] == "" && @$view_sales_details->Purchasing_Price->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Purchasing_Price" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Purchasing_Price" class="form-group view_sales_details_Purchasing_Price">
<input type="text" data-table="view_sales_details" data-field="x_Purchasing_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Purchasing_Price->EditValue ?>"<?php echo $view_sales_details->Purchasing_Price->EditAttributes() ?>>
<?php if (!$view_sales_details->Purchasing_Price->ReadOnly && !$view_sales_details->Purchasing_Price->Disabled && @$view_sales_details->Purchasing_Price->EditAttrs["readonly"] == "" && @$view_sales_details->Purchasing_Price->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Purchasing_Price" class="view_sales_details_Purchasing_Price">
<span<?php echo $view_sales_details->Purchasing_Price->ViewAttributes() ?>>
<?php echo $view_sales_details->Purchasing_Price->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Purchasing_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->FormValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Purchasing_Price" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_sales_details->Sales_Price->Visible) { // Sales_Price ?>
		<td data-name="Sales_Price"<?php echo $view_sales_details->Sales_Price->CellAttributes() ?>>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Price" class="form-group view_sales_details_Sales_Price">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Price->EditValue ?>"<?php echo $view_sales_details->Sales_Price->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Price->ReadOnly && !$view_sales_details->Sales_Price->Disabled && @$view_sales_details->Sales_Price->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Price->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Price" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Price" class="form-group view_sales_details_Sales_Price">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Price->EditValue ?>"<?php echo $view_sales_details->Sales_Price->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Price->ReadOnly && !$view_sales_details->Sales_Price->Disabled && @$view_sales_details->Sales_Price->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Price->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Price" class="view_sales_details_Sales_Price">
<span<?php echo $view_sales_details->Sales_Price->ViewAttributes() ?>>
<?php echo $view_sales_details->Sales_Price->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->FormValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Price" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($view_sales_details->Sales_Total_Amount->Visible) { // Sales_Total_Amount ?>
		<td data-name="Sales_Total_Amount"<?php echo $view_sales_details->Sales_Total_Amount->CellAttributes() ?>>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Total_Amount" class="form-group view_sales_details_Sales_Total_Amount">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Total_Amount->EditValue ?>"<?php echo $view_sales_details->Sales_Total_Amount->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Total_Amount->ReadOnly && !$view_sales_details->Sales_Total_Amount->Disabled && @$view_sales_details->Sales_Total_Amount->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Total_Amount->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->OldValue) ?>">
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Total_Amount" class="form-group view_sales_details_Sales_Total_Amount">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Total_Amount->EditValue ?>"<?php echo $view_sales_details->Sales_Total_Amount->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Total_Amount->ReadOnly && !$view_sales_details->Sales_Total_Amount->Disabled && @$view_sales_details->Sales_Total_Amount->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Total_Amount->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $view_sales_details_grid->RowCnt ?>_view_sales_details_Sales_Total_Amount" class="view_sales_details_Sales_Total_Amount">
<span<?php echo $view_sales_details->Sales_Total_Amount->ViewAttributes() ?>>
<?php echo $view_sales_details->Sales_Total_Amount->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->FormValue) ?>">
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_sales_details_grid->ListOptions->Render("body", "right", $view_sales_details_grid->RowCnt);
?>
	</tr>
<?php if ($view_sales_details->RowType == EW_ROWTYPE_ADD || $view_sales_details->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fview_sales_detailsgrid.UpdateOpts(<?php echo $view_sales_details_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($view_sales_details->CurrentAction <> "gridadd" || $view_sales_details->CurrentMode == "copy")
		if (!$view_sales_details_grid->Recordset->EOF) $view_sales_details_grid->Recordset->MoveNext();
}
?>
<?php
	if ($view_sales_details->CurrentMode == "add" || $view_sales_details->CurrentMode == "copy" || $view_sales_details->CurrentMode == "edit") {
		$view_sales_details_grid->RowIndex = '$rowindex$';
		$view_sales_details_grid->LoadDefaultValues();

		// Set row properties
		$view_sales_details->ResetAttrs();
		$view_sales_details->RowAttrs = array_merge($view_sales_details->RowAttrs, array('data-rowindex'=>$view_sales_details_grid->RowIndex, 'id'=>'r0_view_sales_details', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($view_sales_details->RowAttrs["class"], "ewTemplate");
		$view_sales_details->RowType = EW_ROWTYPE_ADD;

		// Render row
		$view_sales_details_grid->RenderRow();

		// Render list options
		$view_sales_details_grid->RenderListOptions();
		$view_sales_details_grid->StartRowCnt = 0;
?>
	<tr<?php echo $view_sales_details->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_sales_details_grid->ListOptions->Render("body", "left", $view_sales_details_grid->RowIndex);
?>
	<?php if ($view_sales_details->Sales_Number->Visible) { // Sales_Number ?>
		<td data-name="Sales_Number">
<?php if ($view_sales_details->CurrentAction <> "F") { ?>
<span id="el$rowindex$_view_sales_details_Sales_Number" class="form-group view_sales_details_Sales_Number">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Number->EditValue ?>"<?php echo $view_sales_details->Sales_Number->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Sales_Number" class="form-group view_sales_details_Sales_Number">
<span<?php echo $view_sales_details->Sales_Number->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Sales_Number->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Number" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Number->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_sales_details->Supplier_Number->Visible) { // Supplier_Number ?>
		<td data-name="Supplier_Number">
<?php if ($view_sales_details->CurrentAction <> "F") { ?>
<span id="el$rowindex$_view_sales_details_Supplier_Number" class="form-group view_sales_details_Supplier_Number">
<?php $view_sales_details->Supplier_Number->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$view_sales_details->Supplier_Number->EditAttrs["onchange"]; ?>
<select data-table="view_sales_details" data-field="x_Supplier_Number" data-value-separator="<?php echo ew_HtmlEncode(is_array($view_sales_details->Supplier_Number->DisplayValueSeparator) ? json_encode($view_sales_details->Supplier_Number->DisplayValueSeparator) : $view_sales_details->Supplier_Number->DisplayValueSeparator) ?>" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number"<?php echo $view_sales_details->Supplier_Number->EditAttributes() ?>>
<?php
if (is_array($view_sales_details->Supplier_Number->EditValue)) {
	$arwrk = $view_sales_details->Supplier_Number->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($view_sales_details->Supplier_Number->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $view_sales_details->Supplier_Number->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($view_sales_details->Supplier_Number->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->CurrentValue) ?>" selected><?php echo $view_sales_details->Supplier_Number->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $view_sales_details->Supplier_Number->OldValue = "";
?>
</select>
<?php
switch (@$gsLanguage) {
	case "id":
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `Supplier_Number`, `Supplier_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_suppliers`";
		$sWhereWrk = "";
		break;
}
$lookuptblfilter = "`Is_Stock_Available` = 'Y'";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$view_sales_details->Supplier_Number->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$view_sales_details->Supplier_Number->LookupFilters += array("f0" => "`Supplier_Number` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$view_sales_details->Lookup_Selecting($view_sales_details->Supplier_Number, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `Supplier_ID`";
if ($sSqlWrk <> "") $view_sales_details->Supplier_Number->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo $view_sales_details->Supplier_Number->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Supplier_Number" class="form-group view_sales_details_Supplier_Number">
<span<?php echo $view_sales_details->Supplier_Number->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Supplier_Number->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Supplier_Number" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Supplier_Number" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Supplier_Number" value="<?php echo ew_HtmlEncode($view_sales_details->Supplier_Number->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_sales_details->Stock_Item->Visible) { // Stock_Item ?>
		<td data-name="Stock_Item">
<?php if ($view_sales_details->CurrentAction <> "F") { ?>
<?php if ($view_sales_details->Stock_Item->getSessionValue() <> "") { ?>
<span id="el$rowindex$_view_sales_details_Stock_Item" class="form-group view_sales_details_Stock_Item">
<span<?php echo $view_sales_details->Stock_Item->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Stock_Item->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Stock_Item" class="form-group view_sales_details_Stock_Item">
<?php $view_sales_details->Stock_Item->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$view_sales_details->Stock_Item->EditAttrs["onchange"]; ?>
<select data-table="view_sales_details" data-field="x_Stock_Item" data-value-separator="<?php echo ew_HtmlEncode(is_array($view_sales_details->Stock_Item->DisplayValueSeparator) ? json_encode($view_sales_details->Stock_Item->DisplayValueSeparator) : $view_sales_details->Stock_Item->DisplayValueSeparator) ?>" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item"<?php echo $view_sales_details->Stock_Item->EditAttributes() ?>>
<?php
if (is_array($view_sales_details->Stock_Item->EditValue)) {
	$arwrk = $view_sales_details->Stock_Item->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($view_sales_details->Stock_Item->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $view_sales_details->Stock_Item->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($view_sales_details->Stock_Item->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->CurrentValue) ?>" selected><?php echo $view_sales_details->Stock_Item->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $view_sales_details->Stock_Item->OldValue = "";
?>
</select>
<?php
switch (@$gsLanguage) {
	case "id":
		$sSqlWrk = "SELECT `Stock_Number`, `Stock_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_stock_items`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `Stock_Number`, `Stock_Name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `a_stock_items`";
		$sWhereWrk = "{filter}";
		break;
}
$lookuptblfilter = "`Quantity` > 0";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$view_sales_details->Stock_Item->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$view_sales_details->Stock_Item->LookupFilters += array("f0" => "`Stock_Number` = {filter_value}", "t0" => "200", "fn0" => "");
$view_sales_details->Stock_Item->LookupFilters += array("f1" => "`Supplier_Number` IN ({filter_value})", "t1" => "200", "fn1" => "");
$sSqlWrk = "";
$view_sales_details->Lookup_Selecting($view_sales_details->Stock_Item, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $view_sales_details->Stock_Item->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="s_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo $view_sales_details->Stock_Item->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="ln_x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price,x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Stock_Item" class="form-group view_sales_details_Stock_Item">
<span<?php echo $view_sales_details->Stock_Item->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Stock_Item->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Stock_Item" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Stock_Item" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Stock_Item" value="<?php echo ew_HtmlEncode($view_sales_details->Stock_Item->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_sales_details->Sales_Quantity->Visible) { // Sales_Quantity ?>
		<td data-name="Sales_Quantity">
<?php if ($view_sales_details->CurrentAction <> "F") { ?>
<span id="el$rowindex$_view_sales_details_Sales_Quantity" class="form-group view_sales_details_Sales_Quantity">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Quantity" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Quantity->EditValue ?>"<?php echo $view_sales_details->Sales_Quantity->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Quantity->ReadOnly && !$view_sales_details->Sales_Quantity->Disabled && @$view_sales_details->Sales_Quantity->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Quantity->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '0', aForm: false});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Sales_Quantity" class="form-group view_sales_details_Sales_Quantity">
<span<?php echo $view_sales_details->Sales_Quantity->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Sales_Quantity->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Quantity" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Quantity" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Quantity" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Quantity->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_sales_details->Purchasing_Price->Visible) { // Purchasing_Price ?>
		<td data-name="Purchasing_Price">
<?php if ($view_sales_details->CurrentAction <> "F") { ?>
<span id="el$rowindex$_view_sales_details_Purchasing_Price" class="form-group view_sales_details_Purchasing_Price">
<input type="text" data-table="view_sales_details" data-field="x_Purchasing_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Purchasing_Price->EditValue ?>"<?php echo $view_sales_details->Purchasing_Price->EditAttributes() ?>>
<?php if (!$view_sales_details->Purchasing_Price->ReadOnly && !$view_sales_details->Purchasing_Price->Disabled && @$view_sales_details->Purchasing_Price->EditAttrs["readonly"] == "" && @$view_sales_details->Purchasing_Price->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Purchasing_Price" class="form-group view_sales_details_Purchasing_Price">
<span<?php echo $view_sales_details->Purchasing_Price->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Purchasing_Price->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Purchasing_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Purchasing_Price" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Purchasing_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Purchasing_Price->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_sales_details->Sales_Price->Visible) { // Sales_Price ?>
		<td data-name="Sales_Price">
<?php if ($view_sales_details->CurrentAction <> "F") { ?>
<span id="el$rowindex$_view_sales_details_Sales_Price" class="form-group view_sales_details_Sales_Price">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Price->EditValue ?>"<?php echo $view_sales_details->Sales_Price->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Price->ReadOnly && !$view_sales_details->Sales_Price->Disabled && @$view_sales_details->Sales_Price->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Price->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Sales_Price" class="form-group view_sales_details_Sales_Price">
<span<?php echo $view_sales_details->Sales_Price->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Sales_Price->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Price" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Price" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Price" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Price->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($view_sales_details->Sales_Total_Amount->Visible) { // Sales_Total_Amount ?>
		<td data-name="Sales_Total_Amount">
<?php if ($view_sales_details->CurrentAction <> "F") { ?>
<span id="el$rowindex$_view_sales_details_Sales_Total_Amount" class="form-group view_sales_details_Sales_Total_Amount">
<input type="text" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" size="30" placeholder="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->getPlaceHolder()) ?>" value="<?php echo $view_sales_details->Sales_Total_Amount->EditValue ?>"<?php echo $view_sales_details->Sales_Total_Amount->EditAttributes() ?>>
<?php if (!$view_sales_details->Sales_Total_Amount->ReadOnly && !$view_sales_details->Sales_Total_Amount->Disabled && @$view_sales_details->Sales_Total_Amount->EditAttrs["readonly"] == "" && @$view_sales_details->Sales_Total_Amount->EditAttrs["disabled"] == "") { ?>
<script type="text/javascript">
$('#x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount').autoNumeric('init', {aSep: ',', aDec: '.', mDec: '2', aForm: false});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_sales_details_Sales_Total_Amount" class="form-group view_sales_details_Sales_Total_Amount">
<span<?php echo $view_sales_details->Sales_Total_Amount->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_sales_details->Sales_Total_Amount->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="x<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_sales_details" data-field="x_Sales_Total_Amount" name="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" id="o<?php echo $view_sales_details_grid->RowIndex ?>_Sales_Total_Amount" value="<?php echo ew_HtmlEncode($view_sales_details->Sales_Total_Amount->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_sales_details_grid->ListOptions->Render("body", "right", $view_sales_details_grid->RowCnt);
?>
<script type="text/javascript">
fview_sales_detailsgrid.UpdateOpts(<?php echo $view_sales_details_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($view_sales_details->CurrentMode == "add" || $view_sales_details->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $view_sales_details_grid->FormKeyCountName ?>" id="<?php echo $view_sales_details_grid->FormKeyCountName ?>" value="<?php echo $view_sales_details_grid->KeyCount ?>">
<?php echo $view_sales_details_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($view_sales_details->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $view_sales_details_grid->FormKeyCountName ?>" id="<?php echo $view_sales_details_grid->FormKeyCountName ?>" value="<?php echo $view_sales_details_grid->KeyCount ?>">
<?php echo $view_sales_details_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($view_sales_details->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fview_sales_detailsgrid">
</div>
<?php

// Close recordset
if ($view_sales_details_grid->Recordset)
	$view_sales_details_grid->Recordset->Close();
?>
<?php if ($view_sales_details_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($view_sales_details_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($view_sales_details_grid->TotalRecs == 0 && $view_sales_details->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_sales_details_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view_sales_details->Export == "") { ?>
<script type="text/javascript">
fview_sales_detailsgrid.Init();
</script>
<?php } ?>
<?php
$view_sales_details_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$view_sales_details_grid->Page_Terminate();
?>
