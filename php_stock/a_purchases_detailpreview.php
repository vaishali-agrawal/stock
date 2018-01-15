<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "a_purchases_detailinfo.php" ?>
<?php include_once "a_purchasesinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$a_purchases_detail_preview = NULL; // Initialize page object first

class ca_purchases_detail_preview extends ca_purchases_detail {

	// Page ID
	var $PageID = 'preview';

	// Project ID
	var $ProjectID = "{B36B93AF-B58F-461B-B767-5F08C12493E9}";

	// Table name
	var $TableName = 'a_purchases_detail';

	// Page object name
	var $PageObjName = 'a_purchases_detail_preview';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = TRUE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (a_purchases_detail)
		if (!isset($GLOBALS["a_purchases_detail"]) || get_class($GLOBALS["a_purchases_detail"]) == "ca_purchases_detail") {
			$GLOBALS["a_purchases_detail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["a_purchases_detail"];
		}

		// Table object (a_purchases)
		if (!isset($GLOBALS['a_purchases'])) $GLOBALS['a_purchases'] = new ca_purchases();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'preview', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'a_purchases_detail', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (users)
		if (!isset($UserTable)) {
			$UserTable = new cusers();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (is_null($Security)) $Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			exit();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel(CurrentProjectID() . 'a_purchases_detail');
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			echo $Language->Phrase("NoPermission");
			exit();
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Set up list options
		$this->SetupListOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $a_purchases_detail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($a_purchases_detail);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $Recordset;
	var $TotalRecs;
	var $RowCnt;
	var $RecCount;
	var $ListOptions; // List options
	var $OtherOptions; // Other options

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load filter
		$filter = @$_GET["f"];
		$filter = ew_Decrypt($filter);
		if ($filter == "") $filter = "0=1";

		// Set up foreign keys from filter
		$this->SetupForeignKeysFromFilter($filter);

		// Call Recordset Selecting event
		$this->Recordset_Selecting($filter);

		// Load recordset
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset = $this->LoadRs($filter);
		$this->TotalRecs = ($this->Recordset) ? $this->Recordset->RecordCount() : 0;

		// Call Recordset Selected event
		$this->Recordset_Selected($this->Recordset);
		$this->LoadListRowValues($this->Recordset);
		$this->RenderOtherOptions();
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();
		$masterkeyurl = $this->MasterKeyUrl();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl($masterkeyurl)) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl($masterkeyurl)) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())

			//$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . " onclick=\"return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));\"" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->GetDeleteUrl()) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . " onclick=\"return ew_ConfirmDelete(this);\"" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->GetDeleteUrl($masterkeyurl)) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];
		$option->UseButtonGroup = FALSE;

		// Add group option item
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// Add
		$item = &$option->Add("add");
		$item->Visible = $Security->CanAdd();
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->GetItem("add");
		$item->Body = "<a class=\"btn btn-default btn-sm ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl($this->MasterKeyUrl())) . "\">" . $Language->Phrase("AddLink") . "</a>";
	}

	// Get master foreign key url
	function MasterKeyUrl() {
		$mastertblvar = @$_GET["t"];
		$url = "";
		if ($mastertblvar == "a_purchases") {
			$url = "" . EW_TABLE_SHOW_MASTER . "=a_purchases&fk_Purchase_Number=" . urlencode(strval($this->Purchase_Number->QueryStringValue)) . "";
		}
		return $url;
	}

	// Set up foreign keys from filter
	function SetupForeignKeysFromFilter($f) {
		$mastertblvar = @$_GET["t"];
		if ($mastertblvar == "a_purchases") {
			$find = "`Purchase_Number`="; 
			$x = strpos($f, $find);
			if ($x !== FALSE) {
				$x += strlen($find);
				$val = substr($f, $x);
				$val = $this->UnquoteValue($val, "DB");
 				$this->Purchase_Number->setQueryStringValue($val);
			}
		}
	}

	// Unquote value
	function UnquoteValue($val, $dbid) {
		if (substr($val,0,1) == "'" && substr($val,strlen($val)-1) == "'") {
			if (ew_GetConnectionType($dbid) == "MYSQL")
				return stripslashes(substr($val, 1, strlen($val)-2));
			else
				return str_replace("''", "'", substr($val, 1, strlen($val)-2));
		} else {
			return $val;
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE, 'utf-8') ?>
<?php

// Create page object
if (!isset($a_purchases_detail_preview)) $a_purchases_detail_preview = new ca_purchases_detail_preview();

// Page init
$a_purchases_detail_preview->Page_Init();

// Page main
$a_purchases_detail_preview->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$a_purchases_detail_preview->Page_Render();
?>
<?php $a_purchases_detail_preview->ShowPageHeader(); ?>
<?php if ($a_purchases_detail_preview->TotalRecs > 0) { ?>
<div class="ewGrid">
<table class="table ewTable ewPreviewTable">
	<thead><!-- Table header -->
		<tr class="ewTableHeader">
<?php

// Render list options
$a_purchases_detail_preview->RenderListOptions();

// Render list options (header, left)
$a_purchases_detail_preview->ListOptions->Render("header", "left");
?>
		<?php 		
		if (MS_SHOW_RECORD_NUMBER_COLUMN_ON_DETAIL_PREVIEW) { 
		?>
		<?php if (MS_RECORD_NUMBER_PREVIEW_LONG_CAPTION_COLUMN_TABLE) { ?>
            <td style="text-align: right;"><?php echo $Language->Phrase("LongRecNo"); ?></td>
		<?php } else { ?>
			<td style="text-align: right;"><?php echo $Language->Phrase("ShortRecNo"); ?></td>
		<?php } ?>
        <?php } ?>
<?php if ($a_purchases_detail->Supplier_Number->Visible) { // Supplier_Number ?>
			<th><?php echo $a_purchases_detail->Supplier_Number->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Stock_Item->Visible) { // Stock_Item ?>
			<th><?php echo $a_purchases_detail->Stock_Item->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Quantity->Visible) { // Purchasing_Quantity ?>
			<th><?php echo $a_purchases_detail->Purchasing_Quantity->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Price->Visible) { // Purchasing_Price ?>
			<th><?php echo $a_purchases_detail->Purchasing_Price->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Selling_Price->Visible) { // Selling_Price ?>
			<th><?php echo $a_purchases_detail->Selling_Price->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Total_Amount->Visible) { // Purchasing_Total_Amount ?>
			<th><?php echo $a_purchases_detail->Purchasing_Total_Amount->FldCaption() ?></th>
<?php } ?>
<?php

// Render list options (header, right)
$a_purchases_detail_preview->ListOptions->Render("header", "right");
?>
		</tr>
	</thead>
	<tbody><!-- Table body -->
<?php
$a_purchases_detail_preview->RecCount = 0;
$a_purchases_detail_preview->RowCnt = 0;
$rowNumber = 0;
while ($a_purchases_detail_preview->Recordset && !$a_purchases_detail_preview->Recordset->EOF) {
	$rowNumber++;

	// Init row class and style
	$a_purchases_detail_preview->RecCount++;
	$a_purchases_detail_preview->RowCnt++;
	$a_purchases_detail_preview->CssStyle = "";
	$a_purchases_detail_preview->LoadListRowValues($a_purchases_detail_preview->Recordset);
	$a_purchases_detail_preview->AggregateListRowValues(); // Aggregate row values

	// Render row
	$a_purchases_detail_preview->RowType = EW_ROWTYPE_PREVIEW; // Preview record
	$a_purchases_detail_preview->RenderListRow();

	// Render list options
	$a_purchases_detail_preview->RenderListOptions();
?>
	<tr<?php echo $a_purchases_detail_preview->RowAttributes() ?>>
<?php

// Render list options (body, left)
$a_purchases_detail_preview->ListOptions->Render("body", "left", $a_purchases_detail_preview->RowCnt);
?>
	<?php if (MS_SHOW_RECORD_NUMBER_COLUMN_ON_DETAIL_PREVIEW) { ?>
		<?php
			$verticalalign = "";
			if (MS_RECORD_NUMBER_PREVIEW_VERTICAL_ALIGN_TOP) {
				$verticalalign = "vertical-align: top;";
			} else {
				$verticalalign = "";
			}
		?>
        <td style="text-align:right;<?php echo $verticalalign; ?>"><?php echo ew_FormatSeqNo($rowNumber); ?></td>
    <?php } ?>
<?php if ($a_purchases_detail->Supplier_Number->Visible) { // Supplier_Number ?>
		<!-- Supplier_Number -->
		<td<?php echo $a_purchases_detail->Supplier_Number->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Supplier_Number->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Supplier_Number->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Stock_Item->Visible) { // Stock_Item ?>
		<!-- Stock_Item -->
		<td<?php echo $a_purchases_detail->Stock_Item->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Stock_Item->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Stock_Item->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Quantity->Visible) { // Purchasing_Quantity ?>
		<!-- Purchasing_Quantity -->
		<td<?php echo $a_purchases_detail->Purchasing_Quantity->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Purchasing_Quantity->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Purchasing_Quantity->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Price->Visible) { // Purchasing_Price ?>
		<!-- Purchasing_Price -->
		<td<?php echo $a_purchases_detail->Purchasing_Price->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Purchasing_Price->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Purchasing_Price->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Selling_Price->Visible) { // Selling_Price ?>
		<!-- Selling_Price -->
		<td<?php echo $a_purchases_detail->Selling_Price->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Selling_Price->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Selling_Price->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Total_Amount->Visible) { // Purchasing_Total_Amount ?>
		<!-- Purchasing_Total_Amount -->
		<td<?php echo $a_purchases_detail->Purchasing_Total_Amount->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Purchasing_Total_Amount->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Purchasing_Total_Amount->ListViewValue() ?></span>
</td>
<?php } ?>
<?php

// Render list options (body, right)
$a_purchases_detail_preview->ListOptions->Render("body", "right", $a_purchases_detail_preview->RowCnt);
?>
	</tr>
<?php
	$a_purchases_detail_preview->Recordset->MoveNext();
}
?>
	</tbody>
<?php

	// Render aggregate row
	$a_purchases_detail_preview->AggregateListRow(); // Prepare aggregate row

	// Render list options
	$a_purchases_detail_preview->RenderListOptions();
?>
	<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
	<?php if (MS_SHOW_RECORD_NUMBER_COLUMN_ON_DETAIL_PREVIEW) { ?>
        <td style="text-align: right;">&nbsp;</td>
    <?php } ?>
<?php

// Render list options (footer, left)
$a_purchases_detail_preview->ListOptions->Render("footer", "left");
?>
<?php if ($a_purchases_detail->Supplier_Number->Visible) { // Supplier_Number ?>
		<!-- Supplier_Number -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($a_purchases_detail->Stock_Item->Visible) { // Stock_Item ?>
		<!-- Stock_Item -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Quantity->Visible) { // Purchasing_Quantity ?>
		<!-- Purchasing_Quantity -->
		<td>
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $a_purchases_detail->Purchasing_Quantity->ViewValue ?>
		</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Price->Visible) { // Purchasing_Price ?>
		<!-- Purchasing_Price -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($a_purchases_detail->Selling_Price->Visible) { // Selling_Price ?>
		<!-- Selling_Price -->
		<td>
		&nbsp;
		</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Total_Amount->Visible) { // Purchasing_Total_Amount ?>
		<!-- Purchasing_Total_Amount -->
		<td>
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $a_purchases_detail->Purchasing_Total_Amount->ViewValue ?>
		</td>
<?php } ?>
<?php

// Render list options (footer, right)
$a_purchases_detail_preview->ListOptions->Render("footer", "right");
?>
	</tr>
	</tfoot>
</table>
</div>
<?php } ?>
<div class="ewPreviewLowerPanel">
<?php if ($a_purchases_detail_preview->TotalRecs > 0) { ?>
<div class="ewDetailCount"><?php echo $a_purchases_detail_preview->TotalRecs ?>&nbsp;<?php echo $Language->Phrase("Record") ?></div>
<?php // } else { ?>
<?php // } ?>
<div class="ewPreviewOtherOptions">
<?php
	foreach ($a_purchases_detail_preview->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php
$a_purchases_detail_preview->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
if ($a_purchases_detail_preview->Recordset)
	$a_purchases_detail_preview->Recordset->Close();
?>
<?php } else { // if ($a_purchases_detail_preview->TotalRecs > 0)  ?>
<?php //// Begin of Empty Table ?>
<?php // Begin of modification Empty Table in Detail Preview List Pages, by Masino Sinaga, November 29, 2012 ?>
<?php if (MS_SHOW_EMPTY_TABLE_IN_DETAIL_PREVIEW) { ?>
<div class="ewGrid">
<table class="table ewTable ewPreviewTable">
	<thead><!-- Table header -->
		<tr class="ewTableHeader">
        <?php if (MS_SHOW_RECORD_NUMBER_COLUMN_ON_DETAIL_PREVIEW) { ?>
			<?php
			$verticalalign = "";
			if (MS_RECORD_NUMBER_PREVIEW_VERTICAL_ALIGN_TOP) {
				$verticalalign = "vertical-align: top;";
			} else {
				$verticalalign = "";
			}
			?>
			<?php if (MS_RECORD_NUMBER_PREVIEW_LONG_CAPTION_COLUMN_TABLE) { ?>
            <td style="text-align: right;<?php echo $verticalalign; ?>"><?php echo $Language->Phrase("LongRecNo"); ?></td>
			<?php } else { ?>
			<td style="text-align: right;<?php echo $verticalalign; ?>"><?php echo $Language->Phrase("ShortRecNo"); ?></td>
			<?php } ?>
        <?php } ?>
<?php if ($a_purchases_detail->Supplier_Number->Visible) { // Supplier_Number ?>
			<th><?php echo $a_purchases_detail->Supplier_Number->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Stock_Item->Visible) { // Stock_Item ?>
			<th><?php echo $a_purchases_detail->Stock_Item->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Quantity->Visible) { // Purchasing_Quantity ?>
			<th><?php echo $a_purchases_detail->Purchasing_Quantity->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Price->Visible) { // Purchasing_Price ?>
			<th><?php echo $a_purchases_detail->Purchasing_Price->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Selling_Price->Visible) { // Selling_Price ?>
			<th><?php echo $a_purchases_detail->Selling_Price->FldCaption() ?></th>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Total_Amount->Visible) { // Purchasing_Total_Amount ?>
			<th><?php echo $a_purchases_detail->Purchasing_Total_Amount->FldCaption() ?></th>
<?php } ?>
		</tr>
	</thead>
	<tbody><!-- Table body -->
	<tr<?php echo $a_purchases_detail_preview->RowAttributes() ?>>
        <?php if (MS_SHOW_RECORD_NUMBER_COLUMN_ON_DETAIL_PREVIEW) { ?>
        <td style="text-align: right;">&nbsp;</td>
	    <?php } ?>
<?php if ($a_purchases_detail->Supplier_Number->Visible) { // Supplier_Number ?>
		<!-- Supplier_Number -->
		<td<?php echo $a_purchases_detail->Supplier_Number->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Supplier_Number->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Supplier_Number->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Stock_Item->Visible) { // Stock_Item ?>
		<!-- Stock_Item -->
		<td<?php echo $a_purchases_detail->Stock_Item->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Stock_Item->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Stock_Item->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Quantity->Visible) { // Purchasing_Quantity ?>
		<!-- Purchasing_Quantity -->
		<td<?php echo $a_purchases_detail->Purchasing_Quantity->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Purchasing_Quantity->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Purchasing_Quantity->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Price->Visible) { // Purchasing_Price ?>
		<!-- Purchasing_Price -->
		<td<?php echo $a_purchases_detail->Purchasing_Price->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Purchasing_Price->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Purchasing_Price->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Selling_Price->Visible) { // Selling_Price ?>
		<!-- Selling_Price -->
		<td<?php echo $a_purchases_detail->Selling_Price->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Selling_Price->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Selling_Price->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($a_purchases_detail->Purchasing_Total_Amount->Visible) { // Purchasing_Total_Amount ?>
		<!-- Purchasing_Total_Amount -->
		<td<?php echo $a_purchases_detail->Purchasing_Total_Amount->CellAttributes() ?>>
<span<?php echo $a_purchases_detail->Purchasing_Total_Amount->ViewAttributes() ?>>
<?php echo $a_purchases_detail->Purchasing_Total_Amount->ListViewValue() ?></span>
</td>
<?php } ?>
	</tr>
	</tbody>
</table>
</div>
<?php } // MS_SHOW_EMPTY_TABLE_IN_DETAIL_PREVIEW ?>
<div class="ewPreviewLowerPanel">
<div class="ewDetailCount"><?php echo $Language->Phrase("NoRecord") ?></div>
<div class="ewPreviewOtherOptions">
<?php
	foreach ($a_purchases_detail_preview->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php

////////////////////
 // } ?>

<?php // End of modification Empty Table in Detail Preview List Pages, by Masino Sinaga, November 29, 2012 ?>
<?php //// End of Empty Table
} // end if ($a_purchases_detail_preview->TotalRecs > 0)

// Output
$content = ob_get_contents();
ob_end_clean();
echo ew_ConvertToUtf8($content);
?>
<?php
$a_purchases_detail_preview->Page_Terminate();
?>
