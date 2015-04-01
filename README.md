# PHPMoves, a PHP library for the Moves App API based on PyMoves


## Example Usage

	$m = new PHPMoves\Moves('client_id','client_secret','redirect_url');

Get a request token URL:

	$request_url = $m->requestURL();

Open the Moves app and enter the PIN, then you will be redirected the url specified in for the app. The next step is to use the code to get an access token and a refresh token:
Returns: Associative array with 'access_token' and 'refresh_token' 
	$tokens = $m->auth($authorization_code);

Validate an access token, returns false if revoked:

	$valid = $m->validate_token($tokens['access_token']);

If you have an access token you can make requests like:

	$profile = $m->get_profile($access_token);

Refresh an access token returns an associative array with  'access_token' and 'refresh_token' this invalidate your old Access Token and Refresh Token

	$tokens = $->refresh($refresh_token['refresh_token']);

This will fetch all user info. Other requests are also built in, 
	
    $summary = $m->get_range($access_token,'/user/summary/daily', $start, $end)
    $activities = $m->get_range($access_token,'/user/activities/daily', $start, $end)
    $places = $m->get_range($access_token,'/user/places/daily', $start, $end)
    $storyline = $m->get_range($access_token,'/user/storyline/daily', $start, $end)
    
Note: ` $start ` and ` $end ` need to be a date in the format ` yyyyMMdd ` or ` yyyy-MM-dd ` 
also beware of the range requests as they have a limit of 7 days.
