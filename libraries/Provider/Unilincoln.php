<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Lincoln OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

class OAuth2_Provider_Unilincoln extends OAuth2_Provider
{
	/**
	 * @var  string  default scope (useful if a scope is required for user info)
	 */
	protected $scope = array('user.basic');
	
	/**
	 * @var  string  the method to use when requesting tokens
	 */
	protected $method = 'POST';

	public function url_authorize()
	{
		return 'https://sso.lincoln.ac.uk/oauth';
	}

	public function url_access_token()
	{
		return 'https://sso.lincoln.ac.uk/oauth/access_token';
	}	
	
	//Gets the user info using the people API
	public function get_user_info(OAuth2_Token_Access $token)
	{
		$url = 'https://nucleus-proxy.online.lincoln.ac.uk/v1/people/user?' . http_build_query(array(
			'access_token' => $token->access_token
		));

		if ( ! $fetch = file_get_contents($url))
		{
			throw new OAuth2_Exception('Failed to fetch the user\'s details from Nucleus');
		}

		else
		{
			$user = json_decode($fetch);

			if ($user === NULL)
			{
				throw new OAuth2_Exception('The JSON from Nucleus is invalid');
			}

			//Checks to see if an error has occured.
			if ($user->error === TRUE)
			{
				//If so simply returns the error message of the problem
				throw new OAuth2_Exception($user->message);		
			}
			else
			{
				//Otherwise returns the information like normal.
				return $user->results[0];	
			}	
		}			
	}
}
