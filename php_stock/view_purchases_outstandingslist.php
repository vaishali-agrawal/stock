<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "view_purchases_outstandingsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "a_payment_transactionsgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$view_purchases_outstandings_list = NULL; // Initialize page object first

class cview_purchases_outstandings_list extends cview_purchases_outstandings {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B36B93AF-B58F-461B-B767-5F08C12493E9}";

	// Table name
	var $TableName = 'view_purchases_outstandings';

	// Page object name
	var $PageObjName = 'view_purchases_outstandings_list';

	// Grid form hidden field names
	var $FormName = 'fview_purchases_outstandingslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// $hidden = TRUE;
		$hidden = MS_USE_JAVASCRIPT_MESSAGE;
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

			// if (!$hidden)
			//	 $sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			// $html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			// Begin of modification Auto Hide Message, by Masino Sinaga, January 24, 2013

			if (@MS_AUTO_HIDE_SUCCESS_MESSAGE) {

				//$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
				$html .= "<p class=\"alert alert-success msSuccessMessage\" id=\"ewSuccessMessage\">" . $sSuccessMessage . "</p>";
			} else {
				if (!$hidden)
					$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
				$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			}

			// End of modification Auto Hide Message, by Masino Sinaga, January 24, 2013
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

		// echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
		if (@MS_AUTO_HIDE_SUCCESS_MESSAGE || MS_USE_JAVASCRIPT_MESSAGE==0) {
			echo $html;
		} else {
			if (MS_USE_ALERTIFY_FOR_MESSAGE_DIALOG) {
				if ($html <> "") {
					$html = str_replace("'", "\'", $html);
					echo "<script type='text/javascript'>alertify.alert('".$html."', function (ok) { }).set('title', ewLanguage.Phrase('AlertifyAlert'));</script>";
				}
			} else {
				echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
			}
		}
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

		// Table object (view_purchases_outstandings)
		if (!isset($GLOBALS["view_purchases_outstandings"]) || get_class($GLOBALS["view_purchases_outstandings"]) == "cview_purchases_outstandings") {
			$GLOBALS["view_purchases_outstandings"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_purchases_outstandings"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view_purchases_outstandingsadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view_purchases_outstandingsdelete.php";
		$this->MultiUpdateUrl = "view_purchases_outstandingsupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_purchases_outstandings', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fview_purchases_outstandingslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm, $UserTableConn;
		if (!isset($_SESSION['table_view_purchases_outstandings_views'])) { 
			$_SESSION['table_view_purchases_outstandings_views'] = 0;
		}
		$_SESSION['table_view_purchases_outstandings_views'] = $_SESSION['table_view_purchases_outstandings_views']+1;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// Begin of modification Auto Logout After Idle for the Certain Time, by Masino Sinaga, May 5, 2012
		if (IsLoggedIn() && !IsSysAdmin()) {

			// Begin of modification by Masino Sinaga, May 25, 2012 in order to not autologout after clear another user's session ID whenever back to another page.           
			$UserProfile->LoadProfileFromDatabase(CurrentUserName());

			// End of modification by Masino Sinaga, May 25, 2012 in order to not autologout after clear another user's session ID whenever back to another page.
			// Begin of modification Save Last Users' Visitted Page, by Masino Sinaga, May 25, 2012

			$lastpage = ew_CurrentPage();
			if ($lastpage!='logout.php' && $lastpage!='index.php') {
				$lasturl = ew_CurrentUrl();
				$sFilterUserID = str_replace("%u", ew_AdjustSql(CurrentUserName(), EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);
				ew_Execute("UPDATE ".EW_USER_TABLE." SET Current_URL = '".$lasturl."' WHERE ".$sFilterUserID."", $UserTableConn);
			}

			// End of modification Save Last Users' Visitted Page, by Masino Sinaga, May 25, 2012
			$LastAccessDateTime = strval(@$UserProfile->Profile[EW_USER_PROFILE_LAST_ACCESSED_DATE_TIME]);
			$nDiff = intval(ew_DateDiff($LastAccessDateTime, ew_StdCurrentDateTime(), "s"));
			$nCons = intval(MS_AUTO_LOGOUT_AFTER_IDLE_IN_MINUTES) * 60;
			if ($nDiff > $nCons) {

				//header("Location: logout.php?expired=1");
			}
		}

		// End of modification Auto Logout After Idle for the Certain Time, by Masino Sinaga, May 5, 2012
		// Update last accessed time

		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {

			// Do nothing since it's a valid user! SaveProfileToDatabase has been handled from IsValidUser method of UserProfile object.
		} else {

			// Begin of modification How to Overcome "User X already logged in" Issue, by Masino Sinaga, July 22, 2014
			// echo $Language->Phrase("UserProfileCorrupted");

			header("Location: logout.php");

			// End of modification How to Overcome "User X already logged in" Issue, by Masino Sinaga, July 22, 2014
		}
		if (@MS_USE_CONSTANTS_IN_CONFIG_FILE == FALSE) {

			// Call this new function from userfn*.php file
			My_Global_Check();
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Begin of modification Permission Access for Export To Feature, by Masino Sinaga, To prevent users entering from URL, May 12, 2012
		global $gsExport;
		if ($gsExport=="print") {
			if (!$Security->CanExportToPrint() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}
		} elseif ($gsExport=="excel") {
			if (!$Security->CanExportToExcel() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}   
		} elseif ($gsExport=="word") {
			if (!$Security->CanExportToWord() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}   
		} elseif ($gsExport=="html") {
			if (!$Security->CanExportToHTML() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}   
		} elseif ($gsExport=="csv") {
			if (!$Security->CanExportToCSV() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}   
		} elseif ($gsExport=="xml") {
			if (!$Security->CanExportToXML() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}   
		} elseif ($gsExport=="pdf") {
			if (!$Security->CanExportToPDF() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}   
		} elseif ($gsExport=="email") {
			if (!$Security->CanExportToEmail() && !$Security->IsAdmin()) {
				echo $Language->Phrase("nopermission");
				exit();
			}   
		}

		// End of modification Permission Access for Export To Feature, by Masino Sinaga, To prevent users entering from URL, May 12, 2012
		// Get custom export parameters

		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

// Begin of modification Disable/Enable Registration Page, by Masino Sinaga, May 14, 2012
// End of modification Disable/Enable Registration Page, by Masino Sinaga, May 14, 2012
		// Page Load event

		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}
		if (ALWAYS_COMPARE_ROOT_URL == TRUE) {
			if ($_SESSION['php_stock_Root_URL'] <> Get_Root_URL()) {
				header("Location: " . $_SESSION['php_stock_Root_URL']);
			}
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 'a_payment_transactions'
			if (@$_POST["grid"] == "fa_payment_transactionsgrid") {
				if (!isset($GLOBALS["a_payment_transactions_grid"])) $GLOBALS["a_payment_transactions_grid"] = new ca_payment_transactions_grid;
				$GLOBALS["a_payment_transactions_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $view_purchases_outstandings;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_purchases_outstandings);
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;

// Begin of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012
    var $DisplayRecs = MS_TABLE_RECPERPAGE_VALUE;

// End of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012
	var $SearchPanelCollapsed = TRUE; // Modified by Masino Sinaga, September 23, 2014
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $a_payment_transactions_Count;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Restore filter list
			$this->RestoreFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {

			// Begin of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012
			$this->DisplayRecs = MS_TABLE_RECPERPAGE_VALUE; // Load default

			// End of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

	// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012     
		if ((MS_EXPORT_RECORD_OPTIONS=="selectedrecords") && (CurrentPageID() == "list")) {

			// Export selected records
			if ($this->Export <> "")
				$this->CurrentFilter = $this->BuildExportSelectedFilter();
		}

	// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
		// Export data only
		// Begin of modification Printer Friendly always does not use stylesheet, by Masino Sinaga, October 8, 2013 (added "print" in array)

		if ($this->CustomExport == "" && in_array($this->Export, array("html","print","word","excel","xml","csv","email","pdf"))) {

		// End of modification Printer Friendly always does not use stylesheet, by Masino Sinaga, October 8, 2013
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) { // begin of v11.0.4
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		} // end of v11.0.4

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {

	// Begin of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012
        global $Language;
        $sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
        if ($sWrk > MS_TABLE_MAXIMUM_SELECTED_RECORDS || strtolower($sWrk) == "all") {
            $sWrk = MS_TABLE_MAXIMUM_SELECTED_RECORDS;
            $this->setFailureMessage(str_replace("%t", MS_TABLE_MAXIMUM_SELECTED_RECORDS, $Language->Phrase("MaximumRecordsPerPage")));
        }

	// End of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->Purchase_ID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Purchase_ID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->Purchase_ID->AdvancedSearch->ToJSON(), ","); // Field Purchase_ID
		$sFilterList = ew_Concat($sFilterList, $this->Purchase_Number->AdvancedSearch->ToJSON(), ","); // Field Purchase_Number
		$sFilterList = ew_Concat($sFilterList, $this->Purchase_Date->AdvancedSearch->ToJSON(), ","); // Field Purchase_Date
		$sFilterList = ew_Concat($sFilterList, $this->Supplier_ID->AdvancedSearch->ToJSON(), ","); // Field Supplier_ID
		$sFilterList = ew_Concat($sFilterList, $this->Notes->AdvancedSearch->ToJSON(), ","); // Field Notes
		$sFilterList = ew_Concat($sFilterList, $this->Total_Amount->AdvancedSearch->ToJSON(), ","); // Field Total_Amount
		$sFilterList = ew_Concat($sFilterList, $this->Total_Payment->AdvancedSearch->ToJSON(), ","); // Field Total_Payment
		$sFilterList = ew_Concat($sFilterList, $this->Total_Balance->AdvancedSearch->ToJSON(), ","); // Field Total_Balance
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field Purchase_ID
		$this->Purchase_ID->AdvancedSearch->SearchValue = @$filter["x_Purchase_ID"];
		$this->Purchase_ID->AdvancedSearch->SearchOperator = @$filter["z_Purchase_ID"];
		$this->Purchase_ID->AdvancedSearch->SearchCondition = @$filter["v_Purchase_ID"];
		$this->Purchase_ID->AdvancedSearch->SearchValue2 = @$filter["y_Purchase_ID"];
		$this->Purchase_ID->AdvancedSearch->SearchOperator2 = @$filter["w_Purchase_ID"];
		$this->Purchase_ID->AdvancedSearch->Save();

		// Field Purchase_Number
		$this->Purchase_Number->AdvancedSearch->SearchValue = @$filter["x_Purchase_Number"];
		$this->Purchase_Number->AdvancedSearch->SearchOperator = @$filter["z_Purchase_Number"];
		$this->Purchase_Number->AdvancedSearch->SearchCondition = @$filter["v_Purchase_Number"];
		$this->Purchase_Number->AdvancedSearch->SearchValue2 = @$filter["y_Purchase_Number"];
		$this->Purchase_Number->AdvancedSearch->SearchOperator2 = @$filter["w_Purchase_Number"];
		$this->Purchase_Number->AdvancedSearch->Save();

		// Field Purchase_Date
		$this->Purchase_Date->AdvancedSearch->SearchValue = @$filter["x_Purchase_Date"];
		$this->Purchase_Date->AdvancedSearch->SearchOperator = @$filter["z_Purchase_Date"];
		$this->Purchase_Date->AdvancedSearch->SearchCondition = @$filter["v_Purchase_Date"];
		$this->Purchase_Date->AdvancedSearch->SearchValue2 = @$filter["y_Purchase_Date"];
		$this->Purchase_Date->AdvancedSearch->SearchOperator2 = @$filter["w_Purchase_Date"];
		$this->Purchase_Date->AdvancedSearch->Save();

		// Field Supplier_ID
		$this->Supplier_ID->AdvancedSearch->SearchValue = @$filter["x_Supplier_ID"];
		$this->Supplier_ID->AdvancedSearch->SearchOperator = @$filter["z_Supplier_ID"];
		$this->Supplier_ID->AdvancedSearch->SearchCondition = @$filter["v_Supplier_ID"];
		$this->Supplier_ID->AdvancedSearch->SearchValue2 = @$filter["y_Supplier_ID"];
		$this->Supplier_ID->AdvancedSearch->SearchOperator2 = @$filter["w_Supplier_ID"];
		$this->Supplier_ID->AdvancedSearch->Save();

		// Field Notes
		$this->Notes->AdvancedSearch->SearchValue = @$filter["x_Notes"];
		$this->Notes->AdvancedSearch->SearchOperator = @$filter["z_Notes"];
		$this->Notes->AdvancedSearch->SearchCondition = @$filter["v_Notes"];
		$this->Notes->AdvancedSearch->SearchValue2 = @$filter["y_Notes"];
		$this->Notes->AdvancedSearch->SearchOperator2 = @$filter["w_Notes"];
		$this->Notes->AdvancedSearch->Save();

		// Field Total_Amount
		$this->Total_Amount->AdvancedSearch->SearchValue = @$filter["x_Total_Amount"];
		$this->Total_Amount->AdvancedSearch->SearchOperator = @$filter["z_Total_Amount"];
		$this->Total_Amount->AdvancedSearch->SearchCondition = @$filter["v_Total_Amount"];
		$this->Total_Amount->AdvancedSearch->SearchValue2 = @$filter["y_Total_Amount"];
		$this->Total_Amount->AdvancedSearch->SearchOperator2 = @$filter["w_Total_Amount"];
		$this->Total_Amount->AdvancedSearch->Save();

		// Field Total_Payment
		$this->Total_Payment->AdvancedSearch->SearchValue = @$filter["x_Total_Payment"];
		$this->Total_Payment->AdvancedSearch->SearchOperator = @$filter["z_Total_Payment"];
		$this->Total_Payment->AdvancedSearch->SearchCondition = @$filter["v_Total_Payment"];
		$this->Total_Payment->AdvancedSearch->SearchValue2 = @$filter["y_Total_Payment"];
		$this->Total_Payment->AdvancedSearch->SearchOperator2 = @$filter["w_Total_Payment"];
		$this->Total_Payment->AdvancedSearch->Save();

		// Field Total_Balance
		$this->Total_Balance->AdvancedSearch->SearchValue = @$filter["x_Total_Balance"];
		$this->Total_Balance->AdvancedSearch->SearchOperator = @$filter["z_Total_Balance"];
		$this->Total_Balance->AdvancedSearch->SearchCondition = @$filter["v_Total_Balance"];
		$this->Total_Balance->AdvancedSearch->SearchValue2 = @$filter["y_Total_Balance"];
		$this->Total_Balance->AdvancedSearch->SearchOperator2 = @$filter["w_Total_Balance"];
		$this->Total_Balance->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Purchase_ID, $Default, FALSE); // Purchase_ID
		$this->BuildSearchSql($sWhere, $this->Purchase_Number, $Default, FALSE); // Purchase_Number
		$this->BuildSearchSql($sWhere, $this->Purchase_Date, $Default, FALSE); // Purchase_Date
		$this->BuildSearchSql($sWhere, $this->Supplier_ID, $Default, FALSE); // Supplier_ID
		$this->BuildSearchSql($sWhere, $this->Notes, $Default, FALSE); // Notes
		$this->BuildSearchSql($sWhere, $this->Total_Amount, $Default, FALSE); // Total_Amount
		$this->BuildSearchSql($sWhere, $this->Total_Payment, $Default, FALSE); // Total_Payment
		$this->BuildSearchSql($sWhere, $this->Total_Balance, $Default, FALSE); // Total_Balance

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Purchase_ID->AdvancedSearch->Save(); // Purchase_ID
			$this->Purchase_Number->AdvancedSearch->Save(); // Purchase_Number
			$this->Purchase_Date->AdvancedSearch->Save(); // Purchase_Date
			$this->Supplier_ID->AdvancedSearch->Save(); // Supplier_ID
			$this->Notes->AdvancedSearch->Save(); // Notes
			$this->Total_Amount->AdvancedSearch->Save(); // Total_Amount
			$this->Total_Payment->AdvancedSearch->Save(); // Total_Payment
			$this->Total_Balance->AdvancedSearch->Save(); // Total_Balance
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Purchase_Number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Supplier_ID, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Notes, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}

					// Begin of modification Exact Match search criteria, by Masino Sinaga, November 12, 2014. See also: http://www.hkvforums.com/viewtopic.php?f=4&t=35853&p=104026#p104026
					if ($type == "=") {
						$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
						$sWrk = $sFldExpression . " = " . ew_QuotedValue("" . $Keyword . "", EW_DATATYPE_STRING);
					}

					// End of modification Exact Match search criteria, by Masino Sinaga, November 12, 2014. See also: http://www.hkvforums.com/viewtopic.php?f=4&t=35853&p=104026#p104026
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->Purchase_ID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Purchase_Number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Purchase_Date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Supplier_ID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Notes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Total_Amount->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Total_Payment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Total_Balance->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->Purchase_ID->AdvancedSearch->UnsetSession();
		$this->Purchase_Number->AdvancedSearch->UnsetSession();
		$this->Purchase_Date->AdvancedSearch->UnsetSession();
		$this->Supplier_ID->AdvancedSearch->UnsetSession();
		$this->Notes->AdvancedSearch->UnsetSession();
		$this->Total_Amount->AdvancedSearch->UnsetSession();
		$this->Total_Payment->AdvancedSearch->UnsetSession();
		$this->Total_Balance->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->Purchase_ID->AdvancedSearch->Load();
		$this->Purchase_Number->AdvancedSearch->Load();
		$this->Purchase_Date->AdvancedSearch->Load();
		$this->Supplier_ID->AdvancedSearch->Load();
		$this->Notes->AdvancedSearch->Load();
		$this->Total_Amount->AdvancedSearch->Load();
		$this->Total_Payment->AdvancedSearch->Load();
		$this->Total_Balance->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Purchase_Number); // Purchase_Number
			$this->UpdateSort($this->Purchase_Date); // Purchase_Date
			$this->UpdateSort($this->Supplier_ID); // Supplier_ID
			$this->UpdateSort($this->Notes); // Notes
			$this->UpdateSort($this->Total_Amount); // Total_Amount
			$this->UpdateSort($this->Total_Payment); // Total_Payment
			$this->UpdateSort($this->Total_Balance); // Total_Balance
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
				$this->Purchase_Date->setSort("DESC");
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Purchase_Number->setSort("");
				$this->Purchase_Date->setSort("");
				$this->Supplier_ID->setSort("");
				$this->Notes->setSort("");
				$this->Total_Amount->setSort("");
				$this->Total_Payment->setSort("");
				$this->Total_Balance->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "detail_a_payment_transactions"
		$item = &$this->ListOptions->Add("detail_a_payment_transactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'a_payment_transactions') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["a_payment_transactions_grid"])) $GLOBALS["a_payment_transactions_grid"] = new ca_payment_transactions_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("a_payment_transactions");
		$this->DetailPages = $pages;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_a_payment_transactions"
		$oListOpt = &$this->ListOptions->Items["detail_a_payment_transactions"];
		if ($Security->AllowList(CurrentProjectID() . 'a_payment_transactions')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("a_payment_transactions", "TblCaption");

			// $body .= str_replace("%c", $this->a_payment_transactions_Count, $Language->Phrase("DetailCount"));
			if ( $this->a_payment_transactions_Count > 0 && MS_SHOW_DETAILCOUNT_GREATER_THAN_ZERO_ONLY == TRUE ) {
				if (MS_USE_BADGE_FOR_DETAILCOUNT) {
					$body .= "&nbsp;<i class='badge'>".$this->a_payment_transactions_Count."</i>"; // we cannot use <span class='badge'></span> here, not sure why? strange, huh?
				} else {
					$body .= "&nbsp;" . str_replace("%c", $this->a_payment_transactions_Count, $Language->Phrase("DetailCount"));
				}
			} elseif ( $this->a_payment_transactions_Count >= 0 && MS_SHOW_DETAILCOUNT_GREATER_THAN_ZERO_ONLY == FALSE ) {
				if (MS_USE_BADGE_FOR_DETAILCOUNT) {
					$body .= "&nbsp;<i class='badge'>".$this->a_payment_transactions_Count."</i>"; // we cannot use <span class='badge'></span> here, not sure why? strange, huh?
				} else {
					$body .= "&nbsp;" . str_replace("%c", $this->a_payment_transactions_Count, $Language->Phrase("DetailCount"));
				}
			}
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("a_payment_transactionslist.php?" . EW_TABLE_SHOW_MASTER . "=view_purchases_outstandings&fk_Purchase_Number=" . urlencode(strval($this->Purchase_Number->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Purchase_ID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fview_purchases_outstandingslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fview_purchases_outstandingslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fview_purchases_outstandingslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");

		// Begin of modification Customizing Search Panel, by Masino Sinaga, for customize search panel, July 22, 2014
		if (MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_COLLAPSED) {			

			// The code in this first block will be generated if "UseTableSettingForSearchPanelCollapsed" is enabled from "MasinoFixedWidthSite12" extension, also with "InitSearchPanelAsCollapsed" is enabled from -> "Advanced" -> "Tables" setting.
			if ($this->SearchPanelCollapsed==TRUE) {
				$SearchToggleClass = " ";
			} else {
				$SearchToggleClass = " active";
			}
		} else {

			// Nothing to do, because we've been using MS_SEARCH_PANEL_COLLAPSED value from the generated "ewcfg11.php" file
			// $SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active"; // <-- no need to use this anymore!

			if (MS_SEARCH_PANEL_COLLAPSED == TRUE && $this->SearchWhere <> "") {
				$SearchToggleClass = " active";
			} elseif (MS_SEARCH_PANEL_COLLAPSED == TRUE && $this->SearchWhere == "") {
				$SearchToggleClass = " ";
			} elseif (MS_SEARCH_PANEL_COLLAPSED == FALSE && $this->SearchWhere <> "") {
				$SearchToggleClass = " active";			
			} elseif (MS_SEARCH_PANEL_COLLAPSED == FALSE && $this->SearchWhere == "") {
				$SearchToggleClass = " active";
			}
		}

		// End of modification Customizing Search Panel, by Masino Sinaga, for customize search panel, July 22, 2014
		// Begin of modification Hide Search Button for Inline Edit and Inline Copy mode in List Page, by Masino Sinaga, August 4, 2014

		if ($this->CurrentAction == "edit" || $this->CurrentAction == "copy") {
		} else {
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview_purchases_outstandingslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
			$item->Visible = TRUE;
		}

		// End of modification Hide Search Button for Inline Edit and Inline Copy mode in List Page, by Masino Sinaga, August 4, 2014			
		// Begin of modification Hide Search Button for Inline Edit and Inline Copy mode in List Page, by Masino Sinaga, August 4, 2014

		if ($this->CurrentAction == "edit" || $this->CurrentAction == "copy") {
		} else {

			// Show all button
			$item = &$this->SearchOptions->Add("showall");
			$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
			$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101"); // v11.0.4
		}

		// End of modification Hide Search Button for Inline Edit and Inline Copy mode in List Page, by Masino Sinaga, August 4, 2014
		// Advanced search button

		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"view_purchases_outstandingssrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
		$links = "";
		$btngrps = "";
		$sSqlWrk = "`Ref_ID`='" . ew_AdjustSql($this->Purchase_Number->CurrentValue, $this->DBID) . "'";

		// Column "detail_a_payment_transactions"
		if ($this->DetailPages->Items["a_payment_transactions"]->Visible) {
			$link = "";
			$option = &$this->ListOptions->Items["detail_a_payment_transactions"];
			$url = "a_payment_transactionspreview.php?t=view_purchases_outstandings&f=" . ew_Encrypt($sSqlWrk);
			$btngrp = "<div data-table=\"a_payment_transactions\" data-url=\"" . $url . "\" class=\"btn-group\">";
			if ($Security->AllowList(CurrentProjectID() . 'a_payment_transactions')) {			
				$label = $Language->TablePhrase("a_payment_transactions", "TblCaption");

				// $label .= "&nbsp;" . ew_JsEncode2(str_replace("%c", $this->a_payment_transactions_Count, $Language->Phrase("DetailCount")));		
				if ( $this->a_payment_transactions_Count > 0 && @MS_SHOW_DETAILCOUNT_GREATER_THAN_ZERO_ONLY == TRUE ) {
					if (@MS_USE_BADGE_FOR_DETAILCOUNT) {
						$label .= "&nbsp; <span class='badge badge-info'>".$this->a_payment_transactions_Count."</span>"; 
					} else {
						$label .= "&nbsp;" . ew_JsEncode2(str_replace("%c", $this->a_payment_transactions_Count, $Language->Phrase("DetailCount")));
					}
				} elseif ( $this->a_payment_transactions_Count >= 0 && @MS_SHOW_DETAILCOUNT_GREATER_THAN_ZERO_ONLY == FALSE ) {
					if (@MS_USE_BADGE_FOR_DETAILCOUNT) {
						$label .= "&nbsp; <span class='badge badge-info'>".$this->a_payment_transactions_Count."</span>"; 
					} else {
						$label .= "&nbsp;" . ew_JsEncode2(str_replace("%c", $this->a_payment_transactions_Count, $Language->Phrase("DetailCount")));
					}
				}
				$link = "<li><a href=\"#\" data-toggle=\"tab\" data-table=\"a_payment_transactions\" data-url=\"" . $url . "\">" . $label . "</a></li>";			
				$links .= $link;
				$detaillnk = ew_JsEncode3("a_payment_transactionslist.php?" . EW_TABLE_SHOW_MASTER . "=view_purchases_outstandings&fk_Purchase_Number=" . urlencode(strval($this->Purchase_Number->CurrentValue)) . "");
				$btngrp .= "<button type=\"button\" class=\"btn btn-default btn-sm\" title=\"" . $Language->TablePhrase("a_payment_transactions", "TblCaption") . "\" onclick=\"window.location='" . $detaillnk . "'\">" . $Language->Phrase("MasterDetailListLink") . "</button>";
			}
			if ($GLOBALS["a_payment_transactions_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'a_payment_transactions'))
				$btngrp .= "<button type=\"button\" class=\"btn btn-default btn-sm\" title=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" onclick=\"window.location='" . $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=a_payment_transactions") . "'\">" . $Language->Phrase("MasterDetailViewLink") . "</button>";
			$btngrp .= "</div>";
			if ($link <> "") {
				$btngrps .= $btngrp;
				$option->Body .= "<div class=\"hide ewPreview\">" . $link . $btngrp . "</div>";
			}
		}

		// Show detail items if necessary, modification based on v11.0.2, by Masino Sinaga, October 13, 2014
		$showdtl = FALSE;
		foreach ($this->ListOptions->Items as $item) {
			if ($item->Name <> $this->ListOptions->GroupOptionName && $item->Visible && $item->ShowInDropDown && substr($item->Name,0,7) <> "detail_") {
				$showdtl = TRUE;
				break;
			}
		}
		if ($showdtl) {
			foreach ($this->ListOptions->Items as $item) {
				if (substr($item->Name,0,7) == "detail_") {
					$item->Visible = TRUE;
				}
			}
		}

		// Column "preview"
		$option = &$this->ListOptions->GetItem("preview");
		if (!$option) { // Add preview column
			$option = &$this->ListOptions->Add("preview");
			$option->OnLeft = TRUE;
			if ($option->OnLeft) {
				$option->MoveTo($this->ListOptions->ItemPos("checkbox") + 1);
			} else {
				$option->MoveTo($this->ListOptions->ItemPos("checkbox"));
			}
			$option->Visible = !($this->Export <> "" || $this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit");
			$option->ShowInDropDown = FALSE;
			$option->ShowInButtonGroup = FALSE;
		}
		if ($option) {
			$option->Body = "<span class=\"ewPreviewRowBtn icon-expand\"></span>";
			$option->Body .= "<div class=\"hide ewPreview\">" . $links . $btngrps . "</div>";
			if ($option->Visible) $option->Visible = $links <> "";
		}

		// Column "details" (Multiple details)
		$option = &$this->ListOptions->GetItem("details");
		if ($option) {
			$option->Body .= "<div class=\"hide ewPreview\">" . $links . $btngrps . "</div>";
			if ($option->Visible) $option->Visible = $links <> "";
		}
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// Purchase_ID

		$this->Purchase_ID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Purchase_ID"]);
		if ($this->Purchase_ID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Purchase_ID->AdvancedSearch->SearchOperator = @$_GET["z_Purchase_ID"];
		$this->Purchase_ID->AdvancedSearch->SearchCondition = @$_GET["v_Purchase_ID"];
		$this->Purchase_ID->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Purchase_ID"]);
		if ($this->Purchase_ID->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Purchase_ID->AdvancedSearch->SearchOperator2 = @$_GET["w_Purchase_ID"];

		// Purchase_Number
		$this->Purchase_Number->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Purchase_Number"]);
		if ($this->Purchase_Number->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Purchase_Number->AdvancedSearch->SearchOperator = @$_GET["z_Purchase_Number"];
		$this->Purchase_Number->AdvancedSearch->SearchCondition = @$_GET["v_Purchase_Number"];
		$this->Purchase_Number->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Purchase_Number"]);
		if ($this->Purchase_Number->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Purchase_Number->AdvancedSearch->SearchOperator2 = @$_GET["w_Purchase_Number"];

		// Purchase_Date
		$this->Purchase_Date->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Purchase_Date"]);
		if ($this->Purchase_Date->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Purchase_Date->AdvancedSearch->SearchOperator = @$_GET["z_Purchase_Date"];
		$this->Purchase_Date->AdvancedSearch->SearchCondition = @$_GET["v_Purchase_Date"];
		$this->Purchase_Date->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Purchase_Date"]);
		if ($this->Purchase_Date->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Purchase_Date->AdvancedSearch->SearchOperator2 = @$_GET["w_Purchase_Date"];

		// Supplier_ID
		$this->Supplier_ID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Supplier_ID"]);
		if ($this->Supplier_ID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Supplier_ID->AdvancedSearch->SearchOperator = @$_GET["z_Supplier_ID"];
		$this->Supplier_ID->AdvancedSearch->SearchCondition = @$_GET["v_Supplier_ID"];
		$this->Supplier_ID->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Supplier_ID"]);
		if ($this->Supplier_ID->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Supplier_ID->AdvancedSearch->SearchOperator2 = @$_GET["w_Supplier_ID"];

		// Notes
		$this->Notes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Notes"]);
		if ($this->Notes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Notes->AdvancedSearch->SearchOperator = @$_GET["z_Notes"];
		$this->Notes->AdvancedSearch->SearchCondition = @$_GET["v_Notes"];
		$this->Notes->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Notes"]);
		if ($this->Notes->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Notes->AdvancedSearch->SearchOperator2 = @$_GET["w_Notes"];

		// Total_Amount
		$this->Total_Amount->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Total_Amount"]);
		if ($this->Total_Amount->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Total_Amount->AdvancedSearch->SearchOperator = @$_GET["z_Total_Amount"];
		$this->Total_Amount->AdvancedSearch->SearchCondition = @$_GET["v_Total_Amount"];
		$this->Total_Amount->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Total_Amount"]);
		if ($this->Total_Amount->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Total_Amount->AdvancedSearch->SearchOperator2 = @$_GET["w_Total_Amount"];

		// Total_Payment
		$this->Total_Payment->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Total_Payment"]);
		if ($this->Total_Payment->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Total_Payment->AdvancedSearch->SearchOperator = @$_GET["z_Total_Payment"];
		$this->Total_Payment->AdvancedSearch->SearchCondition = @$_GET["v_Total_Payment"];
		$this->Total_Payment->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Total_Payment"]);
		if ($this->Total_Payment->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Total_Payment->AdvancedSearch->SearchOperator2 = @$_GET["w_Total_Payment"];

		// Total_Balance
		$this->Total_Balance->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Total_Balance"]);
		if ($this->Total_Balance->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Total_Balance->AdvancedSearch->SearchOperator = @$_GET["z_Total_Balance"];
		$this->Total_Balance->AdvancedSearch->SearchCondition = @$_GET["v_Total_Balance"];
		$this->Total_Balance->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_Total_Balance"]);
		if ($this->Total_Balance->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->Total_Balance->AdvancedSearch->SearchOperator2 = @$_GET["w_Total_Balance"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Begin of modification (20140916): http://www.hkvforums.com/viewtopic.php?f=4&t=35486&p=102440#p102440
		// Load List page SQL

		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->Purchase_ID->setDbValue($rs->fields('Purchase_ID'));
		$this->Purchase_Number->setDbValue($rs->fields('Purchase_Number'));
		$this->Purchase_Date->setDbValue($rs->fields('Purchase_Date'));
		$this->Supplier_ID->setDbValue($rs->fields('Supplier_ID'));
		$this->Notes->setDbValue($rs->fields('Notes'));
		$this->Total_Amount->setDbValue($rs->fields('Total_Amount'));
		$this->Total_Payment->setDbValue($rs->fields('Total_Payment'));
		$this->Total_Balance->setDbValue($rs->fields('Total_Balance'));
		if (!isset($GLOBALS["a_payment_transactions_grid"])) $GLOBALS["a_payment_transactions_grid"] = new ca_payment_transactions_grid;
		$sDetailFilter = $GLOBALS["a_payment_transactions"]->SqlDetailFilter_view_purchases_outstandings();
		$sDetailFilter = str_replace("@Ref_ID@", ew_AdjustSql($this->Purchase_Number->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["a_payment_transactions"]->setCurrentMasterTable("view_purchases_outstandings");
		$sDetailFilter = $GLOBALS["a_payment_transactions"]->ApplyUserIDFilters($sDetailFilter);
		$this->a_payment_transactions_Count = $GLOBALS["a_payment_transactions"]->LoadRecordCount($sDetailFilter);
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Purchase_ID->DbValue = $row['Purchase_ID'];
		$this->Purchase_Number->DbValue = $row['Purchase_Number'];
		$this->Purchase_Date->DbValue = $row['Purchase_Date'];
		$this->Supplier_ID->DbValue = $row['Supplier_ID'];
		$this->Notes->DbValue = $row['Notes'];
		$this->Total_Amount->DbValue = $row['Total_Amount'];
		$this->Total_Payment->DbValue = $row['Total_Payment'];
		$this->Total_Balance->DbValue = $row['Total_Balance'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Purchase_ID")) <> "")
			$this->Purchase_ID->CurrentValue = $this->getKey("Purchase_ID"); // Purchase_ID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->Total_Amount->FormValue == $this->Total_Amount->CurrentValue && is_numeric(ew_StrToFloat($this->Total_Amount->CurrentValue)))
			$this->Total_Amount->CurrentValue = ew_StrToFloat($this->Total_Amount->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Total_Payment->FormValue == $this->Total_Payment->CurrentValue && is_numeric(ew_StrToFloat($this->Total_Payment->CurrentValue)))
			$this->Total_Payment->CurrentValue = ew_StrToFloat($this->Total_Payment->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Total_Balance->FormValue == $this->Total_Balance->CurrentValue && is_numeric(ew_StrToFloat($this->Total_Balance->CurrentValue)))
			$this->Total_Balance->CurrentValue = ew_StrToFloat($this->Total_Balance->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Purchase_ID

		$this->Purchase_ID->CellCssStyle = "white-space: nowrap;";

		// Purchase_Number
		// Purchase_Date
		// Supplier_ID
		// Notes
		// Total_Amount
		// Total_Payment

		$this->Total_Payment->CellCssStyle = "white-space: nowrap;";

		// Total_Balance
		$this->Total_Balance->CellCssStyle = "white-space: nowrap;";

		// Accumulate aggregate value
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->Total_Amount->CurrentValue))
				$this->Total_Amount->Total += $this->Total_Amount->CurrentValue; // Accumulate total
			if (is_numeric($this->Total_Payment->CurrentValue))
				$this->Total_Payment->Total += $this->Total_Payment->CurrentValue; // Accumulate total
			if (is_numeric($this->Total_Balance->CurrentValue))
				$this->Total_Balance->Total += $this->Total_Balance->CurrentValue; // Accumulate total
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Purchase_Number
		$this->Purchase_Number->ViewValue = $this->Purchase_Number->CurrentValue;
		$this->Purchase_Number->ViewCustomAttributes = "";

		// Purchase_Date
		$this->Purchase_Date->ViewValue = $this->Purchase_Date->CurrentValue;
		$this->Purchase_Date->ViewValue = ew_FormatDateTime($this->Purchase_Date->ViewValue, 9);
		$this->Purchase_Date->ViewCustomAttributes = "";

		// Supplier_ID
		if (strval($this->Supplier_ID->CurrentValue) <> "") {
			$sFilterWrk = "`Supplier_Number`" . ew_SearchString("=", $this->Supplier_ID->CurrentValue, EW_DATATYPE_STRING, "");
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
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Supplier_ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Supplier_ID->ViewValue = $this->Supplier_ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Supplier_ID->ViewValue = $this->Supplier_ID->CurrentValue;
			}
		} else {
			$this->Supplier_ID->ViewValue = NULL;
		}
		$this->Supplier_ID->ViewCustomAttributes = "";

		// Notes
		$this->Notes->ViewValue = $this->Notes->CurrentValue;
		$this->Notes->ViewCustomAttributes = "";

		// Total_Amount
		$this->Total_Amount->ViewValue = $this->Total_Amount->CurrentValue;
		$this->Total_Amount->ViewValue = ew_FormatCurrency($this->Total_Amount->ViewValue, 0, -2, -2, -2);
		$this->Total_Amount->CellCssStyle .= "text-align: right;";
		$this->Total_Amount->ViewCustomAttributes = "";

		// Total_Payment
		$this->Total_Payment->ViewValue = $this->Total_Payment->CurrentValue;
		$this->Total_Payment->ViewValue = ew_FormatCurrency($this->Total_Payment->ViewValue, 0, -2, -2, -2);
		$this->Total_Payment->CellCssStyle .= "text-align: right;";
		$this->Total_Payment->ViewCustomAttributes = "";

		// Total_Balance
		$this->Total_Balance->ViewValue = $this->Total_Balance->CurrentValue;
		$this->Total_Balance->ViewValue = ew_FormatCurrency($this->Total_Balance->ViewValue, 0, -2, -2, -2);
		$this->Total_Balance->CellCssStyle .= "text-align: right;";
		$this->Total_Balance->ViewCustomAttributes = "";

			// Purchase_Number
			$this->Purchase_Number->LinkCustomAttributes = "";
			$this->Purchase_Number->HrefValue = "";
			$this->Purchase_Number->TooltipValue = "";

			// Purchase_Date
			$this->Purchase_Date->LinkCustomAttributes = "";
			$this->Purchase_Date->HrefValue = "";
			$this->Purchase_Date->TooltipValue = "";

			// Supplier_ID
			$this->Supplier_ID->LinkCustomAttributes = "";
			$this->Supplier_ID->HrefValue = "";
			$this->Supplier_ID->TooltipValue = "";

			// Notes
			$this->Notes->LinkCustomAttributes = "";
			$this->Notes->HrefValue = "";
			$this->Notes->TooltipValue = "";

			// Total_Amount
			$this->Total_Amount->LinkCustomAttributes = "";
			$this->Total_Amount->HrefValue = "";
			$this->Total_Amount->TooltipValue = "";

			// Total_Payment
			$this->Total_Payment->LinkCustomAttributes = "";
			$this->Total_Payment->HrefValue = "";
			$this->Total_Payment->TooltipValue = "";

			// Total_Balance
			$this->Total_Balance->LinkCustomAttributes = "";
			$this->Total_Balance->HrefValue = "";
			$this->Total_Balance->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->Total_Amount->Total = 0; // Initialize total
			$this->Total_Payment->Total = 0; // Initialize total
			$this->Total_Balance->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->Total_Amount->CurrentValue = $this->Total_Amount->Total;
			$this->Total_Amount->ViewValue = $this->Total_Amount->CurrentValue;
			$this->Total_Amount->ViewValue = ew_FormatCurrency($this->Total_Amount->ViewValue, 0, -2, -2, -2);
			$this->Total_Amount->CellCssStyle .= "text-align: right;";
			$this->Total_Amount->ViewCustomAttributes = "";
			$this->Total_Amount->HrefValue = ""; // Clear href value
			$this->Total_Payment->CurrentValue = $this->Total_Payment->Total;
			$this->Total_Payment->ViewValue = $this->Total_Payment->CurrentValue;
			$this->Total_Payment->ViewValue = ew_FormatCurrency($this->Total_Payment->ViewValue, 0, -2, -2, -2);
			$this->Total_Payment->CellCssStyle .= "text-align: right;";
			$this->Total_Payment->ViewCustomAttributes = "";
			$this->Total_Payment->HrefValue = ""; // Clear href value
			$this->Total_Balance->CurrentValue = $this->Total_Balance->Total;
			$this->Total_Balance->ViewValue = $this->Total_Balance->CurrentValue;
			$this->Total_Balance->ViewValue = ew_FormatCurrency($this->Total_Balance->ViewValue, 0, -2, -2, -2);
			$this->Total_Balance->CellCssStyle .= "text-align: right;";
			$this->Total_Balance->ViewCustomAttributes = "";
			$this->Total_Balance->HrefValue = ""; // Clear href value
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->Purchase_ID->AdvancedSearch->Load();
		$this->Purchase_Number->AdvancedSearch->Load();
		$this->Purchase_Date->AdvancedSearch->Load();
		$this->Supplier_ID->AdvancedSearch->Load();
		$this->Notes->AdvancedSearch->Load();
		$this->Total_Amount->AdvancedSearch->Load();
		$this->Total_Payment->AdvancedSearch->Load();
		$this->Total_Balance->AdvancedSearch->Load();
	}

	// Build export filter for selected records
	function BuildExportSelectedFilter() {
		global $Language;
		$sWrkFilter = "";
		if ($this->Export <> "") {
			$sWrkFilter = $this->GetKeyFilter();
		}
		return $sWrkFilter;
	}

	// Set up export options
	function SetupExportOptions() {

// Begin of modification Permission Access for Export To Feature, by Masino Sinaga, May 5, 2012
        global $Language, $Security, $view_purchases_outstandings; // <-- Added $Security variable by Masino Sinaga

		// Printer friendly
        if ($Security->CanExportToPrint() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("print");

			// $item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

			if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
				$item->Body = "<a class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" onclick=\"ew_Export(document.fview_purchases_outstandingslist,'" . ew_CurrentPage() . "','print',false,true);\">" . $Language->Phrase("PrinterFriendly") . "</a>";
			} else {
				$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\"  data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
			}

			// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = TRUE;
        }

		// Export to Excel
        if ($Security->CanExportToExcel() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("excel");

			// $item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

			if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
				$item->Body = "<a class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" onclick=\"ew_Export(document.fview_purchases_outstandingslist,'" . ew_CurrentPage() . "','excel',false,true);\">" . $Language->Phrase("ExportToExcel") . "</a>";
			} else {
				$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\"  data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
			}

			// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = TRUE;
        }

		// Export to Word
        if ($Security->CanExportToWord() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("word");

			// $item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

			if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
				$item->Body = "<a class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" onclick=\"ew_Export(document.fview_purchases_outstandingslist,'" . ew_CurrentPage() . "','word',false,true);\">" . $Language->Phrase("ExportToWord") . "</a>";
			} else {
				$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\"  data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
			}

			// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = TRUE;
        }

		// Export to Html
        if ($Security->CanExportToHTML() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("html");

			// $item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

			if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
				$item->Body = "<a class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" onclick=\"ew_Export(document.fview_purchases_outstandingslist,'" . ew_CurrentPage() . "','html',false,true);\">" . $Language->Phrase("ExportToHtml") . "</a>";
			} else {
				$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\"  data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHTML") . "</a>";
			}

			// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = TRUE;
        }

		// Export to Xml
        if ($Security->CanExportToXML() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("xml");

			// $item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

			if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
				$item->Body = "<a class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" onclick=\"ew_Export(document.fview_purchases_outstandingslist,'" . ew_CurrentPage() . "','xml',false,true);\">" . $Language->Phrase("ExportToXml") . "</a>";
			} else {
				$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXML") . "</a>";
			}

			// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = TRUE;
        }

		// Export to Csv
        if ($Security->CanExportToCSV() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("csv");

			// $item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

			if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
				$item->Body = "<a class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" onclick=\"ew_Export(document.fview_purchases_outstandingslist,'" . ew_CurrentPage() . "','csv',false,true);\">" . $Language->Phrase("ExportToCsv") . "</a>";
			} else {
				$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\"  data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCSV") . "</a>";
			}

			// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = TRUE;
        }

		// Export to Pdf
        if ($Security->CanExportToPDF() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("pdf");

			// $item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

			if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
				$item->Body = "<a class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" onclick=\"ew_Export(document.fview_purchases_outstandingslist,'" . ew_CurrentPage() . "','pdf',false,true);\">" . $Language->Phrase("ExportToPDF") . "</a>";
			} else {
				$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\"  data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
			}

			// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = FALSE;
        }

		// Export to Email
		if ($Security->CanExportToEmail() || $Security->IsAdmin() ) {
			$item = &$this->ExportOptions->Add("email");
			$url = "";

			// $item->Body = "<button id=\"emf_view_purchases_outstandings\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_purchases_outstandings',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_purchases_outstandingslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
			// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

		if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") {
			$item->Body = "<a id=\"emf_view_purchases_outstandings\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\"  data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_purchases_outstandings',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_purchases_outstandingslist,sel:true});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		} else {
			$item->Body = "<a id=\"emf_view_purchases_outstandings\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\"  data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_purchases_outstandings',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_purchases_outstandingslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		}

		// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			$item->Visible = TRUE;
        }

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {

			// changed since v11.0.6
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012

		if ($this->ExportAll=="allpages") {

		// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();

		// Begin of changes, since v11.0.6
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}

		// End of changes, since v11.0.6
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<div class=\"alert alert-success ewSuccess\">" . $Language->Phrase("SendEmailSuccess") . "</div>"; // Set up success message
		} else {

			// Sent email failure
			return "<div class=\"alert alert-danger ewError\">" . $Email->SendErrDescription . "</div>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}
		$this->AddSearchQueryString($sQry, $this->Purchase_ID); // Purchase_ID
		$this->AddSearchQueryString($sQry, $this->Purchase_Number); // Purchase_Number
		$this->AddSearchQueryString($sQry, $this->Purchase_Date); // Purchase_Date
		$this->AddSearchQueryString($sQry, $this->Supplier_ID); // Supplier_ID
		$this->AddSearchQueryString($sQry, $this->Notes); // Notes
		$this->AddSearchQueryString($sQry, $this->Total_Amount); // Total_Amount
		$this->AddSearchQueryString($sQry, $this->Total_Payment); // Total_Payment
		$this->AddSearchQueryString($sQry, $this->Total_Balance); // Total_Balance

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1); // v11.0.4

		// $url = ew_CurrentUrl(); // <-- removed since v11.0.4
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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
		$this->ListOptions->UseDropDownButton = FALSE;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {
		$opt = &$this->ListOptions->Add("payment");
		$opt->Header = "Payment";
		$opt->OnLeft = FALSE; 
		$opt->MoveTo(5);
	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {
		if ($this->Total_Balance->CurrentValue > 0) {
			$this->ListOptions->Items["payment"]->Body = "<a href='a_payment_transactionsadd.php?Purchase_Number_Payment=".$this->Purchase_Number->CurrentValue."&Transaction_Type=purchase' title='Add new payment'><span data-phrase='PurchasePayment' class='icon-new ewIcon' data-caption=''>Pay Now!</span></a>";
		}
	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($view_purchases_outstandings_list)) $view_purchases_outstandings_list = new cview_purchases_outstandings_list();

// Page init
$view_purchases_outstandings_list->Page_Init();

// Page main
$view_purchases_outstandings_list->Page_Main();

// Begin of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
getCurrentPageTitle(ew_CurrentPage());

// End of modification Displaying Breadcrumb Links in All Pages, by Masino Sinaga, May 4, 2012
// Global Page Rendering event (in userfn*.php)

Page_Rendering();

// Global auto switch table width style (in userfn*.php), by Masino Sinaga, January 7, 2015
AutoSwitchTableWidthStyle();

// Page Rendering event
$view_purchases_outstandings_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fview_purchases_outstandingslist = new ew_Form("fview_purchases_outstandingslist", "list");
fview_purchases_outstandingslist.FormKeyCountName = '<?php echo $view_purchases_outstandings_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview_purchases_outstandingslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_purchases_outstandingslist.ValidateRequired = true;
<?php } else { ?>
fview_purchases_outstandingslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_purchases_outstandingslist.Lists["x_Supplier_ID"] = {"LinkField":"x_Supplier_Number","Ajax":true,"AutoFill":false,"DisplayFields":["x_Supplier_Name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fview_purchases_outstandingslistsrch = new ew_Form("fview_purchases_outstandingslistsrch");

// Init search panel as collapsed
<?php if (MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_COLLAPSED) { ?>
if (fview_purchases_outstandingslistsrch) fview_purchases_outstandingslistsrch.InitSearchPanel = true;
<?php } else { ?>
<?php if (MS_SEARCH_PANEL_COLLAPSED == TRUE && CurrentPage()->SearchWhere == "") { ?>
if (fview_purchases_outstandingslistsrch) fview_purchases_outstandingslistsrch.InitSearchPanel = true;
<?php } elseif ( (MS_SEARCH_PANEL_COLLAPSED == TRUE && CurrentPage()->SearchWhere <> "") || (MS_SEARCH_PANEL_COLLAPSED == FALSE && CurrentPage()->SearchWhere == "") ) { ?>
if (fview_purchases_outstandingslistsrch) fview_purchases_outstandingslistsrch.InitSearchPanel = false;
<?php } ?>
<?php } ?>
</script>
<script type="text/javascript" src="phpjs/ewscrolltable.min.js"></script>
<style type="text/css">
.ewTablePreviewRow { /* main table preview row color */
	background-color: #FFFFFF; /* preview row color */
}
.ewTablePreviewRow .ewGrid {
	display: table;
}
.ewTablePreviewRow .ewGrid .ewTable {
	width: auto;
}
</style>
<div id="ewPreview" class="hide"><ul class="nav nav-tabs"></ul><div class="tab-content"><div class="tab-pane fade"></div></div></div>
<script type="text/javascript" src="phpjs/ewpreview.min.js"></script>
<script type="text/javascript">
var EW_PREVIEW_PLACEMENT = EW_CSS_FLIP ? "left" : "right";
var EW_PREVIEW_SINGLE_ROW = false;
var EW_PREVIEW_OVERLAY = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<?php $bShowLangSelector = false; ?>
<div class="ewToolbar">
<?php if ($view_purchases_outstandings->Export == "") { ?>
<?php if (MS_SHOW_PHPMAKER_BREADCRUMBLINKS) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if (MS_SHOW_MASINO_BREADCRUMBLINKS) { ?>
<?php echo MasinoBreadcrumbLinks(); ?>
<?php } ?>
<?php } ?>
<?php if ($view_purchases_outstandings_list->TotalRecs > 0 && $view_purchases_outstandings_list->ExportOptions->Visible()) { ?>
<?php $view_purchases_outstandings_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($bShowLangSelector == false) { ?>
<?php if ($view_purchases_outstandings_list->SearchOptions->Visible()) { ?>
<?php $view_purchases_outstandings_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($view_purchases_outstandings_list->FilterOptions->Visible()) { ?>
<?php $view_purchases_outstandings_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<?php if (MS_LANGUAGE_SELECTOR_VISIBILITY=="belowheader") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php // movedown htmmaster session to htmheader session in template ?>
<?php
	$bSelectLimit = $view_purchases_outstandings_list->UseSelectLimit;
	if ($bSelectLimit) { // begin of v11.0.4
		if ($view_purchases_outstandings_list->TotalRecs <= 0)
			$view_purchases_outstandings_list->TotalRecs = $view_purchases_outstandings->SelectRecordCount();
	} else {
		if (!$view_purchases_outstandings_list->Recordset && ($view_purchases_outstandings_list->Recordset = $view_purchases_outstandings_list->LoadRecordset()))
			$view_purchases_outstandings_list->TotalRecs = $view_purchases_outstandings_list->Recordset->RecordCount();
	} // end of v11.0.4
	$view_purchases_outstandings_list->StartRec = 1;

// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012     
    if ($view_purchases_outstandings_list->DisplayRecs <= 0 || ($view_purchases_outstandings->Export <> "" && $view_purchases_outstandings->ExportAll=="allpages")) // Display all records
        $view_purchases_outstandings_list->DisplayRecs = $view_purchases_outstandings_list->TotalRecs;
    if (!($view_purchases_outstandings->Export <> "" && $view_purchases_outstandings->ExportAll=="allpages"))
        $view_purchases_outstandings_list->SetUpStartRec(); // Set up start record position

// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
	if ($bSelectLimit)
		$view_purchases_outstandings_list->Recordset = $view_purchases_outstandings_list->LoadRecordset($view_purchases_outstandings_list->StartRec-1, $view_purchases_outstandings_list->DisplayRecs);

	// Set no record found message
	if ($view_purchases_outstandings->CurrentAction == "" && $view_purchases_outstandings_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_purchases_outstandings_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_purchases_outstandings_list->SearchWhere == "0=101")
			$view_purchases_outstandings_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_purchases_outstandings_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view_purchases_outstandings_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view_purchases_outstandings->Export == "" && $view_purchases_outstandings->CurrentAction == "") { ?>
<form name="fview_purchases_outstandingslistsrch" id="fview_purchases_outstandingslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view_purchases_outstandings_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fview_purchases_outstandingslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_purchases_outstandings">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($view_purchases_outstandings_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($view_purchases_outstandings_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $view_purchases_outstandings_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($view_purchases_outstandings_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($view_purchases_outstandings_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($view_purchases_outstandings_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($view_purchases_outstandings_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $view_purchases_outstandings_list->ShowPageHeader(); ?>
<?php
$view_purchases_outstandings_list->ShowMessage();
?>
<?php //////////////////////////// BEGIN Empty Table ?>
<?php // Begin of modification Displaying Empty Table, by Masino Sinaga, May 3, 2012 ?>
<?php if (MS_SHOW_EMPTY_TABLE_ON_LIST_PAGE) { ?>
<?php if ($view_purchases_outstandings_list->TotalRecs == 0) { ?>
<div class="panel panel-default ewGrid">
<?php if (MS_PAGINATION_POSITION == 1 || MS_PAGINATION_POSITION == 3) { ?>
<div class="panel-heading ewGridUpperPanel" style="height: 40px;">
<?php if ($view_purchases_outstandings_list->TotalRecs == 0 && $view_purchases_outstandings->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_purchases_outstandings_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div class="clearfix"></div><div class="ewPager"></div>
</div>
<?php } ?>
<div id="gmp_view_purchases_outstandings_empty_table" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_view_purchases_outstandingslist" class="table ewTable">
<?php echo $view_purchases_outstandings->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($view_purchases_outstandings->Purchase_Number->Visible) { // Purchase_Number ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Number) == "") { ?>
		<th data-name="Purchase_Number"><div id="elh_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Purchase_Number"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Number) ?>',1);"><div id="elh_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Purchase_Number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Purchase_Number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Purchase_Date->Visible) { // Purchase_Date ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Date) == "") { ?>
		<th data-name="Purchase_Date"><div id="elh_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Purchase_Date"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Date) ?>',1);"><div id="elh_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Purchase_Date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Purchase_Date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Supplier_ID->Visible) { // Supplier_ID ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Supplier_ID) == "") { ?>
		<th data-name="Supplier_ID"><div id="elh_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Supplier_ID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Supplier_ID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Supplier_ID) ?>',1);"><div id="elh_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Supplier_ID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Supplier_ID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Supplier_ID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Notes->Visible) { // Notes ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Notes) == "") { ?>
		<th data-name="Notes"><div id="elh_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Notes->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Notes"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Notes) ?>',1);"><div id="elh_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Notes->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Notes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Notes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Total_Amount->Visible) { // Total_Amount ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Amount) == "") { ?>
		<th data-name="Total_Amount"><div id="elh_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Amount->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_Amount"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Amount) ?>',1);"><div id="elh_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Amount->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Total_Amount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Total_Amount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Total_Payment->Visible) { // Total_Payment ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Payment) == "") { ?>
		<th data-name="Total_Payment"><div id="elh_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $view_purchases_outstandings->Total_Payment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_Payment"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Payment) ?>',1);"><div id="elh_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Payment->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Total_Payment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Total_Payment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Total_Balance->Visible) { // Total_Balance ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Balance) == "") { ?>
		<th data-name="Total_Balance"><div id="elh_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $view_purchases_outstandings->Total_Balance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_Balance"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Balance) ?>',1);"><div id="elh_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Balance->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Total_Balance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Total_Balance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
	</tr>
</thead>
<tbody>
	<tr<?php echo $view_purchases_outstandings->RowAttributes() ?>>
	<?php if ($view_purchases_outstandings->Purchase_Number->Visible) { // Purchase_Number ?>
		<td data-name="Purchase_Number"<?php echo $view_purchases_outstandings->Purchase_Number->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number">
<span<?php echo $view_purchases_outstandings->Purchase_Number->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Purchase_Number->ListViewValue() ?></span>
</span>
<a id="<?php echo $view_purchases_outstandings_list->PageObjName . "_row_" . $view_purchases_outstandings_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Purchase_Date->Visible) { // Purchase_Date ?>
		<td data-name="Purchase_Date"<?php echo $view_purchases_outstandings->Purchase_Date->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date">
<span<?php echo $view_purchases_outstandings->Purchase_Date->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Purchase_Date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Supplier_ID->Visible) { // Supplier_ID ?>
		<td data-name="Supplier_ID"<?php echo $view_purchases_outstandings->Supplier_ID->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID">
<span<?php echo $view_purchases_outstandings->Supplier_ID->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Supplier_ID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Notes->Visible) { // Notes ?>
		<td data-name="Notes"<?php echo $view_purchases_outstandings->Notes->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes">
<span<?php echo $view_purchases_outstandings->Notes->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Notes->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Amount->Visible) { // Total_Amount ?>
		<td data-name="Total_Amount"<?php echo $view_purchases_outstandings->Total_Amount->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount">
<span<?php echo $view_purchases_outstandings->Total_Amount->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Total_Amount->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Payment->Visible) { // Total_Payment ?>
		<td data-name="Total_Payment"<?php echo $view_purchases_outstandings->Total_Payment->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment">
<span<?php echo $view_purchases_outstandings->Total_Payment->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Total_Payment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Balance->Visible) { // Total_Balance ?>
		<td data-name="Total_Balance"<?php echo $view_purchases_outstandings->Total_Balance->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance">
<span<?php echo $view_purchases_outstandings->Total_Balance->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Total_Balance->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	</tr>
</tbody>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
	<?php if ($view_purchases_outstandings->Purchase_Number->Visible) { // Purchase_Number ?>
		<td data-name="Purchase_Number"><span id="elf_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Purchase_Date->Visible) { // Purchase_Date ?>
		<td data-name="Purchase_Date"><span id="elf_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Supplier_ID->Visible) { // Supplier_ID ?>
		<td data-name="Supplier_ID"><span id="elf_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Notes->Visible) { // Notes ?>
		<td data-name="Notes"><span id="elf_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Amount->Visible) { // Total_Amount ?>
		<td data-name="Total_Amount"><span id="elf_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $view_purchases_outstandings->Total_Amount->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Payment->Visible) { // Total_Payment ?>
		<td data-name="Total_Payment"><span id="elf_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $view_purchases_outstandings->Total_Payment->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Balance->Visible) { // Total_Balance ?>
		<td data-name="Total_Balance"><span id="elf_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $view_purchases_outstandings->Total_Balance->ViewValue ?>
		</span></td>
	<?php } ?>
	</tr>
</tfoot>
</table>
</div>
<?php if (MS_PAGINATION_POSITION == 2 || MS_PAGINATION_POSITION == 3) { ?>
<div class="panel-footer ewGridLowerPanel" style="height: 40px;">
<?php if ($view_purchases_outstandings_list->TotalRecs == 0 && $view_purchases_outstandings->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_purchases_outstandings_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div class="clearfix"></div></div>
<?php } ?>
</div>
<?php } ?>
<?php } ?>
<?php // End of modification Displaying Empty Table, by Masino Sinaga, May 3, 2012 ?>
<?php //////////////////////////// END Empty Table ?>
<?php if ($view_purchases_outstandings_list->TotalRecs > 0 || $view_purchases_outstandings->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<?php // Begin of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012 ?>
<?php if ( (MS_PAGINATION_POSITION==1) || (MS_PAGINATION_POSITION==3) ) { ?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($view_purchases_outstandings->CurrentAction <> "gridadd" && $view_purchases_outstandings->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if ($view_purchases_outstandings_list->TotalRecs > 0) { ?>
<?php if ( (MS_SELECTABLE_PAGE_SIZES_POSITION=="Left" && $Language->Phrase("dir")!="rtl") || (MS_SELECTABLE_PAGE_SIZES_POSITION=="Left" && $Language->Phrase("dir")=="rtl") ) { ?>
<div class="ewPager"><span>&nbsp;<?php echo $Language->Phrase("RecordsPerPage") ?>&nbsp;</span>
<input type="hidden" name="t" value="view_purchases_outstandings">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<?php $sRecPerPageList = explode(',', MS_TABLE_SELECTABLE_REC_PER_PAGE_LIST); ?>
<?php
foreach ($sRecPerPageList as $a) {
 $thisDisplayRecs = $a;
 if ($thisDisplayRecs > 0 ) {
   $thisValue = $thisDisplayRecs;  
?>
<option value="<?php echo $thisDisplayRecs; ?>"<?php if ($view_purchases_outstandings_list->DisplayRecs == $thisValue) { ?> selected="selected"<?php } ?>><?php echo $thisDisplayRecs; ?></option>
<?php	} else { ?>
<option value="ALL"<?php if ($view_purchases_outstandings->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
<?php
		}
	}
?>
</select>
</div>
<?php } ?>
<?php } ?>
	<?php if (MS_PAGINATION_STYLE==1) { // link ?>
		<?php if (!isset($view_purchases_outstandings_list->Pager)) $view_purchases_outstandings_list->Pager = new cNumericPager($view_purchases_outstandings_list->StartRec, $view_purchases_outstandings_list->DisplayRecs, $view_purchases_outstandings_list->TotalRecs, $view_purchases_outstandings_list->RecRange) ?>
		<?php if ($view_purchases_outstandings_list->Pager->RecordCount > 0) { ?>
				<?php if (($view_purchases_outstandings_list->Pager->PageCount==1) && ($view_purchases_outstandings_list->Pager->CurrentPage == 1) && (MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE)  ) { ?>
				<?php } else { // MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE ?>
				<div class="ewPager">
				<div class="ewNumericPage"><ul class="pagination">
					<?php if ($view_purchases_outstandings_list->Pager->FirstButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
					<?php } else { // else of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
					<?php } // end of rtl ?>
					<?php } ?>
					<?php if ($view_purchases_outstandings_list->Pager->PrevButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
					<?php } else { // else of rtl { ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
					<?php } // end of rtl { ?>
					<?php } ?>
					<?php foreach ($view_purchases_outstandings_list->Pager->Items as $PagerItem) { ?>
						<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $view_purchases_outstandings_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
					<?php } ?>
					<?php if ($view_purchases_outstandings_list->Pager->NextButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
					<?php } else { // else of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
					<?php } // end of rtl ?>
					<?php } ?>
					<?php if ($view_purchases_outstandings_list->Pager->LastButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
					<?php } else { // else of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
					<?php } // end of rtl ?>
					<?php } ?>
				</ul></div>
				</div>
				<?php } // end MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE ?>
				<div class="ewPager ewRec">
					<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->RecordCount ?></span>
				</div>
		<?php } ?>	
	<?php } elseif (MS_PAGINATION_STYLE==2) { // button ?>
		<?php if (!isset($view_purchases_outstandings_list->Pager)) $view_purchases_outstandings_list->Pager = new cPrevNextPager($view_purchases_outstandings_list->StartRec, $view_purchases_outstandings_list->DisplayRecs, $view_purchases_outstandings_list->TotalRecs) ?>
		<?php if ($view_purchases_outstandings_list->Pager->RecordCount > 0) { ?>
				<?php if (($view_purchases_outstandings_list->Pager->PageCount==1) && ($view_purchases_outstandings_list->Pager->CurrentPage == 1) && (MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE)  ) { ?>
				<?php } else { // end MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE ?>
				<div class="ewPager">
				<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
				<div class="ewPrevNext"><div class="input-group">
				<div class="input-group-btn">
				<!--first page button-->
					<?php if ($view_purchases_outstandings_list->Pager->FirstButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><span class="icon-last ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-last ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				<!--previous page button-->
					<?php if ($view_purchases_outstandings_list->Pager->PrevButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><span class="icon-next ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-next ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				</div>
				<!--current page number-->
					<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_purchases_outstandings_list->Pager->CurrentPage ?>">
				<div class="input-group-btn">
				<!--next page button-->
					<?php if ($view_purchases_outstandings_list->Pager->NextButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				<!--last page button-->
					<?php if ($view_purchases_outstandings_list->Pager->LastButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><span class="icon-first ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-first ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				</div>
				</div>
				</div>
				<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->PageCount ?></span>
				</div>
				<?php } // end MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE ?>
				<div class="ewPager ewRec">
					<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->RecordCount ?></span>
				</div>
		<?php } ?>
	<?php } // end of link or button ?>	
<?php if ($view_purchases_outstandings_list->TotalRecs > 0) { ?>
<?php if ( (MS_SELECTABLE_PAGE_SIZES_POSITION=="Right" && $Language->Phrase("dir")!="rtl") || (MS_SELECTABLE_PAGE_SIZES_POSITION=="Right" && $Language->Phrase("dir")=="rtl") ) { ?>
<div class="ewPager"><span>&nbsp;<?php echo $Language->Phrase("RecordsPerPage") ?>&nbsp;</span>
<input type="hidden" name="t" value="view_purchases_outstandings">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="1"<?php if ($view_purchases_outstandings_list->DisplayRecs == 1) { ?> selected="selected"<?php } ?>>1</option>
<option value="3"<?php if ($view_purchases_outstandings_list->DisplayRecs == 3) { ?> selected="selected"<?php } ?>>3</option>
<option value="5"<?php if ($view_purchases_outstandings_list->DisplayRecs == 5) { ?> selected="selected"<?php } ?>>5</option>
<option value="10"<?php if ($view_purchases_outstandings_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($view_purchases_outstandings_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($view_purchases_outstandings_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($view_purchases_outstandings_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
</select>
</div>
<?php } // end if (MS_SELECTABLE_PAGE_SIZES_POSITION=="Right") ?>
<?php } // end TotalRecs ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_purchases_outstandings_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php // End of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012 ?>
<form name="fview_purchases_outstandingslist" id="fview_purchases_outstandingslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_purchases_outstandings_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_purchases_outstandings_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_purchases_outstandings">
<?php // Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012 ?>
<?php if (MS_EXPORT_RECORD_OPTIONS=="selectedrecords") { ?>
<input type="hidden" name="exporttype" id="exporttype" value="">
<?php } ?>
<?php // End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012 ?>
<div id="gmp_view_purchases_outstandings" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view_purchases_outstandings_list->TotalRecs > 0) { ?>
<table id="tbl_view_purchases_outstandingslist" class="table ewTable">
<?php echo $view_purchases_outstandings->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_purchases_outstandings_list->RowType = EW_ROWTYPE_HEADER; // since v11.0.6

// Render list options
$view_purchases_outstandings_list->RenderListOptions();

// Render list options (header, left)
$view_purchases_outstandings_list->ListOptions->Render("header", "left");
?>
<?php if ($view_purchases_outstandings->Purchase_Number->Visible) { // Purchase_Number ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Number) == "") { ?>
		<th data-name="Purchase_Number"><div id="elh_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Purchase_Number"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Number) ?>',1);"><div id="elh_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Purchase_Number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Purchase_Number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Purchase_Date->Visible) { // Purchase_Date ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Date) == "") { ?>
		<th data-name="Purchase_Date"><div id="elh_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Purchase_Date"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Purchase_Date) ?>',1);"><div id="elh_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Purchase_Date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Purchase_Date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Purchase_Date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Supplier_ID->Visible) { // Supplier_ID ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Supplier_ID) == "") { ?>
		<th data-name="Supplier_ID"><div id="elh_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Supplier_ID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Supplier_ID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Supplier_ID) ?>',1);"><div id="elh_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Supplier_ID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Supplier_ID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Supplier_ID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Notes->Visible) { // Notes ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Notes) == "") { ?>
		<th data-name="Notes"><div id="elh_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Notes->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Notes"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Notes) ?>',1);"><div id="elh_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Notes->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Notes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Notes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Total_Amount->Visible) { // Total_Amount ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Amount) == "") { ?>
		<th data-name="Total_Amount"><div id="elh_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount"><div class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Amount->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_Amount"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Amount) ?>',1);"><div id="elh_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Amount->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Total_Amount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Total_Amount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Total_Payment->Visible) { // Total_Payment ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Payment) == "") { ?>
		<th data-name="Total_Payment"><div id="elh_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $view_purchases_outstandings->Total_Payment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_Payment"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Payment) ?>',1);"><div id="elh_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Payment->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Total_Payment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Total_Payment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_purchases_outstandings->Total_Balance->Visible) { // Total_Balance ?>
	<?php if ($view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Balance) == "") { ?>
		<th data-name="Total_Balance"><div id="elh_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance"><div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $view_purchases_outstandings->Total_Balance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_Balance"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_purchases_outstandings->SortUrl($view_purchases_outstandings->Total_Balance) ?>',1);"><div id="elh_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance">
			<div class="ewTableHeaderBtn" style="white-space: nowrap;"><span class="ewTableHeaderCaption"><?php echo $view_purchases_outstandings->Total_Balance->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_purchases_outstandings->Total_Balance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_purchases_outstandings->Total_Balance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_purchases_outstandings_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php

// Begin of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
if ($view_purchases_outstandings->ExportAll=="allpages" && $view_purchases_outstandings->Export <> "") {
    $view_purchases_outstandings_list->StopRec = $view_purchases_outstandings_list->TotalRecs;

// End of mofidication Flexibility of Export Records Options, by Masino Sinaga, May 14, 2012
} else {

	// Set the last record to display
	if ($view_purchases_outstandings_list->TotalRecs > $view_purchases_outstandings_list->StartRec + $view_purchases_outstandings_list->DisplayRecs - 1)
		$view_purchases_outstandings_list->StopRec = $view_purchases_outstandings_list->StartRec + $view_purchases_outstandings_list->DisplayRecs - 1;
	else
		$view_purchases_outstandings_list->StopRec = $view_purchases_outstandings_list->TotalRecs;
}
$view_purchases_outstandings_list->RecCnt = $view_purchases_outstandings_list->StartRec - 1;
if ($view_purchases_outstandings_list->Recordset && !$view_purchases_outstandings_list->Recordset->EOF) {
	$view_purchases_outstandings_list->Recordset->MoveFirst();
	$bSelectLimit = $view_purchases_outstandings_list->UseSelectLimit;
	if (!$bSelectLimit && $view_purchases_outstandings_list->StartRec > 1)
		$view_purchases_outstandings_list->Recordset->Move($view_purchases_outstandings_list->StartRec - 1);
} elseif (!$view_purchases_outstandings->AllowAddDeleteRow && $view_purchases_outstandings_list->StopRec == 0) {
	$view_purchases_outstandings_list->StopRec = $view_purchases_outstandings->GridAddRowCount;
}

// Initialize aggregate
$view_purchases_outstandings->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_purchases_outstandings->ResetAttrs();
$view_purchases_outstandings_list->RenderRow();
while ($view_purchases_outstandings_list->RecCnt < $view_purchases_outstandings_list->StopRec) {
	$view_purchases_outstandings_list->RecCnt++;
	if (intval($view_purchases_outstandings_list->RecCnt) >= intval($view_purchases_outstandings_list->StartRec)) {
		$view_purchases_outstandings_list->RowCnt++;

		// Set up key count
		$view_purchases_outstandings_list->KeyCount = $view_purchases_outstandings_list->RowIndex;

		// Init row class and style
		$view_purchases_outstandings->ResetAttrs();
		$view_purchases_outstandings->CssClass = "";
		if ($view_purchases_outstandings->CurrentAction == "gridadd") {
		} else {
			$view_purchases_outstandings_list->LoadRowValues($view_purchases_outstandings_list->Recordset); // Load row values
		}
		$view_purchases_outstandings->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view_purchases_outstandings->RowAttrs = array_merge($view_purchases_outstandings->RowAttrs, array('data-rowindex'=>$view_purchases_outstandings_list->RowCnt, 'id'=>'r' . $view_purchases_outstandings_list->RowCnt . '_view_purchases_outstandings', 'data-rowtype'=>$view_purchases_outstandings->RowType));

		// Render row
		$view_purchases_outstandings_list->RenderRow();

		// Render list options
		$view_purchases_outstandings_list->RenderListOptions();
?>
	<tr<?php echo $view_purchases_outstandings->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_purchases_outstandings_list->ListOptions->Render("body", "left", $view_purchases_outstandings_list->RowCnt);
?>
	<?php if ($view_purchases_outstandings->Purchase_Number->Visible) { // Purchase_Number ?>
		<td data-name="Purchase_Number"<?php echo $view_purchases_outstandings->Purchase_Number->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number">
<span<?php echo $view_purchases_outstandings->Purchase_Number->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Purchase_Number->ListViewValue() ?></span>
</span>
<a id="<?php echo $view_purchases_outstandings_list->PageObjName . "_row_" . $view_purchases_outstandings_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Purchase_Date->Visible) { // Purchase_Date ?>
		<td data-name="Purchase_Date"<?php echo $view_purchases_outstandings->Purchase_Date->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date">
<span<?php echo $view_purchases_outstandings->Purchase_Date->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Purchase_Date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Supplier_ID->Visible) { // Supplier_ID ?>
		<td data-name="Supplier_ID"<?php echo $view_purchases_outstandings->Supplier_ID->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID">
<span<?php echo $view_purchases_outstandings->Supplier_ID->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Supplier_ID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Notes->Visible) { // Notes ?>
		<td data-name="Notes"<?php echo $view_purchases_outstandings->Notes->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes">
<span<?php echo $view_purchases_outstandings->Notes->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Notes->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Amount->Visible) { // Total_Amount ?>
		<td data-name="Total_Amount"<?php echo $view_purchases_outstandings->Total_Amount->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount">
<span<?php echo $view_purchases_outstandings->Total_Amount->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Total_Amount->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Payment->Visible) { // Total_Payment ?>
		<td data-name="Total_Payment"<?php echo $view_purchases_outstandings->Total_Payment->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment">
<span<?php echo $view_purchases_outstandings->Total_Payment->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Total_Payment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Balance->Visible) { // Total_Balance ?>
		<td data-name="Total_Balance"<?php echo $view_purchases_outstandings->Total_Balance->CellAttributes() ?>>
<span id="el<?php echo $view_purchases_outstandings_list->RowCnt ?>_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance">
<span<?php echo $view_purchases_outstandings->Total_Balance->ViewAttributes() ?>>
<?php echo $view_purchases_outstandings->Total_Balance->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_purchases_outstandings_list->ListOptions->Render("body", "right", $view_purchases_outstandings_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view_purchases_outstandings->CurrentAction <> "gridadd")
		$view_purchases_outstandings_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$view_purchases_outstandings->RowType = EW_ROWTYPE_AGGREGATE;
$view_purchases_outstandings->ResetAttrs();
$view_purchases_outstandings_list->RenderRow();
?>
<?php if ($view_purchases_outstandings_list->TotalRecs > 0 && ($view_purchases_outstandings->CurrentAction <> "gridadd" && $view_purchases_outstandings->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$view_purchases_outstandings_list->RenderListOptions();

// Render list options (footer, left)
$view_purchases_outstandings_list->ListOptions->Render("footer", "left");
?>
	<?php if ($view_purchases_outstandings->Purchase_Number->Visible) { // Purchase_Number ?>
		<td data-name="Purchase_Number"><span id="elf_view_purchases_outstandings_Purchase_Number" class="view_purchases_outstandings_Purchase_Number">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Purchase_Date->Visible) { // Purchase_Date ?>
		<td data-name="Purchase_Date"><span id="elf_view_purchases_outstandings_Purchase_Date" class="view_purchases_outstandings_Purchase_Date">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Supplier_ID->Visible) { // Supplier_ID ?>
		<td data-name="Supplier_ID"><span id="elf_view_purchases_outstandings_Supplier_ID" class="view_purchases_outstandings_Supplier_ID">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Notes->Visible) { // Notes ?>
		<td data-name="Notes"><span id="elf_view_purchases_outstandings_Notes" class="view_purchases_outstandings_Notes">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Amount->Visible) { // Total_Amount ?>
		<td data-name="Total_Amount"><span id="elf_view_purchases_outstandings_Total_Amount" class="view_purchases_outstandings_Total_Amount">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $view_purchases_outstandings->Total_Amount->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Payment->Visible) { // Total_Payment ?>
		<td data-name="Total_Payment"><span id="elf_view_purchases_outstandings_Total_Payment" class="view_purchases_outstandings_Total_Payment">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $view_purchases_outstandings->Total_Payment->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($view_purchases_outstandings->Total_Balance->Visible) { // Total_Balance ?>
		<td data-name="Total_Balance"><span id="elf_view_purchases_outstandings_Total_Balance" class="view_purchases_outstandings_Total_Balance">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?></span>
<?php echo $view_purchases_outstandings->Total_Balance->ViewValue ?>
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$view_purchases_outstandings_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($view_purchases_outstandings->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view_purchases_outstandings_list->Recordset)
	$view_purchases_outstandings_list->Recordset->Close();
?>
<?php // Begin of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012 ?>
<?php if ( (MS_PAGINATION_POSITION==2) || (MS_PAGINATION_POSITION==3) ) { ?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($view_purchases_outstandings->CurrentAction <> "gridadd" && $view_purchases_outstandings->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if ($view_purchases_outstandings_list->TotalRecs > 0) { ?>
<?php if ( (MS_SELECTABLE_PAGE_SIZES_POSITION=="Left" && $Language->Phrase("dir")!="rtl") || (MS_SELECTABLE_PAGE_SIZES_POSITION=="Left" && $Language->Phrase("dir")=="rtl") ) { ?>
<div class="ewPager"><span>&nbsp;<?php echo $Language->Phrase("RecordsPerPage") ?>&nbsp;</span>
<input type="hidden" name="t" value="view_purchases_outstandings">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<?php $sRecPerPageList = explode(',', MS_TABLE_SELECTABLE_REC_PER_PAGE_LIST); ?>
<?php
foreach ($sRecPerPageList as $a) {
 $thisDisplayRecs = $a;
 if ($thisDisplayRecs > 0 ) {
   $thisValue = $thisDisplayRecs;  
?>
<option value="<?php echo $thisDisplayRecs; ?>"<?php if ($view_purchases_outstandings_list->DisplayRecs == $thisValue) { ?> selected="selected"<?php } ?>><?php echo $thisDisplayRecs; ?></option>
<?php	} else { ?>
<option value="ALL"<?php if ($view_purchases_outstandings->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
<?php
		}
	}
?>
</select>
</div>
<?php } ?>
<?php } ?>
	<?php if (MS_PAGINATION_STYLE==1) { // link ?>
		<?php if (!isset($view_purchases_outstandings_list->Pager)) $view_purchases_outstandings_list->Pager = new cNumericPager($view_purchases_outstandings_list->StartRec, $view_purchases_outstandings_list->DisplayRecs, $view_purchases_outstandings_list->TotalRecs, $view_purchases_outstandings_list->RecRange) ?>
		<?php if ($view_purchases_outstandings_list->Pager->RecordCount > 0) { ?>
				<?php if (($view_purchases_outstandings_list->Pager->PageCount==1) && ($view_purchases_outstandings_list->Pager->CurrentPage == 1) && (MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE)  ) { ?>
				<?php } else { // MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE ?>
				<div class="ewPager">
				<div class="ewNumericPage"><ul class="pagination">
					<?php if ($view_purchases_outstandings_list->Pager->FirstButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
					<?php } else { // else of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
					<?php } // end of rtl ?>
					<?php } ?>
					<?php if ($view_purchases_outstandings_list->Pager->PrevButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
					<?php } else { // else of rtl { ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
					<?php } // end of rtl { ?>
					<?php } ?>
					<?php foreach ($view_purchases_outstandings_list->Pager->Items as $PagerItem) { ?>
						<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $view_purchases_outstandings_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
					<?php } ?>
					<?php if ($view_purchases_outstandings_list->Pager->NextButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
					<?php } else { // else of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
					<?php } // end of rtl ?>
					<?php } ?>
					<?php if ($view_purchases_outstandings_list->Pager->LastButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
					<?php } else { // else of rtl ?>
					<li><a href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
					<?php } // end of rtl ?>
					<?php } ?>
				</ul></div>
				</div>
				<?php } // end MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE ?>
				<div class="ewPager ewRec">
					<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->RecordCount ?></span>
				</div>
		<?php } ?>	
	<?php } elseif (MS_PAGINATION_STYLE==2) { // button ?>
		<?php if (!isset($view_purchases_outstandings_list->Pager)) $view_purchases_outstandings_list->Pager = new cPrevNextPager($view_purchases_outstandings_list->StartRec, $view_purchases_outstandings_list->DisplayRecs, $view_purchases_outstandings_list->TotalRecs) ?>
		<?php if ($view_purchases_outstandings_list->Pager->RecordCount > 0) { ?>
				<?php if (($view_purchases_outstandings_list->Pager->PageCount==1) && ($view_purchases_outstandings_list->Pager->CurrentPage == 1) && (MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE)  ) { ?>
				<?php } else { // end MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE ?>
				<div class="ewPager">
				<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
				<div class="ewPrevNext"><div class="input-group">
				<div class="input-group-btn">
				<!--first page button-->
					<?php if ($view_purchases_outstandings_list->Pager->FirstButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><span class="icon-last ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-last ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				<!--previous page button-->
					<?php if ($view_purchases_outstandings_list->Pager->PrevButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><span class="icon-next ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-next ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				</div>
				<!--current page number-->
					<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_purchases_outstandings_list->Pager->CurrentPage ?>">
				<div class="input-group-btn">
				<!--next page button-->
					<?php if ($view_purchases_outstandings_list->Pager->NextButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-prev ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				<!--last page button-->
					<?php if ($view_purchases_outstandings_list->Pager->LastButton->Enabled) { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><span class="icon-first ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_purchases_outstandings_list->PageUrl() ?>start=<?php echo $view_purchases_outstandings_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } else { ?>
					<?php if ($Language->Phrase("dir") == "rtl") { // begin of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-first ewIcon"></span></a>
					<?php } else { // else of rtl ?>
					<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
					<?php } // end of rtl ?>
					<?php } ?>
				</div>
				</div>
				</div>
				<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->PageCount ?></span>
				</div>
				<?php } // end MS_SHOW_PAGENUM_IF_REC_NOT_OVER_PAGESIZE==FALSE ?>
				<div class="ewPager ewRec">
					<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_purchases_outstandings_list->Pager->RecordCount ?></span>
				</div>
		<?php } ?>
	<?php } // end of link or button ?>	
<?php if ($view_purchases_outstandings_list->TotalRecs > 0) { ?>
<?php if ( (MS_SELECTABLE_PAGE_SIZES_POSITION=="Right" && $Language->Phrase("dir")!="rtl") || (MS_SELECTABLE_PAGE_SIZES_POSITION=="Right" && $Language->Phrase("dir")=="rtl") ) { ?>
<div class="ewPager"><span>&nbsp;<?php echo $Language->Phrase("RecordsPerPage") ?>&nbsp;</span>
<input type="hidden" name="t" value="view_purchases_outstandings">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="1"<?php if ($view_purchases_outstandings_list->DisplayRecs == 1) { ?> selected="selected"<?php } ?>>1</option>
<option value="3"<?php if ($view_purchases_outstandings_list->DisplayRecs == 3) { ?> selected="selected"<?php } ?>>3</option>
<option value="5"<?php if ($view_purchases_outstandings_list->DisplayRecs == 5) { ?> selected="selected"<?php } ?>>5</option>
<option value="10"<?php if ($view_purchases_outstandings_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($view_purchases_outstandings_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($view_purchases_outstandings_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($view_purchases_outstandings_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
</select>
</div>
<?php } // end if (MS_SELECTABLE_PAGE_SIZES_POSITION=="Right") ?>
<?php } // end TotalRecs ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_purchases_outstandings_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php // End of modification Customize Navigation/Pager Panel, by Masino Sinaga, May 2, 2012 ?>
</div>
<?php } ?>
<?php if (MS_SHOW_EMPTY_TABLE_ON_LIST_PAGE==FALSE) { ?>
<?php if ($view_purchases_outstandings_list->TotalRecs == 0 && $view_purchases_outstandings->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_purchases_outstandings_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php } // MS_SHOW_EMPTY_TABLE_ON_LIST_PAGE is false ?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<script type="text/javascript">
fview_purchases_outstandingslistsrch.Init();
fview_purchases_outstandingslistsrch.FilterList = <?php echo $view_purchases_outstandings_list->GetFilterList() ?>;
fview_purchases_outstandingslist.Init();
</script>
<?php } ?>
<?php
$view_purchases_outstandings_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<script type="text/javascript">
$(document).ready(function() {
	$("td:has(.ewAggregate)").css({"text-align": "right", "font-weight": "bold"}).find(".ewAggregate").hide();	
});
$(document).on("preview", function(e, args) {
	var $tabpane = args.$tabpane;
	$tabpane.find("td:has(.ewAggregate)").css({"text-align": "right", "font-weight": "bold"}).find(".ewAggregate").hide();
});
</script>
<?php if (MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_COLLAPSED) { ?>
<?php if (isset($_SESSION['table_view_purchases_outstandings_views']) && $_SESSION['table_view_purchases_outstandings_views'] == 1) { ?>
	<?php if (CurrentPage()->SearchPanelCollapsed==FALSE) { ?>
<script type="text/javascript">
$(document).ready(function() {
	var SearchToggle = $('.ewSearchToggle'); var SearchPanel = $('.ewSearchPanel');
	SearchPanel.addClass('in'); SearchToggle.addClass('active');
});
</script>
	<?php } elseif (CurrentPage()->SearchPanelCollapsed==TRUE) { ?>
<script type="text/javascript">
$(document).ready(function() {
	var SearchToggle = $('.ewSearchToggle'); var SearchPanel = $('.ewSearchPanel');
	SearchPanel.removeClass('in'); SearchToggle.removeClass('active');
});
</script>	
	<?php } ?>
<?php } else { ?>
<?php if (MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_STATUS==TRUE && MS_USE_PHPMAKER_SETTING_FOR_INITIATE_SEARCH_PANEL==TRUE) { ?>
<script type="text/javascript">
$(document).ready(function() { var SearchToggle = $('.ewSearchToggle'); var SearchPanel = $('.ewSearchPanel'); if(getCookie('view_purchases_outstandings_searchpanel')=="active"){ SearchToggle.addClass(getCookie('view_purchases_outstandings_searchpanel')); SearchPanel.addClass('in'); SearchToggle.addClass('active'); }else{ SearchPanel.removeClass('in'); SearchToggle.removeClass('active'); } SearchToggle.on('click',function(event) { event.preventDefault(); if (SearchToggle.hasClass('active')){ createCookie("view_purchases_outstandings_searchpanel", "notactive", 1); }else{ createCookie("view_purchases_outstandings_searchpanel", "active", 1); } }); });
</script>
<?php } elseif (MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_STATUS==TRUE && MS_USE_PHPMAKER_SETTING_FOR_INITIATE_SEARCH_PANEL==FALSE) { ?>
<script type="text/javascript">
$(document).ready(function() { var SearchToggle = $('.ewSearchToggle'); var SearchPanel = $('.ewSearchPanel'); if(getCookie('view_purchases_outstandings_searchpanel')=="active"){ SearchToggle.addClass(getCookie('view_purchases_outstandings_searchpanel')); SearchPanel.addClass('in'); SearchToggle.addClass('active'); }else{ SearchPanel.removeClass('in'); SearchToggle.removeClass('active'); } SearchToggle.on('click',function(event) { event.preventDefault(); if (SearchToggle.hasClass('active')){ createCookie("view_purchases_outstandings_searchpanel", "notactive", 1); }else{ createCookie("view_purchases_outstandings_searchpanel", "active", 1); } }); });
</script>
<?php } ?>
<?php } ?>
<?php } else { // end of MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_COLLAPSED ?>
<?php if (MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_STATUS==TRUE && MS_USE_PHPMAKER_SETTING_FOR_INITIATE_SEARCH_PANEL==TRUE) { ?>
	<?php if (isset($_SESSION['table_view_purchases_outstandings_views']) && $_SESSION['table_view_purchases_outstandings_views'] == 1) { ?>
<script type="text/javascript">
$(document).ready(function() { var SearchToggle = $('.ewSearchToggle'); var SearchPanel = $('.ewSearchPanel'); if(getCookie('view_purchases_outstandings_searchpanel')=="active"){ SearchToggle.addClass(getCookie('view_purchases_outstandings_searchpanel')); SearchPanel.addClass('in'); SearchToggle.addClass('active'); }else{ SearchPanel.removeClass('in'); SearchToggle.removeClass('active'); } SearchToggle.on('click',function(event) { event.preventDefault(); if (SearchToggle.hasClass('active')){ createCookie("view_purchases_outstandings_searchpanel", "notactive", 1); }else{ createCookie("view_purchases_outstandings_searchpanel", "active", 1); } }); });
</script>
	<?php } ?>
<?php } elseif (MS_USE_TABLE_SETTING_FOR_SEARCH_PANEL_STATUS==TRUE && MS_USE_PHPMAKER_SETTING_FOR_INITIATE_SEARCH_PANEL==FALSE) { ?>
<script type="text/javascript">
$(document).ready(function() { var SearchToggle = $('.ewSearchToggle'); var SearchPanel = $('.ewSearchPanel'); if(getCookie('view_purchases_outstandings_searchpanel')=="active"){ SearchToggle.addClass(getCookie('view_purchases_outstandings_searchpanel')); SearchPanel.addClass('in'); SearchToggle.addClass('active'); }else{ SearchPanel.removeClass('in'); SearchToggle.removeClass('active'); } SearchToggle.on('click',function(event) { event.preventDefault(); if (SearchToggle.hasClass('active')){ createCookie("view_purchases_outstandings_searchpanel", "notactive", 1); }else{ createCookie("view_purchases_outstandings_searchpanel", "active", 1); } }); });
</script>
<?php } ?>
<?php } ?>
<?php if (@CurrentPage()->ListOptions->UseDropDownButton == TRUE) { ?>
<?php if (MS_USE_TABLE_SETTING_FOR_DROPUP_LISTOPTIONS == TRUE) { ?>
<script type="text/javascript">
$(document).ready(function() {
	var reccount = <?php echo CurrentPage()->RowCnt; ?>;
	var rowdropup = 4;
	if (reccount > 6) {
		for ( var i = 0; i <= (rowdropup - 1); i++ ) {
			$('#r' + (reccount - i) + '_<?php echo CurrentPage()->TableName; ?> .ewButtonDropdown').addClass('dropup');
		}
	}
});
</script>
<?php } ?>
<?php } ?>
<?php if ($view_purchases_outstandings->Export == "") { ?>
<script type="text/javascript">
$('.ewGridSave, .ewGridInsert').attr('onclick', 'return alertifySaveGrid(this)'); function alertifySaveGrid(obj) { <?php global $Language; ?> if (fview_purchases_outstandingslist.Validate() == true ) { alertify.confirm("<?php echo $Language->Phrase('AlertifySaveGridConfirm'); ?>", function (e) { if (e) { $(window).unbind('beforeunload'); alertify.success("<?php echo $Language->Phrase('AlertifySaveGrid'); ?>"); $("#fview_purchases_outstandingslist").submit(); } }).set("title", "<?php echo $Language->Phrase('AlertifyConfirm'); ?>").set("defaultFocus", "cancel").set('oncancel', function(closeEvent){ alertify.error('<?php echo $Language->Phrase('AlertifyCancel'); ?>');}).set('labels', {ok:'<?php echo $Language->Phrase("MyOKMessage"); ?>!', cancel:'<?php echo $Language->Phrase("MyCancelMessage"); ?>'}); } return false; }
</script>
<script type="text/javascript">
$('.ewInlineUpdate').attr('onclick', 'return alertifySaveInlineEdit(this)'); function alertifySaveInlineEdit(obj) { <?php global $Language; ?> if (fview_purchases_outstandingslist.Validate() == true ) { alertify.confirm("<?php echo $Language->Phrase('AlertifySaveGridConfirm'); ?>", function (e) { if (e) { $(window).unbind('beforeunload'); alertify.success("<?php echo $Language->Phrase('AlertifySaveGrid'); ?>"); $("#fview_purchases_outstandingslist").submit(); } }).set("title", "<?php echo $Language->Phrase('AlertifyConfirm'); ?>").set("defaultFocus", "cancel").set('oncancel', function(closeEvent){ alertify.error('<?php echo $Language->Phrase('AlertifyCancel'); ?>');}).set('labels', {ok:'<?php echo $Language->Phrase("MyOKMessage"); ?>!', cancel:'<?php echo $Language->Phrase("MyCancelMessage"); ?>'}); } return false; }
</script>
<script type="text/javascript">
$('.ewInlineInsert').attr('onclick', 'return alertifySaveInlineInsert(this)'); function alertifySaveInlineInsert(obj) { <?php global $Language; ?> if (fview_purchases_outstandingslist.Validate() == true ) { alertify.confirm("<?php echo $Language->Phrase('AlertifySaveGridConfirm'); ?>", function (e) { if (e) { $(window).unbind('beforeunload'); alertify.success("<?php echo $Language->Phrase('AlertifySaveGrid'); ?>"); $("#fview_purchases_outstandingslist").submit(); } }).set("title", "<?php echo $Language->Phrase('AlertifyConfirm'); ?>").set("defaultFocus", "cancel").set('oncancel', function(closeEvent){ alertify.error('<?php echo $Language->Phrase('AlertifyCancel'); ?>');}).set('labels', {ok:'<?php echo $Language->Phrase("MyOKMessage"); ?>!', cancel:'<?php echo $Language->Phrase("MyCancelMessage"); ?>'}); } return false; }
</script>
<?php } ?>
<?php if ($view_purchases_outstandings->CurrentAction == "" || $view_purchases_outstandings->Export == "") { // Change && become || in order to add scroll table in Grid, by Masino Sinaga, August 3, 2014 ?>
<script type="text/javascript">
<?php if (MS_TABLE_WIDTH_STYLE==1) { // Begin of modification Optimizing Main Table Width to Maximum Width of Site, by Masino Sinaga, April 30, 2012 ?>
<?php $iWidthAdjustment = (MS_MENU_HORIZONTAL) ? 0 : 100; ?>
ew_ScrollableTable("gmp_view_purchases_outstandings", "<?php echo (MS_SCROLL_TABLE_WIDTH - $iWidthAdjustment); ?>px", "<?php echo MS_SCROLL_TABLE_HEIGHT; ?>px");
ew_ScrollableTable("gmp_view_purchases_outstandings_empty_table", "<?php echo (MS_SCROLL_TABLE_WIDTH - $iWidthAdjustment); ?>px", "<?php echo MS_SCROLL_TABLE_HEIGHT; ?>px");
<?php } elseif (MS_TABLE_WIDTH_STYLE==2) { ?>
ew_ScrollableTable("gmp_view_purchases_outstandings", "<?php echo MS_SCROLL_TABLE_WIDTH; ?>px", "<?php echo MS_SCROLL_TABLE_HEIGHT; ?>px");
ew_ScrollableTable("gmp_view_purchases_outstandings_empty_table", "<?php echo MS_SCROLL_TABLE_WIDTH; ?>px", "<?php echo MS_SCROLL_TABLE_HEIGHT; ?>px");
<?php } elseif (MS_TABLE_WIDTH_STYLE==3) { ?>
ew_ScrollableTable("gmp_view_purchases_outstandings", "100%", "<?php echo MS_SCROLL_TABLE_HEIGHT; ?>px");
ew_ScrollableTable("gmp_view_purchases_outstandings_empty_table", "100%", "<?php echo MS_SCROLL_TABLE_HEIGHT; ?>px");
<?php } // End of modification Optimizing Main Table Width to Maximum Width of Site, by Masino Sinaga, April 30, 2012 ?>
<?php } ?>
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view_purchases_outstandings_list->Page_Terminate();
?>
