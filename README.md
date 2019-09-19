# Moves ended its service on 31st July 2018. This project is no longer of any use and will be archived. 







# PHPMoves, a PHP library for the Moves App API based on PyMoves

## Workflow

Create a new instance of the PHPMoves class using the client_id and client_secret provided by moves
```php
$m = new PHPMoves\Moves('client_id','client_secret','redirect_url');
```
Generate a request URL and present it to the user.
```php
$request_url = $m->requestURL();
<a href="<?php echo $request_url; ?>">Click Here</a>
```
Once the user has authenticated successfully they will be redirected back to the redirect_url  with an authorization code included in the request. This code is passed to the auth method to obtain an access_token and  refresh_token. The access token will be used for all future API calles. The refresh_token is used to request a new access_token if the current token becomes invalid or expires.
```php
$request_token = $_GET['code'];
$tokens = $m->auth($request_token);
$access_token = $tokens['access_token'];
$refresh_token = $tokens['refresh_token'];
```
The access_token can now be used to make API. 
```php
echo json_encode($m->get_profile($access_token));
```
## Class Methods

##### requestURL()
Generates a URL for the move API authentication page.

##### validate_token($access_token)
Checks if an access_token is valid returns false if the token has been expired or revoked.

##### auth($authorization_code)
Exchanges an authorization code for an access token and refresh token. Returns an associative array containing both.

##### refresh($refresh_token)
Refreshes the access token and refresh token also expires both old tokens. Returns an associative array containing the updated tokens.

##### get_profile($access_token)
Returns the users moves profile as an array.

##### get_range($access_token, $endpoint, $start, $end, $otherParameters = array())


Used to fetch  API data between two date ranges ` $start ` and ` $end ` need to be a date in the format ` yyyyMMdd ` or ` yyyy-MM-dd `  the maximum request size is 7 days. Returns an array, see examples for usage.
```php	
$summary = $m->get_range($access_token,'/user/summary/daily', $start, $end)
$activities = $m->get_range($access_token,'/user/activities/daily', $start, $end)
$places = $m->get_range($access_token,'/user/places/daily', $start, $end)
$storyline = $m->get_range($access_token,'/user/storyline/daily', $start, $end)
```


