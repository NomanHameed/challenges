<?php

namespace App\Services;

class FitbitConnectionService
{
	public function getToken($request_url, $client_id, $client_secret, $code, $redirect_uri)
	{
		// base64 encode the client_id and client_secret
		$auth = base64_encode("{$client_id}:{$client_secret}");

		// urlencode the redirect_url
		$redirect_uri = urlencode($redirect_uri);

		$request_url .= "?client_id={$client_id}&grant_type=authorization_code&redirect_uri={$redirect_uri}&code={$code}";

		// Set the headers
		$headers = [
			"Authorization: Basic {$auth}",
			"Content-Type: application/x-www-form-urlencoded",
		];

		return $this->curlPostRequest($headers, $request_url);

	}

	public function refreshToken($request_url, $client_id, $client_secret, $refresh_token)
	{
		// base64 encode the client_id and client_secret
		$auth = base64_encode("{$client_id}:{$client_secret}");
		$request_url .= "?grant_type=refresh_token&refresh_token={$refresh_token}";

		// Set the headers
		$headers = [
			"Authorization: Basic {$auth}",
			"Content-Type: application/x-www-form-urlencoded",
		];

		return $this->curlPostRequest($headers, $request_url);

	}

	public function revokeToken($request_url, $client_id, $client_secret, $token)
	{
		// base64 encode the client_id and client_secret
		$auth = base64_encode("{$client_id}:{$client_secret}");
		$request_url .= "?client_id={$client_id}&token={$token}";

		// Set the headers
		$headers = [
			"Authorization: Basic {$auth}",
			"Content-Type: application/x-www-form-urlencoded",
		];

		return $this->curlPostRequest($headers, $request_url);
	}

	public function createSubscription($request_url, $access_token)
	{
		$headers = [
		   "Accept: application/json",
		   "Authorization: Bearer {$access_token}",
		];

		return $this->curlPostRequest($headers, $request_url);
	}

	public function deleteSubscription($request_url, $access_token)
	{
		$headers = [
			"Accept: application/json",
			"Authorization: Bearer {$access_token}",
		];

		return $this->curlDeleteRequest($headers, $request_url);
	}

	public function getActivityLogList($request_url, $access_token)
	{
		$headers = [
			"Accept: application/json",
			"Authorization: Bearer {$access_token}",
		];

		return $this->curlGetRequest($headers, $request_url);
	}

	public function getActivityTCX($request_url, $access_token)
	{
		$headers = [
			"Accept: application/json",
			"Authorization: Bearer {$access_token}",
		];

		return $this->curlGetRequest($headers, $request_url);
	}

	public function curlGetRequest($headers, $request_url)
	{
		$ch = curl_init();
		// Options (see: http://php.net/manual/en/function.curl-setopt.php)
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		//for debug only!
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Set headers
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$response = curl_exec($ch);

		// Throw an exception if there was an error with curl
		if($response === false){
			throw new \Exception(curl_error($ch), curl_errno($ch));
		}

		// Get the body of the response
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$responseBody = substr($response, $header_size);
		// Close curl session
		curl_close($ch);

		// Return response body
		return $responseBody;
	}

	public function curlPostRequest($headers, $request_url)
	{
		$ch = curl_init();
		// Options (see: http://php.net/manual/en/function.curl-setopt.php)
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_POST, 1);

		//for debug only!
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Set headers
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$response = curl_exec($ch);

		// Throw an exception if there was an error with curl
		if($response === false){
			throw new \Exception(curl_error($ch), curl_errno($ch));
		}

		// Get the body of the response
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$responseBody = substr($response, $header_size);
		// Close curl session
		curl_close($ch);

		// Return response body
		return $responseBody;
	}

	public function curlDeleteRequest($headers, $request_url)
	{
		$ch = curl_init();
		// Options (see: http://php.net/manual/en/function.curl-setopt.php)
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		//for debug only!
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Set headers
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$response = curl_exec($ch);

		// Throw an exception if there was an error with curl
		if($response === false){
			throw new \Exception(curl_error($ch), curl_errno($ch));
		}

		// Get the body of the response
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$responseBody = substr($response, $header_size);
		// Close curl session
		curl_close($ch);

		// Return response body
		return $responseBody;
	}
}