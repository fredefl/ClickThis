<?php
    session_start();

    $config['base_url']             =   'http://illution.dk/LinkedInLogin/auth.php';
    $config['callback_url']         =   'http://illution.dk/LinkedInLogin/demo.php';
    $config['linkedin_access']      =   'pssrue14vzty';
    $config['linkedin_secret']      =   'FGZEmiLBywqn7Bu4';

    include_once "linkedin.php";

    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
    //$linkedin->debug = true;

    # Now we retrieve a request token. It will be set as $linkedin->request_token
    $linkedin->getRequestToken();
    $_SESSION['requestToken'] = serialize($linkedin->request_token);
  
    # With a request token in hand, we can generate an authorization URL, which we'll direct the user to
    //echo "Authorization URL: " . $linkedin->generateAuthorizeUrl() . "\n\n";
    header("Location: " . $linkedin->generateAuthorizeUrl());
?>
