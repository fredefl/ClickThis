/**
 * This variable contains a list of all providers
 * @type {Object}
 */
var providerList;
/**
 * This variable is filled with all the providers as an object when the document is ready
 * @type {Object}
 */
var providers;
/**
 * This variable holds the keyword that will be put infront of the standard page names/div names
 * @type {String}
 */
var pageKeyword = "page_p";
/**
 * This keyword will be put infront of the name of each user generated page
 * @type {String}
 */
var userPageKeyword = "user_p";
/**
 * This variable is set by the page changer function
 * @type {Number}
 */
var currentPage = 0;
/**
 * The variable will be set with the content of the users localStorage key "userProviders" 
 * @type Object}
 */
var userProviders;
/**
 * This variable is used in the arrow system options are (".default",".page",".user",".edit",".edit-chose")
 * @type {String}
 */
var pageChangeType = '.page';
/**
 * This variable set how many providers there will be shown per page
 * @type {Number}
 */
var numberPerPage = 6;
/**
 * This variable sets how many providers there will be shown per row
 * @type {Number}
 */
var numberPerRow = 3;
/**
 * This variable stores the last disabled page
 * @type {String}
 */
var oldPage;
/**
 * This variable is used in the edit box the re-create what it changed
 * @type {String}
 */
var oldPageChangeType;
/**
 * This variable stores the state of the page, true means that the page is changing
 * @type {Boolean}
 */
var changeing = false;
/**
 * Set this to false if you dont wan't the left arrow on the first page to send the user to the last page
 * @type {Boolean}
 */
var scrollToEnd = true;
/**
 * An object storing the standard providers
 * @type {Object}
 */
var standardProviders;
/**
 * If is true then edit mode is enabled
 * @type {Boolean}
 */
var editMode = false;
/**
 * If this is true then the left and right button will be available
 * @type {Boolean}
 */
var navigationOnDisabled = true;
/**
 * If this is set to true both standard and user providers,
 * will be shown else only user providers will be shown if user providers exists
 * @type {Boolean}
 */
var showStandardProvidersIfUSerProviders = false;

/**
 * This is the localStorage key for user providers
 * @type {String}
 */
var userProviderKey = "userProviders";

/**
 * The element of the current loginSwipe page
 * @type {object}
 */
var currentPageElement;