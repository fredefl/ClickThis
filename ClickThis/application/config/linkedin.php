<?php
	/**
	 * The LinkedIn secret api key
	 * @since 1.0
	 */
	$config['linkedin_api_secret'] = 'FGZEmiLBywqn7Bu4';

	/**
	 * This is the LinkedIn api key
	 * @since 1.0
	 */
	$config['linkedin_api_key'] = 'pssrue14vzty';

	/**
	 * This is the LinkedIn base url
	 * @deprecated This is deprecated as of the new auth library
	 * @since 1.0
	 */
	$config['linkedin_base_url'] = 'http://illution.dk/ClickThis/login/linkedin/';

	/**
	 * The LinkedIn callback url
	 * @deprecated This is deprecated, as of the new auth library
	 * @since 1.0
	 */
	$config['linkedin_callback_url'] = 'http://illution.dk/ClickThis/login/linkedin/callback';

	/**
	 * An array of available LinkedIn fields
	 * @since 1.0
	 */
	$config["linkedin_fields"] = array(
		"id",
		"first-name",
		"last-name",
		"maiden-name",
		"formatted-name",
		"phonetic-first-name",
		"phonetic-last-name",
		"formatted-phonetic-name",
		"headline",
		"location:(name)",
		"location:(country:(code))",
		"location:(country:(code),name)",
		"industry",
		"distance",
		"relation-to-viewer:(distance)",
		"last-modified-timestamp",
		"current-share",
		"network",
		"connections",
		"num-connections",
		"num-connections-capped",
		"summary",
		"specialties",
		"proposal-comments",
		"associations",
		"honors",
		"interests",
		"positions",
		"publications",
		"patents",
		"languages",
		"skills",
		"certifications",
		"educations",
		"courses",
		"volunteer",
		"three-current-positions",
		"three-past-positions",
		"num-recommenders",
		"recommendations-received",
		"phone-numbers",
		"im-accounts",
		"twitter-accounts",
		"primary-twitter-account",
		"bound-account-types",
		"mfeed-rss-url",
		"following",
		"job-bookmarks",
		"group-memberships",
		"suggestions",
		"date-of-birth",
		"main-address",
		"member-url-resources",
		"member-url-resources:(url)",
		"member-url-resources:(name)",
		"member-url-resources:(name,url)",
		"picture-url",
		"site-standard-profile-request",
		"api-standard-profile-request:(url)",
		"api-standard-profile-request:(headers)",
		"api-standard-profile-request:(headers,url)",
		"public-profile-url",
		"related-profile-views"
	);